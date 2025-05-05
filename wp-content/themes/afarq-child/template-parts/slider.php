<div class="full-slider">
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <?php
            // Imágenes y contenido del slider
            $slides = [
                [
                    'image' => WP_CONTENT_URL . '/uploads/2025/03/afarq-01.jpg',
                    'title' => 'Título 1',
                    'description' => 'Descripción 1',
                ],
                [
                    'image' => WP_CONTENT_URL . '/uploads/2025/03/afarq-03.jpg',
                    'title' => 'Título 3',
                    'description' => 'Descripción 3',
                ],
                [
                    'image' => WP_CONTENT_URL . '/uploads/2025/03/afarq-05.jpg',
                    'title' => 'Título 5',
                    'description' => 'Descripción 5',
                ],
                [
                    'image' => WP_CONTENT_URL . '/uploads/2025/03/afarq-06.jpg',
                    'title' => 'Título 6',
                    'description' => 'Descripción 6',
                ],
                [
                    'image' => WP_CONTENT_URL . '/uploads/2025/03/afarq-08.jpg',
                    'title' => 'Título 8',
                    'description' => 'Descripción 8',
                ],
            ];

    foreach ($slides as $slide) :
    ?>
      <div class="swiper-slide">
        <div class="parallax-layer layer-1">
          <img src="<?php echo esc_url($slide['image']); ?>" alt="">
        </div>
        <div class="parallax-layer layer-2">
            <div class="overlay">
                <h2><?php echo esc_html($slide['title']); ?></h2>
                <p><?php echo esc_html($slide['description']); ?></p>
            </div>
        </div>
      </div>
    <?php endforeach; ?>

        </div>
        
        <!-- Barra de navegación vertical
        <div class="vertical-navigation">
            <?php // foreach ($slides as $index => $slide) { ?>
                <div class="nav-item" data-slide="<?php // echo $index; ?>">
                    <span></span>
                </div>
            <?php // } ?>
        </div>
         -->
    </div>
</div>