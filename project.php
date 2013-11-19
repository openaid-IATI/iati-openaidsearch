<?php
/*
Template Name: Single project page
*/
?>

<?php get_header();

$project_id = $_REQUEST['id'];
$activity = wp_get_activity($project_id);

?>

<?php get_template_part( "map" ); ?>

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
				</ul>
			</div>
		</div>
	</div>

	<div class="page-full-width-line"></div>

	<div class="container">
		<div class="page-content project-page-content">
			<div class="row-fluid">
				<div class="span7 project-tabs-wrapper">

					<?php get_template_part( 'project', 'description' ); ?>
					<?php get_template_part( 'project', 'financials' ); ?>
					<?php get_template_part( 'project', 'documents' ); ?>
					<?php get_template_part( 'project', 'related-indicators' ); ?>
					<?php get_template_part( 'project', 'related-projects' ); ?>
					<?php get_template_part( 'project', 'located-in' ); ?>
				</div>
				<div class="span5">
					<div class="project-spec">

						<div class="projects-project-divider"></div>

						<div class="projects-project-spec-key">Countries:</div>
						<div class="projects-project-spec-value">

						<?php 
						if(!empty($activity->countries)) {
							$sep = '';
							foreach($activity->countries AS $country) {
								echo  $sep . "<a href='".get_bloginfo('url')."/projects/?countries={$country->code}'>" . $country->name . "</a>";
								$sep = ', ';
							}		
						}
						?>
						</div>

						<div class="projects-project-divider"></div>

						<div class="projects-project-spec-key">Regions:</div>
						<div class="projects-project-spec-value">

						<?php 
						if(!empty($activity->countries)) {
							$sep = '';
							foreach($activity->regions AS $region) {
								echo  $sep . "<a href='".get_bloginfo('url')."/projects/?regions={$region->code}'>" . $region->name . "</a>";
								$sep = ', ';
							}		
						}
						?>
						</div>

						<div class="projects-project-divider"></div>

						<div class="projects-project-spec-key">Principal sectors:</div>
						<div class="projects-project-spec-value">

						<?php 
						if(!empty($activity->sectors)) {
							$sep = '';
							foreach($activity->sectors AS $sector) {
								echo  $sep . "<a class='projects-description-link' href='?sectors={$sector->code}'>" . $sector->name . "</a>";
								$sep = ', ';
							}		
						} else {
							echo "No information avaiable";
						} ?>


						</div>

						<div class="projects-project-divider"></div>

						<div class="projects-project-spec-key">Budget:</div>
						<div class="projects-project-spec-value">

							<?php if(!empty($activity->total_budget)) {
								if(!empty($activity->default_currency)) { echo currencyCodeToSign($activity->default_currency->code); }
								echo format_custom_number($activity->total_budget);
							} else {
								echo "-";
							} ?>
						</div>

						<div class="projects-project-divider"></div>

						<div class="projects-project-spec-key">IATI identifier:</div>
						<div class="projects-project-spec-value">

							<a href="<?php echo site_url() . '/project/?id=' . $activity->iati_identifier; ?>" alt="See project details">
							<?php if(!empty($activity->iati_identifier)) { echo $activity->iati_identifier; } ?></div>
							</a>

							

						<div class="projects-project-divider"></div>

						<div class="projects-project-spec-key">Reporting organisation:</div>
						<div class="projects-project-spec-value">
						
						<?php 
							if(!empty($activity->reporting_organisation->name)){ echo $activity->reporting_organisation->name; } else {
							if(!empty($activity->reporting_organisation->code)){ echo $activity->reporting_organisation->code; } }
						?>

						</div>

						<div class="projects-project-divider"></div>

						<div class="projects-project-spec-key">Sectors code(s):</div>
						<div class="projects-project-spec-value">

							<?php 
							if(!empty($activity->sectors)) {
								$sep = '';
								foreach($activity->sectors AS $sector) {
									echo  $sep . "<a class='projects-description-link' href='?sectors={$sector->code}'>" . $sector->code . "</a>";
									$sep = ', ';
								}			
							} else {
								echo "No information avaiable";
							} ?>
						</div>

						<div class="projects-project-divider"></div>

						<div class="projects-project-spec-key">Last updated:</div>
						<div class="projects-project-spec-value">

							<?php if(!empty($activity->last_updated_datetime)) { echo $activity->last_updated_datetime; } ?></div>

						<div class="projects-project-divider"></div>

						<div class="projects-project-spec-key">Start date planned:</div>
						<div class="projects-project-spec-value">

							<?php if(!empty($activity->start_planned)) { echo $activity->start_planned; } ?></div>

						<div class="projects-project-divider"></div>

						<div class="projects-project-spec-key">End date planned:</div>
						<div class="projects-project-spec-value">

							<?php if(!empty($activity->end_planned)) { echo $activity->end_planned; } ?></div>

						<div class="projects-project-divider"></div>

						<div class="projects-project-spec-key">Activity status:</div>
						<div class="projects-project-spec-value">

							<?php if(!empty($activity->activity_status->name)) { echo $activity->activity_status->name; } ?>
						</div>

						<div class="projects-project-divider"></div>

						<div class="projects-project-spec-key">Participating organisations:</div>
						<div class="projects-project-spec-value">

							<?php 
							if(!empty($activity->participating_organisations)) {
								$sep = ', ';
								$part_org_text = '';

								foreach($activity->participating_organisations AS $participating_organisation) {
									if(empty($participating_organisation->name)) {
										$part_org_text .= $participating_organisation->code;

									} else {
										$part_org_text .= $participating_organisation->name . " (" . $participating_organisation->code . ")";
									}
									$part_org_text .= $sep;
								}
								
								$part_org_text = substr($part_org_text, 0, -2);
								echo $part_org_text;
							} else {
								echo "No information avaiable";
							} ?>

						</div>

						<div class="projects-project-divider"></div>

						<div class="project-share-container projects-share-spec">

							<button id="project-share-export" class="project-share-button hneue-bold" name="<?php if(!empty($activity->iati_identifier)) { echo $activity->iati_identifier; } ?>">
								<div class="share-icon"></div>
								<div class="share-text">EXPORT</div>
							</button>

							<span class="st_sharethis" st_url="<?php bloginfo('url'); ?>/project/?id=<?php if(!empty($activity->iati_identifier)) { echo $activity->iati_identifier; } ?>" st_title="<?php if (!empty($activity->titles)){ echo $activity->titles[0]->title; } else { echo "Unkown title"; }?>" displayText="SHARE"></span>

							<button class="project-share-button hneue-bold project-share-bookmark">
								<div class="share-icon"></div>
								<div class="share-text">BOOKMARK</div>
							</button>
							<button class="project-share-button hneue-bold project-share-whistleblower" name="<?php if(!empty($activity->iati_identifier)) { echo $activity->iati_identifier; } ?>">
								<div class="share-icon"></div>
								<div class="share-text">WHISTLEBLOWER</div>
							</button>

						</div>
					</div>
				</div>

			</div>

			<div class="page-full-width-line"></div>


			<div class="container">
				<div class="page-content project-page-content">
					<div class="row-fluid">
						<div class="span7">
							<div id="disqus_thread"></div>
						    <script>
						        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
						        var disqus_shortname = 'openunhabitat'; // required: replace example with your forum shortname
						 		var disqus_identifier = '<?php echo $activity->iati_identifier; ?>';
    							var disqus_title = '<?php echo $activity->titles[0]->title; ?>';
    							var disqus_url = '<?php echo "http://open.unhabitat.org/project/?id=" . $activity->iati_identifier; ?>';

						        /* * * DON'T EDIT BELOW THIS LINE * * */
						        (function() {
						            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
						            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
						            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
						        })();
						    </script>
						    <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
						    <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>

<script>

// PREPARE COUNTRIES FOR SHOWING ON MAP

	<?php if(!empty($activity->countries)) {
		$sep = '';
		$countries = "";
		foreach($activity->countries AS $country) {
			$countries .=  $sep . '"' . $country->code . '"';
			$sep = ', ';
		}
	}
	?>

	var project_countries = new Array(<?php echo $countries; ?>);

</script>

<?php get_template_part('footer-scripts'); ?>
<script>
  $(document).ready(function() {
      sanitize_project_url();
    });
</script>
<?php get_footer(); ?>


	
	