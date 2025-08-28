<?php
/**
 * Mailjet sender name field.
 *
 * @package Welcome_Email_Editor
 */

use Weed\Settings\Settings_Module;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting Mailjet sender name field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$values = $module->values;
	$value  = ! empty( $values['mailjet_sender_name'] ) ? $values['mailjet_sender_name'] : get_option( 'blogname' );
	?>

	<input type="text" name="weed_settings[mailjet_sender_name]" id="weed_settings--mailjet_sender_name" class="regular-text"
		   value="<?php echo esc_attr( $value ); ?>" placeholder="Your Name or Company Name"/>
	<p class="description">
		<?php esc_html_e( 'The name that will appear as the sender of your emails.', 'welcome-email-editor' ); ?>
	</p>

	<?php

};
