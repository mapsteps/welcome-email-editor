<?php
/**
 * Admin new user notification email's body field.
 *
 * @package Welcome_Email_Editor
 */

use Weed\Helpers\Content_Helper;
use Weed\Settings\Settings_Module;
use Weed\Vars;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting admin new user notification email's body field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$defaults = Content_Helper::default_settings();
	$values   = Vars::get( 'values' );
	?>

	<textarea name="weed_settings[admin_new_user_notif_email_body]" id="weed_settings--admin_new_user_notif_email_body"
				class="large-text" rows="8"
				placeholder="<?php echo esc_attr( $defaults['admin_new_user_notif_email_body'] ); ?>"><?php echo esc_html( $values['admin_new_user_notif_email_body'] ); ?></textarea>

	<?php

};
