// hide chart in the begining
jQuery('document').ready(function(){
	ctx = document.getElementById('myChart').getContext('2d');
	myChart = new Chart(ctx, {
		type: 'bar',
		data: {
		labels: [],
		datasets: [{
		    data: [],
		    backgroundColor: 'rgba(132,195,64,.40)',
		    borderWidth: 1
		    }]
		},
		options: {
		    maintainAspectRatio: false,
		    legend: {display:false},
		    title:{
		        display: true,
			        text: 'Top 10 Suburbs With Population From ',
		        fontSize: 20,
				lineHeight: 3
		    },
		    axisX: {
				labelMaxWidth: 70,
				labelWrap: true,   // change it to false
				interval: 1
			},
		    scales: {
			    xAxes: [{
			      ticks: {
			        autoSkip: false
			      },
			    }],
		    	yAxes: [{
		            display: true,
		            ticks: {
		                beginAtZero: true,
		            }
				}]
			}
		}
	});
});

var ctx;
var myChart;	

// update chart after each query
function updateChart(rawdata){
	var label = [];
	var data = [];
	var myObj = rawdata;
	for (var i = 0; i < myObj.length; i++) {
		label.push(myObj[i].burbs);
		data.push(parseInt(myObj[i].Population));
	}
	myChart.config.data = {labels: label,
	    datasets: [{
	        data: data,
	        backgroundColor: 'rgba(132,195,64,.40)',
	        borderWidth: 1
	        }]
	};
	myChart.options.title.text = 'Top 10 Suburbs With Population From '+myObj[0].Nationality;	
	myChart.update();
}

function updateSubChart(rawdata){
	var label = [];
	var data = [];
	var myObj = rawdata;
	var totalpop = 0;
	for (var i = 0; i < myObj.length; i++) {
		totalpop += parseFloat(myObj[i].Population);
	}

	var totpercn = 0.0;
	for (var i = 0; i < myObj.length; i++) {
		var percen = parseFloat(myObj[i].Population) / totalpop;
		if (percen > 0.05) {
			label.push(myObj[i].Nationality);
			data.push(Number(percen).toFixed(2));
			totpercn += percen;
		}
	}
	label.push('Others');
	data.push(0.05);
	myChart.config.data = {labels: label,
	    datasets: [{
	        data: data,
	        backgroundColor: 'rgba(132,195,64,.40)',
	        borderWidth: 1
	        }]
	};
	myChart.options.title.text = 'Foreign Population Ratio in '+myObj[0].burbs;	
	myChart.update();
}	