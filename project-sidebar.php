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
	if(!empty($activity->regions)) {
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
		echo "No information available";
	} ?>


	</div>

	<div class="projects-project-divider"></div>

	<div class="projects-project-spec-key">Budget:</div>
	<div class="projects-project-spec-value">

		<?php if(!empty($activity->total_budget)) {
			// if(!empty($activity->default_currency)) { echo currencyCodeToSign($activity->default_currency->code); }
			echo format_custom_number($activity->total_budget);
		} else {
			echo "-";
		} ?>
	</div>

	<div class="projects-project-divider"></div>

	<div class="projects-project-spec-key">IATI identifier:</div>
	<div class="projects-project-spec-value">

		<?php if(!empty($activity->iati_identifier)) { echo $activity->iati_identifier; } ?></div>
		
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
			echo "No information available";
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
					$part_org_text .= $participating_organisation->name;
					if(!empty($participating_organisation->original_ref)){ $part_org_text .= " (" . $participating_organisation->original_ref . ")"; }
				}
				$part_org_text .= $sep;
			}
			
			$part_org_text = substr($part_org_text, 0, -2);
			echo $part_org_text;
		} else {
			echo "No information available";
		} ?>

	</div>

	<div class="projects-project-divider"></div>


	<div class="projects-project-spec-key">Websites:</div>
	<div class="projects-project-spec-value">

		<?php 
		if(!empty($activity->websites)) {
			$sep = ', ';
			$part_website_text = '';

			foreach($activity->websites AS $website) {
				$part_website_text .= '<a href="'. $website->url .'" target="_blank">link</a>';
				$part_website_text .= $sep;
			}
			
			$part_website_text = substr($part_website_text, 0, -2);
			echo $part_website_text;
		} else {
			echo "No information available";
		} ?>

	</div>

	<div class="projects-project-divider"></div>





	<div class="project-share-container projects-share-spec">

		<button id="project-share-export" class="project-share-button hneue-bold" name="<?php if(!empty($activity->id)) { echo $activity->id; } ?>">
			<div class="share-icon"></div>
			<div class="share-text">EXPORT</div>
		</button>

		<span class="st_sharethis" st_url="<?php bloginfo('url'); ?>/project/?id=<?php if(!empty($activity->id)) { echo $activity->id; } ?>" st_title="<?php if (!empty($activity->titles)){ echo $activity->titles[0]->title; } else { echo "Unkown title"; }?>" displayText="SHARE"></span>

		<button class="project-share-button hneue-bold project-share-bookmark">
			<div class="share-icon"></div>
			<div class="share-text">BOOKMARK</div>
		</button>
		<button class="project-share-button hneue-bold project-share-whistleblower" name="<?php if(!empty($activity->id)) { echo $activity->id; } ?>">
			<div class="share-icon"></div>
			<div class="share-text">WHISTLEBLOWER</div>
		</button>

	</div>
</div>