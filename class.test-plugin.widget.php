<?php

define('TEST_PLUGIN_SHORT_CODE_NAME', 'test-plugin');

class Test_Plugin_Widget extends WP_Widget {
	private static $is_shortcode_activated = true;
	
	public function __construct() {
		parent::__construct(
			'Test_Plugin_Widget',
			__('Test Plugin Widget', 'text_domain' ),
			array(
					'customize_selective_refresh' => true,
			)
		);
	}
	
	public function form( $instance ) {
		// Avaible options
		$dropdown_options = array(
			'show_type' => array(
				'BOTH' => __( 'Total views and top viewed', 'text_domain' ),
				'TOTAL' => __( 'Only total views', 'text_domain' ),
				'TOP' => __( 'Only top viewed', 'text_domain' ),
			)
		);
		
		// Widget data
		$defaults = array(
			'show_type'   => 'BOTH'
		);
		extract(wp_parse_args((array) $instance, $defaults));
		
		// View
		include(TEST_PLUGIN__PLUGIN_DIR . 'views/admin-widget-form.php');
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['show_type']   = isset( $new_instance['show_type'] ) ? wp_strip_all_tags( $new_instance['show_type'] ) : '';
		return $instance;
	}
	
	public function widget( $args, $instance ) {
		$post_counter = Test_Plugin::get_post_counter();
		$total_views = $post_counter->get_posts_count();
		$post_top_viewed_id = $post_counter->get_top_post_id();
		
		include(TEST_PLUGIN__PLUGIN_DIR . 'views/widget.php');
	}
	
	public static function has_shortcode($post_ID) {
		$post_content = get_the_content($post = $post_ID);

		self::$is_getting_title = true;
		$post_title = wp_get_document_title();
		self::$is_getting_title = false;
		
		$has_post_content_shortcode = strpos($post_content, '[' . TEST_PLUGIN_SHORT_CODE_NAME. ']');
		$has_post_title_shortcode = strpos($post_title, '[' . TEST_PLUGIN_SHORT_CODE_NAME. ']');
		
		return $has_post_content_shortcode || $has_post_title_shortcode;
	}
	
	private static $is_getting_title = false; //FIXME: This shall be change due to concurrency
	public static function get_post_title( $title ) {
		if (!self::$is_shortcode_activated) {
			return $title;
		}
		
		if (self::$is_getting_title) {
			return '';
		}
		if (empty($title)) {
			self::$is_getting_title = true;
			$title = wp_get_document_title();
			self::$is_getting_title = false;
			return str_replace('[' . TEST_PLUGIN_SHORT_CODE_NAME. ']', '', $title);
		}
	
		return str_replace('[' . TEST_PLUGIN_SHORT_CODE_NAME. ']', (is_admin() ? '' : self::render_for_post(true)), $title);
	}
	
	private static function render_for_post($is_title) {
		$post_count = Test_Plugin::get_post_counter()->get_post_count();
		
		$rendered = '<span class="test-plugin-widget">';
		if ($is_title) {
			$rendered .= '<span class="test-plugin-widget-title">';
			if (is_single()) {
				$post_count++; //FIXME: Quick fix because post counter is activated after the title is set when viewing a post
			}
		}
		ob_start();
		include(TEST_PLUGIN__PLUGIN_DIR . 'views/widget-on-post.php');
		$rendered .= ob_get_clean();
		if ($is_title) {
			$rendered .= '</span>';
		}
		$rendered .= '</span>';
		
		return $rendered;
	}
	
	public static function register_hooks() {
		add_action('widgets_init', function() {
			register_widget('Test_Plugin_Widget');
		});
		
		add_shortcode(TEST_PLUGIN_SHORT_CODE_NAME, function() {
			return self::render_for_post(false);
		});		
	
		add_filter('the_title', array( 'Test_Plugin_Widget', 'get_post_title'));
		add_filter('pre_get_document_title', array( 'Test_Plugin_Widget', 'get_post_title'));
	}
}

