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
      $selected_year = $_GET['years'];
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
  initialize_map('http://dev.oipa.openaidsearch.org/json',2010,"indicator", "", "");
});
</script>
<script type="text/javascript">

/*
//@todo add this to a global js, also being used by page-projects.php
function create_filter_attributes(objects, keys){
        var html = '';
        var counter = 0;
        $.each(objects, function(index, value){
            
            //limit on 80
        	if (counter == 80){
            	return false;
            }

            if (counter == 0 || counter == 20 || counter == 40 || counter == 60){
                html += '<div class="span3">';
            }
            html += '<div class="squaredThree">';
            html += '<input type="checkbox" value="'+ keys[value] +'" id="land'+keys[value]+'" name="check" />';
            html += '<label class="map-filter-cb-value" for="land'+keys[value]+'"></label>';
            html += '<span>'+value+'</span></div>'; 
            
            if (counter == 19 || counter == 39 || counter == 59 || counter == 79){
                        html += '</div>';

            }

            counter++;

            //limit on 80
            if (counter == 80){
            	return html;	
            }
        });
        return html;
    }
$(document).ready(function() {
//    alert(countryData.type);
   
    
    
    
    
    
    <?php foreach($indicator_results as $i) :?>
    
    <?php if (strlen($i[$selected_indicator])>0) :?>
   
    try 
      {
// jsonPath(countryData, "$..features[?(@.id=='<?php echo $i['country_iso3'] ?>')]")[0].properties.projects = '<?php echo $i[$selected_indicator] ?>';//Run some code here
      }
    catch(err)
      {
        console.log('<?php echo $i['country_iso3'] ?>'+err);
      }
          try{
              
            var circle = L.circle(new L.LatLng(countryloc['<?php echo $i['country_iso'] ?>'].longitude, countryloc['<?php echo $i['country_iso'] ?>'].latitude), <?php echo sqrt(($factor * $i[$selected_indicator])/pi()); //echo round($factor * $i['population']); ?>, {
              color: 'red',
             weight: '0',
              fillColor: '#f03',
              fillOpacity: 0.5
             }).addTo(map);
             circle.bindPopup('<?php echo $selected_indicator?>: <?php echo $i[$selected_indicator]?>');
            }catch(err){
                console.log(err);
            }
        
    
    <?php endif ?>
<?php endforeach; ?>
    
    
    var region_keys = {};
    <?php foreach($regions as $region): ?>
        region_keys['<?php echo $region['code'] ?>'] = '<?php echo $region['code'] ?>';
    <?php endforeach ?>
        
        regions_html = create_filter_attributes(region_keys, region_keys);
        $('#region_filters').append(regions_html);
        
    var country_keys = {};
    <?php foreach($countries as $c): ?>
        country_keys['<?php echo $c['iso'] ?>'] = '<?php echo $c['iso'] ?>';
    <?php endforeach ?>
        
        country_html = create_filter_attributes(country_keys, country_keys);
        $('#country_filters').append(country_html);
        
    var city_keys = {};
    <?php foreach($cities as $c): ?>
        city_keys['<?php echo $c['name'] ?>'] = '<?php echo $c['name'] ?>';
    <?php endforeach ?>
        
     city_html = create_filter_attributes(city_keys, city_keys);
        $('#city_filters').append(city_html);
        
    <?php foreach($years as $year) :?>
        $('#year-<?php echo $year ?>').addClass('slider-active');
    <?php endforeach; ?>
        
    <?php if (isset($selected_year)) :?>
        $( "#map-slider-tooltip" ).slider( "option", "value", <?php echo $selected_year ?> );
        
//        $("#map-slider-tooltip a" ).append("<?php echo $selected_year ?>");
        $('#year-<?php echo $selected_year ?>').addClass('active');
    <?php endif ?>
    
});
function select_year(year){
    window.location = '?years=' + year + '&indicator=<?php echo $selected_indicator ?>' + '&countries=<?php echo $selected_country ?>&regions=<?php echo $selected_region ?>&city=<?php echo $selected_city ?>';;
} 
*/
</script>
