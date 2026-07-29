<?php 
// Shortcodes 

/**
 * Shortcode para mostrar los logos de los clientes
 * 
 * Uso: [clientes]
 */
function logos_clientes_shortcode() {
    ob_start();
    get_template_part( 'template-parts/logos-clientes' );
    return ob_get_clean();
}
add_shortcode( 'clientes', 'logos_clientes_shortcode' );


/**
 * Shortcode para mostrar los logos de los clientes en soluciones, relacionados por sector
 * 
 * Uso: [clientes_soluciones]
 */
function logos_clientes_soluciones_shortcode() {
    ob_start();
    get_template_part( 'template-parts/logos-clientes-soluciones' );
    return ob_get_clean();
}
add_shortcode( 'clientes_soluciones', 'logos_clientes_soluciones_shortcode' );


/**
 * Shortcode para mostrar la navegación por anclas
 * 
 * Uso: [anchor_nav]
 */
function anchor_nav_shortcode() {
    ob_start();
    get_template_part( 'template-parts/anchor-nav' );
    return ob_get_clean();
}
add_shortcode( 'anchor_nav', 'anchor_nav_shortcode' );

