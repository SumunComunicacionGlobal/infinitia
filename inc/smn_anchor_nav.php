<?php
/**
 * Helpers de anchor nav.
 *
 * @package infinitia
 */

/**
 * Obtiene items de navegacion a partir de nodos con clase section-caption.
 *
 * @param int $post_id ID del post.
 * @return array<int, array{id:string,label:string}>
 */
function smn_get_anchor_nav_items( $post_id = 0 ) {
	$post_id = (int) $post_id;

	if ( $post_id <= 0 ) {
		$post_id = (int) get_queried_object_id();
	}

	if ( $post_id <= 0 ) {
		$post_id = (int) get_the_ID();
	}

	if ( $post_id <= 0 || ! class_exists( 'DOMDocument' ) ) {
		return array();
	}

	$content = (string) get_post_field( 'post_content', $post_id );

	if ( '' === trim( $content ) ) {
		return array();
	}

	$previous_errors = libxml_use_internal_errors( true );
	$dom             = new DOMDocument( '1.0', 'UTF-8' );
	$loaded          = $dom->loadHTML( '<?xml encoding="utf-8" ?>' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );

	if ( ! $loaded ) {
		libxml_clear_errors();
		libxml_use_internal_errors( $previous_errors );
		return array();
	}

	$xpath  = new DOMXPath( $dom );
	$nodes  = $xpath->query( '//*[contains(concat(" ", normalize-space(@class), " "), " section-caption ") and not(ancestor::*[@id="anchor-nav-soluciones"])]' );
	$items  = array();
	$used   = array();

	if ( $nodes instanceof DOMNodeList ) {
		foreach ( $nodes as $node ) {
			$p_nodes = $xpath->query( './/p[1]', $node );
			$label   = '';

			if ( $p_nodes instanceof DOMNodeList && $p_nodes->length > 0 ) {
				$label = (string) $p_nodes->item( 0 )->textContent;
			}

			$label = trim( (string) preg_replace( '/\s+/u', ' ', wp_strip_all_tags( $label ) ) );

			if ( '' === $label ) {
				continue;
			}

			$id = sanitize_title( $label );

			if ( 'anchor-nav-soluciones' === $id ) {
				continue;
			}

			if ( '' === $id || isset( $used[ $id ] ) ) {
				continue;
			}

			$used[ $id ] = true;
			$items[] = array(
				'id'    => $id,
				'label' => $label,
			);
		}
	}

	libxml_clear_errors();
	libxml_use_internal_errors( $previous_errors );

	return $items;
}
