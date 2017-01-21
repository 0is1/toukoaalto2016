<?php

/**
* Page Template
*
*
* @file           front-page.php
* @package        Touko Aalto 2017
* @author         Janne Saarela
* @version        Release: 0.1.0
* @filesource     wp-content/themes/toukoaalto2017/front-page.php
* @since          available since Release 0.1.2
*/
?>

<?php
if ( get_field( ACF_ENABLE_TWITTER_BOX, ACF_OPTION_KEY ) ) {
    echo do_shortcode( '[twitter_account_box]' );
}
if ( get_field( ACF_ENABLE_WP_POSTS, ACF_OPTION_KEY ) ) {
    get_template_part( 'templates/homepage-posts' );
}
if ( get_field( ACF_ENABLE_ACTIVITY_FEED, ACF_OPTION_KEY ) ) {
    get_template_part( 'templates/homepage-activityfeed' );
}

?>
