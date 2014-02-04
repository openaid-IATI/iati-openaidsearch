<?php 
if ($meta->total_count > 0){ 
?>
<div class="page-header">
	<div class="container">
		<div id="projects-search-navbar" class="row-fluid">
			<div class="span7">
				<div class="projects-pagination-info hneue-bold">
					<div id="pagination-totals">
						RESULTS <?php echo $meta->offset + 1; ?> - <?php if(($meta->offset + $meta->limit)>$meta->total_count) { echo $meta->total_count; } else { echo ($meta->offset + $meta->limit); } ?> OF <?php echo $meta->total_count; ?>
					</div>

					<div id="sort-type-amount" class="project-sort-type">
                        <span class="project-sort-text hneue-bold">SHOW <?php echo $meta->limit; ?></span>
                        <span class="project-sort-icon"></span>

                        <div id="dropdown-project-amount" class="dropdown-project">
                            <a href="?per_page=5<?php echo wp_generate_link_parameters("per_page"); ?>" class="dropdown-project-amount-link">5</a>
                            <a href="?per_page=10<?php echo wp_generate_link_parameters("per_page"); ?>" class="dropdown-project-amount-link">10</a>
                            <a href="?per_page=25<?php echo wp_generate_link_parameters("per_page"); ?>" class="dropdown-project-amount-link">25</a>
                            <a href="?per_page=50<?php echo wp_generate_link_parameters("per_page"); ?>" class="dropdown-project-amount-link">50</a>
                        </div>

					</div>
					<a id="save-search-results" href="#save-search-results">EXPORT SEARCH RESULTS</a>
                    <a href="#" id="project-share-embed" class="project-page project-share-button hneue-bold">
                        <div class="share-icon"></div>
                        <div class="share-text">EMBED MAP</div>
                        <div id="dropdown-embed-url" class="dropdown-menu-page-header">
                            Code to embed: <br>
                            <textarea name="embed-url"></textarea>
                            <div id="project-share-embed-close">close</div>
                        </div>
                    </a>
				</div>
			</div>
			<div class="span5">
				<div class="projects-sorting hneue-bold">
                    <div id="sort-type-header">
                        SORT BY:
                    </div>

                    <div id="sort-type-budget" class="project-sort-type">
                        <span class="project-sort-text hneue-bold">BUDGET</span>
                        <span class="project-sort-icon"></span>
                        <div id="dropdown-project-budget" class="dropdown-project">
                            <a href="?order_by=total_budget<?php echo wp_generate_link_parameters("order_by"); ?>" class="project-sort-item">ASCENDING</a>
                            <a href="?order_by=-total_budget<?php echo wp_generate_link_parameters("order_by"); ?>" class="project-sort-item">DESCENDING</a>
                        </div>
                    </div>

                    <div id="sort-type-startdate" class="project-sort-type">
                        <span class="project-sort-text hneue-bold">START DATE</span>
                        <span class="project-sort-icon"></span>
                        <div id="dropdown-project-startdate" class="dropdown-project">
                            <a href="?order_by=start_planned<?php echo wp_generate_link_parameters("order_by"); ?>" class="project-sort-item">ASCENDING</a>
                            <a href="?order_by=-start_planned<?php echo wp_generate_link_parameters("order_by"); ?>" class="project-sort-item">DESCENDING</a>
                        </div>
                    </div>

				</div>
			</div>
		</div>
	</div>
</div>
<?php } // close if projects > 0 ?>


<div class="page-content">

<?php
foreach($objects AS $idx=>$project) {
?>
<div class="projects-project-block">
	<div class="container">
		<div class="row-fluid projects-description <?php echo $project->id; ?>">
			<div class="span7">
				<div class="projects-project-title">
					<a href="<?php echo SITE_URL . '/project/?iati_id=' . $project->id; ?>" alt="See project details">
						<?php if (!empty($project->titles)){ 
							echo $project->titles[0]->title; 
						} else {
							echo "Unknown title";
						}?>
					</a>
				</div>
				<div class="projects-project-description <?php echo $project->id; ?>">

				<?php if (!empty($project->descriptions)){ 
						echo $project->descriptions[0]->description;
					} else {
						echo "No description given.";
					}?>

				</div>
				
			</div>

			<div class="span5">
				<button id="<?php echo $project->id; ?>" class="project-expand-button expand-plus" title="Show more"></button>

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
						echo "No information available";
					} ?>
					</div>

					<div class="projects-project-divider"></div>

					
					<div class="projects-project-spec-key">Budget:</div>
					<div class="projects-project-spec-value">

						<?php if(!empty($project->total_budget)) {
							if(!empty($project->default_currency)) { 
								echo currencyCodeToSign($project->default_currency->code); }
								echo format_custom_number($project->total_budget);
						} else {
							echo "-";
						} ?>

					</div>

					

					<div class="projects-project-divider"></div>

					<div class="projects-project-spec-key">IATI identifier:</div>
					<div class="projects-project-spec-value projects-project-spec-title">
					<a href="<?php echo SITE_URL . '/project/?iati_id=' . $project->id; ?>" alt="See project details">
					<?php if(!empty($project->iati_identifier)) { echo $project->iati_identifier; } ?>
					</a>
					</div>

					<div class="projects-project-divider"></div>

					<div class="projects-project-spec-key">Start date planned:</div>
					<div class="projects-project-spec-value">

					 <?php if(!empty($project->start_planned)) { echo $project->start_planned; } ?>

					</div>

					<div class="projects-project-divider"></div>

					<div class="project-project-spec-hidden <?php echo $project->id; ?>">


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
					<a href="?reporting_organisations=<?php echo $project->reporting_organisation->code; ?>">
						<?php 
							if(!empty($project->reporting_organisation->name)) { 
								echo $project->reporting_organisation->name; 
							} else {
								echo $project->reporting_organisation->code; 
							}
						?>
					</a>
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
						echo "No information available";
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
							echo "No information available";
						} 
					?>

					</div>

					<div class="projects-project-divider"></div>

					<div class="project-share-container projects-share-spec">

						<button class="project-share-export project-share-button hneue-bold" name="<?php if(!empty($project->id)) { echo $project->id; } ?>">
							<div class="share-icon"></div>
							<div class="share-text">EXPORT</div>
						</button>

						<span ifclass="st_sharethis" st_url="<?php echo SITE_URL; ?>/project/?id=<?php if(!empty($project->id)) { echo $project->id; } ?>" st_title="<?php if(!empty($project->titles)){echo $project->titles[0]->title;}  else{ echo "Unknown title"; }?>" displayText="SHARE"></span>

						<button class="project-share-bookmark project-share-button hneue-bold">
							<div class="share-icon"></div>
							<div class="share-text">BOOKMARK</div>
						</button>
						<button class="project-share-whistleblower project-share-button hneue-bold" name="<?php if(!empty($project->id)) { echo $project->id; } ?>">
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


    <?php if ($meta->total_count > 0){ ?>
	<div class="container">
	<div class="row-fluid">
		<div class="span12">
			<div id="pagination">
				<?php wp_generate_paging($meta); ?>
			</div>
		</div>
	</div>
    <?php } else {

        echo '<div id="no-projects-found">Your selection didn\'t find any matches.</div>';

    } ?>


	</div>
</div>