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

function sportBlock($results, $type) {
    if (count($results) != 0){
        foreach ($results as $rows) {
            echo '<div class="content-block"><button value="'.$rows->Latitude.' '.$rows->Longitude.'">';
            if ($type != 'none') {
                echo '<div class="block-type">'.$type.'</div>';
            }
            echo '<div class="content-title"><h3>'.$rows->Name.'</h3></div>';
            echo '<div class="content-description">';
            echo '<p><i class="fas fa-map-marker-alt"></i>  '.$rows->Address.'<br>';

            echo '</p></div>';
            echo '</button></div>';
        }
    }
}

/* activity page module */
function yogaGen() {
    global $wpdb;
    $results;
    $total_results = array();
    // store suburb list for autocompletion
    $sublist = $wpdb->get_results( "SELECT DISTINCT Suburb FROM yoga");
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

                            $total_results = $wpdb->get_results( "SELECT * FROM yoga where TRIM(UPPER(Suburb)) = '".strtoupper($_GET['my_variable'])."'");
                            if (count($total_results) != 0){
                                genBlock($total_results, yoga);

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
                    '<p>Sorry, no data available now for' + jQuery('#s-suburb').val() + '</p></div>'+
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
            echo '"'.$row->Suburb.'",';
        } ?>];

        /*initiate the autocomplete function on the "sub-input" element, and pass along the countries array as possible autocomplete values:*/
        autocomplete(document.getElementById("s-suburb"), subs);
    </script>
    <?php
}

function danceGen() {
    global $wpdb;
    $results;
    $total_results = array();
    // store suburb list for autocompletion
    $sublist = $wpdb->get_results( "SELECT DISTINCT Suburb FROM dance");
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

                        $total_results = $wpdb->get_results( "SELECT * FROM dance where TRIM(UPPER(Suburb)) = '".strtoupper($_GET['my_variable'])."'");
                        if (count($total_results) != 0){
                            genBlock($total_results, yoga);

                        }

                    }
                    else {
                        $total_results = $wpdb->get_results( "SELECT * FROM dance");
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
                    '<p>Sorry, no data available now for ' + jQuery('#s-suburb').val() + '</p></div>'+
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
            echo '"'.$row->Suburb.'",';
        } ?>];

        /*initiate the autocomplete function on the "sub-input" element, and pass along the countries array as possible autocomplete values:*/
        autocomplete(document.getElementById("s-suburb"), subs);
    </script>
    <?php
}

function sportGen() {
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
                            <option value="aerobics">Aerobics</option>
                            <option value="basketball">Basketball</option>
                            <option value="cricket">Cricket</option>
                            <option value="fitness">Fitness Center & Gym</option>
                            <option value="golf">Golf</option>
                            <option value="netball">Netball</option>
                            <option value="rugby">Rugby</option>
                            <option value="soccer">Soccer & Football</option>
                            <option value="squash">Squash</option>
                            <option value="swimming">Swimming</option>
                            <option value="tennis">Tennis</option>
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
                        $querylist = array('aerobics','tennis', 'soccer','fitness','netball','golf','swimming','basketball','cricket','squash','rugby');
                        foreach ($querylist as $row) {
                            $sub_result = $wpdb->get_results( "SELECT * FROM ". $row ." where TRIM(UPPER(Suburb)) = '".strtoupper($_GET['my_variable'])."'");
                            if (count($sub_result) != 0){
                                genBlock($sub_result, $row);
                                $total_results = array_merge($total_results, $sub_result);
                            }
                        }

                    }
                    else {
                        $total_results = $wpdb->get_results( "SELECT * FROM aerobics");
                        sportBlock($total_results, 'none');
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
                    '<p>Sorry, no data available now for' + jQuery('#s-suburb').val() + '</p></div>'+
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
                    blockbtn.innerHTML = '<div class="content-title"><h3>'+data[i].Name+'</h3></div>'+
                        '<div class="content-description">'+
                        '<p><i class="fas fa-map-marker-alt"></i> '+data[i].Address+'</p>'+
                        '</div>';

                    block.appendChild(blockbtn);
                    document.getElementById('content-display').appendChild(block);

                    addMarker({lat:data[i].Latitude, lng:data[i].Longitude, name:data[i].Name, addr:data[i].Address, contact:data[i].Contact, web:data[i].Website, court:data[i].NumberFieldsCourts, surface:data[i].FieldSurfaceType});
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
                        '<i class="fas fa-info"></i>Number of Courts： '+pos.court+'<br>'+
                        '<i class="fas fa-info"></i>Filed Surface： '+pos.surface+'<br>'+
                        '<i class="fas fa-info"></i>Number of Courts： '+pos.court+'<br>'+
                        '<i class="fas fa-info"></i>Number of Courts： '+pos.court+'<br>'+
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
            echo 'addMarker({lat:'.$rows->Latitude.',lng:'.$rows->Longitude.',name:"'.$rows->Name.'",addr:"'.$rows->Address.'",contact:"'.$rows->Contact.'",web:"'.$rows->Website.'" ,court:"'.$rows->NumberFieldCourts.'",surface:"'.$rows->FieldSurfaceType.'" });';
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

function sport1Gen() {
    global $wpdb;
    $results;
    $total_results = array();
    // store suburb list for autocompletion
    $sublist = $wpdb->get_results( "SELECT DISTINCT burbs FROM final ");
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
                            <option value="aerobics">Aerobics</option>
                            <option value="basketball">Basketball</option>
                            <option value="cricket">Cricket</option>
                            <option value="fitness">Fitness Center & Gym</option>
                            <option value="golf">Golf</option>
                            <option value="netball">Netball</option>
                            <option value="rugby">Rugby</option>
                            <option value="soccer">Soccer & Football</option>
                            <option value="squash">Squash</option>
                            <option value="swimming">Swimming</option>
                            <option value="tennis">Tennis</option>
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
                        $querylist = array('aerobics','tennis', 'soccer','fitness','netball','golf','swimming','basketball','cricket','squash','rugby');
                        foreach ($querylist as $row) {
                            $sub_result = $wpdb->get_results( "SELECT * FROM ". $row ." where TRIM(UPPER(Suburb)) = '".strtoupper($_GET['my_variable'])."'");
                            if (count($sub_result) != 0){
                                genBlock($sub_result, $row);
                                $total_results = array_merge($total_results, $sub_result);
                            }
                        }

                    }
                    else {
                        $total_results = $wpdb->get_results( "SELECT * FROM aerobics");
                        sportBlock($total_results, 'none');
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

add_shortcode("yoga-generator", "yogaGen");
add_shortcode("dance-generator", "danceGen");
add_shortcode("sport-generator", "sportGen");