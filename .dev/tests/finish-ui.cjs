/** Offline checks against actual WordPress finishing markup and core blocks. */
const assert = require( 'node:assert/strict' );
const fs = require( 'node:fs' );
const path = require( 'node:path' );
const { chromium } = require( process.env.PLAYWRIGHT_MODULE || 'playwright' );
const directory = process.env.UNAPP_FINISH_PREVIEW_DIR;
if ( ! directory ) throw new Error( 'Set UNAPP_FINISH_PREVIEW_DIR to an import.php fixture directory.' );
const config = JSON.parse( fs.readFileSync( path.join( directory, 'paths.json' ) ) );
const captures = process.env.UNAPP_FINISH_SCREENSHOTS;
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
			if ( request.method() === 'POST' ) {
				submitted = new URLSearchParams( request.postData() );
				return route.fulfill( { body: '<p>Captured native form.</p>', contentType: 'text/html' } );
			}
			let file;
			if ( url.hostname === 'finish-fixture.test' ) file = path.join( directory, url.pathname );
			else if ( request.url().startsWith( config.theme_url + '/' ) ) file = path.join( config.theme_path, decodeURIComponent( url.pathname.slice( new URL( config.theme_url ).pathname.length ) ) );
			else if ( request.url().startsWith( config.site_url + '/wp-includes/' ) || request.url().startsWith( config.site_url + '/wp-admin/' ) ) file = path.join( config.wp_path, decodeURIComponent( url.pathname ) );
			if ( file && fs.existsSync( file ) && fs.statSync( file ).isFile() ) return route.fulfill( { path: file, headers: { 'access-control-allow-origin': '*' } } );
			return route.abort();
		} );
		const editor = await context.newPage();
		await editor.goto( 'http://finish-fixture.test/editor.html' );
		const validation = await editor.evaluate( patterns => {
			const invalid = [];
			let count = 0;
			function walk( blocks ) { for ( const block of blocks ) { ++count; if ( ! block.isValid ) invalid.push( { name: block.name, issues: block.validationIssues } ); walk( block.innerBlocks ); } }
			for ( const markup of Object.values( patterns ) ) walk( wp.blocks.parse( markup ) );
			return { invalid, count };
		}, JSON.parse( fs.readFileSync( path.join( directory, 'blocks.json' ) ) ) );
		assert.deepEqual( validation.invalid, [], 'Main actions remain valid native WordPress blocks' );
		console.log( `Valid main-action blocks: ${ validation.count }` );
		await editor.close();
		for ( const width of [ 1280, 768, 390, 320 ] ) {
			const page = await context.newPage();
			await page.setViewportSize( { width, height: 900 } );
			await page.goto( 'http://finish-fixture.test/screen.html' );
			assert.equal( await page.evaluate( () => innerWidth ), width );
			assert.ok( await page.evaluate( () => document.documentElement.scrollWidth <= innerWidth ), `Finish screen fits ${ width }px` );
			assert.ok( await page.getByRole( 'heading', { name: 'Finish your website', exact: true } ).isVisible() );
			assert.match( await page.getByRole( 'link', { name: 'Edit homepage in WordPress', exact: true } ).getAttribute( 'href' ), /post\.php\?post=\d+&action=edit/ );
			assert.match( await page.getByRole( 'link', { name: 'Make it yours', exact: true } ).getAttribute( 'href' ), /page=unapp-design/ );
			assert.match( await page.getByRole( 'link', { name: 'Choose a contact form', exact: true } ).getAttribute( 'href' ), /#unapp-contact-form$/ );
			assert.ok( await page.getByRole( 'link', { name: 'Review search visibility', exact: true } ).isVisible() );
			assert.equal( await page.getByLabel( 'Main action', { exact: true } ).inputValue(), 'quote', 'Saved action resumes on return' );
			checks += 8;
			await page.getByLabel( 'Main action', { exact: true } ).selectOption( 'book' );
			await page.getByLabel( 'Button wording (optional)', { exact: true } ).fill( 'Book a visit' );
			await page.getByLabel( 'Booking or quote URL', { exact: true } ).fill( 'https://booking.test/visit?team=one&service=two' );
			if ( captures ) await page.screenshot( { path: path.join( captures, `finish-${ width }.png` ), fullPage: true } );
			await page.getByRole( 'button', { name: 'Save main action', exact: true } ).focus();
			await Promise.all( [ page.waitForURL( '**/wp-admin/admin-post.php' ), page.keyboard.press( 'Enter' ) ] );
			assert.equal( submitted.get( 'action' ), 'unapp_main_action' );
			assert.equal( submitted.get( 'type' ), 'book' );
			assert.equal( submitted.get( 'label' ), 'Book a visit' );
			assert.equal( submitted.get( 'url' ), 'https://booking.test/visit?team=one&service=two' );
			assert.match( submitted.get( 'snapshot' ), /^[a-f0-9]{64}$/ );
			assert.ok( submitted.get( '_wpnonce' ) );
			checks += 6;
			await page.close();
		}
		console.log( `Finish browser checks passed: ${ checks }` );
	} finally { await browser.close(); }
} )().catch( error => { console.error( error ); process.exitCode = 1; } );
