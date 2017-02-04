<?php get_template_part( 'templates/page', 'header' ); ?>

<?php if ( ! have_posts() ) : ?>
  <div class="alert alert-warning">
    <?php _e( 'Sorry, no results were found.', THEME_SLUG ); ?>
  </div>
	<?php get_search_form(); ?>
<?php endif; ?>

<?php while ( have_posts() ) : the_post(); ?>
	<?php get_template_part( 'templates/content', 'search' ); ?>
<?php endwhile; ?>
<?php $args = array(
    'prev_text' => __( '&laquo; Vanhemmat artikkelit', THEME_SLUG ),
    'next_text' => __( 'Uudemmat artikkelit &raquo;', THEME_SLUG ),
); ?>
<?php the_posts_navigation( $args ); ?>
