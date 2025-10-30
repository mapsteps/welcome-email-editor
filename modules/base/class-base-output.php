<?php
/**
 * Base module output.
 *
 * @package Welcome_Email_Editor
 */

namespace Weed\Base;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Weed\Helpers\Screen_Helper;

/**
 * Class to set up base output.
 */
class Base_Output {
	/**
	 * Screen helper.
	 *
	 * @return object Instance of screen helper.
	 */
	public function screen() {

		return new Screen_Helper();

	}
}
