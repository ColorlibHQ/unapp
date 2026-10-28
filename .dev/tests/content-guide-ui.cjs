/** Offline section-picker, guide and native editor-store checks. */
const assert = require( 'node:assert/strict' );
const fs = require( 'node:fs' );
const path = require( 'node:path' );
const { chromium } = require( process.env.PLAYWRIGHT_MODULE || 'playwright' );
const directory = process.env.UNAPP_CONTENT_PREVIEW_DIR;
if ( ! directory ) throw new Error( 'Set UNAPP_CONTENT_PREVIEW_DIR to an import.php fixture directory.' );
const config = JSON.parse( fs.readFileSync( path.join( directory, 'paths.json' ) ) );
const captures = process.env.UNAPP_CONTENT_SCREENSHOTS;
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
			if ( request.method() === 'POST' || url.searchParams.get( 'step' ) === 'plugins' ) {
				submitted = request.method() === 'POST' ? new URLSearchParams( request.postData() ) : url.searchParams;
				return route.fulfill( { body: '<p>Captured native form.</p>', contentType: 'text/html' } );
			}
			let file;
			if ( url.hostname === 'content-fixture.test' ) file = path.join( directory, url.pathname );
			else if ( request.url().startsWith( config.theme_url + '/' ) ) file = path.join( config.theme_path, decodeURIComponent( url.pathname.slice( new URL( config.theme_url ).pathname.length ) ) );
			else if ( request.url().startsWith( config.site_url + '/wp-includes/' ) || request.url().startsWith( config.site_url + '/wp-admin/' ) ) file = path.join( config.wp_path, decodeURIComponent( url.pathname ) );
			if ( file && fs.existsSync( file ) && fs.statSync( file ).isFile() ) return route.fulfill( { path: file, headers: { 'access-control-allow-origin': '*' } } );
			return route.abort();
		} );
		const editor = await context.newPage();
		await editor.goto( 'http://content-fixture.test/editor.html' );
		const validation = await editor.evaluate( patterns => {
			const invalid = [];
			let count = 0;
			function walk( blocks ) { for ( const block of blocks ) { ++count; if ( ! block.isValid ) invalid.push( { name: block.name, issues: block.validationIssues } ); walk( block.innerBlocks ); } }
			for ( const markup of Object.values( patterns ) ) walk( wp.blocks.parse( markup ) );
			return { invalid, count };
		}, JSON.parse( fs.readFileSync( path.join( directory, 'blocks.json' ) ) ) );
		assert.deepEqual( validation.invalid, [], 'Main actions remain valid native WordPress blocks' );
		console.log( `Valid selected-section blocks: ${ validation.count }` );
		const focus = JSON.parse( fs.readFileSync( path.join( directory, 'focus.json' ) ) );
		const markup = JSON.parse( fs.readFileSync( path.join( directory, 'blocks.json' ) ) )[ 'focus-home' ];
		for ( const section of Object.values( focus.sections ) ) {
			await editor.evaluate( ( { markup, section } ) => {
				wp.data.dispatch( 'core/block-editor' ).resetBlocks( wp.blocks.parse( markup ) );
				window.unappContentGuide = { section: section.id, root: section.root };
			}, { markup, section } );
			await editor.addScriptTag( { path: path.join( config.theme_path, 'assets/js/content-guide.js' ) } );
			const selected = await editor.evaluate( root => {
				const store = wp.data.select( 'core/block-editor' );
				const selected = store.getSelectedBlockClientId();
				return selected && [ selected, ...store.getBlockParents( selected ) ].includes( store.getBlocks()[ root ].clientId );
			}, section.root );
			assert.ok( selected, `Native editor selects section ${ section.id } without changing it` );
			++checks;
		}
		const imageSection = Object.values( focus.sections ).find( section => section.images );
		await editor.evaluate( ( { markup, section } ) => {
			wp.data.dispatch( 'core/block-editor' ).resetBlocks( wp.blocks.parse( markup ) );
			window.unappContentGuide = { section: section.id, root: section.root, focus: 'image' };
		}, { markup, section: imageSection } );
		const beforeFocus = await editor.evaluate( () => wp.blocks.serialize( wp.data.select( 'core/block-editor' ).getBlocks() ) );
		await editor.addScriptTag( { path: path.join( config.theme_path, 'assets/js/content-guide.js' ) } );
		assert.equal( await editor.evaluate( () => wp.data.select( 'core/block-editor' ).getSelectedBlock().name ), 'core/image' );
		assert.equal( await editor.evaluate( () => wp.blocks.serialize( wp.data.select( 'core/block-editor' ).getBlocks() ) ), beforeFocus, 'Native focus never changes saved block content' );
		checks += 2;
		await editor.evaluate( markup => {
			const blocks = wp.blocks.parse( markup );
			for ( const block of blocks ) { if ( block.attributes.metadata ) delete block.attributes.metadata.unappSection; }
			wp.data.dispatch( 'core/block-editor' ).resetBlocks( blocks );
			window.unappContentGuide = { section: 'legacy-section', root: 1 };
		}, markup );
		await editor.addScriptTag( { path: path.join( config.theme_path, 'assets/js/content-guide.js' ) } );
		assert.ok( await editor.evaluate( () => {
			const store = wp.data.select( 'core/block-editor' );
			const selected = store.getSelectedBlockClientId();
			return [ selected, ...store.getBlockParents( selected ) ].includes( store.getBlocks()[ 1 ].clientId );
		} ), 'Older sections without metadata focus through their current root position' );
		++checks;
		const supportFocus = JSON.parse( fs.readFileSync( path.join( directory, 'focus-supporting.json' ) ) );
		const supportMarkup = JSON.parse( fs.readFileSync( path.join( directory, 'blocks.json' ) ) )[ 'focus-supporting' ];
		for ( const section of Object.values( supportFocus.sections ) ) {
			for ( const image of section.image_items ) {
				await editor.evaluate( ( { markup, section, image } ) => {
					wp.data.dispatch( 'core/block-editor' ).resetBlocks( wp.blocks.parse( markup ) );
					window.unappContentGuide = { section: section.id, root: section.root, focus: 'image', imagePath: image.path };
				}, { markup: supportMarkup, section, image } );
				const before = await editor.evaluate( () => wp.blocks.serialize( wp.data.select( 'core/block-editor' ).getBlocks() ) );
				await editor.addScriptTag( { path: path.join( config.theme_path, 'assets/js/content-guide.js' ) } );
				const selected = await editor.evaluate( () => {
					const block = wp.data.select( 'core/block-editor' ).getSelectedBlock();
					return { name: block.name, url: block.attributes.url };
				} );
				assert.equal( selected.name, image.cover ? 'core/cover' : 'core/image', 'Select the exact native image type' );
				assert.equal( selected.url, image.src, 'Every gallery image and Cover selects its own source' );
				assert.equal( await editor.evaluate( () => wp.blocks.serialize( wp.data.select( 'core/block-editor' ).getBlocks() ) ), before, 'Image selection does not change page content' );
				checks += 3;
			}
		}
		await editor.close();
		for ( const width of [ 1280, 768, 390, 320 ] ) {
			const page = await context.newPage();
			await page.setViewportSize( { width, height: 900 } );
			await page.goto( 'http://content-fixture.test/guide.html' );
			assert.equal( await page.evaluate( () => innerWidth ), width );
			assert.ok( await page.evaluate( () => document.documentElement.scrollWidth <= innerWidth ), `Guide fits ${ width }px` );
			assert.ok( await page.getByRole( 'heading', { name: 'Guided content pass', exact: true } ).isVisible() );
			assert.match( await page.getByRole( 'link', { name: 'Edit this section in WordPress', exact: true } ).getAttribute( 'href' ), /post\.php\?post=\d+&action=edit&unapp_section=/ );
			assert.equal( await page.locator( '[aria-current="step"]' ).count(), 1 );
			assert.match( await page.getByRole( 'link', { name: 'Edit section images', exact: true } ).getAttribute( 'href' ), /unapp_focus=image/ );
			if ( captures ) await page.screenshot( { path: path.join( captures, `guide-${ width }.png` ), fullPage: true } );
			await page.getByRole( 'button', { name: 'Reviewed, continue', exact: true } ).focus();
			await Promise.all( [ page.waitForURL( '**/wp-admin/admin-post.php' ), page.keyboard.press( 'Enter' ) ] );
			assert.equal( submitted.get( 'action' ), 'unapp_content_review' );
			assert.match( submitted.get( 'snapshot' ), /^[a-f0-9]{64}$/ );
			assert.ok( submitted.get( '_wpnonce' ) && submitted.get( 'section' ) && submitted.get( 'page_id' ) );
			checks += 9;
			await page.goto( 'http://content-fixture.test/supporting.html' );
			assert.equal( await page.getByRole( 'navigation', { name: 'Starter pages', exact: true } ).getByRole( 'link' ).count(), 5 );
			assert.equal( await page.locator( '[aria-current="page"]' ).count(), 1 );
			await page.getByText( 'Replace images in this section', { exact: true } ).click();
			const replacements = page.getByRole( 'link', { name: /^Replace image \d+ in WordPress$/ } );
			assert.equal( await replacements.count(), 3 );
			assert.equal( await page.getByText( 'Bundled demo image.', { exact: false } ).count(), 3 );
			const urls = await replacements.evaluateAll( links => links.map( link => link.href ) );
			assert.equal( new Set( urls ).size, 3, 'Each replacement has its own destination' );
			for ( const url of urls ) {
				assert.equal( new URL( url ).searchParams.get( 'post' ), String( supportFocus.page ) );
				assert.ok( new URL( url ).searchParams.has( 'unapp_image' ) );
			}
			assert.ok( await page.evaluate( () => document.documentElement.scrollWidth <= innerWidth ), `Image cards fit ${ width }px` );
			await page.locator( '.unapp-guide-images img' ).evaluateAll( images => Promise.all( images.map( image => { image.loading = 'eager'; return image.decode(); } ) ) );
			assert.ok( await page.locator( '.unapp-guide-images img' ).evaluateAll( images => images.every( image => image.complete && image.naturalWidth > 0 ) ), 'Local photo thumbnails load' );
			if ( captures ) await page.screenshot( { path: path.join( captures, `supporting-${ width }.png` ), fullPage: true } );
			await page.getByRole( 'button', { name: 'Reviewed, continue', exact: true } ).focus();
			await Promise.all( [ page.waitForURL( '**/wp-admin/admin-post.php' ), page.keyboard.press( 'Enter' ) ] );
			assert.equal( submitted.get( 'page_id' ), String( supportFocus.page ), 'Review submission is scoped to the selected supporting page' );
			checks += 14;
			await page.goto( 'http://content-fixture.test/picker.html' );
			await page.getByText( 'Choose homepage sections (optional)', { exact: true } ).click();
			assert.ok( await page.locator( 'input[value="pricing"]' ).isChecked() );
			await page.locator( 'input[value="pricing"]' ).uncheck();
			await page.locator( 'input[value="testimonials"]' ).uncheck();
			assert.ok( await page.evaluate( () => document.documentElement.scrollWidth <= innerWidth ), `Section picker fits ${ width }px` );
			if ( captures ) await page.screenshot( { path: path.join( captures, `picker-${ width }.png` ), fullPage: true } );
			await Promise.all( [ page.waitForURL( '**/wp-admin/themes.php?**' ), page.getByRole( 'button', { name: 'Continue', exact: true } ).click() ] );
			assert.equal( submitted.get( 'sections_present' ), '1' );
			assert.ok( submitted.getAll( 'sections[]' ).includes( 'features-zigzag' ) );
			assert.ok( ! submitted.getAll( 'sections[]' ).includes( 'pricing' ) && ! submitted.getAll( 'sections[]' ).includes( 'testimonials' ) );
			checks += 5;
			await page.goto( 'http://content-fixture.test/confirmation.html' );
			assert.deepEqual( await page.locator( 'form:has(input[value="unapp_wizard_build"]) input[name="sections[]"]' ).evaluateAll( inputs => inputs.map( input => input.value ) ), [ 'testimonials' ] );
			const back = new URL( await page.getByRole( 'link', { name: 'Back', exact: true } ).getAttribute( 'href' ) );
			assert.equal( back.searchParams.get( 'sections_present' ), '1' );
			assert.equal( back.searchParams.get( 'sections[0]' ), 'testimonials' );
			assert.ok( await page.evaluate( () => document.documentElement.scrollWidth <= innerWidth ), `Confirmation fits ${ width }px` );
			checks += 4;
			await page.close();
		}
		console.log( `Content-guide browser checks passed: ${ checks }` );
	} finally { await browser.close(); }
} )().catch( error => { console.error( error ); process.exitCode = 1; } );
