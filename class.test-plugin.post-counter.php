<?php

define('TEST_PLUGIN_OPTIONS_PRE_POSTS', TEST_PLUGIN_NAME . '-posts-');

class Test_Plugin_Post_Counter {
	function __construct() {
		
	}
	
	/**
	 * Filter on post read to increment its counter
	 * @param string $content
	 */
	public function count($content) {		
		if (is_single()) {
			// Assign plugin options
			$count_number_to_send = get_option(TEST_PLUGIN_OPTIONS_PRE_ADMIN_OPTIONS . 'count_number_to_send', 0);
			$only_count_if_widget = (get_option(TEST_PLUGIN_OPTIONS_ONLY_COUNT_NAME, '') ? true : false);
			$only_send_if_widget = (get_option(TEST_PLUGIN_OPTIONS_ONLY_SEND_NAME, '') ? true : false);
			
			// Increment post counter by one			
			$post_counter = $this->get_post_count();
			$post_counter++;
			
			// Decide if counting & sending
			$post_ID = get_the_ID();
			$has_shortcode = Test_Plugin_Widget::has_shortcode($post_ID);
				
			$is_counting = !$only_count_if_widget || $has_shortcode;
			$is_sending =	$count_number_to_send == $post_counter && ( 
								(!$only_count_if_widget && !$only_send_if_widget) ||
								($only_count_if_widget && !$only_send_if_widget) ||
								($only_count_if_widget && $only_send_if_widget && $has_shortcode)
							);
			
			// Save count & send email if needed
			if ($is_counting) {
				$option_name = $this->get_counter_option_name();
				if ($post_counter === 1) {
					add_option($option_name, $post_counter);
				} else {
					update_option($option_name, $post_counter);
				}
				
				if ($is_sending) {
					Test_Plugin_Utils::send_email($post_ID);
				}
			}
		}
		
		return $content;
	}
	
	private function get_counter_option_name($post_ID = null) {
		if ($post_ID == null) {
			$post_ID = get_the_ID();	
		}
		return TEST_PLUGIN_OPTIONS_PRE_POSTS . $post_ID;
	}
	
	public function get_post_count($post_ID = null) {
		$option_name = $this->get_counter_option_name($post_ID);
		return get_option($option_name, $default = 0);
	}
	
	public function get_top_post_id() {
		$max_count = 0;
		$max_id = null;
		
		$options = wp_load_alloptions();
		foreach ($options as $option_name => $option_value) {
			$option_name_prefix = substr($option_name, 0, strlen(TEST_PLUGIN_OPTIONS_PRE_POSTS));
		
			if (TEST_PLUGIN_OPTIONS_PRE_POSTS === $option_name_prefix && $option_value > $max_count) {
				$max_count = $option_value;
				$max_id = substr($option_name, strlen(TEST_PLUGIN_OPTIONS_PRE_POSTS));
			}
		}
		
		return $max_id;
	}
	
	public function get_posts_count() {
		$count = 0;
		
		$options = wp_load_alloptions();
		foreach ($options as $option_name => $option_value) {
			$option_name_prefix = substr($option_name, 0, strlen(TEST_PLUGIN_OPTIONS_PRE_POSTS));
		
			if (TEST_PLUGIN_OPTIONS_PRE_POSTS === $option_name_prefix) {
				$count += $option_value;
			}
		}
		
		return $count;
	}
	
	/**
	 * Delete all existing counter
	 */
	public function delete_all_counters() {
		$options = wp_load_alloptions();
		foreach ($options as $option_name => $option_value) {
			$option_name_prefix = substr($option_name, 0, strlen(TEST_PLUGIN_OPTIONS_PRE_POSTS));

			if (TEST_PLUGIN_OPTIONS_PRE_POSTS === $option_name_prefix) {
				delete_option($option_name);
			}
		}
	}
}