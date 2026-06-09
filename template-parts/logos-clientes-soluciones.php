<?php
/**
 * Template part para el shortcode [clientes_soluciones].
 *
 * Muestra logos del CPT "cliente" filtrados por la taxonomía "sector"
 * asignada al contenido actual donde se inserta el shortcode.
 */

$context_post_id = (int) get_the_ID();

if ( $context_post_id <= 0 ) {
	$context_post_id = (int) get_queried_object_id();
}

$sector_term_ids = array();

if ( $context_post_id > 0 ) {
	$sector_terms = get_the_terms( $context_post_id, 'sector' );

	if ( ! empty( $sector_terms ) && ! is_wp_error( $sector_terms ) ) {
		$sector_term_ids = array_values(
			array_unique(
				array_map(
					static function ( $term ) {
						return (int) $term->term_id;
					},
					$sector_terms
				)
			)
		);
	}
}

$clientes_query_args = array(
	'post_type'           => 'cliente',
	'post_status'         => 'publish',
	'posts_per_page'      => -1,
	'orderby'             => 'title',
	'order'               => 'ASC',
	'no_found_rows'       => true,
	'ignore_sticky_posts' => true,
);

if ( ! empty( $sector_term_ids ) ) {
	$clientes_query_args['tax_query'] = array(
		array(
			'taxonomy' => 'sector',
			'field'    => 'term_id',
			'terms'    => $sector_term_ids,
			'operator' => 'IN',
		),
	);
} else {
	// Si no hay sectores en el contenido actual, no se muestran clientes.
	$clientes_query_args['post__in'] = array( 0 );
}

$clientes_query = new WP_Query( $clientes_query_args );
?>

<div class="smn-clientes-shortcode">
	<?php if ( $clientes_query->have_posts() ) : ?>
		<div class="smn-clientes-logos facetwp-template">
			<?php
			while ( $clientes_query->have_posts() ) :
				$clientes_query->the_post();
				$cliente_link = '';

				if ( function_exists( 'get_field' ) ) {
					$cliente_link = get_field( 'link_cliente' );
				}

				if ( empty( $cliente_link ) ) {
					$cliente_link = get_post_meta( get_the_ID(), 'link_cliente', true );
				}

				if ( ! has_post_thumbnail() ) {
					continue;
				}
				?>
				<div class="smn-clientes-logos__item cliente-<?php the_ID(); ?>">
					<?php if ( ! empty( $cliente_link ) ) : ?>
						<a href="<?php echo esc_url( $cliente_link ); ?>" target="_blank" rel="nofollow noopener">
					<?php endif; ?>

					<?php
					echo get_the_post_thumbnail(
						get_the_ID(),
						'full',
						array(
							'class'    => 'smn-clientes-logos__img',
							'loading'  => 'lazy',
							'decoding' => 'async',
						)
					);
					?>

					<?php if ( ! empty( $cliente_link ) ) : ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endwhile; ?>
		</div>
		<?php wp_reset_postdata(); ?>
        <div class="wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex smn-clientes-logos__actions">
			<div class="wp-block-button">
				<button
					type="button"
					class="wp-block-button__link wp-element-button smn-clientes-logos__toggle"
					data-label-more="<?php echo esc_attr__( 'Mostrar todos', 'infinitia' ); ?>"
					data-label-less="<?php echo esc_attr__( 'Mostrar menos', 'infinitia' ); ?>"
					aria-expanded="false"
				>
					<?php echo esc_html__( 'Mostrar todos', 'infinitia' ); ?>
				</button>
			</div>
		</div>
	<?php endif; ?>
</div>
