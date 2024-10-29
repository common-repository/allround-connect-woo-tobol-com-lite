<?php
/**
 * Initial_Value Class File
 *
 * Role of this class is like RC configuration files in application. If you need
 * to initial value to start your plugin or need them for each time that WordPress
 * run your plugin, you can use from this class.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */

namespace ACBOL_Lite\Includes\Config;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Initial_Value.
 * If you need to initial value to start your plugin or need them for
 * each time that WordPress run your plugin, you can use from this class.
 *
 * @package    ACBOL_Lite
 * @author     Luka Leppens <info@allroundconnect.com>
 */
class Initial_Value {

	/**
	 * Initial values to create new custom post type
	 *
	 * @access public
	 * @static
	 * @return array It returns all of arguments that needs for register a post type.
	 */
	public static function args_for_sample_post_type() {
		$labels = [
			'name'               => _x( 'Name', 'post type general name', 'acbol_lite' ),
			'singular_name'      => _x( 'Name', 'post type singular name', 'acbol_lite' ),
			'menu_name'          => _x( 'Names', 'admin menu', 'acbol_lite' ),
			'name_admin_bar'     => _x( 'Name', 'add new on admin bar', 'acbol_lite' ),
			'add_new'            => _x( 'Add New', 'name', 'acbol_lite' ),
			'add_new_item'       => __( 'Add New Name', 'acbol_lite' ),
			'new_item'           => __( 'New Name', 'acbol_lite' ),
			'edit_item'          => __( 'Edit Name', 'acbol_lite' ),
			'view_item'          => __( 'View Name', 'acbol_lite' ),
			'all_items'          => __( 'All Names', 'acbol_lite' ),
			'search_items'       => __( 'Search Names', 'acbol_lite' ),
			'parent_item_colon'  => __( 'Parent Names:', 'acbol_lite' ),
			'not_found'          => __( 'No names found.', 'acbol_lite' ),
			'not_found_in_trash' => __( 'No names found in Trash', 'acbol_lite' ),
		];

		$args = [
			'labels'             => $labels,
			'description'        => __( 'Description.', 'acbol_lite' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'msn-new-post-type' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 8,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
		];

		$post_type_name = 'msn-new-post-type';

		return [
			'args'           => $args,
			'post_type_name' => $post_type_name,
		];
	}

	/**
	 * Initial values to create admin menu page.
	 *
	 * @access public
	 * @return array It returns all of arguments that add_menu_page function needs.
	 * @see    Includes/Abstract/Admin_Menu
	 */
	public function sample_menu_page() {
		$initial_value = [
			'page_title'        => esc_html__( 'Msn Plugin', 'acbol_lite' ),
			'menu_title'        => esc_html__( 'Msn Plugin', 'acbol_lite' ),
			'capability'        => '',
			'menu_slug'         => 'plugin-name-option-page-url',
			'callable_function' => '',//it can be null
			'icon_url'          => 'dashicons-welcome-widgets-menus',
			'position'          => 2,
			'identifier'        => 'plugin_menu_page1'
		];

		return $initial_value;
	}

	/**
	 * Initial values to create admin submenu page (submenu1).
	 *
	 * @access public
	 * @return array It returns all of arguments that add_submenu_page function needs.
	 * @see    Includes/Abstract/Admin_Sub_Menu
	 */
	public function sample_sub_menu_page1() {
		$initial_value = [
			'parent-slug'       => 'plugin-name-option-page-url',
			'page_title'        => esc_html__( 'Plugin Submenu 1', 'acbol_lite' ),
			'menu_title'        => esc_html__( 'Plugin Submenu 1', 'acbol_lite' ),
			'capability'        => 'manage_options',
			'menu_slug'         => 'plugin-name-option-page-url',
			'callable_function' => 'sub_menu1_panel_handler',
		];

		return $initial_value;
	}

	/**
	 * Initial values to create admin submenu page (submenu1).
	 *
	 * @access public
	 * @return array It returns all of arguments that add_submenu_page function needs.
	 * @see    Includes/Abstract/Admin_Sub_Menu
	 */
	public function sample_sub_menu_page2() {
		$initial_value = [
			'parent-slug'       => 'plugin-name-option-page-url',
			'page_title'        => esc_html__( 'Plugin Submenu 2', 'acbol_lite' ),
			'menu_title'        => esc_html__( 'Plugin Submenu 2', 'acbol_lite' ),
			'capability'        => 'manage_options',
			'menu_slug'         => 'acbol-lite-settings',
			'callable_function' => 'sub_menu2_panel_handler',
		];

		return $initial_value;
	}
	/**
	 * Initial values to create admin submenu page (submenu1).
	 *
	 * @access public
	 * @return array It returns all of arguments that add_submenu_page function needs.
	 * @see    Includes/Abstract/Admin_Sub_Menu
	 */
	public function sample_sub_menu_page3() {
		$initial_value = [
			'parent-slug'       => 'plugin-name-option-page-url',
			'page_title'        => esc_html__( 'Plugin Submenu 3', 'acbol_lite' ),
			'menu_title'        => esc_html__( 'Plugin Submenu 3', 'acbol_lite' ),
			'capability'        => 'manage_options',
			'menu_slug'         => 'acbol-lite-logs',
			'callable_function' => 'sub_menu3_panel_handler',
		];

		return $initial_value;
	}

	/**
	 * Initial values to create option page.
	 *
	 * @access public
	 * @return array It returns all of arguments that add_menu_page function needs.
	 * @see    Includes/Abstract/Option_Menu
	 */
	public function sample_option_page() {
		$initial_value = [
			'page_title'        => esc_html__( 'Msn Options', 'acbol_lite' ),
			'menu_title'        => esc_html__( 'Msn Options', 'acbol_lite' ),
			'capability'        => 'manage_options',
			'menu_slug'         => 'plugin-name-menu-page-option-url',
			'callable_function' => 'management_panel_handler',//it can be null
			'position'          => 8,
		];

		return $initial_value;
	}

	/**
	 * Initial values to create meta box 1.
	 *
	 * @access public
	 * @see    https://developer.wordpress.org/reference/functions/get_post_meta/
	 * @see    https://developer.wordpress.org/reference/functions/add_meta_box/
	 * @return array It returns all of arguments that add_meta_box function needs.
	 */
	public function sample_meta_box3() {
		$initial_value = [

			'id'            => 'meta_box_3_id',
			'title'         => esc_html__( 'Meta box3 Headline', 'acbol_lite' ),
			'callback'      => 'render_content', //It always has this name for all of meta boxes
			'screens'       => array( 'post', 'page' ),//null - optional
			'context'       => 'advanced', //optional
			'priority'      => 'high', //optional
			'callback_args' => null, //optional
			'meta_key'      => '_msn_plugin_boilerplate_meta_box_key_3',
			'single'        => true, //the result of get_post_meta Will be an array if $single is false
			'action'        => 'msn_oop_boilerplate_meta_box3',
			'nonce_name'    => 'msn_oop_boilerplate_meta_box3_nonce'

		];

		return $initial_value;
	}

	/**
	 * Initial values to create meta box 1.
	 *
	 * @access public
	 * @see    https://developer.wordpress.org/reference/functions/get_post_meta/
	 * @see    https://developer.wordpress.org/reference/functions/add_meta_box/
	 * @return array It returns all of arguments that add_meta_box function needs.
	 */
	public function sample_meta_box4() {
		$initial_value = [

			'id'            => 'meta_box_4_id',
			'title'         => esc_html__( 'Meta box4 Headline', 'acbol_lite' ),
			'callback'      => 'render_content',
			'screens'       => array( 'post', 'page' ),//null - optional
			'context'       => 'side', //optional
			'priority'      => 'high', //optional
			'callback_args' => null, //optional
			'meta_key'      => '_msn_plugin_boilerplate_meta_box_key_4',
			'single'        => false, //the result of get_post_meta Will be an array if $single is false
			'action'        => 'msn_oop_boilerplate_meta_box4',
			'nonce_name'    => 'msn_oop_boilerplate_meta_box4_nonce'

		];

		return $initial_value;
	}

	/**
	 * Initial values for sample shortcode  1
	 *
	 * @access public
	 * @return array It returns all of arguments that shortcode class needs.
	 */
	public function sample_shortcode1() {
		$initial_value = [
			'tag'          => 'msnshortcode1',
			'default_atts' => [
				'name' => 'Agha Gholam'
			],
		];

		return $initial_value;
	}

	/**
	 * Initial values for show content only for login user shortcode
	 *
	 * @access public
	 * @return array It returns all of arguments that shortcode class needs.
	 */
	public function sample_content_for_login_user_shortcode() {
		$initial_value = [
			'tag'          => 'msn_content_for_login_user',
			'default_atts' => [],
		];

		return $initial_value;
	}

	/**
	 * Initial values for complete shortcode class
	 *
	 * @access public
	 * @return array It returns all of arguments that shortcode class needs.
	 */
	public function sample_complete_shortcode() {
		$initial_value = [
			'tag'          => 'msn_complete_shortcode',
			'default_atts' => [
				'link' => 'https://wpwebmaster.ir',
				'name' => 'Webmaster WordPress'
			],
		];

		return $initial_value;
	}

	/**
	 * Initial values for Custom_Post1 class
	 *
	 * @access public
	 * @return array It returns all of arguments that Custom_Post1 class needs.
	 */
	public function sample_custom_post1() {

		$labels = array(
			'name'               => _x( 'General Name 1', 'post type general name', 'acbol_lite' ),
			'singular_name'      => _x( 'Name 1', 'post type singular name', 'acbol_lite' ),
			'menu_name'          => _x( 'Names 1', 'admin menu', 'acbol_lite' ),
			'name_admin_bar'     => _x( 'Name !', 'add new on admin bar', 'acbol_lite' ),
			'add_new'            => _x( 'Add New ', 'Name 1', 'acbol_lite' ),
			'add_new_item'       => __( 'Add New Name 1', 'acbol_lite' ),
			'new_item'           => __( 'New Name 1', 'acbol_lite' ),
			'edit_item'          => __( 'Edit Name 1', 'acbol_lite' ),
			'view_item'          => __( 'View Name 1', 'acbol_lite' ),
			'all_items'          => __( 'All Names 1', 'acbol_lite' ),
			'search_items'       => __( 'Search Names 1', 'acbol_lite' ),
			'parent_item_colon'  => __( 'Parent Names 1:', 'acbol_lite' ),
			'not_found'          => __( 'No names 1 found', 'acbol_lite' ),
			'not_found_in_trash' => __( 'No names 1 found in Trash', 'acbol_lite' )
		);

		$args          = array(
			'labels'             => $labels,
			'description'        => __( 'Description 1', 'acbol_lite' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'name1' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 8,
			'menu_icon'          => 'dashicons-calendar-alt',
			'show_in_rest'       => true,
			/*'rest_base'             => 'events',
			'rest_controller_class' => 'WP_REST_Posts_Controller',*/
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
		);
		$initial_value = [
			'post_type' => 'msn-name1',
			'args'      => $args,
		];

		return $initial_value;
	}

	/**
	 * Initial values for Custom_Taxonomy1 class
	 *
	 * Sample of args:
	 * $args = array(
	 *    'labels' => array(),
	 *    'description' => '',
	 *    'public' => null,
	 *    'publicly_queryable' => null,
	 *    'hierarchical' => false,
	 *    'show_ui' => true,
	 *    'show_in_menu' => true,
	 *    'show_in_nav_menus' => true,
	 *    'show_in_rest' => null,
	 *    'rest_base' => is $taxonomy,
	 *    'rest_controller_class' => 'WP_REST_Terms_Controller',
	 *    'show_tagcloud' => true,
	 *    'show_in_quick_edit' => true,
	 *    'show_admin_column' => false,
	 *    'capabilities' => array(
	 *        'manage_terms' => 'manage_categories',
	 *        'edit_terms' => 'manage_categories',
	 *        'delete_terms' => 'manage_categories',
	 *        'assign_terms' => 'edit_posts'
	 *    ),
	 *    'rewrite' => array(
	 *        'slug' => '$taxonomy key',
	 *        'with_front' => true,
	 *        'hierarchical' => false,
	 *        'ep_mask' => 'EP_NONE'
	 *    ),
	 *    'meta_box_cb' => null
	 * );
	 *
	 *
	 * @access public
	 * @return array It returns all of arguments that Custom_Taxonomy1 class needs.
	 *
	 * @see    http://hookr.io/functions/register_taxonomy/
	 * @see    https://developer.wordpress.org/reference/functions/register_taxonomy/
	 */
	public function sample_custom_taxonomy1() {
		// Add new taxonomy, with hierarchical structure (like Category)
		$labels = array(
			'name'                       => _x( 'Taxonomies 1', 'taxonomy general name', 'acbol_lite' ),
			'singular_name'              => _x( 'Taxonomy 1', 'taxonomy singular name', 'acbol_lite' ),
			'search_items'               => __( 'Search Taxonomies 1', 'acbol_lite' ),
			'popular_items'              => __( 'Popular Taxonomies 1', 'acbol_lite' ),
			'all_items'                  => __( 'Taxonomies 1', 'acbol_lite' ),
			'parent_item'                => __( 'Parent Taxonomy 1', 'acbol_lite' ), //if not hierarchical it will be null
			'parent_item_colon'          => __( 'Parent Taxonomy 1:', 'acbol_lite' ), //if not hierarchical it will be null
			'edit_item'                  => __( 'Edit Taxonomy 1', 'acbol_lite' ),
			'update_item'                => __( 'Update Taxonomy 1', 'acbol_lite' ),
			'add_new_item'               => __( 'Add New Taxonomy 1', 'acbol_lite' ),
			'new_item_name'              => __( 'New  Taxonomy 1 Name', 'acbol_lite' ),
			'separate_items_with_commas' => __( 'Separate Taxonomy 1 with commas', 'acbol_lite' ),
			'add_or_remove_items'        => __( 'Add or remove  Taxonomies 1', 'acbol_lite' ),
			'choose_from_most_used'      => __( 'Choose from the most used Taxonomies 1', 'acbol_lite' ),
			'not_found'                  => __( 'No Taxonomies 1 found.', 'acbol_lite' ),
			'menu_name'                  => __( 'Taxonomies 1', 'acbol_lite' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_menu'      => true,
			'show_in_rest'      => true,
			//'update_count_callback' => '_update_post_term_count',
			//The statement: If you want to ensure that your custom taxonomy behaves like a tag, you must add the option
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'sample-taxonomy1' ),
		);

		$initial_value = [
			'taxonomy'    => 'msn-sample-taxonomy1',
			'object_type' => [
				'msn-name1',
				'post',
				'msn-events',
			],
			'args'        => $args,
		];

		return $initial_value;
	}

	/**
	 * Return custom values to have custom cron schedule for wp_schedule_event
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_get_schedules/
	 * @return array
	 */
	public function sample_custom_cron_schedule() {
		$initial_value = [
			'weekly'      => [
				'interval' => 604800,
				'display'  => __( 'Once Weekly' )
			],
			'twiceweekly' => [
				'interval' => 1209600,
				'display'  => __( 'Twice Weekly' )
			]
		];

		return $initial_value;
	}

	/**
	 * Initial values to create a simple settings page in option page
	 *
	 * @return array It returns array of initial values to create a settings page.
	 */
	public function sample_setting_page1() {
		$register_setting_args = array(
			'type'              => 'string',
			'description'       => 'A description of setting page 1',
			'sanitize_callback' => 'sanitize_setting_fields', //It must always this name due to its contract in Setting_Page class
			'default'           => null,
			'show_in_rest'      => false,
		);

		/**
		 * An array of settings sections which can be used in add_settings_section method
		 * Initial values for adding  new section in a settings page.
		 *
		 * @var array $settings_sections Array of settings sections for add_settings_section method
		 * @see https://developer.wordpress.org/reference/functions/add_settings_section/
		 */
		$settings_sections = array(
			array(
				'id'                => 'plugin-name-section1',
				//Slug-name to identify the section. Used in the 'id' attribute of tags.
				'title'             => __( 'Setting section 1', 'acbol_lite' ),
				// Formatted title of the section. Shown as the heading for the section.
				'callback_function' => 'section1',
				//Function that echos out any content at the top of the section (between heading and fields).
				'page'              => 'plugin-name-menu-page-option-url',
				//The slug-name of the settings page on which to show the section.
			),
			array(
				'id'                => 'plugin-name-section2',
				'title'             => __( 'Setting section 2', 'acbol_lite' ),
				'callback_function' => 'section2',
				'page'              => 'plugin-name-menu-page-option-url',
			),
		);

		/**
		 * An array of settings fields which can be used in add_settings_field method
		 * Initial values for adding  new fields to a section of a settings page.
		 *
		 * @var array $settings_fields Array of settings fields for add_settings_fields method
		 * @see https://developer.wordpress.org/reference/functions/add_settings_field/
		 */
		$settings_fields = array(
			array(
				'id'                => 'plugin-name-field-1-1',
				//Slug-name to identify the field
				'title'             => __( 'Field One', 'acbol_lite' ),
				//Formatted title of the field. Shown as the label for the field during output
				'callback_function' => 'field_1_1',
				//Function that fills the field with the desired form inputs. The function should echo its output.
				'page'              => 'plugin-name-menu-page-option-url',
				//The slug-name of the settings page on which to show the section
				'section'           => 'plugin-name-section1',
				//The slug-name of the section of the settings page in which to show the box.
			),
			array(
				'id'                => 'plugin-name-field-1-2',
				'title'             => __( 'Field Two', 'acbol_lite' ),
				'callback_function' => 'field_1_2',
				'page'              => 'plugin-name-menu-page-option-url',
				'section'           => 'plugin-name-section1',
			),
		);

		/**
		 * An array of errors which can be used in add_settings_error method
		 * Initial values for adding errors to a settings page when it's submitted
		 *
		 * @var array $settings_errors Array of settings errors for add_settings_error method
		 * @see https://developer.wordpress.org/reference/functions/add_settings_error/
		 */
		$settings_errors = array(
			'error1' => array(
				'setting' => 'plugin-name-field-1-1-error', //Slug title of the setting to which this error applies.
				'code'    => 'plugin-name-field-1-1-error', //Slug-name to identify the error. Used as part of 'id' attribute in HTML output.
				'message' => 'Incorrect value entered! Please only input letters and spaces.', //The formatted message text to display to the user
				'type'    => 'error' //Possible values include 'error', 'success', 'warning', 'info'
			),
		);

		$initial_value = array(
			'option_group'          => 'ACBOL_Lite_option_group1',
			'option_name'           => 'ACBOL_Lite_option_name1',
			'register_setting_args' => $register_setting_args,
			'settings_sections'     => $settings_sections,
			'settings_fields'       => $settings_fields,
			'settings_errors'       => $settings_errors
		);

		return $initial_value;
	}

	/**
	 * Initial values to create a simple setting option in reading page
	 *
	 * @return array It returns array of initial values to create a settings in reading page.
	 */
	public function sample_setting_in_reading_page1() {
		$register_setting_args = array(
			'type'              => 'string',
			'description'       => 'This is a settings to show in reading page',
			'sanitize_callback' => 'sanitize_setting_fields', //It must always this name due to its contract in Setting_Page class
			'default'           => null,
			'show_in_rest'      => false,
		);

		/**
		 * An array of settings sections which can be used in add_settings_section method
		 * Initial values for adding  new section in a settings page.
		 *
		 * @var array $settings_sections Array of settings sections for add_settings_section method
		 * @see https://developer.wordpress.org/reference/functions/add_settings_section/
		 */
		$settings_sections = array(
			array(
				'id'                => 'plugin-name-section-in-reading1',
				//Slug-name to identify the section. Used in the 'id' attribute of tags.
				'title'             => __( 'Setting section 1', 'acbol_lite' ),
				// Formatted title of the section. Shown as the heading for the section.
				'callback_function' => 'section1',
				//Function that echos out any content at the top of the section (between heading and fields).
				'page'              => 'reading',
				//The slug-name of the settings page on which to show the section.
			)
		);

		/**
		 * An array of settings fields which can be used in add_settings_field method
		 * Initial values for adding  new fields to a section of a settings page.
		 *
		 * @var array $settings_fields Array of settings fields for add_settings_fields method
		 * @see https://developer.wordpress.org/reference/functions/add_settings_field/
		 */
		$settings_fields = array(
			array(
				'id'                => 'plugin-name-field-1-in-reading',
				//Slug-name to identify the field
				'title'             => __( 'Field One', 'acbol_lite' ),
				//Formatted title of the field. Shown as the label for the field during output
				'callback_function' => 'field_1_1',
				//Function that fills the field with the desired form inputs. The function should echo its output.
				'page'              => 'reading',
				//The slug-name of the settings page on which to show the section
				'section'           => 'plugin-name-section-in-reading1',
				//The slug-name of the section of the settings page in which to show the box.
			),
		);

		/**
		 * An array of errors which can be used in add_settings_error method
		 * Initial values for adding errors to a settings page when it's submitted
		 *
		 * @var array $settings_errors Array of settings errors for add_settings_error method
		 * @see https://developer.wordpress.org/reference/functions/add_settings_error/
		 */
		$settings_errors = array(
			'error1' => array(
				'setting' => 'plugin-name-field-1-1-in-reading-page-error',
				//Slug title of the setting to which this error applies.
				'code'    => 'plugin-name-field-1-1-in-reading-page-error',
				//Slug-name to identify the error. Used as part of 'id' attribute in HTML output.
				'message' => __( 'Incorrect value entered! Please only input letters and spaces.', 'acbol_lite' ),
				//The formatted message text to display to the user
				'type'    => 'error'
				//Possible values include 'error', 'success', 'warning', 'info'
			),
		);

		$initial_value = array(
			'option_group'          => 'reading',
			'option_name'           => 'ACBOL_Lite_option_name_in_reading',
			'register_setting_args' => $register_setting_args,
			'settings_sections'     => $settings_sections,
			'settings_fields'       => $settings_fields,
			'settings_errors'       => $settings_errors
		);

		return $initial_value;
	}

	public function sample_setting_page2() {


		/**
		 * An array of settings sections which can be used in add_settings_section method
		 * Initial values for adding  new section in a settings page.
		 *
		 * @var array $settings_sections Array of settings sections for add_settings_section method
		 * @see https://developer.wordpress.org/reference/functions/add_settings_section/
		 */
		$settings_sections = array(
			array(
				'id'           => 'ACBOL_Lite_section1',
				//Slug-name to identify the section. Used in the 'id' attribute of tags.
				'title'        => __( 'Setting section 1', 'acbol_lite' ),
				// Formatted title of the section. Shown as the heading for the section.
				//'callback_function' => 'section1',
				//Function that echos out any content at the top of the section (between heading and fields).
				'header_title' => 'Title 1',
				//The slug-name of the settings page on which to show the section.
				'description'  => 'this is first description'
			),
			array(
				'id'           => 'ACBOL_Lite_section2',
				'title'        => __( 'Setting section 2', 'acbol_lite' ),
				//'callback_function' => 'section2',
				'header_title' => 'Title 2',
				'description'  => 'this is second description'
			),
		);

		/**
		 * An array of settings fields which can be used in add_settings_field method
		 * Initial values for adding  new fields to a section of a settings page.
		 *
		 * @var array $settings_fields Array of settings fields for add_settings_fields method
		 * @see https://developer.wordpress.org/reference/functions/add_settings_field/
		 */
		$settings_fields = array(

			'ACBOL_Lite_section1' =>
				array(
					array(
						'id'                => 'text',
						'type'              => 'text',
						'name'              => __( 'Text Input 1', 'acbol_lite' ),
						'desc'              => __( 'Text input description 1', 'acbol_lite' ),
						'default'           => 'Default Text',
						'sanitize_callback' => 'sample_sanitize_text_field',
						'page'              => 'plugin-name-option-page-2',
						'option_name'       => 'ACBOL_Lite_option_name2'
					),
					array(
						'id'                => 'text_no',
						'type'              => 'number',
						'name'              => __( 'Number Input 1', 'acbol_lite' ),
						'desc'              => __( 'Number 1 field with validation callback `intval`', 'acbol_lite' ),
						'default'           => 1,
						'sanitize_callback' => 'sanitize_general_text_field',
						'page'              => 'plugin-name-option-page-2',
						'option_name'       => 'ACBOL_Lite_option_name2'
					)
				),
			'ACBOL_Lite_section2' =>
				array(
					array(
						'id'                => 'text',
						'type'              => 'text',
						'name'              => __( 'Text Input2', 'acbol_lite' ),
						'desc'              => __( 'Text input description2', 'acbol_lite' ),
						'default'           => 'Default Text',
						'sanitize_callback' => 'sample_sanitize_text_field',
						'page'              => 'plugin-name-option-page-2',
						'option_name'       => 'ACBOL_Lite_option_name3'
					),
					array(
						'id'                => 'text_no',
						'type'              => 'number',
						'name'              => __( 'Number Input2', 'acbol_lite' ),
						'desc'              => __( 'Number2 field with validation callback `intval`', 'acbol_lite' ),
						'default'           => 1,
						'sanitize_callback' => 'sanitize_general_text_field',
						'page'              => 'plugin-name-option-page-2',
						'option_name'       => 'ACBOL_Lite_option_name3'
					)
				)
		);

		/**
		 * An array of errors which can be used in add_settings_error method
		 * Initial values for adding errors to a settings page when it's submitted
		 *
		 * @var array $settings_errors Array of settings errors for add_settings_error method
		 * @see https://developer.wordpress.org/reference/functions/add_settings_error/
		 */
		$settings_errors = array(
			'error1' => array(
				'setting' => 'plugin-name-field-1-1-error', //Slug title of the setting to which this error applies.
				'code'    => 'plugin-name-field-1-1-error', //Slug-name to identify the error. Used as part of 'id' attribute in HTML output.
				'message' => 'Incorrect value entered! Please only input letters and spaces.', //The formatted message text to display to the user
				'type'    => 'error' //Possible values include 'error', 'success', 'warning', 'info'
			),
		);


		$register_setting_args2 = array(
			'type'              => 'string',
			'description'       => 'A description of setting page 1',
			'sanitize_callback' => 'sanitize_setting_fields', //It must always this name due to its contract in Setting_Page class
			'default'           => null,
			'show_in_rest'      => false,
		);

		$register_setting_args3 = array(
			'type'              => 'string',
			'description'       => 'A description of setting page 2',
			'sanitize_callback' => 'sanitize_setting_fields', //It must always this name due to its contract in Setting_Page class
			'default'           => null,
			'show_in_rest'      => false,
		);

		$setting_groups = array(
			array(
				'option_group'          => 'ACBOL_Lite_option_group2',
				'option_name'           => 'ACBOL_Lite_option_name2',
				'register_setting_args' => $register_setting_args2,
				'id'                    => 'plugin-name-option-group2-id',
				'title'                 => 'Section 2',
			),
			array(
				'option_group'          => 'ACBOL_Lite_option_group3',
				'option_name'           => 'ACBOL_Lite_option_name3',
				'register_setting_args' => $register_setting_args3,
				'id'                    => 'plugin-name-option-group3-id',
				'title'                 => 'Section 3',
			),
		);

		$initial_value = array(
			'setting_groups'    => $setting_groups,
			'settings_sections' => $settings_sections,
			'settings_fields'   => $settings_fields,
			'settings_errors'   => $settings_errors,
		);

		return $initial_value;
	}

	/**
	 * Initial values to create a settings page in option menu
	 *
	 * @return array[] It returns array of initial values to create a settings page
	 */
	public function get_complete_setting_page_arguments() {
		/**
		 * An array of settings sections which can be used in add_settings_section method
		 * Initial values for adding  new section in a settings page.
		 *
		 * @var array $settings_sections Array of settings sections for add_settings_section method
		 * @see https://developer.wordpress.org/reference/functions/add_settings_section/
		 */
		$settings_sections = array(
			array(
				'id'           => 'ACBOL_Lite_section1',
				//Slug-name to identify the section. Used in the 'id' attribute of tags.
				'title'        => __( '', 'acbol_lite' ),
				// Formatted title of the section. Shown as the heading for the section.
				//'callback_function' => 'section1',
				//Function that echos out any content at the top of the section (between heading and fields).
				'header_title' => 'Bol.com',
				//The slug-name of the settings page on which to show the section.
				'description'  => ''
			),
		);

		/**
		 * An array of settings fields which can be used in add_settings_field method
		 * Initial values for adding  new fields to a section of a settings page.
		 *
		 * @var array $settings_fields Array of settings fields for add_settings_fields method
		 * @see https://developer.wordpress.org/reference/functions/add_settings_field/
		 */
		$settings_fields = array(

			'ACBOL_Lite_section1' =>
				array(
					array(
						'id'   => 'company_title',
						'type' => 'title',
						'name' => '<h2>Bol.com API</h2>',
					),
					array(
						'id'                => 'bol_api_limit',
						'type'              => 'number',
						'name'              => __( 'API requests limiet', 'acbol_lite' ),
						'options'		=> array(
							'tooltip'	=> 'Vul hier het limit in voor het aantal requests dat de wachtrij mag doen',
							'default'	=> 40
						),
					),
					array(
						'id'                => 'bol_client_id',
						'type'              => 'text',
						'name'              => __( 'Client ID', 'acbol_lite' ),
						'options'		=> array(
							'tooltip'	=> 'Vul hier uw client id in van bol.com',
						),
					),
					array(
						'id'                => 'bol_client_secret',
						'type'              => 'password',
						'name'              => __( 'Client Secret', 'acbol_lite' ),
						'options'		=> array(
							'tooltip'	=> 'Vul hier uw client secret van bol.com in',
						),
					),
					array(
						'id'   => 'bol_api',
						'type' => 'separator',
					),
					array(
						'id'                => 'bol_ean',
						'type'              => 'dropdown',
						'name'              => __( 'EAN Code', 'acbol_lite' ),
						'sanitize_callback' => 'sample_sanitize_text_field',
						'desc'				=> 'Staat de EAN code in een ander veld dan de standaard SKU? Met de PRO plugin kun je elk custom field of attribuut selecteren als EAN veld. <a href="https://allroundconnect.com/nl/woocommerce-koppelen-met-bol-com/" target="_blank">Klik hier voor meer informatie</a>',
						'options'			=> array(
							'tooltip'			=> 'Gebruik het product ean als de ean code',
							'dropdown'  => array(
								'Standaard', 
							),
						),
						
					),
					array(
						'id'                => 'bol_ean_custom_field',
						'type'              => 'dropdown',
						'name'              => __( 'Custom veld', 'acbol_lite' ),
						'sanitize_callback' => 'sample_sanitize_text_field',
						'options'			=> array(
							'tooltip'			=> 'Gebruik een product custom field als de ean code',
							'dropdown'  => array(
								'product_custom_field'
							),
						),
						
					),
					array(
						'id'                => 'bol_ean_product_attribute',
						'type'              => 'dropdown',
						'name'              => __( 'Product attribuut', 'acbol_lite' ),
						'sanitize_callback' => 'sample_sanitize_text_field',
						'options'			=> array(
							'tooltip'			=> 'Gebruik een product attribuut als de ean code',
							'dropdown'  => array(
								'product_attributes'
							),
						),
						
					),
					array(
						'id'   => 'bol_import',
						'type' => 'checkbox',
						'name' => __( 'Importeer orders vanuit Bol.com', 'acbol_lite' ),
						'options'		=> array(
							'tooltip'	=> 'Upgrade naar PRO om deze optie te gebruiken',
							'disabled'	=> 'true'
						),
					),
					array(
						'id'   => 'bol_import_lvb',
						'type' => 'checkbox',
						'name' => __( 'Importeer LVB orders vanuit Bol.com', 'acbol_lite' ),
						'options'		=> array(
							'tooltip'	=> 'Upgrade naar PRO om deze optie te gebruiken',
							'disabled'	=> 'true'
						),
					),
					array(
						'id'   => 'bol_import_retour',
						'type' => 'checkbox',
						'name' => __( 'Importeer retouren', 'acbol_lite' ),
						'options'		=> array(
							'tooltip'	=> 'Upgrade naar PRO om deze optie te gebruiken',
							'disabled'	=> 'true'
						),
					),
					array(
						'id'   => 'bol_import_commission',
						'type' => 'checkbox',
						'name' => __( 'Importeer Bol.com commissie', 'acbol_lite' ),
						'options'		=> array(
							'tooltip'	=> 'Upgrade naar PRO om deze optie te gebruiken',
							'disabled'	=> 'true'
						),
					),
					array(
						'id'                => 'bol_product_condition',
						'type'              => 'dropdown',
						'name'              => __( 'Product conditie', 'acbol_lite' ),
						'desc'              => __( '', 'acbol_lite' ),
						'sanitize_callback' => 'sample_sanitize_text_field',
						'options'			=> array(
							'tooltip'			=> 'Selecteer de standaard product conditie',
							'dropdown'  => array(
								'Nieuw', 
								'Als nieuw',
								'Goed',
								'Redelijk',
								'Matig',
							),
						),
					),
					array(
						'id'                => 'bol_fullfilment_method',
						'type'              => 'dropdown',
						'name'              => __( 'Fullfilment methode', 'acbol_lite' ),
						'desc'              => __( '', 'acbol_lite' ),
						'sanitize_callback' => 'sample_sanitize_text_field',
						'options'			=> array(
							'tooltip'			=> 'Selecteer de standaard fulfillment methode',
							'dropdown'  => array(
								'Zelf verzenden', 
								'Bol.com',
							),
						),
					),
					array(
						'id'   => 'bol_transport_separator',
						'type' => 'separator',
					),
					array(
						'id'   => 'bol_transport_title',
						'type' => 'title',
						'name' => '<h2>Verzenden</h2>',
					),
					array(
						'id'                => 'bol_transport',
						'type'              => 'dropdown',
						'name'              => __( 'Verzend transporter', 'acbol_lite' ),
						'desc'              => __( '', 'acbol_lite' ),
						'options'			=> array(
							'tooltip'			=> 'Upgrade naar PRO om deze optie te gebruiken',
							'dropdown'  => array(
								'PostNL', 
								'DHL Parcel',
								'bpost',
								'DPD'
							),
						),
					),
					array(
						'id'                => 'bol_transport_time',
						'type'              => 'dropdown',
						'name'              => __( 'Verzendtijd', 'acbol_lite' ),
						'desc'              => __( '', 'acbol_lite' ),
						'options'			=> array(
							'tooltip'			=> 'Deze plugin is lit',
							'dropdown'  => array(
								'Binnen 24 uur', 
								'1-2 Werkdagen',
								'2-3 Werkdagen',
								'3-5 Werkdagen',
								'4-8 Werkdagen',
								'1-8 Werkdagen',
							),
						),
					),
					array(
						'id'                => 'bol_transport_time_orderd_before',
						'type'              => 'dropdown',
						'name'              => __( 'Indien bestelt voor', 'acbol_lite' ),
						'desc'              => __( '', 'acbol_lite' ),
						'options'			=> array(
							'tooltip'			=> 'Selecteer een tijdstip',
							'dropdown'  => array(
								'23:00', 
								'22:00',
								'21:00', 
								'20:00',
								'19:00', 
								'18:00',
								'17:00', 
								'16:00',
								'15:00', 
								'14:00',
								'13:00', 
								'12:00',
							),
						),
					),
					array(
						'id'   => 'bol_prices_separator',
						'type' => 'separator',
					),
					array(
						'id'   => 'bol_price_title',
						'type' => 'title',
						'name' => '<h2>Prijzen</h2>',
					),
					array(
						'id'   => 'bol_woocommerce_prices',
						'type' => 'checkbox',
						'name' => __( 'Stuur Woocommerce prijzen door naar Bol.com', 'acbol_lite' ),
						'desc' => 'Deze functionaliteit zit alleen in de pro plugin. <a href="https://allroundconnect.com/nl/woocommerce-koppelen-met-bol-com/" target="_blank">Klik hier voor meer informatie</a>',
						'options'		=> array(
							'disabled'	=> 'true',
							'tooltip'	=> 'Vink de checkbox aan om de prijzen door te sturen',
						),
					),
					array(
						'id'   => 'bol_price_margin',
						'type' => 'checkbox',
						'name' => __( 'Marge op Productprijs naar Bol.com', 'acbol_lite' ),
						'options'		=> array(
							'disabled'	=> 'true',
							'tooltip'	=> 'Vink de checkbox aan om een marge op de productprijs te zetten',
						),
					),
					array(
						'id'                => 'bol_price_margin_input',
						'type'              => 'text',
						'name'              => __( 'Marge op productprijs', 'acbol_lite' ),
						'options'		=> array(
							'disabled'	=> 'true',
							'tooltip'	=> 'Gebruik een % om een percentage te kiezen bij. 5% en een . voor centen, bijv 10.50',
						),
					),
				),
		);

		/**
		 * An array of errors which can be used in add_settings_error method
		 * Initial values for adding errors to a settings page when it's submitted
		 *
		 * @var array $settings_errors Array of settings errors for add_settings_error method
		 * @see https://developer.wordpress.org/reference/functions/add_settings_error/
		 */
		$settings_errors = array(
			'error1' => array(
				'setting' => 'plugin-name-field-1-1-error', //Slug title of the setting to which this error applies.
				'code'    => 'plugin-name-field-1-1-error', //Slug-name to identify the error. Used as part of 'id' attribute in HTML output.
				'message' => 'Incorrect value entered! Please only input letters and spaces.', //The formatted message text to display to the user
				'type'    => 'error' //Possible values include 'error', 'success', 'warning', 'info'
			),
		);


		$register_setting_args2 = array(
			'type'              => 'string',
			'description'       => 'A description of setting page 1',
			'sanitize_callback' => 'sanitize_setting_fields', //It must always this name due to its contract in Setting_Page class
			'default'           => null,
			'show_in_rest'      => false,
		);

		$register_setting_args3 = array(
			'type'              => 'string',
			'description'       => 'A description of setting page 2',
			'sanitize_callback' => 'sanitize_setting_fields', //It must always this name due to its contract in Setting_Page class
			'default'           => null,
			'show_in_rest'      => false,
		);


		$initial_value = array(
			'settings_sections' => $settings_sections,
			'settings_fields'   => $settings_fields,
			'settings_errors'   => $settings_errors,
			//'admin_menu_args'   => $admin_menu_args,
		);

		return $initial_value;
	}

	/**
	 * Initial values to create option page.
	 *
	 * @access public
	 * @return array It returns all of arguments that add_options_page function needs.
	 */
	public function get_option_menu_settings() {
		$admin_menu_args = array(
			'page_title'        => esc_html__( 'Complete Settings Page', 'acbol_lite' ),
			'menu_title'        => esc_html__( 'Complete Settings Page', 'acbol_lite' ),
			'capability'        => 'manage_options',
			'menu_slug'         => 'plugin-name-complete-setting-page',
			'callable_function' => 'set_plugin_setting_page',//it can be null
			'position'          => 11,
		);

		return $admin_menu_args;
	}

}
