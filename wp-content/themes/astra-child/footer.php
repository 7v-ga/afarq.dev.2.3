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
    <div id="lightbox-overlay" class="lightbox-overlay">
      <div id="lightbox-loader" class="lightbox-loader"></div>
      <button class="lightbox-close" aria-label="Cerrar"></button>
      <img id="lightbox-image" src="" alt="">
      <button class="lightbox-nav prev" aria-label="Anterior"></button>
      <button class="lightbox-nav next" aria-label="Siguiente"></button>
    </div>

	</body>
</html>
