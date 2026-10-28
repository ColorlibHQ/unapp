/* Scale a real device-width preview to fit the admin panel; no editor state. */
( function () {
	'use strict';
	document.querySelectorAll( '.unapp-design__review' ).forEach( function ( panel ) {
		var canvas = panel.querySelector( '.unapp-design__canvas' );
		var frame = panel.querySelector( 'iframe' );
		if ( ! canvas || ! frame ) {
			return;
		}
		function resize() {
			var selected = panel.querySelector( 'input[name="design_width"]:checked' );
			var available = canvas.clientWidth;
			if ( ! selected || ! available ) {
				return;
			}
			var width = selected.value === 'mobile' ? 390 : selected.value === 'tablet' ? 768 : Math.max( 1024, available );
			var scale = Math.min( 1, available / width );
			frame.style.width = width + 'px';
			frame.style.height = '480px';
			frame.style.transform = 'scale(' + scale + ')';
			canvas.style.height = ( 480 * scale ) + 'px';
			canvas.style.overflow = 'hidden';
			canvas.scrollLeft = 0;
		}
		panel.querySelectorAll( 'input[name="design_width"]' ).forEach( function ( input ) {
			input.addEventListener( 'change', resize );
		} );
		if ( window.ResizeObserver ) {
			new ResizeObserver( resize ).observe( canvas );
		} else {
			window.addEventListener( 'resize', resize );
		}
		resize();
	} );
}() );
