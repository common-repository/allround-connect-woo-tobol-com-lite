<?php
/**
 * De-activator Class File
 *
 * This class defines tasks that must be run when plugin is deactivated.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundweb.nl>
 */

namespace ACBOL_Lite\Includes\Uninstall;

use ACBOL_Lite\Includes\Functions\Current_User;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Deactivator.
 * You can run desire tasks with this class when your plugin is de-activated.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundweb.nl>
 */
class Deactivator {
	use Current_User;

	/**
	 * Run related tasks when plugin is deactivated
	 *
	 * @access public
	 */
	public function deactivate() {

		// $this->register_deactivator_user();

		if ( get_option( 'plugin_name_prefix_plugin_setting_option2' ) ) {
			update_option(
				'plugin_name_prefix_plugin_setting_option2',
				'After de-activation'
			);
		}

		if ( get_option( 'plugin_name_prefix_plugin_setting_option3' ) ) {
			delete_option( 'plugin_name_prefix_plugin_setting_option3' );
		}


		if ( get_option( 'has_rewrite_for_plugin_name_new_post_types' ) ) {
			update_option(
				'has_rewrite_for_plugin_name_new_post_types',
				false
			);
		}

		if ( get_option( 'has_rewrite_for_plugin_name_new_taxonomies' ) ) {
			update_option(
				'has_rewrite_for_plugin_name_new_taxonomies',
				false
			);
		}

		if ( wp_next_scheduled( 'acbol_handle_requests' ) ) {
			wp_clear_scheduled_hook('acbol_handle_requests');
		}
	}
}


