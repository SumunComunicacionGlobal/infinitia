<?php
/**
 * Enqueue scripts
 */

 function smn_scripts() {

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'infinitia-js', get_template_directory_uri() . '/assets/js/infinitia.js', array(), true );

	$sector_icons = array();
	$sector_terms = get_terms(
		array(
			'taxonomy'   => 'sector',
			'hide_empty' => false,
		)
	);

	if ( ! is_wp_error( $sector_terms ) && ! empty( $sector_terms ) ) {
		foreach ( $sector_terms as $term ) {
			$icon_url = '';

			if ( function_exists( 'get_field' ) ) {
				$acf_icon = get_field( 'icon-sector', $term->taxonomy . '_' . $term->term_id );

				if ( is_array( $acf_icon ) ) {
					if ( ! empty( $acf_icon['url'] ) ) {
						$icon_url = (string) $acf_icon['url'];
					} elseif ( ! empty( $acf_icon['ID'] ) ) {
						$icon_url = wp_get_attachment_url( (int) $acf_icon['ID'] );
					} elseif ( ! empty( $acf_icon['id'] ) ) {
						$icon_url = wp_get_attachment_url( (int) $acf_icon['id'] );
					}
				} elseif ( is_numeric( $acf_icon ) ) {
					$icon_url = wp_get_attachment_url( (int) $acf_icon );
				} elseif ( is_string( $acf_icon ) ) {
					$icon_url = $acf_icon;
				}
			}

			if ( empty( $icon_url ) ) {
				$meta_icon = get_term_meta( (int) $term->term_id, 'icon-sector', true );

				if ( is_numeric( $meta_icon ) ) {
					$icon_url = wp_get_attachment_url( (int) $meta_icon );
				} elseif ( is_string( $meta_icon ) ) {
					$icon_url = $meta_icon;
				}
			}

			if ( ! empty( $icon_url ) && ! empty( $term->slug ) ) {
				$sector_icons[ (string) $term->slug ] = esc_url_raw( (string) $icon_url );
			}
		}
	}
	
	// Localizar variables para JavaScript
	wp_localize_script( 'infinitia-js', 'themeData', array(
		'themeUrl'    => get_template_directory_uri(),
		'sectorIcons' => $sector_icons,
	));
	
	wp_enqueue_script( 'infinitia-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), _S_VERSION, true );
	

	if ( has_block( 'cb/carousel' ) ) {
        wp_enqueue_style( 'slick-css', get_template_directory_uri() . '/assets/slick/slick.min.css' );
        wp_enqueue_script( 'slick-js', get_template_directory_uri() . '/assets/slick/slick.min.js', array('jquery'), null, true );
        wp_enqueue_script( 'slick-init-js', get_template_directory_uri() . '/assets/slick/init.js', array('jquery'), null, true );
    }

}
add_action( 'wp_enqueue_scripts', 'smn_scripts' );

/** 
* Gutenberg scripts
*/
function smn_gutenberg_scripts() {

	wp_enqueue_script(
		'be-editor', 
		get_stylesheet_directory_uri() . '/assets/js/editor.js', 
		array( 'wp-blocks', 'wp-dom', 'wp-dom-ready', 'wp-edit-post' ), 
		filemtime( get_stylesheet_directory() . '/assets/js/editor.js' ),
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'smn_gutenberg_scripts' );

/**
 * GSAP script in WordPress
*/
// wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
function theme_gsap_script(){
    // The core GSAP library
    wp_enqueue_script( 'gsap-js', 'https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js', array(), false, true );
    // ScrollTrigger - with gsap.js passed as a dependency
    wp_enqueue_script( 'gsap-st', 'https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/ScrollTrigger.min.js', array('gsap-js'), false, true );
    // Your animation code file - with gsap.js and gsap-st passed as a dependency
    wp_enqueue_script( 'gsap-js2', get_template_directory_uri() . '/assets/js/gsap.js', array('gsap-js', 'gsap-st'), false, true );
}

add_action( 'wp_enqueue_scripts', 'theme_gsap_script' );



