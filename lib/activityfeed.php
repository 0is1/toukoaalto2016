<?php

namespace Roots\Sage\AF;

/**
 * Get bundle data
 *
 * @since 0.1.0
 *
 * @param string $bundle_id Bundle short ID.
 * @param string $limit
 *
 * @return string $bundle_data Bundle data.
 */
function get_bundle_data( $bundle_id, $limit = 10 ) {

	// Construct the HTTP message.
	$url = add_query_arg( 'limit', $limit, WPAF_ACTIVITY_FEED_FEEDURL . $bundle_id );
	$args = '';

	// Send the message and record response.
	$response = wp_remote_get( $url, $args );

	if ( ! is_wp_error( $response ) ) {

		$bundle_data = json_decode( $response['body'] );

		if ( ! property_exists( $bundle_data, 'error' ) ) {

			return $bundle_data;

		} else {

			// Error in bundle.
			error_log( 'Error in bundle: ' . $bundle_data->error->message->name. ' Error status: ' . $bundle_data->error->message->status );
			return false;

		}
	} else {

		error_log( 'Error connecting to Activity Feed API.' );
		return false;

	}
}

/**
* Front-end display
*
* @since 1.0.0
*/

function display_activity_feed() {
    // If feed id is not set, do not try to display it
	$feed_id = get_field( ACF_ACTIVITY_FEED_ID, ACF_OPTION_KEY );
    $feed_limit = get_field( ACF_ACTIVITY_FEED_LIMIT, ACF_OPTION_KEY );
    if ( ! $feed_id ) {
    	return;
    }
    $feed_data = null;
    $feed_json = get_transient( 'activity_feed_' . $feed_id );

    if ( $feed_id && ! $feed_json ) {
    	$feed_json = get_bundle_data( $feed_id, $feed_limit );
    	if ( $feed_json && $feed_json->result ) {
    		set_transient( 'activity_feed_' . $feed_id, $feed_json, 60 );
    	}
    }

    if ( $feed_json ) {

    	$the_feed = parse_json_data( $feed_json, $feed_id );
    	?>
		<h1 class="activity-feed__title"><?php _e( 'Social media feed', THEME_SLUG ); ?></h1>
		<ul class="activity-feed__data">
		<?php
    	if ( ! empty( $the_feed ) ) {
    		if ( is_wp_error( $the_feed ) ) {
    			echo esc_attr( get_error_message( $the_feed ) );
    		} else {
    			echo $the_feed;
    		}
    	}
		?>
    	</ul>
	<?php
    }
}

/**
 * Get parsed feed data from activityfeed JSON
 *
 * @param object $json decoded JSON data from activityfeed.
 * @param string $feed_id the id of activity feed.
 *
 * @return string html markup
 */
function parse_json_data( $json, $feed_id ) {
	if ( ! $json ) {
		return new WP_Error( 'activity-feed-error', __( 'JSON data is null', THEME_SLUG ) );
	} elseif ( isset( $json->error ) ) {
		return new WP_Error( 'activity-feed-error', $json->error->msg );
	} elseif ( ! isset( $feed_id ) ) {
		return new WP_Error( 'activity-feed-error', __( 'feed_id is empty', THEME_SLUG ) );
	}
	$html = '';
	$i = 0;
	foreach ( $json->result as $key => $value ) {
		switch ( $value->name ) {
			case 'Facebook':
				$item_service_url = 'https://www.facebook.com/';
				$item_service_hashtag_url = 'https://www.facebook.com/hashtag/';
			break;
			case 'Twitter':
				$item_service_url = 'https://twitter.com/';
				$item_service_hashtag_url = 'https://twitter.com/hashtag/';
			break;
			case 'Instagram':
				$item_service_url = 'https://www.instagram.com/';
				$item_service_hashtag_url = 'https://www.instagram.com/explore/tags/';
			break;
			default:
				$item_service_url = null;
				$item_service_hashtag_url = null;
			break;
		}

		$html .= '<li class="activity-feed__item">';
		if ( isset( $value->{'sourceUserImage'} ) ) {
			$html .= '<aside class="activity-feed__item-img">';
			$html .= '<img height="48" width="48" alt="'. $value->{'publisherName'} .'" title="'. $value->{'publisherName'} .'" data-echo="'. $value->{'sourceUserImage'} .'"/>';
			$html .= '</aside>';
		} else {
			$html .= '<aside class="activity_feed__item-icon">';
			$html .= '<i class="kaf-icon '. get_item_icon( $value->type, $value->name ) .'"></i>';
			$html .= '</aside>';
		}

		$html .= '<div class="activity-feed__item-inside">';
		$html .= '<span class="activity-feed__details">';

		switch ( $value->name ) {
			case 'Twitter':
				$twitter_name = isset( $value->{'publisherFullName'} ) ? $value->{'publisherFullName'} : $value->{'publisherName'};
				$html .= '<a title="'. $twitter_name .'" href="'. $item_service_url . $value->{'publisherName'} .'"><strong>'. $twitter_name .'</strong></a>';
			break;
			case 'Instagram':
				$instagram_name = isset( $value->{'publisherFullName'} ) ? $value->{'publisherFullName'} : $value->{'publisherName'};
				$html .= '<a title="'. $instagram_name .'" href="'. $item_service_url . $value->{'publisherName'} .'"><strong>'. $instagram_name .'</strong></a>';
			break;
			case 'Facebook':
				$html .= '<a title="'. $value->{'publisherName'} .'" href="'. $item_service_url . $value->source->source .'"><strong>'. $value->{'publisherName'} .'</strong></a>';
			break;
			default:
				$html .= '<strong>'. $value->name .'</strong>';
			break;
		}

		if ( isset( $value->{'actionType'} ) ) {
			$html .= '<a href="'. $value->{'originalUrl'}.'">';
			$html .= '<span class="activity-feed__action">';
			$html .= sprintf( _x( 'shared on %s', '%s = service name', THEME_SLUG ), $value->name );
			$html .= '</span>';
			$html .= '</a>';
		}
		$html .= '</span>';
		$html .= '<span class="activity-feed__content">';
		$nl2br_text = nl2br( $value->content );
		if ( 'Facebook' === $value->name || 'Twitter' === $value->name || 'Instagram' === $value->name ) {
			$html .= '<p>' . parse_urls_and_hashtags( $nl2br_text,  $item_service_url, $item_service_hashtag_url );
			if ( isset( $value->{'imageUrl'} ) ) {
				$img_url = ( 'Twitter' == $value->name ) ? $value->{'imageUrl'} . ':small' : $value->{'imageUrl'};
				$html .= '<a href="'. $value->{'originalUrl'}.'">';
				$html .= '<img alt="'. $value->{'publisherName'} .'" data-echo="'. $img_url .'" src="https://placehold.it/300x300"/>';
				$html .= '</a>';
			}
			$html .= '</p>';
		} else {
			$html .= '<a href="'. $value->{'originalUrl'}.'">';
			$html .= '<p>' . $nl2br_text . '</p>';
			if ( isset( $value->{'imageUrl'} ) ) {
				$html .= '<img alt="'. $value->{'publisherName'} .'" data-echo="'. $value->{'imageUrl'} .'" src="https://placehold.it/300x300"/>';
			}
			$html .= '</a>';
		}
		$html .= '<a href="'. $value->{'originalUrl'}.'" class="activity-feed__time">';
		$html .= '<i class="kaf-icon kaf-icon-clock"></i>';
		$html .= '<time datetime="'. date_i18n( 'd.m.Y H:m', strtotime( $value->timestamp ) ) .'">';
		// Add gmt_offset to the $value->timestamp because original timestamp is UTC 0.
		$time_zone_hours = (int) get_option( 'gmt_offset' );
		$html .= sprintf( _x( '%s ago', '%s = human-readable time difference', THEME_SLUG ), human_time_diff( strtotime( $value->timestamp . $time_zone_hours . ' hours' ), current_time( 'timestamp' ) ) );
		$html .= '</time>';
		$html .= '</a>';
		$html .= '</span>';
		$html .= '</div>'; // Close class="item".
		$html .= '</li>'; // Close class="item-wrap".
		$i++;
		if ( 5 <= $i ) {
			break;
		}
	}

	return $html;
}

/**
 * Get icon name
 *
 * @param string $type social/news/blog
 * @param string $name Facebook/Twitter/"Mixed"
 *
 * @return string (icon css-class)
 */
function get_item_icon( $type, $name ) {
	if ( 'social' === $type && 'Facebook' === $name ) {
		return 'kaf-icon-facebook-squared';
	} elseif ( 'social' === $type && 'Twitter' === $name ) {
		return 'kaf-icon-twitter';
	} elseif ( 'news' === $type ) {
		return 'kaf-icon-doc';
	} else {
		return 'kaf-icon-doc-text';
	}
}

/**
 * Parse text urls, mentions and hashtags and create links
 *
 * @param string $text text.
 * @param string $service_url (example: https://twitter.com/ || https://www.facebook.com/).
 * @param string $hashtag_url (https://twitter.com/hashtag/ || https://www.facebook.com/hashtag/).
 *
 * @return string html
 */
function parse_urls_and_hashtags( $text, $service_url, $hashtag_url ) {
	// http://php.net/manual/en/function.preg-replace-callback.php#109938
	$text = preg_replace_callback( '/([\w]+\:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/',
    	function ( $matches ) {
			return '<a href="' . $matches[0] . '">' . $matches[0] . '</a>';
		}, $text );
	if ( isset( $service_url ) ) {
		$text = preg_replace_callback( '/@([A-Öa-ö0-9\/]*)/',
			function ( $matches ) use ( $service_url ) {
				return '<a href="' . $service_url . $matches[1] .'">@' . $matches[1] . '</a> ';
			},
        $text);
	}
	if ( isset( $hashtag_url ) ) {
		$text = preg_replace_callback( '/#([A-Öa-ö0-9\/\.]*)/',
			function ( $matches ) use ( $hashtag_url ) {
				return '<a href="' . $hashtag_url . $matches[1] . '" >#' . $matches[1] . '</a>';
			},
        $text);
	}

	return $text;
}

/**
* Get error message from WP_Error object
*
* @param Obj $wp_error WP Error object.
*
* @return string html
*/
function get_error_message( $wp_error ) {
	return '<h3 class="error">' . $wp_error->get_error_message() . '</h3>';
}
