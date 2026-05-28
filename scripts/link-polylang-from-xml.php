<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/*
 * Enlaza traducciones Polylang para un CPT usando XML de exportacion WordPress.
 *
 * Modo prueba (default):
 *   wp eval-file wp-content/themes/infinitia/scripts/link-polylang-from-xml.php
 *
 * Modo aplicar:
 *   LINK_TRANSLATIONS_APPLY=1 wp eval-file wp-content/themes/infinitia/scripts/link-polylang-from-xml.php
 */

$xml_file = '/Users/sumun/Downloads/infinitiaindustrialconsulting.WordPress.2026-05-25.xml';
$target_post_type = 'casosdeexito';
$report_csv = ABSPATH . 'polylang-link-report-casosdeexito.csv';
$apply = getenv( 'LINK_TRANSLATIONS_APPLY' ) === '1';

if ( ! function_exists( 'pll_get_post_language' ) || ! function_exists( 'pll_save_post_translations' ) ) {
    echo "ERROR: Polylang no esta disponible (faltan funciones pll_*).\n";
    return;
}

if ( ! file_exists( $xml_file ) ) {
    echo "ERROR: XML no encontrado en {$xml_file}\n";
    return;
}

$xml = simplexml_load_file( $xml_file, 'SimpleXMLElement', LIBXML_NOCDATA );
if ( false === $xml ) {
    echo "ERROR: No se pudo leer XML.\n";
    return;
}

$namespaces = $xml->getNamespaces( true );
$wp_ns = isset( $namespaces['wp'] ) ? $namespaces['wp'] : null;
if ( ! $wp_ns ) {
    echo "ERROR: Namespace wp no encontrado en XML.\n";
    return;
}

$groups = array();
$xml_items_total = 0;
$xml_items_used = 0;
$xml_skipped_missing_lang = 0;
$xml_skipped_missing_group = 0;
$xml_skipped_missing_slug = 0;
$xml_group_lang_conflicts = array();

foreach ( $xml->channel->item as $item ) {
    $xml_items_total++;

    $wp = $item->children( $wp_ns );
    if ( (string) $wp->post_type !== $target_post_type ) {
        continue;
    }

    $slug = trim( (string) $wp->post_name );
    if ( '' === $slug ) {
        $xml_skipped_missing_slug++;
        continue;
    }

    $lang = '';
    $group_key = '';

    foreach ( $item->category as $cat ) {
        $attrs = $cat->attributes();
        $domain = isset( $attrs['domain'] ) ? (string) $attrs['domain'] : '';
        $nicename = isset( $attrs['nicename'] ) ? (string) $attrs['nicename'] : '';

        if ( 'language' === $domain ) {
            $lang = $nicename;
        } elseif ( 'post_translations' === $domain ) {
            $group_key = $nicename;
        }
    }

    if ( '' === $lang ) {
        $xml_skipped_missing_lang++;
        continue;
    }

    if ( '' === $group_key ) {
        $xml_skipped_missing_group++;
        continue;
    }

    $xml_items_used++;

    if ( ! isset( $groups[ $group_key ] ) ) {
        $groups[ $group_key ] = array();
    }

    if ( isset( $groups[ $group_key ][ $lang ] ) && $groups[ $group_key ][ $lang ] !== $slug ) {
        $xml_group_lang_conflicts[] = array(
            'group_key' => $group_key,
            'lang' => $lang,
            'old_slug' => $groups[ $group_key ][ $lang ],
            'new_slug' => $slug,
        );
    }

    $groups[ $group_key ][ $lang ] = $slug;
}

if ( empty( $groups ) ) {
    echo "ERROR: No se encontraron grupos de traduccion en XML para {$target_post_type}.\n";
    return;
}

$posts = get_posts(
    array(
        'post_type' => $target_post_type,
        'post_status' => array( 'publish', 'draft', 'pending', 'private', 'future' ),
        'posts_per_page' => -1,
        'fields' => 'ids',
    )
);

$current_index = array();
$posts_without_lang = 0;

foreach ( $posts as $post_id ) {
    $slug = get_post_field( 'post_name', $post_id );
    $lang = (string) pll_get_post_language( $post_id, 'slug' );

    if ( '' === $lang ) {
        $posts_without_lang++;
        continue;
    }

    $key = $lang . '|' . $slug;
    if ( ! isset( $current_index[ $key ] ) ) {
        $current_index[ $key ] = array();
    }
    $current_index[ $key ][] = (int) $post_id;
}

$groups_total = count( $groups );
$groups_ready = 0;
$groups_linked = 0;
$groups_incomplete = 0;
$groups_with_collisions = 0;
$groups_missing_current = 0;
$already_linked = 0;

$report_rows = array();

foreach ( $groups as $group_key => $lang_slug_map ) {
    $translation_map = array();
    $group_has_missing = false;
    $group_has_collision = false;

    foreach ( $lang_slug_map as $lang => $slug ) {
        $lookup_key = $lang . '|' . $slug;

        if ( ! isset( $current_index[ $lookup_key ] ) ) {
            $group_has_missing = true;
            $groups_missing_current++;
            $report_rows[] = array( $group_key, 'missing_current_post', $lang, $slug, '' );
            continue;
        }

        if ( count( $current_index[ $lookup_key ] ) > 1 ) {
            $group_has_collision = true;
            $groups_with_collisions++;
            $report_rows[] = array(
                $group_key,
                'collision_multiple_posts_same_lang_slug',
                $lang,
                $slug,
                implode( '|', $current_index[ $lookup_key ] ),
            );
            continue;
        }

        $translation_map[ $lang ] = (int) $current_index[ $lookup_key ][0];
    }

    if ( $group_has_missing || $group_has_collision ) {
        $groups_incomplete++;
        continue;
    }

    if ( count( $translation_map ) < 2 ) {
        $groups_incomplete++;
        $report_rows[] = array( $group_key, 'incomplete_group_less_than_2_languages', '', '', '' );
        continue;
    }

    $groups_ready++;

    $existing = pll_get_post_translations( reset( $translation_map ) );
    ksort( $existing );
    $expected = $translation_map;
    ksort( $expected );

    if ( $existing === $expected ) {
        $already_linked++;
        continue;
    }

    if ( $apply ) {
        pll_save_post_translations( $translation_map );
        $groups_linked++;
        echo 'LINKED group=' . $group_key . ' map=' . wp_json_encode( $translation_map ) . "\n";
    } else {
        echo 'DRY-RUN group=' . $group_key . ' map=' . wp_json_encode( $translation_map ) . "\n";
    }
}

$fp = fopen( $report_csv, 'w' );
if ( $fp ) {
    fputcsv( $fp, array( 'group_key', 'issue', 'lang', 'slug', 'post_ids' ) );
    foreach ( $report_rows as $row ) {
        fputcsv( $fp, $row );
    }
    fclose( $fp );
}

echo "\nResumen:\n";
echo 'mode=' . ( $apply ? 'apply' : 'dry-run' ) . "\n";
echo 'xml_items_total=' . $xml_items_total . "\n";
echo 'xml_items_used=' . $xml_items_used . "\n";
echo 'xml_skipped_missing_slug=' . $xml_skipped_missing_slug . "\n";
echo 'xml_skipped_missing_lang=' . $xml_skipped_missing_lang . "\n";
echo 'xml_skipped_missing_group=' . $xml_skipped_missing_group . "\n";
echo 'xml_group_lang_conflicts=' . count( $xml_group_lang_conflicts ) . "\n";
echo 'current_posts_total=' . count( $posts ) . "\n";
echo 'current_posts_without_lang=' . $posts_without_lang . "\n";
echo 'groups_total=' . $groups_total . "\n";
echo 'groups_ready=' . $groups_ready . "\n";
echo 'groups_incomplete=' . $groups_incomplete . "\n";
echo 'groups_missing_current=' . $groups_missing_current . "\n";
echo 'groups_with_collisions=' . $groups_with_collisions . "\n";
echo 'already_linked=' . $already_linked . "\n";
echo 'groups_linked_now=' . ( $apply ? $groups_linked : 0 ) . "\n";
echo 'report_csv=' . $report_csv . "\n";

if ( ! empty( $xml_group_lang_conflicts ) ) {
    echo "\nConflictos XML grupo+idioma (primeros 30):\n";
    foreach ( array_slice( $xml_group_lang_conflicts, 0, 30 ) as $conflict ) {
        echo '- group=' . $conflict['group_key'] . ' lang=' . $conflict['lang'] . ' old=' . $conflict['old_slug'] . ' new=' . $conflict['new_slug'] . "\n";
    }
}
