<div class="menu-toggle" id="mobile-menu">
    <span class="bar"></span>
    <span class="bar"></span>
    <span class="bar"></span>
</div>
<nav class="nav">
    <?php
        wp_nav_menu(array(
            'theme_location' => 'hamburger-menu', // Asegúrate de que esta ubicación esté registrada en functions.php
            'menu_class' => 'nav-list',
            'container' => false,
        ));
    ?>
</nav>