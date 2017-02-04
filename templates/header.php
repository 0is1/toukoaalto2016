<?php
use Roots\Sage\Setup;

?>
<header class="main-header">
    <div class="main-header__container js-header-container">
        <div class="container-fluid">
            <a class="brand" title="<?php echo get_site_url(); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
            <nav class="nav-primary">
                <div class="nav-primary__desktop">
                    <?php
                    if ( has_nav_menu( 'primary_navigation' ) ) :
                        wp_nav_menu( [
                            'theme_location' => 'primary_navigation',
                            'menu_class' => 'nav nav-pills js-main-menu',
                            'container_class' => 'nav-primary__desktop-menu',
                            'walker' => new Setup\Bootstrap_Walker(),
                        ] );
                    endif;
                    ?>
                    <span class="nav-primary__search">
                        <?php get_search_form(); ?>
                    </span>
                </div>
                <div class="nav-primary__mobile js-menu-container">
                    <a class="nav-primary__mobile-toggle" href="#" data-action="toggle-main-menu">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </a>
                    <?php
                    if ( has_nav_menu( 'primary_navigation' ) ) :
                        wp_nav_menu([
                            'theme_location' => 'primary_navigation',
                            'menu_class' => 'nav nav-pills js-main-menu',
                            'container_class' => 'nav-primary__mobile-menu',
                            'walker' => new Setup\Bootstrap_Walker(),
                        ]);
                    endif;
                    ?>
                    <span class="nav-primary__search">
                        <?php get_search_form(); ?>
                    </span>
                </div>
            </nav>
        </div>
    </div>
</header>
