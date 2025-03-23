<?php
/**
 * Template Part for Social Media Menu in Header
 *
 * @package AFARQ
 */
?>

<div class="" data-section="section-hb-social-icons-1">
    <?php 
    if (has_nav_menu('social-menu')) {
        wp_nav_menu(array(
            'theme_location' => 'social-menu',
            'container' => false,
            'items_wrap' => '<ul class="social-menu">%3$s</ul>',
            'walker' => new Social_Menu_Walker(), // Usar el walkerizador personalizado
        ));
    }
    ?>
</div>