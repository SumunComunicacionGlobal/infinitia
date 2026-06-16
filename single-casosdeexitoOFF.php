<?php
/**
 * The template for displaying proyecto CTP pages
 *
 * This is the template that displays proyecto CTP pages by default.
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
		
		<?php get_template_part( 'template-parts/hero' ); ?>			
		
		<?php
		while ( have_posts() ) :
					
			the_post();

			get_template_part( 'template-parts/content', get_post_type() );

		endwhile; // End of the loop.
		?>

		<div class="smn-casosdeexito-taxonomias wp-block-group has-global-padding is-layout-constrained is-style-margin-vertical--medium">
			<?php smn_hybrid_entry_categories_casosdeexito(); ?>
		</div>

		<?php smn_render_reusable_block_by_title( 'CTA Form' ); ?>

	</main><!-- #main -->

<?php
get_footer();
