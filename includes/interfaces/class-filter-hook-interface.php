<?php
/**
 * Filter_Hook_Interface interface File
 *
 * This file contains Filter_Hook_Interface. If you to use add_filter and remove_filter in your class,
 * you must use from this contract to implement it.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */

namespace ACBOL_Lite\Includes\Interfaces;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter_Hook_Interface interface File
 *
 * This file contains Filter_Hook_Interface. If you to use add_action in your class,
 * you must use from this contract to implement it.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */
interface Filter_Hook_Interface {

	/**
	 * Register filters that the object needs to be subscribed to.
	 *
	 */
	public function register_add_filter();
}