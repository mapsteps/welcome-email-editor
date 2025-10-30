<?php
/**
 * Base module setup.
 *
 * @package Welcome_Email_Editor
 */

namespace Weed\Base;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Weed\Helpers\Screen_Helper;

/**
 * Class to set up base module.
 */
class Base_Module {
	/**
	 * Screen helper.
	 *
	 * @return object Instance of array helper.
	 */
	public function screen() {

		return new Screen_Helper();

	}
}
