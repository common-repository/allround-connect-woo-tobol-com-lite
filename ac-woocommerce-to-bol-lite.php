<?php
/**
 * AC Woocommerce to Bol.com Plugin
 * 
 * This plugin was created using the OOP Wordpress Plugin Boilerplate from:
 *  @name Mehdi Soltani 
 *  @author Github user: msn60
 * @link https://github.com/msn60/oop-wordpress-plugin-boilerplate
 *
 * @link              https://allroundconnect.com/woocommerce-koppelen-met-bol-com/
 * @package           ACBOL_Lite
 *
 * @wordpress-plugin
 * Plugin Name:       Allround Connect WooCommerce to Bol.com Lite
 * Plugin URI:        https://allroundconnect.com/woocommerce-koppelen-met-bol-com/
 * Description:       Synchroniseer je prijzen en voorraad van Woocommerce naar Bol.com.
 * Version:           1.0.3
 * Author:            Luka Leppens <info@allroundconnect.com>
 * Author URI:        https://allroundconnect.com/nl/over-ons/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

/**
 * Define your namespaces here by use keyword
 */
use ACBOL_Lite\Includes\Init\{
	Admin_Hook, Core, Constant, Activator, I18n, Public_Hook, Router
};
use ACBOL_Lite\Includes\Config\Initial_Value;
use ACBOL_Lite\Includes\Parts\Other\Remove_Post_Column;
use ACBOL_Lite\Includes\Uninstall\{
	Deactivator, Uninstall
};
use ACBOL_Lite\Includes\Admin\{
	Admin_Menu_Main, Admin_Sub_Menu_Settings, Option_Menu_Settings, Setting_Bol_Page,
	Notices\Admin_Notice1, Notices\Woocommerce_Deactive_Notice
};

use ACBOL_Lite\Includes\Functions\Init_Functions;
use ACBOL_Lite\Includes\Functions\Ajax_Calls;
use ACBOL_Lite\Includes\Database\Table;
use ACBOL_Lite\Includes\Cron\Crons;
use ACBOL_Lite\Includes\Hooks\Filters\Custom_Cron_Schedule;
use ACBOL_Lite\Includes\Hooks\Filters\Filters;

// COMPOSER
$dir = plugin_dir_path( __FILE__ );
require $dir . '/vendor/autoload.php';

/**
 * If this file is called directly, then abort execution.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class ACBOL_Lite_Plugin
 *
 * This class is primary file of plugin which is used from
 * singletone design pattern.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 * @see        ACBOL_Lite\Includes\Init\Core Class
 * @see        ACBOL_Lite\Includes\Init\Constant Class
 * @see        ACBOL_Lite\Includes\Init\Activator Class
 * @see        ACBOL_Lite\Includes\Uninstall\Deactivator Class
 * @see        ACBOL_Lite\Includes\Uninstall\Uninstall Class
 */
final class ACBOL_Lite_Plugin {
	/**
	 * Instance property of ACBOL_Lite_Plugin Class.
	 * This is a property in your plugin primary class. You will use to create
	 * one object from ACBOL_Lite_Plugin class in whole of program execution.
	 *
	 * @access private
	 * @var    ACBOL_Lite_Plugin $instance create only one instance from plugin primary class
	 * @static
	 */
	private static $instance;
	/**
	 * @var Initial_Value $initial_values An object  to keep all of initial values for theme
	 */
	protected $initial_values;
	/**
	 * @var Core $core_object An object to keep core class for plugin.
	 */
	private $core_object;

	/**
	 * ACBOL_Lite_Plugin constructor.
	 * It defines related constant, include autoloader class, register activation hook,
	 * deactivation hook and uninstall hook and call Core class to run dependencies for plugin
	 *
	 * @access private
	 */
	public function __construct() {
		/*Define Autoloader class for plugin*/
		$autoloader_path = 'includes/class-autoloader.php';
		/**
		 * Include autoloader class to load all of classes inside this plugin
		 */
		require_once trailingslashit( plugin_dir_path( __FILE__ ) ) . $autoloader_path;
		/*Define required constant for plugin*/
		Constant::define_constant();

		/**
		 * Register activation hook.
		 * Register activation hook for this plugin by invoking activate
		 * in ACBOL_Lite_Plugin class.
		 *
		 * @param string   $file     path to the plugin file.
		 * @param callback $function The function to be run when the plugin is activated.
		 */
		register_activation_hook(
			__FILE__,
			function () {
				$this->activate(
					new Activator( intval( get_option( 'last_ACBOL_Lite_dbs_version' ) ) )
				);
			}
		);
		/**
		 * Register deactivation hook.
		 * Register deactivation hook for this plugin by invoking deactivate
		 * in ACBOL_Lite_Plugin class.
		 *
		 * @param string   $file     path to the plugin file.
		 * @param callback $function The function to be run when the plugin is deactivated.
		 */
		register_deactivation_hook(
			__FILE__,
			function () {
				$this->deactivate(
					new Deactivator()
				);
			}
		);
		/**
		 * Register uninstall hook.
		 * Register uninstall hook for this plugin by invoking uninstall
		 * in ACBOL_Lite_Plugin class.
		 *
		 * @param string   $file     path to the plugin file.
		 * @param callback $function The function to be run when the plugin is uninstalled.
		 */
		register_uninstall_hook(
			__FILE__,
			array( 'ACBOL_Lite_Plugin', 'uninstall' )
		);
	}

	/**
	 * Call activate method.
	 * This function calls activate method from Activator class.
	 * You can use from this method to run every thing you need when plugin is activated.
	 *
	 * @access public
	 * @see    ACBOL_Lite\Includes\Init\Activator Class
	 */
	public function activate( Activator $activator_object ) {
		global $wpdb;
		$activator_object->activate(
			true,
			[
				// new Custom_Post1( $this->initial_values->sample_custom_post1() )
			],
			[
				// new Custom_Taxonomy1( $this->initial_values->sample_custom_taxonomy1() )
			],
				new Table( $wpdb, 1, "acbol" )
			
			
		);
		
	}

	/**
	 * Call deactivate method.
	 * This function calls deactivate method from Dectivator class.
	 * You can use from this method to run every thing you need when plugin is deactivated.
	 *
	 * @access public
	 */
	public function deactivate( Deactivator $deaactivator_object ) {
		$deaactivator_object->deactivate();
	}

	/**
	 * Create an instance from ACBOL_Lite_Plugin class.
	 *
	 * @access public
	 * @return ACBOL_Lite_Plugin
	 */
	public static function instance() {
		if ( is_null( ( self::$instance ) ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Call uninstall method.
	 * This function calls uninstall method from Uninstall class.
	 * You can use from this method to run every thing you need when plugin is uninstalled.
	 *
	 * @access public
	 */
	public static function uninstall() {
		Uninstall::uninstall();
	}

	/**
	 * Load Core plugin class.
	 *
	 * @access public
	 */
	public function run_ACBOL_Lite_plugin() {
		$this->initial_values = new Initial_Value();
		$this->core_object    = new Core(
			$this->initial_values,
			new Init_Functions(),
			new I18n(),
			new Admin_Hook( ACBOL_Lite_MAIN_NAME, ACBOL_Lite_VERSION ),
			new Public_Hook( ACBOL_Lite_MAIN_NAME, ACBOL_Lite_VERSION ),
			new Router(),
			[
				new Admin_Menu_Main( $this->initial_values->sample_menu_page() )
			],
			[
				new Admin_Sub_Menu_Settings( $this->initial_values->sample_sub_menu_page2() ),
			],[],[],[],[],
			[
				'admin_notice1' => new Admin_Notice1(),
				'woocommerce_deactivate_notice' => new Woocommerce_Deactive_Notice(),
			],
			[],[],
			[
				new Setting_Bol_Page(
					$this->initial_values->get_complete_setting_page_arguments(),
					new Option_Menu_Settings($this->initial_values->get_Option_Menu_Settings())
				)
			],
			new Custom_Cron_Schedule( $this->initial_values->sample_custom_cron_schedule() ),
			[
				new Ajax_Calls(),
			],
			[
				new Filters(),
			],[
				new Crons(),
			],
		);
		$this->core_object->init_core();
	}
}

$ACBOL_Lite_plugin_object = ACBOL_Lite_Plugin::instance();
$ACBOL_Lite_plugin_object->run_ACBOL_Lite_plugin();

/**
 * Settings href hijack
 */
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'my_plugin_settings_link' );
function my_plugin_settings_link($links) { 
	$settings_link = '<a href="admin.php?page=acbol-lite-settings">Settings</a>'; 
	array_unshift($links, $settings_link); 
	return $links; 
}


