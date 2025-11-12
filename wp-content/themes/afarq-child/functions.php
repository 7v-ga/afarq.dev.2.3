<?php
// Cargar estilo y scripts del tema
function my_theme_enqueue_assets() {
    // Estilos
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_uri(), array('parent-style'), time());

    // FontAwesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), null, false);

    // Scripts comunes
    wp_enqueue_script('header-js', get_stylesheet_directory_uri() . '/assets/js/header.js', array(), time(), true);
    wp_enqueue_script('menu-hamburger', get_stylesheet_directory_uri() . '/assets/js/menu-hamburger.js', array('jquery'), time(), true);

    if (is_front_page()) {
        wp_enqueue_style('swiper-css', 'https://unpkg.com/swiper/swiper-bundle.min.css');
        wp_enqueue_script('swiper-js', 'https://unpkg.com/swiper/swiper-bundle.min.js', array('jquery'), null, true);
        wp_enqueue_style('single-proyecto', get_stylesheet_directory_uri() . '/assets/css/slider-portada.css', array(), time());
        wp_enqueue_script('custom-swiper-js', get_stylesheet_directory_uri() . '/assets/js/slider-portada.js', array('swiper-js'), time(), true);
        wp_enqueue_script('metrics-js', get_stylesheet_directory_uri() . '/assets/js/metrics.js', array(), time(), true);
    }

    if (is_singular('proyecto')) {
        wp_enqueue_style('single-proyecto', get_stylesheet_directory_uri() . '/assets/css/single-proyecto.css', array(), time());
        wp_enqueue_script('lightbox-js', get_stylesheet_directory_uri() . '/assets/js/lightbox.js', array(), time(), true);
    }

    global $post;
    if (isset($post) && has_shortcode($post->post_content, 'galeria_proyectos')) {
        wp_enqueue_style('gallery-grid', get_stylesheet_directory_uri() . '/assets/css/page-proyectos.css', [], time());
        wp_enqueue_script('proyectos-script', get_stylesheet_directory_uri() . '/assets/js/masonry-proyectos.js', [], time(), true);
    }

}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_assets');

// Soporte para archivos SVG
function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

// Menús
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
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['projects_completed'])) {
        update_post_meta($post_id, 'projects_completed', sanitize_text_field($_POST['projects_completed']));
    }
    if (isset($_POST['years_experience'])) {
        update_post_meta($post_id, 'years_experience', sanitize_text_field($_POST['years_experience']));
    }
    if (isset($_POST['area_built'])) {
        update_post_meta($post_id, 'area_built', sanitize_text_field($_POST['area_built']));
    }
}
add_action('save_post', 'save_custom_metrics_meta_box');

// Shortcode de métricas
function display_metrics() {
    $post_id = get_the_ID();
    $projects_completed = get_post_meta($post_id, 'projects_completed', true);
    $years_experience = get_post_meta($post_id, 'years_experience', true);
    $area_built = get_post_meta($post_id, 'area_built', true);

    ob_start();
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
    return ob_get_clean();
}
add_shortcode('metrics', 'display_metrics');

// Clase personalizada para menú social
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

// Cargar post adyacentes
function afarq_adjacent_post_types($post_types) {
    $post_types[] = 'proyecto';
    return $post_types;
}
add_filter('get_previous_post_where', function($where) {
    global $post;
    if ($post && $post->post_type === 'proyecto') {
        return str_replace("post_type = 'post'", "post_type = 'proyecto'", $where);
    }
    return $where;
});
add_filter('get_next_post_where', function($where) {
    global $post;
    if ($post && $post->post_type === 'proyecto') {
        return str_replace("post_type = 'post'", "post_type = 'proyecto'", $where);
    }
    return $where;
});

add_filter('get_previous_post_where', 'afarq_prev_post_type_fix');
add_filter('get_next_post_where', 'afarq_next_post_type_fix');

function afarq_prev_post_type_fix($where) {
    global $post;
    if (is_singular('proyecto')) {
        $where = str_replace("post_type = 'post'", "post_type = 'proyecto'", $where);
    }
    return $where;
}

function afarq_next_post_type_fix($where) {
    global $post;
    if (is_singular('proyecto')) {
        $where = str_replace("post_type = 'post'", "post_type = 'proyecto'", $where);
    }
    return $where;
}

// Registro CPT Proyectos
function afarq_register_proyecto_post_type() {
    register_post_type('proyecto', array(
        'labels' => array(
            'name' => 'Proyectos',
            'singular_name' => 'Proyecto'
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'proyectos'),
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'afarq_register_proyecto_post_type');

// Shortcode de proyectos destacados
function outstanding_projects_shortcode() {
    ob_start();

    $args = array(
        'category' => 8,
        'post_type' => 'post',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'featured',
                'value' => '1',
                'compare' => '='
            )
        )
    );

    $projects_query = new WP_Query($args);

    if ($projects_query->have_posts()) {
        echo '<div class="outstanding-projects">';
        echo '<div class="projects-grid">';

        while ($projects_query->have_posts()) {
            $projects_query->the_post();
            $codigo = get_post_meta(get_the_ID(), 'codigo', true);
            $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
            ?>
            <div class="project-item">
                <div class="thumbnail">
                    <?php if ($featured_image) : ?>
                        <img src="<?php echo esc_url($featured_image); ?>" alt="<?php the_title(); ?>">
                    <?php endif; ?>
                </div>
                <h3><?php echo $codigo ? esc_html($codigo) : 'Sin código'; ?></h3>
            </div>
            <?php
        }

        $projects_query->rewind_posts();
        while ($projects_query->have_posts()) {
            $projects_query->the_post();
            $codigo = get_post_meta(get_the_ID(), 'codigo', true);
            $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
            ?>
            <div class="project-item thumbnail">
                <div class="thumbnail">
                    <?php if ($featured_image) : ?>
                        <img src="<?php echo esc_url($featured_image); ?>" alt="<?php the_title(); ?>">
                    <?php endif; ?>
                </div>
                <h3><?php echo $codigo ? esc_html($codigo) : 'Sin código'; ?></h3>
            </div>
            <?php
        }

        echo '</div></div>';
    } else {
        echo '<p>No hay proyectos destacados disponibles.</p>';
    }

    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('outstanding_projects', 'outstanding_projects_shortcode');

// Fade-up del contenido con clase fade-up
function afarq_enqueue_scroll_anim() {
  wp_enqueue_script(
    'afarq-fade-scroll',
    get_stylesheet_directory_uri() . '/assets/js/fadeup.js', array(), time(), true);
}
add_action('wp_enqueue_scripts', 'afarq_enqueue_scroll_anim');

// Parallax
function afarq_enqueue_parallax() {
  wp_enqueue_script(
    'afarq-parallax',
    get_stylesheet_directory_uri() . '/assets/js/parallax.js', array(), time(), true);
}
add_action('wp_enqueue_scripts', 'afarq_enqueue_parallax');

// Galería de proyectos Shortcode
function shortcode_galeria_proyectos($atts) {
    ob_start();

    $post_id = get_the_ID(); // usa el ID de la página actual
    $proyectos = get_field('galeria_proyectos', $post_id);
    $distribucion_raw = get_field('galeria_distribucion', $post_id);
    $bloques = !empty($distribucion_raw) ? explode(',', $distribucion_raw) : ['3x300'];

    if (empty($proyectos)) {
        echo '<p>No hay proyectos para mostrar.</p>';
        return ob_get_clean();
    }

    $distribucion = [];
    foreach ($bloques as $bloque) {
        if (preg_match('/(\d+)x(\d+)/', trim($bloque), $matches)) {
            $distribucion[] = [
                'cantidad' => (int)$matches[1],
                'altura' => (int)$matches[2]
            ];
        }
    }

    $index = 0;
    $proyecto_idx = 0;
    $total = count($proyectos);

    echo '<div class="proyectos-galeria">';
    while ($proyecto_idx < $total) {
        $conf = $distribucion[$index % count($distribucion)];
        $fotos_en_fila = $conf['cantidad'];
        $fila_height = $conf['altura'];

        $fila_proyectos = array_slice($proyectos, $proyecto_idx, $fotos_en_fila);

        echo '<div class="fila-proyectos fade-up" data-altura-base="' . $fila_height . '">';

        foreach ($fila_proyectos as $proyecto) {
            $thumb = get_field('imagen_grid', $proyecto->ID);
            $titulo = get_the_title($proyecto->ID);
            $link = get_permalink($proyecto->ID);

            $thumb_url = '';
            $ratio = null;

            if ($thumb && is_numeric($thumb)) {
                $thumb_url = wp_get_attachment_image_url($thumb, 'large');
                $meta = wp_get_attachment_metadata($thumb);
                $width = $meta['width'] ?? 0;
                $height = $meta['height'] ?? 0;
                if ($width > 0 && $height > 0) {
                    $ratio = $width / $height;
                }
            } else {
                $thumb_url = is_string($thumb) ? esc_url($thumb) : get_stylesheet_directory_uri() . '/assets/img/placeholder.jpg';
            }

            $ratio_attr = $ratio ? ' data-ratio="' . round($ratio, 4) . '"' : '';

            echo '<a href="' . esc_url($link) . '" class="proyecto-item"' . $ratio_attr . '>';
            echo '<div class="proyecto-thumb"><img src="' . $thumb_url . '" alt="' . esc_attr($titulo) . '"></div>';
            echo '<div class="proyecto-info"><h3>' . esc_html($titulo) . '</h3></div>';
            echo '</a>';
        }

        echo '</div>';

        $proyecto_idx += $fotos_en_fila;
        $index++;
    }
    echo '</div>';

    return ob_get_clean();
}
add_shortcode('galeria_proyectos', 'shortcode_galeria_proyectos');
