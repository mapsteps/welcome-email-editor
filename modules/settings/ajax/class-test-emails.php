<?php
/**
 * Test emails.
 *
 * @package Welcome_Email_Editor
 */

namespace Weed\Settings\Ajax;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to handle ajax request to test emails.
 */
class Test_Emails {

	/**
	 * The class instance.
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * The email type.
	 *
	 * @var string
	 */
	public $email_type;

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
	 * The ajax handler.
	 */
	public function ajax_handler() {

		$this->email_type = isset( $_POST['email_type'] ) ? sanitize_text_field( $_POST['email_type'] ) : '';
		$this->nonce      = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';

		if ( ! $this->email_type ) {
			wp_send_json_error( 'Email type is required', 'welcome-email-editor' );
		}

		if ( ! $this->nonce ) {
			wp_send_json_error( 'Invalid nonce', 'welcome-email-editor' );
		}

		add_filter( 'weed_test_email_recipient', array( $this, 'set_testing_recipient' ) );

		switch ( $this->email_type ) {
			case 'admin_new_user_notif_email':
				$this->admin_new_user_notif_email();
				break;

			case 'user_welcome_email':
				$this->user_welcome_email();
				break;

			case 'reset_password_email':
				$this->reset_password_email();
				break;

			default:
				// code...
				break;
		}

		remove_filter( 'weed_test_email_recipient', array( $this, 'set_testing_recipient' ) );

	}

	/**
	 * Test admin's welcome email.
	 */
	public function admin_new_user_notif_email() {

		$current_user = wp_get_current_user();

		wp_new_user_notification( $current_user->ID, null, 'admin' );

		wp_send_json_success( __( 'Email has been sent successfully', 'welcome-email-editor' ) );

	}

	/**
	 * Test user's welcome email.
	 */
	public function user_welcome_email() {

		$current_user = wp_get_current_user();

		wp_new_user_notification( $current_user->ID, null, 'user' );

		wp_send_json_success( __( 'Email has been sent successfully', 'welcome-email-editor' ) );

	}

	/**
	 * Test reset password email.
	 */
	public function reset_password_email() {

		$current_user = wp_get_current_user();

		retrieve_password( $current_user->user_login );

		wp_send_json_success( __( 'Email has been sent successfully', 'welcome-email-editor' ) );

	}

	/**
	 * Set custom recipient for testing emails.
	 *
	 * @return string
	 */
	public function set_testing_recipient() {

		$current_user = wp_get_current_user();

		return $current_user->user_email;

	}

}
