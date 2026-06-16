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

	<?php smn_render_reusable_block_by_title( 'CTA Form' ); ?>

<?php
get_footer();
