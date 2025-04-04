<?php
/**
 * Galería con distribución definida por campo ACF 'galeria_distribucion'
 * Imágenes vienen del contenido (campo 'galeria_html'), recortadas
 */

$galeria_html = get_field('galeria_html');
$distribucion = get_field('galeria_distribucion');

// Obtener imágenes desde el contenido WYSIWYG
preg_match_all('/<img[^>]+>/i', $galeria_html, $matches);
$imagenes = $matches[0] ?? [];

if (empty($imagenes)) return;

$filas = explode(',', $distribucion);
$filas = array_map('intval', array_filter($filas)); // limpiar entradas vacías o no numéricas

$total = count($imagenes);
$i = 0;

echo '<div class="horizontal-gallery">';

foreach ($filas as $cantidad) {
  if ($i >= $total) break;

  echo '<div class="image-row">';

  for ($j = 0; $j < $cantidad && ($i + $j) < $total; $j++) {
    $img_html = $imagenes[$i + $j];

    // Extraer src original
    preg_match('/src="([^"]+)"/', $img_html, $src_match);
    $src = $src_match[1] ?? '';
    $attachment_id = attachment_url_to_postid($src);

    if ($attachment_id) {
      $thumb = wp_get_attachment_image_src($attachment_id, 'large')[0];
      $full = wp_get_attachment_url($attachment_id);
    } else {
      $thumb = $src;
      $full = preg_replace('/-\d+x\d+\.(jpg|jpeg|png|webp)/i', '.$1', $src);
    }

    echo '<img 
      src="' . esc_url($thumb) . '" 
      data-full="' . esc_url($full) . '" 
      loading="lazy" 
      decoding="async" 
      class="lightbox-img" 
      alt="" />';
  }

  echo '</div>'; // .image-row
  $i += $cantidad;
}

echo '</div>'; // .horizontal-gallery
?>
