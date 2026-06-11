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

	 	<?php 
	 		$block = get_page_by_title( 'Hero Archive', OBJECT, 'wp_block' );
                if ( $block ) {
                    $block_content = apply_filters( 'the_content', $block->post_content );
                    echo $block_content;
                }
		?>

		<div class="entry-content wp-block-post-content has-global-padding is-layout-constrained">
			<div class="wp-block-group is-style-margin-vertical">
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php 
						$block = get_page_by_title( 'Loop Blog', OBJECT, 'wp_block' );
							if ( $block ) {
								$block_content = apply_filters( 'the_content', $block->post_content );
								echo $block_content;
							}
					?>
				</article>
			</div>
		</div><!-- .entry-content -->

	</main><!-- #main -->

<?php
get_footer();
