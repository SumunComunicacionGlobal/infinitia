<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/*
 * Importa taxonomias desde un XML de WordPress antiguo hacia el CPT actual.
 *
 * Modos:
 * - Auditoria (default): no guarda cambios, genera plantilla CSV si no existe.
 * - Aplicar: TAX_IMPORT_APPLY=1 wp eval-file ...
 */

$xml_file = '/Users/sumun/Downloads/infinitiaindustrialconsulting.WordPress.2026-05-25.xml';
$target_post_type = 'casosdeexito';
$source_term_domain = 'show_category';
$mapping_csv = ABSPATH . 'tax-mapping-casosdeexito.csv';

$target_taxonomies = array(
    'casos-de-exito-category',
    'materiales',
);

$apply = getenv( 'TAX_IMPORT_APPLY' ) === '1';

if ( ! file_exists( $xml_file ) ) {
    echo "ERROR: No existe XML en: {$xml_file}\n";
    return;
}

$xml = simplexml_load_file( $xml_file, 'SimpleXMLElement', LIBXML_NOCDATA );
if ( false === $xml ) {
    echo "ERROR: No se pudo leer el XML.\n";
    return;
}

$namespaces = $xml->getNamespaces( true );
$wp_ns = isset( $namespaces['wp'] ) ? $namespaces['wp'] : null;

if ( ! $wp_ns ) {
    echo "ERROR: Namespace wp no encontrado en XML.\n";
    return;
}

$xml_posts_by_lang_slug = array();
$unique_source_terms = array();

foreach ( $xml->channel->item as $item ) {
    $wp = $item->children( $wp_ns );

    if ( (string) $wp->post_type !== $target_post_type ) {
        continue;
    }

    $slug = trim( (string) $wp->post_name );
    if ( '' === $slug ) {
        continue;
    }

    $language = '';
    $source_terms = array();

    foreach ( $item->category as $cat ) {
        $attrs = $cat->attributes();
        $domain = isset( $attrs['domain'] ) ? (string) $attrs['domain'] : '';
        $nicename = isset( $attrs['nicename'] ) ? (string) $attrs['nicename'] : '';

        if ( 'language' === $domain ) {
            $language = $nicename;
        }

        if ( $source_term_domain === $domain && '' !== $nicename ) {
            $source_terms[] = $nicename;
            $unique_source_terms[ $nicename ] = true;
        }
    }

    $source_terms = array_values( array_unique( $source_terms ) );
    $key = $language . '|' . $slug;

    $xml_posts_by_lang_slug[ $key ] = array(
        'language' => $language,
        'slug' => $slug,
        'source_terms' => $source_terms,
    );
}

if ( empty( $xml_posts_by_lang_slug ) ) {
    echo "ERROR: No se encontraron items del post_type {$target_post_type} en el XML.\n";
    return;
}

ksort( $unique_source_terms );

if ( ! file_exists( $mapping_csv ) ) {
    $fp = fopen( $mapping_csv, 'w' );
    if ( $fp ) {
        fputcsv( $fp, array( 'old_slug', 'casos_de_exito_category', 'materiales' ) );
        foreach ( array_keys( $unique_source_terms ) as $old_slug ) {
            fputcsv( $fp, array( $old_slug, '', '' ) );
        }
        fclose( $fp );
        echo "Plantilla creada: {$mapping_csv}\n";
        echo "Rellena las columnas casos_de_exito_category y materiales (slugs, separados por | si hay varios).\n";
    } else {
        echo "WARNING: No se pudo crear plantilla CSV en {$mapping_csv}\n";
    }
}

$mapping = array();
if ( file_exists( $mapping_csv ) ) {
    $fp = fopen( $mapping_csv, 'r' );
    if ( $fp ) {
        $header = fgetcsv( $fp );
        while ( ( $row = fgetcsv( $fp ) ) !== false ) {
            $old_slug = isset( $row[0] ) ? trim( $row[0] ) : '';
            if ( '' === $old_slug ) {
                continue;
            }

            $cat_slugs = isset( $row[1] ) ? array_filter( array_map( 'trim', explode( '|', $row[1] ) ) ) : array();
            $mat_slugs = isset( $row[2] ) ? array_filter( array_map( 'trim', explode( '|', $row[2] ) ) ) : array();

            $mapping[ $old_slug ] = array(
                'casos-de-exito-category' => array_values( $cat_slugs ),
                'materiales' => array_values( $mat_slugs ),
            );
        }
        fclose( $fp );
    }
}

$target_posts = get_posts(
    array(
        'post_type' => $target_post_type,
        'post_status' => array( 'publish', 'draft', 'pending', 'private', 'future' ),
        'posts_per_page' => -1,
        'fields' => 'ids',
    )
);

$total = count( $target_posts );
$matched = 0;
$updated = 0;
$missing_xml = 0;
$missing_mapping = array();
$missing_terms = array();

foreach ( $target_posts as $post_id ) {
    $slug = get_post_field( 'post_name', $post_id );
    $lang = '';

    if ( function_exists( 'pll_get_post_language' ) ) {
        $lang = (string) pll_get_post_language( $post_id, 'slug' );
    }

    $key = $lang . '|' . $slug;

    if ( ! isset( $xml_posts_by_lang_slug[ $key ] ) ) {
        $missing_xml++;
        continue;
    }

    $matched++;
    $source_terms = $xml_posts_by_lang_slug[ $key ]['source_terms'];

    $assign_by_tax = array(
        'casos-de-exito-category' => array(),
        'materiales' => array(),
    );

    foreach ( $source_terms as $old_slug ) {
        if ( ! isset( $mapping[ $old_slug ] ) ) {
            $missing_mapping[ $old_slug ] = true;
            continue;
        }

        foreach ( $target_taxonomies as $tax ) {
            if ( empty( $mapping[ $old_slug ][ $tax ] ) ) {
                continue;
            }

            foreach ( $mapping[ $old_slug ][ $tax ] as $new_slug ) {
                $term = get_term_by( 'slug', $new_slug, $tax );
                if ( ! $term || is_wp_error( $term ) ) {
                    $missing_terms[] = $tax . ':' . $new_slug;
                    continue;
                }
                $assign_by_tax[ $tax ][] = (int) $term->term_id;
            }
        }
    }

    foreach ( $target_taxonomies as $tax ) {
        $assign_by_tax[ $tax ] = array_values( array_unique( $assign_by_tax[ $tax ] ) );

        if ( empty( $assign_by_tax[ $tax ] ) ) {
            continue;
        }

        if ( $apply ) {
            wp_set_object_terms( $post_id, $assign_by_tax[ $tax ], $tax, false );
        }
    }

    if ( $apply ) {
        $updated++;
        echo "UPDATED post_id={$post_id} lang={$lang} slug={$slug}\n";
    } else {
        echo "DRY-RUN post_id={$post_id} lang={$lang} slug={$slug}\n";
    }
}

$missing_mapping_count = count( $missing_mapping );
$missing_terms = array_values( array_unique( $missing_terms ) );

echo "\nResumen:\n";
echo "total_posts={$total}\n";
echo "matched_with_xml={$matched}\n";
echo "updated_or_would_update=" . ( $apply ? $updated : $matched ) . "\n";
echo "missing_in_xml={$missing_xml}\n";
echo "missing_mapping_slugs={$missing_mapping_count}\n";
echo "missing_target_terms=" . count( $missing_terms ) . "\n";

if ( $missing_mapping_count > 0 ) {
    echo "\nOld slugs sin mapping (primeros 40):\n";
    $i = 0;
    foreach ( array_keys( $missing_mapping ) as $slug ) {
        echo "- {$slug}\n";
        $i++;
        if ( $i >= 40 ) {
            break;
        }
    }
}

if ( ! empty( $missing_terms ) ) {
    echo "\nSlugs destino no encontrados (primeros 40):\n";
    foreach ( array_slice( $missing_terms, 0, 40 ) as $missing ) {
        echo "- {$missing}\n";
    }
}
