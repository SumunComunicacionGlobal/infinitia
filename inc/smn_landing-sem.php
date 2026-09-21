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
 */
function smn_landing_remove_scripts() {

	if ( ! is_singular( 'landing' ) ) {
		return;
	}

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

	foreach ( $scripts as $handle ) {
		wp_dequeue_script( $handle );
		wp_deregister_script( $handle );
	}

}

add_action(
	'wp_enqueue_scripts',
	'smn_landing_remove_scripts',
	100
);