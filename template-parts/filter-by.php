<?php
/**
 * Componente Filter By.
 *
 * Paso 1: Barra principal.
 */

$is_blog_context = is_home() || is_category() || is_tag() || is_author() || is_date();
$primary_facet_name = $is_blog_context ? 'categorias_blog' : 'soluciones_para';
$primary_tab_label = $is_blog_context ? __( 'Categorías', 'infinitia' ) : __( 'Soluciones para', 'infinitia' );
?>

<section id="filter-by" class="filter-by" aria-label="<?php esc_attr_e( 'Filtro de contenido', 'infinitia' ); ?>">
	<div class="wp-block-group filter-by__bar" role="toolbar" aria-label="<?php esc_attr_e( 'Opciones de filtrado', 'infinitia' ); ?>">
		<button class="filter-by__label toggle-filter-by" type="button" aria-controls="filter-by-content" aria-expanded="false">
			<span class="filter-by__label-icon" aria-hidden="true">
				<?php echo file_get_contents(get_template_directory() . '/assets/icons/carret-mega-menu.svg'); ?>
			</span>
			<span class="filter-by__label-text"><?php esc_html_e( 'Filtrar por', 'infinitia' ); ?></span>
		</button>

		<ul class="filter-by__tabs" role="tablist" aria-label="<?php esc_attr_e( 'Tipos de filtro', 'infinitia' ); ?>">
			<li class="filter-by__tab-item" role="presentation">
				<button class="filter-by__tab" type="button" role="tab" aria-selected="false" aria-controls="filter-by-panel-soluciones" id="filter-by-tab-soluciones">
					<?php echo esc_html( $primary_tab_label ); ?>
				</button>
			</li>
			<?php if ( ! $is_blog_context ) : ?>
			<li class="filter-by__tab-item" role="presentation">
				<button class="filter-by__tab" type="button" role="tab" aria-selected="false" aria-controls="filter-by-panel-materiales" id="filter-by-tab-materiales">
					<?php esc_html_e( 'Materiales', 'infinitia' ); ?>
				</button>
			</li>
			<?php endif; ?>
			<li class="filter-by__tab-item" role="presentation">
				<button class="filter-by__tab" type="button" role="tab" aria-selected="false" aria-controls="filter-by-panel-expertos" id="filter-by-tab-expertos">
					<?php esc_html_e( 'Expertos/as', 'infinitia' ); ?>
				</button>
			</li>
		</ul>

		<button class="filter-by__close" type="button" aria-label="<?php esc_attr_e( 'Cerrar filtros', 'infinitia' ); ?>">
			<span aria-hidden="true"><?php echo file_get_contents(get_template_directory() . '/assets/icons/x.svg'); ?></span>
		</button>
	</div>

	<div id="filter-by-content" class="filter-by-content" aria-live="polite">
		<div id="filter-by-panel-soluciones" class="filter-by__panel filter-by__panel--soluciones" role="tabpanel" aria-labelledby="filter-by-tab-soluciones" aria-hidden="true">
			<?php if (function_exists('facetwp_display')) : ?>
				<?php echo facetwp_display( 'facet', $primary_facet_name ); ?>
			<?php endif; ?>
		</div>

		<?php if ( ! $is_blog_context ) : ?>
		<div id="filter-by-panel-materiales" class="filter-by__panel filter-by__panel--materiales" role="tabpanel" aria-labelledby="filter-by-tab-materiales" aria-hidden="true">
			<?php if (function_exists('facetwp_display')) : ?>
				<?php echo facetwp_display( 'facet', 'materiales' ); ?>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<div id="filter-by-panel-expertos" class="filter-by__panel filter-by__panel--expertos" role="tabpanel" aria-labelledby="filter-by-tab-expertos" aria-hidden="true">
			<?php if (function_exists('facetwp_display')) : ?>
				<?php echo facetwp_display( 'facet', 'expertos' ); ?>
			<?php endif; ?>
		</div>
	</div>
</section>
