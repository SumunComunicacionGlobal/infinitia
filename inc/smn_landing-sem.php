<?php
/**
 * Landing functionality.
 *
 * @package infinitia
 */


/**
 * Landing editor template.
 *
 * Defines the initial block structure when creating
 * a new Landing post.
 *
 * @param array  $args      Post type arguments.
 * @param string $post_type Post type name.
 * @return array
 */
function smn_landing_editor_template( $args, $post_type ) {

	if ( 'landing' !== $post_type ) {
		return $args;
	}

	$args['template'] = array(

		array(
			'core/pattern',
			array(
				'slug' => 'infinitia/landing-sem',
			),
		),

	);

	return $args;
}

add_filter(
	'register_post_type_args',
	'smn_landing_editor_template',
	10,
	2
);


/**
 * Remove unnecessary scripts from Landing pages.
 * 
 * Allowed scripts for landings:
 * - Contact Form 7 and its dependencies (jQuery is allowed when CF7 is present)
 * - International Telephone Input for Contact Form 7
 * - Safe SVG
 */
function smn_landing_remove_scripts() {

	if ( ! is_singular( 'landing' ) ) {
		return;
	}

	// Use global $post or get_post
	global $post;
	if ( ! isset( $post ) || ! $post ) {
		$post = get_post();
	}
	
	if ( ! $post ) {
		echo "<!-- SMN Landing: ERROR - No post object available -->\n";
		return;
	}
	
	$post_content = $post->post_content;
	
	// Check if Contact Form 7 is present in multiple ways
	$has_cf7 = false;
	
	// Method 1: Check for contact-form-7 shortcode
	if ( has_shortcode( $post_content, 'contact-form-7' ) ) {
		$has_cf7 = true;
	}
	
	// Method 2: Check if wpcf7 class is present (embedded blocks)
	if ( strpos( $post_content, 'wpcf7' ) !== false ) {
		$has_cf7 = true;
	}
	
	// Method 3: Check for contact-form-7 as reusable block
	if ( strpos( $post_content, 'contact-form-7' ) !== false ) {
		$has_cf7 = true;
	}
	
	// Debug logging - both file and HTML comment
	$debug_msg = 'Landing: ' . $post->post_name . ' | CF7: ' . ( $has_cf7 ? 'YES' : 'NO' ) . ' | Len: ' . strlen( $post_content );
	
	if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
		error_log( 'SMN ' . $debug_msg );
	}
	
	// Add HTML comment for debugging in source
	echo "<!-- SMN Landing Debug: $debug_msg -->\n";

	// Scripts to always remove from landings
	$scripts_to_remove = array(
		'infinitia-navigation',
		'infinitia-js',
		'gsap-js2',
		'gsap-st',
		'gsap-js',
	);
	
	// Only remove jQuery if no CF7 form is present
	if ( ! $has_cf7 ) {
		$scripts_to_remove[] = 'jquery';
		$scripts_to_remove[] = 'jquery-core';
		$scripts_to_remove[] = 'jquery-migrate';
		echo "<!-- SMN: Removing jQuery (no CF7) -->\n";
	} else {
		echo "<!-- SMN: KEEPING jQuery (CF7 detected) -->\n";
	}

	foreach ( $scripts_to_remove as $handle ) {
		wp_dequeue_script( $handle );
		wp_deregister_script( $handle );
	}

}

add_action(
	'wp_enqueue_scripts',
	'smn_landing_remove_scripts',
	20
);