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

	$search_url = SEARCH_URL . "activities/?format=json&limit=" . $activities_per_page . "&offset=" . $activities_offset;
    
	if ($_DEFAULT_ORGANISATION_ID){
		$search_url = $search_url . "&reporting_organisation__in=" . $_DEFAULT_ORGANISATION_ID;
	}

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
	
	if(!empty($_REQUEST['budgets'])) {
		$budgets = explode(',', trim($_REQUEST['budgets']));
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


?>