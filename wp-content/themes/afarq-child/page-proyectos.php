<?php
/**
 * Template Name: Galería de Proyectos Masonry
 */

get_header(); ?>

<main id="primary" class="site-main proyectos-archivo">
  <section class="proyectos-galeria">
    <?php
    $proyectos = get_field('galeria_proyectos');
    $distribucion_raw = get_field('galeria_distribucion');
    $bloques = !empty($distribucion_raw) ? explode(',', $distribucion_raw) : ['3x300'];

    $distribucion = [];
    foreach ($bloques as $bloque) {
      if (preg_match('/(\d+)x(\d+)/', trim($bloque), $matches)) {
        $distribucion[] = [
          'cantidad' => (int)$matches[1],
          'altura' => (int)$matches[2]
        ];
      }
    }

    $index = 0;
    $proyecto_idx = 0;
    $total = count($proyectos);

    while ($proyecto_idx < $total) {
      $conf = $distribucion[$index % count($distribucion)];
      $fotos_en_fila = $conf['cantidad'];
      $fila_height = $conf['altura'];

      $fila_proyectos = array_slice($proyectos, $proyecto_idx, $fotos_en_fila);

      echo '<div class="fila-proyectos fade-up" data-altura-base="' . $fila_height . '">';

      foreach ($fila_proyectos as $proyecto) {
        $thumb = get_field('imagen_grid', $proyecto->ID);
        $titulo = get_the_title($proyecto->ID);
        $link = get_permalink($proyecto->ID);

        $thumb_url = '';
        $ratio = null;
        $log = '';

        if ($thumb && is_numeric($thumb)) {
          $thumb_url = wp_get_attachment_image_url($thumb, 'large');
          $meta = wp_get_attachment_metadata($thumb);
          $width = $meta['width'] ?? 0;
          $height = $meta['height'] ?? 0;
          if ($width > 0 && $height > 0) {
            $ratio = $width / $height;
            $log = $width . 'x' . $height . ' ratio=' . round($ratio, 4);
          }
        } else {
          $thumb_url = is_string($thumb) ? esc_url($thumb) : get_stylesheet_directory_uri() . '/assets/img/placeholder.jpg';
          $log = 'fallback ratio';
        }

        $ratio_attr = $ratio ? ' data-ratio="' . round($ratio, 4) . '"' : '';
        $log_attr = $log ? ' data-log="' . esc_attr($log) . '"' : '';

        echo '<a href="' . esc_url($link) . '" class="proyecto-item"' . $ratio_attr . $log_attr . '>';
        echo '<div class="proyecto-thumb"><img src="' . $thumb_url . '" alt="' . esc_attr($titulo) . '"></div>';
        echo '<div class="proyecto-info">';
        echo '<h3>' . esc_html($titulo) . '</h3>';
        echo '</div>';
        echo '</a>';
      }

      echo '</div>';

      $proyecto_idx += $fotos_en_fila;
      $index++;
    }
    ?>
  </section>
</main>

<?php get_footer(); ?>
