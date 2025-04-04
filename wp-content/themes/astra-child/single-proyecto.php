<?php
/**
 * Template Name: Proyecto Individual
 * Template Post Type: proyecto
 */

get_header(); ?>

<main id="primary" class="site-main single-proyecto">
  <?php if (have_posts()) :
    while (have_posts()) : the_post(); ?>

      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <!-- Imagen destacada en full width -->
        <?php if (has_post_thumbnail()) : ?>
          <div class="proyecto-featured-image">
            <?php the_post_thumbnail('full'); ?>
          </div>
        <?php endif; ?>

        <div class="proyecto-content-wrapper">
                
          <!-- Tabs -->
          <div class="proyecto-tabs">
            <div class="tab-buttons">
              <button class="tab-btn active" data-tab="descripcion">Descripción</button>
              <button class="tab-btn" data-tab="detalles">Detalles</button>
            </div>
                
            <div class="tab-content active" id="descripcion">
              <?php
                // Dividir el contenido en dos partes (por párrafo)
                $content = apply_filters('the_content', get_the_content());
                $parts = explode('</p>', $content);
                $first_half = implode('</p>', array_slice($parts, 0, ceil(count($parts) / 2))) . '</p>';
                $second_half = implode('</p>', array_slice($parts, ceil(count($parts) / 2)));
              ?>
              <div class="descripcion-extracto">
                <?php echo '<h1 class="inside_h1">'.get_the_title().'</h1>'.$first_half; ?>
              </div>
              <div class="descripcion-completa" style="display: none;">
                <?php echo $second_half; ?>
              </div>
              <button class="leer-mas-btn">Leer más</button>
            </div>
                
            <div class="tab-content" id="detalles">
              <ul class="detalle-lista">
                <?php if ($ubicacion = get_field('ubicacion')) : ?>
                  <li><strong>Ubicación:</strong> <?php echo esc_html($ubicacion); ?></li>
                <?php endif; ?>
                <?php if ($ano = get_field('ano')) : ?>
                  <li><strong>Año:</strong> <?php echo esc_html($ano); ?></li>
                <?php endif; ?>
                <?php if ($tipo = get_field('tipo')) : ?>
                  <li><strong>Tipo:</strong> <?php echo esc_html($tipo); ?></li>
                <?php endif; ?>
              </ul>
            </div>
          </div>
              
        </div>
          <div class="proyecto-galería">
            <?php
            $galeria = get_field('galeria_html');
            if ($galeria) {
              get_template_part('template-parts/gallery-lightbox');
            }
            ?>
          </div>
          <?php
          $current_id = get_the_ID();

          $prev_post = get_posts(array(
            'post_type' => 'proyecto',
            'posts_per_page' => 1,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_status' => 'publish',
            'exclude' => array($current_id),
            'date_query' => array(
              array(
                'before' => get_the_date('c'),
              ),
            ),
          ));

          $next_post = get_posts(array(
            'post_type' => 'proyecto',
            'posts_per_page' => 1,
            'orderby' => 'date',
            'order' => 'ASC',
            'post_status' => 'publish',
            'exclude' => array($current_id),
            'date_query' => array(
              array(
                'after' => get_the_date('c'),
              ),
            ),
          ));

          ?>
          <div class="proyecto-navegacion">
            <?php if (!empty($prev_post)) : ?>
              <a class="nav-arrow prev" href="<?php echo get_permalink($prev_post[0]); ?>" aria-label="Proyecto anterior">
                <span class="arrow">&larr;</span>
                <span class="label"><?php echo esc_html(get_the_title($prev_post[0])); ?></span>
              </a>
            <?php endif; ?>         

            <?php if (!empty($next_post)) : ?>
              <a class="nav-arrow next" href="<?php echo get_permalink($next_post[0]); ?>" aria-label="Proyecto siguiente">
                <span class="label"><?php echo esc_html(get_the_title($next_post[0])); ?></span>
                <span class="arrow">&rarr;</span>
              </a>
            <?php endif; ?>
          </div>

      </article>

  <?php endwhile;
  endif; ?>

</main><!-- #primary -->

<?php get_footer(); ?>
