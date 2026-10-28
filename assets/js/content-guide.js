/* Select a guided section in WordPress; no content writes or custom editor. */
( function () {
	'use strict';
	wp.domReady( function () {
		if ( unappContentGuide.returnLinks && wp.data.dispatch( 'core/notices' ) ) {
			var actions = unappContentGuide.returnLinks.map( function ( link ) {
				return { label: link.label, onClick: function () {
					var editor = wp.data.select( 'core/editor' );
					if ( editor && ( editor.isEditedPostDirty() || editor.isSavingPost() ) ) {
						wp.data.dispatch( 'core/notices' ).createWarningNotice( unappContentGuide.saveMessage, { id: 'unapp-guide-save-first' } );
						return;
					}
					window.location.assign( link.url );
				} };
			} );
			wp.data.dispatch( 'core/notices' ).createInfoNotice( unappContentGuide.returnMessage, { id: 'unapp-guide-return', actions: actions, isDismissible: true } );
		}
		var unsubscribe;
		var finished = false;
		function selectSection() {
			if ( finished ) { return; }
			var blocks = wp.data.select( 'core/block-editor' ).getBlocks();
			if ( ! blocks.length ) { return; }
			var section = blocks.find( function ( block ) { return block.attributes.metadata && block.attributes.metadata.unappSection === unappContentGuide.section; } ) || blocks[ unappContentGuide.root ];
			if ( ! section ) { return; }
			finished = true;
			if ( unsubscribe ) { unsubscribe(); }
			function editable( block ) {
				var types = unappContentGuide.focus === 'image' ? [ 'core/image', 'core/cover' ] : [ 'core/heading', 'core/paragraph', 'core/image' ];
				if ( types.indexOf( block.name ) !== -1 ) { return block; }
				for ( var child of block.innerBlocks ) { var found = editable( child ); if ( found ) { return found; } }
			}
			var image = section;
			if ( typeof unappContentGuide.imagePath === 'string' ) {
				var path = unappContentGuide.imagePath === '' ? [] : unappContentGuide.imagePath.split( '.' );
				for ( var index of path ) { image = image && image.innerBlocks[ Number( index ) ]; }
				if ( ! image || [ 'core/image', 'core/cover' ].indexOf( image.name ) === -1 ) { image = null; }
			} else { image = null; }
			var target = image || editable( section ) || section;
			wp.data.dispatch( 'core/block-editor' ).selectBlock( target.clientId );
			var editor = wp.data.dispatch( 'core/edit-post' );
			if ( editor && editor.setIsListViewOpened ) { editor.setIsListViewOpened( true ); }
		}
		unsubscribe = wp.data.subscribe( selectSection );
		selectSection();
	} );
} )();
