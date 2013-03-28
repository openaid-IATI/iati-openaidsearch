<?php
wp_generate_results_v2($objects, $meta);

foreach($objects AS $idx=>$project) {
?>

<div class="row-fluid projects-description <?php echo $project->iati_identifier; ?>">
	<div class="span7">
		<div class="projects-project-title">
			<a href="<?php echo site_url() . '/project/?id=' . $project->iati_identifier; ?>" alt="See project details"><?php echo $project->titles[0]->title; ?></a>
		</div>
		<div class="projects-project-description <?php echo $project->iati_identifier; ?>">
			<?php echo $project->descriptions[0]->description; ?>
		</div>
		
	</div>

	<div class="span5">
		<!-- TO DO: REPLACE PROJECTIDENTIFIER --> 
		<button id="<?php echo $project->iati_identifier; ?>" class="project-expand-button expand-plus" title="Show more"></button>

		<div class="projects-project-spec">


			<div class="projects-project-divider"></div>

			<div class="projects-project-spec-key">Countries:</div>
			<div class="projects-project-spec-value">

				<?php if(!empty($project->recipient_country)) {
					$sep = '';
					$countries = "";
					$cSep = "";
					foreach($project->recipient_country AS $country) {
						echo  $sep . "<a href='".get_bloginfo('url')."/?s=&amp;countries={$country->iso}'>" . $country->name . "</a>";
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

				<?php if(!empty($project->activity_sectors)) {
					$sep = '';
					foreach($project->activity_sectors AS $sector) {
						if($sector->name=='No information available') {
							echo $sector->name;
						} else {
							echo  $sep . "<a href='".get_bloginfo('url')."/?s=&amp;sectors={$sector->code}'>" . $sector->name . "</a>";
						}
							$sep = ', ';
						}
				} ?>
			</div>

			<div class="projects-project-divider"></div>

			<div class="projects-project-spec-key">Budget:</div>
			<div class="projects-project-spec-value">

				<?php if(!empty($project->statistics->total_budget)) {?>
					US$ <?php echo format_custom_number($project->statistics->total_budget);  ?>
				<?php } ?>

			</div>

			<div class="projects-project-divider"></div>

			<div class="projects-project-spec-key">IATI identifier:</div>
			<div class="projects-project-spec-value">

			<?php if(!empty($project->iati_identifier)) { echo $project->iati_identifier; } ?>

			</div>

			<div class="projects-project-divider"></div>

			<div class="project-project-spec-hidden <?php echo $project->iati_identifier; ?>">

			<div class="projects-project-spec-key">Reporting organisation:</div>
			<div class="projects-project-spec-value">

				<?php if(!empty($project->reporting_organisation->org_name)) { echo $project->reporting_organisation->org_name; } ?>

			</div>

			<div class="projects-project-divider"></div>

			<div class="projects-project-spec-key">Sector code:</div>
			<div class="projects-project-spec-value">

			<?php if(!empty($project->activity_sectors[0]->code)) { echo $project->activity_sectors[0]->code; } ?>

			</div>

			<div class="projects-project-divider"></div>

			<div class="projects-project-spec-key">Last updated:</div>
			<div class="projects-project-spec-value">

			<?php if(!empty($project->date_updated)) { echo $project->date_updated; } ?>

			</div>

			<div class="projects-project-divider"></div>

			<div class="projects-project-spec-key">Start date planned:</div>
			<div class="projects-project-spec-value">

			 <?php if(!empty($project->start_planned)) { echo $project->start_planned; } ?>

			</div>

			<div class="projects-project-divider"></div>

			<div class="projects-project-spec-key">End date planned:</div>
			<div class="projects-project-spec-value">

			<?php if(!empty($project->end_planned)) { echo $project->end_planned; } ?>

			</div>

			<div class="projects-project-divider"></div>

			<div class="projects-project-spec-key">Activity status:</div>
			<div class="projects-project-spec-value">

			<?php if(!empty($project->activity_status->name)) { echo $project->activity_status->name; } ?>

			</div>

			<div class="projects-project-divider"></div>

			<div class="projects-project-spec-key">Name participating organisation:</div>
			<div class="projects-project-spec-value">

			<?php if(!empty($project->reporting_organisation->org_name)) { echo $project->reporting_organisation->org_name; } ?>

			</div>

			<div class="projects-project-divider"></div>

			<div class="projects-project-spec-key">Organisation reference code:</div>
			<div class="projects-project-spec-value">
			
			<?php if(!empty($project->reporting_organisation->ref)) { echo $project->reporting_organisation->ref; } ?>

			</div>

			<div class="projects-project-divider"></div>

			<div class="project-share-container">

				<button id="project-share-export" class="project-share-button hneue-bold">
					<div class="share-icon"></div>
					<div class="share-text">EXPORT</div>
				</button>
				<button id="project-share-share" class="project-share-button hneue-bold">
					<div class="share-icon"></div>
					<div class="share-text">SHARE</div>
				</button>
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


<?php } ?>