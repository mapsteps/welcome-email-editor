<?php
/**
 * Forgot password email's subject field.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting forgot password email's subject field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$defaults = $module->defaults;
	$values   = $module->values;
	?>

	<input type="text" name="weed_settings[forgot_password_email_subject]" id="weed_settings--forgot_password_email_subject" class="regular-text" value="<?php echo esc_attr( $values['forgot_password_email_subject'] ); ?>" placeholder="<?php echo esc_attr( $defaults['forgot_password_email_subject'] ); ?>" />

	<?php

};
