<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package infinitia
 */

?>

	<footer id="colophon" class="site-footer">

		<?php
			$block = get_page_by_title( 'Footer Global', OBJECT, 'wp_block' );

			if ( $block ) {
				echo apply_filters( 'the_content', $block->post_content );
			}
		?>

	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
