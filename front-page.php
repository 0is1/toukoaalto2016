<?php

/**
* Page Template
*
*
* @file           front-page.php
* @package        Touko The Politician
* @author         Janne Saarela
* @version        Release: 0.9.2
* @filesource     wp-content/themes/touko-the-politician/front-page.php
* @since          available since Release 0.9.2
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
