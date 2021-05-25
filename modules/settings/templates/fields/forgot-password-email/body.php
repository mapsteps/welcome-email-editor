<?php
/**
 * Forgot password email's body field.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting forgot password email's body field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$defaults = $module->defaults;
	$values   = $module->values;
	?>

	<textarea name="weed_settings[forgot_password_email_body]" id="weed_settings--forgot_password_email_body" class="large-text" rows="5" value="<?php echo esc_attr( $values['forgot_password_email_body'] ); ?>" placeholder="<?php echo esc_attr( $defaults['forgot_password_email_body'] ); ?>"><?php echo esc_html( $defaults['forgot_password_email_body'] ); ?></textarea>

	<?php

};
