<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<?php astra_content_bottom(); ?>
	</div> <!-- ast-container -->
	</div><!-- #content -->
<?php
	astra_content_after();

	astra_footer_before();

	astra_footer();

	astra_footer_after();
?>
	</div><!-- #page -->
<?php
	astra_body_bottom();
	wp_footer();
  if (get_post_type() =="proyecto") {
?>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');
      
        tabButtons.forEach(btn => {
          btn.addEventListener('click', () => {
            const target = btn.getAttribute('data-tab');
          
            tabButtons.forEach(b => b.classList.remove('active'));
            tabContents.forEach(tc => tc.classList.remove('active'));
          
            btn.classList.add('active');
            document.getElementById(target).classList.add('active');
          });
        });
      
        const leerMasBtn = document.querySelector('.leer-mas-btn');
        const extraContent = document.querySelector('.descripcion-completa');
        const extracto = document.querySelector('.descripcion-extracto');
      
        if (leerMasBtn && extraContent && extracto) {
          leerMasBtn.addEventListener('click', () => {
            const visible = extraContent.style.display === 'block';
            extraContent.style.display = visible ? 'none' : 'block';
            leerMasBtn.textContent = visible ? 'Leer más' : 'Leer menos';
          });
        }
      });
    </script>
    <div class="lightbox-overlay" id="lightbox-overlay">
      <?php get_template_part('template-parts/lightbox-controls'); ?>
      <img id="lightbox-image" alt="Imagen ampliada" />
    </div>  

    <?php }?>
	</body>
</html>
