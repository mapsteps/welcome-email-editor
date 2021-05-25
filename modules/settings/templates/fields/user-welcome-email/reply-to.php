<?php
/**
 * User welcome email's reply to field.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting user welcome email's reply to field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$defaults = $module->defaults;
	$values   = $module->values;
	?>

	<input type="email" name="weed_settings[user_welcome_email_reply_to]" id="weed_settings--user_welcome_email_reply_to" class="regular-text" value="<?php echo esc_attr( $values['user_welcome_email_reply_to'] ); ?>" placeholder="<?php echo esc_attr( $defaults['user_welcome_email_reply_to'] ); ?>" />

	<?php

};
