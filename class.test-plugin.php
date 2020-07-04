<?php

define('TEST_PLUGIN_JS_NAME', TEST_PLUGIN_NAME . '-js');
define('TEST_PLUGIN_CSS_NAME', TEST_PLUGIN_NAME . '-css');
define('TEST_PLUGIN_OPTIONS_PAGE_NAME', TEST_PLUGIN_NAME . '-admin-options');

define('TEST_PLUGIN_OPTIONS_PRE_ADMIN_OPTIONS', TEST_PLUGIN_NAME . '-admin-options-');
define('TEST_PLUGIN_OPTIONS_ONLY_COUNT_NAME', TEST_PLUGIN_OPTIONS_PRE_ADMIN_OPTIONS . 'only_count_if_widget');
define('TEST_PLUGIN_OPTIONS_ONLY_SEND_NAME', TEST_PLUGIN_OPTIONS_PRE_ADMIN_OPTIONS . 'only_send_if_widget');



class Test_Plugin {
	private static $post_counter = null;
	private static $version = '1.0.0';

	private function __construct() {
		// Static class
	}

	public static function activate() {
		// Code when plugin is activated
	}

	public static function deactivate() {
		// Code when plugin is deactivated
	}

	public static function uninstall() {
		$post_counter = self::get_post_counter();
		$post_counter->delete_all_counters();
	}
	
	public static function register_hooks_and_scripts() {
		if (is_admin()) {
			add_action('admin_menu', array( 'Test_Plugin', 'display_admin_menu' ));
			add_action('admin_init', array( 'Test_Plugin', 'register_options' ));
			
			$version_and_params = self::$version 
				. '&oc=' . TEST_PLUGIN_OPTIONS_ONLY_COUNT_NAME
			 	. '&os=' . TEST_PLUGIN_OPTIONS_ONLY_SEND_NAME;
			wp_enqueue_script(TEST_PLUGIN_JS_NAME, TEST_PLUGIN__PLUGIN_URL . 'admin/js/main.js', array( 'jquery' ), $version_and_params, false);
		} else {
			add_action('the_content', array(Test_Plugin::get_post_counter(), 'count'));	
			wp_enqueue_style(TEST_PLUGIN_CSS_NAME, TEST_PLUGIN__PLUGIN_URL . 'public/css/main.css');
		}
			
		Test_Plugin_Widget::register_hooks();
	}
	
	public static function display_admin_menu() {
		add_menu_page(
				TEST_PLUGIN_NAME, 
				'Test plugin', 
				'administrator', 
				TEST_PLUGIN_NAME, 
				function() {
					include(TEST_PLUGIN__PLUGIN_DIR . 'views/admin-options.php');
				}
		);
	}
	
	public static function display_settings_message() {
		echo 'Configure how the plugin reacts';
	}
	
	public static function register_options() {
		add_settings_section(
			TEST_PLUGIN_OPTIONS_PAGE_NAME,
			'Test Plugin Settings',
			array( 'Test_Plugin', 'display_settings_message' ),
			TEST_PLUGIN_OPTIONS_PAGE_NAME
		);
		self::register_option(
			array (
				'type'		=> 'input',
				'subtype'	=> 'number',
				'id'		=> TEST_PLUGIN_OPTIONS_PRE_ADMIN_OPTIONS . 'count_number_to_send',
				'name'		=> TEST_PLUGIN_OPTIONS_PRE_ADMIN_OPTIONS . 'count_number_to_send',
				'title'		=> 'Count number to send an email',
				'required'	=> 'true',
				'value_type'=>'normal',
				'wp_data'	=> 'option',
				'min'		=> '0'
			)
		);	

		self::register_option(
			array (
				'type'		=> 'input',
				'subtype'	=> 'email',
				'id'		=> TEST_PLUGIN_OPTIONS_PRE_ADMIN_OPTIONS . 'email_to_send',
				'name'		=> TEST_PLUGIN_OPTIONS_PRE_ADMIN_OPTIONS . 'email_to_send',
				'title'		=> 'Email when counting is reached',
				'required'	=> 'true',
				'value_type'=>'normal',
				'wp_data'	=> 'option'
			)
		);

		self::register_option(
			array (
				'type'		=> 'input',
				'subtype'	=> 'checkbox',
				'id'		=> TEST_PLUGIN_OPTIONS_ONLY_COUNT_NAME,
				'name'		=> TEST_PLUGIN_OPTIONS_ONLY_COUNT_NAME,
				'title'		=> 'Count only if widget is present on post',
				'required'	=> 'false',
				'value_type'=>'normal',
				'wp_data'	=> 'option'
			)
		);

		self::register_option(
			array (
				'type'		=> 'input',
				'subtype'	=> 'checkbox',
				'id'		=> TEST_PLUGIN_OPTIONS_ONLY_SEND_NAME,
				'name'		=> TEST_PLUGIN_OPTIONS_ONLY_SEND_NAME,
				'title'		=> 'Send only if widget is present on post',
				'required'	=> 'false',
				'value_type'=>'normal',
				'wp_data'	=> 'option'
			)
		);
	}
	
	private static function register_option($option_args) {
		add_settings_field(
			$option_args['id'],
			$option_args['title'],
			array( 'Test_Plugin', 'render_option' ),
			TEST_PLUGIN_OPTIONS_PAGE_NAME,
			TEST_PLUGIN_OPTIONS_PAGE_NAME,
			$option_args
		);		
		
		register_setting(
			TEST_PLUGIN_OPTIONS_PAGE_NAME,
			$option_args['id']
		);
	}
	
	public static function render_option($option_args) {
		$option_value = get_option($option_args['name']);
		
		if($option_args['subtype'] == 'checkbox') {
			$option_value = ($option_value ? 'checked' : '');
		} else {
			$option_value = ' value="' . esc_attr($option_value) . '"';
		}
		
		$is_required = ($option_args['required'] === 'true' ? ' required="true"' : '');
		
		echo '<input type="' . $option_args['subtype'] 
			. '" name="' . $option_args['name'] 
			. '" id="' . $option_args['id'] 
			. '" min="' . $option_args['min']
			. '" max="' . $option_args['max']
			. '"' . $option_value
			. $is_required
			. '>';
	}

	public static function get_post_counter() {
		if (self::$post_counter === null) {
			self::$post_counter = new Test_Plugin_Post_Counter();
		}

		return self::$post_counter;
	}
}