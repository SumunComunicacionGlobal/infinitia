
wp.domReady( () => {
	wp.blocks.registerBlockStyle( 'core/list', [
		{
			name: 'default',
			label: 'Default',
			isDefault: true,
		},
		{
			name: 'separator-list',
			label: 'Separador',
		},
		{
			name: 'arrow-list',
			label: 'Flecha simple',
		},
		{
			name: 'arrow-separator-list',
			label: 'Flecha con separador',
		},
		{
			name: 'check-separator-list',
			label: 'Check con separador',
		},

		{
			name: 'check-list',
			label: 'Check simple',
		},
	] );
	wp.blocks.registerBlockStyle( 'core/button', [
		{
			name: 'with-arrow',
			label: 'Con Flecha',
		},
		{
			name: 'with-chat',
			label: 'Con Chat',
		},
		{
			name: 'outline-with-arrow',
			label: 'Contorno con Flecha',
		},
	] );
	wp.blocks.registerBlockStyle( 'core/paragraph', [
		{
			name: 'default',
			label: 'Default',
			isDefault: true,
		},
		{
			name: 'title-has-image',
			label: 'Imagen integrada',
		},
	] );

	wp.blocks.registerBlockStyle( 'core/cover', [
		{
			name: 'cover-contain-background',
			label: 'Contain Background',
		},
	] );

	wp.blocks.registerBlockStyle( 'core/quote', [
		{
			name: 'quote-card',
			label: 'Card',
		},
	] );

	wp.blocks.registerBlockStyle( 'core/query', [
		{
			name: 'is-related-posts',
			label: 'Related by category',
		},
	] );

	wp.blocks.registerBlockStyle( 'core/columns', [
		{
			name: 'margin-gap--vertical',
			label: 'Gap Wide (80px)',
		},
	] );

	wp.blocks.registerBlockStyle( 'core/group', [
		{
			name: 'group-horizontal-scroll',
			label: 'Horizontal scroll',
		},
		{
			name: 'group-horizontal-scroll-btns',
			label: 'Horizontal scroll with buttons',
		},
		{
			name: 'margin-vertical',
			label: 'Margin vertical (top & bottom 112px)',
		},
		{
			name: 'margin-vertical--medium',
			label: 'Margin vertical (top & bottom 64px)',
		},
		{
			name: 'margin-vertical--top',
			label: 'Margin Top vertical (top 112px)',
		},
		{
			name: 'margin-vertical--top--medium',
			label: 'Margin Top vertical (top 64px)',
		},
		{
			name: 'margin-vertical--bottom',
			label: 'Margin Bottom vertical (bottom 112px)',
		},
		{
			name: 'margin-vertical--bottom--medium',
			label: 'Margin Bottom vertical (bottom 64px)',
		},
		{
			name: 'margin-gap--vertical',
			label: 'Gap vertical (64px)',
		}
	] );
	
	wp.blocks.registerBlockVariation( 'core/group', [
		{
			name: 'group-horizontal-scroll',
			title: 'Horizontal scroll group',
			icon: 'leftright',
			description: 'Items in Horizontal scroll',
			isDefault: false,
			atttibutes: {
				className: 'is-style-group-horizontal-scroll',
			},
		},
	] );
	wp.blocks.registerBlockVariation( 'core/group', [
		{
			name: 'group-horizontal-scroll-btns',
			title: 'Horizontal scroll group with buttons',
			icon: 'sort',
			description: 'Items in Horizontal scroll with buttons',
			isDefault: false,
			atttibutes: {
				className: 'is-style-group-horizontal-scroll--btns',
			},
		},
	] );
} );

/* ADD data space height to wp-block-spacer in gutenberg */
wp.data.subscribe( function() {
	setTimeout( function() {
		const spacerBlocks = document.querySelectorAll( '.wp-block.wp-block-spacer' );

		for ( let item = 0; item < spacerBlocks.length; item++ ) {
			const block = spacerBlocks[ item ];

			const style = getComputedStyle( block ),
				height = parseInt( style.height ) || 0;

			block.querySelector( '.components-resizable-box__container' ).setAttribute( 'data-spaceheight', height + 'px' );
		}
	}, 1 );
} );

/* ADD data-hidden-class attribute to elements with hidden-* classes in the editor */
wp.data.subscribe(function() {
	setTimeout(function() {
		const hiddenBlocks = document.querySelectorAll('[class*="hidden-"]');
		hiddenBlocks.forEach(function(block) {
			const hiddenClasses = Array.from(block.classList).filter(function(cls) {
				return cls.startsWith('hidden-');
			});
			if (hiddenClasses.length > 0) {
				block.setAttribute('data-hidden-class', hiddenClasses.join(' '));
			}
		});
	}, 1);
});

