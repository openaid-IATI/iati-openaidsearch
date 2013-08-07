<div id="map-wrapper">
    



    <!-- Show selection button --> 
    <?php if(!is_home()){ get_template_part( "map", "selection" ); } ?>

    <?php if(is_home()){ get_template_part( "home", "lightbox" ); } ?>

    <?php if(is_page("indicators") | is_page("city-prosperity")){ ?>
    <!-- The 4 graph buttons in right top --> 
    <div id="map-graph-wrapper">
        <div id="map-graph-buttons">
            <a href="#" class="hneue-bold" id="graph-button-map"></a>
            <?php if(is_page("indicators")){ ?>
            <a href="#" class="hneue-bold" id="graph-button-graph"></a> 
            <?php } ?>
            <a href="#" class="hneue-bold" id="graph-button-table"></a>
            <a href="#" class="hneue-bold" id="graph-button-treemap"></a>
        </div>
    </div>
    <?php } ?>

    <?php if(!is_page("city-prosperity")){ ?>
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
    <?php } ?>



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

<div id="map-hide-show">
	<div class="container">
		<div class="row-fluid">
			<div class="span12">
				<button id="map-hide-show-button" class="map-show"><img class="hide-show-icon" src="<?php echo get_template_directory_uri(); ?>/images/hide-show.png" alt="" /><span id="map-hide-show-text" class="hneue-bold">HIDE MAP</span></button>
			</div>
		</div>
	</div>
</div>