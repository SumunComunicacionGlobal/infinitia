<?php
/**
 * Template part para el shortcode [clientes].
 *
 * Muestra logos del CPT "cliente" y delega el filtrado a FacetWP.
 */

$clientes_query = new WP_Query(
	array(
		'post_type'           => 'cliente',
		'post_status'         => 'publish',
		'posts_per_page'      => -1,
		'no_found_rows'       => true,
		'ignore_sticky_posts' => true,
		'facetwp'             => true,
	)
);
?>

<?php if (function_exists('facetwp_display')) : ?>
	<?php echo facetwp_display( 'facet', 'sectores' ); ?>
<?php endif; ?>

<div class="smn-clientes-shortcode">
	<?php if ($clientes_query->have_posts()) : ?>
		<div class="smn-clientes-logos facetwp-template">
			<?php
			while ($clientes_query->have_posts()) :
				$clientes_query->the_post();
				$cliente_link = '';

				if (function_exists('get_field')) {
					$cliente_link = get_field('link_cliente');
				}

				if (empty($cliente_link)) {
					$cliente_link = get_post_meta(get_the_ID(), 'link_cliente', true);
				}

				if (! has_post_thumbnail()) {
					continue;
				}
				?>
				<div class="smn-clientes-logos__item cliente-<?php the_ID(); ?>">
					<?php if (! empty($cliente_link)) : ?>
						<a href="<?php echo esc_url($cliente_link); ?>" target="_blank" rel="nofollow noopener">
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

					<?php if (! empty($cliente_link)) : ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endwhile; ?>
		</div>
		<?php wp_reset_postdata(); ?>
	<?php endif; ?>
</div>
