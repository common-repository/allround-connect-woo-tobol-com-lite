<?php
/**
 * Check_Type trait File
 *
 * This class contains methods that check type of inside arrays, sanitize and return it.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */

namespace ACBOL_Lite\Includes\Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check_Type trait File
 *
 * This class contains methods that check type of inside arrays.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */
trait Check_Type {

	/**
	 * Method to check type of each item in an array and return them
	 *
	 *
	 * @access  public
	 *
	 * @param array  $items Passed array to check type of each items inside it
	 * @param string $type  type to check
	 */
	public function check_array_by_parent_type( array $items, $type ): array {
		$result['valid']   = [];
		$result['invalid'] = [];
		foreach ( $items as $item ) {
			if ( get_parent_class( $item ) == $type ) {
				$result['valid'][] = $item;
			} else {
				$result['invalid'][] = $item;
			}

		}

		return $result;

	}

	/**
	 * Method to check type of each item in an array and return them for associative arrays
	 *
	 *
	 * @access  public
	 *
	 * @param array  $items Passed array to check type of each items inside it
	 * @param string $type  type to check
	 */
	public function check_array_by_parent_type_assoc( array $items, $type ): array {
		$result['valid']   = [];
		$result['invalid'] = [];
		foreach ( $items as $key => $item ) {
			if ( get_parent_class( $item ) == $type ) {
				$result['valid'][ $key ] = $item;
			} else {
				$result['invalid'][ $key ] = $item;
			}

		}

		return $result;

	}

}
