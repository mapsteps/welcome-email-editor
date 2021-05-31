<?php
/**
 * Settings module output.
 *
 * @package Welcome_Email_Editor
 */

namespace Weed\Settings;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Weed\Base\Base_Output;

/**
 * Class to setup dashboard output.
 */
class Settings_Output extends Base_Output {

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
	 * Setup settings output.
	 */
	public function setup() {

		if ( ! isset( $this->settings['enable'] ) ) {
			return;
		}

		add_filter( 'retrieve_password_title', array( $this, 'forgot_password_title' ), 10, 3 );
		add_filter( 'retrieve_password_message', array( $this, 'retrieve_password_message' ), 10, 4 );
		add_filter( 'wpmu_welcome_user_notification', array( $this, 'wpmu_new_user_notification' ), 10, 3 );

		/**
		 * Supporting https://s2member.com/ plugin.
		 * This support brought from the our plugin's old code.
		 *
		 * @see https://www.s2member.com/codex/stable/s2member/email_configs/package-filters/#src_doc_ws_plugin__s2member_after_email_config_release
		 */
		add_action( 'ws_plugin__s2member_after_email_config_release', array( $this, 'set_email_from_headers' ) );

	}

	/**
	 * Retrieve password title.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/retrieve_password_title/
	 *
	 * @param string  $title Email subject.
	 * @param string  $user_login The username for the user.
	 * @param WP_User $user_data WP_User object.
	 *
	 * @return string
	 */
	public function retrieve_password_title( $title, $user_login, $user_data ) {

		$saved_title = $this->values['forgot_password_email_subject'];

		if ( ! $saved_title ) {
			return $title;
		}

		return $saved_title;

	}

	/**
	 * Retrieve password message.
	 *
	 * @param string $message Email message.
	 * @param string $key The activation key.
	 * @param string $user_login The activation key.
	 * @param string $user_data WP_User object.
	 *
	 * @return string
	 */
	public function retrieve_password_message( $message, $key, $user_login, $user_data ) {

		$saved_message = $this->values['forgot_password_email_body'];

		if ( ! $saved_message ) {
			return $message;
		}

		$site_url = get_site_url();

		if ( is_multisite() ) {
			$blogname = $GLOBALS['current_site']->site_name;
		} else {
			$blogname = get_option( 'blogname' );
		}

		// $reset_url = network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login');
		$reset_url = wp_login_url() . '?action=rp&key=' . $key . '&login=' . rawurlencode( $user_login );

		$find = array(
			'[user_login]',
			'[blog_name]',
			'[site_url]',
			'[reset_url]',
		);

		$replace = array(
			$user_login,
			$blogname,
			$site_url,
			$reset_url,
		);

		$saved_message = str_ireplace( $find, $replace, $saved_message );

		return $saved_message;

	}

	/**
	 * Filters whether to bypass the welcome email after user activation.
	 *
	 * Returning false disables the welcome email.
	 *
	 * @param int    $user_id  User ID.
	 * @param string $password User password.
	 * @param array  $meta     Signup meta data. Default empty array.
	 */
	public function wpmu_new_user_notification( $user_id, $password, $meta = array() ) {

		return wp_new_user_notification( $user_id, null, 'both' ); // @todo.. check this does something and fix.

	}

	/**
	 * Set email http headers but without the content type.
	 */
	public function set_email_from_headers() {

		$values = $this->values;

		if ( $values['from_email'] ) {
			add_filter( 'wp_mail_from', array( $this, 'from_email' ), 1 );
		}

		if ( $values['from_name'] ) {
			add_filter( 'wp_mail_from_name', array( $this, 'from_name' ), 1 );
		}

		do_action( 'weed_set_email_from_headers' );

	}

	/**
	 * Set email http headers.
	 */
	public function set_email_headers() {

		$values = $this->values;

		if ( $values['from_email'] ) {
			add_filter( 'wp_mail_from', array( $this, 'from_email' ), 1 );
		}

		if ( $values['from_name'] ) {
			add_filter( 'wp_mail_from_name', array( $this, 'from_name' ), 1 );
		}

		if ( 'html' === $values['content_type'] ) {
			add_filter( 'wp_mail_content_type', array( $this, 'content_type' ), 1 );
			add_filter( 'wp_mail_charset', array( $this, 'charset' ), 1 );
		}

		do_action( 'weed_set_email_headers' );

	}

	/**
	 * Reset email http headers.
	 *
	 * @return void
	 */
	public function reset_email_headers() {

		$values = $this->values;

		if ( $values['from_email'] ) {
			remove_filter( 'wp_mail_from', array( $this, 'from_email' ), 1 );
		}

		if ( $values['from_name'] ) {
			remove_filter( 'wp_mail_from_name', array( $this, 'from_name' ), 1 );
		}

		if ( 'html' === $values['content_type'] ) {
			remove_filter( 'wp_mail_content_type', array( $this, 'content_type' ), 1 );
			remove_filter( 'wp_mail_charset', array( $this, 'charset' ), 1 );
		}

		do_action( 'weed_reset_email_headers' );

	}

	/**
	 * Implement from to http header.
	 *
	 * @param string $from_email Email address to send from.
	 */
	public function from_email( $from_email ) {

		$values = $this->values;

		if ( ! $values['from_email'] ) {
			return $from_email;
		}

		if ( ! apply_filters( 'weed_use_from_email', true ) ) {
			return $from_email;
		}

		$admin_email = get_option( 'admin_email' );

		$find = array(
			'[admin_email]',
		);

		$replace = array(
			$admin_email,
		);

		$from_email = str_ireplace( $find, $replace, $from_email );

		return $from_email;

	}

	/**
	 * Implement from to http header.
	 *
	 * @param string $from_name Name associated with the "from" email address.
	 */
	public function from_name( $from_name ) {

		$values = $this->values;

		if ( ! $values['from_name'] ) {
			return $from_name;
		}

		if ( ! apply_filters( 'weed_use_from_name', true ) ) {
			return $from_name;
		}

		$admin_email = get_option( 'admin_email' );

		$find = array(
			'[admin_email]',
		);

		$replace = array(
			$admin_email,
		);

		$from_name = str_ireplace( $find, $replace, $from_name );

		return $from_name;

	}

}
