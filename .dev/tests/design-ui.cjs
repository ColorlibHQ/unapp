/** Offline browser checks for the actual WordPress-rendered design workspace. */
const assert = require( 'node:assert/strict' );
const fs = require( 'node:fs' );
const path = require( 'node:path' );
const { chromium } = require( process.env.PLAYWRIGHT_MODULE || 'playwright' );
const root = process.env.UNAPP_DESIGN_PREVIEW_DIR;
if ( ! root ) throw new Error( 'Set UNAPP_DESIGN_PREVIEW_DIR; generate fixtures with .dev/tests/import.php.' );
const config = JSON.parse( fs.readFileSync( path.join( root, 'paths.json' ) ) );
const screenshots = process.env.UNAPP_DESIGN_SCREENSHOTS;
if ( screenshots ) fs.mkdirSync( screenshots, { recursive: true } );

( async () => {
	const browser = await chromium.launch( { headless: true, ...( process.env.CHROME_PATH ? { executablePath: process.env.CHROME_PATH } : {} ) } );
	let checks = 0;
	let submitted;
	try {
		const context = await browser.newContext();
		await context.route( '**/*', route => {
			const request = route.request();
			const url = new URL( request.url() );
			if ( request.method() === 'POST' ) {
				if ( ! request.isNavigationRequest() ) return route.abort();
				submitted = new URLSearchParams( request.postData() );
				return route.fulfill( { body: '<p>Captured test request.</p>', contentType: 'text/html' } );
			}
			let file;
			if ( url.hostname === 'design-fixture.test' ) file = path.join( root, url.pathname === '/' ? 'screen.html' : url.pathname );
			else if ( url.searchParams.get( 'action' ) === 'unapp_design_preview' ) file = path.join( root, `${ url.searchParams.get( 'area' ) }-${ url.searchParams.get( 'preset' ) }.html` );
			else if ( request.url().startsWith( config.theme_url + '/' ) ) file = path.join( config.theme_path, decodeURIComponent( url.pathname.slice( new URL( config.theme_url ).pathname.length ) ) );
			else if ( request.url().startsWith( config.site_url + '/' ) && /^\/(wp-includes|wp-admin)\//.test( url.pathname ) ) file = path.join( config.wp_path, decodeURIComponent( url.pathname ) );
			if ( file && fs.existsSync( file ) && fs.statSync( file ).isFile() ) return route.fulfill( { path: file, headers: { 'access-control-allow-origin': '*' } } );
			return route.abort();
		} );
		const editor = await context.newPage();
		await editor.goto( 'http://design-fixture.test/editor.html' );
		const validation = await editor.evaluate( () => {
			const invalid = [];
			let count = 0;
			function walk( blocks, preset ) {
				for ( const block of blocks ) {
					++count;
					if ( ! block.isValid ) invalid.push( { preset, name: block.name, issues: block.validationIssues } );
					walk( block.innerBlocks, preset );
				}
			}
			for ( const [ preset, markup ] of Object.entries( window.designPatterns ) ) walk( wp.blocks.parse( markup ), preset );
			return { invalid, count };
		} );
		assert.deepEqual( validation.invalid, [], 'Presets pass WordPress core save validation' );
		console.log( `Valid native preset blocks: ${ validation.count }` );
		await editor.close();
		for ( const width of [ 1280, 390, 320 ] ) {
			const page = await context.newPage();
			await page.setViewportSize( { width, height: 900 } );
			await page.goto( 'http://design-fixture.test/' );
			assert.equal( await page.evaluate( () => innerWidth ), width );
			++checks;
			assert.equal( await page.getByRole( 'heading', { name: 'Make it yours', exact: true } ).count(), 1 );
			assert.ok( await page.evaluate( () => document.documentElement.scrollWidth <= innerWidth ) );
			assert.equal( await page.locator( '.unapp-design__presets a' ).count(), 6 );
			assert.equal( new URL( await page.getByRole( 'link', { name: 'Edit your current header in WordPress' } ).getAttribute( 'href' ) ).searchParams.get( 'postType' ), 'wp_template_part' );
			checks += 4;
			const mobile = page.getByRole( 'radio', { name: 'Mobile', exact: true } );
			await mobile.focus();
			await page.keyboard.press( 'Space' );
			assert.ok( await mobile.isChecked() );
			assert.ok( ( await page.locator( 'iframe' ).boundingBox() ).width <= 390 );
			checks += 2;
			await page.frameLocator( 'iframe' ).getByRole( 'heading', { name: 'Your site, your style', exact: true } ).waitFor();
			const preview = page.frames().find( frame => frame.parentFrame() );
			await preview.evaluate( () => document.fonts.ready );
			assert.ok( await preview.evaluate( () => document.documentElement.scrollWidth <= innerWidth ), 'Embedded preview fits the available phone width' );
			++checks;
			if ( screenshots ) await page.screenshot( { path: path.join( screenshots, `make-it-yours-${ width }.png` ), fullPage: true } );
			await Promise.all( [ page.waitForURL( '**/wp-admin/admin-post.php' ), page.getByRole( 'button', { name: 'Apply this header', exact: true } ).click() ] );
			assert.equal( submitted.get( 'action' ), 'unapp_design_save' );
			assert.equal( submitted.get( 'area' ), 'header' );
			assert.equal( submitted.get( 'preset' ), 'standard' );
			assert.match( submitted.get( 'snapshot' ), /^[a-f0-9]{64}$/ );
			assert.ok( submitted.get( '_wpnonce' ) );
			checks += 5;
			await page.goto( 'http://design-fixture.test/' );
			await page.getByRole( 'button', { name: 'Undo latest preset', exact: true } ).focus();
			await Promise.all( [ page.waitForURL( '**/wp-admin/admin-post.php' ), page.keyboard.press( 'Enter' ) ] );
			assert.equal( submitted.get( 'operation' ), 'undo' );
			assert.ok( submitted.get( 'record' ) && submitted.get( '_wpnonce' ) );
			checks += 2;
			await page.close();
		}
		for ( const preset of Object.keys( JSON.parse( fs.readFileSync( path.join( root, 'patterns.json' ) ) ) ) ) {
			for ( const width of [ 1280, 768, 390, 320 ] ) {
				const page = await context.newPage();
				await page.setViewportSize( { width, height: 900 } );
				await page.goto( `http://design-fixture.test/${ preset }.html` );
				assert.equal( await page.evaluate( () => innerWidth ), width );
				++checks;
				await page.evaluate( () => document.fonts.ready );
				assert.ok( await page.evaluate( () => document.documentElement.scrollWidth <= innerWidth ), `${ preset }: no overflow at ${ width }` );
				assert.equal( await page.locator( 'main h1' ).count(), 1 );
				if ( screenshots && [ 1280, 390 ].includes( width ) ) await page.screenshot( { path: path.join( screenshots, `${ preset }-${ width }.png` ) } );
				checks += 2;
				await page.close();
			}
		}
		console.log( `Design browser checks passed: ${ checks }` );
	} finally { await browser.close(); }
} )().catch( error => { console.error( error ); process.exitCode = 1; } );
