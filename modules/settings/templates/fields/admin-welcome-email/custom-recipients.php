<?php
/**
 * Admin welcome email's custom recipient field.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting admin welcome email's custom recipient field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$defaults = $module->defaults;
	$values   = $module->values;
	?>

	<input type="text" name="weed_settings[admin_welcome_email_custom_recipients]" id="weed_settings--admin_welcome_email_custom_recipients" class="regular-text" value="<?php echo esc_attr( $values['admin_welcome_email_custom_recipients'] ); ?>" placeholder="<?php echo esc_attr( $defaults['admin_welcome_email_custom_recipients'] ); ?>" />

	<?php

};
