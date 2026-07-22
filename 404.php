<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package infinitia
 */

get_header();
?>

	<main id="primary" class="site-main">

		<section class="error-404 not-found entry-content wp-block-post-content has-global-padding is-layout-constrained wp-block-post-content-is-layout-constrained">

			<div class="page-content wp-block-group is-style-margin-vertical">
				<div class="wp-block-group is-style-margin-vertical--top section-caption is-layout-flex wp-container-core-group-is-layout-13c19379 wp-block-group-is-layout-flex">
					<div class="wp-block-safe-svg-svg-icon safe-svg-cover" style="text-align: left;">
						<div class="safe-svg-inside safe-svg-inline" style="width: 16px; height: 16px; background-color: var(--wp--preset--color--); color: var(--wp--preset--color--);">
							<!--?xml version="1.0" encoding="UTF-8"?-->
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="8" viewBox="0 0 16 8" fill="none" id="caption-title-icon">
								<path d="M4.00029 8C6.20958 8 8.00057 6.20914 8.00057 4C8.00057 1.79086 6.20958 0 4.00029 0C1.79099 0 0 1.79086 0 4C0 6.20914 1.79099 8 4.00029 8Z" fill="#088988"></path>
								<path d="M11.9997 8C14.209 8 16 6.20914 16 4C16 1.79086 14.209 0 11.9997 0C9.79042 0 7.99943 1.79086 7.99943 4C7.99943 6.20914 9.79042 8 11.9997 8Z" fill="#088988"></path>
							</svg>
						</div>
					</div>
					<div class="wp-block-smn-parent-title" id="parent-title-quienes-somos">404</div>
				</div>

				<h1 class="page-title"><?php esc_html_e( 'Oops! No podemos encontrar lo que estás buscando', 'infinitia' ); ?></h1>
				<p><?php esc_html_e( 'Parece que no se ha encontrado nada en esta página. ¿Por qué no pruebas a ir a la página de inicio o a hacer una búsqueda?', 'infinitia' ); ?></p>

					<?php
					get_search_form();
					;?>

			</div><!-- .page-content -->
		</section><!-- .error-404 -->

	</main><!-- #main -->

<?php
get_footer();
