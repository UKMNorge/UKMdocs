<?php
/* 
Plugin Name: UKMdocs
Plugin URI: http://www.github.com/UKMNorge/UKMdocs
Description: Verktøy som laster opp dokumenter til nettsiden og tilgjengeliggjør de i form av shortcodes. Må aktiveres på undersiden Arrangør, ikke site-wide.
Author: UKM Norge / A Hustad
Version: 0.1 
Author URI: http://www.github.com/AsgeirSH/
*/

if(is_admin()) {
	add_action('admin_menu', 'UKMdok_menu');
}


function UKMdok_menu() {
	$page = add_menu_page('Dokumenter', 'Dokumenter', 'superadmin', 'UKMdocs', 'UKMdocs', 'http://ico.ukm.no/chart-32.png', 155);

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
}