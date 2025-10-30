<?php
/**
 * Content helper.
 *
 * @package Welcome_Email_Editor
 */

namespace Weed\Helpers;

use wpdb;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to setup screen helper.
 */
class Content_Helper {

	/**
	 * Get BuddyPress custom profile fields.
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return array
	 */
	public function get_bp_user_custom_fields( $user_id ) {

		if ( ! defined( 'BP_PLUGIN_URL' ) ) {
			return array();
		}

		/**
		 * Global wpdb instance.
		 *
		 * @global wpdb $wpdb
		 */
		global $wpdb;

		// TODO: Use BuddyPress function if it exists instead of directly touching wpdb.
		$sql = 'SELECT f.name, d.value
				FROM
					' . $wpdb->prefix . 'bp_xprofile_fields f
					JOIN ' . $wpdb->prefix . 'bp_xprofile_data d ON (d.field_id = f.id)
				WHERE d.user_id = ' . $user_id;

		$array = $wpdb->get_results( $sql );

		$assoc_array = array();

		foreach ( $array as $key => $value ) {
			$assoc_array[ $value->name ] = $value->value;
		}

		return apply_filters( 'weed_bp_custom_fields', $assoc_array );

	}

	/**
	 * Get user's custom fields.
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return array
	 */
	public function get_user_custom_fields( $user_id ) {

		/**
		 * Global wpdb instance.
		 *
		 * @global wpdb $wpdb
		 */
		global $wpdb;

		// TODO: Use get_user_meta instead of directly touching wpdb.
		$sql = 'SELECT meta_key, meta_value
				FROM ' . $wpdb->usermeta . '
				WHERE user_ID = ' . $user_id;

		$meta_items = $wpdb->get_results( $sql );

		$custom_fields = array();

		if ( $meta_items ) {
			foreach ( $meta_items as $meta_item ) {
				$custom_fields[ $meta_item->meta_key ] = $meta_item->meta_value;
			}
		}

		return $custom_fields;

	}

	/**
	 * Replace placeholders with values.
	 *
	 * @param array  $pairs Array of find and replace pairs.
	 * @param string $content Content to replace.
	 *
	 * @return string Replaced content.
	 */
	public function replace_content( $pairs, $content ) {

		if ( empty( $pairs ) ) {
			return $content;
		}

		$find    = array();
		$replace = array();

		foreach ( $pairs as $key => $value ) {
			$find[]    = $key;
			$replace[] = $value;
		}

		return str_ireplace( $find, $replace, $content );

	}

	/**
	 * Replace conditional placeholders.
	 *
	 * @param string $content Content to replace.
	 *
	 * @return string Replaced content.
	 */
	public function replace_conditional_placeholders( $content ) {

		// Collect substrings between [not_logged_in][/not_logged_in] placeholders.
		preg_match_all( '/\[not_logged_in](.*?)\[\/not_logged_in]/s', $content, $not_logged_in_matches );

		// Collect substrings between [logged_in][/logged_in] placeholders.
		preg_match_all( '/\[logged_in](.*?)\[\/logged_in]/s', $content, $logged_in_matches );

		// Parse the content for [not_logged_in][/not_logged_in] placeholders.
		if ( ! empty( $not_logged_in_matches[0] ) ) {
			foreach ( $not_logged_in_matches[0] as $key => $value ) {
				$not_logged_in_content = $not_logged_in_matches[1][ $key ];

				if ( ! is_user_logged_in() ) {
					$content = str_ireplace( $value, $not_logged_in_content, $content );
				} else {
					$content = str_ireplace( $value, '', $content );
				}
			}
		}

		// Parse the content for [logged_in][/logged_in] placeholders.
		if ( ! empty( $logged_in_matches[0] ) ) {
			foreach ( $logged_in_matches[0] as $key => $value ) {
				$logged_in_content = $logged_in_matches[1][ $key ];

				if ( is_user_logged_in() ) {
					$content = str_ireplace( $value, $logged_in_content, $content );
				} else {
					$content = str_ireplace( $value, '', $content );
				}
			}
		}

		return $content;

	}

	/**
	 * Get default settings.
	 *
	 * @return array Default settings.
	 */
	public static function default_settings() {

		/* translators: [user_login] will be replaced with the user's login name. */
		$user_welcome_email_body  = __( 'Username:', 'welcome-email-editor' ) . ' [user_login]' . "\r\n\r\n";
		$user_welcome_email_body .= __( 'To set your password, visit the following address:', 'welcome-email-editor' ) . "\r\n\r\n";
		$user_welcome_email_body .= '[reset_pass_url]' . "\r\n\r\n";
		$user_welcome_email_body .= '[login_url]' . "\r\n";

		/* translators: [blog_name] will be replaced with the site title. */
		$admin_welcome_email_body = __( 'New user registration on your site', 'welcome-email-editor' ) . " [blog_name]\r\n\r\n";
		/* translators: [user_login] will be replaced with the user's login name. */
		$admin_welcome_email_body .= __( 'Username:', 'welcome-email-editor' ) . " [user_login]\r\n\r\n";
		/* translators: [user_email] will be replaced with the user's email address. */
		$admin_welcome_email_body .= __( 'Email:', 'welcome-email-editor' ) . " [user_email]\r\n";

		$reset_password_message = __( 'Someone has requested a password reset for the following account:', 'welcome-email-editor' ) . "\r\n\r\n";
		/* translators: [blog_name] will be replaced with the site name. */
		$reset_password_message .= __( 'Site Name:', 'welcome-email-editor' ) . " [blog_name]\r\n\r\n";
		/* translators: [user_login] will be replaced with the user's login name. */
		$reset_password_message .= __( 'Username:', 'welcome-email-editor' ) . " [user_login]\r\n\r\n";
		$reset_password_message .= __( 'If this was a mistake, ignore this email and nothing will happen.', 'welcome-email-editor' ) . "\r\n\r\n";
		$reset_password_message .= __( 'To reset your password, visit the following address:', 'welcome-email-editor' ) . "\r\n\r\n";
		$reset_password_message .= '[reset_pass_url]' . "\r\n\r\n";
		/* translators: [user_ip] will be replaced with the user's IP address. */
		$reset_password_message .= __( '[not_logged_in]This password reset request originated from the IP address [user_ip].[/not_logged_in]', 'welcome-email-editor' ) . "\r\n";

		return array(
			// General settings.
			'from_email'                                   => '',
			'force_from_email'                             => false,
			'from_name'                                    => '',
			'force_from_name'                              => false,
			'content_type'                                 => 'text',

			// Test SMTP settings.
			'test_smtp_recipient_email'                    => get_bloginfo( 'admin_email' ),

			// SMTP settings.
			'smtp_host'                                    => '',
			'smtp_port'                                    => 25,
			'smtp_encryption'                              => '',
			'smtp_username'                                => '',
			'smtp_password'                                => '',

			// Welcome email settings - for user.
			/* translators: %s will be replaced with the site name. */
			'user_welcome_email_subject'                   => sprintf( __( '%s Login Details', 'welcome-email-editor' ), '[[blog_name]]' ),
			'user_welcome_email_body'                      => $user_welcome_email_body,
			'user_welcome_email_attachment_url'            => '',
			'user_welcome_email_reply_to_email'            => '',
			'user_welcome_email_reply_to_name'             => '',
			'user_welcome_email_additional_headers'        => '',

			// Welcome email settings - for admin.
			/* translators: %s will be replaced with the site name. */
			'admin_new_user_notif_email_subject'           => sprintf( __( '%s New User Registration', 'welcome-email-editor' ), '[[blog_name]]' ),
			'admin_new_user_notif_email_body'              => $admin_welcome_email_body,
			'admin_new_user_notif_email_custom_recipients' => '',

			// Reset password email settings.
			/* translators: %s will be replaced with the site name. */
			'reset_password_email_subject'                 => sprintf( __( '%s Password Reset', 'welcome-email-editor' ), '[[blog_name]]' ),
			'reset_password_email_body'                    => $reset_password_message,
		);

	}

	/**
	 * Parse settings from database with default values.
	 *
	 * @param array $settings The settings array.
	 *
	 * @return array Parsed settings.
	 */
	public function parse_settings( $settings = array() ) {

		$settings = ! is_array( $settings ) || empty( $settings ) ? get_option( 'weed_settings', array() ) : $settings;
		$settings = ! is_array( $settings ) ? [] : $settings;

		if ( ! empty( $settings['smtp_port'] ) && is_string( $settings['smtp_port'] ) ) {
			$settings['smtp_port'] = absint( $settings['smtp_port'] );
		}

		return wp_parse_args( $settings, self::default_settings() );

	}

}
