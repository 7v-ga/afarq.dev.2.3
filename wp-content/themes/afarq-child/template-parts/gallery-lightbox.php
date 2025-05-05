<?php
$galeria_html = get_field('galeria_html');
$distribucion_txt = get_field('galeria_distribucion');

if (!$galeria_html) return;

// Extraer <img> del WYSIWYG
preg_match_all('/<img[^>]+>/i', $galeria_html, $matches);
$imagenes = $matches[0];
$total = count($imagenes);
$index = 0;

// Procesar distribución escrita como: "3,2,1"
$distribucion = array_filter(array_map('intval', explode(',', $distribucion_txt)));

if (empty($distribucion)) {
  echo '<p style="color: #999;">No se ha definido una distribución válida.</p>';
  return;
}

echo '<div class="horizontal-gallery">';

foreach ($distribucion as $fila => $cantidad_por_fila) {
  if ($index >= $total) {
    echo '<p style="color: #ccc;">⚠️ No hay suficientes imágenes para la fila ' . ($fila + 1) . '.</p>';
    break;
  }

  echo '<div class="image-row">';

  for ($j = 0; $j < $cantidad_por_fila && $index < $total; $j++, $index++) {
    $img_html = $imagenes[$index];

    // Obtener src original
    preg_match('/src="([^"]+)"/', $img_html, $src_match);
    $src = $src_match[1] ?? '';
    $attachment_id = attachment_url_to_postid($src);

    if ($attachment_id) {
      $full = wp_get_attachment_url($attachment_id);
    } else {
      $full = preg_replace('/-\d+x\d+\.(jpg|png|webp)/i', '.$1', $src);
    }

    echo '<img 
      src="' . esc_url($full) . '" 
      data-full="' . esc_url($full) . '" 
      loading="lazy" 
      decoding="async" 
      class="lightbox-img" 
      alt="" />';
  }

  echo '</div>';
}

echo '</div>';
?>
