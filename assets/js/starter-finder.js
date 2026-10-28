/* Progressive enhancement: native GET form remains the no-JavaScript fallback. */
( function () {
	'use strict';
	function start() {
		var form = document.querySelector( '.unapp-finder' );
		if ( ! form ) { return; }
		var query = form.querySelector( '[name="starter_search"]' );
		var category = form.querySelector( '[name="starter_category"]' );
		var cards = Array.from( document.querySelectorAll( '#unapp-starter-results > [data-starter-search]' ) );
		function filter() {
			var words = query.value.normalize( 'NFD' ).replace( /\p{M}/gu, '' ).toLowerCase().replace( /[^\p{L}\p{N}]+/gu, ' ' ).trim().split( /\s+/ ).filter( Boolean );
			var count = 0;
			cards.forEach( function ( card ) {
				var matches = words.every( function ( word ) { return card.dataset.starterSearch.includes( word ); } ) && ( ! category.value || card.dataset.starterCategories.split( ' ' ).includes( category.value ) );
				card.hidden = ! matches;
				if ( matches ) { ++count; }
			} );
			document.querySelector( '.unapp-finder__count span' ).textContent = count;
			document.querySelector( '.unapp-finder__empty' ).hidden = count !== 0;
		}
		form.addEventListener( 'input', filter );
		form.addEventListener( 'change', filter );
		form.addEventListener( 'submit', function ( event ) { event.preventDefault(); filter(); } );
		form.querySelector( '.unapp-finder__clear' ).addEventListener( 'click', function ( event ) { event.preventDefault(); query.value = ''; category.value = ''; filter(); query.focus(); } );
		filter();
	}
	if ( document.readyState === 'loading' ) { document.addEventListener( 'DOMContentLoaded', start ); } else { start(); }
} )();
