<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package infinitia
 */

if ( ! function_exists( 'smn_hybrid_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function smn_hybrid_posted_on() {
		$day = get_the_date('j'); // Día sin ceros a la izquierda
		$month_year = get_the_date('F Y'); // Mes completo + año
		
		$time_string = '<time class="entry-date published updated" datetime="%1$s"><span class="day">%2$s</span>%3$s</time>';

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( $day ),
			esc_html( $month_year )
		);

		echo '<div class="posted-on">' . $time_string . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'smn_hybrid_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function smn_hybrid_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'infinitia' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'smn_hybrid_entry_categories' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function smn_hybrid_entry_categories() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( '', 'infinitia' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<div class="cat-links">' . esc_html__( '%1$s', 'infinitia' ) . '</div>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}
endif;

if ( ! function_exists( 'smn_hybrid_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function smn_hybrid_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'infinitia' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'infinitia' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'infinitia' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'infinitia' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'infinitia' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'infinitia' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'smn_hybrid_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function smn_hybrid_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
					the_post_thumbnail(
						'post-thumbnail',
						array(
							'alt' => the_title_attribute(
								array(
									'echo' => false,
								)
							),
						)
					);
				?>
			</a>

			<?php
		endif; // End is_singular().
	}
endif;

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
endif;


if ( ! function_exists( 'smn_hybrid_entry_categories_casosdeexito' ) ) :
	/**
	 * Muestra términos de las taxonomías 'casos-de-exito-category' y 'materiales' para el post actual.
	 */
	function smn_hybrid_entry_categories_casosdeexito() {
		$taxos = array(
			'casos-de-exito-category' => __( 'Categoría', 'infinitia' ),
			'materiales' => __( 'Materiales', 'infinitia' ),
		);
		   foreach ( $taxos as $tax_slug => $tax_label ) {
			   $terms = get_the_terms( get_the_ID(), $tax_slug );
			   if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				   echo '<div class="smn-casosdeexito-taxo smn-casosdeexito-taxo--' . esc_attr( $tax_slug ) . '">';
				   foreach ( $terms as $term ) {
					   $class = $tax_slug === 'materiales' ? 'tag' : 'tag-category';
					   echo '<span class="' . esc_attr( $class ) . '">' . esc_html( $term->name ) . '</span> ';
				   }
				   echo '</div>';
			   }
		   }
	}
endif;