<?php
use Roots\Sage\Setup;
?>
<header class="banner">
  <div class="container">
    <a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
    <nav class="nav-primary">
		<?php
        if ( has_nav_menu( 'primary_navigation' ) ) :
            wp_nav_menu( [
                'theme_location' => 'primary_navigation',
                'menu_class' => 'nav nav-pills js-main-menu',
                'walker' => new Setup\Bootstrap_Walker(),
            ] );
        endif;
		?>
    </nav>
  </div>
</header>
