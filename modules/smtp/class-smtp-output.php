<?php
/**
 * SMTP module output.
 *
 * @package Welcome_Email_Editor
 */

namespace Weed\Smtp;

use Weed\Base\Base_Output;
use Weed\Vars;
use Weed\Helpers\Mailjet_Helper;
use WP_Error;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to set up SMTP output.
 */
class Smtp_Output extends Base_Output {

	/**
	 * The class instance.
	 *
	 * @var object
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

		$this->url = WEED_PLUGIN_URL . '/modules/settings';

	}

	/**
	 * Get instance of the class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Init the class setup.
	 */
	public static function init() {

		$class = new self();
		$class->setup();

	}

	/**
	 * Setup Mailjet output.
	 */
	public function setup() {

		// Replace wp_mail function with Mailjet implementation
		add_filter( 'pre_wp_mail', array( $this, 'mailjet_wp_mail' ), 10, 2 );
		add_action( 'wp_mail_failed', array( $this, 'wp_mail_failed' ), 10 );

	}

	/**
	 * Replace wp_mail with Mailjet implementation.
	 *
	 * @param null|bool $return Short-circuit return value.
	 * @param array     $atts   Array of the `wp_mail()` arguments.
	 *
	 * @return bool|null
	 */
	public function mailjet_wp_mail( $return, $atts ) {

		$values = Vars::get( 'values' );

		// Check if Mailjet is configured
		if ( empty( $values['mailjet_public_key'] ) || empty( $values['mailjet_secret_key'] ) ) {
			// Let WordPress handle the email normally if Mailjet is not configured
			return null;
		}

		// Extract wp_mail arguments
		$to          = $atts['to'];
		$subject     = $atts['subject'];
		$message     = $atts['message'];
		$headers     = isset( $atts['headers'] ) ? $atts['headers'] : '';
		$attachments = isset( $atts['attachments'] ) ? $atts['attachments'] : array();

		// Use Mailjet helper to send email
		$mailjet_helper = new Mailjet_Helper();
		$result = $mailjet_helper->send( $to, $subject, $message, $headers, $attachments );

		// Return the result to short-circuit wp_mail
		return $result;

	}

	/**
	 * Hook into wp_mail_failed to catch errors.
	 *
	 * @param WP_Error $wp_error The WP_Error instance.
	 */
	public function wp_mail_failed( $wp_error ) {

		$error = $wp_error->get_error_message();

		Vars::set( 'wp_mail_failed', $error );
	}

}
