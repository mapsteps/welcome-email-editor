<?php
/**
 * Setter & getter utility
 *
 * @package Ultimate_Quick_View
 */

namespace Weed;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Global setter & getter utility
 *
 * Setting up value:
 * Vars::set($key, $value);
 *
 * Getting value:
 * Vars::get($key);
 */
class Vars {

	/**
	 * Item's container
	 *
	 * @var array
	 */
	private static $vars = [];

	/**
	 * Get value from a given key
	 *
	 * @param string $name The key name.
	 *
	 * @return mixed
	 */
	public static function get( $name ) {
		return isset( self::$vars[ $name ] ) ? self::$vars[ $name ] : '';
	}

	/**
	 * Set key-value pair
	 * - single mode: set the $key as key name, $value as the data
	 * - multiple mode: set the $key as array of key-value pairs, and leave the $value empty
	 *
	 * @param string $name Can be either key name or array of key-value pairs.
	 * @param string $value The data.
	 *
	 * @return void
	 */
	public static function set( $name, $value = '' ) {
		if ( is_array( $name ) ) {
			foreach ( $name as $key => $value ) {
				self::$vars[ $key ] = $value;
			}
		} else {
			self::$vars[ $name ] = $value;
		}
	}
}
