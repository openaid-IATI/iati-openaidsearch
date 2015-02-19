<?php 
include( TEMPLATEPATH .'/constants.php' );

// Register Custom Navigation Walker
require_once('wp_bootstrap_navwalker.php');

// WORDPRESS THEME FUNCTIONS
add_theme_support( 'menus' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'automatic-feed-links' );

function add_rewrite_rules( $wp_rewrite ) 
{
  $new_rules = array(
    'project/([^/]+)/?$' => 'index.php?pagename=project&iati_id='.$wp_rewrite->preg_index(1),
    'country/([^/]+)/?$' => 'index.php?pagename=country&country_id='.$wp_rewrite->preg_index(1),
    'donor/([^/]+)/?$' => 'index.php?pagename=donor&donor_id='.$wp_rewrite->preg_index(1),
    'embed/([^/]+)/?$' => 'index.php?pagename=embed&iati_id='.$wp_rewrite->preg_index(1),
  );
  $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
add_action('generate_rewrite_rules', 'add_rewrite_rules');





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

function oipa_widgets_init() {
	register_sidebar(array(
		'name' => __( 'OIPA theme page sidebar' ),
	  	'id' => 'unh-page-sidebar',
	    'before_widget' => '<div class="drop-shadow postit page-sidebar-item">',
	    'after_widget' => '</div>',
	    'before_title' => '<div class="postit-title hneue-light">',
	    'after_title' => '</div>',
	));

	register_sidebar(array(
		'name' => __( 'Footer sidebar' ),
	  	'id' => 'footer-sidebar',
	    'before_widget' => '',
	    'after_widget' => '',
	    'before_title' => '',
	    'after_title' => '',
	));
}
add_action( 'widgets_init', 'oipa_widgets_init' );

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

function wp_generate_rsr_projects(&$objects, $iati_id){
	
	$search_url = "http://rsr.akvo.org/api/v1/project/?format=json&partnerships__iati_activity_id=" . $iati_id . "&distinct=True&limit=100&depth=1";
	$search_url = oipa_filter_request($search_url);
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


function oipa_get_data_for_url($url) {

  $search_url = OIPA_URL . $url;
  $content = @file_get_contents($search_url);
  if ($content === false) { return false; }
  return $content;
}


/**
 * AJAX calls to OIPA
 */
function refresh_elements() {
 

	$type = $_GET['call'];
	include( TEMPLATEPATH . '/oipa-functions.php' ); 

  if ($type === 'projects'){
  	include( TEMPLATEPATH . '/ajax/project-list-ajax.php' );
  } else if ($type === 'projects-on-detail'){
    include( TEMPLATEPATH . '/ajax/project-list-ajax.php');
  } else if ($type === 'countries'){
  	include( TEMPLATEPATH . '/ajax/country-list-ajax.php' );
  } else if ($type === 'regions'){
  	include( TEMPLATEPATH . '/ajax/ajax-list-regions.php' );
  } else if ($type === 'sectors'){
  	include( TEMPLATEPATH . '/ajax/ajax-list-sectors.php' );
  } else if ($type === 'donors'){
  	include( TEMPLATEPATH . '/ajax/donor-list-ajax.php' );
  } else if ($type === 'total-projects'){

    $url_add = "";
    $org = DEFAULT_ORGANISATION_ID;
    if (!empty($org)){
      $url_add = "&reporting_organisation__in=" . DEFAULT_ORGANISATION_ID;
    }

    echo oipa_get_data_for_url('activity-aggregate-any/?format=json&group_by=reporting-org' . $url_add );
  } else if ($type === 'total-donors'){

    $url_add = "";
    $org = DEFAULT_ORGANISATION_ID;
    if (!empty($org)){
      $url_add = "&reporting_organisation__in=" . DEFAULT_ORGANISATION_ID;
    }

    echo oipa_get_data_for_url('donor-activities/?format=json&limit=1' . $url_add );
  } else if ($type === 'total-countries'){

    $url_add = "";
    $org = DEFAULT_ORGANISATION_ID;
    if (!empty($org)){
      $url_add = "&reporting_organisation__in=" . DEFAULT_ORGANISATION_ID;
    }

    echo oipa_get_data_for_url('country-activities/?format=json&limit=1' . $url_add );
  } else if ($type === 'homepage-total-budget'){
    echo oipa_get_data_for_url('activity-aggregate-any/?format=json&group_by=reporting-org&aggregation_key=total-budget&reporting_organisation__in=' . DEFAULT_ORGANISATION_ID);
  } else if ($type === 'homepage-total-expenditure'){
    echo oipa_get_data_for_url('activity-aggregate-any/?format=json&group_by=reporting-org&aggregation_key=expenditure&reporting_organisation__in=' . DEFAULT_ORGANISATION_ID);
  }else if ($type === 'homepage-major-programmes'){
    echo oipa_get_data_for_url('sector-activities/?format=json&sectors__in=1,2,3,4,5');
  } else if ($type === 'region'){
    echo oipa_get_data_for_url('regions/' . $_REQUEST["region"] . '/?format=json');
  } else if ($type === 'country'){
    echo oipa_get_data_for_url('countries/' . $_REQUEST["country"] . '/?format=json');
  }else if (in_array($type, array(
    'country-geojson',
    'activities',
    'country-activities',
    'region-activities',
    'global-activities',
    'activity-filter-options',
    'activity-list',
    'sector-activities',
    'donor-activities',
    'donors',
    'regions',
    'countries',
    'sectors'
    ))){
    echo oipa_get_data_for_url($type . '/?' . $_SERVER["QUERY_STRING"]);
  } else {
  	return 'Something went wrong. The call was not foud.';
  }
  
  exit();

}

add_action('wp_ajax_refresh_elements', 'refresh_elements');
add_action('wp_ajax_nopriv_refresh_elements', 'refresh_elements');



