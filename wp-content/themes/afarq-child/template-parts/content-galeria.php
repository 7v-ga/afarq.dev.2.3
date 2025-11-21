<?php
/**
 * Template Part: Galería horizontal con lightbox fullscreen
 * Requiere campo ACF tipo WYSIWYG llamado 'galeria_html'
 */

function afarq_render_clean_gallery_from_wysiwyg($wysiwyg_html) {
  preg_match_all('/<img[^>]+>/i', $wysiwyg_html, $matches);
  $imagenes = $matches[0];
  if (empty($imagenes)) return '';

  $output = '<div class="horizontal-gallery">';
  $total = count($imagenes);
  $i = 0;
  $grupo_anterior = 0;

  while ($i < $total) {
    do {
      $grupo = rand(1, 3);
    } while ($grupo === $grupo_anterior && $total - $i > 1);
    $grupo_anterior = $grupo;

    if ($i + $grupo > $total) {
      $grupo = $total - $i;
    }

    $altura = rand(1, 3);
    $output .= '<div class="image-row h-row-' . $altura . '">';

    for ($j = 0; $j < $grupo; $j++) {
      $img_html = $imagenes[$i + $j];
      preg_match('/src="([^"]+)"/', $img_html, $src_match);
      $src = $src_match[1] ?? '';
      $attachment_id = attachment_url_to_postid($src);

      if ($attachment_id) {
        $thumb = wp_get_attachment_image_src($attachment_id, 'medium_large')[0];
        $full = wp_get_attachment_url($attachment_id);
      } else {
        $thumb = $src;
        $full = preg_replace('/-\d+x\d+\.(jpg|png|webp)/i', '.$1', $src);
      }

      $output .= '<img 
        src="' . esc_url($thumb) . '" 
        data-full="' . esc_url($full) . '" 
        loading="lazy" 
        decoding="async" 
        class="lightbox-img" 
        alt="" />';
    }

    $output .= '</div>';
    $i += $grupo;
  }

  $output .= '</div>';
  return $output;
}

// Render desde campo ACF
$galeria = get_field('galeria_html');
if ($galeria) {
  echo afarq_render_clean_gallery_from_wysiwyg($galeria);
}
?>
