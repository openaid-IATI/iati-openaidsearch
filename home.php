<?php get_header(); ?>

<?php get_template_part( "map" ); ?>

<div id="page-wrapper">
	<div class="container">
		<div class="page-content">
			<div class="row-fluid postit-page home-page">
				<div class="span4">
				<?php 

				$cat_obj = get_category_by_slug('row-1'); 
				$row_cat_id = $cat_obj->term_id;

				$args = array(
				'posts_per_page'  => 15,
				'numberposts'     => 15,
				'category'        => $row_cat_id,
				'order'           => 'ASC',
				'post_type'       => 'homepage-item',
				);

			 	$row1_posts_array = get_posts( $args );

 				foreach( $row1_posts_array as $post ) :	setup_postdata($post); ?>

					<div class="drop-shadow postit">
						<div class="postit-title hneue-light"><?php the_title(); ?></div>
						<div class="postit-text"><?php the_content(); ?></div>
						<div class="postit-thumb"><?php the_post_thumbnail(); ?></div>
					</div>

				<?php endforeach; ?>

					

				</div>
				<div class="span4">
							<?php 

				$cat_obj = get_category_by_slug('row-2'); 
				$row_cat_id = $cat_obj->term_id;

				$args = array(
				'posts_per_page'  => 15,
				'numberposts'     => 15,
				'category'        => $row_cat_id,
				'order'           => 'ASC',
				'post_type'       => 'homepage-item',
				);

			 	$row1_posts_array = get_posts( $args );

 				foreach( $row1_posts_array as $post ) :	setup_postdata($post); ?>

					<div class="drop-shadow postit">
						<div class="postit-title hneue-light"><?php the_title(); ?></div>
						<div class="postit-text"><?php the_content(); ?></div>
						<div class="postit-thumb"><?php the_post_thumbnail(); ?></div>
					</div>

				<?php endforeach; ?>
				</div>
				<div class="span4">
							<?php 

				$cat_obj = get_category_by_slug('row-3'); 
				$row_cat_id = $cat_obj->term_id;

				$args = array(
				'posts_per_page'  => 15,
				'numberposts'     => 15,
				'category'        => $row_cat_id,
				'order'           => 'ASC',
				'post_type'       => 'homepage-item',
				);

			 	$row1_posts_array = get_posts( $args );

 				foreach( $row1_posts_array as $post ) :	setup_postdata($post); ?>

					<div class="drop-shadow postit">
						<div class="postit-title hneue-light"><?php the_title(); ?></div>
						<div class="postit-text"><?php the_content(); ?></div>
						<div class="postit-thumb"><?php the_post_thumbnail(); ?></div>
					</div>

				<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>


<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/dependencies/all_projects_data.js"></script>
        
<script type="text/javascript">
    

    $(document).ready(function() {

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
            .setContent('<div id="map-tip-header">' + layer.feature.properties.name + '</div><div id="map-tip-text">Total projects: '+ layer.feature.properties.projects + '</div><div id="map-tip-link"><a href="projects/?s=&countries='+layer.feature.properties.iso+'">Click to view related projects</a></div>')
            .openOn(map);
        }

        function resetHighlight(e) {
            geojson.resetStyle(e.target);
        }

        geojson = L.geoJson(allCountryData, {style: style,onEachFeature: function(feature,layer) {

            layer.on({
                mouseover: highlightFeature,
                mouseout: resetHighlight,
                click: showPopup
            });
        }}).addTo(map); 
    }); 
</script>
