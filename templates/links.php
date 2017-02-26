<?php use Roots\Sage\Assets; ?>
<?php if ( get_field( ACF_ENABLE_LINKS, ACF_OPTION_KEY ) ) : ?>
    <ul class="main-header__links-nav">
        <?php if ( get_field( ACF_FACEBOOK_ID, ACF_OPTION_KEY ) ) : ?>
            <li class="main-header__links-item">
                <a target="_blank" title="Facebook" class="main-header__links-fb" href="https://facebook.com/<?php echo get_field( ACF_FACEBOOK_ID, ACF_OPTION_KEY );?>">
                    <img src="<?php echo Assets\asset_path( 'images/facebook-logo.svg' );?>" alt="Facebook">
                </a>
            </li>
        <?php endif; ?>
        <?php if ( get_field( ACF_TWITTER_USERNAME, ACF_OPTION_KEY ) ) : ?>
            <li class="main-header__links-item">
                <a target="_blank" title="Twitter" class="main-header__links-twitter" href="https://twitter.com/<?php echo get_field( ACF_TWITTER_USERNAME, ACF_OPTION_KEY );?>">
                    <img src="<?php echo Assets\asset_path( 'images/twitter-logo.svg' );?>" alt="Twitter">
                </a>
            </li>
        <?php endif; ?>
        <?php if ( get_field( ACF_INSTAGRAM_USERNAME, ACF_OPTION_KEY ) ) : ?>
            <li class="main-header__links-item">
                <a target="_blank" title="Instagram" class="main-header__links-instagram" href="https://instagram.com/<?php echo get_field( ACF_INSTAGRAM_USERNAME, ACF_OPTION_KEY );?>">
                    <img src="<?php echo Assets\asset_path( 'images/instagram-logo.svg' );?>" alt="Instagram">
                </a>
            </li>
        <?php endif; ?>
        <?php if ( get_field( ACF_BLOG_URL, ACF_OPTION_KEY ) ) : ?>
            <li class="main-header__links-item">
                <a target="_blank" title="Blogi" class="main-header__links-blog" href="<?php echo get_field( ACF_BLOG_URL, ACF_OPTION_KEY );?>">
                    <img src="<?php echo Assets\asset_path( 'images/blog-logo.svg' );?>" alt="Blog">
                </a>
            </li>
        <?php endif; ?>
        <?php if ( get_field( ACF_DONATE_URL, ACF_OPTION_KEY ) ) : ?>
            <li class="main-header__links-item">
                <a target="_blank" title="<?php _e( 'Lahjoita kampanjaan', THEME_SLUG ); ?>" class="main-header__links-donate" href="<?php echo get_field( ACF_DONATE_URL, ACF_OPTION_KEY );?>">
                    <img src="<?php echo Assets\asset_path( 'images/donate-logo.svg' );?>" alt="Donate">
                </a>
            </li>
        <?php endif; ?>
    </ul>
<?php endif; ?>
