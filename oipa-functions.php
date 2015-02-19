<?php 

function oipa_get_filters(){

	$search_url = OIPA_URL . "activity-filter-options/?format=json&reporting_organisation__in=" . DEFAULT_ORGANISATION_ID;
	$content = file_get_contents($search_url);
	$filter_options = json_decode($content);
	return $filter_options;
}

function oipa_get_activity($identifier) {

	if(empty($identifier)) return null;
	$search_url = OIPA_URL . "activities/{$identifier}?format=json";
	$content = @file_get_contents($search_url);
	if ($content === false) { return false; }
	$activity = json_decode($content);
	return $activity;
}

function oipa_generate_results(&$objects, &$total_count, $perspective = null){
	$search_url = OIPA_URL . "activities?format=json&fields=id,title,descriptions,recipient_countries,recipient_regions,sectors,participating_organisations,reporting_organisation,total_budget,iati_identifier,activity_dates,last_updated_datetime,activity_status";
    $search_url = oipa_filter_request($search_url);
    // var_dump($search_url);
	$content = file_get_contents($search_url);
	$result = json_decode($content);
	$total_count = $result->count;
	$objects = $result->results;
}

function oipa_generate_regional_results(&$objects, &$meta, $region_id, $offsetpar = ""){

	$search_url = OIPA_URL . "activities/?format=json&page_size=10&regions__in=" . $region_id;
	$search_url .= "&reporting_organisation__in=" . DEFAULT_ORGANISATION_ID;
	$content = file_get_contents($search_url);
	$result = json_decode($content);
	$meta = $result->meta;
	$objects = $result->objects;
}

function oipa_generate_country_results(&$country_activities){

	$search_url = OIPA_URL . "country-activities/?format=json";
    $search_url = oipa_filter_request($search_url);
	$content = file_get_contents($search_url);
	$country_activities = json_decode($content);
}

function oipa_generate_region_results(&$region_activities){

	$search_url = OIPA_URL . "region-activities/?format=json";
    $search_url = oipa_filter_request($search_url);
	$content = file_get_contents($search_url);
	$region_activities = json_decode($content);
}

function oipa_generate_sector_results(&$sector_activities){

	$search_url = OIPA_URL . "sector-activities/?format=json";
    $search_url = oipa_filter_request($search_url);
	$content = file_get_contents($search_url);
	$sector_activities = json_decode($content);
}

function oipa_generate_donor_results(&$donor_activities){

	$search_url = OIPA_URL . "donor-activities/?format=json";
    $search_url = oipa_filter_request($search_url);
	$content = file_get_contents($search_url);
	$donor_activities = json_decode($content);
}

function oipa_get_donor($donor_id, &$donor_info){
	$search_url = OIPA_URL . "donor-activities/?format=json&donors__in=" . $donor_id;
	$search_url .= "&reporting_organisation__in=" . DEFAULT_ORGANISATION_ID;
	$search_url = str_replace(" ", "%20", $search_url);
	$content = file_get_contents($search_url);
	$donor_info = json_decode($content);
}

function oipa_get_country($country_id, &$country_info, &$country){

	$search_url = OIPA_URL . "country-activities/?format=json&countries__in=" . $country_id;
	$search_url .= "&reporting_organisation__in=" . DEFAULT_ORGANISATION_ID;
	$content = file_get_contents($search_url);
	$country_info = json_decode($content);
	$country_url = OIPA_URL . "countries/" . $country_id . "/?format=json";
	$country_content = file_get_contents($country_url);
	$country = json_decode($country_content);
}

function oipa_get_region($region_id, &$region_info, &$region){

	$search_url = OIPA_URL . "region-activities/?format=json&regions__in=" . $region_id;
	$search_url .= "&reporting_organisation__in=" . DEFAULT_ORGANISATION_ID;
	$content = file_get_contents($search_url);
	$region_info = json_decode($content);
	$region_url = OIPA_URL . "regions/" . $region_id . "/?format=json";
	$region_content = file_get_contents($region_url);
	$region = json_decode($region_content);
}

function oipa_get_sector($sector_id, &$sector_info){

	$search_url = OIPA_URL . "sector-activities/?format=json&sectors__in=" . $sector_id;
	$search_url .= "&reporting_organisation__in=" . DEFAULT_ORGANISATION_ID;
	$content = file_get_contents($search_url);
	$sector_info = json_decode($content);
}

function oipa_generate_link_parameters($filter_to_unset=null){

	parse_str($_SERVER['QUERY_STRING'], $vars);
	if(isset($filter_to_unset)){
		if(isset($_GET[$filter_to_unset])){ unset($vars[$filter_to_unset]); }
	}
	if(isset($_GET['offset'])){ unset($vars['offset']); }
	if(isset($_GET['action'])){ unset($vars['action']); }
	$parameters = http_build_query($vars);
	$parameters = str_replace("%2C", ",", $parameters);
	if ($parameters){ $parameters = "&" . $parameters; }
	return $parameters;
}

function oipa_filter_request($search_url){

	$disallowed_parameters = array(
		"action",
		"call",
		"format"
	);

	foreach($_REQUEST as $key => $parameter){
		if (!in_array($key, $disallowed_parameters)){
			$search_url .= "&" . $key . "=" . $parameter;
		}
	}

	if (DEFAULT_ORGANISATION_ID && (strpos($search_url,'reporting_organisations=') === false)){
		$search_url .= "&reporting_organisations=" . DEFAULT_ORGANISATION_ID;
	}

    return $search_url;
}

function currencyCodeToSign($currency){
	switch ($currency) {
	    case "CAD":
	        return "CAD $ ";
	        break;
	    case "DDK":
	        return "DDK kr. ";
	        break;
	    case "EUR":
	        return "EUR ";
	        break;
	    case "GBP":
	        return "GBP £ ";
	        break;
	    case "INR":
	        return "INR Rs ";
	        break;
	    case "NOK":
	        return "NOK kr. ";
	        break;
	    case "NPR":
	        return "NPR Rs ";
	        break;
	    case "NZD":
	        return "NZD $ ";
	        break;
	    case "PKR":
	        return "PKR Rs ";
	        break;
	    case "USD":
	        return "US $ ";
	        break;
	    case "ZAR":
	        return "Rand ";
	        break;

    }
    return "Unknown/mixed currency - ";
}

function get_activity_id_in_wp(){
  if (isset($_GET['id'])){
    $id = $_GET['id'];
  } else if(isset($_GET['iati_id'])){
    $id = $_GET['iati_id'];
  } else {
     $url_parts = explode("/", $_SERVER["REQUEST_URI"]);
     $partamount = count($url_parts);
     $id = $url_parts[($partamount -2)];
  }

  return $id;
}

function get_oipa_field($field){
	if(!empty($field)) { 
		return $field; 
	} else {
		return "-";
	}
}

