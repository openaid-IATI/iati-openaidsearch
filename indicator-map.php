<div id="map-wrapper">
    



    <!-- Show selection button --> 
    <?php get_template_part( "map", "selection" ); ?>

    <!-- The 4 graph buttons in right top --> 
    <div id="map-graph-wrapper">
        <div id="map-graph-buttons">
            <a href="#" class="hneue-bold" id="graph-button-map"></a>
            <a href="#" class="hneue-bold" id="graph-button-graph"></a> 
            <a href="#" class="hneue-bold" id="graph-button-table"></a>
            <a href="#" class="hneue-bold" id="graph-button-treemap"></a>
        </div>
    </div>

    <div id="map-slider-tooltip">
   
    </div>

    <div id="map-timeline-wrapper">
        <?php for ($i = 1950; $i < 2051;$i++){   
        echo '<div class="slider-year';
//        if($i > 2000 && $i < 2013){ echo ' slider-active'; }
        echo '" id="year-' . $i . '">';
        echo '<div class="slider-year-inner-white"></div></div>'; 
        } ?>
    </div>


    <!-- The black overlay for filtering options --> 
	<div id="map-filter-overlay">
        <div class="container">
<!--                            var indicator_keys = {};
        indicator_keys['all'] = 'all';
        indicator_keys['avg_annual_rate_change_percentage_urban'] = 'test';
                indicator_keys['avg_annual_rate_change_total_population'] = 'test';
        indicator_keys['bottle_water'] = 'test';
        indicator_keys['composting_toilet'] = 'test';
        indicator_keys['connection_to_electricity'] = 'test';
        indicator_keys['deprivation'] = 'test';
        indicator_keys['enrollment_female_primary_education'] = 'test';
        indicator_keys['enrollment_male_primary_education'] = 'test';
        indicator_keys['has_telephone'] = 'test';
        indicator_keys['improved_floor'] = 'test';
        indicator_keys['improved_flush_toilet'] = 'test';
        indicator_keys['improved_pit_latrine'] = 'test';
        indicator_keys['improved_spring_surface_water'] = 'test';
        indicator_keys['improved_toilet'] = 'test';
        indicator_keys['improved_water'] = 'test';
        indicator_keys['piped_water'] = 'test';
        indicator_keys['pit_latrine_with_slab_or_covered_latrine'] = 'test';
        indicator_keys['pit_latrine_without_slab'] = 'test';
        indicator_keys['test'] = 'test';
        indicator_keys['test'] = 'test';
        indicator_keys['test'] = 'test';
        indicator_keys['test'] = 'test';
        indicator_keys['test'] = 'test';-->
			<div class="row-fluid map-filter-list">
                            <div id="indicator_filters">
				
                        </div>
                            <div id="region_filters"></div>
                            <div id="country_filters"></div>
                            <div id="city_filters"></div>
                            <div id="dimension_filters"></div>
                            
                            
                </div>
			
		</div>

		<div id="map-filters-buttons">
			<div class="container">
				<div class="row-fluid">
					<div class="span12">

						<button id="map-filter-cancel" class="hneue-bold">CANCEL</button>
						<button id="map-filter-save" class="hneue-bold">SAVE</button>

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

<div id="map-hide-show">
	<div class="container">
		<div class="row-fluid">
			<div class="span12">
				<button id="map-hide-show-button" class="map-show"><img class="hide-show-icon" src="<?php echo get_template_directory_uri(); ?>/images/hide-show.png" alt="" /><span id="map-hide-show-text" class="hneue-bold">HIDE MAP</span></button>
			</div>
		</div>
	</div>
</div>