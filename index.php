<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package infinitia
 */

get_header();
?>
	<main id="primary" class="site-main">

		<?php smn_render_reusable_block_by_title( 'Hero Blog' ); ?>
		
		<div class="wp-block-query entry-content wp-block-post-content has-global-padding is-layout-constrained facetwp-template">
			<div class="wp-block-group is-style-margin-vertical">
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php
						$block = smn_get_reusable_block_by_title( 'Loop Blog' );
						if ( $block ) {
							$block_content = do_blocks( $block->post_content );
							echo $block_content;
						}
					?>
				</article>
			</div>
		</div><!-- .entry-content -->

	</main><!-- #main -->

<?php
get_footer();



