<?php
/**
 * SMTP host field.
 *
 * @package Welcome_Email_Editor
 */

use Weed\Settings\Settings_Module;
use Weed\Vars;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting SMTP host field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$values = Vars::get( 'values' );
	$value  = ! empty( $values['smtp_host'] ) ? $values['smtp_host'] : '';
	?>

	<div data-show-when-mailer-type="smtp">
		<input type="text" name="weed_settings[smtp_host]" id="weed_settings--smtp_host" class="regular-text"
				value="<?php echo esc_attr( $value ); ?>" placeholder="Example: smtp.zoho.com"/>
	</div>

	<?php

};
