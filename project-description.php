
<?php
$back_url = $_REQUEST['back_url'];
if(empty($back_url) && !empty($_SERVER['HTTP_REFERER'])) $back_url = $_SERVER['HTTP_REFERER'];
$project_id = $_REQUEST['id'];
$activity = wp_get_activity($project_id);
?>

<div class="row-fluid project-description">
	<div class="span7">
		<div class="projects-project-title">
			<?php echo $activity->titles[0]->title; ?></div>
		<div class="projects-project-description">
			<?php echo $activity->descriptions[0]->description; ?>
		</div>
	</div>
	<div class="span5">
		<div class="projects-project-spec">

			<div class="projects-project-divider"></div>

			<div class="projects-project-spec-key">Countries:</div>
			<div class="projects-project-spec-value">

				<?php if(!empty($activity->recipient_country)) {
					$sep = '';
					$countries = "";
					$cSep = "";
					foreach($activity->recipient_country AS $country) {
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
				<a href="#">

					<?php if(!empty($activity->activity_sectors)) {
						$sep = '';
						foreach($activity->activity_sectors AS $sector) {
							if($sector->name=='No information available') {
								echo $sector->name;
							} else {
								echo  $sep . "<a href='".get_bloginfo('url')."/?s=&amp;sectors={$sector->code}'>" . $sector->name . "</a>";
							}
								$sep = ', ';
							}
					} ?>
				</a>
			</div>

			<div class="projects-project-divider"></div>

			<div class="projects-project-spec-key">Budget:</div>
			<div class="projects-project-spec-value">

				<?php if(!empty($activity->statistics->total_budget)) {?>
					US$ <?php echo format_custom_number($activity->statistics->total_budget);  ?>
				<?php } ?>

			</div>

			<div class="projects-project-divider"></div>

			<div class="projects-project-spec-key">IATI identifier:</div>
			<div class="projects-project-spec-value">

			<?php if(!empty($activity->iati_identifier)) { echo $activity->iati_identifier; } ?>

			</div>

			<div class="projects-project-divider"></div>

			<div class="projects-project-spec-key">Reporting organisation:</div>
			<div class="projects-project-spec-value">

				<?php if(!empty($activity->reporting_organisation->org_name)) { echo $activity->reporting_organisation->org_name; } ?>

			</div>

			<div class="projects-project-divider"></div>

			<div class="projects-project-spec-key">Sector code:</div>
			<div class="projects-project-spec-value">

			<?php if(!empty($activity->activity_sectors[0]->code)) { echo $activity->activity_sectors[0]->code; } ?>

			</div>

			<div class="projects-project-divider"></div>

			<div class="projects-project-spec-key">Last updated:</div>
			<div class="projects-project-spec-value">

			<?php if(!empty($activity->date_updated)) { echo $activity->date_updated; } ?>

			</div>

			<div class="projects-project-divider"></div>

			<div class="projects-project-spec-key">Start date planned:</div>
			<div class="projects-project-spec-value">

			 <?php if(!empty($activity->start_planned)) { echo $activity->start_planned; } ?>

			</div>

			<div class="projects-project-divider"></div>

			<div class="projects-project-spec-key">End date planned:</div>
			<div class="projects-project-spec-value">

			<?php if(!empty($activity->end_planned)) { echo $activity->end_planned; } ?>

			</div>

			<div class="projects-project-divider"></div>

			<div class="projects-project-spec-key">Activity status:</div>
			<div class="projects-project-spec-value">

			<?php if(!empty($activity->activity_status->name)) { echo $activity->activity_status->name; } ?>

			</div>

			<div class="projects-project-divider"></div>

			<div class="projects-project-spec-key">Name participating organisation:</div>
			<div class="projects-project-spec-value">

			<?php if(!empty($activity->reporting_organisation->org_name)) { echo $activity->reporting_organisation->org_name; } ?>

			</div>

			<div class="projects-project-divider"></div>

			<div class="projects-project-spec-key">Organisation reference code:</div>
			<div class="projects-project-spec-value">
			
			<?php if(!empty($activity->reporting_organisation->ref)) { echo $activity->reporting_organisation->ref; } ?>

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