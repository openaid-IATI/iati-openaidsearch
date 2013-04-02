<div id="map-wrapper">
    
    
    <div id="selection-hide-show">
        <div class="container">
            <div class="row-fluid">
                <div class="span12">
                    <button id="selection-hide-show-button" class="selection-show"><img class="hide-show-icon" src="<?php echo get_template_directory_uri(); ?>/images/hide-show.png" alt="" /><span id="selection-hide-show-text" class="hneue-bold">SHOW SELECTION</span></button>
                    <?php if(is_home()){ ?>
                        <div id="map-lightbox-bg"></div>
                        <div id="map-lightbox" class="hneue-light">
                            <div id="map-lightbox-text">
                                <p class="map-lightbox-title">Publicly available open data.</p>
                                <p>Information about UN-Habitats projects and programmes.</p>
                            </div>
                            <button id="map-lightbox-close"></button>
                            <a href="/projects/?hide_map=true">All projects</a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>



	<div id="map-filter-overlay">
        <div class="container">

			<div class="row-fluid map-filter-list">

				<?php 

                                
                               $indicators = array('avg_annual_rate_change_percentage_urban' => 'avg_annual_rate_change_percentage_urban',
                                                    'avg_annual_rate_change_total_population' => 'avg_annual_rate_change_total_population',
                                                    
                                                    'bottle_water' => 'bottle_water',
                                                    'composting_toilet' => 'composting_toilet',
                                                    'connection_to_electricity' => 'avg_annual_rate_change_percentage_urban',
                                                    'deprivation' => 'deprivation',
                                                    'enrollment_female_primary_education' => 'enrollment_female_primary_education',
                                                    'enrollment_male_primary_education' => 'enrollment_male_primary_education',
                                                    'has_telephone' => 'has_telephone',
                                                    'improved_floor' => 'improved_floor',
                                                    'improved_flush_toilet' => 'improved_flush_toilet',
                                                    'improved_pit_latrine' => 'improved_pit_latrine',
                                                    'improved_spring_surface_water' => 'improved_spring_surface_water',
                                                    'improved_toilet' => 'improved_toilet',
                                                    'improved_water' => 'improved_water',
                                                    'piped_water' => 'piped_water',
                                                    'pit_latrine_with_slab_or_covered_latrine' => 'pit_latrine_with_slab_or_covered_latrine',
                                                    'pit_latrine_without_slab' => 'pit_latrine_without_slab',
                                                    'pop_rural_area' => 'pop_rural_area',
                                                    'pop_urban_area' => 'pop_urban_area',
                                                    'pop_urban_percentage' => 'pop_urban_percentage',
                                                    'population' => 'population',
                                                    'protected_well' => 'protected_well',
                                                    'public_tap_pump_borehole' => 'public_tap_pump_borehole',
                                                    'pump_borehole' => 'pump_borehole',
                                                    'rainwater' => 'rainwater',
                                                    'slum_proportion_living_urban' => 'slum_proportion_living_urban',
                                                    'sufficient_living' => 'sufficient_living',
                                                    'under_five_mortality_rate' => 'under_five_mortality_rate',
                                                    'urban_population' => 'urban_population',
                                                    'urban_slum_population' => 'urban_slum_population'

                );
                               ?>
                            <?php $c = 0;?>
                            <?php foreach ($indicators as $key => $value) :?>
                            
                            <?php if(in_array($c, array(0, 15, 30, 45, 60))) : ?>
                                    <div class="span2">
                            <?php endif ?>
                            
                            <div class="squaredThree">
						<input type="checkbox" value="None" id="land<?php echo $key; ?>" name="check" />
						<label class="map-filter-cb-value" for="land<?php echo $key; ?>"></label>
						<span><?php echo $key; ?></span>
					</div>
                                        
                             <?php if(in_array($c, array(14,29,44, 59,74))) : ?>
                                    </div>
                            <?php endif ?>
                                        
                                        
                            <?php $c++;?>
                            <?php endforeach;?>
            </div>
                                
				
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
	<div id="map"></div>
    <div id="map-border-bottom"></div>
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