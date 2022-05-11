<?php
/**
 * Replace wp_new_user_notification pluggable function.
 *
 * @package Welcome_Email_Editor
 */

use Weed\Vars;
use Weed\Helpers\Content_Helper;
use Weed\Helpers\Email_Helper;
use Weed\Settings\Settings_Output;

if ( ! function_exists( 'wp_new_user_notification' ) ) {

	/**
	 * Email login credentials to a newly-registered user.
	 *
	 * A new user registration notification is also sent to admin email.
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_new_user_notification/
	 * @see wp-includes/pluggable.php
	 *
	 * @since 2.0.0
	 * @since 4.3.0 The `$plaintext_pass` parameter was changed to `$notify`.
	 * @since 4.3.1 The `$plaintext_pass` parameter was deprecated. `$notify` added as a third parameter.
	 * @since 4.6.0 The `$notify` parameter accepts 'user' for sending notification only to the user created.
	 *
	 * @param int    $user_id    User ID.
	 * @param null   $deprecated Not used (argument deprecated).
	 * @param string $notify     Optional. Type of notification that should happen. Accepts 'admin' or an empty
	 *                           string (admin only), 'user', or 'both' (admin and user). Default empty.
	 */
	function wp_new_user_notification( $user_id, $deprecated = null, $notify = '' ) {

		if ( null !== $deprecated ) {
			_deprecated_argument( __FUNCTION__, '4.3.1' );
		}

		// Accepts only 'user', 'admin' , 'both' or default '' as $notify.
		if ( ! in_array( $notify, array( 'user', 'admin', 'both', '' ), true ) ) {
			return;
		}

		$user = get_userdata( $user_id );

		$values = Vars::get( 'values' );

		$content_helper = new Content_Helper();
		$email_helper   = new Email_Helper();
		$module_output  = Settings_Output::get_instance();

		$module_output->set_email_headers();

		// The blogname option is escaped with esc_html() on the way into the database in sanitize_option().
		// We want to reverse this for the plain text arena of emails.
		$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

		$current_date  = date_i18n( get_option( 'date_format' ) );
		$current_time  = date_i18n( get_option( 'time_format' ) );
		$admin_email   = get_option( 'admin_email' );
		$custom_fields = $content_helper->get_user_custom_fields( $user_id );

		$headers = $email_helper->get_extra_headers();

		if ( 'user' !== $notify ) {
			$switched_locale = switch_to_locale( get_locale() );

			$default_admin_subject = __( '[%s] New User Registration' );

			$admin_subject_placeholders = array(
				'[blog_name]',
				'[site_url]',
				'[first_name]',
				'[last_name]',
				'[user_email]',
				'[user_login]',
				'[user_id]',
				'[date]',
				'[time]',
			);

			$admin_subject_values = array(
				$blogname,
				network_site_url(),
				$user->first_name,
				$user->last_name,
				$user->user_email,
				$user->user_login,
				$user->ID,
				$current_date,
				$current_time,
			);

			$admin_subject = $values['admin_new_user_notif_email_subject'];
			$admin_subject = $admin_subject ? $admin_subject : $default_admin_subject;
			$admin_subject = str_ireplace( $admin_subject_placeholders, $admin_subject_values, $admin_subject );
			$admin_subject = apply_filters( 'weed_user_welcome_email_subject', $admin_subject );

			$admin_body_placeholders = array(
				'[blog_name]',
				'[site_url]',
				'[first_name]',
				'[last_name]',
				'[user_email]',
				'[user_login]',
				'[user_id]',
				'[date]',
				'[time]',
				'[admin_email]',
				'[login_url]',
				'[reset_pass_url]',
				'[plaintext_password]',
				'[user_password]',
				'[custom_fields]',
				'[bp_custom_fields]',
				'[post_data]',
			);

			$admin_body_values = array(
				$blogname,
				network_site_url(),
				$user->first_name,
				$user->last_name,
				$user->user_email,
				$user->user_login,
				$user->ID,
				$current_date,
				$current_time,
				$admin_email,
				wp_login_url(),
				wp_login_url(),
				'*****',
				'*****',
				'<pre>' . print_r( $custom_fields, true ) . '</pre>', // ! Not recommended.
				'<pre>' . print_r( $_REQUEST, true ) . '</pre>', // ! Not recommended.
			);

			/* translators: %s: Site title. */
			$default_admin_body = sprintf( __( 'New user registration on your site %s:' ), $blogname ) . "\r\n\r\n";
			/* translators: %s: User login. */
			$default_admin_body .= sprintf( __( 'Username: %s' ), $user->user_login ) . "\r\n\r\n";
			/* translators: %s: User email address. */
			$default_admin_body .= sprintf( __( 'Email: %s' ), $user->user_email ) . "\r\n";

			$admin_body = $values['admin_new_user_notif_email_body'];
			$admin_body = $admin_body ? $admin_body : $default_admin_body;
			$admin_body = str_ireplace( $admin_body_placeholders, $admin_body_values, $admin_body );

			if ( stripos( $admin_body, '[bp_custom_fields]' ) ) {
				if ( defined( 'BP_PLUGIN_URL' ) ) {
					$admin_body = str_replace(
						'[bp_custom_fields]',
						'<pre>' . print_r( $content_helper->get_bp_user_custom_fields( $user_id ), true ) . '</pre>',
						$admin_body
					);
				}
			}

			$admin_body = apply_filters( 'weed_user_welcome_email_body', $admin_body );

			$wp_new_user_notification_email_admin = array(
				'to'      => get_option( 'admin_email' ),
				/* translators: New user registration notification email subject. %s: Site title. */
				'subject' => $admin_subject,
				'message' => $admin_body,
				'headers' => ! empty( $headers ) ? $headers : '',
			);

			/**
			 * Filters the contents of the new user notification email sent to the site admin.
			 *
			 * @param array   $wp_new_user_notification_email_admin {
			 *     Used to build wp_mail().
			 *
			 *     @type string $to      The intended recipient - site admin email address.
			 *     @type string $subject The subject of the email.
			 *     @type string $message The body of the email.
			 *     @type string $headers The headers of the email.
			 * }
			 * @param WP_User $user     User object for new user.
			 * @param string  $blogname The site title.
			 */
			$wp_new_user_notification_email_admin = apply_filters( 'wp_new_user_notification_email_admin', $wp_new_user_notification_email_admin, $user, $blogname );

			$custom_recipient_emails = array();

			$custom_recipients = $values['admin_new_user_notif_email_custom_recipients'];
			$custom_recipients = trim( $custom_recipients );
			$custom_recipients = rtrim( $custom_recipients, ',' ); // Make sure there's no trailing comma to prevent double commas.

			if ( ! empty( $custom_recipients ) ) {
				$custom_recipients = $custom_recipients . ','; // Let's add a trailing comma for the explode.
				$custom_recipients = explode( ',', $custom_recipients );

				foreach ( $custom_recipients as $custom_recipient ) {
					$custom_recipient = trim( $custom_recipient );

					if ( ! empty( $custom_recipient ) ) {
						// Let's keep this checking separately, think about future possibility if would add custom recipient(s) as email string.
						if ( is_numeric( $custom_recipient ) ) {
							$custom_recipient = absint( $custom_recipient );

							$custom_recipient_user = get_userdata( $custom_recipient );

							if ( $custom_recipient_user ) {
								// Prevent the email from being sent twice to admin_email.
								if ( $custom_recipient_user->user_email !== $admin_email ) {
									array_push( $custom_recipient_emails, $custom_recipient_user->user_email );
								}
							}
						}
					}
				}
			}

			$testing_recipient = apply_filters( 'weed_test_email_recipient', '' );

			wp_mail(
				( ! empty( $testing_recipient ) ? $testing_recipient : $wp_new_user_notification_email_admin['to'] ),
				wp_specialchars_decode( sprintf( $wp_new_user_notification_email_admin['subject'], $blogname ) ),
				$wp_new_user_notification_email_admin['message'],
				$wp_new_user_notification_email_admin['headers']
			);

			if ( empty( $testing_recipient ) ) {
				foreach ( $custom_recipient_emails as $custom_recipient_email ) {
					wp_mail(
						$custom_recipient_email,
						wp_specialchars_decode( sprintf( $wp_new_user_notification_email_admin['subject'], $blogname ) ),
						$wp_new_user_notification_email_admin['message'],
						$wp_new_user_notification_email_admin['headers']
					);
				}
			}

			if ( $switched_locale ) {
				restore_previous_locale();
			}
		}

		// `$deprecated` was pre-4.3 `$plaintext_pass`. An empty `$plaintext_pass` didn't sent a user notification.
		if ( 'admin' === $notify || ( empty( $deprecated ) && empty( $notify ) ) ) {
			return;
		}

		$key = get_password_reset_key( $user );

		if ( is_wp_error( $key ) ) {
			return;
		}

		$switched_locale = switch_to_locale( get_user_locale( $user ) );

		$reset_pass_url = network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user->user_login ), 'login' );

		$default_user_subject = __( '[%s] Login Details' );

		$user_subject_placeholders = array(
			'[blog_name]',
			'[site_url]',
			'[first_name]',
			'[last_name]',
			'[user_email]',
			'[user_login]',
			'[user_id]',
			'[date]',
			'[time]',
		);

		$user_subject_values = array(
			$blogname,
			network_site_url(),
			$user->first_name,
			$user->last_name,
			$user->user_email,
			$user->user_login,
			$user->ID,
			$current_date,
			$current_time,
		);

		$user_subject = $values['user_welcome_email_subject'];
		$user_subject = $user_subject ? $user_subject : $default_user_subject;
		$user_subject = str_ireplace( $user_subject_placeholders, $user_subject_values, $user_subject );
		$user_subject = apply_filters( 'weed_user_welcome_email_subject', $user_subject );

		/* translators: %s: User login. */
		$default_user_body  = sprintf( __( 'Username: %s' ), $user->user_login ) . "\r\n\r\n";
		$default_user_body .= __( 'To set your password, visit the following address:' ) . "\r\n\r\n";
		$default_user_body .= $reset_pass_url . "\r\n\r\n";
		$default_user_body .= wp_login_url() . "\r\n";

		$user_body_placeholders = array(
			'[blog_name]',
			'[site_url]',
			'[first_name]',
			'[last_name]',
			'[user_email]',
			'[user_login]',
			'[user_id]',
			'[date]',
			'[time]',
			'[admin_email]',
			'[login_url]',
			'[reset_pass_url]',
			'[reset_pass_link]',
			'[plaintext_password]',
			'[user_password]',
		);

		$user_body_values = array(
			$blogname,
			network_site_url(),
			$user->first_name,
			$user->last_name,
			$user->user_email,
			$user->user_login,
			$user->ID,
			$current_date,
			$current_time,
			$admin_email,
			wp_login_url(),
			$reset_pass_url,
			'<a href="' . $reset_pass_url . '" target="_blank">' . __( 'Click to set', 'welcome-email-editor' ) . '</a>',
			'*****',
			'*****',
		);

		$user_body = $values['user_welcome_email_body'];
		$user_body = $user_body ? $user_body : $default_user_body;
		$user_body = str_ireplace( $user_body_placeholders, $user_body_values, $user_body );

		$wp_new_user_notification_email = array(
			'to'      => $user->user_email,
			/* translators: Login details notification email subject. %s: Site title. */
			'subject' => $user_subject,
			'message' => $user_body,
			'headers' => ! empty( $headers ) ? $headers : '',
		);

		/**
		 * Filters the contents of the new user notification email sent to the new user.
		 *
		 * This comment was taken from wp-includes/pluggable.php inside wp_new_user_notification() function.
		 *
		 * @param array   $wp_new_user_notification_email {
		 *     Used to build wp_mail().
		 *
		 *     @type string $to      The intended recipient - New user email address.
		 *     @type string $subject The subject of the email.
		 *     @type string $message The body of the email.
		 *     @type string $headers The headers of the email.
		 * }
		 * @param WP_User $user     User object for new user.
		 * @param string  $blogname The site title.
		 */
		$wp_new_user_notification_email = apply_filters( 'wp_new_user_notification_email', $wp_new_user_notification_email, $user, $blogname );

		$attachment = $values['user_welcome_email_attachment_url'];
		$attachment = trim( $attachment );
		$attachment = empty( $attachment ) ? '' : $attachment;

		if ( ! empty( $attachment ) ) {
			$attachment = str_replace( trailingslashit( site_url() ), trailingslashit( $_SERVER['DOCUMENT_ROOT'] ), $attachment );
		}

		wp_mail(
			$wp_new_user_notification_email['to'],
			wp_specialchars_decode( sprintf( $wp_new_user_notification_email['subject'], $blogname ) ),
			$wp_new_user_notification_email['message'],
			$wp_new_user_notification_email['headers'],
			$attachment
		);

		if ( $switched_locale ) {
			restore_previous_locale();
		}

		$module_output->reset_email_headers();
	}
}
