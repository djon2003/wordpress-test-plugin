<?php

class Test_Plugin_Utils {
	private function __construct() {
		// Static class
	}
	
	public static function send_email($post_ID) {
		$email_to_send = get_option(TEST_PLUGIN_OPTIONS_PRE_ADMIN_OPTIONS . 'email_to_send', '');
		$post_title = get_the_title($post_ID);
		$post_views = Test_Plugin::get_post_counter()->get_post_count($post_ID);
		
		$message = 'Post #' . $post_ID . ' titled ' . $post_title . ' :<br>It has reached ' . $post_views . ' views';
		
		mail($email_to_send, 'Post has reached desired views', $message);
	}
}