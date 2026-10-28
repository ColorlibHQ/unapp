/** Native block validity, real responsive layouts, preview controls and POST forms. */
const assert = require( 'node:assert/strict' );
const fs = require( 'node:fs' );
const path = require( 'node:path' );
const { chromium } = require( process.env.PLAYWRIGHT_MODULE || 'playwright' );
const directory = process.env.UNAPP_SWAP_PREVIEW_DIR;
if ( ! directory ) throw new Error( 'Set UNAPP_SWAP_PREVIEW_DIR to the section-swap fixture directory.' );
const config = JSON.parse( fs.readFileSync( path.join( directory, 'paths.json' ) ) );
const captures = process.env.UNAPP_SWAP_SCREENSHOTS;
if ( captures ) fs.mkdirSync( captures, { recursive: true } );
( async () => {
	const browser = await chromium.launch( { headless: true, ...( process.env.CHROME_PATH ? { executablePath: process.env.CHROME_PATH } : {} ) } );
	let checks = 0;
	let submitted;
	try {
		const context = await browser.newContext();
		await context.route( '**/*', route => {
			const request = route.request();
			const url = new URL( request.url() );
			if ( request.method() === 'POST' || ( url.pathname.endsWith( '/themes.php' ) && url.searchParams.has( 'swap' ) ) ) {
				submitted = request.method() === 'POST' ? new URLSearchParams( request.postData() ) : url.searchParams;
				return route.fulfill( { body: 'Captured native form.', contentType: 'text/html' } );
			}
			let file;
			if ( url.hostname === 'swap-fixture.test' ) file = path.join( directory, url.pathname );
			else if ( url.searchParams.get( 'action' ) === 'unapp_section_preview' ) file = path.join( directory, `hero-${ url.searchParams.get( 'preset' ) }.html` );
			else if ( request.url().startsWith( config.theme_url + '/' ) ) file = path.join( config.theme_path, decodeURIComponent( url.pathname.slice( new URL( config.theme_url ).pathname.length ) ) );
			else if ( request.url().startsWith( config.site_url + '/wp-includes/' ) || request.url().startsWith( config.site_url + '/wp-admin/' ) ) file = path.join( config.wp_path, decodeURIComponent( url.pathname ) );
			if ( file && fs.existsSync( file ) && fs.statSync( file ).isFile() ) return route.fulfill( { path: file, headers: { 'access-control-allow-origin': '*' } } );
			return route.abort();
		} );
		const editor = await context.newPage();
		await editor.goto( 'http://swap-fixture.test/editor.html' );
		const validation = await editor.evaluate( variants => {
			const invalid = [];
			let count = 0;
			function walk( blocks, key ) { for ( const block of blocks ) { ++count; if ( ! block.isValid ) invalid.push( { key, name: block.name, issues: block.validationIssues } ); walk( block.innerBlocks, key ); } }
			for ( const [ key, markup ] of Object.entries( variants ) ) walk( wp.blocks.parse( markup ), key );
			return { count, invalid };
		}, JSON.parse( fs.readFileSync( path.join( directory, 'blocks.json' ) ) ) );
		assert.deepEqual( validation.invalid, [], 'Every compatible swap stays valid in the native WordPress editor' );
		console.log( `Valid swapped-section blocks: ${ validation.count }` );
		await editor.close();
		const variants = [ 'hero-image-left', 'hero-image-right', 'hero-stacked', 'services-grid-1', 'services-grid-2', 'services-grid-3', 'cta-side-by-side', 'cta-stacked' ];
		for ( const width of [ 1280, 768, 390, 320 ] ) {
			const page = await context.newPage();
			await page.setViewportSize( { width, height: 1000 } );
			for ( const variant of variants ) {
				await page.goto( `http://swap-fixture.test/${ variant }.html` );
				await page.locator( 'img[src]:not([src=""])' ).evaluateAll( images => Promise.all( images.map( image => { image.loading = 'eager'; return image.decode(); } ) ) ).catch( async error => { throw new Error( `${ variant } at ${ width }px: ${ error.message }; ${ JSON.stringify( await page.locator( 'img[src]:not([src=""])' ).evaluateAll( images => images.map( image => ( { src: image.currentSrc || image.src, complete: image.complete, width: image.naturalWidth } ) ) ) ) }` ); } );
				assert.ok( await page.evaluate( () => document.documentElement.scrollWidth <= innerWidth ), `${ variant } fits ${ width }px` );
				assert.equal( await page.locator( 'h1' ).count(), variant.startsWith( 'hero' ) ? 1 : 0 );
				assert.equal( await page.locator( 'a[href^="mailto:"], a[href^="tel:"]' ).count(), 0, 'Preview actions are inert' );
				if ( variant.startsWith( 'hero' ) ) {
					const positions = await page.evaluate( () => {
						const heading = document.querySelector( 'h1' ).getBoundingClientRect();
						const image = document.querySelector( 'img' ).getBoundingClientRect();
						return { heading: { x: heading.x, y: heading.y }, image: { x: image.x, y: image.y } };
					} );
					if ( width > 781 && variant !== 'hero-stacked' ) assert.ok( variant === 'hero-image-left' ? positions.image.x < positions.heading.x : positions.image.x > positions.heading.x, 'Hero columns have the requested visual and DOM order' );
					else if ( variant === 'hero-stacked' || variant === 'hero-image-right' ) assert.ok( positions.image.y > positions.heading.y, 'Stacked heroes keep copy before the image' );
				} else if ( variant.startsWith( 'services' ) ) {
					const expected = width <= 739 || variant === 'services-grid-1' ? 1 : Number( variant.slice( -1 ) );
					assert.equal( await page.locator( '.unapp-section-grid' ).evaluate( grid => getComputedStyle( grid ).gridTemplateColumns.split( ' ' ).length ), expected, 'Service grids use the requested count and collapse on phones' );
				} else if ( variant === 'cta-stacked' ) {
					assert.equal( await page.locator( '.is-style-unapp-section-stack' ).evaluate( columns => getComputedStyle( columns ).flexDirection ), 'column' );
				}
				if ( captures && ( width === 1280 || width === 390 ) ) await page.screenshot( { path: path.join( captures, `${ variant }-${ width }.png` ), fullPage: true } );
				checks += 4;
			}
			await page.goto( 'http://swap-fixture.test/screen.html' );
			const frame = page.frameLocator( 'iframe[title="Your section layout preview"]' );
			await frame.locator( 'h1' ).waitFor();
			assert.ok( await page.evaluate( () => document.documentElement.scrollWidth <= innerWidth ), `Swap controls fit ${ width }px` );
			await page.getByLabel( 'Mobile', { exact: true } ).check();
			await page.locator( 'iframe[title="Your section layout preview"]' ).scrollIntoViewIfNeeded();
			await page.frames().find( item => item.url().includes( 'action=unapp_section_preview' ) ).waitForFunction( () => innerWidth === 390 );
			assert.equal( await frame.locator( 'body' ).evaluate( () => new Promise( resolve => requestAnimationFrame( () => resolve( innerWidth ) ) ) ), 390 );
			await page.getByLabel( 'Tablet', { exact: true } ).check();
			await page.frames().find( item => item.url().includes( 'action=unapp_section_preview' ) ).waitForFunction( () => innerWidth === 768 );
			assert.equal( await frame.locator( 'body' ).evaluate( () => new Promise( resolve => requestAnimationFrame( () => resolve( innerWidth ) ) ) ), 768 );
			await page.getByLabel( 'Mobile', { exact: true } ).check();
			await page.frames().find( item => item.url().includes( 'action=unapp_section_preview' ) ).waitForFunction( () => innerWidth === 390 );
			assert.ok( await page.locator( '.unapp-design__canvas' ).evaluate( canvas => ( () => { const frame = canvas.querySelector( 'iframe' ).getBoundingClientRect(); const box = canvas.getBoundingClientRect(); return frame.left >= box.left - 2 && frame.right <= box.right + 2; } )() ), 'Scaled preview stays inside its canvas at every width' );
			if ( captures ) await page.screenshot( { path: path.join( captures, `controls-${ width }.png` ), fullPage: true } );
			await page.getByRole( 'button', { name: 'Apply this layout', exact: true } ).focus();
			await Promise.all( [ page.waitForURL( '**/wp-admin/admin-post.php' ), page.keyboard.press( 'Enter' ) ] );
			assert.equal( submitted.get( 'action' ), 'unapp_section_swap' );
			assert.equal( submitted.get( 'page_id' ), String( config.page ) );
			assert.equal( submitted.get( 'preset' ), 'image-left' );
			assert.match( submitted.get( 'snapshot' ), /^[a-f0-9]{64}$/ );
			assert.ok( submitted.get( '_wpnonce' ) );
			checks += 8;
			await page.goto( 'http://swap-fixture.test/screen.html' );
			await page.getByLabel( 'Section layout', { exact: true } ).selectOption( 'stacked' );
			await page.getByRole( 'button', { name: 'Preview layout', exact: true } ).focus();
			await Promise.all( [ page.waitForURL( '**/wp-admin/themes.php?**' ), page.keyboard.press( 'Enter' ) ] );
			assert.equal( submitted.get( 'swap' ), 'stacked' );
			assert.equal( submitted.get( 'section' ), 'coach-hero' );
			checks += 2;
			await page.goto( 'http://swap-fixture.test/screen.html' );
			await page.getByRole( 'button', { name: 'Undo last section swap', exact: true } ).focus();
			await Promise.all( [ page.waitForURL( '**/wp-admin/admin-post.php' ), page.keyboard.press( 'Enter' ) ] );
			assert.equal( submitted.get( 'operation' ), 'undo' );
			assert.ok( submitted.get( 'record' ) && submitted.get( '_wpnonce' ) );
			checks += 2;
			await page.close();
		}
		const noScript = await browser.newContext( { javaScriptEnabled: false } );
		await noScript.route( '**/*', route => {
			const url = new URL( route.request().url() );
			if ( url.hostname === 'swap-fixture.test' ) return route.fulfill( { path: path.join( directory, url.pathname ) } );
			return route.abort();
		} );
		const plain = await noScript.newPage();
		await plain.goto( 'http://swap-fixture.test/screen.html' );
		assert.ok( await plain.getByRole( 'button', { name: 'Apply this layout', exact: true } ).isVisible(), 'Native preview/apply forms remain available without JavaScript' );
		++checks;
		await noScript.close();
		console.log( `Section-swap browser checks passed: ${ checks }` );
	} finally { await browser.close(); }
} )().catch( error => { console.error( error ); process.exitCode = 1; } );
