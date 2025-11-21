<?php
/**
 * Mailjet sender name field.
 *
 * @package Welcome_Email_Editor
 */

use Weed\Settings\Settings_Module;
use Weed\Vars;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting mailjet sender name field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$values = Vars::get( 'values' );
	$value  = ! empty( $values['mailjet_sender_name'] ) ? $values['mailjet_sender_name'] : '';
	?>

	<div class="weed-fields" data-show-when-mailer-type="mailjet_api">
		<input type="text" name="weed_settings[mailjet_sender_name]" id="weed_settings--mailjet_sender_name"
				value="<?php echo esc_attr( $value ); ?>" class="regular-text"/>
		<p class="description">
			<?php esc_html_e( 'The name that will appear in the "From" field.', 'welcome-email-editor' ); ?>
		</p>
	</div>

	<?php

};
