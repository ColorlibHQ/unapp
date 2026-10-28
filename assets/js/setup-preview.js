/* POST private choices into the frame; scale actual device widths, not the page. */
( function () {
	'use strict';
	function start() {
		document.querySelectorAll( '.unapp-setup-preview' ).forEach( function ( panel ) {
			var canvas = panel.querySelector( '.unapp-setup-preview__canvas' );
			var frame = panel.querySelector( 'iframe' );
			function resize() {
				var selected = panel.querySelector( 'input[name="setup_preview_width"]:checked' ).value;
				var width = selected === 'mobile' ? 390 : selected === 'tablet' ? 768 : Math.max( 1024, canvas.clientWidth );
				var scale = Math.min( 1, canvas.clientWidth / width );
				frame.style.width = width + 'px';
				frame.style.height = '720px';
				frame.style.transform = 'scale(' + scale + ')';
				frame.style.left = ( ( canvas.clientWidth - width * scale ) / 2 ) + 'px';
				canvas.style.height = ( 720 * scale ) + 'px';
			}
			panel.addEventListener( 'change', resize );
			window.addEventListener( 'resize', resize );
			if ( window.ResizeObserver ) { new ResizeObserver( resize ).observe( canvas ); }
			resize();
			panel.querySelector( 'form' ).submit();
		} );
	}
	if ( document.readyState === 'loading' ) { document.addEventListener( 'DOMContentLoaded', start ); } else { start(); }
} )();
