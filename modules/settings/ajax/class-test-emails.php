<?php
/**
 * Test emails.
 *
 * @package Welcome_Email_Editor
 */

namespace Weed\Settings\Ajax;

use Weed\Vars;

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
	 * The nonce.
	 *
	 * @var string
	 */
	public $nonce;

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

		$capability = apply_filters( 'weed_settings_capability', 'manage_options' );

		if ( ! current_user_can( $capability ) ) {
			wp_send_json_error( 'You do not have permission to do this', 'welcome-email-editor' );
		}

		if ( ! $this->email_type ) {
			wp_send_json_error( 'Email type is required', 'welcome-email-editor' );
		}

		if ( ! $this->nonce ) {
			wp_send_json_error( 'Invalid nonce', 'welcome-email-editor' );
		}

		if ( ! $this->verify_nonce() ) {
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

			case 'test_smtp_email':
				$this->test_smtp_email();
				break;

			case 'test_mailjet_api_email':
				$this->test_mailjet_api_email();
				break;

			case 'test_mailjet_api_with_attachment':
				$this->test_mailjet_api_with_attachment();
				break;
		}

		remove_filter( 'weed_test_email_recipient', array( $this, 'set_testing_recipient' ) );

	}

	/**
	 * Verify nonce.
	 *
	 * @return bool
	 */
	private function verify_nonce() {

		$nonce_action = '';

		switch ( $this->email_type ) {
			case 'admin_new_user_notif_email':
				$nonce_action = WEED_PLUGIN_DIR . '_Admin_Welcome_Email';
				break;

			case 'user_welcome_email':
				$nonce_action = WEED_PLUGIN_DIR . '_User_Welcome_Email';
				break;

			case 'reset_password_email':
				$nonce_action = WEED_PLUGIN_DIR . '_Reset_Password_Email';
				break;

			case 'test_smtp_email':
				$nonce_action = WEED_PLUGIN_DIR . '_Test_SMTP_Email';
				break;

			case 'test_mailjet_api_email':
				$nonce_action = WEED_PLUGIN_DIR . '_Test_Mailjet_API_Email';
				break;

			case 'test_mailjet_api_with_attachment':
				$nonce_action = WEED_PLUGIN_DIR . '_Test_Mailjet_API_Email_With_Attachment';
				break;
		}

		$is_valid = wp_verify_nonce( $this->nonce, $nonce_action );

		return (bool) $is_valid;

	}

	/**
	 * Test admin's welcome email.
	 */
	public function admin_new_user_notif_email() {

		$current_user = wp_get_current_user();

		wp_new_user_notification( $current_user->ID, null, 'admin' );

		$error_msg = Vars::get( 'wp_mail_failed' );

		if ( $error_msg ) {
			wp_send_json_error( $error_msg );
		}

		wp_send_json_success( __( 'Email has been sent successfully', 'welcome-email-editor' ) );

	}

	/**
	 * Test user's welcome email.
	 */
	public function user_welcome_email() {

		$current_user = wp_get_current_user();

		wp_new_user_notification( $current_user->ID, null, 'user' );

		$error_msg = Vars::get( 'wp_mail_failed' );

		if ( $error_msg ) {
			wp_send_json_error( $error_msg );
		}

		wp_send_json_success( __( 'Email has been sent successfully', 'welcome-email-editor' ) );

	}

	/**
	 * Test reset password email.
	 */
	public function reset_password_email() {

		$current_user = wp_get_current_user();

		retrieve_password( $current_user->user_login );

		$error_msg = Vars::get( 'wp_mail_failed' );

		if ( $error_msg ) {
			wp_send_json_error( $error_msg );
		}

		wp_send_json_success( __( 'Email has been sent successfully', 'welcome-email-editor' ) );

	}

	/**
	 * Test email.
	 */
	public function test_smtp_email() {

		$admin_email = get_bloginfo( 'admin_email' );
		$recipient   = isset( $_POST['to_email'] ) ? sanitize_text_field( wp_unslash( $_POST['to_email'] ) ) : $admin_email;

		$values = Vars::get( 'values' );

		$content_type = isset( $values['content_type'] ) ? $values['content_type'] : 'text';

		$subject = __( 'Swift SMTP: Test Email', 'welcome-email-editor' );

		ob_start();
		require WEED_PLUGIN_DIR . '/modules/settings/templates/emails/smtp-test' . ( $content_type === 'html' ? '-' . $content_type : '' ) . '-email.php';
		$body = ob_get_clean();

		wp_mail( $recipient, $subject, $body );

		$error_msg = Vars::get( 'wp_mail_failed' );

		if ( $error_msg ) {
			wp_send_json_error( $error_msg );
		}

		wp_send_json_success( __( 'Email has been sent successfully', 'welcome-email-editor' ) );

	}

	/**
	 * Test Mailjet API email.
	 */
	public function test_mailjet_api_email() {

		$admin_email = get_bloginfo( 'admin_email' );
		$recipient   = isset( $_POST['to_email'] ) ? sanitize_text_field( wp_unslash( $_POST['to_email'] ) ) : $admin_email;

		$values = Vars::get( 'values' );

		$content_type = isset( $values['content_type'] ) ? $values['content_type'] : 'text';

		$subject = __( 'Swift SMTP: Mailjet API Test Email', 'welcome-email-editor' );

		ob_start();
		require WEED_PLUGIN_DIR . '/modules/settings/templates/emails/smtp-test' . ( $content_type === 'html' ? '-' . $content_type : '' ) . '-email.php';
		$body = ob_get_clean();

		// Use Mailjet API sender directly.
		$mailjet_sender = \Weed\Mailjet_Api\Mailjet_Api_Sender::get_instance();
		$result         = $mailjet_sender->send_email( $recipient, $subject, $body );

		if ( ! $result ) {
			$error_msg = Vars::get( 'wp_mail_failed' );
			wp_send_json_error( $error_msg ? $error_msg : __( 'Failed to send test email via Mailjet API', 'welcome-email-editor' ) );
		}

		wp_send_json_success( __( 'Email has been sent successfully via Mailjet API', 'welcome-email-editor' ) );

	}

	/**
	 * Test Mailjet API email with attachment.
	 */
	public function test_mailjet_api_with_attachment() {

		$admin_email = get_bloginfo( 'admin_email' );
		$recipient   = isset( $_POST['to_email'] ) ? sanitize_text_field( wp_unslash( $_POST['to_email'] ) ) : $admin_email;

		$values = Vars::get( 'values' );

		$content_type = isset( $values['content_type'] ) ? $values['content_type'] : 'text';

		$subject = __( 'Swift SMTP: Mailjet API Test Email with Attachment', 'welcome-email-editor' );

		ob_start();
		require WEED_PLUGIN_DIR . '/modules/settings/templates/emails/smtp-test' . ( $content_type === 'html' ? '-' . $content_type : '' ) . '-email.php';
		$body = ob_get_clean();

		// Generate a sample text file attachment.
		$upload_dir    = wp_upload_dir();
		$temp_file     = trailingslashit( $upload_dir['basedir'] ) . 'mailjet-test-attachment-' . time() . '.txt';
		$file_content  = "This is a test attachment file generated by Swift SMTP.\n\n";
		$file_content .= 'Date: ' . current_time( 'mysql' ) . "\n";
		$file_content .= 'Recipient: ' . $recipient . "\n";

		// Create the temporary file.
		file_put_contents( $temp_file, $file_content );

		// Use Mailjet API sender directly with attachment.
		$mailjet_sender = \Weed\Mailjet_Api\Mailjet_Api_Sender::get_instance();
		$result         = $mailjet_sender->send_email( $recipient, $subject, $body, '', array( $temp_file ) );

		// Clean up the temporary file.
		if ( file_exists( $temp_file ) ) {
			unlink( $temp_file );
		}

		if ( ! $result ) {
			$error_msg = Vars::get( 'wp_mail_failed' );
			wp_send_json_error( $error_msg ? $error_msg : __( 'Failed to send test email with attachment via Mailjet API', 'welcome-email-editor' ) );
		}

		wp_send_json_success( __( 'Email with attachment has been sent successfully via Mailjet API', 'welcome-email-editor' ) );

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
