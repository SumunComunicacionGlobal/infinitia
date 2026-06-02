<?php
/**
 * Template Name: Soluciones
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package infinitia
 */

get_header();
?>

	<main id="primary" class="site-main">

        <?php get_template_part( 'template-parts/hero-soluciones' ); ?>

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );

		endwhile; // End of the loop.
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
