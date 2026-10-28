/** Offline checks against actual WordPress finishing markup and core blocks. */
const assert = require( 'node:assert/strict' );
const fs = require( 'node:fs' );
const path = require( 'node:path' );
const { chromium } = require( process.env.PLAYWRIGHT_MODULE || 'playwright' );
const directory = process.env.UNAPP_PERSONALIZED_PREVIEW_DIR;
if ( ! directory ) throw new Error( 'Set UNAPP_PERSONALIZED_PREVIEW_DIR to an import.php fixture directory.' );
const config = JSON.parse( fs.readFileSync( path.join( directory, 'paths.json' ) ) );
const captures = process.env.UNAPP_PERSONALIZED_SCREENSHOTS;
if ( captures ) fs.mkdirSync( captures, { recursive: true } );
( async () => {
	const browser = await chromium.launch( { headless: true, ...( process.env.CHROME_PATH ? { executablePath: process.env.CHROME_PATH } : {} ) } );
	let checks = 0;
	let submitted;
	let previewPayload;
	try {
		const context = await browser.newContext();
		await context.route( '**/*', route => {
			const request = route.request();
			const url = new URL( request.url() );
			if ( request.method() === 'POST' ) {
				submitted = new URLSearchParams( request.postData() );
				if ( submitted.get( 'action' ) === 'unapp_personalized_preview' ) { previewPayload = submitted; return route.fulfill( { path: path.join( directory, 'home.html' ), contentType: 'text/html' } ); }
				return route.fulfill( { body: '<p>Captured native form.</p>', contentType: 'text/html' } );
			}
			let file;
			if ( request.url() === config.logo_url ) file = path.join( config.wp_path, 'wp-includes/images/w-logo-blue-white-bg.png' );
			else if ( url.hostname === 'personalized-fixture.test' ) file = path.join( directory, url.pathname );
			else if ( request.url().startsWith( config.theme_url + '/' ) ) file = path.join( config.theme_path, decodeURIComponent( url.pathname.slice( new URL( config.theme_url ).pathname.length ) ) );
			else if ( request.url().startsWith( config.site_url + '/wp-includes/' ) || request.url().startsWith( config.site_url + '/wp-admin/' ) ) file = path.join( config.wp_path, decodeURIComponent( url.pathname ) );
			if ( file && fs.existsSync( file ) && fs.statSync( file ).isFile() ) return route.fulfill( { path: file, headers: { 'access-control-allow-origin': '*' } } );
			return route.abort();
		} );
		const editor = await context.newPage();
		await editor.goto( 'http://personalized-fixture.test/editor.html' );
		const validation = await editor.evaluate( patterns => {
			const invalid = [];
			let count = 0;
			function walk( blocks ) { for ( const block of blocks ) { ++count; if ( ! block.isValid ) invalid.push( { name: block.name, issues: block.validationIssues } ); walk( block.innerBlocks ); } }
			for ( const markup of Object.values( patterns ) ) walk( wp.blocks.parse( markup ) );
			return { invalid, count };
		}, JSON.parse( fs.readFileSync( path.join( directory, 'blocks.json' ) ) ) );
		assert.deepEqual( validation.invalid, [], 'Main actions remain valid native WordPress blocks' );
		console.log( `Valid personalized-preview blocks: ${ validation.count }` );
		await editor.close();
		const variation = JSON.parse( fs.readFileSync( path.join( directory, 'variation.json' ) ) );
		const primary = variation.settings.color.palette.find( item => item.slug === 'primary' ).color;
		const headingFont = variation.settings.typography.fontFamilies.find( item => item.slug === 'heading' ).fontFamily;
		for ( const width of [ 1280, 768, 390, 320 ] ) {
			const page = await context.newPage();
			await page.setViewportSize( { width, height: 900 } );
			await page.goto( 'http://personalized-fixture.test/screen.html' );
			const frame = page.frameLocator( 'iframe[name="unapp-personalized-preview"]' );
			await frame.getByRole( 'heading', { name: 'Less chasing. More shipping.', exact: true } ).waitFor();
			assert.equal( previewPayload.get( 'main_action_type' ), 'book' );
			assert.equal( previewPayload.get( 'main_action_label' ), 'Book a visit' );
			assert.equal( previewPayload.get( 'site_title' ), 'Harbor & Co.' );
			assert.deepEqual( previewPayload.getAll( 'sections[]' ), [ 'features-zigzag' ] );
			assert.ok( previewPayload.get( '_wpnonce' ) );
			assert.ok( await page.evaluate( () => document.documentElement.scrollWidth <= innerWidth ), `Personalized setup fits ${ width }px` );
			assert.ok( await frame.getByText( 'Thoughtful products for busy teams.', { exact: true } ).count() >= 1 );
			assert.equal( await frame.locator( 'img.custom-logo' ).count(), 1 );
			assert.equal( await frame.locator( 'a[data-unapp-preview-destination^="https://booking.test/"]' ).count(), 3 );
			assert.equal( await frame.locator( '#pricing' ).count(), 0 );
			assert.equal( await frame.locator( 'html' ).evaluate( node => getComputedStyle( node ).getPropertyValue( '--wp--preset--color--primary' ).trim() ), primary );
			assert.equal( await frame.locator( 'html' ).evaluate( node => getComputedStyle( node ).getPropertyValue( '--wp--preset--font-family--heading' ).trim() ), headingFont );
			assert.equal( await frame.locator( 'a[data-unapp-preview-destination^="https://booking.test/"]:not([inert])' ).count(), 0 );
			checks += 13;
			for ( const [ device, expected ] of [ [ 'tablet', 768 ], [ 'mobile', 390 ], [ 'desktop', 1024 ] ] ) {
				await page.locator( `input[name="setup_preview_width"][value="${ device }"]` ).check();
				await page.waitForFunction( ( { selector, expected } ) => parseInt( document.querySelector( selector ).style.width, 10 ) >= expected, { selector: 'iframe', expected } );
				const actual = await frame.locator( 'html' ).evaluate( () => innerWidth );
				assert.equal( actual, expected );
				assert.ok( await frame.locator( 'html' ).evaluate( () => document.documentElement.scrollWidth <= innerWidth ), `${ device } content fits at ${ width }px admin width` );
				checks += 2;
			}
			await page.getByRole( 'radio', { name: 'Mobile', exact: true } ).focus();
			await page.keyboard.press( 'Space' );
			assert.ok( await page.getByRole( 'radio', { name: 'Mobile', exact: true } ).isChecked() );
			++checks;
			if ( captures ) await page.screenshot( { path: path.join( captures, `personalized-${ width }.png` ), fullPage: true } );
			const build = page.locator( 'form:has(input[value="unapp_wizard_build"])' );
			const payload = await build.evaluate( form => Array.from( new FormData( form ).entries() ) );
			for ( const [ key, value ] of previewPayload ) {
				if ( [ 'action', '_wpnonce', '_wp_http_referer' ].includes( key ) ) continue;
				assert.ok( payload.some( item => item[ 0 ] === key && item[ 1 ].replace( /\r\n/g, '\n' ) === value.replace( /\r\n/g, '\n' ) ), `Build retains preview field ${ key }` );
			}
			await page.getByRole( 'button', { name: 'Build my site', exact: true } ).focus();
			await Promise.all( [ page.waitForURL( '**/wp-admin/admin-post.php' ), page.keyboard.press( 'Enter' ) ] );
			assert.equal( submitted.get( 'action' ), 'unapp_wizard_build' );
			assert.equal( submitted.get( 'main_action_url' ), previewPayload.get( 'main_action_url' ) );
			checks += 2;
			await page.close();
		}
		const noScript = await browser.newContext( { javaScriptEnabled: false } );
		await noScript.route( '**/*', route => {
			if ( route.request().method() === 'POST' ) return route.fulfill( { path: path.join( directory, 'home.html' ), contentType: 'text/html' } );
			if ( route.request().url().endsWith( 'screen.html' ) ) return route.fulfill( { path: path.join( directory, 'screen.html' ), contentType: 'text/html' } );
			return route.abort();
		} );
		const fallback = await noScript.newPage();
		await fallback.goto( 'http://personalized-fixture.test/screen.html' );
		await fallback.getByRole( 'button', { name: 'Reload personalized preview', exact: true } ).click();
		await fallback.frameLocator( 'iframe' ).getByText( 'Thoughtful products for busy teams.', { exact: true } ).first().waitFor();
		++checks;
		await noScript.close();
		console.log( `Personalized-preview browser checks passed: ${ checks }` );
	} finally { await browser.close(); }
} )().catch( error => { console.error( error ); process.exitCode = 1; } );
