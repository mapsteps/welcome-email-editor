<?php
/**
 * Mailjet sender email field.
 *
 * @package Welcome_Email_Editor
 */

use Weed\Settings\Settings_Module;
use Weed\Vars;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting mailjet sender email field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$values = Vars::get( 'values' );
	$value  = ! empty( $values['mailjet_sender_email'] ) ? $values['mailjet_sender_email'] : '';
	?>

	<div class="weed-fields" data-show-when-mailer-type="mailjet_api">
		<input type="email" name="weed_settings[mailjet_sender_email]" id="weed_settings--mailjet_sender_email"
				value="<?php echo esc_attr( $value ); ?>" class="regular-text"/>
		<p class="description">
			<?php esc_html_e( 'The email address that will appear in the "From" field. Must be a verified sender in Mailjet.', 'welcome-email-editor' ); ?>
		</p>
	</div>

	<?php

};
