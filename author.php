<?php
/**
 * The template for displaying author pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package infinitia
 */

get_header();
?>

	<main id="primary" class="site-main">

	 	<?php smn_render_reusable_block_by_title( 'Hero Author' ); ?>

		<div class="wp-block-query entry-content wp-block-post-content has-global-padding is-layout-constrained">
			<div class="wp-block-group is-style-margin-vertical">
			<?php
			$author_id = (int) get_queried_object_id();

			if ( function_exists( 'get_field' ) && $author_id > 0 ) {
				$acf_user_key = 'user_' . $author_id;
				$cargo_author = get_field( 'cargo_author', $acf_user_key );
				$descripcion_author = get_field( 'descripcion_author', $acf_user_key );
				$linkedin_author = get_field( 'linkedin_author', $acf_user_key );

				if ( ! empty( $cargo_author ) ) {
					echo '<div class="author-meta author-meta--cargo">' . wp_kses_post( $cargo_author ) . '</div>';
				}

				if ( ! empty( $descripcion_author ) ) {
					echo '<div class="author-meta author-meta--descripcion">' . wp_kses_post( $descripcion_author ) . '</div>';
				}

				if ( ! empty( $linkedin_author ) ) {
					echo '<div class="author-meta author-meta--linkedin">';
					echo '<a href="' . esc_url( $linkedin_author ) . '" target="_blank" rel="noopener noreferrer">';
					echo '<span class="author-meta__linkedin-label">LinkedIn</span>';
					echo '</a>';
					echo '</div>';
				}
			}
			?>
            
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php smn_render_reusable_block_by_title( 'Loop Blog' ); ?>
				</article>
			</div>
		</div><!-- .entry-content -->

	</main><!-- #main -->

<?php
get_footer();
