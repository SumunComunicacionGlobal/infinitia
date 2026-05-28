<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package infinitia
 */

if ( ! function_exists( 'smn_support' ) ) :

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 *
	 * @return void
	 */
	function smn_support() {

		// Add support for block styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style-editor.css' );
		add_editor_style( 'style.css' );

		// Add support for excerpts in pages.
		add_post_type_support( 'page', 'excerpt' );

		// To use your template part inside your theme’s create a .html in /parts
		// and then put the php function "block_template_part( 'part-name' );" where you want to call it.
		// You can also create a template like page.html in /templates. And insert a part inside it: <!-- wp:template-part {"slug":"part-name"} /-->
		add_theme_support( 'block-template-parts' );

	}

endif;

add_action( 'after_setup_theme', 'smn_support' );

/**
 * Match front-end content width in editor for selected post types only.
 *
 * @param array                 $editor_settings Default editor settings.
 * @param WP_Block_Editor_Context $editor_context Editor context.
 * @return array
 */
function smn_editor_content_width_by_post_type( $editor_settings, $editor_context ) {
	$allowed_post_types = array( 'post', 'casosdeexito', 'ensayo' );

	if ( empty( $editor_context->post ) || empty( $editor_context->post->post_type ) ) {
		return $editor_settings;
	}

	if ( ! in_array( $editor_context->post->post_type, $allowed_post_types, true ) ) {
		return $editor_settings;
	}

	$editor_css = '
		.editor-styles-wrapper {
			--smn-editor-content-width: 960px;
			--smn-editor-wide-width: 64rem;
		}

		.editor-styles-wrapper .is-root-container.is-layout-constrained > :where(:not([data-align="left"]):not([data-align="right"]):not([data-align="full"]):not([data-align="wide"])) {
			max-width: var(--smn-editor-content-width);
			margin-left: auto;
			margin-right: auto;
		}

		.editor-styles-wrapper .is-root-container > [data-align="wide"] {
			max-width: var(--smn-editor-wide-width);
			margin-left: auto;
			margin-right: auto;
		}

		.editor-styles-wrapper .is-root-container > [data-align="full"] {
			max-width: none;
		}
	';

	if ( empty( $editor_settings['styles'] ) || ! is_array( $editor_settings['styles'] ) ) {
		$editor_settings['styles'] = array();
	}

	$editor_settings['styles'][] = array(
		'css' => $editor_css,
	);

	return $editor_settings;
}
add_filter( 'block_editor_settings_all', 'smn_editor_content_width_by_post_type', 10, 2 );


/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function smn_hybrid_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'smn_hybrid_body_classes' );

/**
 * Show language code (ES, EN, etc.) for language switcher items in classic menus.
 *
 * @param array    $items Menu items.
 * @param stdClass $args  Menu arguments.
 * @return array
 */
function smn_nav_menu_language_code_labels( $items, $args ) {
	foreach ( $items as $item ) {
		if ( empty( $item->classes ) || ! is_array( $item->classes ) ) {
			continue;
		}

		$language_slug = '';

		foreach ( $item->classes as $class_name ) {
			if ( ! is_string( $class_name ) ) {
				continue;
			}

			if ( preg_match( '/^lang-item-([a-z]{2}(?:-[a-z]{2})?)$/i', $class_name, $matches ) ) {
				$language_slug = strtolower( $matches[1] );
				break;
			}
		}

		if ( empty( $language_slug ) ) {
			continue;
		}

		$item->title = strtoupper( substr( $language_slug, 0, 2 ) );
	}

	return $items;
}
add_filter( 'wp_nav_menu_objects', 'smn_nav_menu_language_code_labels', 20, 2 );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function smn_hybrid_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'smn_hybrid_pingback_header' );
