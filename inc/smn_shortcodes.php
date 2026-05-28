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

