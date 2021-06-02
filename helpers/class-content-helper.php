<?php
/**
 * Content helper.
 *
 * @package Welcome_Email_Editor
 */

namespace Weed\Helpers;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to setup screen helper.
 */
class Content_Helper {

	/**
	 * Get BuddyPress custom profile fields.
	 *
	 * @param int $user_id The user ID.
	 * @return array
	 */
	public function get_bp_user_custom_fields( $user_id ) {

		if ( ! defined( 'BP_PLUGIN_URL' ) ) {
			return array();
		}

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

		$assoc_array = apply_filters( 'weed_bp_custom_fields', $assoc_array );

		return $assoc_array;

	}

	/**
	 * Get user's custom fields.
	 *
	 * @param int $user_id The user ID.
	 * @return array
	 */
	public function get_user_custom_fields( $user_id ) {

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

}
