<?php
/**
 * infinitia functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package infinitia
 */



if ( ! function_exists( 'smn_styles' ) ) :

	/**
	 * Enqueue styles.
	 *
	 * @return void
	 */
	function smn_styles() {
		// Register theme stylesheet.
		$theme_version = wp_get_theme()->get( 'Version' );

		$version_string = is_string( $theme_version ) ? $theme_version : false;
		wp_register_style(
			'smn-style',
			get_template_directory_uri() . '/style.css',
			array(),
			$version_string
		);

		// Enqueue theme stylesheet.
		wp_enqueue_style( 'smn-style' );
	}

endif;

add_action( 'wp_enqueue_scripts', 'smn_styles' );



// Enqueue scripts
require get_template_directory() . '/inc/smn_enqueue-scripts.php';

/**
 * Implement the theme support features.
 */
require get_template_directory() . '/inc/smn_theme-support.php';

// Register blocks
require get_template_directory() . '/inc/smn_register-blocks.php';

// Shortcodes
require get_template_directory() . '/inc/smn_shortcodes.php';

// Hooks
require get_template_directory() . '/inc/smn_hooks.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Register widget area.
 */
require get_template_directory() . '/inc/smn_widget-area.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Custom walkers for nav menus.
 */
require get_template_directory() . '/inc/class-smn-walker-mega-menu-groups.php';



/**
 * Obtiene un bloque reusable por titulo y lo traduce al idioma actual si Polylang esta activo.
 *
 * @param string $title Titulo del bloque reusable (wp_block) en idioma base.
 * @return WP_Post|null
 */
function smn_get_reusable_block_by_title( $title ) {
	if ( empty( $title ) ) {
		return null;
	}

	$block = get_page_by_title( $title, OBJECT, 'wp_block' );

	if ( ! ( $block instanceof WP_Post ) ) {
		return null;
	}

	if ( function_exists( 'pll_get_post' ) ) {
		$translated_block_id = 0;

		if ( function_exists( 'pll_current_language' ) ) {
			$translated_block_id = (int) pll_get_post( (int) $block->ID, pll_current_language( 'slug' ) );
		} else {
			$translated_block_id = (int) pll_get_post( (int) $block->ID );
		}

		if ( $translated_block_id > 0 && $translated_block_id !== (int) $block->ID ) {
			$translated_block = get_post( $translated_block_id );

			if ( $translated_block instanceof WP_Post && 'wp_block' === $translated_block->post_type ) {
				$block = $translated_block;
			}
		}
	}

	return $block;
}

/**
 * Renderiza un bloque reusable por titulo, respetando la traduccion activa en Polylang.
 *
 * @param string $title Titulo del bloque reusable (wp_block) en idioma base.
 * @return void
 */
function smn_render_reusable_block_by_title( $title ) {
	$block = smn_get_reusable_block_by_title( $title );

	if ( ! $block ) {
		return;
	}

	echo apply_filters( 'the_content', $block->post_content );
}

/**
 * Filter block data to translate referenced post IDs in core/block blocks.
 */
add_filter("render_block_data", function ($block, $source_block) {
    if (
        isset($block["blockName"]) &&
        $block["blockName"] === "core/block" &&
        function_exists("pll_get_post") &&
        !empty($block["attrs"]["ref"])
    ) {
        $translated_ref = pll_get_post((int) $block["attrs"]["ref"]);
        if (!empty($translated_ref)) {
            $block["attrs"]["ref"] = (int) $translated_ref;
        }
    }
    return $block;
}, 10, 2);
