<div id="map-wrapper">

    <?php if(is_page("projects")){ get_template_part( "map", "selection" ); } ?>
    

    <?php if(is_home()){ get_template_part( "home", "lightbox" ); } ?>

    <div id="map-filter-overlay">
        <div class="container">

            <div class="row-fluid map-filter-list">
                   
                
                    <div id="country_filters"></div>
                    <div id="region_filters"></div>
                    <div id="sector_filters"></div>
                    <div id="budget_filters"></div>

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