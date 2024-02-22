<?php
/**
 * SMTP module output.
 *
 * @package Welcome_Email_Editor
 */

namespace Weed\Smtp;

use PHPMailer\PHPMailer\PHPMailer;
use Weed\Base\Base_Output;
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
	 * Setup SMTP output.
	 */
	public function setup() {

		add_action( 'phpmailer_init', array( $this, 'phpmailer_init' ), 9999 );
		add_action( 'wp_mail_failed', array( $this, 'wp_mail_failed' ), 10 );

	}

	/**
	 * Hook into phpmailer_init to set up SMTP.
	 *
	 * @param PHPMailer $php_mailer The PHPMailer instance.
	 */
	public function phpmailer_init( $php_mailer ) {

		$values = Vars::get( 'values' );

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
