<?php
/**
 * Mailjet public key field.
 *
 * @package Welcome_Email_Editor
 */

use Weed\Settings\Settings_Module;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting Mailjet public key field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$values = $module->values;
	$value  = ! empty( $values['mailjet_public_key'] ) ? $values['mailjet_public_key'] : '';
	?>

	<input type="text" name="weed_settings[mailjet_public_key]" id="weed_settings--mailjet_public_key" class="regular-text"
			value="<?php echo esc_attr( $value ); ?>" placeholder="Your Mailjet API Public Key"/>
	<p class="description">
		<?php esc_html_e( 'Enter your Mailjet API Public Key. You can find this in your Mailjet account under Account Settings > API Keys.', 'welcome-email-editor' ); ?>
	</p>

	<?php

};
