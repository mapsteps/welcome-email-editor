<?php
/**
 * Base module output.
 *
 * @package Welcome_Email_Editor
 */

namespace Weed\Base;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Weed\Vars;
use Weed\Helpers\Screen_Helper;

/**
 * Class to set up base output.
 */
class Base_Output {
	/**
	 * The parsed settings values.
	 *
	 * @var array
	 */
	public $values;

	/**
	 * Module constructor.
	 */
	public function __construct() {

		$this->values = Vars::get( 'values' );

	}

	/**
	 * Screen helper.
	 *
	 * @return object Instance of screen helper.
	 */
	public function screen() {

		return new Screen_Helper();

	}
}
