<?php
/**
 * Muestra una galería tipo Masonry a partir de un campo WYSIWYG ACF (galeria_html)
 */

$galeria = get_field('galeria_html');

if ($galeria) {
  // Extrae solo las etiquetas <img> del contenido
  preg_match_all('/<img[^>]+>/i', $galeria, $imagenes);

  if (!empty($imagenes[0])) : ?>
    <div class="masonry-gallery">
      <div class="masonry-sizer"></div>
      <?php foreach ($imagenes[0] as $img) : ?>
        <div class="masonry-item"><?php echo $img; ?></div>
      <?php endforeach; ?>
    </div>
<?php endif;
}
?>
