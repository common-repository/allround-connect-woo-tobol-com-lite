<?php
/**
 * Activator Class File
 *
 * This file contains Activator class. If you want to perform some actions
 * in activating of your plugin, you can add your desire methods to it.
 * Actions likes installing separated tables (except WordPress tables),
 * initializing configs for plugin and using update_option, can do with this class.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */

namespace ACBOL_Lite\Includes\Init;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use ACBOL_Lite\Includes\Abstracts\{
	Custom_Post_Type, Custom_Taxonomy
};
use ACBOL_Lite\Includes\Database\Table;
use ACBOL_Lite\Includes\Config\Info;
use ACBOL_Lite\Includes\Functions\{
	Check_Type, Current_User
};

/**
 * Class Activator.
 * If you want to perform some actions in activating of your plugin, you can add your desire methods to it.
 * Actions likes installing separated tables (except WordPress tables),
 * initializing configs for plugin and using update_option, can do with this class.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 * @see        \ACBOL_Lite\Includes\Config\Info
 * @see        \ACBOL_Lite\Includes\Database\Table
 */
class Activator {
	use Check_Type;
	use Current_User;
	/**
	 * @var int $last_db_version The last version of your plugin database
	 */
	private $last_db_version;

	/**
	 * @var Custom_Post_Type[] $custom_post_types
	 */
	private $custom_post_types;

	/**
	 * @var Custom_Taxonomy[] $custom_taxonomies
	 */
	private $custom_taxonomies;

	/**
	 * @var Table $table_object Table object to create or modify tables
	 */
	private $table_object;

	/**
	 * Activator constructor.
	 */
	public function __construct( $last_db_version = null ) {
		$this->last_db_version = $last_db_version;
	}

	/**
	 * Method activate in Activator Class
	 *
	 * It calls when plugin is activated.
	 *
	 * @access  public
	 * @static
	 */
	public function activate(
		$is_need_table_modification = false,
		array $custom_post_types = null,
		array $custom_taxonomies = null,
		object $table_object = null
	) {

		if ( ! is_null( $custom_post_types ) ) {
			$this->custom_post_types = $this->check_array_by_parent_type( $custom_post_types, Custom_Post_Type::class )['valid'];
			if ( ! is_null( $this->custom_post_types ) ) {
				$this->register_plugin_custom_post_type();
			}
		}

		if ( ! is_null( $custom_taxonomies ) ) {
			$this->custom_taxonomies = $this->check_array_by_parent_type( $custom_taxonomies, Custom_Taxonomy::class )['valid'];
			if ( ! is_null( $this->custom_taxonomies ) ) {
				$this->register_plugin_custom_taxonomy();
			}
		}

		// Create needed tables in plugin activation.
		$this->table_object = $table_object;
		if ( $is_need_table_modification === true ) {
			$this->create_needed_tables();
		}
		// Initialize plugin settings and info in option table.
		// TODO: separate this part to another method and then call it
		Info::add_info_in_plugin_activation();

		//TODO: Show customized messages when plugin is activated


	}

	/**
	 * Method to register all of needed custom post types and flush rewrite rules
	 *
	 * @access private
	 */
	private function register_plugin_custom_post_type() {
		if ( ! is_null( $this->custom_post_types ) ) {
			foreach ( $this->custom_post_types as $custom_post_type ) {
				$custom_post_type->add_custom_post_type();
			}
			// ATTENTION: This is *only* done during plugin activation hook in this example!
			// You should *NEVER EVER* do this on every page load!!
			flush_rewrite_rules();
			if ( ! get_option( 'has_rewrite_for_ACBOL_Lite_new_post_types' ) ) {
				flush_rewrite_rules();
				update_option(
					'has_rewrite_for_ACBOL_Lite_new_post_types',
					true
				);
			}
		}
	}

	/**
	 * Method to register all of needed custom taxonomies and flush rewrite rules
	 *
	 * @access private
	 */
	private function register_plugin_custom_taxonomy() {
		if ( ! is_null( $this->custom_taxonomies ) ) {
			foreach ( $this->custom_taxonomies as $custom_taxonomy ) {
				$custom_taxonomy->add_custom_taxonomy();
			}
			// ATTENTION: This is *only* done during plugin activation hook in this example!
			// You should *NEVER EVER* do this on every page load!!
			flush_rewrite_rules();
			if ( ! get_option( 'has_rewrite_for_ACBOL_Lite_new_taxonomies' ) ) {
				flush_rewrite_rules();
				update_option(
					'has_rewrite_for_ACBOL_Lite_new_taxonomies',
					true
				);
			}
		}
	}

	/**
	 * Create needed tables when plugin is activated
	 *
	 * Check is your table exist in database or not you can use from it
	 * for all of your table in first time that your plugin is created.
	 */
	private function create_needed_tables() {
		if ( $this->table_object->has_table_name !== true ) {
			$this->table_object->create_your_table_name();
		}

		// update_option(
		// 	'last_ACBOL_Lite_dbs_version',
		// 	$this->table_object->db_version
		// );
	}
}

