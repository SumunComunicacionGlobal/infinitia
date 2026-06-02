<?php
// Bloque Reusable "Hero Page" para el Hero de la página.
$block = get_page_by_title( 'Hero Page', OBJECT, 'wp_block' );

if ( ! $block ) {
    return;
}

$block_content = apply_filters( 'the_content', $block->post_content );

// Se renderiza antes del loop en page.php, así que usamos el queried object.
$current_post_id = get_queried_object_id();
$hero_title = '';
$hero_video = '';

if ( function_exists( 'get_field' ) && $current_post_id ) {
    $hero_title = get_field( 'hero_title_page', $current_post_id );

    $hero_video = get_field( 'hero_video_page', $current_post_id );
}

if ( $hero_title || $hero_video ) {
    $dom = new DOMDocument();
    libxml_use_internal_errors( true );
    $dom->loadHTML( mb_convert_encoding( $block_content, 'HTML-ENTITIES', 'UTF-8' ), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
    $xpath = new DOMXPath( $dom );

    if ( $hero_title ) {
        // Prioriza el título del hero, con fallback al primer h1/h2 disponible.
        $title_nodes = $xpath->query( "//*[@id='hero']//h1[contains(@class, 'wp-block-post-title')] | //*[@id='hero']//h2[contains(@class, 'wp-block-post-title')] | //*[@id='hero']//h1 | //*[@id='hero']//h2" );

        if ( $title_nodes->length > 0 ) {
            $title_nodes->item( 0 )->nodeValue = wp_strip_all_tags( $hero_title );
        }
    }

    if ( $hero_video ) {
        $hero_element = $xpath->query( "//*[@id='hero' or @id='hero-soluciones' or contains(concat(' ', normalize-space(@class), ' '), ' wp-block-cover ')]" );

        if ( $hero_element->length > 0 ) {
            $hero_container = $hero_element->item( 0 );
            $existing_video = $xpath->query( ".//video[contains(@class, 'wp-block-cover__video-background')]", $hero_container );

            if ( $existing_video->length > 0 ) {
                $video_element = $existing_video->item( 0 );
            } else {
                $video_element = $dom->createElement( 'video' );
                $video_element->setAttribute( 'class', 'wp-block-cover__video-background intrinsic-ignore' );
                $hero_container->insertBefore( $video_element, $hero_container->firstChild );
            }

            $video_element->setAttribute( 'autoplay', '' );
            $video_element->setAttribute( 'muted', '' );
            $video_element->setAttribute( 'loop', '' );
            $video_element->setAttribute( 'playsinline', '' );
            $video_element->setAttribute( 'src', esc_url( $hero_video ) );
            $video_element->setAttribute( 'data-object-fit', 'cover' );
        }
    }

    $block_content = $dom->saveHTML();
    libxml_clear_errors();
}

echo $block_content;
?>