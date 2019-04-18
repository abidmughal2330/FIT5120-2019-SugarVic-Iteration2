jQuery(document).ready(function(){
	// nationality handler
	jQuery('#cus-inputform-submit').click(function() {
		var nat = jQuery("#nat-input").val();
		var sub = jQuery('#sub-input').val();
		if (jQuery.trim(nat) != '') {
			var data = {
				action: 'nat_response',
	            post_var: nat
			};
			// the_ajax_script.ajaxurl is a variable that will contain the url to the ajax processing file
		 	jQuery.post(custom_ajax_script.ajaxurl, data, function(response) {
		 		var rawdata = JSON.parse(response);
		 		if (rawdata[0].length != 0){
					updateChart(rawdata[0]);
					updateHeatmap(rawdata);
					jQuery('#chartcontainer').show();
					jQuery('#chart_tips_click').show();
					jQuery('#heatmapcontainer').show();
					jQuery('#init-info').hide();
					jQuery('#chart-error').hide();
				}
				else {
					jQuery('#init-info').hide();
					jQuery('#chartcontainer').hide();
					jQuery('#heatmapcontainer').hide();
					jQuery('#chart-error').show();
					var err = jQuery('#nat-input').val();
					jQuery('#chart-error h3').text('**Sorry, no data for ' + err);
				}
		 	});
		}
		else if (jQuery.trim(sub) != '') {
			var data = {
				action: 'sub_response',
	            post_var: sub,
			};
			jQuery.post(custom_ajax_script.ajaxurl, data, function(response) {
				var rawdata = JSON.parse(response);
				if (rawdata.length != 0){
					updateSubChart(rawdata);
					jQuery('#chartcontainer').show();
					jQuery('#chart_tips_click').hide();
					jQuery('#heatmapcontainer').hide();
					jQuery('#init-info').hide();
					jQuery('#chart-error').hide();
				}
				else {
					jQuery('#init-info').hide();
					jQuery('#chartcontainer').hide();
					jQuery('#heatmapcontainer').hide();
					jQuery('#chart-error').show();
					var err = jQuery('#sub-input').val();
					jQuery('#chart-error h3').text('**Sorry, no data for ' + err);
				}
			});
		}
		else {
			jQuery('#chartcontainer').hide();
			jQuery('#init-info').show();
			jQuery('#chart-error').hide();
		}
		return false;
	});

	// activity search button event
	jQuery('#search-btn').click(function() {
		var sub = jQuery("#s-suburb").val();
		var cat = jQuery('#search-list').val();
		var msg = sub + ',' + cat;

		var data = {
			action: 'map_response',
			post_var: msg
		};
		jQuery.post(custom_ajax_script.ajaxurl, data, function(response) {
			var rawdata = JSON.parse(response);
			blockGen(rawdata[0]);
			setCenter(rawdata[1]);
		});
	});

	// restaurant search button event
	jQuery('#rest_search').click(function() {
		var sub = jQuery.trim(jQuery('#rest_sub').val());
		var cuisine = jQuery.trim(jQuery('#rest_cuisine').val());
		var rating = jQuery('#rest_sort').val();
		var order = jQuery('#rest_order').val();
		if (sub != '' && cuisine != ''){
			document.getElementById('cardcontainer').innerHTML = '';
			jQuery(".rest-tips").hide();
			jQuery(".rest-error").hide();
			jQuery('.rest-inputerror').hide();
			jQuery(".rest-showmore").hide();
			jQuery(".preload").show();
			var msg = sub + ',' + cuisine + ',' + rating + ',' + order;
			var data = {
				action: 'rest_response',
				post_var: msg
			};
			jQuery.post(custom_ajax_script.ajaxurl, data, function(response) {
				if (jQuery.trim(response) == 'Error'){
					jQuery(".preload").hide();
					jQuery(".rest-error").html("Sorry, no results for " + sub);
					jQuery(".rest-error").show();
				}
				else {
					var rawdata = JSON.parse(response);
					var restdata = rawdata.restaurants;
					cardGen(restdata);
				}
			});
		}
		else {
			document.getElementById('cardcontainer').innerHTML = '';
			jQuery(".rest-showmore").hide();
			jQuery('.rest-inputerror').show();
		}
	});

})