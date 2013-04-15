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

                        

                        <div id="sort-type-startdate" class="project-sort-type">
                            <span class="project-sort-text hneue-bold">START DATE</span>
                            <span class="project-sort-icon"></span>
                            <div id="dropdown-project-startdate" class="dropdown-project">
                                <?php $params['order_by'] = "start_planned"; ?>
                                <a href="?<?php echo http_build_query($params); ?>" id="dropdown-project-startdate-asc">ASCENDING</a>
                                <?php $params['order_by'] = "-start_planned"; ?>
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
        $projects = wp_get_activities();

         //if parameters are set
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
       <?php } ?>
<script type="text/javascript">

    $(document).ready(function() {

        var countries = new Array();
        var country_keys = {};
        var region_keys = {};
        var sector_keys = {};
        var budget_keys = {};
        budget_keys['all'] = 'All';
        budget_keys[''] = '> US$ 0';
        budget_keys['10000'] = '> US$ 10.000';
        budget_keys['50000'] = '> US$ 50.000';
        budget_keys['100000'] = '> US$ 100.000';
        budget_keys['500000'] = '> US$ 500.000';
        budget_keys['1000000'] = '> US$ 1.000.000';
        
        <?php 
        // if parameters set
        if(count($_GET)) {

        foreach($projects as $i) :?>
                <?php if (!empty($i['recipient_country'])) :?>
        try
        
        {   
            var iso3 = jsonPath(country_info, "$[?(@.ISO2=='<?php echo $i['recipient_country'][0]['iso'] ?>')]")[0].ISO3
            
            jsonPath(countryData, "$..features[?(@.id=='"+ iso3 +"')]")[0].properties.projects = '<?php echo $totals[$i['recipient_country'][0]['iso']]['total_cnt'] ?>';//Run some code here
            jsonPath(countryData, "$..features[?(@.id=='"+ iso3 +"')]")[0].properties.iso = '<?php echo $i['recipient_country'][0]['iso'] ?>';
            
            if ( $.inArray( "<?php echo $i['recipient_country'][0]['name'] ?>", countries ) > -1 )
            {

            }else{
                countries.push("<?php echo $i['recipient_country'][0]['name'] ?>");
                
                
                country_keys["<?php echo $i['recipient_country'][0]['iso'] ?>"] = "<?php echo $i['recipient_country'][0]['name'] ?>";
                
                <?php foreach ($i['recipient_region'] as $region) :?>
                    region_keys["<?php echo $region['code'] ?>"] = "<?php echo $region['name'] ?>"
                <?php endforeach; ?>
                    
                <?php foreach ($i['activity_sectors'] as $sector) :?>
                    sector_keys["<?php echo $sector['code'] ?>"] = "<?php echo $sector['name'] ?>"
                <?php endforeach; ?>
            }
        }
        catch(err)
        {
            console.log('<?php echo $i['recipient_country'][0]['iso'] ?>'+err);
        }
            <?php endif ?>
        <?php endforeach;?>

        country_html = create_filter_attributes(country_keys, 4);
        $('#country-filters').append(country_html);
        
        region_html = create_filter_attributes(region_keys, 3);
        $('#region-filters').append(region_html);
        
        sector_html = create_filter_attributes(sector_keys, 3);
        $('#sector-filters').append(sector_html);

        budget_html = create_filter_attributes(budget_keys, 3);
        $('#budget-filters').append(budget_html);

        <?php 
        // end if parameters are set
        } else { ?>
        countryData = allCountryData;
        country_keys = {"KP":"Korea, Democratic People's Republic of","NG":"Nigeria","ID":"Indonesia","LY":"Libyan Arab Jamahiriya","EG":"Egypt","MZ":"Mozambique","SO":"Somalia","CD":"Democratic Republic of the Congo","MW":"Malawi","BF":"Burkina Faso","SN":"Senegal","MG":"Madagascar","SD":"Sudan","LK":"Sri Lanka","AF":"Afghanistan","PH":"Philippines","MN":"Mongolia","VN":"Viet Nam","PK":"Pakistan","MM":"Burma","TL":"Timor-Leste","CN":"China","CR":"Costa Rica","BR":"Brazil","EC":"Ecuador","SV":"El Salvador","GT":"Guatemala","HT":"Haiti","CU":"Cuba","IQ":"Iraq","JP":"Japan"};
        region_keys = {"298":"Africa, regional","489":"South America, regional","589":"Middle East, regional","798":"Asia, regional","889":"Oceania, regional"} ;
        sector_keys = {"11420":"Higher education","11430":"Advanced technical and managerial training","13010":"Population policy and administrative management","16030":"Housing policy and administrative management","16050":"Multisector aid for basic social services","23010":"Energy policy and administrative management","43030":"Urban development and management","43082":"Research/scientific institutions","74010":"Disaster prevention and preparedness","99810":"Sectors not specified"};
        budget_keys = {"all":"All", "":"> US$ 0", "10000":"> US$ 10.000","50000":"> US$ 50.000","100000":"> US$ 100.000","500000":"> US$ 500.000","1000000":"> US$ 1.000.000"};


        country_html = create_filter_attributes(country_keys, 4);
        $('#country-filters').append(country_html);
        region_html = create_filter_attributes(region_keys, 3);
        $('#region-filters').append(region_html);
        sector_html = create_filter_attributes(sector_keys, 3);
        $('#sector-filters').append(sector_html);
        budget_html = create_filter_attributes(budget_keys, 3);
        $('#budget-filters').append(budget_html);

        // end else
        <?php
        }?>
        

        var geojson;

        function getColor(d) {
            return d > 6  ? '#045A8D' :
                   d > 1   ? '#2476A2' :
                   d > 0   ? '#2B8CBE' :
            // return d > 8  ? '#FE6305' :
            //        d > 4   ? '#FE7421' :
            //        d > 0   ? '#FE8236' :
                   //d > 220   ? '#2B8CBE' :
                              'transparent';
        }

              // return d > 8  ? '#045A8D' :
              //      d > 4   ? '#2476A2' :
              //      d > 0   ? '#2B8CBE' :

        function getWeight(d) {
            return d > 0  ? 1 :
                              0;
        }

        function style(feature) {
            return {
                fillColor: getColor(feature.properties.projects),
                weight: getWeight(feature.properties.projects),
                opacity: 1,
                color: '#FFF',
                dashArray: '',
                fillOpacity: 0.7
            };
        }

        function highlightFeature(e) {
            var layer = e.target;
            
            if(typeof layer.feature.properties.projects != "undefined"){
                
                if (currently_selected_country != layer.feature.properties.name){
                    set_currently_selected_country(layer.feature.properties.name);
                    showPopup(e);
                }

                layer.setStyle({
                    weight: 2,
                    fillOpacity: 0.9
                });

                if (!L.Browser.ie && !L.Browser.opera) {
                    layer.bringToFront();
                }
            }
        }

        function showPopup(e){
            var layer = e.target;
            var mostNorth = layer.getBounds().getNorthWest().lat;
            var mostSouth = layer.getBounds().getSouthWest().lat;
            var center = layer.getBounds().getCenter();
            var heightToDraw = ((mostNorth - mostSouth) / 4) + center.lat;
            var pointToDraw = new L.LatLng(heightToDraw, center.lng);

            var popup = L.popup()
            .setLatLng(pointToDraw)
            .setContent('<div id="map-tip-header">' + layer.feature.properties.name + '</div><div id="map-tip-text">Total projects: '+ layer.feature.properties.projects + '</div><div id="map-tip-link"><a href="?s=&countries='+layer.feature.properties.iso+'">Click to view related projects</a></div>')
            .openOn(map);
        }

        function resetHighlight(e) {
            geojson.resetStyle(e.target);
        }

        geojson = L.geoJson(countryData, {style: style,onEachFeature: function(feature,layer) {

            layer.on({
                mouseover: highlightFeature,
                mouseout: resetHighlight,
                click: showPopup
            });
        }}).addTo(map); 
    }); 


    
</script>


