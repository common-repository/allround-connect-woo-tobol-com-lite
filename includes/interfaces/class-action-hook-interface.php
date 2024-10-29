<?php
/**
 * Action_Hook_Interface interface File
 *
 * This file contains Action_Hook_Interface_Interface. If you to use add_action and remove_action in your class,
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
 * Action_Hook_Interface interface File
 *
 * This file contains Action_Hook_Interface_Interface. If you to use add_action in your class,
 * you must use from this contract to implement it.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */
interface Action_Hook_Interface {

	/**
	 * Register actions that the object needs to be subscribed to.
	 *
	 */
	public function register_add_action();
}