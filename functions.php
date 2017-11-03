<?php

// ---------------------------------------------------------------------------
// Enqueue theme styles and scripts
// ---------------------------------------------------------------------------

add_action( 'wp_enqueue_scripts', 'vaasawebdev_styles_and_scripts', 20 );

function vaasawebdev_styles_and_scripts() {
	wp_enqueue_style( 'vaasawebdev-styles', get_stylesheet_uri() );
	wp_enqueue_script( 'vaasawebdev-scripts', get_template_directory_uri() . '/js/theme.min.js', array( 'jquery' ), false, true );
}

// ---------------------------------------------------------------------------
// Disable emojis
// ---------------------------------------------------------------------------

add_action( 'init', 'mikrogramma_disable_emojis' );

function mikrogramma_disable_emojis() {

	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	add_filter( 'emoji_svg_url', '__return_false' );
	add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}

function disable_emojicons_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}

// ---------------------------------------------------------------------------
// Remove unnecessary stuff from HTML head
// ---------------------------------------------------------------------------

add_filter( 'xmlrpc_enabled', '__return_true' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'rel_canonical');
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
remove_action( 'rest_api_init', 'wp_oembed_register_route' );
remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
remove_action( 'wp_head', 'wp_oembed_add_host_js' );
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'feed_links', 2 );

// ---------------------------------------------------------------------------
// Enqueue jQuery in footer / remove jQuery migrate
// ---------------------------------------------------------------------------

add_action( 'wp_enqueue_scripts', 'mikrogramma_move_jquery_to_footer', 1 );

function mikrogramma_move_jquery_to_footer() {

	if( is_admin() ) {
		return;
	}

	wp_scripts()->add_data( 'jquery', 'group', 1 );
	wp_scripts()->add_data( 'jquery-core', 'group', 1 );
	wp_scripts()->add_data( 'jquery-migrate', 'group', 1 );
}

add_filter( 'wp_default_scripts', 'mikrogramma_remove_jquery_migrate' );

function mikrogramma_remove_jquery_migrate( &$scripts) {
	if( ! is_admin() ) {
		$scripts->remove( 'jquery' );
		$scripts->add( 'jquery', false, array( 'jquery-core' ), '1.12.4' );
	}
}

// ---------------------------------------------------------------------------
// Initialize widget area
// ---------------------------------------------------------------------------

add_action( 'widgets_init', 'vaasawebdev_widgets_init' );

function vaasawebdev_widgets_init() {
	register_sidebar( array(
		'name'           => 'Sidebar',
		'id'             => 'widgets',
		'before_widget'  => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'   => '</aside>',
		'before_title'   => '<h4 class="widgettitle">',
		'after_title'    => '</h4>',
	) );
}

// ---------------------------------------------------------------------------
// Add classes to WordPress nav menu for Foundation DropDown Menu support
// ---------------------------------------------------------------------------

if ( ! class_exists( 'Mikrogramma_Top_Bar_Walker' ) ) :
class Mikrogramma_Top_Bar_Walker extends Walker_Nav_Menu {
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"dropdown menu vertical\" data-toggle>\n";
	}
}
endif;
