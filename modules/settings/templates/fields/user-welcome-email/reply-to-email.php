<?php
/**
 * User welcome email's reply to email field.
 *
 * @package Welcome_Email_Editor
 */

use Weed\Helpers\Content_Helper;
use Weed\Settings\Settings_Module;
use Weed\Vars;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting user welcome email's reply to field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$defaults = Content_Helper::default_settings();
	$values   = Vars::get( 'values' );
	?>

	<input type="text" name="weed_settings[user_welcome_email_reply_to_email]" id="weed_settings--user_welcome_email_reply_to_email" class="regular-text" value="<?php echo esc_attr( $values['user_welcome_email_reply_to_email'] ); ?>" placeholder="<?php echo esc_attr( $defaults['user_welcome_email_reply_to_email'] ); ?>" />

	<?php

};
