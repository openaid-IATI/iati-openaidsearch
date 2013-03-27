<?php
$objects = wp_generate_results_v2();

foreach($objects AS $idx=>$project) {
?>

<div class="row-fluid projects-description">
	<div class="span7">
		<div class="projects-project-title">
			<?php echo $project->titles[0]->title; ?>
		</div>
		<div class="projects-project-description">
			<?php echo $project->descriptions[0]->description; ?>
		</div>
		
	</div>

	<div class="span5">
		<!-- TO DO: REPLACE PROJECTIDENTIFIER --> 
		<button id="PROJECTIDENTIFIER" class="project-expand-button" title="Show more"></button>

		<div class="projects-project-spec">

			<div class="projects-project-divider"></div>

			<div class="projects-project-spec-key">Countries:</div>
			<div class="projects-project-spec-value">
			
			</div>

			<div class="projects-project-divider"></div>

			<div class="projects-project-spec-key">Principal sector:</div>
			<div class="projects-project-spec-value">
			<?php
			$sep = '';
			foreach($project->activity_sectors AS $sector) {
						echo $sep . $sector->name;
						$sep = ', ';
			}
			?>
			</div>

			<div class="projects-project-divider"></div>

			<div class="projects-project-spec-key">Budget:</div>
			<div class="projects-project-spec-value">
			
			</div>

			<div class="projects-project-divider"></div>

			<div class="projects-project-spec-key">IATI identifier:</div>
			<div class="projects-project-spec-value">
				
			</div>

			<div class="projects-project-divider"></div>

		</div>
	</div>
</div>


<?php } ?>