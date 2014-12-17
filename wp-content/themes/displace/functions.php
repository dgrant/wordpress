<?php
/**
 * Displace functions and definitions
 *
 * @package Displace
 * @since Displace 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Displace 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 820; /* pixels */

if ( ! function_exists( 'displace_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since Displace 1.0
 */
function displace_setup() {

	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Custom Theme Options
	 */
	require( get_template_directory() . '/inc/theme-options.php' );

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Displace, use a find and replace
	 * to change 'displace' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'displace', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	add_editor_style('style-editor.css');

	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 960, 360, true );

	function displace_image_quality( $quality ) {
		return 99;
	}

	add_filter( 'jpeg_quality', 'displace_image_quality' );
	add_filter( 'wp_editor_set_quality', 'displace_image_quality' );

	add_theme_support( 'html5' );

	/*
	 * This theme supports custom background color and image, and here
	 * we also set up the default background color.
	 */
	add_theme_support( 'custom-background', array(
		'default-color' => '1d2024',
	) );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'displace' ),
	) );

	/**
	 * Add support for the Aside Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'link', 'image', 'gallery', 'audio', 'video' ) );

	/**
	 * Add infinite scroll support for Jetpack
	 */
	add_theme_support( 'infinite-scroll', array(
		'type'		=> 'click',
		'container'	=> 'content',
		'footer'	=> 'colophon'
) );
}
endif; // displace_setup
add_action( 'after_setup_theme', 'displace_setup' );

/**
 * Enqueue scripts and styles
 */
function displace_scripts() {

	// Add Genericons font.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/fonts/genericons/genericons.css', array(), '3.0' );

	// Add Roboto Slab font.
	wp_enqueue_style( 'roboto-slab', '://fonts.googleapis.com/css?family=Roboto+Slab:400,700,300&subset=latin,cyrillic', array(), null );

	// Load main stylesheet.
	wp_enqueue_style( 'displace-style', get_stylesheet_uri() );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'displace_scripts' );

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since Displace 1.0
 */
function displace_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar', 'displace' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
}
add_action( 'widgets_init', 'displace_widgets_init' );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 */
function displace_wp_title( $title, $sep ) {
	global $page, $paged;

	if ( is_feed() )
		return $title;

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " $sep $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= " $sep " . sprintf( __( 'Page %s', 'displace' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'displace_wp_title', 10, 2 );

/**
 * Implement the Custom Header feature
 */
require( get_template_directory() . '/inc/custom-header.php' );

function displace_embed_defaults($embed_size) {
	if ( ! has_post_format( 'video' ) ) {
		$embed_size['width'] = 580;
		$embed_size['height'] = 330;
	} else {
		$embed_size['width'] = 820;
		$embed_size['height'] = 460;
	}

	return $embed_size;
}
add_filter('embed_defaults', 'displace_embed_defaults');

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @since Displace 1.0
 */
function displace_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}

add_filter( 'wp_page_menu_args', 'displace_page_menu_args' );

function displace_post_classes($classes) {
	global $content_width;

	if ( has_post_format('gallery') or has_post_format('image') or has_post_format('video') )
		$classes[] = 'post-dark';
	else
		$classes[] = 'post-bright';

	if ( has_post_format('gallery') or has_post_format('image') or has_post_format('video') or has_post_format('audio') or has_post_format('aside') or has_post_format('link') )
		$classes[] = 'post-wide';

	if ( has_post_thumbnail() ) {
		$classes[] = 'has-post-thumb';

		$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'post-thumbnail' );
		if ( $image[1] < $content_width )
			$classes[] = 'small-thumb';
	}

	return $classes;
}

add_filter('post_class', 'displace_post_classes');

/**
 * Adds custom classes to the array of body classes.
 *
 * @since Displace 1.0
 */
function displace_body_classes( $classes ) {
	global $content_width;

	$background_image = get_background_image();
	$theme_options = displace_get_theme_options();
	$background_color = get_background_color();

	// Adds a class of group-blog to blogs with more than 1 published author
	if ( is_multi_author() )
		$classes[] = 'group-blog';

	if ( get_option( 'show_avatars' ) == 1 )
		$classes[] = 'comment-avatars';

	// Add a class if a custom background image is set
	if ( !empty( $background_image ) ) {
		$classes[] = 'background-image';

		if ( $theme_options['background_cover'] == 'on' )
			$classes[] = 'background-cover';
	}

	// Add a class if a custom background color is set
	if ( ! in_array( $background_color, array( '1d2024' ) ) ) {
		$classes[] = 'background-color';
	}

	if ( $theme_options['color_scheme'] == 'dark' )
		$classes[] = 'dark';
	else
		$classes[] = 'bright';

	if ( is_attachment() ) {
		$mime_type = explode( '/', get_post_mime_type() );
		$mime_type = $mime_type[0];

		$classes[] = 'attachment-mime-' . $mime_type;
	}

	if ( is_attachment() && wp_attachment_is_image() ) {
		$image = wp_get_attachment_metadata();

		// check if attachment width is less than content width
		if ( $image['width'] < $content_width )
			$classes[] = 'small-attach';
	}

	return $classes;
}
add_filter( 'body_class', 'displace_body_classes' );

add_filter( 'use_default_gallery_style', '__return_false' );

/**
 * Remove 10px border from wp-captions
 */
function displace_caption_shortcode($attr, $content = null) {

	if ( ! isset( $attr['caption'] ) ) {
		if ( preg_match( '#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)(.*)#is', $content, $matches ) ) {
			$content = $matches[1];
			$attr['caption'] = trim( $matches[2] );
		}
	}

	$output = apply_filters('img_caption_shortcode', '', $attr, $content);
	if ( $output != '' )
		return $output;

	extract(shortcode_atts(array(
		'id' => '',
		'align' => 'alignnone',
		'width' => '',
		'caption' => ''
	), $attr));

	if ( 1 > (int) $width || empty($caption) )
	return $content;

	if ( $id ) $id = 'id="' . esc_attr($id) . '" ';

	return '<div ' . $id . 'class="wp-caption ' . esc_attr($align) . '" style="width: ' . $width . 'px">' . do_shortcode( $content ) . '<p class="wp-caption-text">' . $caption . '</p></div>';
}
add_shortcode('wp_caption', 'displace_caption_shortcode');
add_shortcode('caption', 'displace_caption_shortcode');

function displace_excerpt_more($more) {
	return ' &hellip;';
}
add_filter('excerpt_more', 'displace_excerpt_more');