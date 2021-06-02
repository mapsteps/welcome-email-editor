<?php
/**
 * From email field.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting from email field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$values = $module->values;

	$placeholder = site_url();
	$placeholder = rtrim( $placeholder, '/' );
	$placeholder = str_ireplace( 'https://', '', $placeholder );
	$placeholder = str_ireplace( 'http://', '', $placeholder );
	$placeholder = str_ireplace( 'www.', '', $placeholder );
	$placeholder = 'wordpress@' . $placeholder;
	?>

	<input type="text" name="weed_settings[from_email]" id="weed_settings--from_email" class="regular-text" value="<?php echo esc_attr( $values['from_email'] ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>" />

	<?php

};
