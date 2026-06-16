<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package infinitia
 */

get_header();
?>

	<main id="primary" class="site-main">

	 	<?php smn_render_reusable_block_by_title( 'Hero Archive' ); ?>

		<div class="entry-content wp-block-post-content has-global-padding is-layout-constrained">
			<div class="wp-block-group is-style-margin-vertical">
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php smn_render_reusable_block_by_title( 'Loop Blog' ); ?>
				</article>
			</div>
		</div><!-- .entry-content -->

	</main><!-- #main -->

<?php
get_footer();
