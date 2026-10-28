/** Verify the actual WP-rendered checklist UI; all requests are intercepted locally. */
const assert = require( 'node:assert/strict' );
const fs = require( 'node:fs' );
const { chromium } = require( process.env.PLAYWRIGHT_MODULE || 'playwright' );
const fixture = process.env.UNAPP_LAUNCH_PREVIEW_PATH;
if ( ! fixture ) throw new Error( 'Generate a WordPress fixture with UNAPP_LAUNCH_PREVIEW_PATH first; see .dev/README.md.' );

( async () => {
	const browser = await chromium.launch( {
		headless: true,
		...( process.env.CHROME_PATH ? { executablePath: process.env.CHROME_PATH } : {} )
	} );
	let checks = 0;
	try {
		for ( const width of [ 1280, 390 ] ) {
			const page = await browser.newPage( { viewport: { width, height: 900 } } );
			let submitted;
			await page.route( '**/*', route => {
				if ( route.request().method() === 'POST' ) {
					submitted = new URLSearchParams( route.request().postData() );
					return route.fulfill( { body: '<p>Captured test submission.</p>', contentType: 'text/html' } );
				}
				if ( route.request().url() === 'http://unapp-launch.test/' ) return route.fulfill( { path: fixture, contentType: 'text/html' } );
				return route.abort();
			} );
			await page.goto( 'http://unapp-launch.test/' );
			assert.equal( await page.getByRole( 'heading', { name: 'Launch checklist', exact: true } ).count(), 1 );
			assert.equal( await page.evaluate( () => document.documentElement.scrollWidth <= innerWidth ), true );
			checks += 2;
			if ( process.env.UNAPP_LAUNCH_SCREENSHOT_DIR ) await page.screenshot( { path: `${process.env.UNAPP_LAUNCH_SCREENSHOT_DIR}/unapp-launch-${width}.png`, fullPage: true } );
			const review = page.getByRole( 'checkbox', { name: 'Review business details, prices and testimonials', exact: true } );
			await review.focus();
			await page.keyboard.press( 'Space' );
			assert.equal( await review.isChecked(), true );
			checks++;
			await page.getByRole( 'button', { name: 'Save reviewed items', exact: true } ).click();
			await page.waitForLoadState( 'load' );
			assert.equal( submitted.get( 'action' ), 'unapp_launch_reviews' );
			assert.deepEqual( submitted.getAll( 'reviewed[]' ), [ 'content' ] );
			assert.match( submitted.get( 'snapshot' ), /^[a-f0-9]{64}$/ );
			assert.ok( submitted.get( '_wpnonce' ) );
			checks += 4;
			await page.goto( 'http://unapp-launch.test/' );
			const picker = page.getByRole( 'combobox', { name: 'Form to display', exact: true } );
			assert.match( await picker.inputValue(), /^testforms:[0-9]+$/ );
			await picker.focus();
			await page.keyboard.press( 'e' );
			await page.keyboard.press( 'Tab' );
			assert.equal( await picker.inputValue(), 'email' );
			await page.getByRole( 'button', { name: 'Save contact form', exact: true } ).click();
			await page.waitForLoadState( 'load' );
			assert.equal( submitted.get( 'action' ), 'unapp_contact_form' );
			assert.equal( submitted.get( 'contact_form' ), 'email' );
			assert.ok( submitted.get( '_wpnonce' ) );
			checks += 5;
			await page.close();
		}
		console.log( `Launch checklist browser checks passed: ${checks}` );
	} finally {
		await browser.close();
	}
} )().catch( error => { console.error( error ); process.exitCode = 1; } );
