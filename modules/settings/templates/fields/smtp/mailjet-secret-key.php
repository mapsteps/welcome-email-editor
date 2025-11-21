<?php
/**
 * Mailjet Secret Key field.
 *
 * @package Welcome_Email_Editor
 */

use Weed\Settings\Settings_Module;
use Weed\Vars;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting Mailjet Secret Key field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$values = Vars::get( 'values' );
	$value  = ! empty( $values['mailjet_secret_key'] ) ? $values['mailjet_secret_key'] : '';
	?>

	<div data-show-when-mailer-type="mailjet_api">
		<input type="password" name="weed_settings[mailjet_secret_key]" id="weed_settings--mailjet_secret_key" class="regular-text"
				value="<?php echo esc_attr( $value ); ?>" placeholder="Enter your Mailjet Secret Key"/>
	</div>

	<?php

};
