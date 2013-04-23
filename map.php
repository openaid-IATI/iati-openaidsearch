<div id="map-wrapper">

    <?php if(is_page("projects")){ get_template_part( "map", "selection" ); } ?>
    
    <?php if(is_home()){ get_template_part( "home", "lightbox" ); } ?>

    <div id="map-filter-overlay">
        <div class="container">
            <div class="row-fluid map-filter-list">
                   
                    <div id="countries-filters"></div>
                    <div id="regions-filters"></div>
                    <div id="sectors-filters"></div>
                    <div id="budgets-filters"></div>

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

<?php $hide_map = (!is_page("projects") && !is_home()); ?>

<div id="map-loader">
    <div id="map-loader-text">Reloading project map</div>
    <img src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" alt="" />
</div>
<div id="map" <?php if($hide_map){ echo 'style="height:13.5em"'; }?>></div>
<div id="map-border-bottom"></div>
</div>

<div id="map-hide-show">
    <div class="container">
        <div class="row-fluid">
            <div class="span12">
                <button id="map-hide-show-button" class="map-<?php if($hide_map) {echo "hide"; } else {echo "show"; } ?>">
                    <img class="hide-show-icon" src="<?php echo get_template_directory_uri(); ?>/images/hide-show.png" alt="" />
                    <span id="map-hide-show-text" class="hneue-bold"><?php if($hide_map) {echo "SHOW MAP"; } else {echo "HIDE MAP"; } ?></span>
                </button>
            </div>
        </div>
    </div>
</div>