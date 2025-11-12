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
              <div class="descripcion-extracto">
                <?php echo '<h1 class="inside_h1">'.get_the_title().'</h1>'.get_the_content(); ?>
              </div>
            </div>
                
            <div class="tab-content" id="detalles">
              <ul class="detalle-lista">
                <?php if ($arquitectura = get_field('arquitectura')) : ?>
                  <li><strong>Arquitectura:</strong> <?php echo esc_html($arquitectura); ?></li>
                <?php endif; ?>
                <?php if ($ingenieria = get_field('ingenieria')) : ?>
                  <li><strong>Ingeniería:</strong> <?php echo esc_html($ingenieria); ?></li>
                <?php endif; ?>
                <?php if ($construccion = get_field('construccion')) : ?>
                  <li><strong>Construcción:</strong> <?php echo esc_html($construccion); ?></li>
                <?php endif; ?>
                <?php if ($ubicacion = get_field('ubicacion')) : ?>
                  <li><strong>Ubicación:</strong> <?php echo esc_html($ubicacion); ?></li>
                <?php endif; ?>
                <?php if ($fotografia = get_field('fotografia')) : ?>
                  <li><strong>Fotografía:</strong> <?php echo esc_html($fotografia); ?></li>
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
            <a class="nav-arrow view-all" href="<?php echo esc_url(site_url('/nuestros-proyectos')); ?>" aria-label="Ver todos los proyectos">
              <span class="label">Ver todos</span>
              <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/view-all.svg" alt="Ver todos" />
            </a>
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

