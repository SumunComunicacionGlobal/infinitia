<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package infinitia
 */

get_header();
?>


	<main id="primary" class="site-main">

	 	<?php smn_render_reusable_block_by_title( 'Hero Archive' ); ?>

		<div class="entry-content wp-block-post-content has-global-padding is-layout-constrained">
			<div class="wp-block-group is-style-margin-vertical">

			


				<?php if ( have_posts() ) : ?>

					<?php smn_render_reusable_block_by_title( 'Loop Default' );

					the_posts_navigation();

				else :

					get_template_part( 'template-parts/content', 'none' );

				endif;
				?>
			</div>
		</div><!-- .entry-content -->

	</main><!-- #main -->

	

<?php
get_footer();
