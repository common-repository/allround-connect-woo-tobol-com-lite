<?php
/**
 * Admin_Sub_Menu_Settings Class File
 *
 * This file contains Admin_Sub_Menu class. If you want create an sub menu page
 * under an admin page (inside Admin panel of WordPress), you can use from this class.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */

namespace ACBOL_Lite\Includes\Admin;

use ACBOL_Lite\Includes\Abstracts\Admin_Sub_Menu;
use ACBOL_Lite\Includes\Functions\Template_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Admin_Sub_Menu_Settings.
 * If you want create an sub menu page under an admin page
 * (inside Admin panel of WordPress), you can use from this class.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 * @see        wp-admin/includes/plugin.php
 * @see        https://developer.wordpress.org/reference/functions/add_submenu_page/
 */
class Admin_Sub_Menu_Settings extends Admin_Sub_Menu{
	use Template_Builder;
	/**
	 * Admin_Sub_Menu constructor.
	 * This constructor gets initial values to send to add_submenu_page function to
	 * create admin submenu.
	 *
	 * @access public
	 *
	 * @param array $initial_value Initial value to pass to add_submenu_page function.
	 */
	public function __construct( array $initial_value ) {
		$initial_value['menu_title'] = 'Settings';
		parent::__construct($initial_value);
	}

	/**
	 * Method sub_menu1_panel_handler in Admin_Sub_Menu Class
	 *
	 * For each admin submenu page, we must have callable function that render and
	 * handle this menu page. For each menu page, you must have its own function.
	 *
	 * @access  public
	 */
	public function render_sub_menu_panel() {
		$this->load_template( 'options-page.main-options-page', [] );
	}

}
