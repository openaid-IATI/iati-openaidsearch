<?php 
oipa_generate_results($activities, $total_count);

foreach($activities AS $idx=>$activity) {
?>
<div class="projects-project-block">
	<div class="container">
		<div class="row projects-description <?php echo $activity->id; ?>">
			<div class="col-md-7">
				<div class="projects-project-title">
					<a href="<?php echo SITE_URL . '/project/' . $activity->id . '/'; ?>" alt="See project details">
						<?php if (!empty($activity->title)){ 
							echo $activity->title->narratives[0]->text; 
						} else {
							echo "No title";
						} ?>
					</a>
				</div>
				<div class="projects-project-description <?php echo $activity->id; ?>">
					<?php
					if (!empty($activity->descriptions)){ 
						echo $activity->descriptions[0]->narratives->text;
					} else {
						echo "No description given.";
					} ?>
				</div>
			</div>
			<div class="col-md-5">
				<button id="<?php echo $activity->id; ?>" class="project-expand-button expand-plus" title="Show more"></button>
				<div class="projects-project-spec">

					<div class="projects-project-divider"></div>
					<div class="projects-project-spec-key">Countries:</div>
					<div class="projects-project-spec-value">
					<?php


					if(!empty($activity->recipient_countries)) {
						$sep = '';
						foreach($activity->recipient_countries AS $recipient_country) {
							echo  $sep . "<a class='projects-description-link' href='?countries={$recipient_country->country->code}'>" . $recipient_country->country->name . "</a>";
							$sep = ', ';
						}
					} else {
						echo "-";
					}
					?>
					</div>

					<div class="projects-project-divider"></div>
					<div class="projects-project-spec-key">Regions:</div>
					<div class="projects-project-spec-value">
					<?php 
					if(!empty($activity->recipient_regions)) {
						$sep = '';
						foreach($activity->recipient_regions AS $recipient_region) {
							echo  $sep . "<a class='projects-description-link' href='?regions={$recipient_region->region->code}'>" . $recipient_region->region->name . "</a>";
							$sep = ', ';
						}
					} else {
						echo "-";
					}
					?>
					</div>

					<div class="projects-project-divider"></div>
					<div class="projects-project-spec-key">Sector(s):</div>
					<div class="projects-project-spec-value">

					<?php 
					if(!empty($activity->sectors)) {
						$sep = '';
						foreach($activity->sectors as $recipient_sector) {

							echo  $sep . "<a class='projects-description-link' href='?sectors={$recipient_sector->sector->code}'>" . $recipient_sector->sector->name . "</a>";
							$sep = ', ';
						}		
					} else {
						echo "No information available";
					} ?>
					</div>

					<div class="projects-project-divider"></div>
					<div class="projects-project-spec-key">Budget:</div>
					<div class="projects-project-spec-value">

						<?php 
						if(!empty($activity->total_budget)) {
							if(!empty($activity->total_budget->value)) { 
								if(!empty($activity->total_budget->currency)){
									echo currencyCodeToSign($activity->total_budget->currency->code); 
								}
							}
							echo number_format($activity->total_budget->value, 0, ".", ".");
						} else {
							echo "-";
						}
						?>
					</div>

					<div class="projects-project-divider"></div>
					<div class="projects-project-spec-key">Reporting organisation:</div>
					<div class="projects-project-spec-value">
						<a href="?reporting_organisations=<?php echo $activity->reporting_organisation->organisation->code; ?>">
							<?php 
							if(!empty($activity->reporting_organisation->organisation->name)) { 
								echo $activity->reporting_organisation->organisation->name->narratives[0]->text; 
							} else {
								echo $activity->reporting_organisation->organisation->code; 
							}
							?>
						</a>
					</div>

					<div class="projects-project-divider"></div>
					<div class="projects-project-spec-key">IATI identifier:</div>
					<div class="projects-project-spec-value projects-project-spec-title">
						<a href="<?php echo SITE_URL . '/project/' . $activity->id . '/'; ?>" alt="See project details">
							<?php echo get_oipa_field($activity->iati_identifier); ?>
						</a>
					</div>

					<div class="projects-project-divider"></div>
					<div class="projects-project-spec-key">Start date planned:</div>
					<div class="projects-project-spec-value">
						<?php echo get_oipa_field($activity->activity_dates->start_planned); ?>
					</div>
					
					<div class="projects-project-divider"></div>
					<div class="project-project-spec-hidden <?php echo $activity->id; ?>">

						<div class="projects-project-spec-key">Last updated:</div>
						<div class="projects-project-spec-value">
							<?php echo get_oipa_field($activity->last_updated_datetime); ?>
						</div>

						<div class="projects-project-divider"></div>
						<div class="projects-project-spec-key">End date planned:</div>
						<div class="projects-project-spec-value">
							<?php echo get_oipa_field($activity->activity_dates->end_planned); ?>
						</div>

						<div class="projects-project-divider"></div>
						<div class="projects-project-spec-key">Activity status:</div>
						<div class="projects-project-spec-value">
						<?php 
						if(!empty($activity->activity_status) && isset($activity_status[$activity->activity_status->code])) {
							echo $activity_status[$activity->activity_status->code];
						}
						?>
						</div>
						<div class="projects-project-divider"></div>
						<div class="projects-project-spec-key">Participating organisations:</div>
						<div class="projects-project-spec-value">
						<?php 
							if(!empty($activity->participating_organisations)) {
								$sep = ', ';
								$part_org_text = '';

								foreach($activity->participating_organisations AS $participating_organisation) {
									if(empty($participating_organisation->organisation->name)) {
										
										$part_org_text .= $participating_organisation->organisation->code;

									} else {
										$part_org_text .= $participating_organisation->organisation->name->narratives[0]->text;
									}
									$part_org_text .= $sep;
								}
								
								$part_org_text = substr($part_org_text, 0, -2);
								echo $part_org_text;
							} else {
								echo "No information available";
							} 
						?>
						</div>

						<div class="projects-project-divider"></div>
						<div class="project-share-container projects-share-spec">
							<button class="project-share-export project-share-button hneue-bold" name="<?php if(!empty($activity->id)) { echo $activity->id; } ?>">
								<div class="share-icon"></div>
								<div class="share-text">EXPORT</div>
							</button>
							<span ifclass="st_sharethis" st_url="<?php echo SITE_URL; ?>/project/?id=<?php if(!empty($activity->id)) { echo $activity->id; } ?>" st_title="<?php if(!empty($activity->titles)){echo $activity->titles[0]->title;}  else{ echo "Unknown title"; }?>" displayText="SHARE"></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php } ?>

<input type="hidden" class="list-amount-input" value="<?php echo $total_count; ?>" />