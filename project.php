<?php
/*
Template Name: Single project page
*/

get_header();
$id = get_activity_id_in_wp();
$activity = oipa_get_activity($id);
if (!$activity):
?>

<div id="page-wrapper">
	<div class="page-content">
		<div id="no-projects-found">Activity not found.</div>
		</div>
	</div>
</div>
<?
else:

get_template_part( "map" );
?>
<div id="page-wrapper">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<a href="<?php echo bloginfo('url'); ?>/projects/" id="project-back-button">BACK TO SEARCH RESULTS</a>
			</div>
		</div>
	</div>
	<div class="page-full-width-line"></div>
	<div class="container">
		<div class="row">
			<div class="col-md-12 project-navbar">
				<ul class="nav nav-pills">
					<li class="active">
						<a data-target-div="project-description" href="javascript:void(0)">Description</a>
					</li>
					<li>
						<a data-target-div="project-financials" href="javascript:void(0)">Financials</a>
					</li>
					<li>
						<a data-target-div="project-documents" href="javascript:void(0)">Documents</a>
					</li>
					<li>
						<a data-target-div="project-rsr" href="javascript:void(0)">RSR / Local projects</a>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="page-full-width-line"></div>

	<div class="container">
		<div class="page-content project-page-content main-page-content">
			<div class="row">
				<div class="col-md-7 project-tabs-wrapper">
					<?php 
					include( TEMPLATEPATH .'/project-description.php' ); 
					include( TEMPLATEPATH .'/project-financials.php' );
					include( TEMPLATEPATH .'/project-documents.php' );
					?>
				</div>
				<div class="col-md-5">
					<?php 
					$rsr_loaded = false;
					include( TEMPLATEPATH .'/project-sidebar.php' ); 
					?>
				</div>
			</div>
		</div>
	</div>

	<div id="project-rsr">
		<?php
		include( TEMPLATEPATH .'/project-rsr.php' ); 
		?>
	</div>

	<div class="page-full-width-line"></div>

	<div class="container">
		<div class="page-content project-page-content">
			<div class="row">
				<div class="col-md-7">
					<div id="disqus_thread"></div>
				    <script>
				        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
				        var disqus_shortname = ''; // required: replace example with your forum shortname
				 		var disqus_identifier = '<?php echo $activity->iati_identifier; ?>';
						var disqus_title = '<?php echo $project_title; ?>';
						var disqus_url = '<?php echo site_url() . "/project/?iati_id=" . $activity->iati_identifier; ?>';

				        /* * * DON'T EDIT BELOW THIS LINE * * */
				        (function() {
				            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
				            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
				            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
				        })();
				    </script>
				    <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
				</div>
			</div>
		</div>
	</div>

</div>
<?php get_template_part('footer-scripts'); ?>
<script src="<?php echo get_template_directory_uri(); ?>/js/dependencies/countries.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/project.js"></script>
<script>

// PREPARE COUNTRIES FOR SHOWING ON MAP
	
	<?php 
	$recipient_countries_string = "";
	if(!empty($activity->recipient_countries)) {
		foreach($activity->recipient_countries AS $recipient_country) {
			$recipient_countries_arr[] = $recipient_country->country->code;
		}
		$recipient_countries_string = join(",", $recipient_countries_arr);

	}
	?>

	var project_countries = new Array("<?php echo $recipient_countries_string; ?>");

    var map = new OipaMap();
    map.set_map("map", "topright");
    map.selection = Oipa.mainSelection;
    map.selection.group_by = "country";
    Oipa.maps.push(map);

    var geocountries = {"type":"FeatureCollection","features":[]};

	for(var i = 0; i < countryData.features.length; i++){	

		if(jQuery.inArray(countryData.features[i].properties.iso2, project_countries) != -1){
			var geocountry = {
			    "type": "Feature",
			    "geometry": {
			        "type": "Polygon",
			        "coordinates": countryData.features[i].geometry.coordinates
			    }
			}
			geocountries.features.push(geocountry);
		}
	}

	var geojson = L.geoJson(geocountries, {
	    style: function(feature) {
	        	return {color: "orange"};
	        	return {fillColor: "orange"};
	    }
	});

	console.log(geocountries);
	console.log(geojson);
	geojson.addTo(map.map);




</script>
<?php endif; ?>

<script> refresh_rsr_projects("<?php echo $id; ?>"); </script>
<?php get_footer(); ?>


	
	
