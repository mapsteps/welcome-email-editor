<?php
/**
 * Base module setup.
 *
 * @package Welcome_Email_Editor
 */

namespace Weed\Base;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Weed\Vars;
use Weed\Helpers\Screen_Helper;

/**
 * Class to setup base module.
 */
class Base_Module {
	/**
	 * The default settings.
	 *
	 * @var array
	 */
	public $defaults;

	/**
	 * The saved settings.
	 *
	 * @var array
	 */
	public $settings;

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

		$this->defaults = Vars::get( 'default_settings' );
		$this->settings = Vars::get( 'settings' );
		$this->values   = Vars::get( 'values' );

	}

	/**
	 * Screen helper.
	 *
	 * @return object Instance of array helper.
	 */
	public function screen() {

		return new Screen_Helper();

	}
}
