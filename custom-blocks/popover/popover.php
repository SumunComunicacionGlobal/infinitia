<?php

/**
 * Render callback para el bloque smn/popover.
 *
 * @param array  $attributes Atributos del bloque.
 * @param string $content    HTML de InnerBlocks.
 * @return string
 */
function smn_render_popover_block( $attributes, $content ) {
    $raw_id = isset( $attributes['popoverId'] ) ? (string) $attributes['popoverId'] : '';
    $popover_id = sanitize_title( $raw_id );
    $close_label = __( 'Cerrar', 'infinitia' );

    $wrapper_attributes = get_block_wrapper_attributes(
        array(
            'class' => 'smn-popover-container',
        )
    );

    $id_attribute = '' !== $popover_id ? ' id="' . esc_attr( $popover_id ) . '"' : '';
    $close_target_attributes = '' !== $popover_id
        ? ' popovertarget="' . esc_attr( $popover_id ) . '" popovertargetaction="hide"'
        : '';
    $close_button = sprintf(
        '<button type="button" class="smn-popover-close" aria-label="%1$s"%2$s><img class="smn-popover-close__icon" src="%3$s" alt="" aria-hidden="true" loading="lazy" decoding="async" /></button>',
        esc_attr( $close_label ),
        $close_target_attributes,
        esc_url( get_stylesheet_directory_uri() . '/assets/icons/x.svg' )
    );

    return sprintf(
        '<div %1$s popover%2$s>%3$s%4$s</div>',
        $wrapper_attributes,
        $id_attribute,
        $close_button,
        $content
    );
}
