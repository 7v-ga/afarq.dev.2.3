<?php
// Cargar estilo del tema padre
function my_theme_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_uri(), array('parent-style'), time()); // Actualiza la versión
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');

// Cargar Scripts de JS
function my_theme_enqueue_scripts() {
    // Cargar header.js
    wp_enqueue_script('header-js', get_stylesheet_directory_uri() . '/js/header.js', array(), time(), true);
    
    // Cargar menú hamburguesa
    wp_enqueue_script('menu-hamburger', get_stylesheet_directory_uri() . '/js/menu-hamburger.js', array('jquery'), time(), true);
    
    if (is_front_page()) {
        // Cargar Swiper CSS y JS
        wp_enqueue_style('swiper-css', 'https://unpkg.com/swiper/swiper-bundle.min.css');
        wp_enqueue_script('swiper-js', 'https://unpkg.com/swiper/swiper-bundle.min.js', array('jquery'), null, true);
        wp_enqueue_script('custom-swiper-js', get_stylesheet_directory_uri() . '/js/custom-swiper.js', array('swiper-js'), null, true);
        
        // Cargar el script metrics.js
        wp_enqueue_script('metrics-js', get_stylesheet_directory_uri() . '/js/metrics.js', array(), time(), true);

        // Carga oitstanding-projects.js
        wp_enqueue_script('outstanding-projects', get_stylesheet_directory_uri() . '/js/outstanding-projects.js', array(), time(), true);
    }
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_scripts');

// Soporte para archivos SVG
function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');  

// Menú Hamburguesa
function my_theme_setup() {
    register_nav_menu('primary', __('Primary Menu'));
    register_nav_menu('hamburger-menu', __('Hamburger Menu'));
    register_nav_menu('social-menu', __('Social Menu'));
}
add_action('after_setup_theme', 'my_theme_setup');

// Meta box para métricas
function add_custom_metrics_meta_box() {
    global $post;
    if ($post->post_type == 'page' && $post->post_name == 'inicio') {
        add_meta_box(
            'custom_metrics', 
            'Métricas', 
            'render_custom_metrics_meta_box', 
            'page', 
            'normal', 
            'high'
        );
    }
}
add_action('add_meta_boxes', 'add_custom_metrics_meta_box');

function render_custom_metrics_meta_box($post) {
    $projects_completed = get_post_meta($post->ID, 'projects_completed', true);
    $years_experience = get_post_meta($post->ID, 'years_experience', true);
    $area_built = get_post_meta($post->ID, 'area_built', true);
    ?>
    <label for="projects_completed">Proyectos realizados:</label>
    <input type="number" id="projects_completed" name="projects_completed" value="<?php echo esc_attr($projects_completed); ?>" />

    <label for="years_experience">Años de experiencia:</label>
    <input type="number" id="years_experience" name="years_experience" value="<?php echo esc_attr($years_experience); ?>" />

    <label for="area_built">m<sup>2</sup> Construidos:</label>
    <input type="number" id="area_built" name="area_built" value="<?php echo esc_attr($area_built); ?>" />
    <?php
}

function save_custom_metrics_meta_box($post_id) {
    if (array_key_exists('projects_completed', $_POST)) {
        update_post_meta($post_id, 'projects_completed', $_POST['projects_completed']);
    }
    if (array_key_exists('years_experience', $_POST)) {
        update_post_meta($post_id, 'years_experience', $_POST['years_experience']);
    }
    if (array_key_exists('area_built', $_POST)) {
        update_post_meta($post_id, 'area_built', $_POST['area_built']);
    }
}
add_action('save_post', 'save_custom_metrics_meta_box');

function display_metrics() {
    $post_id = get_the_ID();
    $projects_completed = get_post_meta($post_id, 'projects_completed', true);
    $years_experience = get_post_meta($post_id, 'years_experience', true);
    $area_built = get_post_meta($post_id, 'area_built', true);

    ob_start(); // Iniciar la captura de salida
    ?>
    <div class="metric-container inner-layout">
        <div class="metric">
            <p class="metric-count"><?php echo esc_html($projects_completed); ?></p>
            <p class="metric-label">Proyectos Completados</p>
        </div>
        <div class="metric">
            <p class="metric-count"><?php echo esc_html($years_experience); ?></p>
            <p class="metric-label">Años de Experiencia</p>
        </div>
        <div class="metric">
            <p class="metric-count"><?php echo esc_html($area_built); ?></p>
            <p class="metric-label">m<sup>2</sup> Construidos</p>
        </div>
    </div>

    <?php
    return ob_get_clean(); // Devolver el contenido capturado
}
add_shortcode('metrics', 'display_metrics');

// Clase para el menú social
class Social_Menu_Walker extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $url = esc_url($item->url);
        $title = esc_html($item->title);
        $icon_class = 'social-icon ' . strtolower($title) . '-icon';

        $output .= '<li>';
        $output .= '<a href="' . $url . '" target="_blank" class="' . $icon_class . '" aria-label="' . $title . '">';
        $output .= '<i class="fab fa-' . strtolower($title) . '"></i>';
        $output .= '</a>'; 
        $output .= '</li>';
    }
}

// Cargar Font Awesome
function enqueue_font_awesome() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), null, false);
}
add_action('wp_enqueue_scripts', 'enqueue_font_awesome');

// Proyectos destacadoss
function outstanding_projects_shortcode() {
    ob_start(); // Iniciar la captura de salida

    // Cambia '8' por tu ID de categoría deseada
    $args = array(
        'category' => 8,
        'post_type' => 'post',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'featured', // Por si tienes un campo personalizado para los destacados
                'value' => '1',
                'compare' => '='
            )
        )
    );

    $projects_query = new WP_Query($args);

    if ($projects_query->have_posts()) {
    echo '<div class="outstanding-projects">';
    echo '<div class="projects-grid">'; // Contenedor para la grilla de proyectos
    while ($projects_query->have_posts()) {
        $projects_query->the_post();
        $codigo = get_post_meta(get_the_ID(), 'codigo', true);
        $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
        ?>
        <div class="project-item">
            <?php if ($featured_image) : ?>
                <img src="<?php echo esc_url($featured_image); ?>" alt="<?php the_title(); ?>" />
            <?php endif; ?>
            <?php if ($codigo) : ?>
                <h3><?php echo esc_html($codigo); ?></h3> <!-- Imprimir el código -->
            <?php else : ?>
                <h3>No hay código disponible.</h3> <!-- Mensaje alternativo -->
            <?php endif; ?>
        </div>
        <?php
    }
    // Repetir los proyectos para el efecto de loop infinito
    while ($projects_query->have_posts()) {
        $projects_query->the_post();
        $codigo = get_post_meta(get_the_ID(), 'codigo', true);
        $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
        ?>
        <div class="project-item thumbnail">
            <div class="thumbnail"> 
            <?php if ($featured_image) : ?>
                <img src="<?php echo esc_url($featured_image); ?>" alt="<?php the_title(); ?>" />
            <?php endif; ?>
            </div>
            <?php if ($codigo) : ?>
                <h3><?php echo esc_html($codigo); ?></h3> <!-- Imprimir el código -->
            <?php else : ?>
                <h3>No hay código disponible.</h3> <!-- Mensaje alternativo -->
            <?php endif; ?>
        </div>
        <?php
    }
    echo '</div>'; // Cerrar contenedor de proyectos
    echo '</div>'; // Cerrar div principal
    } else {
        echo '<p>No hay proyectos destacados disponibles.</p>';
    }

    wp_reset_postdata(); // Restablecer la consulta
    return ob_get_clean(); // Devolver el contenido capturado
}

// Registrar el shortcode
add_shortcode('outstanding_projects', 'outstanding_projects_shortcode');