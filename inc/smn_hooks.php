<?php

// Agrega un filtro para el bloque de consulta de WordPress
// que muestra los posts relacionados en la página de un post y los filtra por categorías
add_filter('render_block_data', function ($parsed_block) {
    if (is_admin() || wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST)) {
        return $parsed_block;
    }

    $current_post_id = (int) get_queried_object_id();

    if (
        is_single() &&
        $current_post_id > 0 &&
        isset($parsed_block['blockName']) &&
        $parsed_block['blockName'] === 'core/query' &&
        isset($parsed_block['attrs']['className']) &&
        strpos($parsed_block['attrs']['className'], 'is-style-is-related-posts') !== false
    ) {
        $category_ids = wp_get_post_categories($current_post_id);

        if (!empty($category_ids)) {
            $parsed_block['attrs']['query']['categoryIds'] = $category_ids;
            $parsed_block['attrs']['query']['exclude'] = [$current_post_id];
            $parsed_block['attrs']['query']['sticky'] = '';
            $parsed_block['attrs']['query']['perPage'] = 6;
        }
    }

    return $parsed_block;
});



/**
 * Complianz: Show the banner when a html element with class 'cmplz-show-banner' is clicked
 */
function cmplz_show_banner_on_click() {
	?>
	<style>
        .cmplz-show-banner {
            cursor: pointer;
        }
	</style>
	<script>
        function addEvent(event, selector, callback, context) {
            document.addEventListener(event, e => {
                if ( e.target.closest(selector) ) {
                    callback(e);
                }
            });
        }
        addEvent('click', '.cmplz-show-banner', function(e){
            e.preventDefault();
            document.querySelectorAll('.cmplz-manage-consent').forEach(obj => {
                obj.click();
            });
        });
	</script>
	<?php
}
add_action( 'wp_footer', 'cmplz_show_banner_on_click' );

/**
 * Obtiene el término de nivel superior para un post dado y taxonomía.
 *
 * @param int $post_id ID del post.
 * @param string $taxonomy Taxonomía.
 * @return WP_Term|null
 */
function smn_get_top_level_term_for_post($post_id, $taxonomy) {
    $terms = get_the_terms($post_id, $taxonomy);
    if (empty($terms) || is_wp_error($terms)) {
        return null;
    }

    foreach ($terms as $term) {
        $ancestor = $term;

        while (!empty($ancestor->parent)) {
            $parent_term = get_term($ancestor->parent, $taxonomy);
            if (!$parent_term || is_wp_error($parent_term)) {
                break;
            }
            $ancestor = $parent_term;
        }

        if (!empty($ancestor->name)) {
            return $ancestor;
        }
    }

    return null;
}

/**
 * Obtiene el post_id desde el contexto del bloque o del loop actual.
 *
 * @param array $block Datos del bloque.
 * @return int
 */
function smn_get_post_id_from_block_context($block) {
    $post_id = 0;

    if (isset($block['context']['postId'])) {
        $post_id = (int) $block['context']['postId'];
    }

    if (!$post_id) {
        $post_id = (int) get_the_ID();
    }

    return $post_id;
}

/**
 * Renderiza el icono de sector desde ACF para un termino.
 *
 * @param WP_Term $term Termino de taxonomia.
 * @return string
 */
function smn_get_sector_icon_html($term) {
    if (!function_exists('get_field') || empty($term) || !($term instanceof WP_Term)) {
        return '';
    }

    $icon_field = get_field('icon-sector', $term);
    if (empty($icon_field)) {
        $icon_field = get_field('icon-sector', $term->taxonomy . '_' . $term->term_id);
    }

    if (empty($icon_field)) {
        return '';
    }

    if (is_array($icon_field)) {
        if (!empty($icon_field['ID'])) {
            return wp_get_attachment_image((int) $icon_field['ID'], 'thumbnail', false, array('class' => 'smn-post-term-icon-image'));
        }

        if (!empty($icon_field['id'])) {
            return wp_get_attachment_image((int) $icon_field['id'], 'thumbnail', false, array('class' => 'smn-post-term-icon-image'));
        }

        if (!empty($icon_field['url'])) {
            return '<img class="smn-post-term-icon-image" src="' . esc_url($icon_field['url']) . '" alt="" loading="lazy" decoding="async" />';
        }
    }

    if (is_numeric($icon_field)) {
        return wp_get_attachment_image((int) $icon_field, 'thumbnail', false, array('class' => 'smn-post-term-icon-image'));
    }

    if (is_string($icon_field)) {
        return '<img class="smn-post-term-icon-image" src="' . esc_url($icon_field) . '" alt="" loading="lazy" decoding="async" />';
    }

    return '';
}

/**
 * Mantiene el contenedor original del bloque core/post-terms y reemplaza su contenido interno.
 *
 * @param string $block_content HTML original del bloque.
 * @param string $inner_html    HTML interno nuevo.
 * @return string
 */
function smn_replace_post_terms_inner_html($block_content, $inner_html) {
    $trimmed = trim((string) $block_content);

    if (preg_match('/^<([a-z0-9]+)\b([^>]*)>.*<\/\1>$/is', $trimmed, $matches)) {
        return '<' . $matches[1] . $matches[2] . '>' . $inner_html . '</' . $matches[1] . '>';
    }

    return $inner_html;
}

/**
 * Quita enlaces del bloque core/post-terms y deja solo texto.
 */

function smn_remove_post_terms_links($block_content, $block) {
    if (empty($block_content)) {
        return $block_content;
    }

    $taxonomy = isset($block['attrs']['term']) ? $block['attrs']['term'] : '';
    $post_id = smn_get_post_id_from_block_context($block);

    if ('casos-de-exito-category' === $taxonomy) {
        if ($post_id) {
            $top_level_term = smn_get_top_level_term_for_post($post_id, $taxonomy);

            if (!empty($top_level_term) && !is_wp_error($top_level_term)) {
                $term_name_class = 'smn-post-term-name--' . sanitize_html_class(sanitize_title($top_level_term->name));
                $inner_html = '<span class="' . esc_attr($term_name_class) . '">' . esc_html($top_level_term->name) . '</span>';

                return smn_replace_post_terms_inner_html($block_content, $inner_html);
            }
        }

        return smn_replace_post_terms_inner_html($block_content, '');
    }

    if ('sector' === $taxonomy && $post_id) {
        $terms = get_the_terms($post_id, $taxonomy);

        if (empty($terms) || is_wp_error($terms)) {
            return '';
        }

        $items = array();
        foreach ($terms as $term) {
            $term_name_class = 'smn-post-term-name--' . sanitize_html_class(sanitize_title($term->name));
            $icon_html = smn_get_sector_icon_html($term);
            $item_html = '<span class="smn-post-term-name ' . esc_attr($term_name_class) . '">';

            if ('' !== $icon_html) {
                $item_html .= '<span class="smn-post-term-icon" aria-hidden="true">' . $icon_html . '</span>';
            }

            $item_html .= '<span class="smn-post-term-label">' . esc_html($term->name) . '</span>';
            $item_html .= '</span>';
            $items[] = $item_html;
        }

        return smn_replace_post_terms_inner_html($block_content, implode('', $items));
    }

    // Convierte cada enlace de término en un span para mantener estructura/estilo.
    $block_content = preg_replace_callback(
        '/<a\b([^>]*)>(.*?)<\/a>/is',
        static function ($matches) {
            return '<span class="smn-post-term-name">' . $matches[2] . '</span>';
        },
        $block_content
    );

    return $block_content;
}
add_filter('render_block_core/post-terms', 'smn_remove_post_terms_links', 10, 2);

/**
 * Obtiene la URL de contacto según idioma actual (es/en).
 *
 * @return string
 */
function smn_get_contact_page_url_by_language() {
    $lang = 'es';

    if (function_exists('pll_current_language')) {
        $lang = pll_current_language('slug');
    } elseif (defined('ICL_LANGUAGE_CODE')) {
        $lang = ICL_LANGUAGE_CODE;
    } else {
        $locale = get_locale();
        if (strpos($locale, 'en_') === 0) {
            $lang = 'en';
        }
    }

    $contact_slug = $lang === 'en' ? 'contact' : 'contacto';
    $contact_page = get_page_by_path($contact_slug);

    if ($contact_page instanceof WP_Post) {
        return get_permalink($contact_page);
    }

    return home_url('/' . $contact_slug . '/');
}

/**
 * Inyecta autor de la entrada y botón de contacto bajo los bloques quote
 * en el CPT casosdeexito.
 *
 * @param string $block_content Contenido renderizado del bloque.
 * @param array  $block         Datos del bloque.
 *
 * @return string
 */
function smn_inject_quote_footer_casosdeexito($block_content, $block) {
    if (empty($block_content) || is_admin()) {
        return $block_content;
    }

    if (!is_singular('casosdeexito')) {
        return $block_content;
    }

    $post_id = get_the_ID();
    if (!$post_id) {
        return $block_content;
    }

    $author_id = (int) get_post_field('post_author', $post_id);
    $author_name = get_the_author_meta('display_name', $author_id);
    $author_avatar = get_avatar(
        $author_id,
        56,
        '',
        $author_name,
        array(
            'class' => 'smn-quote-author-avatar',
        )
    );
    $contact_url = smn_get_contact_page_url_by_language();

    $locale = get_locale();
    $is_english = strpos($locale, 'en_') === 0;
    $button_text = $is_english ? __('Talk to a specialist', 'infinitia') : __('Habla con un especialista', 'infinitia');

    $footer_html  = '<div class="smn-quote-footer">';
    $footer_html .= '<div class="smn-quote-author-wrap">';
    $footer_html .= $author_avatar;
    $footer_html .= '<p class="smn-quote-author">' . esc_html($author_name) . '</p>';
    $footer_html .= '</div>';
    $footer_html .= '<div class="wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex">';
    $footer_html .= '<div class="wp-block-button is-style-with-chat">';
    $footer_html .= '<a class="wp-block-button__link wp-element-button smn-quote-contact-button" href="' . esc_url($contact_url) . '">' . esc_html($button_text) . '</a>';
    $footer_html .= '</div>';
    $footer_html .= '</div>';
    $footer_html .= '</div>';

    return $block_content . $footer_html;
}
add_filter('render_block_core/quote', 'smn_inject_quote_footer_casosdeexito', 10, 2);

/**
 * Resuelve el contenido del bloque smn/parent-title en hero sin tocar el bloque custom.
 *
 * Reglas:
 * 1) Si el bloque queda vacío, usar título de la página/post.
 * 2) Si ACF caption_hero_page existe, usar ese valor.
 * 3) En singles, usar etiqueta por tipo de contenido.
 *
 * @param string $block_content Contenido renderizado del bloque.
 * @param array  $block         Datos del bloque.
 *
 * @return string
 */
function smn_render_parent_title_fallback($block_content, $block) {
    if (is_admin() || wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST)) {
        return $block_content;
    }

    if (empty($block['blockName']) || 'smn/parent-title' !== $block['blockName']) {
        return $block_content;
    }

    $post_id = 0;
    if (!empty($block['context']['postId'])) {
        $post_id = (int) $block['context']['postId'];
    }
    if (!$post_id) {
        $post_id = (int) get_queried_object_id();
    }
    if (!$post_id) {
        $post_id = (int) get_the_ID();
    }

    if (!$post_id) {
        return $block_content;
    }

    $resolved_title = '';
    $post_type = get_post_type($post_id);
    $single_labels = array(
        'post' => __('Noticias', 'infinitia'),
        'ensayo' => __('Análisis y ensayos', 'infinitia'),
        'casosdeexito' => __('Proyectos', 'infinitia'),
    );

    if (is_singular() && isset($single_labels[$post_type])) {
        $resolved_title = $single_labels[$post_type];
    } else {
        $caption = '';
        if (function_exists('get_field')) {
            $caption = get_field('caption_hero_page', $post_id);
        }

        if (!empty($caption)) {
            $resolved_title = wp_strip_all_tags((string) $caption);
        } else {
            $current_text = trim(wp_strip_all_tags((string) $block_content));
            $resolved_title = '' !== $current_text ? $current_text : get_the_title($post_id);
        }
    }

    $resolved_title = trim((string) $resolved_title);
    if ('' === $resolved_title) {
        return $block_content;
    }

    if (!empty($block_content) && preg_match('/^<([a-z0-9]+)\\b([^>]*)>.*<\\/\\1>$/is', trim($block_content), $matches)) {
        return '<' . $matches[1] . $matches[2] . '>' . esc_html($resolved_title) . '</' . $matches[1] . '>';
    }

    return sprintf(
        '<div class="wp-block-smn-parent-title" id="parent-title-%s">%s</div>',
        esc_attr(sanitize_title($resolved_title)),
        esc_html($resolved_title)
    );
}
add_filter('render_block', 'smn_render_parent_title_fallback', 10, 2);

/**
 * Evita que el extracto automático (primer texto del contenido)
 * se muestre en páginas/singles cuando no hay excerpt manual.
 *
 * @param string          $excerpt Extracto calculado por WordPress.
 * @param WP_Post|int|null $post   Objeto o ID de post.
 *
 * @return string
 */
function smn_disable_fallback_excerpt_on_singular($excerpt, $post) {
    if (is_admin() || wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST)) {
        return $excerpt;
    }

    if (!is_singular()) {
        return $excerpt;
    }

    $post_object = get_post($post);
    if (!$post_object instanceof WP_Post) {
        return $excerpt;
    }

    $queried_post_id = (int) get_queried_object_id();
    if (!$queried_post_id || (int) $post_object->ID !== $queried_post_id) {
        return $excerpt;
    }

    if (has_excerpt($post_object)) {
        return $excerpt;
    }

    return '';
}
add_filter('get_the_excerpt', 'smn_disable_fallback_excerpt_on_singular', 10, 2);

/**
 * Resuelve un usuario de WP a partir del valor de FacetWP en el facet expertos.
 *
 * @param string $facet_value Valor de data-value de FacetWP.
 * @return WP_User|null
 */
function smn_get_expert_user_from_facet_value($facet_value) {
    $facet_value = (string) $facet_value;

    if ('' === $facet_value) {
        return null;
    }

    if (ctype_digit($facet_value)) {
        $user = get_user_by('id', (int) $facet_value);
        if ($user instanceof WP_User) {
            return $user;
        }
    }

    $user = get_user_by('slug', sanitize_title($facet_value));
    if ($user instanceof WP_User) {
        return $user;
    }

    $user = get_user_by('login', $facet_value);
    if ($user instanceof WP_User) {
        return $user;
    }

    return null;
}

/**
 * Inserta avatar en cada opción del facet "expertos" de FacetWP.
 *
 * @param string $output HTML del facet.
 * @param array  $params Contexto del facet.
 * @return string
 */
function smn_add_avatar_to_expertos_facet($output, $params) {
    $facet_name = isset($params['facet']['name']) ? (string) $params['facet']['name'] : '';

    if ('expertos' !== $facet_name || false === strpos($output, 'facetwp-checkbox')) {
        return $output;
    }

    $pattern = '/(<div class="facetwp-checkbox[^\"]*"[^>]*data-value="([^\"]+)"[^>]*>)(.*?)(<\/div>)/s';

    $output = preg_replace_callback(
        $pattern,
        static function ($matches) {
            $opening_tag = $matches[1];
            $facet_value = html_entity_decode($matches[2], ENT_QUOTES, 'UTF-8');
            $inner_html = $matches[3];
            $closing_tag = $matches[4];

            $user = smn_get_expert_user_from_facet_value($facet_value);
            if (!($user instanceof WP_User)) {
                return $matches[0];
            }

            $avatar_html = get_avatar(
                $user->ID,
                32,
                '',
                $user->display_name,
                array(
                    'class' => 'smn-facet-expert-avatar',
                    'loading' => 'lazy',
                    'decoding' => 'async',
                )
            );

            if (empty($avatar_html)) {
                return $matches[0];
            }

            if (false !== strpos($inner_html, 'smn-facet-expert-avatar-wrap')) {
                return $matches[0];
            }

            if (false !== strpos($inner_html, 'facetwp-display-value')) {
                $inner_html = preg_replace(
                    '/(<span class="facetwp-display-value">)/',
                    '<span class="smn-facet-expert-avatar-wrap" aria-hidden="true">' . $avatar_html . '</span>$1',
                    $inner_html,
                    1
                );
            } else {
                $inner_html = '<span class="smn-facet-expert-avatar-wrap" aria-hidden="true">' . $avatar_html . '</span>' . $inner_html;
            }

            return $opening_tag . $inner_html . $closing_tag;
        },
        $output
    );

    return is_string($output) ? $output : '';
}
add_filter('facetwp_facet_html', 'smn_add_avatar_to_expertos_facet', 10, 2);


add_filter( 'facetwp_shortcode_html', function( $output, $atts ) {
    if ( 'sectores' == $atts['facet'] ) {
        // Reemplaza 'tu-clase' con la clase que deseas añadir
        $output = str_replace( 'facetwp-facet-sectores', 'facetwp-facet-sectores is-style-group-horizontal-scroll-btns', $output );
    }
    return $output;
}, 10, 2 );