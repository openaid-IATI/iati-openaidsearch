<div id="map-wrapper">


    <div id="selection-hide-show">
        <div class="container">
            <div class="row-fluid">
                <div class="span12">
                    <button id="selection-hide-show-button" class="selection-show"><img class="hide-show-icon" src="<?php echo get_template_directory_uri(); ?>/images/hide-show.png" alt="" /><span id="selection-hide-show-text" class="hneue-bold">SHOW SELECTION</span></button>
                    <?php if (is_home()) { ?>
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
                

               
                
                
                
                    <div id="country_filters" class="hide"></div>
                    
                    <div id="region_filters" class="hide"></div>
                    <div id="sector_filters" class="hide"></div>
                    <div id="budget_filters" class="hide"></div>
                                                 
                                        
                                                          
                


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