<?php
/**
 * Mailjet Backend field.
 *
 * @package Welcome_Email_Editor
 */

use Weed\Settings\Settings_Module;
use Weed\Vars;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting Mailjet Backend field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$values = Vars::get( 'values' );
	$value  = ! empty( $values['mailjet_backend'] ) ? $values['mailjet_backend'] : 'smtp';
	?>

	<div class="weed-fields weed-radio-fields" data-show-when-mailer-type="mailjet">
		<label for="weed_settings--mailjet_backend-smtp" class="label radio-label">
			<?php esc_html_e( 'SMTP', 'welcome-email-editor' ); ?>
			<input type="radio" name="weed_settings[mailjet_backend]" id="weed_settings--mailjet_backend-smtp"
					value="smtp" <?php checked( $value, 'smtp' ); ?>/>
			<div class="indicator"></div>
		</label>

		<label for="weed_settings--mailjet_backend-api" class="label radio-label">
			<?php esc_html_e( 'API', 'welcome-email-editor' ); ?>
			<input type="radio" name="weed_settings[mailjet_backend]" id="weed_settings--mailjet_backend-api"
					value="api" <?php checked( $value, 'api' ); ?>/>
			<div class="indicator"></div>
		</label>

		<p class="description">
			<?php esc_html_e( 'Choose SMTP for traditional email sending or API for direct Mailjet API integration. API method prevents automatic contact saving to Mailjet lists.', 'welcome-email-editor' ); ?>
		</p>
	</div>

	<?php

};
