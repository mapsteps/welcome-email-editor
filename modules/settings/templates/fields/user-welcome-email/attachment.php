<?php
/**
 * User welcome email's attachment field.
 *
 * @package Welcome_Email_Editor
 */

use Weed\Helpers\Content_Helper;
use Weed\Settings\Settings_Module;
use Weed\Vars;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting user welcome email's attachment field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$defaults = Content_Helper::default_settings();
	$values   = Vars::get( 'values' );
	?>

	<input type="url" name="weed_settings[user_welcome_email_attachment_url]" id="weed_settings--user_welcome_email_attachment_url" class="regular-text" value="<?php echo esc_attr( $values['user_welcome_email_attachment_url'] ); ?>" placeholder="<?php echo esc_attr( $defaults['user_welcome_email_attachment_url'] ); ?>" />

	<p class="description">
		<?php esc_html_e( 'To add an attachment to your welcome email, please enter the URL here.', 'welcome-email-editor' ); ?>
	</p>

	<?php

};
