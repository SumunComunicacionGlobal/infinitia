<?php
/**
 * The template for displaying all single equipo posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package infinitia
 */

get_header();
?>

	<main id="primary" class="site-main">
		
		<?php smn_render_reusable_block_by_title( 'Hero Equipo' ); ?>

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', get_post_type() ); ?>

		<?php
		
		endwhile; // End of the loop.

			get_template_part( 'template-parts/related', get_post_type() );
		?>

	</main><!-- #main -->

	<?php smn_render_reusable_block_by_title( 'CTA Form' ); ?>

<?php
get_footer();

