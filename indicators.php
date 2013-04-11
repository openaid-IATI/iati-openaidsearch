<?php
/*
Template Name: Indicators page
*/
?>

<?php get_header(); ?>


<?php 
/*
      $regions = wp_get_regions(); 
      
      
      
//      $countries = wp_get_unique_result($indicator_results, 'country_name', 'dac_region_name');
      $selected_region = $_GET['region'];
      $selected_country = $_GET['countries'];
      $selected_countryyear = $_GET['years'];
      //@todo fix dynamic cities
      $selected_city = '';
      $selected_indicator = $_GET['indicator'];
      
      
      
      if(!strlen($selected_indicator)>0 || $selected_indicator == 'all'){
          $selected_indicator = 'population';
      }
      
      $countries = wp_get_countries($selected_region);
      
      $cities = wp_get_cities($selected_country);
      
      
      
      $indicator_results = wp_get_indicator_results($selected_region, $selected_country, $selected_year);
      
//      $indicator_relevant_results = wp_get_relevant_results($selected_indicator);
      
      $years = wp_get_unique_result(wp_get_relevant_results($selected_indicator), 'year');
      if (!isset($selected_year)){
          $selected_year = $years[0];
      }
      

      $temp = array();
    foreach ($indicator_results as $i) {
        array_push($temp, $i[$selected_indicator]);
    }
    $max_indicator = max($temp);
    //$factor = 400000 / 40000;
    //$factor = 600000 / $max_pop;
    $maxcircleradius = 3000000000000;
    $factor = $maxcircleradius / $max_indicator;
    */
    
?>  
<?php get_template_part( "indicator", "filters" ); ?>
<?php get_template_part( "indicator", "map" ); ?>

<div id="page-wrapper">
	<div class="page-header page-header-less-margin">
		<div class="container">
			<div class="row-fluid">
				<div class="span8">

					<div class="project-share-container share-left">

						<a href="#" id="project-share-graph" class="project-share-button hneue-bold">
							<div class="share-icon"></div>
							<div class="share-text">TYPE GRAPH</div>
							<div id="dropdown-type-graph">
								<button id="dropdown-line-graph">LINE GRAPH</button>
								<button id="dropdown-table-graph">TABLE GRAPH</button>
							</div>
						</a>
						

						<a href="#" id="project-share-export" class="project-share-button hneue-bold">
							<div class="share-icon"></div>
							<div class="share-text">EXPORT</div>
						</a>
						<a href="#" id="project-share-embed" class="project-share-button hneue-bold">
							<div class="share-icon"></div>
							<div class="share-text">EMBED</div>
						</a>
					</div>

				</div>
				<div class="span4">
					<div class="project-share-container">

						<button id="project-share-share" class="project-share-button hneue-bold">
							<div class="share-icon"></div>
							<div class="share-text">SHARE</div>
						</button>
						<button id="project-share-whistleblower" class="project-share-button hneue-bold">
							<div class="share-icon"></div>
							<div class="share-text">WHISTLEBLOWER</div>
						</button>
						<button id="project-share-bookmark" class="project-share-button hneue-bold">
							<div class="share-icon"></div>
							<div class="share-text">BOOKMARK</div>
						</button>

					</div>
				</div>
			</div>

		</div>
	</div>

	<div class="container">
		<div class="page-content">
			<div class="row-fluid">
				<div class="span12">
					<div id="line-chart-placeholder"></div>
					<div id="table-chart-placeholder"></div>
				</div>
				
			</div>
		</div>
	</div>
</div>


<?php get_footer(); ?>
<script type="text/javascript">
$(document).ready(function(){
  initialize_map('http://dev.oipa.openaidsearch.org/json',2015,"indicator", "", "");
});
</script>
