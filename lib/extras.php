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

/**
* Add Bootstrap dropdown logic to main navigation.
*
* @param  string  $item_output The menu item output.
* @param  WP_Post $item        Menu item object.
* @param  int     $depth       Depth of the menu.
* @param  array   $args        wp_nav_menu() arguments.
* @return string  Menu item with possible description.
* @since  0.1.0
*/
function menu_description( $item_output, $item, $depth, $args ) {

	if ( in_array( 'menu-item-has-children', $item->classes ) ) {
		$item_output = str_replace( '<a', '<a data-toggle="dropdown" class="nav-link dropdown-toggle"', $item_output );
	} else if ( 0 == $depth ) {
		$item_output = str_replace( '<a', '<a class="nav-link"', $item_output );
	}
	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', __NAMESPACE__ . '\\menu_description', 10, 4 );

/**
* Add Bootstrap dropdown logic to navigation.
*/
function nav_class( $classes, $item ) {
	if ( $item->menu_item_parent ) {
		$classes[] = 'dropdown-item';
	} else if ( in_array( 'menu-item-has-children', $classes ) ) {
		$classes[] = 'nav-item dropdown';
	} else {
		$classes[] = 'nav-item';
	}
	return $classes;
}
add_filter( 'nav_menu_css_class' , __NAMESPACE__ . '\\nav_class', 10, 2 );
