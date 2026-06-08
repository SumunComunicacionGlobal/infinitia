<?php
function smn_render_parent_title_block( $attributes, $content ) {
    $post_id = get_the_ID();
    if ( ! $post_id ) return '';
    $parent_id = wp_get_post_parent_id( $post_id );
    if ( $parent_id ) {
        $raw_title = get_the_title( $parent_id );
        $title = esc_html( $raw_title );
        $title_slug = sanitize_title( $raw_title );
        return sprintf(
            '<div %s id="parent-title-%s">%s</div>',
            get_block_wrapper_attributes(),
            esc_attr( $title_slug ),
            $title
        );
    }
    return '';
}