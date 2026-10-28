/** Browser regressions: PLAYWRIGHT_MODULE=/path/to/playwright node .dev/tests/toggles.cjs */
const assert = require( 'node:assert/strict' );
const path = require( 'node:path' );
const { chromium } = require( process.env.PLAYWRIGHT_MODULE || 'playwright' );

( async () => {
	const browser = await chromium.launch( {
		headless: true,
		...( process.env.CHROME_PATH ? { executablePath: process.env.CHROME_PATH } : {} )
	} );
	let checks = 0;
	try {
		const page = await browser.newPage();
		await page.setContent( '<section class="wp-block-group alignfull" id="prices"><div class="unapp-period"><a href="#">Show yearly prices</a></div><button class="unapp-period">Show yearly prices</button></section><section class="wp-block-group alignfull" id="other"><a class="unapp-period" href="#">Show yearly prices</a></section><div class="unapp-scheme-toggle"><a href="#"><span>Dark</span></a></div><button class="unapp-scheme-toggle">Dark</button>' );
		await page.addScriptTag( { path: path.resolve( __dirname, '../../assets/js/period-toggle.js' ) } );
		await page.addScriptTag( { path: path.resolve( __dirname, '../../assets/js/scheme-toggle.js' ) } );
		const yearly = page.locator( '#prices a' );
		const yearlyButton = page.locator( '#prices button' );
		await yearly.focus();
		await page.keyboard.press( 'Space' );
		assert.equal( await yearly.getAttribute( 'aria-pressed' ), 'true' );
		assert.equal( await yearlyButton.getAttribute( 'aria-pressed' ), 'true' );
		assert.equal( await page.locator( '#other a' ).getAttribute( 'aria-pressed' ), 'false' );
		checks += 3;
		await page.keyboard.press( 'Enter' );
		assert.equal( await yearly.getAttribute( 'aria-pressed' ), 'false' );
		checks++;
		await page.keyboard.down( 'Space' );
		await page.keyboard.down( 'Space' );
		await page.keyboard.up( 'Space' );
		assert.equal( await yearly.getAttribute( 'aria-pressed' ), 'true' );
		checks++;
		await yearlyButton.focus();
		await page.keyboard.press( 'Space' );
		assert.equal( await yearly.getAttribute( 'aria-pressed' ), 'false' );
		assert.equal( await yearlyButton.getAttribute( 'aria-pressed' ), 'false' );
		checks += 2;
		const dark = page.locator( '.unapp-scheme-toggle a' );
		const darkButton = page.locator( 'button.unapp-scheme-toggle' );
		await dark.focus();
		await page.keyboard.press( 'Space' );
		assert.equal( await page.locator( 'html' ).getAttribute( 'data-unapp-scheme' ), 'dark' );
		assert.equal( await darkButton.getAttribute( 'aria-pressed' ), 'true' );
		assert.equal( await dark.getAttribute( 'aria-label' ), 'Dark mode' );
		checks += 3;
		await page.keyboard.press( 'Enter' );
		assert.equal( await dark.getAttribute( 'aria-pressed' ), 'false' );
		checks++;
		await darkButton.focus();
		await page.keyboard.press( 'Space' );
		assert.equal( await dark.getAttribute( 'aria-pressed' ), 'true' );
		checks++;
		assert.equal( await page.evaluate( () => location.hash ), '' );
		checks++;
		console.log( `Browser toggle regression checks passed: ${checks}` );
	} finally {
		await browser.close();
	}
} )().catch( error => { console.error( error ); process.exitCode = 1; } );
