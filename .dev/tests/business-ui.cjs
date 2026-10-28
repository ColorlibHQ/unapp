/** Verify real wizard/profile forms and shared core-block markup offline. */
const assert = require( 'node:assert/strict' );
const fs = require( 'node:fs' );
const path = require( 'node:path' );
const { chromium } = require( process.env.PLAYWRIGHT_MODULE || 'playwright' );
const directory = process.env.UNAPP_BUSINESS_PREVIEW_DIR;
if ( ! directory ) throw new Error( 'Set UNAPP_BUSINESS_PREVIEW_DIR to an import.php fixture directory.' );
const config = JSON.parse( fs.readFileSync( path.join( directory, 'paths.json' ) ) );
const captures = process.env.UNAPP_BUSINESS_SCREENSHOTS;
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
				return route.fulfill( { body: '<p>Captured test form.</p>', contentType: 'text/html' } );
			}
			if ( url.searchParams.get( 'step' ) === 'plugins' ) {
				submitted = url.searchParams;
				return route.fulfill( { body: '<p>Captured wizard details.</p>', contentType: 'text/html' } );
			}
			let file;
			if ( url.hostname === 'business-fixture.test' ) file = path.join( directory, url.pathname );
			else if ( request.url().startsWith( config.theme_url + '/' ) ) file = path.join( config.theme_path, decodeURIComponent( url.pathname.slice( new URL( config.theme_url ).pathname.length ) ) );
			else if ( request.url().startsWith( config.site_url + '/wp-includes/' ) ) file = path.join( config.wp_path, decodeURIComponent( url.pathname ) );
			if ( file && fs.existsSync( file ) && fs.statSync( file ).isFile() ) return route.fulfill( { path: file, headers: { 'access-control-allow-origin': '*' } } );
			return route.abort();
		} );
		const editor = await context.newPage();
		await editor.goto( 'http://business-fixture.test/editor.html' );
		const raw = JSON.parse( fs.readFileSync( path.join( directory, 'profile-blocks.json' ) ) );
		const validation = await editor.evaluate( patterns => {
			const invalid = [];
			let count = 0;
			function walk( blocks ) { for ( const block of blocks ) { ++count; if ( ! block.isValid ) invalid.push( { name: block.name, issues: block.validationIssues } ); walk( block.innerBlocks ); } }
			for ( const markup of Object.values( patterns ) ) walk( wp.blocks.parse( markup ) );
			return { invalid, count };
		}, raw );
		assert.deepEqual( validation.invalid, [], 'Shared details remain valid native WordPress blocks' );
		console.log( `Valid personalized blocks: ${ validation.count }` );
		await editor.close();
		for ( const width of [ 1280, 768, 390, 320 ] ) {
			const page = await context.newPage();
			await page.setViewportSize( { width, height: 900 } );
			await page.goto( 'http://business-fixture.test/home.html' );
			await page.evaluate( () => document.fonts.ready );
			assert.ok( await page.evaluate( () => document.documentElement.scrollWidth <= innerWidth ), `Personalized home fits ${ width }px` );
			assert.ok( await page.locator( '.unapp-business-profile' ).count() >= 2 );
			assert.ok( await page.getByText( 'hello@harbor.test', { exact: true } ).count() >= 2 );
			checks += 3;
			if ( captures ) await page.screenshot( { path: path.join( captures, `one-page-${ width }.png` ), fullPage: width === 1280 } );
			if ( captures ) await page.locator( 'footer' ).last().screenshot( { path: path.join( captures, `footer-${ width }.png` ) } );
			await page.goto( 'http://business-fixture.test/wizard.html' );
			assert.ok( await page.getByRole( 'radio', { name: /One-page website/ } ).isChecked() );
			assert.ok( await page.evaluate( () => document.documentElement.scrollWidth <= innerWidth ), `Wizard fits ${ width }px` );
			await page.getByRole( 'radio', { name: /Full website/ } ).check();
			await page.getByLabel( 'What do you do?' ).fill( 'A local studio for thoughtful websites.' );
			await page.getByText( 'More business details (optional)', { exact: true } ).click();
			await page.getByLabel( 'Opening hours', { exact: true } ).fill( 'Mon–Fri, 10–6' );
			if ( captures ) await page.screenshot( { path: path.join( captures, `wizard-${ width }.png` ), fullPage: true } );
			await Promise.all( [ page.waitForURL( '**/wp-admin/themes.php?**' ), page.getByRole( 'button', { name: 'Continue', exact: true } ).click() ] );
			assert.equal( submitted.get( 'layout' ), 'full' );
			assert.equal( submitted.get( 'description' ), 'A local studio for thoughtful websites.' );
			assert.equal( submitted.get( 'hours' ), 'Mon–Fri, 10–6' );
			assert.ok( submitted.get( 'colors' ) && submitted.get( 'typography' ), 'Closed optional style controls retain recommended defaults' );
			checks += 6;
			await page.goto( 'http://business-fixture.test/profile.html' );
			assert.ok( await page.evaluate( () => document.documentElement.scrollWidth <= innerWidth ), `Business profile fits ${ width }px` );
			await page.getByLabel( 'Public email', { exact: true } ).fill( 'owner@new.test' );
			if ( captures ) await page.screenshot( { path: path.join( captures, `profile-${ width }.png` ), fullPage: true } );
			await Promise.all( [ page.waitForURL( '**/wp-admin/admin-post.php' ), page.getByRole( 'button', { name: 'Save business profile', exact: true } ).click() ] );
			assert.equal( submitted.get( 'email' ), 'owner@new.test' );
			assert.equal( submitted.get( 'action' ), 'unapp_business_save' );
			assert.match( submitted.get( 'snapshot' ), /^[a-f0-9]{64}$/ );
			assert.ok( submitted.get( '_wpnonce' ) );
			checks += 5;
			await page.goto( 'http://business-fixture.test/profile.html' );
			await page.getByRole( 'button', { name: 'Add supporting pages', exact: true } ).focus();
			await Promise.all( [ page.waitForURL( '**/wp-admin/admin-post.php' ), page.keyboard.press( 'Enter' ) ] );
			assert.equal( submitted.get( 'operation' ), 'expand' );
			assert.ok( submitted.get( '_wpnonce' ) );
			checks += 2;
			await page.close();
		}
		console.log( `Business browser checks passed: ${ checks }` );
	} finally { await browser.close(); }
} )().catch( error => { console.error( error ); process.exitCode = 1; } );
