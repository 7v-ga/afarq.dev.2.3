<?php
/**
 * Template Name: Galería de Proyectos
 */

get_header(); ?>

<main id="primary" class="site-main proyectos-archivo">
  <section class="proyectos-galeria">
    <?php
    // Obtener la lista de proyectos desde campo ACF tipo "Relación"
    $proyectos = get_field('galeria_proyectos');

    // Distribución personalizada desde ACF de la página
    $distribucion_raw = get_field('galeria_distribucion');
    $distribucion = !empty($distribucion_raw)
      ? array_map('intval', explode(',', $distribucion_raw))
      : [3, 2, 1]; // fallback por defecto

    $index = 0;

    if (!empty($proyectos)) {
      $total = count($proyectos);
      $proyecto_idx = 0;

      while ($proyecto_idx < $total) {
        $fotos_en_fila = $distribucion[$index % count($distribucion)];
        $fila_proyectos = array_slice($proyectos, $proyecto_idx, $fotos_en_fila);

        echo '<div class="fila-proyectos fila-' . $fotos_en_fila . ' fade-in-row">';

        foreach ($fila_proyectos as $proyecto) {
          $ubicacion = get_field('ubicacion', $proyecto->ID);
          $thumb = get_field('imagen_grid', $proyecto->ID);
          $titulo = get_the_title($proyecto->ID);
          $link = get_permalink($proyecto->ID);

          if (!$thumb) {
            $thumb_url = get_stylesheet_directory_uri() . '/assets/img/placeholder.jpg';
          } elseif (is_array($thumb) && isset($thumb['url'])) {
            $thumb_url = esc_url($thumb['url']);
          } else {
            $thumb_url = esc_url($thumb);
          }

          echo '<a href="' . esc_url($link) . '" class="proyecto-item">';
          echo '<div class="proyecto-thumb"><img src="' . $thumb_url . '" alt="' . esc_attr($titulo) . '"></div>';
          echo '<div class="proyecto-info">';
          echo '<h3>' . esc_html($titulo) . '</h3>';
          if ($ubicacion) echo '<p>' . esc_html($ubicacion) . '</p>';
          echo '</div>';
          echo '</a>';
        }

        echo '</div>';

        $proyecto_idx += $fotos_en_fila;
        $index++;
      }
    }
    ?>
  </section>
</main>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const rows = document.querySelectorAll(".fila-proyectos");
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("visible");
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.1 }
  );

  rows.forEach((row) => observer.observe(row));
});
</script>

<?php get_footer(); ?>
