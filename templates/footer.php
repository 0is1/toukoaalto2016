<?php use Roots\Sage\Assets; ?>
<footer class="site-footer">
  <div class="container site-footer__wrapper">
    <section class="site-footer__section">
    <?php if ( $text_left = get_field( ACF_FOOTER_INFO_LEFT, ACF_OPTION_KEY ) ) : ?>
        <h3><?php echo get_field( ACF_FOOTER_INFO_HEADER_LEFT, ACF_OPTION_KEY ); ?></h3>
        <p class="site-footer__text-left"><?php echo $text_left; ?></p>
    <?php endif; ?>
    </section>
    <section class="site-footer__section">
    <?php if ( $text_right = get_field( ACF_FOOTER_INFO_RIGHT, ACF_OPTION_KEY ) ) : ?>
        <h3><?php echo get_field( ACF_FOOTER_INFO_HEADER_RIGHT, ACF_OPTION_KEY ); ?></h3>
        <p class="site-footer__text-right"><?php echo $text_right; ?></p>
    <?php endif; ?>
    </section>
    <?php if ( get_field( ACF_DONATE_URL, ACF_OPTION_KEY ) ) : ?>
    <section class="site-footer__section donate">
        <a target="_blank" title="Lahjoitus" href="<?php echo get_field( ACF_DONATE_URL, ACF_OPTION_KEY );?>">
            <img src="<?php echo Assets\asset_path( 'images/donate-logo.svg' );?>" alt="Donate">
            <span><?php _e( 'Lahjoita kampanjaan', THEME_SLUG ); ?></span>
        </a>
    </section>
    <?php endif; ?>
    <section class="site-footer__section site-footer__logo">
    <?php if ( $image_id = get_field( ACF_FOOTER_LOGO, ACF_OPTION_KEY ) ) :
        $img = wp_get_attachment_image_src( $image_id, 'medium' );
        $img_src = $img[0];
        ?>
        <img src="<?php echo $img_src?>" />
    <?php endif; ?>
    </section>
    <section class="site-footer__section">
        <span class="created"><?php _e( 'Toteutus: ', THEME_SLUG );?>
            <a href="<?php echo esc_url( 'https://jannejuhani.me' );?>" title="jannejuhani.me">jannejuhani</a>
        </span>
    </section>
  </div>
</footer>
