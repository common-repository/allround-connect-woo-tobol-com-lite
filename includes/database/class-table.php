<?php
/**
 * Table Class File
 *
 * This file contains Table class. If you want to add new tables to your project
 * (except of WordPress table), you can use from this class.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */

namespace ACBOL_Lite\Includes\Database;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Table to add new tables to your project
 *
 * If you want to add new tables to your project
 * (except of WordPress table), you can use from this class.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */
class Table {
	// TODO: This class needs to refactor based on SOLID principles
	/**
	 * Define charset_collate property in Table class
	 *
	 * @access     public
	 * @var string $charset_collate Define charset collection for database.
	 */
	public $charset_collate;
	/**
	 * Define db_version property in Table class
	 *
	 * @access     public
	 * @var int $db_version Set database version for creating table.
	 */
	public $db_version;
	/**
	 * Define has_table_name property in Table class
	 *
	 * @access     public
	 * @var int $has_table_name To check that "Is a table exist with this name or not?".
	 */
	public $has_table_name;
	/**
	 * Define wpdb property in Table class
	 *
	 * @access     private
	 * @var object $wpdb It keeps global $wpdb object inside a Table instance.
	 */
	private $wpdb;

	/**
	 * Table constructor
	 *
	 * This constructor initial all of property for an object which is created
	 * from Table class.
	 *
	 * @access public
	 */
	public function __construct(
		$wpdb_object, $db_version, $has_table_name
	) {
		/**
		 * Use from global $wpdb object.
		 *
		 * @global object $wpdb This is an instantiation of the wpdb class.
		 * @see /wp-includes/wp-db.php
		 */
		$this->wpdb                 = $wpdb_object;
		$this->charset_collate      = $this->wpdb->get_charset_collate();
		$this->db_version           = $db_version;
		$this->has_table_name = $has_table_name;
	}

	/**
	 * Define create_your_table_name method in Table class
	 *
	 * If you want to create a table, you can use from this method. If you
	 * need to create more than one table, you must user from several methods
	 * like this (separated form each other).
	 *
	 * @access  public
	 */
	public function create_your_table_name() {
		$table_name = $this->wpdb->prefix . 'acbol_lite';
		if ( $this->wpdb->get_var( "show tables like '$table_name'" ) !== $table_name ) {
			$sql
				= "CREATE TABLE IF NOT EXISTS $table_name ( id INT(9) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, token VARCHAR(1000), time_end DATETIME )";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );
			// update_option( 'has_table_name', true );
		}

		$table_name = $this->wpdb->prefix . 'acbol_lite_pending_requests';
		if ( $this->wpdb->get_var( "show tables like '$table_name'" ) !== $table_name ) {
			$sql
				= "CREATE TABLE IF NOT EXISTS $table_name ( id INT(9) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, post_id VARCHAR(100), process VARCHAR(100), request VARCHAR(100), href VARCHAR(100), method VARCHAR(100) )";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );
			// update_option( 'has_table_name', true );
		}
	}
}
