<?php
/**
 * Public_Hook Class File
 *
 * This file contains hooks that you need in public
 * (like enqueue styles or scripts in front end)
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */

namespace ACBOL_Lite\Includes\Init;

use ACBOL_Lite\Includes\Interfaces\Action_Hook_Interface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */
class Public_Hook implements Action_Hook_Interface {

	/**
	 * The ID of this plugin.
	 *
	 * @access   private
	 * @var      string $acbol_lite The ID of this plugin.
	 */
	private $acbol_lite;

	/**
	 * The version of this plugin.
	 *
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $plugin_version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @access   public
	 *
	 * @param      string $acbol_lite    The name of the plugin.
	 * @param      string $plugin_version The version of this plugin.
	 */
	public function __construct( $acbol_lite, $plugin_version ) {

		$this->acbol_lite    = $acbol_lite;
		$this->plugin_version = $plugin_version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @access   public
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style(
			$this->acbol_lite . '-public-style',
			ACBOL_Lite_CSS . 'acbol-lite-public-ver-' . ACBOL_Lite_CSS_VERSION . '.css',
			array(),
			null,
			'all'
		);
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @access   public
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script(
			$this->acbol_lite . '-public-script',
			ACBOL_Lite_JS . 'acbol-lite-public-ver-' . ACBOL_Lite_JS_VERSION . '.js',
			array( 'jquery' ),
			null,
			true
		);
	}

	/**
	 * Register actions that the object needs to be subscribed to.
	 *
	 */
	public function register_add_action() {
		$this->set_enqueue_scripts_action();
	}

	/**
	 * Register enqueue scripts action
	 */
	public function set_enqueue_scripts_action() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}
}
