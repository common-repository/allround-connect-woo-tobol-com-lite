<?php
/**
 * Constant Class File
 *
 * This file contains Constant class which defines needed constants to ease
 * your plugin development processes.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */

namespace ACBOL_Lite\Includes\Init;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Class Constant
 *
 * This class defines needed constants that you will use in plugin development.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */
class Constant {

	/**
	 * Define define_constant method in Constant class
	 *
	 * It defines all of constants that you need
	 *
	 * @access  public
	 * @static
	 */
	public static function define_constant() {

		/**
		 * ACBOL_Lite_PATH constant.
		 * It is used to specify plugin path
		 */
		if ( ! defined( 'ACBOL_Lite_PATH' ) ) {
			define( 'ACBOL_Lite_PATH', trailingslashit( plugin_dir_path( dirname( dirname( __FILE__ ) ) ) ) );
		}

		/**
		 * ACBOL_Lite_URL constant.
		 * It is used to specify plugin urls
		 */
		if ( ! defined( 'ACBOL_Lite_URL' ) ) {
			define( 'ACBOL_Lite_URL', trailingslashit( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) ) );
		}

		/**
		 * ACBOL_Lite_CSS constant.
		 * It is used to specify css urls inside assets directory. It's used in front end and
		 * using to  load related CSS files for front end user.
		 */
		if ( ! defined( 'ACBOL_Lite_CSS' ) ) {
			define( 'ACBOL_Lite_CSS', trailingslashit( ACBOL_Lite_URL ) . 'assets/css/' );
		}

		/**
		 * ACBOL_Lite_JS constant.
		 * It is used to specify JavaScript urls inside assets directory. It's used in front end and
		 * using to load related JS files for front end user.
		 */
		if ( ! defined( 'ACBOL_Lite_JS' ) ) {
			define( 'ACBOL_Lite_JS', trailingslashit( ACBOL_Lite_URL ) . 'assets/js/' );
		}

		/**
		 * ACBOL_Lite_IMG constant.
		 * It is used to specify image urls inside assets directory. It's used in front end and
		 * using to load related image files for front end user.
		 */
		if ( ! defined( 'ACBOL_Lite_IMG' ) ) {
			define( 'ACBOL_Lite_IMG', trailingslashit( ACBOL_Lite_URL ) . 'assets/images/' );
		}

		/**
		 * ACBOL_Lite_ADMIN_CSS constant.
		 * It is used to specify css urls inside assets/admin directory. It's used in WordPress
		 *  admin panel and using to  load related CSS files for admin user.
		 */
		if ( ! defined( 'ACBOL_Lite_ADMIN_CSS' ) ) {
			define( 'ACBOL_Lite_ADMIN_CSS', trailingslashit( ACBOL_Lite_URL ) . 'assets/admin/css/' );
		}

		/**
		 * ACBOL_Lite_ADMIN_JS constant.
		 * It is used to specify JS urls inside assets/admin directory. It's used in WordPress
		 *  admin panel and using to  load related JS files for admin user.
		 */
		if ( ! defined( 'ACBOL_Lite_ADMIN_JS' ) ) {
			define( 'ACBOL_Lite_ADMIN_JS', trailingslashit( ACBOL_Lite_URL ) . 'assets/admin/js/' );
		}

		/**
		 * ACBOL_Lite_ADMIN_IMG constant.
		 * It is used to specify image urls inside assets/admin directory. It's used in WordPress
		 *  admin panel and using to  load related JS files for admin user.
		 */
		if ( ! defined( 'ACBOL_Lite_ADMIN_IMG' ) ) {
			define( 'ACBOL_Lite_ADMIN_IMG', trailingslashit( ACBOL_Lite_URL ) . 'assets/admin/images/' );
		}

		/**
		 * ACBOL_Lite_TPL constant.
		 * It is used to specify template urls inside templates directory.
		 */
		if ( ! defined( 'ACBOL_Lite_TPL' ) ) {
			define( 'ACBOL_Lite_TPL', trailingslashit( ACBOL_Lite_PATH . 'templates' ) );
		}

		/**
		 * ACBOL_Lite_INC constant.
		 * It is used to specify include path inside includes directory.
		 */
		if ( ! defined( 'ACBOL_Lite_INC' ) ) {
			define( 'ACBOL_Lite_INC', trailingslashit( ACBOL_Lite_PATH . 'includes' ) );
		}

		/**
		 * ACBOL_Lite_LANG constant.
		 * It is used to specify language path inside languages directory.
		 */
		if ( ! defined( 'ACBOL_Lite_LANG' ) ) {
			define( 'ACBOL_Lite_LANG', trailingslashit( ACBOL_Lite_PATH . 'languages' ) );
		}

		/**
		 * ACBOL_Lite_TPL_ADMIN constant.
		 * It is used to specify template urls inside templates/admin directory. If you want to
		 * create a template for admin panel or administration purpose, you will use from it.
		 */
		if ( ! defined( 'ACBOL_Lite_TPL_ADMIN' ) ) {
			define( 'ACBOL_Lite_TPL_ADMIN', trailingslashit( ACBOL_Lite_TPL . 'admin' ) );
		}

		/**
		 * ACBOL_Lite_TPL_FRONT constant.
		 * It is used to specify template urls inside templates/front directory. If you want to
		 * create a template for front end or end user purposes, you will use from it.
		 */
		if ( ! defined( 'ACBOL_Lite_TPL_FRONT' ) ) {
			define( 'ACBOL_Lite_TPL_FRONT', trailingslashit( ACBOL_Lite_TPL . 'front' ) );
		}

		/**
		 * ACBOL_Lite_TPL constant.
		 * It is used to specify template urls inside templates directory.
		 */
		if ( ! defined( 'ACBOL_Lite_LOGS' ) ) {
			define( 'ACBOL_Lite_LOGS', trailingslashit( ACBOL_Lite_PATH . 'logs' ) );
		}

		/**
		 * ACBOL_Lite_CSS_VERSION constant.
		 * You can use from this constant to apply on main CSS file when you have changed it.
		 */
		if ( ! defined( 'ACBOL_Lite_CSS_VERSION' ) ) {
			define( 'ACBOL_Lite_CSS_VERSION', 1 );
		}
		/**
		 * ACBOL_Lite_JS_VERSION constant.
		 * You can use from this constant to apply on main JS file when you have changed it.
		 */
		if ( ! defined( 'ACBOL_Lite_JS_VERSION' ) ) {
			define( 'ACBOL_Lite_JS_VERSION', 1 );
		}

		/**
		 * ACBOL_Lite_CSS_VERSION constant.
		 * You can use from this constant to apply on main CSS file when you have changed it.
		 */
		if ( ! defined( 'ACBOL_Lite_ADMIN_CSS_VERSION' ) ) {
			define( 'ACBOL_Lite_ADMIN_CSS_VERSION', 1 );
		}
		/**
		 * ACBOL_Lite_JS_VERSION constant.
		 * You can use from this constant to apply on main JS file when you have changed it.
		 */
		if ( ! defined( 'ACBOL_Lite_ADMIN_JS_VERSION' ) ) {
			define( 'ACBOL_Lite_ADMIN_JS_VERSION', 1 );
		}

		/**
		 * ACBOL_Lite_VERSION constant.
		 * It defines version of plugin for management tasks in your plugin
		 */
		if ( ! defined( 'ACBOL_Lite_VERSION') ) {
			define( 'ACBOL_Lite_VERSION', '1.0.2' );
		}

		/**
		 * ACBOL_Lite_MAIN_NAME constant.
		 * It defines name of plugin for management tasks in your plugin
		 */
		if ( ! defined( 'ACBOL_Lite_MAIN_NAME') ) {
			define( 'ACBOL_Lite_MAIN_NAME', 'plugin-name' );
		}

		/**
		 * ACBOL_Lite_DB_VERSION constant
		 *
		 * It defines database version
		 * You can use from this constant to apply your changes in updates or
		 * activate plugin again
		 */
		if ( ! defined( 'ACBOL_Lite_DB_VERSION') ) {
			define( 'ACBOL_Lite_DB_VERSION', 1 );
		}

		/**
		 * ACBOL_Lite_TEXTDOMAIN constant
		 *
		 * It defines text domain name for plugin
		 */
		if ( ! defined( 'ACBOL_Lite_TEXTDOMAIN') ) {
			define( 'ACBOL_Lite_TEXTDOMAIN', 'plugin-name-textdomain' );
		}
		/*In future maybe I want to add constants for separated upload directory inside plugin directory*/
	}
}
