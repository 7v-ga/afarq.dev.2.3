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
                
            <div class="tab-content active" id="detalles">

              <?php echo '<h1 class="inside_h1">'.get_the_title().'</h1><br>'; ?>
              <ul class="detalle-lista">
                <?php if ($arquitectura = get_field('arquitectura')) : ?>
                  <li><strong>Arquitectura:</strong> <?php echo esc_html($arquitectura); ?></li>
                <?php endif; ?>
                <?php if ($superficie = get_field('superficie')) : ?>
                  <li><strong>Superficie:</strong> <?php echo esc_html($superficie); ?></li>
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
          <?php
// Obtener lista ordenada desde la página de inicio (suponiendo ID = 2 o usando slug)
$inicio = get_page_by_path('inicio'); // o get_post(2);
$lista = get_field('galeria_proyectos', $inicio);

$proyecto_actual_id = get_the_ID();
$anterior = null;
$siguiente = null;

if ($lista && is_array($lista)) {
  $ids = array_map(function ($item) {
    return is_object($item) ? $item->ID : intval($item);
  }, $lista);

  $pos = array_search($proyecto_actual_id, $ids);

  if ($pos !== false) {
    $anterior_id = $ids[$pos - 1] ?? null;
    $siguiente_id = $ids[$pos + 1] ?? null;

    $anterior = $anterior_id ? get_post($anterior_id) : null;
    $siguiente = $siguiente_id ? get_post($siguiente_id) : null;
  }
}
?>

<div class="proyecto-navegacion">
  <?php if ($anterior) : ?>
    <a class="nav-arrow prev" href="<?php echo get_permalink($anterior); ?>" aria-label="Proyecto anterior">
      <span class="arrow">&larr;</span>
      <span class="label"><?php echo esc_html(get_the_title($anterior)); ?></span>
    </a>
  <?php endif; ?>

  <a class="nav-arrow view-all" href="<?php echo esc_url(site_url('/nuestros-proyectos')); ?>" aria-label="Ver todos los proyectos">
    <span class="label">Ver todos</span>
    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/view-all.svg" alt="Ver todos" />
  </a>

  <?php if ($siguiente) : ?>
    <a class="nav-arrow next" href="<?php echo get_permalink($siguiente); ?>" aria-label="Proyecto siguiente">
      <span class="label"><?php echo esc_html(get_the_title($siguiente)); ?></span>
      <span class="arrow">&rarr;</span>
    </a>
  <?php endif; ?>
</div>


      </article>

  <?php endwhile;
  endif; ?>

</main><!-- #primary -->

<?php get_footer(); ?>

