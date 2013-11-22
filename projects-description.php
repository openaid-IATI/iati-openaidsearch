<?php
wp_generate_results_v2($objects, $meta);

foreach($objects AS $idx=>$project) {

?>

<div class="projects-project-block">
<div class="container">
	<div class="row-fluid projects-description <?php echo $project->iati_identifier; ?>">
		<div class="span7">
			<div class="projects-project-title">
				<a href="<?php echo site_url() . '/project/?id=' . $project->iati_identifier; ?>" alt="See project details">
					<?php if (!empty($project->titles)){ 
						echo $project->titles[0]->title; 
					} else {
						echo "Unknown title";
					}?>
				</a>
			</div>
			<div class="projects-project-description <?php echo $project->iati_identifier; ?>">

			<?php if (!empty($project->descriptions)){ 
					echo $project->descriptions[0]->description;
				} else {
					echo "No description given.";
				}?>

			</div>
			
		</div>

		<div class="span5">
			<button id="<?php echo $project->iati_identifier; ?>" class="project-expand-button expand-plus" title="Show more"></button>

			<div class="projects-project-spec">


				<div class="projects-project-divider"></div>

				<div class="projects-project-spec-key">Countries:</div>
				<div class="projects-project-spec-value">

				<?php 

				if(!empty($project->countries)) {
					$sep = '';
					foreach($project->countries AS $country) {
						echo  $sep . "<a class='projects-description-link' href='?countries={$country->code}'>" . $country->name . "</a>";
						$sep = ', ';
					}
				}
				?>

				</div>

				<div class="projects-project-divider"></div>

				<div class="projects-project-spec-key">Principal sector:</div>
				<div class="projects-project-spec-value">



					<?php 
				if(!empty($project->sectors)) {
					$sep = '';
					foreach($project->sectors AS $sector) {
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


					<?php if(!empty($project->total_budget)) {
						if(!empty($project->default_currency)) { echo currencyCodeToSign($project->default_currency->code); }
						echo format_custom_number($project->total_budget);
					} else {
						echo "-";
					} ?>


				</div>

				

				<div class="projects-project-divider"></div>

				<div class="projects-project-spec-key">IATI identifier:</div>
				<div class="projects-project-spec-value">
				<a href="<?php echo site_url() . '/project/?id=' . $project->iati_identifier; ?>" alt="See project details">
				<?php if(!empty($project->iati_identifier)) { echo $project->iati_identifier; } ?>
				</a>
				</div>

				<div class="projects-project-divider"></div>

				<div class="projects-project-spec-key">Start date planned:</div>
				<div class="projects-project-spec-value">

				 <?php if(!empty($project->start_planned)) { echo $project->start_planned; } ?>

				</div>

				<div class="projects-project-divider"></div>

				<div class="project-project-spec-hidden <?php echo $project->iati_identifier; ?>">


				<div class="projects-project-spec-key">Last updated:</div>
				<div class="projects-project-spec-value">

				<?php if(!empty($project->last_updated_datetime)) { echo $project->last_updated_datetime; } ?>

				</div>

				<div class="projects-project-divider"></div>

				<div class="projects-project-spec-key">End date planned:</div>
				<div class="projects-project-spec-value">

				<?php if(!empty($project->end_planned)) { echo $project->end_planned; } ?>

				</div>

				<div class="projects-project-divider"></div>

				<div class="projects-project-spec-key">Reporting organisation:</div>
				<div class="projects-project-spec-value">

					<?php 
						if(!empty($project->reporting_organisation->name)) { 
							echo $project->reporting_organisation->name; 
						} else {
							if(!empty($project->reporting_organisation->code)){ 
								echo $project->reporting_organisation->code; 
							} 
						}
					?>

				</div>

				<div class="projects-project-divider"></div>

				<div class="projects-project-spec-key">Sector code(s):</div>
				<div class="projects-project-spec-value">
				
				<?php 
				if(!empty($project->sectors)) {
					$sep = '';
					foreach($project->sectors AS $sector) {
						echo  $sep . "<a class='projects-description-link' href='?sectors={$sector->code}'>" . $sector->code . "</a>";
						$sep = ', ';
					}			
				} else {
					echo "No information avaiable";
				} ?>			

				</div>

				<div class="projects-project-divider"></div>

				<div class="projects-project-spec-key">Activity status:</div>
				<div class="projects-project-spec-value">

				<?php if(!empty($project->activity_status->name)) { echo $project->activity_status->name; } ?>

				</div>

				<div class="projects-project-divider"></div>

				<div class="projects-project-spec-key">Participating organisations:</div>
				<div class="projects-project-spec-value">

				<?php 
					if(!empty($project->participating_organisations)) {
						$sep = ', ';
						$part_org_text = '';

						foreach($project->participating_organisations AS $participating_organisation) {
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
					} 
				?>

				</div>

				<div class="projects-project-divider"></div>

				<div class="project-share-container projects-share-spec">

					<button class="project-share-export project-share-button hneue-bold" name="<?php if(!empty($project->iati_identifier)) { echo $project->iati_identifier; } ?>">
						<div class="share-icon"></div>
						<div class="share-text">EXPORT</div>
					</button>

					<span ifclass="st_sharethis" st_url="<?php bloginfo('url'); ?>/project/?id=<?php if(!empty($project->iati_identifier)) { echo $project->iati_identifier; } ?>" st_title="<?php if(!empty($project->titles)){echo $project->titles[0]->title;}  else{ echo "Unknown title"; }?>" displayText="SHARE"></span>

					<button class="project-share-bookmark project-share-button hneue-bold">
						<div class="share-icon"></div>
						<div class="share-text">BOOKMARK</div>
					</button>
					<button class="project-share-whistleblower project-share-button hneue-bold" name="<?php if(!empty($project->iati_identifier)) { echo $project->iati_identifier; } ?>">
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

<?php } ?>