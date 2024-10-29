<?php
/**
 * Admin_Notice abstract Class File
 *
 * This file contains contract for Admin_Notice class.
 * If you want to create a Admin_Notice, you must use from this contract.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */

namespace ACBOL_Lite\Includes\Abstracts;


use ACBOL_Lite\Includes\Functions\Utility;
use ACBOL_Lite\Includes\Interfaces\Action_Hook_Interface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin_Notice abstract Class File
 *
 * This file contains contract for Admin_Notice class.
 * If you want to create a Admin_Notice, you must use from this contract.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 *
 * @see        https://code.tutsplus.com/series/persisted-wordpress-admin-notices--cms-1252
 * @see        https://code.tutsplus.com/tutorials/persisted-wordpress-admin-notices-part-1--cms-30134
 */
abstract class Admin_Notice implements Action_Hook_Interface {
	use Utility;

	/**
	 * call 'admin_notice' add_action to show notice on admin panel
	 *
	 * @access public
	 */
	public function register_add_action() {
		add_action( 'admin_notices', [$this , 'show_admin_notice'] );
	}


	/**
	 * Abstract Method show admin notice
	 *
	 * For each each defined notice, you must generate it
	 *
	 * @param array $args Arguments which are needed to show on notice
	 */
	abstract public function show_admin_notice();

}
