<?php
namespace Roots\Sage\RssPostsHandler;

use Roots\Sage\SaveImageFromRemote;

/**
 * Save RSS posts to WP
 *
 *
 * @file           rss-posts-handler.php
 * @package        Touko The Politician
 * @author         Janne Saarela
 * @version        0.1.0
 * @filesource     wp-content/themes/toukoaalto2017/lib/rss-posts-handler.php
 * @since          available since 0.1.0
 */

 /**
  * Function: check_if_rss_post_meta_key_exists
  *
  * @param string $meta_key to check from rss_post
  * @return mixed $rss_post object, empty array or false
  */

function check_if_rss_post_meta_key_exists( $meta_key = false ) {

    if ( ! $meta_key ) {
		return new WP_Error( 'meta key missing error', __( 'Meta key is not set', THEME_SLUG ) );
    }

    $options = array(
	'posts_per_page'  => 1,
	'orderby'         => 'post_date',
	'order'           => 'DESC',
	'post_type'       => RSS_POST_NAME,
	'post_status'     => 'publish',
	'meta_key'        => $meta_key,
	);

	$rss_post = get_posts( $options );
	return $rss_post;

}

  /**
   * Function: get_rss_post_category_ids_from_category_names
   *
   * @param array $category_names to get the ids
   * @return array $category_ids
   */

function get_rss_post_category_ids_from_category_names( $category_names = array() ) {

    $category_ids = array();

    foreach ( $category_names as $name ) {
		$name = trim( $name );
		$category_id = term_exists( $name, RSS_CATEGORY_SLUG );
		if ( ! $category_id ) {
			$new_category = wp_insert_term( $name, RSS_CATEGORY_SLUG );
			if ( ! is_wp_error( $new_category ) ) {
				$category_id = $new_category['term_id'];
			}
		} else { $category_id = $category_id['term_id'];
		}
		if ( $category_id && absint( $category_id ) ) { array_push( $category_ids, absint( $category_id ) );
		}
    }
    return $category_ids;
}

add_filter( 'loop_and_save_rss_feed_posts', __NAMESPACE__ . '\\loop_and_save_rss_feed_posts', 1, 0 );

function loop_and_save_rss_feed_posts() {

    if ( get_field( ACF_RSS_ENABLE, ACF_OPTION_KEY ) && sizeof( $urls = get_field( ACF_RSS_URLS, ACF_OPTION_KEY ) ) > 0 ) :
        // check if the repeater field has rows of data
        if ( have_rows( ACF_RSS_URLS, ACF_OPTION_KEY ) ) :

         	// loop through the rows of data
            while ( have_rows( ACF_RSS_URLS, ACF_OPTION_KEY ) ) : the_row();

                $feed_url = get_sub_field( ACF_RSS_URL );

    			// Get RSS Feed(s)
    			include_once( ABSPATH . WPINC . '/feed.php' );

    			// Get a SimplePie feed object from the specified feed source.
    			$rss = fetch_feed( $feed_url );
    			$maxitems = 0;

    			if ( ! is_wp_error( $rss ) ) : // Checks that the object is created correctly
    				// Figure out how many total items there are, but limit it to 20.
    				$maxitems = $rss->get_item_quantity( 20 );
    				// Build an array of all the items, starting with element 0 (first element).
    				$rss_items = $rss->get_items( 0, $maxitems );
    			endif;

    			if ( $maxitems > 0 ) :
    				// Try to get 'touko' user to add it as a post_author, fallback to user_id 1
    				$user = get_user_by( 'login', 'touko' );
    				$user_id = $user ? $user->ID : 1;
    				foreach ( $rss_items as $item ) :
    					$category_name_array = array();
    					if ( sizeof( $categories = $item->get_categories() ) > 0 ) {
    						foreach ( $categories as $category ) {
    							array_push( $category_name_array, $category->term );
    						}
    					}
    					$meta_guid = $item->get_item_tags( '','guid' );
    					$meta_guid_key = ( is_array( $meta_guid[0] ) && isset( $meta_guid[0]['data'] ) ) ?  esc_url( $meta_guid[0]['data'] ) : esc_url( $item->get_permalink() );
    					$rss_post_exists = check_if_rss_post_meta_key_exists( $meta_guid_key );
    					if ( ! $rss_post_exists ) {
    						$thumbnail_hash = false;
    						$thumbnail_url = false;
    						$rss_post_attachment_exists = false;
    						if ( $enclosure = $item->get_enclosure() ) {
    							$thumbnails = (array) $enclosure->get_thumbnails();
    							if ( sizeof( $thumbnails ) > 0 ) {
    								$thumbnail_hash = md5( $thumbnails[0] );
    								$thumbnail_url = $thumbnails[0];
    								$rss_post_attachment_exists = check_if_rss_post_meta_key_exists( $thumbnail_hash );
    								if ( $rss_post_attachment_exists && sizeof( $rss_post_attachment_exists ) > 0 && isset( $rss_post_attachment_exists[0]->$thumbnail_hash ) ) {
    									$attachment_id = $rss_post_attachment_exists[0]->$thumbnail_hash;
    								} else {
                                        $attachment_id = false;
    								}
    							}
    						}
    						// Remove all inline styles
    						$new_content = preg_replace( '/style=\\"[^\\"]*\\"/', '', $item->get_content() );
    						// Add class "new-paragraph" to all p-tags
    						$new_content = str_replace( '<p>', "<p class='new-paragraph'>", $new_content );
    						$candidate_post = array(
        						'post_title' => esc_html( $item->get_title() ),
        						'post_content' => $new_content,
        						'post_status' => 'publish',
        						'post_type' => RSS_POST_NAME,
        						'post_author' => $user_id,
    						);
    						$post_id = wp_insert_post( $candidate_post );
    						$category_ids = get_rss_post_category_ids_from_category_names( $category_name_array );
    						if ( sizeof( $category_ids ) > 0 ) {
    						    wp_set_object_terms( $post_id, $category_ids, RSS_CATEGORY_SLUG, true );
    						}

                            add_post_meta( $post_id, $meta_guid_key, true, true );

                            if ( ! $attachment_id ) {
                                $attachment_id = apply_filters( 'save_image_from_remote_to_post', $thumbnail_url, $post_id );
    						}

    						if ( $thumbnail_hash && ! is_wp_error( $attachment_id ) && $attachment_id ) {
								add_post_meta( $post_id, $thumbnail_hash, $attachment_id , true );
    							update_post_meta( $post_id, '_thumbnail_id', $attachment_id );
    						}
    					}

                    endforeach;

    			endif;

            endwhile;

        endif;

    endif; // if $options['enable_rss_feed_fetch']
}

// On an early action hook, check if the hook is scheduled - if not, schedule it.

function setup_rss_schedule() {
	if ( ! wp_next_scheduled( 'schedule_daily_rss_data' ) ) {
		wp_schedule_event( time(), 'daily', 'schedule_daily_rss_data' );
	}
}

add_action( 'wp', __NAMESPACE__ . '\\setup_rss_schedule' );

// Run rss data once a day or when called via API
add_action( 'schedule_daily_rss_data', function(){
	apply_filters( __NAMESPACE__ . '\\loop_and_save_rss_feed_posts', null );
});


/**
* Register route to schedule rss posts
* Call from cron with
* curl -d nonce=schedule-rss-posts-sync-request-from-cron http://toukoaalto.fi/wp-json/schedule-rss-posts/v1/sync
*/
add_action( 'rest_api_init', function () {
    register_rest_route( 'schedule-rss-posts/v1', '/sync', array(
        'methods'  => 'POST',
        'callback' => __NAMESPACE__ . '\\maybe_schedule_rss_posts',
    ) );
});

/**
* Maybe schedule rss posts
* @param WP_REST_Request $request
*
* @return json (true|false)
*/
function maybe_schedule_rss_posts( \WP_REST_Request $request ) {
	$nonce = $request['nonce'];

    if ( 'schedule-rss-posts-sync-request-from-cron' != $nonce ) {
        error_log( print_r( 'Invalid nonce!', true ) );
        return false;
    }
    loop_and_save_rss_feed_posts();
    return true;
}
