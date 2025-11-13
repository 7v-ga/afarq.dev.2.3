<?php
/**
 * Template Part: Home Slider
 * Ubicación: wp-content/themes/afarq-child/template-parts/slider.php
 */
?>

<div class="full-slider">
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <?php
            /**
             * 1) OBTENER LOS PROYECTOS DEL SLIDER DESDE ACF
             */

            // Opción A: grupo "Slider" en la página de inicio
            $front_page_id   = get_option( 'page_on_front' );
            $slider_projects = get_field( 'slider_proyectos', $front_page_id );

            // Opción B: grupo "Slider" en Options Page
            // $slider_projects = get_field( 'slider_proyectos', 'option' );

            if ( $slider_projects && is_array( $slider_projects ) ) :

                foreach ( $slider_projects as $post ) :
                    setup_postdata( $post );

                    $slide_title       = get_the_title( $post->ID );
                    $slide_description = get_the_excerpt( $post->ID ); // o algún otro campo ACF

                    /**
                     * 2) OBTENER TODAS LAS IMÁGENES DESDE EL CAMPO WYSIWYG
                     */
                    $slider_html = get_field( 'imagenes_slider', $post->ID );
                    $image_urls  = [];

                    if ( $slider_html ) {
                        // Intentar con DOMDocument (si está disponible)
                        if ( class_exists( 'DOMDocument' ) ) {
                            libxml_use_internal_errors( true );
                            $dom = new DOMDocument();
                            // Forzamos UTF-8 para evitar warnings
                            $dom->loadHTML( '<?xml encoding="utf-8" ?>' . $slider_html );
                            $imgs = $dom->getElementsByTagName( 'img' );

                            foreach ( $imgs as $img ) {
                                $src = $img->getAttribute( 'src' );
                                if ( $src ) {
                                    $image_urls[] = $src;
                                }
                            }
                            libxml_clear_errors();
                        }

                        // Fallback por regex si DOMDocument no está disponible
                        if ( empty( $image_urls ) ) {
                            if ( preg_match_all( '/<img[^>]+src=[\'"]([^\'"]+)[\'"]/i', $slider_html, $matches ) ) {
                                $image_urls = $matches[1];
                            }
                        }
                    }

                    /**
                     * 3) SI NO ENCONTRAMOS NINGUNA IMAGEN EN EL WYSIWYG,
                     *    USAMOS LA IMAGEN DESTACADA COMO ÚNICO SLIDE
                     */
                    if ( empty( $image_urls ) && has_post_thumbnail( $post->ID ) ) {
                        $image_urls[] = get_the_post_thumbnail_url( $post->ID, 'full' );
                    }

                    /**
                     * 4) PINTAR UN SLIDE POR CADA IMAGEN DEL PROYECTO
                     */
                    if ( ! empty( $image_urls ) ) :
                        foreach ( $image_urls as $image_url ) :
                            ?>
                            <div class="swiper-slide">
                                <div class="parallax-layer layer-1">
                                    <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $slide_title ); ?>">
                                </div>

                                <?php if ( $slide_title || $slide_description ) : ?>
                                    <div class="slider-caption">
                                        <?php if ( $slide_title ) : ?>
                                            <h2><?php echo esc_html( $slide_title ); ?></h2>
                                        <?php endif; ?>

                                        <?php if ( $slide_description ) : ?>
                                            <p><?php echo esc_html( $slide_description ); ?></p>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php
                        endforeach;
                    endif;

                endforeach;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</div>
