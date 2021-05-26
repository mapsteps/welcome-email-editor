<?php
/**
 * User welcome email's attachment field.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting user welcome email's attachment field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$defaults = $module->defaults;
	$values   = $module->values;
	?>

	<input type="url" name="weed_settings[user_welcome_email_attachment_url]" id="weed_settings--user_welcome_email_attachment_url" class="regular-text" value="<?php echo esc_attr( $values['user_welcome_email_attachment_url'] ); ?>" placeholder="<?php echo esc_attr( $defaults['user_welcome_email_attachment_url'] ); ?>" />

	<p class="description">
		<?php _e( 'If you want the welcome email to have an attachment then put the URL here.', 'welcome-email-editor' ); ?>
	</p>

	<?php

};
