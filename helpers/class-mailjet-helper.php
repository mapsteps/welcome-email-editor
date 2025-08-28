<?php
/**
 * Mailjet email helper class.
 *
 * @package Welcome_Email_Editor
 */

namespace Weed\Helpers;

use Mailjet\Resources;
use Mailjet\Client;
use Weed\Vars;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to handle Mailjet API communication.
 */
class Mailjet_Helper {

	/**
	 * Mailjet's forbidden headers
	 *
	 * @link https://dev.mailjet.com/guides/#add-email-headers
	 *
	 * @var array
	 */
	private $forbidden_headers = [
		'From',
		'To',
		'Cc',
		'Bcc',
		'Subject',
		'Reply-To',
		'Return-Path',
		'Delivered-To',
		'Received',
		'Date',
		'Message-ID',
		'References',
		'In-Reply-To',
		'Auto-Submitted',
		'Auto-Generated',
		'List-Unsubscribe',
		'List-Subscribe',
		'List-Owner',
		'List-Id',
		'List-Archive',
		'List-Post',
		'List-Help',
		'Mailing-List',
		'Originator',
		'X-Mailer',
		'X-Originating-IP',
		'X-Spam-Status',
		'X-Spam-Score',
		'X-Spam-Flag',
		'Authentication-Results',
		'Received-SPF',
		'X-Virus-Scanned',
		'X-Spam-Checker-Version',
		'X-Spam-Level',
		'X-Spam-Tests',
		'X-Spam-ASN',
		'X-Spam-Country',
		'X-Spam-Language',
		'X-Spam-Charset',
		'X-Spam-Report',
		'X-Spam-Prev-Subject',
		'X-Spam-Flag-2',
		'X-Spam-Status-2',
		'X-Spam-Level-2',
		'X-Spam-Score-2',
		'X-Spam-Report-2',
		'X-Spam-Checker-Version-2',
		'X-Spam-Tests-2',
		'X-Spam-ASN-2',
		'X-Spam-Country-2',
		'X-Spam-Language-2',
		'X-Spam-Charset-2',
		'X-Spam-Prev-Subject-2',
		'X-Spam-Flag-3',
		'X-Spam-Status-3',
		'X-Spam-Level-3',
		'X-Spam-Score-3',
		'X-Spam-Report-3',
		'X-Spam-Checker-Version-3',
		'X-Spam-Tests-3',
		'X-Spam-ASN-3',
		'X-Spam-Country-3',
		'X-Spam-Language-3',
		'X-Spam-Charset-3',
		'X-Spam-Prev-Subject-3',
	];

	/**
	 * Send email via Mailjet
	 *
	 * @param string|array $recipients  Recipient email or array of recipient emails.
	 * @param string       $subject     Message subject.
	 * @param string       $body        Message body.
	 * @param string|array $headers     Additional headers.
	 * @param array        $attachments Files to attach.
	 *
	 * @return bool Whether the email contents were sent successfully or not.
	 */
	public function send( $recipients, $subject = '', $body = '', $headers = '', $attachments = [] ) {
		if ( ! $recipients ) {
			return false;
		}

		$values = Vars::get( 'values' );

		// Check if Mailjet is configured.
		if ( empty( $values['mailjet_public_key'] ) || empty( $values['mailjet_secret_key'] ) ) {
			return false;
		}

		$recipients = $this->handle_recipients( $recipients );

		$sender = [
			'Email' => ! empty( $values['mailjet_sender_email'] ) ? $values['mailjet_sender_email'] : get_option( 'admin_email' ),
			'Name'  => ! empty( $values['mailjet_sender_name'] ) ? $values['mailjet_sender_name'] : get_option( 'blogname' ),
		];

		$mailjet = new Client( $values['mailjet_public_key'], $values['mailjet_secret_key'], true, [ 'version' => 'v3.1' ] );

		$msg_item = [
			'From'    => $sender,
			'To'      => $recipients,
			'Subject' => $subject,
		];

		// Check if template is configured.
		$use_template = false;
		$template_id  = 0;

		if ( ! empty( $values['mailjet_template_id'] ) ) {
			$use_template = true;
			$template_id  = absint( $values['mailjet_template_id'] );
		}

		if ( $use_template ) {
			$msg_item['TemplateLanguage'] = true;
			$msg_item['TemplateID']       = $template_id;
			$msg_item['Variables']        = [
				'subject' => $subject,
				'body'    => $body,
			];
		} else {
			if ( 'text/html' === apply_filters( 'wp_mail_content_type', 'text/plain' ) ) {
				$msg_item['HTMLPart'] = $body;
			} else {
				$msg_item['TextPart'] = $body;
			}
		}

		if ( ! empty( $headers ) ) {
			$msg_item['Headers'] = $this->handle_headers( $headers );
		}

		if ( ! empty( $attachments ) ) {
			$msg_item['Attachments'] = $this->handle_attachments( $attachments );
		}

		$mail_prop = [
			'Messages' => [
				$msg_item,
			],
		];

		$response = $mailjet->post(
			Resources::$Email, // phpcs:ignore -- this is Mailjet format
			[ 'body' => $mail_prop ]
		);

		$is_success = false;

		if ( method_exists( $response, 'success' ) ) {
			$is_success = $response->success();

			if ( ! $is_success ) {
				// If error, log the data.
				error_log( 'Mailjet Error: ' . print_r( $response->getData(), true ) );
				
				// Store error for display in admin.
				Vars::set( 'wp_mail_failed', 'Mailjet API Error: ' . print_r( $response->getData(), true ) );
			}
		}

		return $is_success;
	}

	/**
	 * Convert WordPress's formatted recipients to Mailjet's format
	 *
	 * @param array $recipients Array of recipients.
	 *
	 * @return array $mailjet_recipients Recipients in Mailjet format.
	 */
	private function handle_recipients( $recipients ) {
		// Mailjet formatted recipients.
		$mailjet_recipients = [];

		// If $recipients is a string.
		if ( is_string( $recipients ) ) {
			$string_with_commas = explode( ',', $recipients );

			// If $recipients is a single email.
			if ( count( $string_with_commas ) <= 1 ) {
				$recipient = trim( $recipients, ',' );
				array_push( $mailjet_recipients, [ 'Email' => $recipients ] );
			} else {
				// If $recipients is a comma separated emails.
				foreach ( $string_with_commas as $recipient_str ) {
					array_push( $mailjet_recipients, [ 'Email' => trim( $recipient_str ) ] );
				}
			}

			return $mailjet_recipients;
		}

		// Only string or array is supported.
		if ( ! is_array( $recipients ) ) {
			return $mailjet_recipients;
		}

		foreach ( $recipients as $recipient ) {
			// If $recipients is array of email string.
			if ( is_string( $recipient ) ) {
				array_push( $mailjet_recipients, [ 'Email' => $recipient ] );
			} elseif ( is_array( $recipient ) ) {
				// If $recipients is array of associative array.
				$recipient_props = [];

				foreach ( $recipient as $key => $val ) {
					if ( array_key_exists( 'Email', $recipient ) ) {
						$recipient_props[ $key ] = $val;
					}
				}

				array_push( $mailjet_recipients, $recipient_props );
			}
		}

		return $mailjet_recipients;
	}

	/**
	 * Convert WordPress's formatted attachments to Mailjet's format
	 *
	 * @param array $attachments Array of file path.
	 *
	 * @return array $mailjet_attachments Attachments in Mailjet format.
	 */
	private function handle_attachments( $attachments ) {
		$mailjet_attachments = [];

		if ( ! is_array( $attachments ) ) {
			return $mailjet_attachments;
		}

		foreach ( $attachments as $file_path ) {
			$file_contents = file_get_contents( $file_path );

			// Make sure the file exists.
			if ( false !== $file_contents ) {
				$paths     = explode( '/', $file_path );
				$file_name = end( $paths );

				array_push(
					$mailjet_attachments,
					[
						'ContentType'   => 'application/octet-stream',
						'Filename'      => $file_name,
						'Base64Content' => base64_encode( $file_contents ),
					]
				);
			}
		}

		return $mailjet_attachments;
	}

	/**
	 * Convert WordPress's formatted headers to Mailjet's format
	 *
	 * @param array $headers Array of headers.
	 *
	 * @return array $mailjet_headers Headers in Mailjet format.
	 */
	private function handle_headers( $headers ) {
		// Mailjet formatted headers.
		$mailjet_headers = [];

		if ( empty( $headers ) ) {
			return $mailjet_headers;
		}

		if ( is_array( $headers ) ) {
			foreach ( $headers as $header ) {
				$header       = trim( $header );
				$explode      = explode( ':', $header );
				$header_key   = isset( $explode[0] ) && ! empty( $explode[0] ) ? trim( $explode[0] ) : '';
				$header_value = isset( $explode[1] ) && ! empty( $explode[1] ) ? trim( $explode[1] ) : '';

				if ( ! in_array( $header_key, $this->forbidden_headers, true ) && ! empty( $header_key ) && ! empty( $header_value ) ) {
					$mailjet_headers[ $header_key ] = $header_value;
				}
			}
		} else {
			// Explode the headers out.
			$tempheaders = explode( "\n", str_replace( "\r\n", "\n", $headers ) );

			if ( ! empty( $tempheaders ) ) {
				// Iterate through the raw headers.
				foreach ( (array) $tempheaders as $header ) {
					if ( strpos( $header, ':' ) === false ) {
						continue;
					}

					// Explode them out.
					list( $header_key, $header_value ) = explode( ':', trim( $header ), 2 );

					// Cleanup crew.
					$header_key   = trim( $header_key );
					$header_value = trim( $header_value );

					if ( ! in_array( $header_key, $this->forbidden_headers, true ) && ! empty( $header_key ) && ! empty( $header_value ) ) {
						$mailjet_headers[ $header_key ] = $header_value;
					}
				}
			}
		}

		return $mailjet_headers;
	}

	/**
	 * Get Mailjet templates
	 *
	 * @return array
	 */
	public function get_mailjet_templates() {
		$values = Vars::get( 'values' );

		if ( empty( $values['mailjet_public_key'] ) || empty( $values['mailjet_secret_key'] ) ) {
			return [];
		}

		$api_url = 'https://api.mailjet.com/v3/REST/template?EditMode=tool&Limit=100&OwnerType=user';

		$response = wp_remote_get(
			$api_url,
			[
				'headers' => [
					'Authorization' => 'Basic ' . base64_encode( $values['mailjet_public_key'] . ':' . $values['mailjet_secret_key'] ),
				],
			]
		);

		$templates = [];

		if ( is_wp_error( $response ) ) {
			return $templates;
		}

		$json_list  = $response['body'];
		$array_list = json_decode( $json_list, true );

		if ( ! isset( $array_list['Count'] ) || $array_list['Count'] < 1 ) {
			return $templates;
		}

		foreach ( $array_list['Data'] as $template ) {
			if ( in_array( 'transactional', $template['Purposes'], true ) ) {
				array_push(
					$templates,
					[
						'id'          => $template['ID'],
						'name'        => $template['Name'],
						'description' => $template['Description'],
					]
				);
			}
		}

		return $templates;
	}
}
