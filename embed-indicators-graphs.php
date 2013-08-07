<?php
/*
Template Name: Embed indicator map page
*/
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Open UN-Habitat</title>
   

    <!-- Bootstrap -->

    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" type="image/vnd.microsoft.icon"/>
    <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" type="image/x-ico"/>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/leaflet.css" />
    <link href="<?php echo get_template_directory_uri(); ?>/style.css" rel="stylesheet" media="screen">

    <style>
      body {margin:0; background-color: transparent;}

    </style>

    <!--[if lte IE 8]>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/leaflet.ie.css" />
     <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/ie-8-and-down.css" />
    <![endif]-->
    
    

    <?php wp_head(); ?>
</head>
<body style="background-color: #F1EEE8";>
<div id="map-wrapper" style="display:none">
    



    <!-- Show selection button --> 
    <?php //get_template_part( "map", "selection" ); ?>


    <?php //if(is_page("indicators")){ ?>
    <div id="map-timeline-wrapper">
        <div id="timeline-left"></div>
        <div id="map-timeline">
            <div id="map-slider-tooltip"></div>

            <?php for ($i = 1950; $i < 2051;$i++){   
            echo '<div class="slider-year';
            echo '" id="year-' . $i . '">';
            if ($i == 1950) { echo '<div class="slider-year-inner-left"></div>';}
            echo '<div class="slider-year-inner-white"></div></div>'; 
            } ?>
        </div>
        <div id="timeline-right"></div>
    </div>
    <?php //} ?>



    <!-- The black overlay for filtering options --> 
  <div id="map-filter-overlay">
        <div class="container">

      <div class="row-fluid map-filter-list">
                <div id="indicators-filters"></div>
                <div id="regions-filters"></div>
                <div id="countries-filters"></div>
                <div id="cities-filters"></div>
            </div>
      
    </div>

    <div id="map-filters-buttons">
      <div class="container">
        <div class="row-fluid">
          <div class="span9">

                        <button id="map-filter-save" class="hneue-bold">SAVE</button>
            <button id="map-filter-cancel" class="hneue-bold">CANCEL</button>
                        <div id="map-filter-errorbox"></div>
                    </div>
                    <div class="span3 hneue-bold">
                        <div id="map-filter-pagination"></div>
                    </div>
        </div>
      </div>
    </div>

  </div>
    <!-- treemap visualization placeholder, TO DO: load dynamically on click, no preloading of the treemap -->
    <div id="treemap-placeholder"></div>
    <div id="map-loader">
        <div id="map-loader-text">Loading indicator map</div>
        <img src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" alt="" />
    </div>
  <div id="map"></div>
</div>


      <div id="charts-column-1" style="width: 580px; height:380px; display: block; margin: 0; padding: 10px;">
        <h2 id="line-chart-name1" style="margin: 0; margin-bottom: 0.2em;"></h2>
        <div id="line-chart-filter1" style ="margin-top: 10px"></div>
        <div id="line-chart-placeholder1" style ="margin-top: 10px"></div>




<script type="text/javascript">
var site = '<?php echo SITE_URL; ?>';
var home_url = "<?php echo bloginfo("url"); ?>";
var template_directory = "<?php echo bloginfo("template_url"); ?>";
<?php
global $_DEFAULT_ORGANISATION_ID;
echo 'var organisation_id = "' . $_DEFAULT_ORGANISATION_ID . '"';
?>

</script>

<script src="<?php echo get_template_directory_uri(); ?>/js/dependencies/jquery.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/dependencies/jquery-ui.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/dependencies/leaflet.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/dependencies/bootstrap.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/map.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/main.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/dependencies/jquery.nouislider.min.js"></script>
<script src="https://www.google.com/jsapi"></script>
<script src="http://canvg.googlecode.com/svn/trunk/rgbcolor.js" type="text/javascript"></script>
<script src="http://canvg.googlecode.com/svn/trunk/canvg.js" type="text/javascript"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/dependencies/html2canvas.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/dependencies/jquery.plugin.html2canvas.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/indicators.js"></script>

<script type="text/javascript">
$(document).ready(function(){
  selected_type = "indicator";
  query_string_to_selection();

  if (current_selection.indicators === undefined){
    current_selection.indicators = [];
    current_selection.indicators.push({"id":"population", "name":"Total population"});
  }
  reload_map();
  initialize_filters(fill_selection_box, initialize_charts);
  map.options.minZoom = 1;
  map.setView([-4.05, 25.09], 2);
});
</script>

