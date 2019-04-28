 <?php
/**

 * Plugin Name: Graph Generator
 * Description: This plugin generates data graph 
 * Version: 1.0.0
 * Author: Aaron
 * License: GPL2
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ajax hooking */ 
function enqueueAjaxScript() {
	wp_enqueue_script('custom-ajax', plugins_url('js/ajax.js', __FILE__), array('jquery'));
	$script_data = array(
        'ajaxurl' => admin_url( 'admin-ajax.php' )
    );
    wp_localize_script(
        'custom-ajax',
        'custom_ajax_script',
        $script_data
    );
}
add_action('wp_enqueue_scripts', 'enqueueAjaxScript');
include(plugin_dir_path( __FILE__ ) . 'db/db.php');

function enqueueCommunityScript() {
	wp_enqueue_style('community-style', plugins_url('css/community.css', __FILE__));
	wp_enqueue_style('autocomplete-style', plugins_url('css/autocomplete.css', __FILE__));
	wp_enqueue_style('nice-select-style', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css');
	wp_enqueue_script('nice-select-script', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js');
	wp_enqueue_script('autocomplete-script', plugins_url('js/autocomplete.js', __FILE__));

}
add_action('wp_enqueue_scripts', 'enqueueCommunityScript');
function enqueueActivityScript() {
	wp_enqueue_style('activity-style', plugins_url('css/activity.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'enqueueActivityScript');
function enqueueRestaurantScript() {
	wp_enqueue_style('restaurant-style', plugins_url('css/restaurant.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'enqueueRestaurantScript');

$sub = array();
$pop = array();
$test = '';
/* user input module for community page*/
function getUserInput() { 
	global $wpdb;
	//store value of nation dropdown list
	$nat = $wpdb->get_results( "SELECT DISTINCT Nationality FROM final"); 
	//store suburb list for autocompletion
	$sublist = $wpdb->get_results( "SELECT DISTINCT burbs FROM final");
	?>
	<div class="community-container">
		<form autocomplete="off">
	   		<div class="nationality-dropdown autocomplete">
			    <input id="nat-input" type="text" name="nat-input" placeholder="Nationality" required="please enter suburb">
			</div>
			<div class="seperate-word">
				<p>Or</p>
			</div>
			<div class="suburb-dropdown autocomplete">
			    <input id="sub-input" type="text" name="sub-input" placeholder="Suburb">
			</div>
			<div class="submitbtn">
		    	<input type="button" name="show_dowpdown_value" value="Show" id="cus-inputform-submit" />
		    </div>
		</form>
	</div>
	<script type="text/javascript">
		// clear other input text when focus on one input
		jQuery( "#nat-input" ).focus(function() {
			jQuery("#sub-input").val('');
		});
		jQuery( "#sub-input" ).focus(function() {
			jQuery("#nat-input").val('');
		});
	</script>
	 <!-- auto completion -->
	<script>

		/*An array containing all the country names in the world:*/
		var subs = [<?php foreach ($sublist as $row) {
			echo '"'.$row->burbs.'",';
		} ?>];

		var nats = [<?php foreach ($nat as $row) {
			echo '"'.$row->Nationality.'",';
		} ?>];

		/*initiate the autocomplete function on the "sub-input" element, and pass along the countries array as possible autocomplete values:*/
		autocomplete(document.getElementById("sub-input"), subs);
		autocomplete(document.getElementById("nat-input"), nats);
	</script>
	 <?php  
	
}

/* barchart module for community page */
function graphGen() {?>
	<div id="init-info">
		<h3>**Please enter a nationality or suburb</h3>
		<p>**Tips:<br>
		You can enter a nationality to see the top 10 suburbs which contains people from there<br>
		Or you can enter a suburb to see the proportion of people from foreign countries<br></p>
	</div>	
	<div class="chartcontainer" id="chartcontainer" style="display: none">
		<section class="chartholder"><canvas id="myChart"></canvas></section>
		<div id='chart_tips'>Tips: you can hover on the graph to see more details <span id='chart_tips_click'> and also <b style="color: #84c340;">CLICK ON THE BAR</b> to see all activities in the related suburb</span></div>
	</div>
	<div id="chart-error" style="display: none; margin-top: 10px;">
		<h3>**Sorry, no data for</h3>
	</div>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
	<script type="text/javascript">
		// hide chart in the begining
		var ctx = document.getElementById('myChart').getContext('2d');
		var myChart;	
		var myBarChart;
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
				},
		    }
		});

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
			myChart.options.onHover = function(e) {
				// jQuery("#myChart").css("cursor", e[0] ? "pointer" : "default");
				var point = this.getElementAtEvent(e);
		            if (point.length) e.target.style.cursor = 'pointer';
		            else e.target.style.cursor = 'default';
			};
			// add bar click event
			myChart.options.onClick = function(c,i) {
			    e = i[0];
			    var x_value = this.data.labels[e._index];
			    var y_value = this.data.datasets[0].data[e._index];
			    var url = <?php echo '"'.home_url().'/activity?my_variable="'; ?>+x_value;
			    //alert(url);
			    window.open(url);
			};
		}

		// update suburb chart
		function updateSubChart(rawdata){
			var label = [];
			var data = [];
			var myObj = rawdata;
			// calculate total population
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
			myChart.options.scales.yAxes.ticks = {
					min: 0,
	              	max: 50,
	               	callback: function(value){return value+ "%"}
               }
			myChart.options.title.text = 'Foreign Population Ratio in '+myObj[0].burbs;	
			myChart.update();
			// disable click function
			myChart.options.onClick = null;
		}	
	</script>
<?php  	
}

/* heat map module for community page*/
function heatmapGen() {?>		
	<div class="heatmapcontainer" id="heatmapcontainer" style="display: none;">
		<div id="heatmap"></div>
	</div>
	<script>
		var map, heatmap;
		function initMap() {
			map = new google.maps.Map(document.getElementById('heatmap'), {
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

    </script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAamXiqL6AsLUQSEpSdBps9uPgwo8i1oNs&libraries=visualization&callback=initMap">
    </script>
<?php  
}

/* initiate content block for first loading*/
function genBlock($results, $type) {
	if (count($results) != 0){
		foreach ($results as $rows) {
			echo '<div class="content-block"><button value="'.$rows->Latitude.' '.$rows->Longitude.'">';
			if ($type != 'none') {
				echo '<div class="block-type">'.$type.'</div>';
			}
			echo '<div class="content-title"><h3>'.$rows->Name.'</h3></div>';
			echo '<div class="content-description">';
			echo '<p><i class="fas fa-map-marker-alt"></i>  '.$rows->Address.'<br>';
			echo '<i class="fas fa-phone"></i> '.$rows->Contact.'<br>';
			echo '<a href="'.$rows->Website.'"><i class="fas fa-globe"></i> '.$rows->Website.'</a>';
			echo '</p></div>';
			echo '</button></div>';
		}
	}
}

/* activity page module */
function activityGen() {
	global $wpdb;
	$results;
	$total_results = array();
	// store suburb list for autocompletion
	$sublist = $wpdb->get_results( "SELECT DISTINCT burbs FROM final");
	?>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	<div class="activity-container">
	 	<div class="search-container" id="search-container">
			<div class="content-search">
				<form method="post" autocomplete="off">
					<div class="search-box">
						<div class="search-txt autocomplete">
						    <input id="s-suburb" type="text" name="s-suburb" placeholder="Suburb" value="<?php echo (isset($_GET['my_variable'])?$_GET['my_variable']:'') ?>">
						</div>
						<select name="s-category" id="search-list">
							
							<option value="yoga">Yoga</option>
							<option value="dance">Dance</option>
							<option value="sport">Sports</option>
							
							
						</select>
						<input type="button" name="s-submit" id="search-btn" value="Show">
					</div>
				</form>
			</div>	
		</div>
	 	<div class="side-container" id="side-container">
	 		
	 		<div class="content-wrap">
				<div class="content-display" id="content-display">
					<?php 	
					if (isset($_GET['my_variable']))
					{
						echo $_GET['my_variable'];
						$querylist = array('sport','dance', 'yoga');
						foreach ($querylist as $row) {
							$sub_result = $wpdb->get_results( "SELECT * FROM ". $row ." where TRIM(UPPER(Suburb)) = '".strtoupper($_GET['my_variable'])."'");
							if (count($sub_result) != 0){
								genBlock($sub_result, $row);
								$total_results = array_merge($total_results, $sub_result);
							}
						}
					}
					else {
						$total_results = $wpdb->get_results( "SELECT * FROM yoga");
						genBlock($total_results, 'none');
					}
					 ?>		
				</div>
			</div>
		</div>
		
		<div class="map-container" id="map-container">
			<div id="map"></div>
		</div>
		<div class="side-ctrl-container" id="side-ctrl-container">
			<button id="side-ctrl-btn" onclick="sideCtrl()">
				<i class="fas fa-angle-left" id="ctrl-icon"></i>
			</button>
		</div>
	</div>
	<!-- google map script -->
	<script type="text/javascript">
		// jQuery(document).ready(function() {
		// 	jQuery('select').niceSelect();
		// });

		function sideCtrl() {
			document.getElementById('map-container').classList.toggle('active');
			document.getElementById('side-container').classList.toggle('active');
			document.getElementById('search-container').classList.toggle('active');
			document.getElementById('side-ctrl-container').classList.toggle('active');
			if (document.getElementById("ctrl-icon").classList.contains('fa-angle-right')) {
				document.getElementById("ctrl-icon").classList.remove('fa-angle-right');
				document.getElementById("ctrl-icon").classList.add('fa-angle-left');
			}
			else {
				document.getElementById("ctrl-icon").classList.remove('fa-angle-left');
				document.getElementById("ctrl-icon").classList.add('fa-angle-right');
			}
		}

		// dynamicaly generate black content according to query
		function blockGen(data) {
			if (data == ''){
				jQuery('#content-display').html(
					'<div class="content-block"><button>'+
					'<div class="content-title"><h3>No data</h3></div>'+
					'<div class="content-description">'+
					'<p>Sorry, no data for ' + jQuery('#s-suburb').val() + '</p></div>'+
					'</button></div>'
					);
				clearMarker();
				clearCluster();
			}
			else {
				clearCluster();
				clearMarker(); // clear map marker;
				document.getElementById('content-display').innerHTML = ''; // clear content
				for (var i = 0; i < data.length; i++) {
					var block = document.createElement('div'); // create new block
					var blockbtn = document.createElement('button');
					block.classList.add('content-block');
					blockbtn.value = data[i].Latitude + ' ' + data[i].Longitude;
					blockbtn.classList.add('content-block-btn');
					if (jQuery('#search-list').val() != 'sport') {
						blockbtn.innerHTML = '<div class="content-title"><h3>'+data[i].Name+'</h3></div>'+
							'<div class="content-description">'+
							'<p><i class="fas fa-map-marker-alt"></i>  '+data[i].Address+'<br>'+
							'<i class="fas fa-phone"></i> '+data[i].Contact+'<br>'+
							'<a href="'+data[i].Website+'"><i class="fas fa-globe"></i> '+data[i].Website+'</a>'+
							'</p></div>';
					}
					else {
						blockbtn.innerHTML = '<div class="content-title"><h3>'+data[i].Name+'</h3></div>'+
							'<div class="content-description">'+
							'<p><i class="fas fa-map-marker-alt"></i> '+data[i].Address+'</p>'+
							'</div>';
					}
					block.appendChild(blockbtn);
					document.getElementById('content-display').appendChild(block);

					addMarker({lat:data[i].Latitude, lng:data[i].Longitude, name:data[i].Name, addr:data[i].Address, contact:data[i].Contact, web:data[i].Website});
					if (i == 200){
						break;
					}
				}
				addCluster();
			}
		}
		
		var map;
		var markers = [];
		var markerCluster;

		// block listener for map center change
		jQuery('#content-display').on('click', '.'+'content-block-btn', function() {
			var rawpos = jQuery(this).val();
			var pos = rawpos.split(" ");
			map.panTo({lat: parseFloat(pos[0]), lng: parseFloat(pos[1])});
		});

		// set the view port to selected marker on the map
		jQuery(".content-display button").click(function(){
			var rawpos = jQuery(this).val();
			var pos = rawpos.split(" ");
			map.panTo({lat: parseFloat(pos[0]), lng: parseFloat(pos[1])});
		});


		function addInfowindow(marker, pos) {
			// add event listener to zoom when click the marker
			google.maps.event.addListener(marker,'click',function() {
				var infowindow = new google.maps.InfoWindow({
				content: '<div class="cus-InfoWindow">' + 
						'<h3 id="cus-info-title">'+pos.name+'</h3><hr>' + 
						'<p><i class="fas fa-map-marker-alt"></i>  '+pos.addr+'<br>'+
							'<i class="fas fa-phone"></i> '+pos.contact+'<br>'+
							'<a href="'+pos.web+'"><i class="fas fa-globe"></i> '+pos.web+'</a>'+
							'</p></div>'
				});
				infowindow.open(map,marker);
				map.panTo(marker.getPosition());
				map.setZoom(16);
				google.maps.event.addListener(map, 'click', function() {
			    	infowindow.close();
				});
			});
		}

		function addMarker(pos) {
			// add a marker for geo location
			marker = new google.maps.Marker({
				position: new google.maps.LatLng(pos.lat, pos.lng),
				map: map,
				animation: google.maps.Animation.DROP,
				title: pos.name
			});
			// add info window
			addInfowindow(marker, pos);
			markers.push(marker);
		}

		function clearMarker() {
			// clear map markers
			for (var i = 0; i < markers.length; i++) {
				markers[i].setMap(null);
			}
			markers = [];
		}

		function addCluster() {
			markerCluster = new MarkerClusterer(map, markers,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m',maxZoom: 14});
		}

		function clearCluster() {
			markerCluster.clearMarkers();
		}

		function initMap() {
			var pos = getCenter();
			map = new google.maps.Map(document.getElementById('map'), {
			  zoom: pos.zoom,
			  center: {lat: pos.lat, lng: pos.lng},
			  mapTypeId: 'roadmap',
			  disableDefaultUI: true,
			  zoomControl: true,
			  fullscreenControl: true
			});
			initMarker();
			addCluster();
		}

		// change map center each for each query
		function setCenter(data) {
			if (data != null){
				map.panTo({lat: parseFloat(data[0].latitude), lng: parseFloat(data[0].longitude)});
				map.setZoom(15);
			}
			else {
				map.panTo({lat: -37.814, lng: 144.96332});
				map.setZoom(12);
			}
		}

		function initMarker() {
			<?php $count = 0; ?>
			<?php foreach ($total_results as $rows) {
				if ($count == 200) {break;}
				// add marker for each location
				echo 'addMarker({lat:'.$rows->Latitude.',lng:'.$rows->Longitude.',name:"'.$rows->Name.'",addr:"'.$rows->Address.'",contact:"'.$rows->Contact.'",web:"'.$rows->Website.'"});';
	      		$count++;
			} ?>
		}

		// get view port to the selected suburb  
		function getCenter(data) {
			<?php
			$center = array();
			if (!isset($_GET['my_variable'])) {
				$center = array('lat'=>-37.814, 'lng'=>144.96332, 'zoom'=>12);
			}
			else {
				global $wpdb;
				$geo = $wpdb->get_results( "SELECT * FROM geo where TRIM(UPPER(Suburbs)) = '".strtoupper($_GET['my_variable'])."'");
				foreach ($geo as $row) {
					$center['lat'] = $row->latitude;
					$center['lng'] = $row->longitude;
					$center['zoom'] = 14;
				}
			}?>
			return {lat:<?php echo $center['lat'] ?>, lng:<?php echo $center['lng'] ?>, zoom:<?php echo $center['zoom'] ?>};
		}
		
	</script>
	<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
    </script>
	<script async defer
		        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAamXiqL6AsLUQSEpSdBps9uPgwo8i1oNs&libraries=visualization&callback=initMap">
	</script>
	<!-- autocompletion script -->
	<script>
		/*An array containing all the country names in the world:*/
		var subs = [<?php foreach ($sublist as $row) {
			echo '"'.$row->burbs.'",';
		} ?>];

		/*initiate the autocomplete function on the "sub-input" element, and pass along the countries array as possible autocomplete values:*/
		autocomplete(document.getElementById("s-suburb"), subs);
	</script>
	<?php  
}

// restaurant page module
function restaurantGen() {
	global $wpdb;
	$sublist = $wpdb->get_results( "SELECT DISTINCT burbs FROM final");
	?>
	<div class="rest-userinput">
		<form autocomplete="off">
			<div class="autocomplete rest-subwrap">
				<input type="text" id="rest_sub" placeholder="Suburbs" required>
			</div>
			<div class="autocomplete rest-cuiwrap">
				<input type="text" id="rest_cuisine" placeholder="Cuisines" required>
			</div>
			<input type="button" id="rest_search" value="search">
			
		</form>
		<div class="sortwrap" style="width: 100%;margin-top: 10px;">
			Sort by 
			<select id="rest_sort" style="margin-right: 10px;">
				<option value="rating">Rating</option>
				<option value="cost">Cost</option>
			</select>
			Order
			<select id="rest_order">
				<option value="desc">Desc</option>
				<option value="asc">Asc</option>
			</select>
		</div>
	</div>
	<div style="width:100%;">
		<hr style="border-color: black;background-color: black">
	</div>
	<div class="restcontainer" id="restcontainer">
		<div class="preload">
			<img src="<?php echo plugin_dir_url( __FILE__ ) . 'img/giphy.gif'; ?>">
		</div>
		<div class="cardcontainer" id='cardcontainer'></div>
		<div class="rest-tips" style="color: black;">**Find all resturants serving a cuisine in a suburb</div>
		<div class="rest-error" style="display: none; color: black;"></div>
		<div class="rest-showmore" style="">
			<button onclick="showmore();">Show more</button>
		</div>
		<div class="rest-inputerror" style="display: none;color: black;">**Please input a <b>SUBURB</b> and a <b>CUISINE</b></div>
	</div>
	<script type="text/javascript">
		var imgLoc = "<?php echo plugin_dir_url( __FILE__ ) . 'img/'; ?>"; // default img location
		var loadinterval = 9; // data increment
		var loadlen; // which part of data to be load
		var wholedata; // store received data

		// first data loading from server
		function cardGen(data) {
			wholedata = data;
			datalen = data.length;
			loadlen = loadinterval;
			if (loadlen > datalen) {
				loadlen = datalen;
			}
			for (var i = 0; i < loadlen; i++) {
				createCard(data[i]);
			}
			jQuery(".preload").hide();
			if (loadlen < datalen){
				jQuery(".rest-showmore").show();
			}
		}

		// create each card
		function createCard(data) {
			var block = document.createElement('div'); // create new block
			var cardfront = document.createElement('div');
			var cardback = document.createElement('div');
			var rate = document.createElement('span');
			block.classList.add('rest-card');
			cardfront.classList.add('rest-cardfront');
			cardback.classList.add('rest-cardback');
			rate.classList.add('rest-rate');

			// front side
			if (data.restaurant.thumb != ''){
				cardfront.style.background = 'linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)), url('+data.restaurant.thumb+')';
				cardfront.style.backgroundSize = 'cover';
			}
			else {
				cardfront.style.background = 'linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)), url('+imgLoc+'/Restaurant.jpg)';
				cardfront.style.backgroundSize = 'cover';
			}
			cardfront.innerHTML = '<div class="frontcontent"><h1>'+data.restaurant.name+'</h1></div>'+
				// "<img src='"+data.restaurant.thumb+"' /><br/>"+
				// "<a href='"+data.restaurant.url+"'>Visit restaurant page</a><br/>"+
				'</div></div>';

			// back side
			cardback.innerHTML = '<div class="backcontent">'+
				'<h1>'+data.restaurant.name+'</h1>'+
				'<div class="backmid"><span class="type">CUISINES:</span><span class="value">'+data.restaurant.cuisines+'</span>'+
				'<span class="type">COST FOR TWO:</span><span class="value">A$'+data.restaurant.average_cost_for_two+'</span>'+
				'<span class="type">RARING:</span><span class="value">'+data.restaurant.user_rating.rating_text+'</span>'+
				'<span class="type">ADDRESS:</span><span class="value">'+data.restaurant.location.address+'</span></div>'+
				'<a href="'+data.restaurant.url+'">Visit restaurant page</a></div>';
			
			rate.innerHTML = data.restaurant.user_rating.aggregate_rating;
			rate.style.backgroundColor = '#'+data.restaurant.user_rating.rating_color;
			cardfront.appendChild(rate);
			block.appendChild(cardfront);
			block.appendChild(cardback);
			document.getElementById('cardcontainer').appendChild(block);
		}

		// show more result
		function showmore() {
			for (var i = loadlen; i < datalen; i++ ) {
				createCard(wholedata[i]);
			}
			loadlen += loadinterval;
			if (loadlen < datalen){
				
			}
			else {
				jQuery(".rest-showmore").hide();
			}
		}
	</script>
	<script type="text/javascript">
		var subs = [<?php foreach ($sublist as $row) {
			echo '"'.$row->burbs.'",';
		} ?>];
		var cuisines = [
			"Afghani","African","American","Arabian","Argentine","Asian","Asian Fusion","Australian","Austrian","BBQ","Bakery","Bangladeshi","Bar Food","Basque","Belgian","Beverages","Brazilian","British","Bubble Tea","Burger","Burmese","Cafe","Cafe Food","Cambodian","Cantonese","Caribbean","Charcoal Chicken","Chinese","Coffee and Tea","Colombian","Contemporary","Continental","Creole","Crepes","Croatian","Danish","Deli","Desserts","Drinks Only","Dumplings","Dutch","Eastern European","Egyptian","Ethiopian","European","Falafel","Fast Food","Filipino","Finger Food","Fish and Chips","French","Fried Chicken","Frozen Yogurt","Fusion","Georgian","German","Greek","Grill","Hawaiian","Healthy Food","Hot Pot","Hungarian","Ice Cream","Indian","Indonesian","International","Iranian","Iraqi","Irish","Israeli","Italian","Japanese","Japanese BBQ","Jewish","Juices","Kebab","Kiwi","Korean","Korean BBQ","Laotian","Latin American","Lebanese","Malaysian","Mauritian","Meat Pie","Mediterranean","Mexican","Middle Eastern","Modern Australian","Modern European","Mongolian","Moroccan","Nepalese","New Mexican","Oriental","Pakistani","Pan Asian","Parma","Pastry","Patisserie","Peruvian","Pho","Pizza","Poké","Polish","Portuguese","Pub Food","Ramen","Roast","Russian","Salad","Sandwich","Scandinavian","Scottish","Seafood","Shanghai","Sichuan","Singaporean","Soul Food","South African","South Indian","Spanish","SriLankan","Steak","Street Food","Sushi","Swedish","Swiss","Taiwanese","Tapas","Tea","Teppanyaki","Teriyaki","Tex-Mex","Thai","Tibetan","Turkish","Ukrainian","Uruguayan","Uyghur","Vegan","Vegetarian","Venezuelan","Vietnamese","Yum Cha"
		];

		autocomplete(document.getElementById("rest_sub"), subs);
		autocomplete(document.getElementById("rest_cuisine"), cuisines);
	</script>
	<?php  
}

add_shortcode("user-input", "getUserInput");
add_shortcode("graph-generator", "graphGen");
add_shortcode("map-generator", "heatmapGen");
add_shortcode("activity-generator", "activityGen");
add_shortcode("restaurant-generator", "restaurantGen");