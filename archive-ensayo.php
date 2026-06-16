<?php
/**
 * The template for displaying archive Análisis y Ensayos
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package infinitia
 */

get_header();
?>

	<main id="primary" class="site-main">

	 	<?php smn_render_reusable_block_by_title( 'Hero Ensayos' ); ?>

        <?php get_template_part( 'template-parts/filter-by' ); ?>

		<div class="entry-content wp-block-post-content has-global-padding is-layout-constrained">
			<div class="wp-block-group">
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php smn_render_reusable_block_by_title( 'Loop Default' ); ?>
				</article>
			</div>
		</div><!-- .entry-content -->

	</main><!-- #main -->

    <?php smn_render_reusable_block_by_title( 'CTA Form' ); ?>

<?php
get_footer();
