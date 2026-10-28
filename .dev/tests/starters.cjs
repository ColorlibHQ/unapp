/** Offline browser checks against exports from .dev/render-starter.php. */
const assert = require( 'node:assert/strict' );
const fs = require( 'node:fs' );
const path = require( 'node:path' );
const { chromium } = require( process.env.PLAYWRIGHT_MODULE || 'playwright' );
const directory = process.env.UNAPP_STARTER_PREVIEW_DIR;
if ( ! directory ) throw new Error( 'Set UNAPP_STARTER_PREVIEW_DIR to a directory of exported previews.' );
const config = JSON.parse( fs.readFileSync( path.join( directory, 'paths.json' ) ) );
const screenshots = process.env.UNAPP_STARTER_SCREENSHOTS;
if ( screenshots ) fs.mkdirSync( screenshots, { recursive: true } );

async function serve( route, fixture ) {
	const url = route.request().url();
	if ( url.startsWith( 'http://unapp-preview.test/' ) ) return route.fulfill( { path: path.join( directory, fixture ), contentType: 'text/html' } );
	let file;
	if ( url.startsWith( config.theme_url + '/' ) ) file = path.join( config.theme_path, decodeURIComponent( new URL( url ).pathname.slice( new URL( config.theme_url ).pathname.length ) ) );
	else if ( url.startsWith( config.site_url + '/wp-includes/' ) ) file = path.join( config.wp_path, 'wp-includes', decodeURIComponent( new URL( url ).pathname.split( '/wp-includes/' )[ 1 ] ) );
	else for ( const kind of [ 'plugins', 'uploads' ] ) {
		if ( config[ `${ kind }_url` ] && url.startsWith( config[ `${ kind }_url` ] + '/' ) ) file = path.join( config[ `${ kind }_path` ], decodeURIComponent( new URL( url ).pathname.slice( new URL( config[ `${ kind }_url` ] ).pathname.length ) ) );
	}
	if ( file && fs.existsSync( file ) && fs.statSync( file ).isFile() ) return route.fulfill( { path: file, headers: { 'access-control-allow-origin': '*' } } );
	return route.abort();
}

( async () => {
	const browser = await chromium.launch( { headless: true, ...( process.env.CHROME_PATH ? { executablePath: process.env.CHROME_PATH } : {} ) } );
	let checks = 0;
	try {
		if ( fs.existsSync( path.join( directory, 'editor.html' ) ) ) {
			const editor = await browser.newPage();
			await editor.route( '**/*', route => serve( route, 'editor.html' ) );
			await editor.goto( 'http://unapp-preview.test/editor' );
			const validation = await editor.evaluate( () => {
				const invalid = [];
				let count = 0;
				function walk( blocks, pattern ) {
					for ( const block of blocks ) {
						++count;
						if ( ! block.isValid ) invalid.push( { pattern, name: block.name, issues: block.validationIssues } );
						walk( block.innerBlocks, pattern );
					}
				}
				for ( const [ pattern, markup ] of Object.entries( window.starterPatterns ) ) walk( wp.blocks.parse( markup ), pattern );
				return { invalid, count };
			} );
			assert.deepEqual( validation.invalid, [], 'Changed patterns pass WordPress core save-markup validation' );
			checks += validation.count;
			console.log( `Valid editor blocks: ${ validation.count }` );
			await editor.close();
		}
		const starters = process.env.UNAPP_STARTERS ? process.env.UNAPP_STARTERS.split( ',' ) : [ 'shop', 'restaurant', 'fitness', 'church', 'blog', 'realestate', 'medical', 'education', 'events', 'finance', 'saas', 'portfolio', 'agency', 'home-services', 'cleaning', 'salon', 'coach', 'photographer', 'nonprofit' ];
		for ( const starter of starters ) {
			for ( const width of [ 1280, 768, 390, 360, 320 ] ) {
				const height = ( { 390: 844, 360: 800, 320: 740 } )[ width ] || 900;
				const page = await browser.newPage( { viewport: { width, height } } );
				await page.route( '**/*', route => serve( route, `${ starter }-home.html` ) );
				await page.goto( 'http://unapp-preview.test/' );
				await page.evaluate( () => document.fonts.ready );
				if ( starter === 'shop' && process.env.UNAPP_EXPECT_SAMPLE_PRODUCTS ) {
					assert.equal( await page.locator( '.wc-block-product-template > li' ).count(), 4, 'Native WooCommerce collection renders all four fixture products' );
					++checks;
				}
				// Scroll once to load native lazy images throughout the composition.
				await page.evaluate( async () => {
					for ( const img of document.querySelectorAll( 'img[src]:not([src=""]):not([data-wp-bind--src])' ) ) {
						img.loading = 'eager';
						await img.decode().catch( () => {} );
					}
				} );
				const result = await page.evaluate( () => {
					const hero = document.querySelector( '.unapp-starter-hero' );
					const buttons = [ ...hero.querySelectorAll( '.unapp-starter-actions a' ) ];
					return {
						overflow: document.documentElement.scrollWidth - innerWidth,
						headings: document.querySelectorAll( 'h1' ).length,
						missing: [ ...document.querySelectorAll( 'img[src]:not([src=""]):not([data-wp-bind--src])' ) ].filter( img => ! img.complete || ! img.naturalWidth ).map( img => img.src ),
						art: hero.querySelector( 'img' ).src,
						buttons: buttons.map( a => { const r = a.getBoundingClientRect(); return { width: r.width, height: r.height, bottom: r.bottom, left: r.left, right: r.right, href: a.href }; } ),
						anchors: buttons.map( a => new URL( a.href ).hash.slice( 1 ) ).filter( Boolean ).map( id => ( { id, count: document.querySelectorAll( `[id="${ id }"]` ).length } ) )
					};
				} );
				const label = `${ starter } at ${ width }px`;
				assert.ok( result.overflow <= 1, `${ label }: horizontal overflow ${ result.overflow }px` );
				assert.equal( result.headings, 1, `${ label }: one page heading` );
				assert.deepEqual( result.missing, [], `${ label }: every image loads` );
				assert.ok( result.art.includes( '/showcase/' ), `${ label }: purpose-made hero artwork` );
				assert.equal( result.buttons.length, 2, `${ label }: primary and secondary actions` );
				for ( const action of result.buttons ) {
					assert.ok( action.height >= 48 && action.left >= 0 && action.right <= width + 1, `${ label }: usable, contained actions` );
					if ( width <= 599 ) assert.ok( action.width >= width - 100, `${ label }: full-width phone action` );
				}
				for ( const anchor of result.anchors ) assert.equal( anchor.count, 1, `${ label }: unique destination #${ anchor.id }` );
				if ( width <= 599 ) assert.ok( result.buttons[ 0 ].bottom < height, `${ label }: primary action in first screen` );
				checks += 9;
				if ( starter === 'photographer' ) {
					const work = await page.locator( '#selected-work img' ).evaluateAll( images => images.map( image => ( { src: image.src, alt: image.alt } ) ) );
					assert.equal( work.length, 3, 'Photographer has a complete three-image portfolio' );
					assert.equal( new Set( work.map( image => image.src ) ).size, 3, 'Portfolio photographs are distinct' );
					assert.ok( work.every( image => image.alt.trim() ), 'Portfolio photographs have descriptive alternative text' );
					checks += 3;
				}
				if ( starter === 'nonprofit' ) {
					assert.equal( await page.locator( '#support a[href^="mailto:"]' ).count(), 3, 'Nonprofit offers three practical involvement/giving enquiries without a payment plugin' );
					assert.equal( await page.locator( '#support form' ).count(), 0, 'Nonprofit does not imply a configured checkout' );
					checks += 2;
				}
				if ( screenshots ) {
					await page.screenshot( { path: path.join( screenshots, `${ starter }-${ width }.png` ), fullPage: width === 1280 || width === 390 } );
					if ( width === 1280 || width === 390 ) await page.screenshot( { path: path.join( screenshots, `${ starter }-${ width }-hero.png` ) } );
					if ( width === 1280 ) await page.screenshot( { path: path.join( screenshots, `${ starter }-thumbnail.png` ), clip: { x: 0, y: 0, width: 1280, height: 960 } } );
					if ( starter === 'photographer' && width === 1280 ) await page.locator( '#selected-work' ).screenshot( { path: path.join( screenshots, 'photographer-gallery.png' ) } );
				}
				console.log( `Passed ${ label }` );
				await page.close();
			}
		}
	} finally { await browser.close(); }
	console.log( `Starter browser checks passed: ${ checks }` );
} )().catch( error => { console.error( error ); process.exitCode = 1; } );
