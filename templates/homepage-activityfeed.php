<?php

use Roots\Sage\AF;

/**
* Homepage Posts Template
*
* @file           homepage-activityfeed.php
* @package        Touko Aalto 2017
* @author         Janne Saarela
* @version        Release: 0.1.0
* @filesource     wp-content/themes/toukoaalto2017/templates/homepage-activityfeed.php
* @since          available since Release 0.1.0
*/
?>
<section class="activity-feed">
    <?php
        AF\display_activity_feed();
    ?>
</section>
