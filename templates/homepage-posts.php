<?php

/**
* Homepage Posts Template
*
*
* @file           homepage-posts.php
* @package        Touko Aalto 2017
* @author         Janne Saarela
* @version        Release: 0.1.0
* @filesource     wp-content/themes/toukoaalto2017/templates/homepage-posts.php
* @since          available since Release 0.1.0
*/
?>

<?php
$query = new WP_Query(
    array(
    'post_type' =>
        array(
            'post',
            RSS_POST_NAME,
        ),
    'posts_per_page' => get_field( ACF_FRONT_POST_COUNT, ACF_OPTION_KEY ),
    )
);
?>
<?php if ( $query->have_posts() ) : ?>
    <h1 class="home__main-header"><?php _e( 'Latest posts', THEME_SLUG ); ?></h1>
	<?php while ( $query->have_posts() ) : $query->the_post(); ?>
        <article <?php post_class();?>>
            <a href="<?php echo get_permalink(); ?>" title="<?php the_title() ?>">
                <h1><?php the_title() ?></h1>
            </a>
            <div class='post-content'>
                <?php the_excerpt(); ?>
            </div>
      </article>
    <?php
    endwhile;
    wp_reset_postdata();
    ?>
    <?php
    if ( get_option( 'show_on_front' ) == 'page' && get_option( 'page_for_posts' ) ) : ?>
        <div class="all-blog-posts">
            <a href="<?php echo get_permalink( get_option( 'page_for_posts' ) );?>" title="<?php __( 'All Posts', THEME_SLUG ); ?>">
                <?php _e( 'All Posts &raquo;', THEME_SLUG ); ?>
            </a>
        </div>
	<?php endif; ?>
<?php
endif; // have_posts()
