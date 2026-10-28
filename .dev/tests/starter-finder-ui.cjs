/** Search, categories, keyboard behavior and native GET fallback, offline. */
const assert = require( 'node:assert/strict' );
const fs = require( 'node:fs' );
const path = require( 'node:path' );
const { chromium } = require( process.env.PLAYWRIGHT_MODULE || 'playwright' );
const directory = process.env.UNAPP_FINDER_PREVIEW_DIR;
if ( ! directory ) throw new Error( 'Set UNAPP_FINDER_PREVIEW_DIR to exported import.php fixtures.' );
const config = JSON.parse( fs.readFileSync( path.join( directory, 'paths.json' ) ) );
const captures = process.env.UNAPP_FINDER_SCREENSHOTS;
if ( captures ) fs.mkdirSync( captures, { recursive: true } );
async function serve( route ) {
	const url = new URL( route.request().url() );
	let file;
	if ( url.hostname === 'finder-fixture.test' ) file = path.join( directory, url.pathname );
	else if ( url.pathname.endsWith( '/wp-admin/themes.php' ) ) file = path.join( directory, 'filtered.html' );
	else if ( route.request().url().startsWith( config.theme_url + '/' ) ) file = path.join( config.theme_path, decodeURIComponent( url.pathname.slice( new URL( config.theme_url ).pathname.length ) ) );
	else if ( route.request().url().startsWith( config.site_url + '/wp-includes/' ) || route.request().url().startsWith( config.site_url + '/wp-admin/' ) ) file = path.join( config.wp_path, decodeURIComponent( url.pathname ) );
	if ( file && fs.existsSync( file ) && fs.statSync( file ).isFile() ) return route.fulfill( { path: file, headers: { 'access-control-allow-origin': '*' } } );
	return route.abort();
}
( async () => {
	const browser = await chromium.launch( { headless: true, ...( process.env.CHROME_PATH ? { executablePath: process.env.CHROME_PATH } : {} ) } );
	let checks = 0;
	try {
		const context = await browser.newContext();
		await context.route( '**/*', serve );
		for ( const width of [ 1280, 768, 390, 320 ] ) {
			const page = await context.newPage();
			await page.setViewportSize( { width, height: 900 } );
			await page.goto( 'http://finder-fixture.test/all.html' );
			assert.equal( await page.locator( '[data-starter]:visible' ).count(), 19 );
			assert.ok( await page.evaluate( () => document.documentElement.scrollWidth <= innerWidth ), `Finder fits ${ width }px` );
			const query = page.getByRole( 'searchbox', { name: 'Find your starter', exact: true } );
			const category = page.getByLabel( 'Category', { exact: true } );
			for ( const [ word, expected ] of [ [ 'electrician', 'home-services' ], [ 'housekeeping', 'cleaning' ], [ 'SALON/BARBER', 'salon' ] ] ) {
				await query.fill( word );
				assert.deepEqual( await page.locator( '[data-starter]:visible' ).evaluateAll( cards => cards.map( card => card.dataset.starter ) ), [ expected ] );
				assert.match( await page.getByRole( 'status' ).innerText(), /^1 of 19/ );
				assert.ok( await page.locator( `[data-starter="${ expected }"]` ).getByText( 'Free · Native blocks · No required plugins', { exact: true } ).isVisible() );
				checks += 3;
			}
			await category.selectOption( 'shop' );
			assert.equal( await page.locator( '[data-starter]:visible' ).count(), 0 );
			assert.ok( await page.getByText( 'No starters match. Try a broader search or clear the category.', { exact: true } ).isVisible() );
			await page.getByRole( 'link', { name: 'Clear filters', exact: true } ).focus();
			await page.keyboard.press( 'Enter' );
			assert.equal( await page.locator( '[data-starter]:visible' ).count(), 19 );
			await category.selectOption( 'shop' );
			assert.deepEqual( await page.locator( '[data-starter]:visible' ).evaluateAll( cards => cards.map( card => card.dataset.starter ) ), [ 'shop' ] );
			assert.ok( await page.getByText( 'Free · Requires WooCommerce', { exact: true } ).isVisible() );
			checks += 7;
			await page.getByRole( 'link', { name: 'Clear filters', exact: true } ).click();
			await category.selectOption( 'local' );
			assert.ok( await page.locator( '[data-starter="home-services"]' ).isVisible() && await page.locator( '[data-starter="cleaning"]' ).isVisible() && await page.locator( '[data-starter="salon"]' ).isVisible() );
			assert.ok( await page.evaluate( () => document.documentElement.scrollWidth <= innerWidth ), `Filtered cards fit ${ width }px` );
			const missing = await page.locator( '[data-starter]:visible img' ).evaluateAll( async images => {
				for ( const image of images ) { image.loading = 'eager'; await image.decode().catch( () => {} ); }
				return images.filter( image => ! image.naturalWidth ).map( image => image.src );
			} );
			assert.deepEqual( missing, [], 'Visible preview thumbnails load from bundled assets' );
			++checks;
			if ( captures ) await page.screenshot( { path: path.join( captures, `finder-${ width }.png` ), fullPage: true } );
			await query.fill( 'barber' );
			await query.press( 'Enter' );
			assert.equal( await page.locator( '[data-starter]:visible' ).count(), 1 );
			const card = page.locator( '[data-starter="salon"]' );
			assert.match( await card.getByRole( 'link', { name: 'Set up this starter', exact: true } ).getAttribute( 'href' ), /step=brand&starter=salon/ );
			assert.match( await card.getByRole( 'link', { name: 'Preview', exact: true } ).getAttribute( 'href' ), /step=preview&starter=salon/ );
			checks += 5;
			await page.goto( 'http://finder-fixture.test/filtered.html' );
			assert.equal( await page.locator( '[data-starter]:visible' ).count(), 1 );
			await page.getByRole( 'link', { name: 'Clear filters', exact: true } ).click();
			assert.equal( await page.locator( '[data-starter]:visible' ).count(), 19, 'Clearing a server-filtered result restores every bundled card' );
			checks += 2;
			await page.close();
		}
		const noScript = await browser.newContext( { javaScriptEnabled: false } );
		await noScript.route( '**/*', serve );
		const fallback = await noScript.newPage();
		await fallback.goto( 'http://finder-fixture.test/all.html' );
		await fallback.getByRole( 'searchbox', { name: 'Find your starter', exact: true } ).fill( 'barber' );
		await fallback.getByLabel( 'Category', { exact: true } ).selectOption( 'local' );
		await Promise.all( [ fallback.waitForURL( '**/wp-admin/themes.php?**' ), fallback.getByRole( 'button', { name: 'Find starters', exact: true } ).click() ] );
		assert.equal( await fallback.locator( '[data-starter]:visible' ).count(), 1 );
		assert.ok( await fallback.locator( '[data-starter="salon"]' ).isVisible() );
		assert.equal( new URL( fallback.url() ).searchParams.get( 'starter_search' ), 'barber' );
		checks += 3;
		await noScript.close();
		console.log( `Starter finder browser checks passed: ${ checks }` );
	} finally { await browser.close(); }
} )().catch( error => { console.error( error ); process.exitCode = 1; } );
