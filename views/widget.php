<div class="widget" style="border:solid 1px yellow;">
	<?php
	
	if ($instance['show_type'] == 'BOTH' || $instance['show_type'] == 'TOTAL') {
		include(TEST_PLUGIN__PLUGIN_DIR . 'views/widget-total-views.php');
	}
	
	if ($instance['show_type'] == 'BOTH' || $instance['show_type'] == 'TOP') {
		include(TEST_PLUGIN__PLUGIN_DIR . 'views/widget-top-viewed.php');
	}
	
	?>
</div>