/**
 * 
 */

(function() {
	function extractUrlParam(name, URL){
		URL = URL || window.location.href;
		
		var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(URL);
		return results != null ? results[1] : null;
	}
	
	// Retrieve fields name
	var scripts = document.getElementsByTagName('script');
	var script = scripts[scripts.length - 1];
	var scriptURL = script.src;
	
	var sendOnlyIfWidget = extractUrlParam('os', scriptURL);
	var countOnlyIfWidget = extractUrlParam('oc', scriptURL);
	
	jQuery(function() {
		// Declare fields if they exists
		var $sendOnlyIfWidget = jQuery("#" + sendOnlyIfWidget);
		var $countOnlyIfWidget = jQuery("#" + countOnlyIfWidget);

		// Code when in options page
		if ($sendOnlyIfWidget.length != 0) {
			if ($countOnlyIfWidget.is(':checked')) {
				$sendOnlyIfWidget.prop('disabled', true);
				$sendOnlyIfWidget.prop('checked', true);
			}
			
			$countOnlyIfWidget.click(function() {
				if ($countOnlyIfWidget.is(':checked')) {
					$sendOnlyIfWidget.prop('disabled', true);
					$sendOnlyIfWidget.prop('checked', true);
				} else {
					$sendOnlyIfWidget.prop('disabled', false);
				}
			});
		}		
	});
})();