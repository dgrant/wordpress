<?php
/**
 * @package Displace
 * @since Displace 1.0
 */

/**
 * Setup the WordPress core custom header feature.
 *
 * @uses displace_header_style()
 * @uses displace_admin_header_style()
 * @uses displace_admin_header_image()
 *
 * @package Displace
 */
function displace_custom_header_setup() {
	$args = array(
		'default-image'          => '',
		'default-text-color'     => 'fff',
		'width'                  => 220,
		'height'                 => 220,
		'flex-height'            => true,
		'wp-head-callback'       => 'displace_header_style',
		'admin-head-callback'    => 'displace_admin_header_style',
		'admin-preview-callback' => 'displace_admin_header_image'
	);

	$args = apply_filters( 'displace_custom_header_args', $args );

	add_theme_support( 'custom-header', $args );
}
add_action( 'after_setup_theme', 'displace_custom_header_setup' );

/**
 * Shiv for get_custom_header().
 *
 * get_custom_header() was introduced to WordPress
 * in version 3.4. To provide backward compatibility
 * with previous versions, we will define our own version
 * of this function.
 *
 * @todo Remove this function when WordPress 3.6 is released.
 * @return stdClass All properties represent attributes of the curent header image.
 *
 * @package Displace
 * @since Displace 1.1
 */

if ( ! function_exists( 'get_custom_header' ) ) {
	function get_custom_header() {
		return (object) array(
			'url'           => get_header_image(),
			'thumbnail_url' => get_header_image(),
			'width'         => HEADER_IMAGE_WIDTH,
			'height'        => HEADER_IMAGE_HEIGHT,
		);
	}
}

if ( ! function_exists( 'displace_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see displace_custom_header_setup().
 *
 * @since Displace 1.0
 */
function displace_header_style() {

	// If no custom options for text are set, let's bail
	// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
	if ( HEADER_TEXTCOLOR == get_header_textcolor() )
		return;
	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == get_header_textcolor() ) :
	?>
		#masthead > div {
			margin: 0;
			padding: 0;
			border: 0;
			box-shadow: none;
		}
		a.caption-image {
			margin-bottom: 30px;
		}
		.site-title,
		.site-description {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		.site-title a,
		.site-description {
			color: #<?php echo get_header_textcolor(); ?> !important;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // displace_header_style

if ( ! function_exists( 'displace_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @see displace_custom_header_setup().
 *
 * @since Displace 1.0
 */
function displace_admin_header_style() {
	$background_color = get_background_color();
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		width: 220px;
		padding: 40px;
		border: none;
		text-align: right;
		background-color: #<?php echo $background_color; ?>;
	}
	#headimg h1 {
		margin: 0;
		padding-bottom: 5px;
		line-height: 50px;
		font-family: "League Gothic", sans-serif;
		font-weight: 400;
		font-size: 54px;
		letter-spacing: -1px;
		text-shadow: 0 3px rgba(0,0,0,0.25);
	}
	#headimg h1 a {
		color: #fff;
		text-decoration: none;
	}
	#headimg img {
		display: block;
		margin-left: auto;
		margin-bottom: 15px;
		border-radius: 1px;
	}
	#desc {
		line-height: 20px;
		opacity: 0.8;
		font-family: "Segoe UI", sans-serif;
		font-style: italic;
		font-size: 14px;
		text-shadow: 0 1px rgba(0,0,0,0.25);
	}
	</style>
<?php
}
endif; // displace_admin_header_style

if ( ! function_exists( 'displace_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @see displace_custom_header_setup().
 *
 * @since Displace 1.0
 */
function displace_admin_header_image() { ?>
	<div id="headimg">
		<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
		<?php endif;

		if ( 'blank' == get_header_textcolor() || '' == get_header_textcolor() )
			$style = ' style="display:none;"';
		else
			$style = ' style="color:#' . get_header_textcolor() . ';"';
		?>
		<h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
	</div>
<?php }
endif; // displace_admin_header_image