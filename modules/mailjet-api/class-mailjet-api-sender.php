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

		// Process attachments if provided.
		if ( ! empty( $attachments ) ) {
			$processed_attachments = $this->prepare_attachments( $attachments, $message, $content_type );

			if ( ! empty( $processed_attachments['regular'] ) ) {
				$payload['Messages'][0]['Attachments'] = $processed_attachments['regular'];
			}

			if ( ! empty( $processed_attachments['inline'] ) ) {
				$payload['Messages'][0]['InlinedAttachments'] = $processed_attachments['inline'];
			}
		}

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
	 * Prepare attachments for Mailjet API.
	 *
	 * @param string|array $attachments File paths to attach.
	 * @param string       $message     Email message content.
	 * @param string       $content_type Content type (html or text).
	 *
	 * @return array Array with 'regular' and 'inline' attachment arrays.
	 */
	private function prepare_attachments( $attachments, $message, $content_type ) {

		$prepared = array(
			'regular' => array(),
			'inline'  => array(),
		);

		// Ensure attachments is an array.
		if ( ! is_array( $attachments ) ) {
			$attachments = explode( "\n", str_replace( "\r\n", "\n", $attachments ) );
		}

		if ( empty( $attachments ) ) {
			return $prepared;
		}

		// Extract inline attachments if HTML email.
		if ( 'html' === $content_type ) {
			$extracted          = $this->extract_inline_attachments( $message, $attachments );
			$prepared['inline'] = $extracted['inline'];
			$attachments        = $extracted['regular'];
		}

		// Process regular attachments.
		$total_size     = 0;
		$max_total_size = 14 * 1024 * 1024; // 14 MB (leave buffer for 15MB Mailjet limit).

		foreach ( $attachments as $attachment ) {
			$attachment = trim( $attachment );

			if ( empty( $attachment ) ) {
				continue;
			}

			// Check if file exists.
			if ( ! file_exists( $attachment ) ) {
				error_log( 'Mailjet API: Attachment file not found: ' . $attachment );
				continue;
			}

			// Check if file is readable.
			if ( ! is_readable( $attachment ) ) {
				error_log( 'Mailjet API: Attachment file not readable: ' . $attachment );
				continue;
			}

			// Get file size.
			$file_size = filesize( $attachment );

			// Check total size limit.
			if ( ( $total_size + $file_size ) > $max_total_size ) {
				error_log( 'Mailjet API: Attachment size limit exceeded (14MB), skipping: ' . $attachment );
				continue;
			}

			// Read file content.
			$file_content = file_get_contents( $attachment );
			if ( false === $file_content ) {
				error_log( 'Mailjet API: Failed to read attachment: ' . $attachment );
				continue;
			}

			// Get MIME type and filename.
			$mime_type = $this->get_mime_type( $attachment );
			$filename  = basename( $attachment );

			// Add to regular attachments array.
			$prepared['regular'][] = array(
				'ContentType'   => $mime_type,
				'Filename'      => $filename,
				'Base64Content' => base64_encode( $file_content ),
			);

			$total_size += $file_size;
		}

		return $prepared;

	}

	/**
	 * Extract inline attachments from HTML message.
	 *
	 * @param string $html_message HTML message content.
	 * @param array  $attachments  Array of attachment file paths.
	 *
	 * @return array Array with 'inline' and 'regular' keys.
	 */
	private function extract_inline_attachments( $html_message, $attachments ) {

		$result = array(
			'inline'  => array(),
			'regular' => array(),
		);

		// Find all cid: references in HTML.
		preg_match_all( '/src=["\']cid:([^"\']+)["\']/', $html_message, $matches );

		if ( empty( $matches[1] ) ) {
			// No inline attachments found, all are regular.
			$result['regular'] = $attachments;
			return $result;
		}

		$cid_references = $matches[1];

		// Process each attachment.
		foreach ( $attachments as $attachment ) {
			$attachment = trim( $attachment );

			if ( empty( $attachment ) || ! file_exists( $attachment ) ) {
				continue;
			}

			$filename = basename( $attachment );
			$file_ext = pathinfo( $filename, PATHINFO_EXTENSION );
			$cid      = pathinfo( $filename, PATHINFO_FILENAME );

			// Check if this attachment is referenced as inline.
			$is_inline = false;
			foreach ( $cid_references as $cid_ref ) {
				// Match by filename without extension or full filename.
				if ( $cid_ref === $cid || $cid_ref === $filename ) {
					$is_inline = true;
					break;
				}
			}

			if ( $is_inline ) {
				// Read file content.
				$file_content = file_get_contents( $attachment );
				if ( false === $file_content ) {
					error_log( 'Mailjet API: Failed to read inline attachment: ' . $attachment );
					continue;
				}

				// Add to inline attachments.
				$result['inline'][] = array(
					'ContentType'   => $this->get_mime_type( $attachment ),
					'Filename'      => $filename,
					'ContentID'     => $cid,
					'Base64Content' => base64_encode( $file_content ),
				);
			} else {
				// Regular attachment.
				$result['regular'][] = $attachment;
			}
		}

		return $result;

	}

	/**
	 * Get MIME type for a file.
	 *
	 * @param string $file_path Path to file.
	 *
	 * @return string MIME type.
	 */
	private function get_mime_type( $file_path ) {

		// Use WordPress function if available.
		if ( function_exists( 'wp_check_filetype' ) ) {
			$file_info = wp_check_filetype( $file_path );
			if ( ! empty( $file_info['type'] ) ) {
				return $file_info['type'];
			}
		}

		// Fallback to PHP's mime_content_type.
		if ( function_exists( 'mime_content_type' ) ) {
			$mime = mime_content_type( $file_path );
			if ( $mime ) {
				return $mime;
			}
		}

		// Default fallback.
		return 'application/octet-stream';

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
