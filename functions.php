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
 * Filter block data to translate referenced post IDs in core/block blocks.
 */
add_filter("render_block_data", function ($block, $source_block) {
    if ($block["blockName"] === "core/block") {
        if (function_exists("pll_get_post")) {
            $block["attrs"]["ref"] = pll_get_post($block["attrs"]["ref"]);
        }
    }
    return $block;
}, 10, 2);
