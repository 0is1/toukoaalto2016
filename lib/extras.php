<?php

namespace Roots\Sage\Extras;

use Roots\Sage\Setup;

/**
 * Add <body> classes
 */
function body_class( $classes ) {
	// Add page slug if it doesn't exist
	if ( is_single() || is_page() && ! is_front_page() ) {
		if ( ! in_array( basename( get_permalink() ), $classes ) ) {
			$classes[] = basename( get_permalink() );
		}
	}

	// Add class if sidebar is active
	if ( Setup\display_sidebar() ) {
		$classes[] = 'sidebar-primary';
	}

	return $classes;
}
add_filter( 'body_class', __NAMESPACE__ . '\\body_class' );

/**
 * Clean up the_excerpt()
 */
function excerpt_more() {
	return ' &hellip; <a href="' . get_permalink() . '">' . __( 'Continued', THEME_SLUG ) . '</a>';
}
add_filter( 'excerpt_more', __NAMESPACE__ . '\\excerpt_more' );

if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_page(array(
		'page_title' 	=> __( 'Theme General Settings', THEME_SLUG ),
		'menu_title'	=> __( 'Theme Settings', THEME_SLUG ),
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false,
	));
}

// Show rss_posts in main archive loop
function add_rss_post_type_to_archive_query( $query ) {
	if ( is_home() && $query->is_main_query() ) {
		$query->set( 'post_type', array( 'post', RSS_POST_NAME ) );
	}

	return $query;
}

add_action( 'pre_get_posts', __NAMESPACE__ . '\\add_rss_post_type_to_archive_query' );
