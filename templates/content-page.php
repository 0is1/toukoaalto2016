<div class="entry-content">
    <?php if ( has_post_thumbnail() ) : ?>
        <?php the_post_thumbnail( IMAGE_SIZE_WIDE_LG ); ?>
    <?php endif; ?>
    <?php the_content(); ?>
</div>
<?php wp_link_pages( [ 'before' => '<nav class="page-nav"><p>' . __( 'Pages:', THEME_SLUG ), 'after' => '</p></nav>' ] ); ?>
