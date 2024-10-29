<?php
/**
 * Log_In_Footer Class File
 *
 * This class contains functions to log when wp_footer hook initiates.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */

namespace ACBOL_Lite\Includes\Functions;

use ACBOL_Lite\Includes\Interfaces\Action_Hook_With_Args_Interface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Log_In_Footer Class File
 *
 * This class contains functions to log when wp_footer hook initiates.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */
class Log_In_Footer implements Action_Hook_With_Args_Interface {
	use Logger;

	/**
	 * Register actions that the object needs to be subscribed to.
	 *
	 * @see https://stackoverflow.com/questions/2843356/can-i-pass-arguments-to-my-function-through-add-action
	 */
	public function register_add_action_with_arguments( $args ) {
		if ( is_admin() ) {
			add_action( 'admin_footer',
				function () use ( $args ) {
					$this->append_log_in_text_file( $args['log_message'], $args['file_name'], $args['type'] );
				}
			);
		} else {
			add_action( 'wp_footer',
				function () use ( $args ) {
					$this->append_log_in_text_file( $args['log_message'], $args['file_name'], $args['type'] );
				}
			);
		}

	}
}
