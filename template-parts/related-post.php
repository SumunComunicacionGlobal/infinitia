<?php
    // Obtener las categorías del post actual
    $categories = get_the_category();
    $category_ids = array();
    
    if( $categories ) {
        foreach( $categories as $category ) {
            $category_ids[] = $category->term_id;
        }
    }
    
    // Buscar posts relacionados por categoría
    $related = array();
    if( !empty( $category_ids ) ) {
        $related = get_posts(
            array(
                'post_type' => 'post',
                'numberposts' => 3,
                'post__not_in' => array( $post->ID ),
                'category__in' => $category_ids,
                'orderby' => 'date',
                'order' => 'DESC'
            )
        );
    }
    
    if( $related ):
        $card_blog_block = smn_get_reusable_block_by_title( 'Card Blog' ); ?>

        <section class="wp-block is-layout-constrained has-global-padding">   
            <p class="has-heading-4-font-size uppercase"><?php esc_html_e( 'Entradas relacionadas', 'infinitia' ); ?></p>
            <div class="mt-3 mb-3 grid-columns-3 wp-block-group is-layout-grid ">	
                <?php
                    if( $related ) foreach( $related as $post ) {
                        setup_postdata($post);

                        if ( $card_blog_block ) {
                            $block_content = apply_filters( 'the_content', $card_blog_block->post_content );
                            echo $block_content;
                        }
					
                        }
                    wp_reset_postdata();
                ?>
            </div>    
        </section>

        

     <?php endif ;?>