<?php
/**
 * Check_Woocommerce trait File
 *
 * This class contains methods that check is woocommerce activated or not
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */

namespace ACBOL_Lite\Includes\Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check_Woocommerce trait File
 *
 * This class contains methods that check is woocommerce activated or not
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */
trait Check_Woocommerce {

	/**
	 * Method to check is Woocommerce activated or not
	 *
	 *
	 * @access  public
	 *
	 * @return bool
	 */
	public function is_woocommerce_active( ) {
		if ( in_array('woocommerce/woocommerce.php', apply_filters('acbol_lite_active_plugins',get_option('active_plugins')))) {
			return true;
		} else {
			return false;
		}
	}

}
