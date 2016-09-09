<?php
/* 
Plugin Name: UKMdocs
Plugin URI: http://www.github.com/UKMNorge/UKMdocs
Description: Verktøy som laster opp dokumenter til nettsiden og tilgjengeliggjør de i form av shortcodes. Må aktiveres på undersiden Arrangør, ikke site-wide.
Author: UKM Norge / A Hustad
Version: 0.1 
Author URI: http://www.github.com/AsgeirSH/
*/

## Shortcode setup:
add_shortcode('ukmdocs', 'UKMdocs_shortcode_parser');


#var_dump($blog);
if (is_admin() && get_current_blog_id() == (UKM_HOSTNAME == 'ukm.dev' ? 13 : 881) )  {
	add_action('admin_menu', 'UKMdok_menu');
}

function UKMdok_menu() {
	$page = add_menu_page('Dokumenter', 'Dokumenter', 'superadmin', 'UKMdocs', 'UKMdocs', 'http://ico.ukm.no/chart-16.png', 155);

	add_action( 'admin_print_styles-' . $page, 'UKMdokumenter_scripts_and_styles' );
}

function UKMdocs() {
	$TWIGdata = [];
	$action = isset($_GET['action']) ? $_GET['action'] : 'dokumenter';
	require_once('controller/'.$action.'.controller.php');

	echo TWIG( $action.'.html.twig', $TWIGdata, dirname(__FILE__), true);
}

function UKMdokumenter_scripts_and_styles() {
	wp_enqueue_style('UKMwp_dashboard_css');
	wp_enqueue_script('WPbootstrap3_js');
	wp_enqueue_style('WPbootstrap3_css');
	wp_enqueue_media();
}

# Selve entry-point til parseren.
# Denne kalles når Wordpress har funnet shortcoden vår i teksten.
# Kan returnere HTML.
function UKMdocs_shortcode_parser($attributes) {
	$attributes = shortcode_atts( array(
		'cat' => null,
		'doc' => null
		), $attributes, 'ukmdocs' );

	require_once('shortcodes.php');
	return parse($attributes);
}