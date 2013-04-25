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
				<button id="project-back-button">BACK TO SEARCH RESULTS</button>
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
						<a id="project-commitments-link" href="#project-commitments">Commitments</a>
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
					<?php get_template_part( 'project', 'commitments' ); ?>
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

							<?php if(!empty($activity->
							recipient_country)) {
					$sep = '';
					$countries = "";
					$cSep = "";
					foreach($activity->recipient_country AS $country) {
						echo  $sep . "
							<a href='".get_bloginfo('url')."/projects/?countries={$country->iso}'>" . $country->name . "</a>
							";
						$countries .= $cSep . $country->iso;
						$sep = ', ';
						$cSep = '|';
					}
				}
				?>
						</div>

						<div class="projects-project-divider"></div>

						<div class="projects-project-spec-key">Principal sector:</div>
						<div class="projects-project-spec-value">

							<?php if(!empty($activity->
							activity_sectors)) {
					$sep = '';
					foreach($activity->activity_sectors AS $sector) {
						if($sector->name=='No information available') {
							echo $sector->name;
						} else {
							echo  $sep . "
							<a href='".get_bloginfo('url')."/projects/?sectors={$sector->code}'>" . $sector->name . "</a>
							";
						}
							$sep = ', ';
						}
				} ?>
						</div>

						<div class="projects-project-divider"></div>

						<div class="projects-project-spec-key">Budget:</div>
						<div class="projects-project-spec-value">

							<?php if(!empty($activity->
							statistics->total_budget)) {?>
							US$
							<?php echo format_custom_number($activity->
							statistics->total_budget);  ?>
							<?php } ?></div>

						<div class="projects-project-divider"></div>

						<div class="projects-project-spec-key">IATI identifier:</div>
						<div class="projects-project-spec-value">

							<?php if(!empty($activity->iati_identifier)) { echo $activity->iati_identifier; } ?></div>

						<div class="projects-project-divider"></div>

						<div class="projects-project-spec-key">Reporting organisation:</div>
						<div class="projects-project-spec-value">

							<?php if(!empty($activity->
							reporting_organisation->org_name)) { echo $activity->reporting_organisation->org_name; } ?>
						</div>

						<div class="projects-project-divider"></div>

						<div class="projects-project-spec-key">Sector code:</div>
						<div class="projects-project-spec-value">

							<?php if(!empty($activity->
							activity_sectors[0]->code)) { echo "<a href='".get_bloginfo('url')."/projects/?sectors={$sector->code}'>" . $activity->activity_sectors[0]->code . "</a>"; } ?>
						</div>

						<div class="projects-project-divider"></div>

						<div class="projects-project-spec-key">Last updated:</div>
						<div class="projects-project-spec-value">

							<?php if(!empty($activity->date_updated)) { echo $activity->date_updated; } ?></div>

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

							<?php if(!empty($activity->
							activity_status->name)) { echo $activity->activity_status->name; } ?>
						</div>

						<div class="projects-project-divider"></div>

						<div class="projects-project-spec-key">Name participating organisation:</div>
						<div class="projects-project-spec-value">

							<?php if(!empty($activity->
							reporting_organisation->org_name)) { echo $activity->reporting_organisation->org_name; } ?>
						</div>

						<div class="projects-project-divider"></div>

						<div class="projects-project-spec-key">Organisation reference code:</div>
						<div class="projects-project-spec-value">

							<?php if(!empty($activity->
							reporting_organisation->ref)) { echo $activity->reporting_organisation->ref; } ?>
						</div>

						<div class="projects-project-divider"></div>

						<div class="project-share-container projects-share-spec">

							<button id="project-share-export" class="project-share-button hneue-bold" name="<?php if(!empty($activity->iati_identifier)) { echo $activity->iati_identifier; } ?>">
								<div class="share-icon"></div>
								<div class="share-text">EXPORT</div>
							</button>

							<span class="st_sharethis" st_url="<?php bloginfo('url'); ?>/project/?id=<?php if(!empty($activity->iati_identifier)) { echo $activity->iati_identifier; } ?>" st_title="<?php echo $activity->titles[0]->title; ?>" displayText="SHARE"></span>

							<button id="project-share-bookmark" class="project-share-button hneue-bold">
								<div class="share-icon"></div>
								<div class="share-text">BOOKMARK</div>
							</button>
							<button id="project-share-whistleblower" class="project-share-button hneue-bold">
								<div class="share-icon"></div>
								<div class="share-text">WHISTLEBLOWER</div>
							</button>

						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
	</div>
	<script type="text/javascript">

	// PREPARE COUNTRIES FOR SHOWING ON MAP

		<?php if(!empty($activity->recipient_country)) {
			$sep = '';
			$countries = "";
			foreach($activity->recipient_country AS $country) {
				$countries .=  $sep . '"' . $country->iso . '"';
				$sep = ', ';
			}
		}
		?>

		var project_countries = new Array(<?php echo $countries; ?>);

	</script>


	<?php get_footer(); ?>
	
	