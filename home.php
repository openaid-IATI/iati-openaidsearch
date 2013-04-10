<?php get_header(); ?>

<?php get_template_part( "map" ); ?>

<div id="page-wrapper">
	<div class="container">
		<div class="page-content">
			<div class="row-fluid postit-page">
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
						<div class="postit-text">
							<?php the_content(); ?>
							<img src="<?php echo get_template_directory_uri(); ?>/images/pop-search-arrow.png" width"25" height="20">
							<img src="<?php echo get_template_directory_uri(); ?>/images/pop-search-arrow.png" width"25" height="20">
							<img src="<?php echo get_template_directory_uri(); ?>/images/pop-search-arrow.png" width"25" height="20">
							<img src="<?php echo get_template_directory_uri(); ?>/images/pop-search-arrow.png" width"25" height="20">
							<img src="<?php echo get_template_directory_uri(); ?>/images/pop-search-arrow.png" width"25" height="20">

						</div>

					</div>

				<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>


        <?php 
        $projects = wp_get_activities();// print_r($projects);
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
        
        ?>

<script type="text/javascript">
    

    $(document).ready(function() {

        <?php foreach($projects as $i) :?>
                <?php if (!empty($i['recipient_country'])) :?>
        try
        
        {   
            var iso3 = jsonPath(country_info, "$[?(@.ISO2=='<?php echo $i['recipient_country'][0]['iso'] ?>')]")[0].ISO3
            
            jsonPath(countryData, "$..features[?(@.id=='"+ iso3 +"')]")[0].properties.projects = '23<?php echo $totals[$i['recipient_country'][0]['iso']]['total_cnt'] ?>';//Run some code here
            jsonPath(countryData, "$..features[?(@.id=='"+ iso3 +"')]")[0].properties.iso = '<?php echo $i['recipient_country'][0]['iso'] ?>';
            
        }
        catch(err)
        {
            console.log('<?php echo $i['recipient_country'][0]['iso'] ?>'+err);
        }
            <?php endif ?>
        <?php endforeach;?>
     

        L.geoJson(countryData, {style: style,onEachFeature: function(feature,layer) {
                var total_projects = feature.properties.projects;
                var str = "test"
                str += total_projects;
                  layer.bindPopup('<p>Total projects: '+str.substring(6)+ '</p><p><a href="/projects/?s=&countries='+feature.properties.iso+'">Click to view related projects</a></p>');
              }}).addTo(map);
        
//        var popup = L.popup()
//    .setLatLng(latlng)
//    .setContent('<p>Hello world!<br />This is a nice popup.</p>')
//    .openOn(map);
        
    }); 
</script>
