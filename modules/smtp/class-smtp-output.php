<?php
/**
 * SMTP module output.
 *
 * @package Welcome_Email_Editor
 */

namespace Weed\Smtp;

use PHPMailer\PHPMailer\PHPMailer;
use Weed\Base\Base_Output;
use Weed\Logs\Logs_Module;
use Weed\Vars;
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
	 * Flag to track if API sending failed.
	 *
	 * @var bool
	 */
	public $api_failed = false;

	/**
	 * Module constructor.
	 */
	public function __construct() {

		$this->url = WEED_PLUGIN_URL . '/modules/smtp';

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
	 * Setup SMTP output.
	 */
	public function setup() {

		add_filter( 'pre_wp_mail', array( $this, 'maybe_use_mailjet_api' ), 10, 2 );
		add_action( 'phpmailer_init', array( $this, 'phpmailer_init' ), 9999 );
		add_action( 'wp_mail_failed', array( $this, 'wp_mail_failed' ), 10 );

	}

	/**
	 * Intercept wp_mail and use Mailjet API if configured.
	 *
	 * @param null|bool $return Short-circuit return value.
	 * @param array     $atts   Array of the wp_mail() arguments.
	 *
	 * @return null|bool
	 */
	public function maybe_use_mailjet_api( $return, $atts ) {

		$values = Vars::get( 'values' );

		// Check if we should use Mailjet API.
		$mailer_type = ! empty( $values['mailer_type'] ) ? $values['mailer_type'] : 'default';

		if ( 'mailjet_api' !== $mailer_type ) {
			// Not using Mailjet API, let wp_mail continue normally.
			return $return;
		}

		// Use Mailjet API to send email.
		require_once WEED_PLUGIN_DIR . '/modules/mailjet-api/class-mailjet-api-sender.php';

		$sender = \Weed\Mailjet_Api\Mailjet_Api_Sender::get_instance();

		$to          = isset( $atts['to'] ) ? $atts['to'] : '';
		$subject     = isset( $atts['subject'] ) ? $atts['subject'] : '';
		$message     = isset( $atts['message'] ) ? $atts['message'] : '';
		$headers     = isset( $atts['headers'] ) ? $atts['headers'] : '';
		$attachments = isset( $atts['attachments'] ) ? $atts['attachments'] : array();

		// Set sender in global for logging (since phpmailer_init won't fire for API).
		$this->set_sender_for_logging( $headers );

		$result = $sender->send_email( $to, $subject, $message, $headers, $attachments );

		if ( $result ) {
			// Trigger success hook for logging.
			$this->trigger_mail_success_hook( $to, $subject, $message, $headers, $attachments );
			return true;
		}

		// Trigger failure hook for logging.
		$this->trigger_mail_failed_hook();

		// API failed, fall back to SMTP.
		$this->api_failed = true;

		// Return null to let wp_mail continue to phpmailer_init.
		return null;

	}

	/**
	 * Hook into phpmailer_init to set up SMTP.
	 *
	 * @param PHPMailer $php_mailer The PHPMailer instance.
	 */
	public function phpmailer_init( $php_mailer ) {

		$values = Vars::get( 'values' );

		// Get the mailer type (default to 'default' if not set).
		$mailer_type = ! empty( $values['mailer_type'] ) ? $values['mailer_type'] : 'default';

		// Configure PHPMailer based on mailer type.
		if ( 'mailjet_api' === $mailer_type ) {
			if ( ! $this->api_failed ) {
				// Use Mailjet API instead of SMTP.
				// We need to intercept the email before PHPMailer sends it.
				// This is done via the pre_wp_mail filter in a separate method.
				// Here we just return early to prevent SMTP configuration.
				return;
			}

			// Mailjet SMTP configuration (fallback).
			if ( empty( $values['mailjet_api_key'] ) || empty( $values['mailjet_secret_key'] ) ) {
				return;
			}

			$php_mailer->isSMTP();

			// phpcs:disable
			$php_mailer->Host       = 'in-v3.mailjet.com';
			$php_mailer->Port       = 587;
			$php_mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
			$php_mailer->SMTPAuth   = true;
			$php_mailer->Username   = $values['mailjet_api_key'];
			$php_mailer->Password   = $values['mailjet_secret_key'];
			// phpcs:enable
		} else {
			// Default SMTP configuration.
			if ( empty( $values['smtp_host'] ) || empty( $values['smtp_port'] ) ) {
				return;
			}

			$php_mailer->isSMTP();

			// phpcs:disable
			$php_mailer->Host     = $values['smtp_host'];
			$php_mailer->SMTPAuth = true;

			if ( ! empty( $values['smtp_username'] ) ) {
				$php_mailer->Username = $values['smtp_username'];
			}

			if ( ! empty( $values['smtp_password'] ) ) {
				$php_mailer->Password = $values['smtp_password'];
			}

			$php_mailer->Port = $values['smtp_port'];

			if ( ! empty( $values['smtp_encryption'] ) ) {
				$php_mailer->SMTPSecure = $values['smtp_encryption'] === 'ssl'
					? PHPMailer::ENCRYPTION_SMTPS
					: PHPMailer::ENCRYPTION_STARTTLS;
			}
			// phpcs:enable
		}

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

	/**
	 * Set sender email in global variable for logging.
	 *
	 * Since phpmailer_init doesn't fire when using Mailjet API,
	 * we need to manually set the sender for the Logs module.
	 *
	 * @param string|array $headers Email headers.
	 */
	private function set_sender_for_logging( $headers ) {

		$values = Vars::get( 'values' );

		// Determine sender email (same logic as Mailjet_Api_Sender::prepare_headers).
		$from_email = ! empty( $values['from_email'] ) ? $values['from_email'] : get_option( 'admin_email' );

		// Check if From header is provided.
		if ( ! empty( $headers ) ) {
			$headers_array = is_array( $headers ) ? $headers : explode( "\r\n", $headers );

			foreach ( $headers_array as $header ) {
				$header = trim( $header );

				if ( stripos( $header, 'From:' ) === 0 ) {
					$from_value = trim( substr( $header, 5 ) );

					if ( preg_match( '/(.*)<(.+)>/', $from_value, $matches ) ) {
						$from_email = trim( $matches[2] );
					} else {
						$from_email = $from_value;
					}
					break;
				}
			}
		}

		// Ensure Logs_Module class is loaded.
		require_once WEED_PLUGIN_DIR . '/modules/logs/class-logs-module.php';

		// Set sender in global variable used by Logs_Module.
		$log_global_var = ( new Logs_Module() )->log_global_var;

		if ( isset( $GLOBALS[ $log_global_var ] ) ) {
			$GLOBALS[ $log_global_var ]['sender'] = $from_email;
		}

	}

	/**
	 * Trigger wp_mail_succeeded hook for logging.
	 *
	 * @param string|string[] $to          Email recipients.
	 * @param string          $subject     Email subject.
	 * @param string          $message     Email message.
	 * @param string|string[] $headers     Email headers.
	 * @param string|string[] $attachments Email attachments.
	 */
	private function trigger_mail_success_hook( $to, $subject, $message, $headers, $attachments ) {

		// Build mail_data array matching WordPress wp_mail_succeeded structure.
		$mail_data = array(
			'to'          => is_array( $to ) ? $to : array( $to ),
			'subject'     => $subject,
			'message'     => $message,
			'headers'     => is_array( $headers ) ? $headers : ( ! empty( $headers ) ? explode( "\r\n", $headers ) : array() ),
			'attachments' => is_array( $attachments ) ? $attachments : array(),
		);

		/**
		 * Fires after Mailjet API has successfully sent an email.
		 *
		 * @param array $mail_data An array containing the email recipient(s), subject, message, headers, and attachments.
		 */
		do_action( 'wp_mail_succeeded', $mail_data );

	}

	/**
	 * Trigger wp_mail_failed hook for logging.
	 */
	private function trigger_mail_failed_hook() {

		$error = new WP_Error(
			'mailjet_api_failed',
			__( 'Mailjet API failed to send email. Falling back to SMTP.', 'welcome-email-editor' )
		);

		/**
		 * Fires after Mailjet API has failed to send an email.
		 *
		 * @param WP_Error $error A WP_Error object with the error message.
		 */
		do_action( 'wp_mail_failed', $error );

	}

}
