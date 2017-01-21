<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) { exit;
}

/**
 * Custom post type: RSS posts
 *
 *
 * @file           rss-post-type.php
 * @package        Touko Aalto 2017
 * @author         Janne Saarela
 * @version        0.1.0
 * @filesource     wp-content/themes/toukoaalto2017/lib/rss-post-type.php
 * @since          available since 0.1.0
 */

// let's create the function for the custom type
function rss_post() {
	// creating (registering) the custom type
	register_post_type( RSS_POST_NAME, /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
	 	// let's now add all the options for this post type
		array(
        'labels' => array(
			'name' => __( 'RSS Posts', THEME_SLUG ), /* This is the Title of the Group */
			'singular_name' => __( 'RSS Posts', THEME_SLUG ), /* This is the individual type */
			'all_items' => __( 'All RSS Posts', THEME_SLUG ), /* the all items menu item */
			'add_new' => __( 'Add New RSS Post', THEME_SLUG ), /* The add new menu item */
			'add_new_item' => __( 'Add New RSS Post', THEME_SLUG ), /* Add New Display Title */
			'edit' => __( 'Edit RSS Post', THEME_SLUG ), /* Edit Dialog */
			'edit_item' => __( 'Edit RSS Post', THEME_SLUG ), /* Edit Display Title */
			'new_item' => __( 'New RSS Post', THEME_SLUG ), /* New Display Title */
			'view_item' => __( 'View RSS Post', THEME_SLUG ), /* View Display Title */
			'search_items' => __( 'Search RSS Posts', THEME_SLUG ), /* Search Custom Type Title */
			'not_found' => __( 'Nothing found in the Database.', THEME_SLUG ), /* This displays if there are no entries yet */
			'not_found_in_trash' => __( 'Nothing found in Trash', THEME_SLUG ), /* This displays if there is nothing in the trash */
			'parent_item_colon' => '',
			), /* end of arrays */
			'description' => __( 'RSS Post custom post type', THEME_SLUG ), /* Custom Type Description */
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 8, /* this is what order you want it to appear in on the left hand side menu */
			'menu_icon' => 'dashicons-rss', /* the icon for the custom post type menu */
			'rewrite'	=> array( 'slug' => 'rss_artikkelit', 'with_front' => false ), /* you can specify its url slug */
			'has_archive' => RSS_POST_NAME, /* you can rename the slug here */
			'capability_type' => 'post',
			'hierarchical' => false,
			/* the next one is important, it tells what's enabled in the post editor */
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'sticky', 'page-attributes' ),
	 	) /* end of options */
	); /* end of register post type */

	/* this adds your post categories to your custom post type */
	register_taxonomy_for_object_type( 'category', 'rss_artikkelit' );
	/* this adds your post tags to your custom post type */
	register_taxonomy_for_object_type( 'post_tag', 'rss_artikkelit' );

	/*
	for more information on taxonomies, go here:
	http://codex.wordpress.org/Function_Reference/register_taxonomy
	*/

	// now let's add custom categories (these act like categories)
	register_taxonomy( RSS_CATEGORY_SLUG,
        array( RSS_POST_NAME ), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
        array(
        'hierarchical' => true,     /* if this is true, it acts like categories */
  		'labels' => array(
  			'name' => __( 'RSS Posts Categories', THEME_SLUG ), /* name of the custom taxonomy */
  			'singular_name' => __( 'RSS Posts Category', THEME_SLUG ), /* single taxonomy name */
  			'search_items' => __( 'Search RSS Posts Categories', THEME_SLUG ), /* search title for taxomony */
  			'all_items' => __( 'All RSS Posts Categories', THEME_SLUG ), /* all title for taxonomies */
  			'parent_item' => __( 'Parent RSS Posts Category', THEME_SLUG ), /* parent title for taxonomy */
  			'parent_item_colon' => __( 'Parent RSS Posts Category:', THEME_SLUG ), /* parent taxonomy title */
  			'edit_item' => __( 'Edit RSS Posts Category', THEME_SLUG ), /* edit custom taxonomy title */
  			'update_item' => __( 'Update RSS Posts Category', THEME_SLUG ), /* update title for taxonomy */
  			'add_new_item' => __( 'Add New RSS Posts Category', THEME_SLUG ), /* add new title for taxonomy */
  			'new_item_name' => __( 'New RSS Posts Category Name', THEME_SLUG ) /* name title for taxonomy */,
  		),
  		'show_admin_column' => true,
  		'show_ui' => true,
  		'query_var' => true,
  		'rewrite' => array( 'slug' => 'rss_post_category' ),
        )
	);
	register_taxonomy( 'rss_post_tag',
        array( RSS_POST_NAME ), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
        array(
        'hierarchical' => false,    /* if this is false, it acts like tags */
  		'labels' => array(
  			'name' => __( 'RSS Posts Tags', THEME_SLUG ), /* name of the custom taxonomy */
  			'singular_name' => __( 'RSS Posts Tag', THEME_SLUG ), /* single taxonomy name */
  			'search_items' => __( 'Search RSS Posts Tags', THEME_SLUG ), /* search title for taxomony */
  			'all_items' => __( 'All RSS Posts Tags', THEME_SLUG ), /* all title for taxonomies */
  			'parent_item' => __( 'Parent RSS Posts Tag', THEME_SLUG ), /* parent title for taxonomy */
  			'parent_item_colon' => __( 'Parent RSS Posts Tag:', THEME_SLUG ), /* parent taxonomy title */
  			'edit_item' => __( 'Edit RSS Posts Tag', THEME_SLUG ), /* edit custom taxonomy title */
  			'update_item' => __( 'Update RSS Posts Tag', THEME_SLUG ), /* update title for taxonomy */
  			'add_new_item' => __( 'Add New RSS Posts Tag', THEME_SLUG ), /* add new title for taxonomy */
  			'new_item_name' => __( 'New RSS Posts Tag Name', THEME_SLUG ) /* name title for taxonomy */,
  		),
  		'show_admin_column' => true,
  		'show_ui' => true,
  		'query_var' => true,
        )
	);
}

// adding the function to the Wordpress init
add_action( 'init', RSS_POST_NAME );
