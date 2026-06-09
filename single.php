<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package infinitia
 */

get_header();
?>

	<main id="primary" class="site-main">
		
		<?php get_template_part( 'template-parts/hero' ); ?>

		<?php echo do_shortcode( '[toc]' ); ?>

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', get_post_type() ); ?>

			<div class="smn-casosdeexito-taxonomias wp-block-group has-global-padding is-layout-constrained is-style-margin-vertical--medium">
				<?php smn_hybrid_entry_categories_casosdeexito(); ?>
			</div>

			

			<div class="has-global-padding is-layout-constrained">
				<?php the_post_navigation(
					array(
						'prev_text' => '<div class="nav-subtitle">' . esc_html__( 'Anterior:', 'infinitia' ) . '</div> <div class="nav-title">%title</div>',
						'next_text' => '<div class="nav-subtitle">' . esc_html__( 'Siguiente:', 'infinitia' ) . '</div> <div class="nav-title">%title</div>',
					)
				); ?>
			</div>

		<?php
		
		endwhile; // End of the loop.

			get_template_part( 'template-parts/related', get_post_type() );
		?>

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

