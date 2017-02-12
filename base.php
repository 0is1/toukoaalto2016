<?php

use Roots\Sage\Setup;
use Roots\Sage\Wrapper;

?>

<!doctype html>
<html <?php language_attributes(); ?>>
	<?php get_template_part( 'templates/head' ); ?>
  	<body <?php body_class(); ?>>
	<?php if (
		get_field( ACF_FACEBOOK_APP_ID, ACF_OPTION_KEY ) &&
		get_field( ACF_FACEBOOK_ID, ACF_OPTION_KEY ) &&
		get_field( ACF_ENABLE_FACEBOOK_PAGE_BOX, ACF_OPTION_KEY ) ) : ?>
		<?php $facebook_app_id = get_field( ACF_FACEBOOK_APP_ID, ACF_OPTION_KEY ) ?>
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/fi_FI/sdk.js#xfbml=1&version=v2.8&appId=<?php echo $facebook_app_id;?>";
		fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
	<?php endif; ?>
    <!--[if IE]>
      <div class="alert alert-warning browsehappy">
        <?php _e( 'You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', THEME_SLUG ); ?>
      </div>
    <![endif]-->
    <?php
      do_action( 'get_header' );
      get_template_part( 'templates/header' );
    ?>
    <div class="wrap container" role="document">
      <div class="content row">
        <main class="main">
			<?php include Wrapper\template_path(); ?>
        </main><!-- /.main -->
        <?php if ( Setup\display_sidebar() ) : ?>
          <aside class="sidebar">
            <?php include Wrapper\sidebar_path(); ?>
          </aside><!-- /.sidebar -->
        <?php endif; ?>
      </div><!-- /.content -->
    </div><!-- /.wrap -->
    <?php
      do_action( 'get_footer' );
      get_template_part( 'templates/footer' );
      wp_footer();
    ?>
  </body>
</html>
