var map, heatmap;
function initMap() {
	map = new google.maps.Map(document.getElementById('map'), {
		zoom: 10,
		maxZoom: 12,
		center: {lat: -37.814, lng: 144.96332},
		mapTypeId: 'roadmap',
		disableDefaultUI: true,
		zoomControl: true,
		fullscreenControl: true
	});

}

// update map after each query
function updateHeatmap(rawdata) {
	var subpop = rawdata[1];
	var subloc = rawdata[2];
	var hdata = [];
	for (var i = 0; i < rawdata[2].length; i++) {
		for (var j = 0; j < rawdata[1].length; j++) {
			if (rawdata[2][i].Suburbs == rawdata[1][j].burbs)
			{
				hdata.push({location:new google.maps.LatLng(rawdata[2][i].latitude, rawdata[2][i].longitude), weight: parseInt(rawdata[1][j].Population)});
			}
		}
	}
	if (heatmap != null) {
		heatmap.setMap(null);
	}
	heatmap = new google.maps.visualization.HeatmapLayer({
	  data: hdata,
	  map: map
	});
	heatmap.setOptions({radius:50});
}