<?php
/**
 * The Mailjet API module class.
 *
 * @package Weed
 */

namespace Weed\Mailjet_Api;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Weed\Base\Base_Module;

/**
 * Class to set up Mailjet API module.
 */
class Mailjet_Api_Module extends Base_Module {

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

		$this->url = WEED_PLUGIN_URL . '/modules/mailjet-api';

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

		// Load the Mailjet API sender class.
		require_once __DIR__ . '/class-mailjet-api-sender.php';

	}

}
