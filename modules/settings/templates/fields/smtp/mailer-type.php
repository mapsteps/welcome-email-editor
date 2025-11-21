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
	$value  = ! empty( $values['mailer_type'] ) ? $values['mailer_type'] : 'default';
	?>

	<div class="weed-fields weed-radio-fields">
		<label for="weed_settings--mailer_type-default" class="label radio-label">
			<?php esc_html_e( 'Default (SMTP)', 'welcome-email-editor' ); ?>
			<input type="radio" name="weed_settings[mailer_type]" id="weed_settings--mailer_type-default"
					value="default" <?php checked( $value, 'default' ); ?>/>
			<div class="indicator"></div>
		</label>

		<label for="weed_settings--mailer_type-mailjet" class="label radio-label">
			<?php esc_html_e( 'Mailjet', 'welcome-email-editor' ); ?>
			<input type="radio" name="weed_settings[mailer_type]" id="weed_settings--mailer_type-mailjet"
					value="mailjet" <?php checked( $value, 'mailjet' ); ?>/>
			<div class="indicator"></div>
		</label>
	</div>

	<?php

};
