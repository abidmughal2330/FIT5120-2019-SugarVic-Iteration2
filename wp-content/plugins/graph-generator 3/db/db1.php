<?php 

// nationality handler
function getNat() {
	if ( isset( $_POST["post_var"] ) ) {
		global $wpdb;
		$results1 = $wpdb->get_results( "SELECT * FROM final WHERE Nationality = '".trim($_POST["post_var"])."'  ORDER BY Population DESC LIMIT 10");
		$results2 = $wpdb->get_results( "SELECT * FROM final WHERE Nationality = '".trim($_POST["post_var"])."'  ORDER BY Population DESC");
		$geo = $wpdb->get_results( "SELECT * FROM geo");
		$product = array($results1, $results2, $geo);
		echo json_encode($product);
		die();
	}
}
add_action('wp_ajax_nat_response', 'getNat');
add_action('wp_ajax_nopriv_nat_response', 'getNat');


function getSub() {
	if ( isset( $_POST["post_var"] ) ) {
		global $wpdb;
		$results = $wpdb->get_results( "SELECT * FROM final WHERE burbs = '".trim($_POST["post_var"])."' and Nationality <> 'Australia' ORDER BY Population DESC");
		echo json_encode($results);
		die();
	}
}
add_action('wp_ajax_sub_response', 'getSub');
add_action('wp_ajax_nopriv_sub_response', 'getSub');

// activity query handler 
function getInfo() {
	if ( isset( $_POST["post_var"] ) ) {
		$arr = explode(",",$_POST["post_var"]);
		$sub = trim($arr[0]);
		$cat = trim($arr[1]);
		$results;
		$geo;
		global $wpdb;
		if (trim($sub) != '') {
			if ($cat == 'dance')
				{$results = $wpdb->get_results( "SELECT * FROM dance where TRIM(UPPER(Suburb)) = '".strtoupper($sub)."'");}
			else if ($cat == 'yoga')
				{$results = $wpdb->get_results( "SELECT * FROM yoga where TRIM(UPPER(Suburb)) = '".strtoupper($sub)."'");}
			else if ($cat == 'cooking')
				{$results = $wpdb->get_results( "SELECT * FROM cooking where TRIM(UPPER(Suburb)) = '".strtoupper($sub)."'");}
			else if ($cat == 'community')
				{$results = $wpdb->get_results( "SELECT * FROM community where TRIM(UPPER(Suburb)) = '".strtoupper($sub)."'");}
			else if ($cat == 'sport')
				{$results = $wpdb->get_results( "SELECT * FROM sport where TRIM(UPPER(Suburb)) = '".strtoupper($sub)."'");}
			else {$results = $wpdb->get_results( "SELECT * FROM volunteer where TRIM(UPPER(Suburb)) = '".strtoupper($sub)."'");}
			$geo = $wpdb->get_results( "SELECT * FROM geo where TRIM(UPPER(Suburbs)) = '".strtoupper($sub)."'");
		}
		else {
			if ($cat == 'dance')
				{$results = $wpdb->get_results( "SELECT * FROM dance");}
			else if ($cat == 'yoga')
				{$results = $wpdb->get_results( "SELECT * FROM yoga");}
			else if ($cat == 'cooking')
				{$results = $wpdb->get_results( "SELECT * FROM cooking");}
			else if ($cat == 'community')
				{$results = $wpdb->get_results( "SELECT * FROM community");}
			else if ($cat == 'sport')
				{$results = $wpdb->get_results( "SELECT * FROM sport");}
			else {$results = $wpdb->get_results( "SELECT * FROM volunteer");}
		}
		$product = array($results, $geo);
		echo json_encode($product);
		die();
	}
}
add_action('wp_ajax_map_response', 'getInfo');
add_action('wp_ajax_nopriv_map_response', 'getInfo');

// restaurant query handler
function getRest() {
	if (isset( $_POST["post_var"] )) {
		$arr = explode(",",$_POST["post_var"]);
		$sub = $arr[0];
		$cuisine = $arr[1];
		$rating = $arr[2];
		$order = $arr[3];
		$result = getSubId($sub);
		if ($result == 'Error') {
			echo 'Error';
		}
		else {
			curl_close ($ch);
			$zomdata = $result;
			$placeid = $zomdata->location_suggestions[0]->entity_id;

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://developers.zomato.com/api/v2.1/search?entity_id=".$placeid."&entity_type=subzone&q=".$cuisine."&sort=".$rating."&order=".$order."&start=0&count=50");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
			$headers = array(
			"Accept: application/json",
			"User-Key: ffa11c7a5ea76d43651915534dd355f3"
			);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			$result = curl_exec($ch);

			if (curl_errno($ch)) {
				echo 'Error';
			}
			curl_close ($ch);
			$zomdata = json_decode($result);
			$zomrestaurants = $zomdata->restaurants;
			if (count($zomrestaurants) == 0) {
				echo "Error"; 
			}
			else{
				echo $result;
			}
		}
		die();
	}
}
// get suburb id
function getSubId($sub) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://developers.zomato.com/api/v2.1/locations?query=".$sub."%2C%20melbourne");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
	$headers = array(
	"Accept: application/json",
	"User-Key: ffa11c7a5ea76d43651915534dd355f3"
	);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);

	if (curl_errno($ch)) {
		curl_close ($ch);
		return 'Error';
	}
	$zomdata = json_decode($result);
	$location = $zomdata->location_suggestions;
	if (count($location) == 0){
		return 'Error';
	}
	curl_close ($ch);
	return $zomdata;
}

add_action('wp_ajax_rest_response', 'getRest');
add_action('wp_ajax_nopriv_rest_response', 'getRest');