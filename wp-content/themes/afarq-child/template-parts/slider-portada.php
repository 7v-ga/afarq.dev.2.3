<div class="full-slider">
  <div class="swiper-container">
    <div class="swiper-wrapper">
      <?php
      // Query para slides (podés usar CPT o personalizar esto)
      $args = [
        'post_type' => 'slide',
        'posts_per_page' => -1,
      ];
      $query = new WP_Query($args);

      while ($query->have_posts()) : $query->the_post();
        $img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
        if ($img_url) :
      ?>
        <div class="swiper-slide">
          <div class="parallax-layer">
            <img src="<?php echo esc_url($img_url); ?>" alt="<?php the_title_attribute(); ?>" />
          </div>
        </div>
      <?php
        endif;
      endwhile;
      wp_reset_postdata();
      ?>
    </div>
  </div>
</div>
