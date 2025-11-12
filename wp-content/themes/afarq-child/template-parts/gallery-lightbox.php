<?php
$galeria_html = get_field('galeria_html');
$distribucion_txt = get_field('galeria_distribucion');

if (!$galeria_html) return;

preg_match_all('/<img[^>]+>/i', $galeria_html, $matches);
$imagenes = $matches[0];
$total = count($imagenes);
$index = 0;

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

  $ratios = [];
  $imagenes_fila = [];

  for ($j = 0; $j < $cantidad_por_fila && $index < $total; $j++, $index++) {
    $img_html = $imagenes[$index];

    preg_match('/src="([^"]+)"/', $img_html, $src_match);
    $src = $src_match[1] ?? '';
    $attachment_id = attachment_url_to_postid($src);

    $ratio = 1; // default cuadrado
    if ($attachment_id) {
      $meta = wp_get_attachment_metadata($attachment_id);
      $w = $meta['width'] ?? 1;
      $h = $meta['height'] ?? 1;
      $ratio = $w / $h;
    }

    $imagenes_fila[] = [
      'src' => esc_url($src),
      'ratio' => $ratio,
      'attachment_id' => $attachment_id,
    ];
  }

  $total_ratio = array_sum(array_column($imagenes_fila, 'ratio'));

  foreach ($imagenes_fila as $img) {
    $flex = $img['ratio'] / $total_ratio;

    echo '<div class="image-wrapper" style="flex: ' . $flex . '">';
    echo '<img 
      src="' . esc_url($img['src']) . '" 
      data-full="' . esc_url($img['src']) . '"
      loading="lazy"
      decoding="async"
      class="lightbox-img"
      alt="" />';
    echo '</div>';
  }

  echo '</div>';
}

echo '</div>';
