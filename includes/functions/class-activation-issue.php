<?php
/**
 * Activation_Issue trait File
 *
 * This class contains functions to log activation issues when plugin is activated
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */

namespace ACBOL_Lite\Includes\Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Activation_Issue trait File
 *
 * This class contains functions to log activation issues when plugin is activated
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */
trait Activation_Issue {

	/**
	 * Register 'activated_plugin' add_action to call related method to log error
	 */
	public function register_error_activation_add_action() {
		add_action( 'activated_plugin', [ $this, 'save_plugin_activation_error' ] );
	}

	/**
	 * Save activation errors or warnings or notices in option table
	 */
	public function save_plugin_activation_error() {
		update_option( 'msn_plugin_activation_error', ob_get_contents() );
	}

	/**
	 * Show plugin activation errors or warnings or notices by echoing it
	 */
	public function show_plugin_activation_error() {
		echo get_option( 'msn_plugin_activation_error' );
	}

}