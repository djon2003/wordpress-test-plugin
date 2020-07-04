<div class="wrap">
	<div id="icon-themes" class="icon32"></div>
	<!--NEED THE settings_errors below so that the errors/success messages are shown after submission - wasn't working once we started using add_menu_page and stopped using add_options_page so needed this-->
	<?php settings_errors(); ?>  
	<form method="POST" action="options.php">  
	<?php
		settings_fields ( TEST_PLUGIN_OPTIONS_PAGE_NAME );
		do_settings_sections ( TEST_PLUGIN_OPTIONS_PAGE_NAME );
	?>
	<?php submit_button(); ?>  
	</form>
</div>