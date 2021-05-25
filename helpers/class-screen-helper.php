<?php
/**
 * Screen helper.
 *
 * @package Welcome_Email_Editor
 */

namespace Weed\Helpers;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to setup screen helper.
 */
class Screen_Helper {
	/**
	 * Check if current screen is Welcome Email Editor's settings page.
	 *
	 * @return boolean
	 */
	public function is_settings() {

		$current_screen = get_current_screen();

		return ( 'settings_page_weed_settings' === $current_screen->id ? true : false );

	}
}
