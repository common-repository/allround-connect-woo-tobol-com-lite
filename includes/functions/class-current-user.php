<?php
/**
 * Current_User Class File
 *
 * This class contains functions to return current user
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */

namespace ACBOL_Lite\Includes\Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Current_User Class File
 *
 * This class contains functions to return current user
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */
trait Current_User {

	/**
	 * Add pluggable.php before it runs to get current user
	 *
	 * After using this function, you can access to all property of WP_User class like:
	 * ID
	 * user_login
	 * user_pass
	 * user_nicename
	 * user_email
	 * user_url
	 * user_registered
	 * user_activation_key
	 * user_status
	 * display_name
	 *
	 * @access  public
	 *
	 * @return \WP_User
	 */
	public function get_this_login_user() {
		include_once( ABSPATH . 'wp-includes/pluggable.php' );
		return wp_get_current_user();
	}

}