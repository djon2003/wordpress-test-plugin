<?php
/**
 * Plugin Name: Test plugin name
 * Description: My testing description
 * Plugin URI: http://www.cints.net
 * Version: 1.0.0
 * Author: Jonathan Boivin
 * Licence: GPLv2+
 */

define('TEST_PLUGIN_NAME', 'test_plugin');
define('TEST_PLUGIN__PLUGIN_DIR', plugin_dir_path( __FILE__ ));
define('TEST_PLUGIN__PLUGIN_URL', plugin_dir_url( __FILE__ ));

// Do requires
require_once(TEST_PLUGIN__PLUGIN_DIR . 'class.test-plugin.widget.php');
require_once(TEST_PLUGIN__PLUGIN_DIR . 'class.test-plugin.post-counter.php');
require_once(TEST_PLUGIN__PLUGIN_DIR . 'class.test-plugin.php');


// Set primary hooks
register_activation_hook( __FILE__, array('Test_Plugin', 'activate'));
register_deactivation_hook( __FILE__, array('Test_Plugin', 'deactivate'));
register_uninstall_hook( __FILE__, array('Test_Plugin', 'uninstall'));

// Register other hooks
Test_Plugin::register_hooks_and_scripts();

