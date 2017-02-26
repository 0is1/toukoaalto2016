<?php

/**
* Page Template
*
*
* @file           home.php
* @package        Touko Aalto 2017
* @author         Janne Saarela
* @version        Release: 0.1.0
* @filesource     wp-content/themes/toukoaalto2017/home.php
* @since          available since Release 0.1.0
*/
?>
<div class="home__wrapper">
    <div class="home__hero">
        <?php if ( $image_id = get_field( ACF_PAGE_HEADER_IMAGE, ACF_OPTION_KEY ) ) :
            $img_md = wp_get_attachment_image_src( $image_id, IMAGE_SIZE_WIDE_MD );
            $img_src_md = $img_md[0];
            $img_lg = wp_get_attachment_image_src( $image_id, IMAGE_SIZE_WIDE_LG );
            $img_src_lg = $img_lg[0];
            $img_xl = wp_get_attachment_image_src( $image_id, IMAGE_SIZE_WIDE_XL );
            $img_src_xl = $img_xl[0];
            ?>

            <img class="js-object-fit" src="<?php echo $img_src_md;?>" srcset="<?php echo $img_src_lg;?> 1x,
            <?php echo $img_src_xl;?> 2x,
            <?php echo $img_src_xl;?> 3x" alt="<?php echo bloginfo( 'title' ); ?>" />
        <?php endif; ?>
        <?php if ( $header_text = get_field( ACF_PAGE_HEADER_TEXT, ACF_OPTION_KEY ) ) : ?>
            <div class="container js-hero-text">
                <h3><?php echo $header_text; ?></h3>
            </div>
        <?php endif; ?>
    </div>
    <?php
    // check if the repeater field has rows of data
    if ( have_rows( ACF_PINNED_POSTS, ACF_OPTION_KEY ) ) : ?>
        <div class="home__pinned-posts">
        <?php
        // loop through the rows of data
        while ( have_rows( ACF_PINNED_POSTS, ACF_OPTION_KEY ) ) : the_row();
            $pinned_posts = get_sub_field( ACF_PINNED_POST );
            foreach ( $pinned_posts as $post ) : ?>
                <article class="home__pinned-post">
                    <a title="<?php echo $post->post_title; ?>" href="<?php echo get_the_permalink( $post->ID ); ?>">
                        <?php the_post_thumbnail( $post->ID ); ?>
                    </a>
                    <span class="text">
                        <h3>
                            <a title="<?php echo $post->post_title; ?>" href="<?php echo get_the_permalink( $post->ID ); ?>"><?php echo $post->post_title; ?></a>
                        </h3>
                    </span>
                </article>
            <?php
            endforeach;
            ?>
        <?php endwhile; ?>
        </div>
    <?php
    endif; // have_rows( ACF_PINNED_POSTS, ACF_OPTION_KEY )
    ?>

    <?php if ( get_field( ACF_FACEBOOK_ID, ACF_OPTION_KEY ) && get_field( ACF_ENABLE_FACEBOOK_PAGE_BOX, ACF_OPTION_KEY ) ) : ?>
        <?php $facebook_id = get_field( ACF_FACEBOOK_ID, ACF_OPTION_KEY ); ?>
        <div class="home__facebook-page">
            <h3 class="home__facebook-page-title">Facebook</h3>
            <div class="fb-page" data-width="500" data-href="https://www.facebook.com/<?php echo $facebook_id;?>/" data-tabs="" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
                <blockquote cite="https://www.facebook.com/touko.aalto/" class="fb-xfbml-parse-ignore">
                    <a href="https://www.facebook.com/<?php echo $facebook_id;?>/"><?php echo get_bloginfo( 'name' ); ?></a>
                </blockquote>
            </div>
        </div>
    <?php endif; ?>
    <?php if ( get_field( ACF_ENABLE_TWITTER_BOX, ACF_OPTION_KEY ) ) : ?>
        <div class="home__twitter-account-box"><?php echo do_shortcode( '[twitter_account_box]' ); ?></div>
    <?php endif; ?>
    <?php if ( get_field( ACF_ENABLE_WP_POSTS, ACF_OPTION_KEY ) ) : ?>
        <div class="home__homepage-posts"><?php get_template_part( 'templates/homepage-posts' ); ?></div>
    <?php endif; ?>
    <?php if ( get_field( ACF_ENABLE_ACTIVITY_FEED, ACF_OPTION_KEY ) ) : ?>
        <div class="home__activityfeed"><?php get_template_part( 'templates/homepage-activityfeed' ); ?></div>
    <?php endif;

    ?>
</div>
