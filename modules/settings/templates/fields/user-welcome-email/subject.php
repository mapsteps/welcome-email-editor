<?php
/**
 * User welcome email's subject field.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting user welcome email's subject field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$defaults = $module->defaults;
	$values   = $module->values;
	?>

	<input type="text" name="weed_settings[user_welcome_email_subject]" id="weed_settings--user_welcome_email_subject" class="regular-text" value="<?php echo esc_attr( $values['user_welcome_email_subject'] ); ?>" placeholder="<?php echo esc_attr( $defaults['user_welcome_email_subject'] ); ?>" />

	<?php

};
