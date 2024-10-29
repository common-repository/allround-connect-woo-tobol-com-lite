<?php
/**
 * Uninstall Class
 *
 * This class defines tasks that must be run when plugin uninstalling.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundweb.nl>
 */

namespace ACBOL_Lite\Includes\Uninstall;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Uninstall
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundweb.nl>
 */
class Uninstall {
	/**
	 * Destroy Config
	 * Drop Database
	 * Delete options
	 * Removing Settings
	 */
	public static function uninstall() {

		// TODO: delete_option for option values that need them again
		// TODO: delete_option for post types ('has_rewrite_for_plugin_name_new_post_types')
		// TODO: unregister_setting + delete_option for  Clean de-registration of registered setting in settings page

	}
}



