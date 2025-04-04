<?php
/**
 * The header for AFARQ (Astra Child) Theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content>
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?><!DOCTYPE html>
<?php astra_html_before(); ?>
<html <?php language_attributes(); ?>>
<head>
<?php astra_head_top(); ?>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php
if ( apply_filters( 'astra_header_profile_gmpg_link', true ) ) {
	?>
	<link rel="profile" href="https://gmpg.org/xfn/11"> 
	<?php
}
?>
<?php wp_head(); ?>
<?php astra_head_bottom(); ?>
</head>

<body <?php astra_schema_body(); ?> <?php body_class(); ?>>
<?php astra_body_top(); ?>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#content" title="<?php echo esc_attr( astra_default_strings( 'string-header-skip-link', false ) ); ?>">
	<?php echo esc_html( astra_default_strings( 'string-header-skip-link', false ) ); ?>
</a>

<link href="https://fonts.cdnfonts.com/css/neou" rel="stylesheet">

<div id="page" class="hfeed site">
	<?php
	astra_header_before();

	// Main Header Layout
	?>
	<header class="main-header-bar-wrap">
		<div <?php echo wp_kses_post( astra_attr( 'main-header-bar' ) ); ?>>
			<?php astra_main_header_bar_top(); ?>
			<div class="ast-container">
				<div class="ast-flex main-header-container">
                    <div class="site-header-2-section-left-center site-header-section ast-flex ast-grid-section-left-center"></div>
                    <div class="site-header-2-section-center site-header-section ast-flex ast-grid-section-center">
					<?php 
					astra_masthead_content(); // Contenido principal del encabezado
                    ?>
                    </div>
                    <div class="site-header-2-section-right-center site-header-section ast-flex ast-grid-right-center-section">
                        <?php
					    get_template_part('template-parts/header/menu-social'); // Incluir el menú hamburguesa
					    get_template_part('template-parts/header/menu-hamburger'); // Incluir el menú hamburguesa
					    ?>
                    </div>
				</div><!-- Main Header Container -->
			</div><!-- ast-container -->
			<?php astra_main_header_bar_bottom(); ?>
		</div><!-- Main Header Bar -->
	</header><!-- Main Header Bar Wrap -->

	<?php astra_header_after(); ?>

	<div id="content" class="site-content">
		<div class="ast-container">
		<?php astra_content_top(); ?>