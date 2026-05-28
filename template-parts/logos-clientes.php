<?php
/**
 * Template part para el shortcode [clientes].
 *
 * Muestra:
 * 1) Lista de términos de la taxonomía "sector" con icono ACF (icon-sector).
 * 2) Loop de imágenes destacadas del CPT "clientes".
 */

$taxonomy = 'sector';

$terms = get_terms(
	array(
		'taxonomy'   => $taxonomy,
		'hide_empty' => false,
	)
);

$clientes_query = new WP_Query(
	array(
		'post_type'           => 'cliente',
		'post_status'         => 'publish',
		'posts_per_page'      => -1,
		'no_found_rows'       => true,
		'ignore_sticky_posts' => true,
	)
);
?>

<div class="smn-clientes-shortcode">
	<?php if (! is_wp_error($terms) && ! empty($terms)) : ?>
		<ul class="smn-sectores-list is-style-group-horizontal-scroll-btns">
			<?php foreach ($terms as $term) : ?>
				<?php
				$term_link = get_term_link($term);
				$icon_url  = '';

				if (function_exists('get_field')) {
					$icon_url = get_field('icon-sector', $taxonomy . '_' . $term->term_id);
				}

				if (empty($icon_url)) {
					$icon_url = get_term_meta($term->term_id, 'icon-sector', true);
				}
				?>
				<li class="smn-sectores-list__item term-<?php echo esc_attr($term->term_id); ?>">
					<?php if (! is_wp_error($term_link) && ! empty($term_link)) : ?>
						<a class="smn-sectores-list__link" href="<?php echo esc_url($term_link); ?>">
							<?php if (! empty($icon_url)) : ?>
								<img class="smn-sectores-list__icon" src="<?php echo esc_url($icon_url); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async" />
							<?php endif; ?>
							<span class="smn-sectores-list__name"><?php echo esc_html($term->name); ?></span>
						</a>
					<?php else : ?>
						<span class="smn-sectores-list__text">
							<?php if (! empty($icon_url)) : ?>
								<img class="smn-sectores-list__icon" src="<?php echo esc_url($icon_url); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async" />
							<?php endif; ?>
							<span class="smn-sectores-list__name"><?php echo esc_html($term->name); ?></span>
						</span>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<?php if ($clientes_query->have_posts()) : ?>
		<div class="smn-clientes-logos">
			<?php
			while ($clientes_query->have_posts()) :
				$clientes_query->the_post();
				if (! has_post_thumbnail()) {
					continue;
				}
				?>
				<div class="smn-clientes-logos__item cliente-<?php the_ID(); ?>">
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
				</div>
			<?php endwhile; ?>
		</div>
		<?php wp_reset_postdata(); ?>
	<?php endif; ?>
</div>
