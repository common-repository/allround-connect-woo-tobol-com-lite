<?php
/**
 * Admin_Menu Class File
 *
 * This file contains Admin_Menu class. If you want create an admin page
 * inside admin panel of WordPress, you can use from this class.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */

namespace ACBOL_Lite\Includes\Admin;

use ACBOL_Lite\Includes\Abstracts\Admin_Menu;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Admin_Menu.
 * If you want create an admin page inside admin panel of WordPress,
 * you can use from this class.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 *
 * @see        wp-admin/includes/plugin.php
 * @see        https://developer.wordpress.org/reference/functions/add_menu_page/
 */
class Admin_Menu_Main extends Admin_Menu{


	/**
	 * Admin_Menu constructor.
	 * This constructor gets initial values to send to add_menu_page function to
	 * create admin menu.
	 *
	 * @access public
	 *
	 * @param array $initial_value Initial value to pass to add_menu_page function.
	 */
	public function __construct( array $initial_values ) {
		$dir = substr(plugin_dir_url( __DIR__ ), 0, -9) . "assets/images/ac-icon.png";

		$initial_values['menu_title'] = 'Allround Connect';
		$initial_values['icon_url'] = $dir;
		parent::__construct($initial_values);
	}

	/**
	 * Method management_panel_handler in Admin_Menu Class
	 *
	 * For each admin menu page, we must have callable function that render and
	 * handle this menu page. For each menu page, you must have its own function.
	 *
	 * @access  public
	 */
	public function management_panel_handler() {

	}
}
