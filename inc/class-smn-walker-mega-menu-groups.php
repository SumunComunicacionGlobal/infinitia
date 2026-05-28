<?php
/**
 * Walker personalizado para renderizar el megamenu sin agrupaciones,
 * con soporte de iconos ACF, submenus y widget final.
 */
class SMN_Walker_Mega_Menu_Groups extends Walker_Nav_Menu {
    
    public function walk( $elements, $max_depth, ...$args ) {
        $output = '';
        
        if ( $max_depth < -1 ) {
            return $output;
        }
        
        if ( empty( $elements ) ) {
            return $output;
        }
        
        $parent_field = $this->db_fields['parent'];
        
        // Crear índice de hijos por parent ID.
        $children_elements = array();
        
        foreach ( $elements as $e ) {
            if ( !empty( $e->$parent_field ) ) {
                $children_elements[$e->$parent_field][] = $e;
            }
        }
        
        // Filtrar solo elementos de nivel superior.
        $top_level_elements = array();
        foreach ( $elements as $e ) {
            if ( empty( $e->$parent_field ) ) {
                // Detectar si tiene hijos y agregar clase correspondiente.
                if ( isset( $children_elements[$e->ID] ) ) {
                    $e->classes[] = 'menu-item-has-children';
                }
                $top_level_elements[] = $e;
            }
        }

        // Renderizar items sin agrupaciones.
        foreach ( $top_level_elements as $item ) {
            $output .= $this->build_menu_item( $item, $children_elements );
        }
        
        // Agregar widget al final del megamenu.
        if ( is_active_sidebar( 'widget-megamenu' ) ) {
            ob_start();
            dynamic_sidebar( 'widget-megamenu' );
            $widget_html = ob_get_clean();
            $output .= '<li class="menu-widget-container menu-widget-mega">' . $widget_html . '</li>';
        }
        
        return $output;
    }
    
    private function build_menu_item( $item, $children_elements = array() ) {
        $has_children = isset( $children_elements[$item->ID] ) && !empty( $children_elements[$item->ID] );
        $title_description = get_field( 'description-title-item-menu', $item );
        
        $output = '<li id="menu-item-'. $item->ID . '" class="' . implode(' ', $item->classes ) . '">';
        $output .= '<a href="' . esc_url( $item->url ) . '"';
        
        if ( $item->attr_title ) {
            $output .= ' title="' . esc_attr( $item->attr_title ) . '"';
        }
        if ( $item->target ) {
            $output .= ' target="' . esc_attr( $item->target ) . '"';
        }
        if ( $item->xfn ) {
            $output .= ' rel="' . esc_attr( $item->xfn ) . '"';
        }
        
        $output .= '>';
        
        $output .= apply_filters( 'the_title', $item->title, $item->ID );

        if ( ! empty( $title_description ) ) {
            $output .= '<div class="menu-title-description">' . esc_html( $title_description ) . '</div>';
        }

        $output .= '</a>';
        
        // Agregar submenú si tiene hijos
        if ( $has_children ) {
            $output .= '<ul class="sub-menu">';
            foreach ( $children_elements[$item->ID] as $child ) {
                $output .= $this->build_menu_item( $child, $children_elements );
            }
            $output .= '</ul>';
        }
        
        $output .= '</li>';
        
        return $output;
    }
    
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        // Este método ya no se usa, la lógica está en walk()
    }
    
    function end_el( &$output, $item, $depth = 0, $args = array() ) {
        // Este método ya no se usa, la lógica está en walk()
    }
}
