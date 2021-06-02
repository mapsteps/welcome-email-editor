<?php
/**
 * Reset password email's body field.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting reset password email's body field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$defaults = $module->defaults;
	$values   = $module->values;
	?>

	<textarea name="weed_settings[reset_password_email_body]" id="weed_settings--reset_password_email_body" class="large-text" rows="8" placeholder="<?php echo esc_attr( $defaults['reset_password_email_body'] ); ?>"><?php echo esc_html( $values['reset_password_email_body'] ); ?></textarea>

	<?php

};
