/**
 * Setup wizard: the logo picker.
 *
 * Everything else in the wizard is plain form fields; only the media modal
 * needs script.
 */
( function () {
	function init() {
		var frame = null;
		var wrap = document.querySelector( '.unapp-logo' );

		if ( ! wrap || ! window.wp || ! window.wp.media ) {
			return;
		}

		var preview = wrap.querySelector( '.unapp-logo__preview' );
		var field = wrap.querySelector( '.unapp-logo__id' );
		var clear = wrap.querySelector( '.unapp-logo__clear' );

		wrap.querySelector( '.unapp-logo__choose' ).addEventListener( 'click', function () {
			if ( ! frame ) {
				frame = window.wp.media( {
					title: window.unappWizard ? window.unappWizard.chooseLogo : 'Choose a logo',
					button: { text: window.unappWizard ? window.unappWizard.useLogo : 'Use this logo' },
					library: { type: 'image' },
					multiple: false
				} );

				frame.on( 'select', function () {
					var image = frame.state().get( 'selection' ).first().toJSON();
					var src = image.sizes && image.sizes.medium ? image.sizes.medium.url : image.url;

					field.value = image.id;
					preview.src = src;
					preview.hidden = false;
					clear.hidden = false;
				} );
			}

			frame.open();
		} );

		clear.addEventListener( 'click', function () {
			field.value = '';
			preview.hidden = true;
			preview.removeAttribute( 'src' );
			clear.hidden = true;
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
