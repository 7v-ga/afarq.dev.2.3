<?php
/**
 * Template Name: Galería de Proyectos
 */

get_header(); ?>

<main id="primary" class="site-main proyectos-archivo">
  <section class="proyectos-galeria">
    <?php echo '<p style="color:red">Template cargado ✔</p>'; ?>
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

<style>
.fila-proyectos {
  display: flex;
  gap: 1s0px;
  width: 100%;
  align-items: stretch;
  opacity: 0;
  transform: translateY(20px);
  transition: opacity 0.8s ease, transform 0.8s ease;
  overflow: hidden;
  height: auto;
}

.fila-proyectos.visible {
  opacity: 1;
  transform: translateY(0);
}

.fila-1 .proyecto-item { flex: 0 0 calc(100%); }
.fila-2 .proyecto-item { flex: 0 0 calc(50% - 10px); }
.fila-3 .proyecto-item { flex: 0 0 calc(33.3333% - 13.33px); }
.fila-4 .proyecto-item { flex: 0 0 calc(25% - 15px); }

.proyecto-item {
  position: relative;
  overflow: hidden;
  text-decoration: none;
  color: var(--ast-global-color-2);
  display: flex;
  flex-direction: column;
  height: 100%;
  color: 
}

.proyecto-thumb {
  width: 100%;
  overflow: hidden;
  display: flex;
  align-items: stretch;
}

.proyecto-thumb img {
  width: 100%;
  height: auto;
  display: block;
  transition: filter 0.4s ease, transform 0.4s ease;
  object-fit: cover;
}

.proyecto-info {
  position: absolute;
  inset: 0;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  color: var(--ast-global-color-1);
  text-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
  background: rgba(0, 0, 0, 0);
  opacity: 0;
  transition: opacity 0.3s ease;
  pointer-events: none;
  padding: 0 10px;
  text-align: center;
}

.proyecto-info h3,
.proyecto-info p {
  margin: 0;
  color: var(--ast-global-color-3);
  line-height: 1.3;
}

.proyecto-info h3 {
  font-size: 1.2rem;
}

.proyecto-info p {
  font-size: 0.95rem;
  color: var(--ast-global-color-3);
}

.proyecto-item:hover .proyecto-thumb img {
  transform: scale(1.04);
  filter: brightness(340%) saturate(10%) contrast(15%);
}

.proyecto-item:hover .proyecto-info,
.proyecto-item:hover .proyecto-info p,
.proyecto-item:hover .proyecto-info h3 {
  opacity: 1;
}

@media (max-width: 768px) {
  .fila-proyectos {
    flex-wrap: wrap;
  }
  .fila-2 .proyecto-item,
  .fila-3 .proyecto-item,
  .fila-4 .proyecto-item {
    flex: 0 0 100%;
  }
}
</style>

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
