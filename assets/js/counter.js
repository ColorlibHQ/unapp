/**
 * Unapp stat counter.
 *
 * Animates numbers inside paragraphs that carry the `unapp-count` class when
 * they scroll into view. Purely progressive enhancement: the final value is
 * already in the markup, and nothing runs when the visitor prefers reduced
 * motion or IntersectionObserver is unavailable.
 */
( function () {
	'use strict';

	var nodes = document.querySelectorAll( '.unapp-count' );
	if ( ! nodes.length || ! ( 'IntersectionObserver' in window ) ) {
		return;
	}
	if ( window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
		return;
	}

	var DURATION = 1600;

	function animate( el ) {
		var original = el.textContent.trim();
		var match    = original.match( /^([^\d]*)(\d[\d.,]*)(.*)$/ );
		if ( ! match ) {
			return;
		}

		var prefix    = match[ 1 ];
		var numberStr = match[ 2 ];
		var suffix    = match[ 3 ];
		var useComma  = numberStr.indexOf( ',' ) !== -1;
		var decimals  = ( numberStr.split( '.' )[ 1 ] || '' ).length;
		var target    = parseFloat( numberStr.replace( /,/g, '' ) );
		if ( isNaN( target ) ) {
			return;
		}

		function format( value ) {
			var str = value.toFixed( decimals );
			if ( useComma ) {
				var parts = str.split( '.' );
				parts[ 0 ] = parts[ 0 ].replace( /\B(?=(\d{3})+(?!\d))/g, ',' );
				str = parts.join( '.' );
			}
			return prefix + str + suffix;
		}

		var start = null;
		function step( timestamp ) {
			if ( start === null ) {
				start = timestamp;
			}
			var progress = Math.min( ( timestamp - start ) / DURATION, 1 );
			var eased    = 1 - Math.pow( 1 - progress, 3 );
			el.textContent = format( target * eased );
			if ( progress < 1 ) {
				window.requestAnimationFrame( step );
			} else {
				el.textContent = original;
			}
		}

		el.textContent = format( 0 );
		window.requestAnimationFrame( step );
	}

	var observer = new IntersectionObserver(
		function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					observer.unobserve( entry.target );
					animate( entry.target );
				}
			} );
		},
		{ threshold: 0.4 }
	);

	nodes.forEach( function ( el ) {
		observer.observe( el );
	} );
} )();
