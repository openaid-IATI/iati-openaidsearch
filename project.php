<?php
/*
Template Name: Single project page
*/
?>

<?php 

get_header();


$found = false;


if (isset($_REQUEST['iati_id'])){
	$iati_id = $_REQUEST['iati_id'];
	$activity = wp_get_activity($iati_id);
	get_template_part( "map" );
	if ($activity){
		$found = true;
	}
} else if (isset($_REQUEST['id'])){
	$iati_id = $_REQUEST['id'];
	$activity = wp_get_activity($iati_id);
	get_template_part( "map" );
	if ($activity){
		$found = true;
	}
}

if (!$found){
?>

<div id="page-wrapper">
	<div class="page-content">
		<div id="no-projects-found">Activity not found.</div>
		</div>
	</div>
</div>

<?
} else {

?>


<div id="page-wrapper">

	<div class="container">
		<div class="row-fluid">
			<div class="span12">
				<a href="<?php echo bloginfo('url'); ?>/projects/" id="project-back-button">BACK TO SEARCH RESULTS</a>
			</div>
		</div>
	</div>

	<div class="page-full-width-line"></div>

	<div class="container">
		<div class="row-fluid">
			<div class="span12 project-navbar">
				<ul class="nav nav-pills">
					<li class="active">
						<a id="project-description-link" href="#project-description">Description</a>
					</li>
					<li>
						<a id="project-financials-link" href="#project-financials">Financials</a>
					</li>
					<li>
						<a id="project-documents-link" href="#project-documents">Documents</a>
					</li>
					<li>
						<a id="project-related-projects-link" href="#project-related-projects">Related projects</a>
					</li>
					<li>
						<a id="project-related-indicators-link" href="#project-related-indicators">Related indicators</a>
					</li>
					<li>
						<a id="project-located-in-link" href="#project-located-in">Located in</a>
					</li>
					<li>
						<a id="project-rsr-link" href="#project-rsr">RSR / Local projects</a>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="page-full-width-line"></div>

	<div class="container">
		<div class="page-content project-page-content main-page-content">
			<div class="row-fluid">
				<div class="span7 project-tabs-wrapper">

					<?php 
					include( TEMPLATEPATH .'/project-description.php' ); 
					include( TEMPLATEPATH .'/project-financials.php' );
					include( TEMPLATEPATH .'/project-documents.php' );
					include( TEMPLATEPATH .'/project-related-indicators.php' );
					include( TEMPLATEPATH .'/project-related-projects.php' );
					include( TEMPLATEPATH .'/project-located-in.php' ); 

					$rsr_loaded = false;
					?>
					
					
				</div>
				<div class="span5">
					<?php 
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
			<div class="row-fluid">
				<div class="span7">
					<div id="disqus_thread"></div>
				    <script>
				        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
				        var disqus_shortname = ''; // required: replace example with your forum shortname
				 		var disqus_identifier = '<?php echo $activity->iati_identifier; ?>';
						var disqus_title = '<?php echo $activity->titles[0]->title; ?>';
						var disqus_url = '<?php echo site_url() . "/project/?id=" . $activity->iati_identifier; ?>';

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
<script>

// PREPARE COUNTRIES FOR SHOWING ON MAP
	
	<?php 
	$countries = "";
	if(!empty($activity->countries)) {
		$sep = '';
		foreach($activity->countries AS $country) {
			$countries .=  $sep . '"' . $country->code . '"';
			$sep = ', ';
		}
	}
	?>

	var project_countries = new Array(<?php echo $countries; ?>);

</script>
<?php } ?>
<?php get_template_part('footer-scripts'); ?>
<script> refresh_rsr_projects("<?php echo $iati_id; ?>"); </script>
<?php get_footer(); ?>


	
	