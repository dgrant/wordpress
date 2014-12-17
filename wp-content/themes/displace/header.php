<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Displace
 * @since Displace 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
<title><?php wp_title( '&bull;', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 10]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
<![endif]-->
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<?php do_action( 'before' ); ?>
	<header id="masthead" class="site-header" role="banner">
		<?php
		$header_image = get_header_image();
		$site_title = get_bloginfo( 'name' );
		$site_description = get_bloginfo( 'description' );

		if ( ! empty( $header_image ) or ! empty( $site_title ) or ! empty( $site_description ) ) : ?>
			<div>
				<?php if ( ! empty( $header_image ) ) : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" class="caption-image" rel="home">
						<img src="<?php echo esc_url( $header_image ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" />
					</a>
				<?php endif;

				if ( ! empty( $site_title ) ) : ?>
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php endif;

				if ( ! empty( $site_description ) ) : ?>
					<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
				<?php endif; ?>
			</div>
		<?php endif;


		$primary_menu = wp_nav_menu( array( 'theme_location' => 'primary', 'echo' => false, 'fallback_cb' => '__return_null' ) );

		if ( $primary_menu ) : ?>
			<nav role="navigation" class="site-navigation main-navigation">
				<h1 class="assistive-text"><?php _e( 'Menu', 'displace' ); ?></h1>
				<div class="assistive-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'displace' ); ?>"><?php _e( 'Skip to content', 'displace' ); ?></a></div>

				<?php echo $primary_menu; ?>
			</nav><!-- .site-navigation .main-navigation -->
		<?php endif; ?>
	</header><!-- #masthead .site-header -->
