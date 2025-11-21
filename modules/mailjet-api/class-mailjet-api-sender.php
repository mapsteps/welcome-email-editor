<?php
/**
 * Mailjet API sender.
 *
 * @package Welcome_Email_Editor
 */

namespace Weed\Mailjet_Api;

use Weed\Vars;
use WP_Error;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to send emails via Mailjet API.
 */
class Mailjet_Api_Sender {

	/**
	 * The class instance.
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Mailjet API endpoint.
	 *
	 * @var string
	 */
	const API_ENDPOINT = 'https://api.mailjet.com/v3.1/send';

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
	 * Send email via Mailjet API.
	 *
	 * @param string|array $to          Array or comma-separated list of email addresses to send message.
	 * @param string       $subject     Email subject.
	 * @param string       $message     Message contents.
	 * @param string|array $headers     Optional. Additional headers.
	 * @param string|array $attachments Optional. Paths to files to attach.
	 *
	 * @return bool Whether the email was sent successfully.
	 */
	public function send_email( $to, $subject, $message, $headers = '', $attachments = array() ) {

		$values = Vars::get( 'values' );

		// Validate API credentials.
		if ( empty( $values['mailjet_api_key'] ) || empty( $values['mailjet_secret_key'] ) ) {
			error_log( 'Mailjet API: Missing API credentials' );
			return false;
		}

		// Prepare recipients.
		$recipients = $this->prepare_recipients( $to );
		if ( empty( $recipients ) ) {
			error_log( 'Mailjet API: No valid recipients' );
			return false;
		}

		// Prepare headers and extract from/reply-to.
		$parsed_headers = $this->prepare_headers( $headers );

		// Determine content type.
		$content_type = ! empty( $values['content_type'] ) ? $values['content_type'] : 'html';

		// Build API payload.
		$payload = array(
			'Messages' => array(
				array(
					'From'    => $parsed_headers['from'],
					'To'      => $recipients,
					'Subject' => $subject,
				),
			),
		);

		// Add message content based on content type.
		if ( 'html' === $content_type ) {
			$payload['Messages'][0]['HTMLPart'] = $message;
			// Also add text part by stripping HTML.
			$payload['Messages'][0]['TextPart'] = wp_strip_all_tags( $message );
		} else {
			$payload['Messages'][0]['TextPart'] = $message;
		}

		// Add Reply-To if present.
		if ( ! empty( $parsed_headers['reply_to'] ) ) {
			$payload['Messages'][0]['ReplyTo'] = $parsed_headers['reply_to'];
		}

		// Note: Attachments are not implemented in this version.
		// They would require base64 encoding and proper MIME type detection.

		// Make API request.
		$response = $this->make_api_request( $payload, $values['mailjet_api_key'], $values['mailjet_secret_key'] );

		return $this->handle_api_response( $response );

	}

	/**
	 * Prepare recipients array from various input formats.
	 *
	 * @param string|array $to Email addresses.
	 *
	 * @return array Array of recipient objects.
	 */
	private function prepare_recipients( $to ) {

		$recipients = array();

		// Convert to array if string.
		if ( is_string( $to ) ) {
			$to = explode( ',', $to );
		}

		if ( ! is_array( $to ) ) {
			return $recipients;
		}

		foreach ( $to as $recipient ) {
			$recipient = trim( $recipient );

			// Check if recipient has name format: "Name <email@example.com>".
			if ( preg_match( '/(.*)<(.+)>/', $recipient, $matches ) ) {
				$recipients[] = array(
					'Email' => trim( $matches[2] ),
					'Name'  => trim( $matches[1] ),
				);
			} else {
				$recipients[] = array(
					'Email' => $recipient,
				);
			}
		}

		return $recipients;

	}

	/**
	 * Prepare headers and extract From and Reply-To information.
	 *
	 * @param string|array $headers Email headers.
	 *
	 * @return array Parsed headers with 'from' and 'reply_to' keys.
	 */
	private function prepare_headers( $headers ) {

		$values = Vars::get( 'values' );

		$parsed = array(
			'from'     => array(
				'Email' => get_option( 'admin_email' ),
				'Name'  => get_option( 'blogname' ),
			),
			'reply_to' => null,
		);

		// Use from_email and from_name from settings if available.
		if ( ! empty( $values['from_email'] ) ) {
			$parsed['from']['Email'] = $values['from_email'];
		}

		if ( ! empty( $values['from_name'] ) ) {
			$parsed['from']['Name'] = $values['from_name'];
		}

		// Parse headers if provided.
		if ( empty( $headers ) ) {
			return $parsed;
		}

		// Convert to array if string.
		if ( is_string( $headers ) ) {
			$headers = explode( "\r\n", $headers );
		}

		if ( ! is_array( $headers ) ) {
			return $parsed;
		}

		foreach ( $headers as $header ) {
			$header = trim( $header );

			// Parse From header.
			if ( stripos( $header, 'From:' ) === 0 ) {
				$from_value = trim( substr( $header, 5 ) );

				if ( preg_match( '/(.*)<(.+)>/', $from_value, $matches ) ) {
					$parsed['from'] = array(
						'Email' => trim( $matches[2] ),
						'Name'  => trim( $matches[1] ),
					);
				} else {
					$parsed['from']['Email'] = $from_value;
				}
			}

			// Parse Reply-To header.
			if ( stripos( $header, 'Reply-To:' ) === 0 ) {
				$reply_value = trim( substr( $header, 9 ) );

				if ( preg_match( '/(.*)<(.+)>/', $reply_value, $matches ) ) {
					$parsed['reply_to'] = array(
						'Email' => trim( $matches[2] ),
						'Name'  => trim( $matches[1] ),
					);
				} else {
					$parsed['reply_to'] = array(
						'Email' => $reply_value,
					);
				}
			}
		}

		return $parsed;

	}

	/**
	 * Make API request to Mailjet.
	 *
	 * @param array  $payload    Request payload.
	 * @param string $api_key    Mailjet API key.
	 * @param string $secret_key Mailjet secret key.
	 *
	 * @return array|WP_Error Response from API.
	 */
	private function make_api_request( $payload, $api_key, $secret_key ) {

		$response = wp_remote_post(
			self::API_ENDPOINT,
			array(
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => 'Basic ' . base64_encode( $api_key . ':' . $secret_key ),
				),
				'body'    => wp_json_encode( $payload ),
				'timeout' => 30,
			)
		);

		return $response;

	}

	/**
	 * Handle API response and log errors.
	 *
	 * @param array|WP_Error $response API response.
	 *
	 * @return bool Whether the request was successful.
	 */
	private function handle_api_response( $response ) {

		if ( is_wp_error( $response ) ) {
			error_log( 'Mailjet API Error: ' . $response->get_error_message() );
			return false;
		}

		$response_code = wp_remote_retrieve_response_code( $response );
		$response_body = wp_remote_retrieve_body( $response );

		if ( 200 !== $response_code ) {
			error_log( 'Mailjet API Error: HTTP ' . $response_code . ' - ' . $response_body );
			return false;
		}

		$body_data = json_decode( $response_body, true );

		if ( isset( $body_data['Messages'][0]['Status'] ) && 'success' === $body_data['Messages'][0]['Status'] ) {
			return true;
		}

		error_log( 'Mailjet API: Unexpected response - ' . $response_body );
		return false;

	}

}
