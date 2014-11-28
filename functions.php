<?php 
include( TEMPLATEPATH .'/constants.php' );

// WORDPRESS THEME FUNCTIONS
add_theme_support( 'menus' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'automatic-feed-links' );





// add_filter( 'rewrite_rules_array','my_insert_rewrite_rules' );
// // add_filter( 'query_vars','my_insert_query_vars' );
// add_action( 'wp_loaded','my_flush_rules' );

// // flush_rules() if our rules are not yet included
// function my_flush_rules(){
// 	$rules = get_option( 'rewrite_rules' );

// 	//if ( ! isset( $rules['(project)/([^/]+)/'] ) ) {
// 		global $wp_rewrite;
// 	   	$wp_rewrite->flush_rules();
// 	//}
// }

// // Adding a new rule
// function my_insert_rewrite_rules( $rules )
// {	

// 	add_rewrite_tag('%iati_id%','(.*)');
// 	add_rewrite_tag('%backlink%','(.*)');
// 	$newrules = array();
// 	$newrules['(project)/([^/]+)/?$'] = 'index.php?pagename=$matches[1]&id=$matches[2]';
// 	global $wp_rewrite;
// 	var_dump($wp_rewrite);
// 	return $newrules + $rules;
// }


// // Adding the id var so that WP recognizes it
// function my_insert_query_vars( $vars )
// {
//     array_push($vars, 'iati_id');
//     return $vars;
// }






// function add_rewrite_rules( $wp_rewrite ) 
// {
// 	$new_rules = array(
// 		'project/([^/]+)/?' => 'index.php?pagename=project&iati_id=$matches[1]'
// 	);

// 	$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
// 	var_dump($wp_rewrite->rules);
// 	var_dump("test");
// }
// add_action('generate_rewrite_rules', 'add_rewrite_rules');


function projects_list() {
	include( TEMPLATEPATH .'/ajax-projects-list.php' );
	die();
}

function rsr_call() {
	$iati_id = "";
	if (isset($_REQUEST['iati_id'])){
		$iati_id = $_REQUEST['iati_id'];
	}
	include( TEMPLATEPATH .'/project-rsr-ajax.php' );
	die();
}
add_action('wp_ajax_projects_list', 'projects_list');
add_action('wp_ajax_nopriv_projects_list', 'projects_list');
add_action('wp_ajax_rsr_call', 'rsr_call');
add_action('wp_ajax_nopriv_rsr_call', 'rsr_call');


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
		'name' => __( 'OIPA theme page sidebar' ),
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




function add_popular_search($query){
	global $wpdb;
	$table_name = $wpdb->prefix . "popular_searches";
	$alreadyasked = $wpdb->get_row($wpdb->prepare("SELECT phrase, count FROM {$table_name} WHERE phrase = %s", $query));
	if ($alreadyasked){
		$newcount = $alreadyasked->count + 1;

		$wpdb->update( 
			$table_name, 
			array( 
				'count' => $newcount	// integer (number) 
			), 
			array( 'phrase' => $query ), 
			array( '%d' ), 
			array( '%s' ) 
		);
	} else{

		$wpdb->insert( 
			$table_name, 
			array( 
				'phrase' => $query, 
				'count' => 1 
			)
		);
	}
}

function wp_get_activity() {
	$identifier = null;
	if (isset($_REQUEST['iati_id'])){
		$identifier = $_REQUEST['iati_id'];
	}
	if(empty($identifier)) return null;
	$search_url = SEARCH_URL . "activities/{$identifier}/?format=json";

	$content = @file_get_contents($search_url);
	if ($content === false) { return false; }
	$activity = json_decode($content);
	return $activity;
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

add_filter( 'request', 'my_request_filter' );
function my_request_filter( $query_vars ) {
	if( isset( $_GET['s'] ) && empty( $_GET['s'] ) ) {
		$query_vars['s'] = " ";
	}
	return $query_vars;
}



class PopularSearchWidget extends WP_Widget {

	function PopularSearchWidget() {
		// Instantiate the parent object
		parent::__construct( false, 'Popular Search widget' );
	}

	function widget( $args, $instance ) {
		
		global $wpdb;
		
		$table_name = $wpdb->prefix . "popular_searches";
		$popularsearches = $wpdb->get_results( 
			"
			SELECT * 
			FROM {$table_name}
			ORDER BY count desc
			LIMIT 5
			"
		);

		echo '<div class="drop-shadow postit page-sidebar-item"><div class="postit-title hneue-light">Popular searches</div><div class="textwidget">';
		echo '<div class="postit-text">';
		foreach ( $popularsearches as $popularsearch ) 
		{	
			echo '<div class="popular-search-item"><div class="pop-search-icon"></div><div><a href="/projects/?query=' . $popularsearch->phrase . '">' . $popularsearch->phrase . '</a></div></div>';
		}

		echo '</div></div></div>';
	}

	function update( $new_instance, $old_instance ) {
		// Save widget options
	}

	function form( $instance ) {
		// Output admin widget options form
	}
}

function myplugin_register_widgets() {
	register_widget( 'PopularSearchWidget' );
}

add_action( 'widgets_init', 'myplugin_register_widgets' );

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

	$search_url = SEARCH_URL . "activity-list/?format=json&limit=" . $activities_per_page;

	if($activities_offset != 0){
		$search_url = $search_url . "&offset=" . $activities_offset;
	}

	if ($_DEFAULT_ORGANISATION_ID){
		$search_url = $search_url . "&reporting_organisation__in=" . $_DEFAULT_ORGANISATION_ID;
	}

    $search_url = wp_filter_request($search_url);
	$content = file_get_contents($search_url);
	$result = json_decode($content);
	$meta = $result->meta;
	$objects = $result->objects;
}

function wp_generate_rsr_projects(&$objects, $iati_id){
	
	$search_url = "http://rsr.akvo.org/api/v1/project/?format=json&partnerships__iati_activity_id=" . $iati_id . "&distinct=True&limit=100&depth=1";
	$content = file_get_contents($search_url);
	$result = json_decode($content);
	$objects = $result->objects;
}

function wp_generate_link_parameters($filter_to_unset=null){

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
function wp_generate_paging($meta) {

	global $_PER_PAGE;
	$per_page = $_PER_PAGE;
	if(isset($_GET['per_page'])){ $per_page = $_GET['per_page']; }
	
	$parameters = wp_generate_link_parameters();
	$total_count = $meta->total_count;
	$offset = $meta->offset;
	$limit = $meta->limit;
	
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
	   $paging_block .= "<li><a href='?offset=" . ($offset - $limit) . $parameters . "' class='limitstart'><span>&larr; </span></a></li>";
	} // end if 

	if ($cur_page > (1 + $range)){
	   //$params['offset'] = 0;
	   $paging_block .= "<li><a href='?offset=0" . $parameters . "' class='page'><span>1</span></a></li>";
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
	      	 $paging_block .= "<li><a href='?offset=" . (($x*$per_page) - $per_page) . $parameters . "' class='page'><span>$x</span></a></li>";
	      } // end else
	   } // end if 
	} // end for

	if($cur_page < ($total_pages - (1 + $range))){
	    $paging_block .= "<li>...</li>";
	}

	if($cur_page < ($total_pages - $range)){
	   //$params['offset'] = $total_count - ($total_count % $per_page);
	   $paging_block .= "<li><a href='?offset=" . ($total_count - ($total_count % $per_page)) . $parameters . "' class='page'><span>$total_pages</span></a></li>";
	}
	               
	// if not on last page, show forward and last page links        
	if ($cur_page != $total_pages) {

	   // get next page
	   $nextpage = $cur_page + 1;
	    // echo forward link for next page 
	   //$params['offset'] = $offset + $limit;
	   $paging_block .= "<li><a href='?offset=" . ($offset + $limit) . $parameters . "' class='endmilit'><span>&rarr; </span></a></li>";
	   //http_build_query($params)
	   //$params['offset'] = $total_count - ($total_count % $per_page);
	   $paging_block .= "<li><a href='?offset=" . ($total_count - ($total_count % $per_page)) . $parameters . "' class='end'><span>&raquo;</span></a></li>";
	} // end if
	/****** end build pagination links ******/


	$paging_block .= "</ul>";


	echo $paging_block; 
}


function wp_filter_request($search_url){

    if(!empty($_REQUEST['countries'])) {
		$countries = explode(',', trim($_REQUEST['countries']));
		foreach($countries AS &$c) $c = trim($c);
		$countries = implode(',', $countries);
		$search_url .= "&countries__in={$countries}";
		$has_filter = true;
		if(!empty($srch_countries)) {
			$search_url .= ",{$srch_countries}";
		}
	} else {
		if(!empty($srch_countries)) {
			$search_url .= "&countries__in={$srch_countries}";
			$has_filter = true;
		}	
	}
	
	if(!empty($_REQUEST['regions'])) {
		$regions = explode(',', trim($_REQUEST['regions']));
		foreach($regions AS &$c) $c = trim($c);
		$regions = implode(',', $regions);
		$search_url .= "&regions__in={$regions}";
		$has_filter = true;
	}

	if(!empty($_REQUEST['query'])) {
		// $regions = explode('|', trim($_REQUEST['regions']));
		// foreach($regions AS &$c) $c = trim($c);
		// $regions = implode('|', $regions);
		$search_url .= "&query=".$_REQUEST['query'];
		$curquery = $_REQUEST['query'];
		add_popular_search($curquery);
		$has_filter = false;
	}
	
	if(!empty($_REQUEST['sectors'])) {
		$sectors = explode(',', trim($_REQUEST['sectors']));
		foreach($sectors AS &$c) $c = trim($c);
		$sectors = implode(',', $sectors);
		$search_url .= "&sectors__in={$sectors}";
		$has_filter = true;
	}

	if(!empty($_REQUEST['reporting_organisations'])) {
		$reporting_organisations = explode(',', trim($_REQUEST['reporting_organisations']));
		foreach($reporting_organisations AS &$c) $c = trim($c);
		$reporting_organisations = implode(',', $reporting_organisations);
		$search_url .= "&reporting_organisation__in={$reporting_organisations}";
		$has_filter = true;
	}
	
	if(!empty($_REQUEST['budgets'])) {
		$budget_gte = 99999999999;
		$budget_lte = 0;
		$budgets = explode(',', trim($_REQUEST['budgets']));
		foreach ($budgets as &$budget) {
		    $lower_higher = explode('-', $budget);
		    if($lower_higher[0] < $budget_gte){
		    	$budget_gte = $lower_higher[0];
		    }
		    if (sizeof($lower_higher) > 1) {
		    	
		    	if($lower_higher[1] > $budget_lte){
		    		$budget_lte = $lower_higher[1];
		    	}
		    }
		}

		if ($budget_gte != 99999999999){
			$search_url .= "&total_budget__gte={$budget_gte}";
		}
		if ($budget_lte != 0){
			$search_url .= "&total_budget__lte={$budget_lte}";
		}
		$has_filter = true;
	}

	if(!empty($_REQUEST['order_by'])){
		$orderby = trim($_REQUEST['order_by']);
		$search_url .= "&order_by={$orderby}";
	} else {
		$search_url .= "&order_by=-total_budget";
	}

    return $search_url;
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
	if (substr($ret, 0, 1) == "."){
		$ret = substr($ret, 1);
	}
	return $ret;
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
}




function add_rewrite_rules( $wp_rewrite ) 
{
  $new_rules = array(
    'project/([^/]+)/?$' => 'index.php?pagename=project&iati_id='.$wp_rewrite->preg_index(1),
    'embed/([^/]+)/?$' => 'index.php?pagename=embed&iati_id='.$wp_rewrite->preg_index(1),
  );
  $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
add_action('generate_rewrite_rules', 'add_rewrite_rules');

