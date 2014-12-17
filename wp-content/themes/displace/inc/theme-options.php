<?php
/**
 * Displace Theme Options
 *
 * @package Displace
 * @since Displace 1.0
 */

/**
 * Register the form setting for our displace_options array.
 *
 * This function is attached to the admin_init action hook.
 *
 * This call to register_setting() registers a validation callback, displace_theme_options_validate(),
 * which is used when the option is saved, to ensure that our option values are properly
 * formatted, and safe.
 *
 * @since Displace 1.0
 */
function displace_theme_options_init() {
	register_setting(
		'displace_options', // Options group, see settings_fields() call in displace_theme_options_render_page()
		'displace_theme_options', // Database option, see displace_get_theme_options()
		'displace_theme_options_validate' // The sanitization callback, see displace_theme_options_validate()
	);

	// Register our settings field group
	add_settings_section(
		'general', // Unique identifier for the settings section
		'', // Section title (we don't want one)
		'__return_false', // Section callback (we don't want anything)
		'theme_options' // Menu slug, used to uniquely identify the page; see displace_theme_options_add_page()
	);

	// Register our individual settings fields
	add_settings_field( 'background_cover', __( 'Full width background', 'displace' ), 'displace_settings_field_background_cover', 'theme_options', 'general' );

	add_settings_field( 'color_scheme', __( 'Overlay', 'displace' ), 'displace_settings_color_scheme_radio_buttons', 'theme_options', 'general' );
}
add_action( 'admin_init', 'displace_theme_options_init' );

/**
 * Change the capability required to save the 'displace_options' options group.
 *
 * @see displace_theme_options_init() First parameter to register_setting() is the name of the options group.
 * @see displace_theme_options_add_page() The edit_theme_options capability is used for viewing the page.
 *
 * @param string $capability The capability used for the page, which is manage_options by default.
 * @return string The capability to actually use.
 */
function displace_option_page_capability( $capability ) {
	return 'edit_theme_options';
}
add_filter( 'option_page_capability_displace_options', 'displace_option_page_capability' );

/**
 * Add our theme options page to the admin menu.
 *
 * This function is attached to the admin_menu action hook.
 *
 * @since Displace 1.0
 */
function displace_theme_options_add_page() {
	$theme_page = add_theme_page(
		__( 'Theme Options', 'displace' ),   // Name of page
		__( 'Theme Options', 'displace' ),   // Label in menu
		'edit_theme_options',          // Capability required
		'theme_options',               // Menu slug, used to uniquely identify the page
		'displace_theme_options_render_page' // Function that renders the options page
	);
}
add_action( 'admin_menu', 'displace_theme_options_add_page' );

/**
 * Returns the options array for Displace.
 *
 * @since Displace 1.0
 */
function displace_get_theme_options() {
	$saved = (array) get_option( 'displace_theme_options' );
	$defaults = array(
		'background_cover'	=> 'off',
		'alignment'			=> 'center',
		'color_scheme'		=> 'dark'
	);

	$defaults = apply_filters( 'displace_default_theme_options', $defaults );

	$options = wp_parse_args( $saved, $defaults );
	$options = array_intersect_key( $options, $defaults );

	return $options;
}

/**
 * Renders the Theme Options administration screen.
 *
 * @since Displace 1.0
 */
function displace_theme_options_render_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php printf( __( '%s Theme Options', 'displace' ), wp_get_theme() ); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'displace_options' );
				do_settings_sections( 'theme_options' );
				submit_button();
			?>
		</form>
	</div>
	<?php
}

function displace_customize_register( $wp_customize ) {

	$defaults = displace_get_theme_options();

	$wp_customize->add_setting( 'displace_theme_options[color_scheme]', array(
		'type'				=> 'option',
		'default'			=> $defaults['color_scheme'],
		'sanitize_callback'	=> 'sanitize_key'
	) );

	$layouts = displace_color_scheme_radio_buttons();
	$choices = array();
	foreach ( $layouts as $layout ) {
		$choices[$layout['value']] = $layout['label'];
	}

	$wp_customize->add_control( 'displace_theme_options[color_scheme]', array(
		'label'		=> __( 'Overlay', 'displace' ),
		'section'	=> 'colors',
		'type'		=> 'radio',
		'choices'	=> $choices
	) );

	$wp_customize->add_setting( 'displace_theme_options[background_cover]', array(
		'type'				=> 'option',
		'default'			=> $defaults['background_cover'],
		'sanitize_callback'	=> 'sanitize_key'
	) );

	$layouts = displace_background_cover_radio_buttons();
	$choices = array();
	foreach ( $layouts as $layout ) {
		$choices[$layout['value']] = $layout['label'];
	}

	$wp_customize->add_control( 'displace_theme_options[background_cover]', array(
		'label'		=> __( 'Full width background', 'displace' ),
		'section'	=> 'background_image',
		'type'		=> 'radio',
		'choices'	=> $choices
	) );
}
add_action( 'customize_register', 'displace_customize_register' );

/**
 * Sanitize and validate form input. Accepts an array, return a sanitized array.
 *
 * @see displace_theme_options_init()
 * @todo set up Reset Options action
 *
 * @param array $input Unknown values.
 * @return array Sanitized theme options ready to be stored in the database.
 *
 * @since Displace 1.0
 */
function displace_theme_options_validate( $input ) {
	$output = array();

	if ( isset( $input['background_cover'] ) && array_key_exists( $input['background_cover'], displace_background_cover_radio_buttons() ) )
		$output['background_cover'] = $input['background_cover'];

	if ( isset( $input['color_scheme'] ) && array_key_exists( $input['color_scheme'], displace_color_scheme_radio_buttons() ) )
		$output['color_scheme'] = $input['color_scheme'];

	return apply_filters( 'displace_theme_options_validate', $output, $input );
}

/**
 * Returns an array of sample radio options registered for Displace.
 *
 * @since Displace 1.0
 */
function displace_background_cover_radio_buttons() {
	$background_cover_radio_buttons = array(
		'on' => array(
			'value' => 'on',
			'label' => __( 'On', 'displace' )
		),
		'off' => array(
			'value' => 'off',
			'label' => __( 'Off', 'displace' )
		)
	);

	return apply_filters( 'displace_background_cover_radio_buttons', $background_cover_radio_buttons );
}

/**
 * Returns an array of sample radio options registered for Displace.
 *
 * @since Displace 1.0
 */
function displace_color_scheme_radio_buttons() {
	$color_scheme_radio_buttons = array(
		'dark' => array(
			'value' => 'dark',
			'label' => __( 'Dark', 'displace' )
		),
		'bright' => array(
			'value' => 'bright',
			'label' => __( 'Transparent', 'displace' )
		)
	);

	return apply_filters( 'displace_color_scheme_radio_buttons', $color_scheme_radio_buttons );
}

/**
 * Renders the sample checkbox setting field.
 * This option will be disabled if background image is not set.
 */
function displace_settings_field_background_cover() {
	$options = displace_get_theme_options();
	$background_image = get_background_image();
	?>
	<label for="background_cover"<?php if ( empty( $background_image ) ) echo ' style="color: #aaa"'; ?>>
		<input type="checkbox" name="displace_theme_options[background_cover]" id="background_cover" <?php checked( 'on', $options['background_cover'] ); ?> <?php if ( empty( $background_image ) ) echo 'disabled'; ?> />
		<?php _e( 'Make the background image cover whole browser window', 'displace' ); ?><br/>
		<?php if ( empty( $background_image ) ) : ?>
			<p style="font-size: 95%; color: #666"><?php echo sprintf( __( 'Set a <a href="%s">custom background</a> first.', 'displace' ), admin_url('themes.php?page=custom-background') ); ?></p>
		<?php else : ?>
			<p style="font-size: 95%; color: #666"><?php _e( 'Try this feature with big beautiful images! Not suited for patterns.', 'displace' ); ?></p>
		<?php endif; ?>
	</label>
	<?php
}

/**
 * Renders the radio options setting field.
 *
 * @since Displace 1.0
 */
function displace_settings_color_scheme_radio_buttons() {
	$options = displace_get_theme_options();

	foreach ( displace_color_scheme_radio_buttons() as $button ) {
	?>
	<div class="layout">
		<label class="description">
			<input type="radio" name="displace_theme_options[color_scheme]" value="<?php echo esc_attr( $button['value'] ); ?>" <?php checked( $options['color_scheme'], $button['value'] ); ?> />
			<?php echo $button['label']; ?>
		</label>
	</div>
	<?php
	}
}