<?php
/**
 * Screen helper.
 *
 * @package Welcome_Email_Editor
 */

namespace Weed\Helpers;

use Weed\Vars;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to setup screen helper.
 */
class Email_Helper {
	/**
	 * Get extra email headers.
	 *
	 * This will be used to set the headers parameter in `wp_mail()`.
	 *
	 * Included as extra headers:
	 * - Reply-To (name & email).
	 * - Additional / custom headers.
	 *
	 * @return array List of Header string.
	 */
	public function get_extra_headers() {

		$values   = Vars::get( 'values' );
		$settings = Vars::get( 'settings' );

		/**
		 * The blogname option is escaped with esc_html() on the way into the database in sanitize_option().
		 * We want to reverse this for the plain text arena of emails.
		 *
		 * This comment was taken from wp-includes/pluggable.php inside `wp_new_user_notification` function.
		 */
		$blogname    = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		$admin_email = get_option( 'admin_email' );

		$headers  = array();
		$reply_to = '';

		if ( ! empty( $values['user_welcome_email_reply_to_email'] ) ) {
			$reply_to = 'Reply-To:';

			// Only check for reply to name, if reply to email exists.
			if ( ! empty( $values['user_welcome_email_reply_to_name'] ) ) {
				$reply_to .= ' ' . $settings['user_welcome_email_reply_to_name'];
			}

			$reply_to .= ' <' . $settings['user_welcome_email_reply_to_email'] . '>';
		}

		if ( ! empty( $reply_to ) ) {
			array_push( $headers, $reply_to );
		}

		$custom_headers = $values['user_welcome_email_additional_headers'];
		$custom_headers = trim( $custom_headers );
		$custom_headers = str_ireplace( "\r\n", "\n", $custom_headers );
		$custom_headers = explode( "\n", $custom_headers );

		$placeholders = array(
			'[site_url]',
			'[blog_name]',
			'[admin_email]',
		);

		$replacements = array(
			network_site_url(),
			$blogname,
			$admin_email,
		);

		foreach ( $custom_headers as $custom_header ) {
			$custom_header = trim( $custom_header );

			if ( ! empty( $custom_header ) ) {
				$custom_header = str_ireplace( $placeholders, $replacements, $custom_header );
				array_push( $headers, $custom_header );
			}
		}

		return $headers;

	}
}
