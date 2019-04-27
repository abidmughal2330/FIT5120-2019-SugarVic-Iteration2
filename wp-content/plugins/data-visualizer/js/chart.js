jQuery(document).ready(function() {
	if(chart_type == 'line with view finder') {
		chart_type = 'line';
		var subchart = true;
	}
	else {
		var subchart = false;
	}
	var chart = c3.generate({
		bindto: '#' + chart_id,
	    data: {
	        url:  csv_file,
	        type: chart_type
	    },
    	subchart: {
	        show: subchart
	    }
	});
});