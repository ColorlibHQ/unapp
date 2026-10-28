/**
 * Monthly / yearly price switch.
 *
 * Without JavaScript the monthly prices render and the button is inert, so the
 * section is still complete — the switch only ever hides one set and shows the
 * other.
 */
( function () {
	function init() {
		document.querySelectorAll( '.unapp-period' ).forEach( function ( wrapper ) {
			var control = wrapper.matches( 'a, button' ) ? wrapper : wrapper.querySelector( 'a, button' );
			var scope = wrapper.closest( '.wp-block-group.alignfull' ) || document.body;

			if ( ! control ) {
				return;
			}

			control.setAttribute( 'role', 'button' );
			control.setAttribute( 'aria-label', window.unappPeriodToggle ? window.unappPeriodToggle.label : 'Yearly billing' );
			control.setAttribute( 'aria-pressed', scope.getAttribute( 'data-unapp-period' ) === 'yearly' ? 'true' : 'false' );
			// Links need Space activation to behave like the buttons they announce.
			if ( control.tagName === 'A' ) {
				control.addEventListener( 'keydown', function ( event ) {
					if ( event.key === ' ' ) {
						event.preventDefault();
						if ( ! event.repeat ) {
							control.click();
						}
					}
				} );
			}

			control.addEventListener( 'click', function ( event ) {
				event.preventDefault();
				var yearly = scope.getAttribute( 'data-unapp-period' ) === 'yearly';
				scope.setAttribute( 'data-unapp-period', yearly ? 'monthly' : 'yearly' );
				scope.querySelectorAll( '.unapp-period' ).forEach( function ( toggle ) {
					if ( ( toggle.closest( '.wp-block-group.alignfull' ) || document.body ) === scope ) {
						var button = toggle.matches( 'a, button' ) ? toggle : toggle.querySelector( 'a, button' );
						if ( button ) {
							button.setAttribute( 'aria-pressed', yearly ? 'false' : 'true' );
						}
					}
				} );
			} );
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
