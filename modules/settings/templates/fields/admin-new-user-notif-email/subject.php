<?php
/**
 * Admin new user notification email's subject field.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting admin new user notification email's subject field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$defaults = $module->defaults;
	$values   = $module->values;
	?>

	<input type="text" name="weed_settings[admin_new_user_notif_email_subject]" id="weed_settings--admin_new_user_notif_email_subject" class="regular-text" value="<?php echo esc_attr( $values['admin_new_user_notif_email_subject'] ); ?>" placeholder="<?php echo esc_attr( $defaults['admin_new_user_notif_email_subject'] ); ?>" />

	<?php

};
