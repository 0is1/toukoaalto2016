<?php
/**
* Sage includes
*
* The $sage_includes array determines the code library included in your theme.
* Add or remove files to the array as needed. Supports child theme overrides.
*
* Please note that missing files will produce a fatal error.
*
* @link https://github.com/roots/sage/pull/1042
*/

if ( ! function_exists( 'get_field' ) ) {
    wp_die( 'Install ACF-Pro to continue...' );
}

define( 'THEME_SLUG', 'sage' );
define( 'RSS_POST_NAME', 'rss_post' );
define( 'RSS_CATEGORY_SLUG' , 'rss_post_category' );
define( 'TEMP_IMAGE_FOLDER', 'temp_images' );
define( 'WPAF_ACTIVITY_FEED_FEEDURL', 'https://kehittamo-activityfeed.herokuapp.com/feed/' );

// Image sizes
define( 'IMAGE_SIZE_WIDE_XS', 'thumbnail-wide-xs' );
define( 'IMAGE_SIZE_WIDE_SM', 'thumbnail-wide-sm' );
define( 'IMAGE_SIZE_WIDE_MD', 'thumbnail-wide-md' );
define( 'IMAGE_SIZE_WIDE_LG', 'thumbnail-wide-lg' );
define( 'IMAGE_SIZE_WIDE_XL', 'thumbnail-wide-xl' );


// ACF
define( 'ACF_OPTION_KEY', 'option' );
define( 'ACF_ENABLE_WP_POSTS', 'enable_wp_posts_newsfeed' );
define( 'ACF_FRONT_POST_COUNT', 'wp_blog_visible_posts_count' );
define( 'ACF_ENABLE_TWITTER_BOX', 'enable_twitter_follow_box' );
define( 'ACF_ENABLE_ACTIVITY_FEED', 'enable_activity_feed' );
define( 'ACF_ACTIVITY_FEED_ID', 'activity_feed_id' );
define( 'ACF_ACTIVITY_FEED_LIMIT', 'activity_feed_limit' );
define( 'ACF_PAGE_HEADER_IMAGE', 'page_header_image' );
define( 'ACF_PAGE_HEADER_TEXT', 'page_header_text' );

$sage_includes = [
    'lib/assets.php',    // Scripts and stylesheets
    'lib/activityfeed.php',    // Activityfeed
    'lib/extras.php',    // Custom functions
    'lib/dependencies.php',    // dependencies
    'lib/setup.php',     // Theme setup
    'lib/titles.php',    // Page titles
    'lib/wrapper.php',   // Theme wrapper class
    'lib/customizer.php', // Theme customizer
    'lib/rss-post-type.php', // RSS custom post type
];

foreach ( $sage_includes as $file ) {
	if ( ! $filepath = locate_template( $file ) ) {
		trigger_error( sprintf( __( 'Error locating %s for inclusion', THEME_SLUG ), $file ), E_USER_ERROR );
	}

	require_once $filepath;
}

unset( $file, $filepath );
