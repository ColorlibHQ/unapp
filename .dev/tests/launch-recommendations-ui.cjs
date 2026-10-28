/** Optional checks stay outside required forms and work with native controls. */
const assert = require( 'node:assert/strict' );
const { chromium } = require( process.env.PLAYWRIGHT_MODULE || 'playwright' );
const fixture = process.env.UNAPP_RECOMMEND_PREVIEW_PATH;
if ( ! fixture ) throw new Error( 'Set UNAPP_RECOMMEND_PREVIEW_PATH to a WordPress-rendered recommendations fixture.' );
( async () => {
	const browser = await chromium.launch( { headless: true, ...( process.env.CHROME_PATH ? { executablePath: process.env.CHROME_PATH } : {} ) } );
	let checks = 0;
	try {
		for ( const width of [ 1280, 768, 390, 320 ] ) {
			const page = await browser.newPage( { viewport: { width, height: 900 }, javaScriptEnabled: false } );
			let submitted;
			await page.route( '**/*', route => {
				if ( route.request().method() === 'POST' ) {
					submitted = new URLSearchParams( route.request().postData() );
					return route.fulfill( { body: 'Captured required review form.', contentType: 'text/html' } );
				}
				return route.request().url() === 'http://recommend-fixture.test/' ? route.fulfill( { path: fixture, contentType: 'text/html' } ) : route.abort();
			} );
			await page.goto( 'http://recommend-fixture.test/' );
			const optional = page.locator( '#unapp-recommended-checks' );
			assert.equal( await optional.getByRole( 'checkbox' ).count(), 0, 'Optional hints never require a tick' );
			assert.equal( await optional.locator( 'form' ).count(), 0, 'Recommendations do not save settings' );
			assert.equal( await optional.locator( 'xpath=ancestor::form' ).count(), 0, 'Optional checks are outside the required review form' );
			const summary = optional.locator( ':scope > details > summary' );
			await summary.focus();
			await page.keyboard.press( 'Enter' );
			for ( const title of [ 'Site icon', 'Navigation destinations', 'Search visibility', 'Image descriptions' ] ) assert.ok( await optional.getByRole( 'heading', { name: title, exact: true } ).isVisible() );
			assert.ok( await optional.getByText( 'Indexing is discouraged', { exact: true } ).isVisible() );
			assert.ok( await optional.getByText( 'Optional branding', { exact: true } ).isVisible() );
			assert.match( await optional.getByRole( 'link', { name: 'Open General Settings', exact: true } ).getAttribute( 'href' ), /\/options-general\.php$/ );
			assert.match( await optional.getByRole( 'link', { name: 'Review Reading settings', exact: true } ).getAttribute( 'href' ), /\/options-reading\.php$/ );
			for ( const suggestion of await optional.locator( '.unapp-launch__recommendation details > summary' ).all() ) await suggestion.click();
			assert.ok( await optional.getByText( /An image has no alternative-text attribute/ ).count() > 0 );
			assert.ok( await optional.getByText( /This is correct for decoration/ ).count() > 0 );
			assert.ok( await optional.getByText( /Choose a destination for this menu item/ ).count() > 0 );
			assert.equal( await page.evaluate( () => document.documentElement.scrollWidth <= innerWidth ), true, `Expanded recommendations fit ${ width }px` );
			if ( process.env.UNAPP_RECOMMEND_SCREENSHOTS ) await optional.screenshot( { path: `${ process.env.UNAPP_RECOMMEND_SCREENSHOTS }/recommended-${ width }.png` } );
			const review = page.getByRole( 'checkbox', { name: 'Review business details, prices and testimonials', exact: true } );
			await review.check();
			await page.getByRole( 'button', { name: 'Save reviewed items', exact: true } ).focus();
			await Promise.all( [ page.waitForURL( '**/wp-admin/admin-post.php' ), page.keyboard.press( 'Enter' ) ] );
			assert.equal( submitted.get( 'action' ), 'unapp_launch_reviews' );
			assert.deepEqual( submitted.getAll( 'reviewed[]' ), [ 'content' ], 'Recommendations do not enter the required payload' );
			checks += 19;
			await page.close();
		}
		console.log( `Optional launch-check browser checks passed: ${ checks }` );
	} finally { await browser.close(); }
} )().catch( error => { console.error( error ); process.exitCode = 1; } );
