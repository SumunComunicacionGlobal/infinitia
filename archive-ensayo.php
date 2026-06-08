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

	 	<?php 
	 		$block = get_page_by_title( 'Hero Ensayos', OBJECT, 'wp_block' );
                if ( $block ) {
                    $block_content = apply_filters( 'the_content', $block->post_content );
                    echo $block_content;
                }
		?>

        <?php get_template_part( 'template-parts/filter-by' ); ?>

		<div class="wp-block-query entry-content wp-block-post-content has-global-padding is-layout-constrained">
			<div class="wp-block-group">
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php 
						$block = get_page_by_title( 'Loop Default', OBJECT, 'wp_block' );
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
		$block = get_page_by_title( 'CTA Form', OBJECT, 'wp_block' );
			if ( $block ) {
				$block_content = apply_filters( 'the_content', $block->post_content );
				echo $block_content;
			}
	?>

<?php
get_footer();
