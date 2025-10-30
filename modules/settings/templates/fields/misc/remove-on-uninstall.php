<?php
/**
 * "Remove on uninstall" field.
 *
 * @package Welcome_Email_Editor
 */

use Weed\Vars;

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	$settings   = Vars::get( 'values' );
	$is_checked = isset( $settings['remove_on_uninstall'] ) ? 1 : 0;
	?>

	<label for="weed_settings[remove_on_uninstall]" class="label checkbox-label">
		&nbsp;
		<input type="checkbox" name="weed_settings[remove_on_uninstall]" id="weed_settings[remove_on_uninstall]" value="1" <?php checked( $is_checked, 1 ); ?>>
		<div class="indicator"></div>
	</label>

	<?php

};
