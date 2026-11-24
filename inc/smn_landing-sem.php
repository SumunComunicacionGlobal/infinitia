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

	// Check if Contact Form 7 is present
	if ( has_shortcode( get_post()->post_content, 'contact-form-7' ) ) {
		// Keep jQuery and other CF7 dependencies
		$scripts = array(
			'infinitia-navigation',
			'infinitia-js',
			'gsap-js2',
			'gsap-st',
			'gsap-js',
		);
		
		// Explicitly enqueue the International Telephone Input scripts if the plugin is active
		if ( function_exists( 'wpcf7it_plugin_activation' ) || class_exists( 'WPCF7_Intl_Tel' ) ) {
			wp_enqueue_script( 'wpcf7-intl-tel-lib' );
			wp_enqueue_script( 'wpcf7-intl-tel' );
		}
	} else {
		// Remove all unnecessary scripts
		$scripts = array(
			'infinitia-navigation',
			'infinitia-js',
			'gsap-js2',
			'gsap-st',
			'gsap-js',
	        'jquery',
			'jquery-core',
			'jquery-migrate',
		);
	}

	foreach ( $scripts as $handle ) {
		wp_dequeue_script( $handle );
		wp_deregister_script( $handle );
	}

}

add_action(
	'wp_enqueue_scripts',
	'smn_landing_remove_scripts',
	20
);