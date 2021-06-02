<?php
/**
 * Reset password email's subject field.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting reset password email's subject field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$defaults = $module->defaults;
	$values   = $module->values;
	?>

	<input type="text" name="weed_settings[reset_password_email_subject]" id="weed_settings--reset_password_email_subject" class="regular-text" value="<?php echo esc_attr( $values['reset_password_email_subject'] ); ?>" placeholder="<?php echo esc_attr( $defaults['reset_password_email_subject'] ); ?>" />

	<?php

};
