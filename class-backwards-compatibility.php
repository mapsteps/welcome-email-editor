<?php
/**
 * Backwards compatibility.
 *
 * @package Welcome_Email_Editor
 */

namespace Weed;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class that handles backwards compatibility.
 */
class Backwards_Compatibility {

	/**
	 * The class instance.
	 *
	 * @var object
	 */
	public static $instance;

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
		$instance = new Backwards_Compatibility();
		$instance->setup();
	}

	/**
	 * Setup the class.
	 */
	public function setup() {

		add_action( 'admin_init', array( $this, 'compatibility_check' ) );

	}

	/**
	 * Run compatibility checking on admin_init hook.
	 */
	public function compatibility_check() {

		// Don't run checking on heartbeat request.
		if ( isset( $_POST['action'] ) && 'heartbeat' === $_POST['action'] ) {
			return;
		}

		$this->v5_compatibility();

	}

	/**
	 * Run compatibility check based on v5 update.
	 *
	 * The v5 update is the first update after plugin's transfer from Sean to David.
	 * It was a major update where there are plugin re-structure and option key (and sub-keys) renaming.
	 */
	public function v5_compatibility() {

		// Make sure we don't check again.
		if ( get_option( 'weed_v5_compatibility' ) ) {
			return;
		}

		$old_settings = get_option( 'sb_we_settings', array() );
		$settings     = get_option( 'weed_settings', array() );

		if ( empty( $old_settings ) || ! is_object( $old_settings ) ) {
			// Make sure we don't check again.
			update_option( 'weed_v5_compatibility', 1 );

			return;
		}

		/**
		 * If "Set Global Email Headers" was set to "Yes", then bring the values to the new "General Settings".
		 * If set to "No", then leave the "General Settings" empty.
		 *
		 * The "Mail Content Type" has "html" as the default value for it's selectbox.
		 */
		if ( $old_settings->set_global_headers ) {
			if ( $old_settings->header_from_email ) {
				$settings['from_email'] = $old_settings->header_from_email;
			}

			if ( $old_settings->header_from_name ) {
				$settings['from_name'] = $old_settings->header_from_name;
			}

			if ( $old_settings->header_send_as ) {
				$settings['content_type'] = $old_settings->header_send_as;
			}
		}

		// User welcome email metabox.

		if ( $old_settings->user_subject ) {
			$settings['user_welcome_email_subject'] = $old_settings->user_subject;
		}

		if ( $old_settings->user_body ) {
			$settings['user_welcome_email_body'] = $old_settings->user_body;
		}

		if ( $old_settings->we_attachment_url ) {
			$settings['user_welcome_email_attachment_url'] = $old_settings->we_attachment_url;
		}

		if ( $old_settings->header_reply_to ) {
			$settings['user_welcome_email_reply_to_email'] = $old_settings->header_reply_to;
		}

		if ( $old_settings->header_additional ) {
			$settings['user_welcome_email_additional_headers'] = $old_settings->header_additional;
		}

		// Admin new user notification metabox.

		if ( $old_settings->admin_subject ) {
			$settings['admin_new_user_notif_email_subject'] = $old_settings->admin_subject;
		}

		if ( $old_settings->admin_body ) {
			$settings['admin_new_user_notif_email_body'] = $old_settings->admin_body;
		}

		if ( $old_settings->admin_notify_user_id ) {
			$settings['admin_new_user_notif_email_custom_recipients'] = $old_settings->admin_notify_user_id;
		}

		// Reset password metabox.

		if ( $old_settings->password_reminder_subject ) {
			$settings['reset_password_email_subject'] = $old_settings->password_reminder_subject;
		}

		if ( $old_settings->password_reminder_body ) {
			$settings['reset_password_email_body'] = $old_settings->password_reminder_body;
		}

		update_option( 'weed_settings', $settings );
		delete_option( 'sb_we_settings' );

		// Make sure we don't check again.
		update_option( 'weed_v5_compatibility', 1 );
	}
}
