<?php
/**
 * Mailjet secret key field.
 *
 * @package Welcome_Email_Editor
 */

use Weed\Settings\Settings_Module;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting Mailjet secret key field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$values = $module->values;
	$value  = ! empty( $values['mailjet_secret_key'] ) ? $values['mailjet_secret_key'] : '';
	?>

	<input type="password" name="weed_settings[mailjet_secret_key]" id="weed_settings--mailjet_secret_key" class="regular-text"
		   value="<?php echo esc_attr( $value ); ?>" placeholder="Your Mailjet API Secret Key"/>
	<p class="description">
		<?php esc_html_e( 'Enter your Mailjet API Secret Key. This will be stored securely and masked for security.', 'welcome-email-editor' ); ?>
	</p>

	<?php

};
