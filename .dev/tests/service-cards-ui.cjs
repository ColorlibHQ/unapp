/** Real service forms and native editor notices/stores; no live requests. */
const assert = require( 'node:assert/strict' );
const fs = require( 'node:fs' );
const path = require( 'node:path' );
const { chromium } = require( process.env.PLAYWRIGHT_MODULE || 'playwright' );
const directory = process.env.UNAPP_SERVICE_PREVIEW_DIR;
if ( ! directory ) throw new Error( 'Set UNAPP_SERVICE_PREVIEW_DIR to the WordPress service fixture directory.' );
const config = JSON.parse( fs.readFileSync( path.join( directory, 'paths.json' ) ) );
const captures = process.env.UNAPP_SERVICE_SCREENSHOTS;
if ( captures ) fs.mkdirSync( captures, { recursive: true } );
( async () => {
	const browser = await chromium.launch( { headless: true, ...( process.env.CHROME_PATH ? { executablePath: process.env.CHROME_PATH } : {} ) } );
	let checks = 0; let submitted;
	try {
		const context = await browser.newContext();
		await context.route( '**/*', route => {
			const request = route.request(); const url = new URL( request.url() );
			if ( request.method() === 'POST' || url.pathname.endsWith( '/themes.php' ) ) {
				submitted = request.method() === 'POST' ? new URLSearchParams( request.postData() ) : url.searchParams;
				return route.fulfill( { body: '<p>Captured native destination.</p>', contentType: 'text/html' } );
			}
			let file;
			if ( url.hostname === 'services-fixture.test' ) file = path.join( directory, url.pathname );
			else if ( request.url().startsWith( config.theme_url + '/' ) ) file = path.join( config.theme_path, decodeURIComponent( url.pathname.slice( new URL( config.theme_url ).pathname.length ) ) );
			else if ( request.url().startsWith( config.site_url + '/wp-includes/' ) || request.url().startsWith( config.site_url + '/wp-admin/' ) ) file = path.join( config.wp_path, decodeURIComponent( url.pathname ) );
			if ( file && fs.existsSync( file ) && fs.statSync( file ).isFile() ) return route.fulfill( { path: file, headers: { 'access-control-allow-origin': '*' } } );
			return route.abort();
		} );
		const editor = await context.newPage();
		await editor.goto( 'http://services-fixture.test/editor.html' );
		const validation = await editor.evaluate( variants => {
			const invalid = []; let count = 0;
			function walk( blocks ) { for ( const block of blocks ) { ++count; if ( ! block.isValid ) invalid.push( block.name ); walk( block.innerBlocks ); } }
			for ( const markup of Object.values( variants ) ) walk( wp.blocks.parse( markup ) );
			return { invalid, count };
		}, JSON.parse( fs.readFileSync( path.join( directory, 'blocks.json' ) ) ) );
		assert.deepEqual( validation.invalid, [], 'Real service copy remains valid native WordPress blocks' );
		console.log( `Valid service-personalized blocks: ${ validation.count }` );
		const markup = JSON.parse( fs.readFileSync( path.join( directory, 'home.json' ) ) );
		const returns = JSON.parse( fs.readFileSync( path.join( directory, 'returns.json' ) ) );
		await editor.evaluate( ( { markup, returns } ) => {
			wp.data.dispatch( 'core' ).addEntities( [ { kind: 'postType', name: 'page', key: 'id', baseURL: '/wp/v2/pages', rawAttributes: [ 'title', 'content', 'excerpt' ] } ] );
			wp.data.dispatch( 'core' ).receiveEntityRecords( 'postType', 'page', [ { id: 123, type: 'page', status: 'publish', title: { raw: 'A real page', rendered: 'A real page' }, content: { raw: markup, rendered: '' }, excerpt: { raw: '', rendered: '' } } ] );
			wp.data.dispatch( 'core/editor' ).setEditedPost( 'page', 123 );
			wp.data.dispatch( 'core/block-editor' ).resetBlocks( wp.blocks.parse( markup ) );
			window.unappContentGuide = returns;
		}, { markup, returns } );
		await editor.addScriptTag( { path: path.join( config.theme_path, 'assets/js/content-guide.js' ) } );
		assert.equal( await editor.evaluate( () => wp.data.select( 'core/notices' ).getNotices().find( notice => notice.id === 'unapp-guide-return' ).actions.length ), 3 );
		await editor.evaluate( () => {
			wp.data.dispatch( 'core/editor' ).editPost( { title: 'Unsaved native edit' } );
			const notice = wp.data.select( 'core/notices' ).getNotices().find( item => item.id === 'unapp-guide-return' );
			notice.actions[0].onClick();
		} );
		assert.equal( await editor.evaluate( () => wp.data.select( 'core/editor' ).isEditedPostDirty() ), true );
		assert.equal( await editor.evaluate( () => wp.data.select( 'core/notices' ).getNotices().filter( notice => notice.id === 'unapp-guide-save-first' ).length ), 1 );
		assert.equal( editor.url(), 'http://services-fixture.test/editor.html', 'Unsaved changes prevent return navigation' );
		checks += 4;
		await editor.evaluate( () => {
			wp.data.dispatch( 'core' ).undo();
			wp.data.dispatch( 'core' ).editEntityRecord( 'postType', 'page', 123, { title: 'A real page' } );
			const notice = wp.data.select( 'core/notices' ).getNotices().find( item => item.id === 'unapp-guide-return' );
			wp.element.createRoot( document.getElementById( 'shortcuts' ) ).render( wp.element.createElement( wp.components.Notice, { status: 'info', actions: notice.actions, isDismissible: false }, notice.content ) );
		} );
		assert.equal( await editor.evaluate( () => wp.data.select( 'core/editor' ).isEditedPostDirty() ), false );
		for ( const width of [ 1280, 768, 390, 320 ] ) {
			await editor.setViewportSize( { width, height: 900 } );
			assert.ok( await editor.getByRole( 'button', { name: 'Review this section', exact: true } ).isVisible() );
			assert.ok( await editor.evaluate( () => document.documentElement.scrollWidth <= innerWidth ), `Native return notice fits ${ width }px` );
			if ( captures ) await editor.locator( '.components-notice' ).screenshot( { path: path.join( captures, `editor-returns-${ width }.png` ) } );
			checks += 2;
		}
		const beforeReturn = await editor.evaluate( () => wp.blocks.serialize( wp.data.select( 'core/block-editor' ).getBlocks() ) );
		assert.ok( beforeReturn.includes( 'My direct title' ), 'Return helpers do not rewrite edited page content' );
		await editor.getByRole( 'button', { name: 'Review this section', exact: true } ).focus();
		await Promise.all( [ editor.waitForURL( '**/wp-admin/themes.php?**' ), editor.keyboard.press( 'Enter' ) ] );
		assert.equal( submitted.get( 'section' ), returns.section );
		assert.equal( submitted.get( 'page' ), 'unapp-content-guide' );
		checks += 3;
		await editor.close();
		for ( const width of [ 1280, 768, 390, 320 ] ) {
			const page = await context.newPage(); await page.setViewportSize( { width, height: 900 } );
			for ( const file of [ 'picker', 'profile' ] ) {
				await page.goto( `http://services-fixture.test/${ file }.html` );
				await page.locator( '.unapp-service-fields > summary' ).click();
				assert.equal( await page.getByLabel( 'Service name', { exact: true } ).count(), 3 );
				await page.getByLabel( 'Service name', { exact: true } ).first().fill( 'Window care & repairs' );
				await page.getByLabel( 'Short service description', { exact: true } ).first().fill( 'Careful work, agreed before the visit.' );
				assert.ok( await page.evaluate( () => document.documentElement.scrollWidth <= innerWidth ), `Service ${ file } fits ${ width }px` );
				if ( captures ) await page.locator( '.unapp-service-fields' ).screenshot( { path: path.join( captures, `${ file }-${ width }.png` ) } );
				await Promise.all( [ page.waitForURL( '**/wp-admin/**' ), page.getByRole( 'button', { name: file === 'picker' ? 'Continue' : 'Save business profile', exact: true } ).click() ] );
				assert.equal( submitted.get( 'service_title_0' ), 'Window care & repairs' );
				assert.equal( submitted.get( 'service_description_0' ), 'Careful work, agreed before the visit.' );
				assert.equal( submitted.get( 'service_cards_present' ), '1' );
				checks += 5;
			}
			await page.goto( 'http://services-fixture.test/confirmation.html' );
			await page.getByRole( 'button', { name: 'Back', exact: true } ).focus();
			await Promise.all( [ page.waitForURL( '**/wp-admin/themes.php?**' ), page.keyboard.press( 'Enter' ) ] );
			assert.equal( submitted.get( 'back' ), '1' );
			assert.equal( submitted.get( 'service_title_0' ), 'A newer profile title' );
			assert.ok( submitted.get( 'unapp_choices_nonce' ) );
			checks += 3;
			await page.close();
		}
		console.log( `Service-card and editor-return browser checks passed: ${ checks }` );
	} finally { await browser.close(); }
} )().catch( error => { console.error( error ); process.exitCode = 1; } );
