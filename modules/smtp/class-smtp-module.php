<?php
/**
 * The SMTP module class.
 *
 * @package Weed
 */

namespace Weed\Smtp;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Weed\Base\Base_Module;

/**
 * Class to set up SMTP module.
 */
class Smtp_Module extends Base_Module {

	/**
	 * The class instance.
	 *
	 * @var self
	 */
	public static $instance;

	/**
	 * The current module url.
	 *
	 * @var string
	 */
	public $url;

	/**
	 * Module constructor.
	 */
	public function __construct() {

		parent::__construct();

		$this->url = WEED_PLUGIN_URL . '/modules/smtp';

	}

	/**
	 * Get instance of the class.
	 *
	 * @return self
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Set up the module.
	 */
	public function setup() {

		// The module output.
		require_once __DIR__ . '/class-smtp-output.php';
		Smtp_Output::init();

	}

}
