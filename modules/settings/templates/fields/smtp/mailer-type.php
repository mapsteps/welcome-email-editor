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

	<div class="weed-fields weed-image-toggle-fields">
		<!-- SMTP Option with Envelope Icon -->
		<label for="weed_settings--mailer_type-smtp" class="image-toggle-label">
			<input type="radio" name="weed_settings[mailer_type]" 
				id="weed_settings--mailer_type-smtp"
				value="smtp" <?php checked( $value, 'smtp' ); ?> />
			<div class="image-toggle-container">
				<img src="<?php echo esc_url( WEED_PLUGIN_URL . '/assets/images/integrations/smtp-envelope.png' ); ?>" alt="SMTP (Default)" class="toggle-image" />
				<span class="toggle-label"><?php esc_html_e( 'SMTP (Default)', 'welcome-email-editor' ); ?></span>
			</div>
		</label>

		<!-- Mailjet API Option with Mailjet Logo -->
		<label for="weed_settings--mailer_type-mailjet_api" class="image-toggle-label">
			<input type="radio" name="weed_settings[mailer_type]" 
				id="weed_settings--mailer_type-mailjet_api"
				value="mailjet_api" <?php checked( $value, 'mailjet_api' ); ?> />
			<div class="image-toggle-container">
				<img src="<?php echo esc_url( WEED_PLUGIN_URL . '/assets/images/integrations/mailjet-logo.png' ); ?>" alt="Mailjet API" class="toggle-image" />
				<span class="toggle-label"><?php esc_html_e( 'Mailjet API', 'welcome-email-editor' ); ?></span>
			</div>
		</label>
	</div>

	<?php

};
