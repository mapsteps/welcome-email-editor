<?php
/**
 * Mailer type field.
 *
 * @package Welcome_Email_Editor
 */

use Weed\Settings\Settings_Module;
use Weed\Vars;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting mailer type field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$values = Vars::get( 'values' );
	$value  = ! empty( $values['mailer_type'] ) ? $values['mailer_type'] : 'smtp';
	?>

	<div class="weed-fields weed-radio-fields">
		<label for="weed_settings--mailer_type-smtp" class="label radio-label">
			<?php esc_html_e( 'SMTP (Default)', 'welcome-email-editor' ); ?>
			<input type="radio" name="weed_settings[mailer_type]" id="weed_settings--mailer_type-smtp"
					value="smtp" <?php checked( $value, 'smtp' ); ?>/>
			<div class="indicator"></div>
		</label>

		<label for="weed_settings--mailer_type-mailjet_api" class="label radio-label">
			<?php esc_html_e( 'Mailjet API', 'welcome-email-editor' ); ?>
			<input type="radio" name="weed_settings[mailer_type]" id="weed_settings--mailer_type-mailjet_api"
					value="mailjet_api" <?php checked( $value, 'mailjet_api' ); ?>/>
			<div class="indicator"></div>
		</label>
	</div>

	<?php

};
