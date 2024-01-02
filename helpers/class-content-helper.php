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

}
