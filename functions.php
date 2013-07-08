<?php 
include( TEMPLATEPATH .'/constants.php' );
	
// Add RSS links to <head> section
add_theme_support( 'automatic-feed-links' );

// WORDPRESS THEME FUNCTIONS
add_theme_support( 'menus' );

add_theme_support( 'post-thumbnails' );

function my_function_admin_bar(){ return false; }
add_filter( 'show_admin_bar' , 'my_function_admin_bar');

function register_my_menus() {
  register_nav_menus(
    array(
      'header-menu' => __( 'Header Menu' ),
      'footer-menu' => __( 'Footer Menu' )
    )
  );
}
add_action( 'init', 'register_my_menus' );

function unh_widgets_init() {
	register_sidebar(array(
		'name' => __( 'UN-Habitat page sidebar' ),
	  	'id' => 'unh-page-sidebar',
	    'before_widget' => '<div class="drop-shadow postit page-sidebar-item">',
	    'after_widget' => '</div>',
	    'before_title' => '<div class="postit-title hneue-light">',
	    'after_title' => '</div>',
	));
}
add_action( 'widgets_init', 'unh_widgets_init' );

function homepage_items_post_type() {
	$labels = array(
		'name'               => _x( 'Homepage items', 'Homepage items' ),
		'singular_name'      => _x( 'Homepage item', 'Homepage item' ),
		'add_new'            => _x( 'Add New', 'Homepage item' ),
		'add_new_item'       => __( 'Add New Homepage item' ),
		'edit_item'          => __( 'Edit Homepage item' ),
		'new_item'           => __( 'New Homepage item' ),
		'all_items'          => __( 'All Homepage items' ),
		'view_item'          => __( 'View Homepage item' ),
		'search_items'       => __( 'Search Homepage items' ),
		'not_found'          => __( 'No Homepage items found' ),
		'not_found_in_trash' => __( 'No Homepage items found in the Trash' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'Homepage'
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'Holds homepage items',
		'public'        => true,
		'menu_position' => 5,
		'supports'      => array( 'title', 'editor', 'thumbnail'),
		'has_archive'   => true,
		'taxonomies' 	=> array('category'),
	);
	register_post_type( 'homepage-item', $args );	
}
add_action( 'init', 'homepage_items_post_type' );

function faq_items_post_type() {
	$labels = array(
		'name'               => _x( 'FAQ items', 'FAQ items' ),
		'singular_name'      => _x( 'FAQ item', 'FAQ item' ),
		'add_new'            => _x( 'Add New', 'FAQ item' ),
		'add_new_item'       => __( 'Add New FAQ item' ),
		'edit_item'          => __( 'Edit FAQ item' ),
		'new_item'           => __( 'New FAQ item' ),
		'all_items'          => __( 'All FAQ items' ),
		'view_item'          => __( 'View FAQ item' ),
		'search_items'       => __( 'Search FAQ items' ),
		'not_found'          => __( 'No FAQ items found' ),
		'not_found_in_trash' => __( 'No FAQ items found in the Trash' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'FAQ'
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'Holds FAQ items',
		'public'        => true,
		'menu_position' => 6,
		'supports'      => array( 'title', 'editor', 'thumbnail'),
		'has_archive'   => true,
		'taxonomies' 	=> array('category'),
	);
	register_post_type( 'faq-item', $args );	
}
add_action( 'init', 'faq_items_post_type' );




function wp_generate_results_v2(&$objects, &$meta, $offsetpar = ""){
	global $_PER_PAGE;
	global $_DEFAULT_ORGANISATION_ID;

	// get amount of activities per page
	$activities_per_page = $_PER_PAGE;
	if(isset($_GET['per_page'])){	$activities_per_page = $_GET['per_page']; }

	// get offset
	$activities_offset = 0;
	if(isset($_REQUEST['offset'])){	$activities_offset = rawurlencode($_REQUEST['offset']);	}
	//if($offsetpar != ""){ $activities_offset = $offsetpar; }

	$search_url = SEARCH_URL . "activities/?format=json&limit=" . $activities_per_page . "&offset=" . $activities_offset."&organisations=".$_DEFAULT_ORGANISATION_ID;
    
    $search_url = wp_filter_request($search_url);
	$content = file_get_contents($search_url);
	$result = json_decode($content);
	$meta = $result->meta;
	$objects = $result->objects;
}


function wp_generate_paging($meta) {
	global $_PER_PAGE;
	$total_count = $meta->total_count;
	$offset = $meta->offset;
	$limit = $meta->limit;
	$per_page = $_PER_PAGE;
	if(isset($_GET['per_page'])){ $per_page = $_GET['per_page']; }
	$total_pages = ceil($total_count/$limit);
	$cur_page = $offset/$limit + 1;

	// range of num links to show
	$range = 2;

	$params = $_GET;

	$paging_block = "<ul class='menu pagination'>";

	// if not on page 1, don't show back links
	if ($cur_page > 1) {
	   // show << link to go back to page 1
	   //$params['offset'] = 0;
	   $paging_block .=  "<li><a href='?offset=0' class='start'><span>&laquo;</span></a></li>";
	   // get previous page num
	   $prevpage = $cur_page - 1;
	   // show < link to go back to 1 page
	   //$params['offset'] = $offset - $limit;
	   $paging_block .= "<li><a href='?offset=" . ($offset - $limit) . "' class='limitstart'><span>&larr; </span></a></li>";
	} // end if 

	if ($cur_page > (1 + $range)){
	   //$params['offset'] = 0;
	   $paging_block .= "<li><a href='?offset=0' class='page'><span>1</span></a></li>";
	}

	if ($cur_page > (2 + $range)){
	   $paging_block .= "<li>...</li>";
	}

	// loop to show links to range of pages around current page
	for ($x = ($cur_page - $range); $x < (($cur_page + $range) + 1); $x++) {
	   // if it's a valid page number...
	   if (($x > 0) && ($x <= $total_pages)) {
	      // if we're on current page...
	      if ($x == $cur_page) {
	         // 'highlight' it but don't make a link
	         $paging_block .= "<li><a class='active'>$x</a></li>";
	      // if not current page...
	      } else {
	         // make it a link
	      	 //$params['offset'] = ($x*$per_page) - $per_page;
	      	 $paging_block .= "<li><a href='?offset=" . (($x*$per_page) - $per_page) . "' class='page'><span>$x</span></a></li>";
	      } // end else
	   } // end if 
	} // end for

	if($cur_page < ($total_pages - (1 + $range))){
	    $paging_block .= "<li>...</li>";
	}

	if($cur_page < ($total_pages - $range)){
	   //$params['offset'] = $total_count - ($total_count % $per_page);
	   $paging_block .= "<li><a href='?offset=" . ($total_count - ($total_count % $per_page)) . "' class='page'><span>$total_pages</span></a></li>";
	}
	               
	// if not on last page, show forward and last page links        
	if ($cur_page != $total_pages) {

	   // get next page
	   $nextpage = $cur_page + 1;
	    // echo forward link for next page 
	   //$params['offset'] = $offset + $limit;
	   $paging_block .= "<li><a href='?offset=" . ($offset + $limit) . "' class='endmilit'><span>&rarr; </span></a></li>";
	   //http_build_query($params)
	   //$params['offset'] = $total_count - ($total_count % $per_page);
	   $paging_block .= "<li><a href='?offset=" . ($total_count - ($total_count % $per_page)) . "' class='end'><span>&raquo;</span></a></li>";
	} // end if
	/****** end build pagination links ******/


	$paging_block .= "</ul>";


	echo $paging_block; 
}



	
	
add_filter( 'request', 'my_request_filter' );
function my_request_filter( $query_vars ) {
	if( isset( $_GET['s'] ) && empty( $_GET['s'] ) ) {
		$query_vars['s'] = " ";
	}
	return $query_vars;
}


function wp_filter_request($search_url){

    if(!empty($_REQUEST['countries'])) {
		$countries = explode('|', trim($_REQUEST['countries']));
		foreach($countries AS &$c) $c = trim($c);
		$countries = implode('|', $countries);
		$search_url .= "&countries={$countries}";
		$has_filter = true;
		if(!empty($srch_countries)) {
			$search_url .= "|{$srch_countries}";
		}
	} else {
		if(!empty($srch_countries)) {
			$search_url .= "&countries={$srch_countries}";
			$has_filter = true;
		}	
	}
	
	if(!empty($_REQUEST['regions'])) {
		$regions = explode('|', trim($_REQUEST['regions']));
		foreach($regions AS &$c) $c = trim($c);
		$regions = implode('|', $regions);
		$search_url .= "&regions={$regions}";
		$has_filter = true;
	}

	if(!empty($_REQUEST['query'])) {
		// $regions = explode('|', trim($_REQUEST['regions']));
		// foreach($regions AS &$c) $c = trim($c);
		// $regions = implode('|', $regions);
		$search_url .= "&query=".$_REQUEST['query'];
		$has_filter = false;
	}
	
	if(!empty($_REQUEST['sectors'])) {
		$sectors = explode('|', trim($_REQUEST['sectors']));
		foreach($sectors AS &$c) $c = trim($c);
		$sectors = implode('|', $sectors);
		$search_url .= "&sectors={$sectors}";
		$has_filter = true;
	}
	
	if(!empty($_REQUEST['budgets'])) {
		$budgets = explode('|', trim($_REQUEST['budgets']));
		//Get the lowest budget from filter and use this one, all the other are included in the range
		ksort($budgets);
		$search_url .= "&statistics__total_budget__gt={$budgets[0]}";
		$has_filter = true;
	}

	if(!empty($_REQUEST['order_by'])){
		$orderby = trim($_REQUEST['order_by']);
		$search_url .= "&order_by={$orderby}";
	}

    return $search_url;
}

function wp_get_activities() {
//	if(empty($identifier)) return null;
	$search_url = SEARCH_URL . "activities/?format=json&limit=200&organisations=41120";//
        
        $search_url = wp_filter_request($search_url);
	
	
	
	$content = file_get_contents($search_url);
        
	$result = json_decode($content);
        $objects = $result->objects;
        return objectToArray($objects);
      
	

}

function wp_get_activity($identifier) {
	if(empty($identifier)) return null;
	$search_url = SEARCH_URL . "activities/{$identifier}/?format=json";
	
	$content = file_get_contents($search_url);
	$activity = json_decode($content);
	return $activity;

}

function format_custom_number($num) {
	
	$s = explode('.', $num);

	$parts = "";
	if(strlen($s[0])>3) {
		$parts = "." . substr($s[0], strlen($s[0])-3, 3);
		$s[0] = substr($s[0], 0, strlen($s[0])-3);
		
		if(strlen($s[0])>3) {
			$parts = "." . substr($s[0], strlen($s[0])-3, 3) . $parts;
			$s[0] = substr($s[0], 0, strlen($s[0])-3);
			if(strlen($s[0])>3) {
				$parts = "." . substr($s[0], strlen($s[0])-3, 3) . $parts;
				$s[0] = substr($s[0], 0, strlen($s[0])-3);
			} else {
				$parts = $s[0] . $parts;
			}
		} else {
			$parts = $s[0] . $parts;
		}
	} else {
		$parts = $s[0] . $parts;
	}
	
	
	$ret = $parts;
	
	if(isset($s[1])) {
		if($s[1]!="00") {
			$ret .= "," . $s[1];
		}
	}
	
	return $ret;
}

function wp_get_regions() {
	
	$search_url = SEARCH_URL . "region/?format=json&limit=10";
	
	$content = file_get_contents($search_url);
	$result = json_decode($content);
	$objects = $result->objects;
	return objectToArray($objects);

}
function wp_get_countries($region) {
	
	$search_url = SEARCH_URL . "country/?format=json&limit=250&region=".$region;
	
	$content = file_get_contents($search_url);
	$result = json_decode($content);
	$objects = $result->objects;
	return objectToArray($objects);

}
function wp_get_cities($country) {
	
	$search_url = SEARCH_URL . "city/?format=json&limit=200&country=".$country;
	
	$content = file_get_contents($search_url);
	$result = json_decode($content);
	$objects = $result->objects;
	return objectToArray($objects);

}
function wp_get_indicator_results($region = '', $country = '', $year = ''){
    //        $search_url = SEARCH_URL . "indicators-country/?format=json&limit=6000&year=".$year."&region=".$region."&country=".$country;
        if (strlen($region)>0){
            $region = 'regions='.$region.'&';
        }else{
            $region = '';
        }
        if (strlen($country)>0){
            $country = 'iso='.$country.'&';
        }else{
            $country = '';
        }
        if (strlen($year)>0){
            $year = 'year='.$year.'&';
        }else{
            $year = '';
        }
        $search_url = SEARCH_URL . "indicators-country/?format=json&limit=600&".$region.$country.$year;
	
	$content = file_get_contents($search_url);
	$result = json_decode($content);
	$objects = $result->objects;
	return objectToArray($objects);
}
function wp_get_relevant_results($indicator = ''){
    //        $search_url = SEARCH_URL . "indicators-country/?format=json&limit=6000&year=".$year."&region=".$region."&country=".$country;
        if (strlen($region)>0){
            $region = 'regions='.$region.'&';
        }else{
            $region = '';
        }
        if (strlen($country)>0){
            $country = 'iso='.$country.'&';
        }else{
            $country = '';
        }
        if (strlen($indicator)>0){
            $indicator = $indicator;
        }else{
            $indicator = '';
        }
       
        $search_url = SEARCH_URL . "indicators-country/?format=json&limit=600";
	
	$content = file_get_contents($search_url);
	$result = json_decode($content);
	$objects = $result->objects;
	$all_objects = objectToArray($objects);
        
        $return_objs = array();
        foreach ($all_objects as $obj){
            
            if (strlen($obj[$indicator]>0)){
                array_push($return_objs, $obj);
            }
        }
//        print_r($return_objs);
        
        return $return_objs;
}   
function wp_get_unique_result($array, $value){
    $temp = array();
    foreach($array as $a){
        
        array_push($temp, $a[$value]);
    }
    return array_unique($temp, 1);
}

function wp_generate_filter_html( $filter, $limit = 4 ) {
	$limit = intval($limit);
	if($limit<=0) $limit = 4;
	
	$filter = strtoupper($filter);
	
	$return = "<ul>";
	$add_more = false;
	$generate_popup = false;
	switch($filter) {
		case 'COUNTRY':
			global $_COUNTRY_ISO_MAP;
			if(empty($_COUNTRY_ISO_MAP) && !file_exists(TEMPLATEPATH . '/countries.php')) {
				wp_generate_constants();
				include_once( TEMPLATEPATH . '/countries.php' );
				asort($_COUNTRY_ISO_MAP);
			}
			$_data = $_COUNTRY_ISO_MAP;
			$selected = array();
			if(isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
				$query = rawurlencode($_REQUEST['s']);
				$srch_countries = array_map('strtolower', $_COUNTRY_ISO_MAP);
				$srch_countries = array_flip($srch_countries);
				$key = strtolower($query);
				if(isset($srch_countries[$key])) {
					$srch_countries = $srch_countries[$key];
				} else {
					$srch_countries = null;
				}
			}
			
			if(!empty($_REQUEST['countries'])) {
				$tmp = explode('|', $_REQUEST['countries']);
				foreach($tmp AS &$s) {
					$selected[$s] = $_COUNTRY_ISO_MAP[$s];
				}
				
				if(!empty($srch_countries) && !isset($selected[$srch_countries])) {
					$selected[$srch_countries] = $_COUNTRY_ISO_MAP[$srch_countries];
				}
				
				if(count($selected)>$limit) {
					$limit=count($selected);
					$_data = array_diff($_data, $selected);
				} else {
					$limit -= count($selected);
					$_data = array_diff($_data, $selected);
				}
			} else {
				if(!empty($srch_countries)) {
					$selected[$srch_countries] = $_COUNTRY_ISO_MAP[$srch_countries];
				}
				
				if(count($selected)>$limit) {
					$limit=count($selected);
					$_data = $selected;
				} else {
					$limit -= count($selected);
					$_data = array_diff($_data, $selected);
				}
			}
			
			$cnt = 1;
			$checked = "";
			if(empty($selected)) $checked = "checked=\"checked\"";
			$return .= "<li>
							<label for=\"id_countries_{$cnt}\"><input name=\"countries\" value=\"All\" id=\"id_countries_{$cnt}\" type=\"checkbox\" {$checked} />All</label>
						</li>";
			if(!empty($selected)) {
				foreach($selected AS $iso=>$c) {
					$checked = "checked=\"checked\"";
					$cnt++;
					$return .= "<li>
									<label for=\"id_countries_{$cnt}\"><input name=\"countries\" value=\"{$iso}\" id=\"id_countries_{$cnt}\" type=\"checkbox\" {$checked} /><span>{$c}</span></label>
								</li>";
				}
				
				$limit+=$cnt-1;
			}
			foreach($_data AS $iso=>$c) {
				$checked = "";
				if(isset($selected[$iso])) $checked = "checked=\"checked\"";
				$cnt++;
				$return .= "<li>
							<label for=\"id_countries_{$cnt}\"><input name=\"countries\" value=\"{$iso}\" id=\"id_countries_{$cnt}\" type=\"checkbox\" {$checked} /><span>{$c}</span></label>
						</li>";
				if($cnt>$limit) break;
			}
			if($limit<count($_COUNTRY_ISO_MAP)) {
				$add_more = true;
				$generate_popup = true;
			}

			break;
		case 'REGION':
			global $_REGION_CHOICES;
			if(empty($_REGION_CHOICES) && !file_exists(TEMPLATEPATH . '/regions.php')) {
				wp_generate_constants();
				include_once( TEMPLATEPATH . '/regions.php' );
				asort($_REGION_CHOICES);
			}
			$_data = $_REGION_CHOICES;

			$selected = array();
			if(!empty($_REQUEST['regions'])) {
				$tmp = explode('|', $_REQUEST['regions']);
				foreach($tmp AS &$s) {
					$selected[$s] = $_REGION_CHOICES[$s];
				}
				
				if(count($selected)>$limit) {
					$limit=count($selected);
					$_data = $selected;
				} else {
					$limit -= count($selected);
					$_data = array_diff($_data, $selected);
				}
			}
			
			$cnt = 1;
			$checked = "";
			if(empty($selected)) $checked = "checked=\"checked\"";
			$return .= "<li>
							<label for=\"id_regions_{$cnt}\"><input name=\"regions\" value=\"All\" id=\"id_regions_{$cnt}\" type=\"checkbox\" {$checked} />All</label>
						</li>";
			if(!empty($selected)) {
				foreach($selected AS $iso=>$c) {
					$checked = "checked=\"checked\"";
					$cnt++;
					$return .= "<li>
									<label for=\"id_regions_{$cnt}\"><input name=\"regions\" value=\"{$iso}\" id=\"id_regions_{$cnt}\" type=\"checkbox\" {$checked} /><span>{$c}</span></label>
								</li>";
				}
				
				$limit+=$cnt-1;
			}
			foreach($_data AS $iso=>$c) {
				$checked = "";
				if(isset($selected[$iso])) $checked = "checked=\"checked\"";
				$cnt++;
				$return .= "<li>
							<label for=\"id_regions_{$cnt}\"><input name=\"regions\" value=\"{$iso}\" id=\"id_regions_{$cnt}\" type=\"checkbox\" {$checked} /><span>{$c}</span></label>
						</li>";
				if($cnt>$limit) break;
			}
			
			if($limit<count($_REGION_CHOICES)) {
				$add_more = true;
				$generate_popup = true;
			}

			break;
		case 'SECTOR':
			global $_SECTOR_CHOICES;
			if(empty($_SECTOR_CHOICES) && !file_exists(TEMPLATEPATH . '/sectors.php')) {
				wp_generate_constants();
				include_once( TEMPLATEPATH . '/sectors.php' );
				asort($_SECTOR_CHOICES);
			}
			$_data = $_SECTOR_CHOICES;
			$selected = array();
			if(!empty($_REQUEST['sectors'])) {
				$tmp = explode('|', $_REQUEST['sectors']);
				foreach($tmp AS &$s) {
					$selected[$s] = $_SECTOR_CHOICES[$s];
				}
				
				if(count($selected)>$limit) {
					$limit=count($selected);
					$_data = $selected;
				} else {
					$limit -= count($selected);
					$_data = array_diff($_data, $selected);
				}
			}
			
			$cnt = 1;
			$checked = "";
			if(empty($selected)) $checked = "checked=\"checked\"";
			$return .= "<li>
							<label for=\"id_sectors_{$cnt}\"><input name=\"sectors\" value=\"All\" id=\"id_sectors_{$cnt}\" type=\"checkbox\" {$checked} />All</label>
						</li>";
			if(!empty($selected)) {
				foreach($selected AS $iso=>$c) {
					$checked = "checked=\"checked\"";
					$cnt++;
					$return .= "<li>
									<label for=\"id_sectors_{$cnt}\"><input name=\"sectors\" value=\"{$iso}\" id=\"id_sectors_{$cnt}\" type=\"checkbox\" {$checked} /><span>{$c}</span></label>
								</li>";
				}
				
				$limit+=$cnt-1;
			}
			foreach($_data AS $iso=>$c) {
				$checked = "";
				if(isset($selected[$iso])) $checked = "checked=\"checked\"";
				$cnt++;
				$return .= "<li>
							<label for=\"id_sectors_{$cnt}\"><input name=\"sectors\" value=\"{$iso}\" id=\"id_sectors_{$cnt}\" type=\"checkbox\" {$checked} /><span>{$c}</span></label>
						</li>";
				if($cnt>$limit) break;
			}
			
			if($limit<count($_SECTOR_CHOICES)) {
				$add_more = true;
				$generate_popup = true;
			}

			break;
		case 'BUDGET':
			global $_BUDGET_CHOICES;
			$_data = $_BUDGET_CHOICES;
			$limit=6; //Show all
			$selected = array();
			if(!empty($_REQUEST['budgets'])) {
				$tmp = explode('|', $_REQUEST['budgets']);
				foreach($tmp AS &$s) {
					$selected[$s] = $_BUDGET_CHOICES[$s];
				}
				
				if(count($selected)>$limit) {
					$limit=count($selected);
					$_data = $selected;
				}
			}
			
			$cnt = 1;
			$checked = "";
			if(empty($selected)) $checked = "checked=\"checked\"";
			$return .= "<li>
							<label for=\"id_budgets_{$cnt}\"><input name=\"budgets\" value=\"All\" id=\"id_budgets_{$cnt}\" type=\"checkbox\" {$checked} />All</label>
						</li>";
			foreach($_data AS $iso=>$c) {
				$checked = "";
				if(isset($selected[$iso])) $checked = "checked=\"checked\"";
				$cnt++;
				$return .= "<li>
							<label for=\"id_budgets_{$cnt}\"><input name=\"budgets\" value=\"{$iso}\" id=\"id_budgets_{$cnt}\" type=\"checkbox\" {$checked} /><span>{$c}</span></label>
						</li>";
				if($cnt>$limit) break;
			}
			
			if($limit<count($_BUDGET_CHOICES)) {
				$add_more = true;
				$generate_popup = true;
			}

			break;
		default:
			
			break;
	}
	$return .= "</ul>";
	if($add_more) {
		$href = strtolower($filter) . '_popup';
		$return .= "<a class=\"overlay\" href=\"#{$href}\">+ SEE ALL</a>";
	}
	return $return;
	
}

function wp_generate_filter_popup($filter, $limit = 4 ) {
	$limit = intval($limit);
	if($limit<=0) $limit = 4;
	$base_url = get_option('home');
	if(isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
		$query = str_replace(" ","+",$_REQUEST['s']);
	}
	
	$return = '	<div id="__FILTERID__" class="nodisp">
						<div style="position:relative;">
							<div class="submtBtns rounded-corners" id="popupsubmtBtn">
								<a href="'.$base_url.'/?s='.$query.'">__FILTERDESC__</a>
							</div>
						';
	
	$filter = strtoupper($filter);
	
	switch($filter) {
		case 'COUNTRY':
			global $_COUNTRY_ISO_MAP;
			if(empty($_COUNTRY_ISO_MAP) && !file_exists(TEMPLATEPATH . '/countries.php')) {
				wp_generate_constants();
				include_once( TEMPLATEPATH . '/countries.php' );
				asort($_COUNTRY_ISO_MAP);
			}
			
			$selected = array();
			if(!empty($_REQUEST['countries'])) {
				$tmp = explode('|', $_REQUEST['countries']);
				foreach($tmp AS &$s) {
					$selected[$s] = $_COUNTRY_ISO_MAP[$s];
				}
			}
			
			$return = preg_replace("/__FILTERID__/", strtolower($filter)."_popup", $return);
			$return = preg_replace("/__FILTERDESC__/", "SEARCH", $return);
			
			$checked = "";
			if(empty($selected)) $checked = "checked=\"checked\"";
			$return .= '<div class="countrieslist">
							<div class="all">
								<label for="id_countries_0"><input name="countries" value="All" id="id_countries_0" type="checkbox" '.$checked.' />All</label>
							</div>';
			
			$fltr_cnt = count($_COUNTRY_ISO_MAP);
			
			if($fltr_cnt%4!=0) $fltr_cnt++;
			while($fltr_cnt%4!=0) {
				$fltr_cnt++;
			}
			
			$items_per_col = $fltr_cnt/4;
			$cnt = 0;
			$return .= "<div class=\"col\"><ul>";
			if(!empty($_COUNTRY_ISO_MAP)) {
				foreach($_COUNTRY_ISO_MAP AS $iso=>$c) {
					$checked = "";
					if(isset($selected[$iso])) $checked = "checked=\"checked\"";
					$cnt++;
					$return .= "<li>
								<input name=\"countries\" id=\"check-country{$cnt}\" class=\"check\" type=\"checkbox\" value=\"{$iso}\" {$checked} />
								<label for=\"check-country{$cnt}\">{$c}</label>
							</li>";
					if($cnt%$items_per_col==0) {
						$return .= "</ul></div><div class=\"col\"><ul>";
					}
				}
			}
			$return .= "</ul></div>";
			$return .= "<div class=\"clr\"></div>";
			
			
			break;
		case 'REGION':
			global $_REGION_CHOICES;
			if(empty($_REGION_CHOICES) && !file_exists(TEMPLATEPATH . '/regions.php')) {
				wp_generate_constants();
				include_once( TEMPLATEPATH . '/regions.php' );
				asort($_REGION_CHOICES);
			}
			
			$selected = array();
			if(!empty($_REQUEST['regions'])) {
				$tmp = explode('|', $_REQUEST['regions']);
				foreach($tmp AS &$s) {
					$selected[$s] = $_REGION_CHOICES[$s];
				}
			}
			
			$return = preg_replace("/__FILTERID__/", strtolower($filter)."_popup", $return);
			$return = preg_replace("/__FILTERDESC__/", "SEARCH", $return);
			
			$checked = "";
			if(empty($selected)) $checked = "checked=\"checked\"";
			$return .= '<div class="regionslist">
							<div class="all">
								<label for="id_regions_0"><input name="regions" value="All" id="id_regions_0" type="checkbox" '.$checked.' />All</label>
							</div>';
			
			$fltr_cnt = count($_REGION_CHOICES);
			
			if($fltr_cnt%4!=0) $fltr_cnt++;
			while($fltr_cnt%4!=0) {
				$fltr_cnt++;
			}
			
			$items_per_col = $fltr_cnt/4;
			$cnt = 0;
			$return .= "<div class=\"col\"><ul>";
			if(!empty($_REGION_CHOICES)) {
				foreach($_REGION_CHOICES AS $iso=>$c) {
					$checked = "";
					if(isset($selected[$iso])) $checked = "checked=\"checked\"";
					$cnt++;
					$return .= "<li>
								<input name=\"regions\" id=\"check-region{$cnt}\" class=\"check\" type=\"checkbox\" value=\"{$iso}\" {$checked} />
								<label for=\"check-region{$cnt}\">{$c}</label>
							</li>";
					if($cnt%$items_per_col==0) {
						$return .= "</ul></div><div class=\"col\"><ul>";
					}
				}
			}
			$return .= "</ul></div>";
			$return .= "<div class=\"clr\"></div>";
			
			
			break;
			
		case 'SECTOR':
			global $_SECTOR_CHOICES;
			if(empty($_SECTOR_CHOICES) && !file_exists(TEMPLATEPATH . '/sectors.php')) {
				wp_generate_constants();
				include_once( TEMPLATEPATH . '/sectors.php' );
				asort($_SECTOR_CHOICES);
			}
			
			$selected = array();
			if(!empty($_REQUEST['sectors'])) {
				$tmp = explode('|', $_REQUEST['sectors']);
				foreach($tmp AS &$s) {
					$selected[$s] = $_SECTOR_CHOICES[$s];
				}
			}
			
			$return = preg_replace("/__FILTERID__/", strtolower($filter)."_popup", $return);
			$return = preg_replace("/__FILTERDESC__/", "SEARCH", $return);
			
			$checked = "";
			if(empty($selected)) $checked = "checked=\"checked\"";
			$return .= '<div class="sectorslist">
							<div class="all">
								<label for="id_sectors_0"><input name="sectors" value="All" id="id_sectors_0" type="checkbox" '.$checked.' />All</label>
							</div>';
			
			$fltr_cnt = count($_SECTOR_CHOICES);
			
			if($fltr_cnt%4!=0) $fltr_cnt++;
			while($fltr_cnt%4!=0) {
				$fltr_cnt++;
			}
			
			$items_per_col = $fltr_cnt/4;
			$cnt = 0;
			$return .= "<div class=\"col\"><ul>";
			if(!empty($_SECTOR_CHOICES)) {
				foreach($_SECTOR_CHOICES AS $iso=>$c) {
					$checked = "";
					if(isset($selected[$iso])) $checked = "checked=\"checked\"";
					$cnt++;
					$return .= "<li>
								<input name=\"sectors\" id=\"check-sector{$cnt}\" class=\"check\" type=\"checkbox\" value=\"{$iso}\" {$checked} />
								<label for=\"check-sector{$cnt}\">{$c}</label>
							</li>";
					if($cnt%$items_per_col==0) {
						$return .= "</ul></div><div class=\"col\"><ul>";
					}
				}
			}
			$return .= "</ul></div>";
			$return .= "<div class=\"clr\"></div>";
			
			
			break;
		case 'BUDGET':
			global $_BUDGET_CHOICES;
			
			if($limit>=count($_BUDGET_CHOICES)) {
				return "";
			}
			
			$selected = array();
			if(!empty($_REQUEST['budgets'])) {
				$tmp = explode('|', $_REQUEST['budgets']);
				foreach($tmp AS &$s) {
					$selected[$s] = $_BUDGET_CHOICES[$s];
				}
			}
			
			$return = preg_replace("/__FILTERID__/", strtolower($filter)."_popup", $return);
			$return = preg_replace("/__FILTERDESC__/", "SEARCH", $return);
			
			$checked = "";
			if(empty($selected)) $checked = "checked=\"checked\"";
			$return .= '<div class="budgetlist">
							<div class="all">
								<label for="id_budgets_0"><input name="budgets" value="All" id="id_budgets_0" type="checkbox" '.$checked.' />All</label>
							</div>';
			
			$fltr_cnt = count($_BUDGET_CHOICES);
			
			if($fltr_cnt%4!=0) $fltr_cnt++;
			while($fltr_cnt%4!=0) {
				$fltr_cnt++;
			}
			
			$items_per_col = $fltr_cnt/4;
			$cnt = 0;
			$return .= "<div class=\"col\"><ul>";
			foreach($_BUDGET_CHOICES AS $iso=>$c) {
				$checked = "";
				if(isset($selected[$iso])) $checked = "checked=\"checked\"";
				$cnt++;
				$return .= "<li>
							<input name=\"budgets\" id=\"check-budget{$cnt}\" class=\"check\" type=\"checkbox\" value=\"{$iso}\" {$checked} />
							<label for=\"check-budget{$cnt}\">{$c}</label>
						</li>";
				if($cnt%$items_per_col==0) {
					$return .= "</ul></div><div class=\"col\"><ul>";
				}
			}
			$return .= "</ul></div>";
			$return .= "<div class=\"clr\"></div>";
			
			
			break;
		default:
			
			break;
	}
	
	
					
					
	$return .= '		</div>
					</div>
				</div>';
	
	return $return;
}

function wp_generate_constants() {
	global $_DEFAULT_ORGANISATION_ID;
	$limit = 50;
	$countries = array();
	$sectors = array();
	$regions = array();
	$budgets = array();
	$countries_activities = array();
	$total_budget = 0;
	
	$activities_url = SEARCH_URL . "activities/?format=json&limit={$limit}";
	if(!empty($_DEFAULT_ORGANISATION_ID)) {
		$activities_url .= "&organisations=" . $_DEFAULT_ORGANISATION_ID;
	}
	$content = file_get_contents($activities_url);
	$result = json_decode($content);
	$meta = $result->meta;
	$count = $meta->total_count;
	
	$objects = $result->objects;
	$activities = objectToArray($objects);
	
	foreach($activities AS $a) {
		if(!empty($a['recipient_country'])) {
			foreach($a['recipient_country'] AS $c) {
				$countries_activities[$c['iso']] += 1;
				if(isset($countries[$c['iso']])) continue;
				$countries[$c['iso']] = $c['name'];
				
				if(!isset($budgets[$c['iso']])) {
					$budgets[$c['iso']] = $a['statistics']['total_budget'];
				} else {
					$budgets[$c['iso']] += $a['statistics']['total_budget'];
				}
				$total_budget += $a['statistics']['total_budget'];
			}
		}
		if(!empty($a['activity_sectors'])) {
			foreach($a['activity_sectors'] AS $s) {
				if(isset($sectors[$s['code']])) continue;
				$sectors[$s['code']] = $s['name'];
			}
		}
		if(!empty($a['recipient_region'])) {
			foreach($a['recipient_region'] AS $r) {
				if(isset($regions[$r['code']])) continue;
				$regions[$r['code']] = $r['name'];
			}
		}
	}
	
	
	$start=$limit;
	while($start<$count) {
		$activities_url = SEARCH_URL . "activities/?format=json&start={$start}&limit={$limit}";
		if(!empty($_DEFAULT_ORGANISATION_ID)) {
			$activities_url .= "&organisations=" . $_DEFAULT_ORGANISATION_ID;
		}
		$content = file_get_contents($activities_url);
		$result = json_decode($content);
		$objects = $result->objects;
		$activities = objectToArray($objects);
		
		foreach($activities AS $a) {
			if(!empty($a['recipient_country'])) {
				foreach($a['recipient_country'] AS $c) {
					$countries_activities[$c['iso']] += 1;
					if(isset($countries[$c['iso']])) continue;
					$countries[$c['iso']] = $c['name'];
					
					if(!isset($budgets[$c['iso']])) {
						$budgets[$c['iso']] = $a['statistics']['total_budget'];
					} else {
						$budgets[$c['iso']] += $a['statistics']['total_budget'];
					}
					$total_budget += $a['statistics']['total_budget'];
				}
			}
			if(!empty($a['activity_sectors'])) {
				foreach($a['activity_sectors'] AS $s) {
					if(isset($sectors[$s['code']])) continue;
					$sectors[$s['code']] = $s['name'];
				}
			}
			if(!empty($a['recipient_region'])) {
				foreach($a['recipient_region'] AS $r) {
					if(isset($regions[$r['code']])) continue;
					$regions[$r['code']] = $r['name'];
				}
			}
		}
		
		$start+=$limit;
	}
	$to_write = '<?php
$_COUNTRY_ISO_MAP = array(
';
	if(!empty($countries)) {
		
		foreach($countries AS $key=>$value) {
			if(empty($value) || $value=='#N/A') continue;
			$name = addslashes($value);
			$to_write .= "'{$key}' => '{$name}',\n";
		}
		
	}
	$to_write .= ');
$_COUNTRY_BUDGETS = array(
';
$to_write .= "'all' => '{$total_budget}',
";
		if(!empty($budgets)) {
		
			foreach($budgets AS $key=>$value) {
				$to_write .= "'{$key}' => '{$value}',\n";
			}
			
		}

$to_write .= ');
	
$_COUNTRY_ACTIVITY_COUNT = array(
';
	if(!empty($countries_activities)) {
		
		foreach($countries_activities AS $key=>$value) {
			$value = intval($value);
			$to_write .= "'{$key}' => '{$value}',\n";
		}
		
	}

$to_write .= ');
?>';
	$fp = fopen(TEMPLATEPATH . '/countries.php', 'w+');
	fwrite($fp, $to_write);
	fclose($fp);
	
	$to_write = '<?php
$_SECTOR_CHOICES = array(
';
	if(!empty($sectors)) {
		
		foreach($sectors AS $key=>$value) {
			if(empty($value) || $value=='#N/A') continue;
			$name = addslashes($value);
			$to_write .= "'{$key}' => '{$name}',\n";
		}
		
	}
	$to_write .= ');
?>';
	$fp = fopen(TEMPLATEPATH . '/sectors.php', 'w+');
	fwrite($fp, $to_write);
	fclose($fp);
	
	$to_write = '<?php
$_REGION_CHOICES = array(
';
	if(!empty($regions)) {
		
		foreach($regions AS $key=>$value) {
			if(empty($value) || $value=='#N/A') continue;
			$name = addslashes($value);
			$to_write .= "'{$key}' => '{$name}',\n";
		}
	}
	
	$to_write .= ');
?>';
	$fp = fopen(TEMPLATEPATH . '/regions.php', 'w+');
	fwrite($fp, $to_write);
	fclose($fp);
}

function wp_get_summary_data($type) {
	if(empty($type)) return false;
	
	global $_DEFAULT_ORGANISATION_ID, $_REGION_CHOICES, $_SECTOR_CHOICES;
	$limit = 50;
	$regions = array();
	$sectors = array();
	$total_budget = 0;
	
	$activities_url = SEARCH_URL . "activities/?format=json&limit={$limit}";
	if(!empty($_DEFAULT_ORGANISATION_ID)) {
		$activities_url .= "&organisations=" . $_DEFAULT_ORGANISATION_ID;
	}
	
	$content = file_get_contents($activities_url);
	$result = json_decode($content);
	$meta = $result->meta;
	$count = $meta->total_count;
	
	$objects = $result->objects;
	$activities = objectToArray($objects);
	
	foreach($activities AS $a) {
		$total_budget += floatval($a['statistics']['total_budget']);
		switch($type) {
			case 'cn':
				if(!empty($a['recipient_region'])) {
					foreach($a['recipient_region'] AS $r) {
						if(!isset($regions[$r['code']])) {
							$regions[$r['code']] = 0;
						} else {
							$regions[$r['code']] += floatval($a['statistics']['total_budget']);
						}			
					}
				} else {
					if(!isset($regions[0])) {
						$regions[0] = 0;
					} else {
						$regions[0] += floatval($a['statistics']['total_budget']);
					}
				}
				break;
			case 'sc':
				if(!empty($a['activity_sectors'])) {
					foreach($a['activity_sectors'] AS $s) {
						if(!isset($sectors[$s['code']])) {
							$sectors[$s['code']] = 0;
						} else {
							$sectors[$s['code']] += floatval($a['statistics']['total_budget']);
						}			
					}
				} else {
					if(!isset($sectors[0])) {
						$sectors[0] = 0;
					} else {
						$sectors[0] += floatval($a['statistics']['total_budget']);
					}
				}
				break;
		}
	}
	
	
	$start=$limit;
	while($start<$count) {
		$activities_url = SEARCH_URL . "activities/?format=json&start={$start}&limit={$limit}";
		if(!empty($_DEFAULT_ORGANISATION_ID)) {
			$activities_url .= "&organisations=" . $_DEFAULT_ORGANISATION_ID;
		}
		$content = file_get_contents($activities_url);
		$result = json_decode($content);
		$objects = $result->objects;
		$activities = objectToArray($objects);
		
		foreach($activities AS $a) {
			$total_budget += floatval($a['statistics']['total_budget']);
			switch($type) {
				case 'cn':
					if(!empty($a['recipient_region'])) {
						foreach($a['recipient_region'] AS $r) {
							if(!isset($regions[$r['code']])) {
								$regions[$r['code']] = 0;
							} else {
								$regions[$r['code']] += floatval($a['statistics']['total_budget']);
							}
						}
					} else {
						if(!isset($regions[0])) {
							$regions[0] = 0;
						} else {
							$regions[0] += floatval($a['statistics']['total_budget']);
						}
					}
					break;
				case 'sc':
					if(!empty($a['activity_sectors'])) {
						foreach($a['activity_sectors'] AS $s) {
							if(!isset($sectors[$s['code']])) {
								$sectors[$s['code']] = 0;
							} else {
								$sectors[$s['code']] += floatval($a['statistics']['total_budget']);
							}
						}
					} else {
						if(!isset($sectors[0])) {
							$sectors[0] = 0;
						} else {
							$sectors[0] += floatval($a['statistics']['total_budget']);
						}
					}
					break;
			}		
					
		}
		
		$start+=$limit;
	}
	
	$object['total_budget'] = $total_budget;
	$object['total_projects'] = $count;
	
	switch($type) {
		case 'cn':
			$object['data'][] = array('Region', 'Funding Percentage');
			foreach($regions AS $idx=>$r) {
				if($idx==0) {
					$object['data'][] = array('Others', $r);
				} else {
					$object['data'][] = array($_REGION_CHOICES[$idx], $r);
				}
			}
			break;
		case 'sc':
			$object['data'][] = array('Sector', 'Funding Percentage');
			foreach($sectors AS $idx=>$s) {
				if($idx==0) {
					$object['data'][] = array('Others', $s);
				} else {
					$object['data'][] = array($_SECTOR_CHOICES[$idx], $s);
				}
			}
			break;
	}
	
	return $object;
	
}



function wp_generate_home_map_data() {
		global $_GM_POLYGONS, $_DEFAULT_ORGANISATION_ID, $_COUNTRY_ISO_MAP, $_COUNTRY_ACTIVITY_COUNT;
		
		$array['objects'] = array();
		$array['meta']['total_count'] = COUNT($_COUNTRY_ISO_MAP);
		foreach($_COUNTRY_ISO_MAP AS $iso=>$c) {
			if(isset($array['objects'][$iso])) {
				$array['objects'][$iso]['total_cnt']++;
			} else {
				if(isset($_GM_POLYGONS[$iso])) {
					$array['objects'][$iso] = array('path' => $_GM_POLYGONS[$iso], 'name' => $c, 'total_cnt' => $_COUNTRY_ACTIVITY_COUNT[$iso]);
				}
			}
		}
		
		return $array;
}



function objectToArray($d) {
	if (is_object($d)) {
		// Gets the properties of the given object
		// with get_object_vars function
		$d = get_object_vars($d);
	}

	if (is_array($d)) {
		/*
		* Return array converted to object
		* Using __FUNCTION__ (Magic constant)
		* for recursive call
		*/
		return array_map(__FUNCTION__, $d);
	}
	else {
		// Return array
		return $d;
	}
}

function chart_scripts_method() {
    	wp_deregister_script( 'jsapi' );
    	wp_register_script( 'jsapi', 'http://www.google.com/jsapi');
    	wp_enqueue_script( 'jsapi' );
	}   
	add_action('wp_enqueue_scripts', 'chart_scripts_method');
?>