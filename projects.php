<?php
/*
Template Name: Projects page
*/
?>

<?php wp_generate_results_v2($objects, $meta); ?>

<?php get_header(); ?>
<?php get_template_part( "project", "filters" ); ?>
<?php get_template_part( "map" ); ?>

<div id="page-wrapper">

	<div class="page-header page-header-less-margin">
		<div class="container">
			<div id="projects-search-navbar" class="row-fluid">
				<div class="span7">
					<div class="projects-pagination-info hneue-bold">
						<div id="pagination-totals">
							RESULTS <?php echo $meta->offset + 1; ?> - <?php if(($meta->offset + $meta->limit)>$meta->total_count) { echo $meta->total_count; } else { echo ($meta->offset + $meta->limit); } ?> OF <?php echo $meta->total_count; ?>
						</div>

						<div id="sort-type-amount" class="project-sort-type">
                            <span class="project-sort-text hneue-bold">SHOW <?php echo $meta->limit; ?></span>
                            <span class="project-sort-icon"></span>

                            <div id="dropdown-project-amount" class="dropdown-project">

                                <!-- TO DO: keep parameters -->
                                <a href="<?php echo home_url(); ?>/projects/?offset=0&per_page=5" id="dropdown-project-amount-5">5</a>
                                <a href="<?php echo home_url(); ?>/projects/?offset=0&per_page=10" id="dropdown-project-amount-10">10</a>
                                <a href="<?php echo home_url(); ?>/projects/?offset=0&per_page=25" id="dropdown-project-amount-20">25</a>
                                <a href="<?php echo home_url(); ?>/projects/?offset=0&per_page=50" id="dropdown-project-amount-20">50</a>
                            </div>

						</div>
						<a id="save-search-results" href="#save-search-results">SAVE SEARCH RESULTS</a>

					</div>
				</div>
				<div class="span5">
					<div class="projects-sorting hneue-bold">
                        <div id="sort-type-header">
                            SORT BY:
                        </div>

                        <?php
                        $params = $_GET;
                        $params['order_by'] = "budget";
                        ?>
                        
                        <div id="sort-type-budget" class="project-sort-type">
                            <span class="project-sort-text hneue-bold">BUDGET</span>
                            <span class="project-sort-icon"></span>
                            <div id="dropdown-project-budget" class="dropdown-project">
                                <a href="?<?php echo http_build_query($params); ?>" id="dropdown-project-budget-asc">ASCENDING</a>
                                <a href="?<?php echo http_build_query($params); ?>" id="dropdown-project-budget-desc">DESCENDING</a>
                            </div>
                        </div>

                        <?php
                        $params['order_by'] = "start_planned";
                        ?>

                        <div id="sort-type-startdate" class="project-sort-type">
                            <span class="project-sort-text hneue-bold">START DATE</span>
                            <span class="project-sort-icon"></span>
                            <div id="dropdown-project-startdate" class="dropdown-project">
                                <a href="?<?php echo http_build_query($params); ?>" id="dropdown-project-startdate-asc">ASCENDING</a>
                                <a href="?<?php echo http_build_query($params); ?>" id="dropdown-project-startdate-desc">DESCENDING</a>
                            </div>
                        </div>

                        <?php
                        $params['order_by'] = "country";
                        ?>
                        <div id="sort-type-country" class="project-sort-type">
                            <span class="project-sort-text hneue-bold">COUNTRY</span>
                            <span class="project-sort-icon"></span>
                            <div id="dropdown-project-country" class="dropdown-project">
                                <a href="?<?php echo http_build_query($params); ?>" id="dropdown-project-country-asc">ASCENDING</a>
                                <a href="?<?php echo http_build_query($params); ?>" id="dropdown-project-country-desc">DESCENDING</a>
                            </div>
                        </div>

					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="page-content">
		
		
			<?php get_template_part( "projects", "description" ); ?>
	
        


		<div class="container">
		<div class="row-fluid">
			<div class="span12">
				<div id="pagination">
					<?php wp_generate_paging($meta); ?>
				</div>
			</div>
		</div>



		</div>
	</div>
</div>
<div id="paginated-loader">
    <div id="paginated-text">Loading projects</div>
    <img src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" alt="" />
</div>

<?php get_footer(); ?>
        <?php 
        $projects = wp_get_activities();// print_r($projects);

        // if parameters are set
        if(count($_GET)) {
            $totals = array();
            foreach($projects AS $a) {
                    
                    foreach($a['recipient_country'] AS $c) {
                            if(isset($totals[$c['iso']])) {
                                    $totals[$c['iso']]['total_cnt']++;
                            }else{
                                $totals[$c['iso']]['total_cnt'] = 1;
                            }
                    }
    	    }
        } else {
        ?>
        <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/dependencies/all_projects_data.js"></script>
        <?php  
        }

        ?>
<script type="text/javascript">
    
    function create_filter_attributes(objects, keys){
        var html = '';
        $.each(objects, function(index, value){
            
            if (index == 0 || index == 20 || index == 40 || index == 60){
                html += '<div class="span3">';
            }
            html += '<div class="squaredThree">';
            html += '<input type="checkbox" value="'+ keys[value] +'" id="land'+keys[value]+'" name="check" />';
            html += '<label class="map-filter-cb-value" for="land'+keys[value]+'"></label>';
            html += '<span>'+value+'</span></div>'; 
            
            if (index == 19 || index == 39 || index == 59){
                        html += '</div>';

            }
        });
        return html;
    }
    $(document).ready(function() {
//            jsonPath(countryData, "$..features[?(@.id=='AFG')]")[0].properties.projects = 2444;
        var countries = new Array();
        var country_keys = {};
        var region_keys = {};
        var sector_keys = {};
        var budget_keys = {};
        budget_keys['all'] = 'all';
        budget_keys['> US$ 0'] = '';
        budget_keys['> US$ 10.000'] = '10000';
        budget_keys['> US$ 50.000'] = '50000';
        budget_keys['> US$ 100.000'] = '100000';
        budget_keys['> US$ 500.000'] = '500000';
        budget_keys['> US$ 1.000.000'] = '1000000';
        
        <?php 
        // if parameters set
        if(count($_GET)) {

        foreach($projects as $i) :?>
                <?php if (!empty($i['recipient_country'])) :?>
        try
        
        {   
            var iso3 = jsonPath(country_info, "$[?(@.ISO2=='<?php echo $i['recipient_country'][0]['iso'] ?>')]")[0].ISO3
            
            jsonPath(countryData, "$..features[?(@.id=='"+ iso3 +"')]")[0].properties.projects = '23<?php echo $totals[$i['recipient_country'][0]['iso']]['total_cnt'] ?>';//Run some code here
            jsonPath(countryData, "$..features[?(@.id=='"+ iso3 +"')]")[0].properties.iso = '<?php echo $i['recipient_country'][0]['iso'] ?>';
            
            if ( $.inArray( "<?php echo $i['recipient_country'][0]['name'] ?>", countries ) > -1 )
            {

            }else{
                countries.push("<?php echo $i['recipient_country'][0]['name'] ?>");
                
                
                country_keys["<?php echo $i['recipient_country'][0]['name'] ?>"] = "<?php echo $i['recipient_country'][0]['iso'] ?>";
                
                <?php foreach ($i['recipient_region'] as $region) :?>
                    region_keys["<?php echo $region['name'] ?>"] = "<?php echo $region['code'] ?>"
                <?php endforeach; ?>
                    
                <?php foreach ($i['activity_sectors'] as $sector) :?>
                    sector_keys["<?php echo $sector['name'] ?>"] = "<?php echo $sector['code'] ?>"
                <?php endforeach; ?>
            }
        }
        catch(err)
        {
            console.log('<?php echo $i['recipient_country'][0]['iso'] ?>'+err);
        }
            <?php endif ?>
        <?php endforeach;?>
        <?php 
        // end if parameters are set
        } else { ?>
        countryData = allCountryData;
        <?php
        }?>
        
        countries = countries.sort();
        
        country_html = create_filter_attributes(countries, country_keys);
        
        $('#country_filters').append(country_html);
        
        var regions = [];
        for (var key in region_keys){
            regions.push(key);
        }
        region_html = create_filter_attributes(regions, region_keys);
        $('#region_filters').append(region_html);
        
        var sectors = [];
        for (var key in sector_keys){
            sectors.push(key);
        }
        
        
        sector_html = create_filter_attributes(sectors, sector_keys);
        $('#sector_filters').append(sector_html);
        
        var budgets = [];
        for (var key in budget_keys){
            budgets.push(key);
        }
        budget_html = create_filter_attributes(budgets, budget_keys);
        $('#budget_filters').append(budget_html);


        L.geoJson(countryData, {style: style,onEachFeature: function(feature,layer) {
                var total_projects = feature.properties.projects;
                var str = "test"
                str += total_projects;
                  layer.bindPopup('<p>Total projects: '+str.substring(6)+ '</p><p><a href="?s=&countries='+feature.properties.iso+'">Click to view related projects</a></p>');
              }}).addTo(map);

//        var popup = L.popup()
//    .setLatLng(latlng)
//    .setContent('<p>Hello world!<br />This is a nice popup.</p>')
//    .openOn(map);
        
    }); 


    
</script>


