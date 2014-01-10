<?php

if ($rsr_loaded == false){
	?> 
	<div class="container rsr-project-block">
		<div class="row-fluid">
			<div class="span12">
				Loading RSR projects...
			</div>
		</div>
	</div>
	<?php
} else {

	$rsr_project_count = 0;

	foreach($objects AS $idx=>$rsr_project) {

		$partnerships = $rsr_project->partnerships;

		foreach($partnerships AS $idx=>$partnership){
			if ($iati_id == $partnership->iati_activity_id){ 
				$rsr_project_count++;

				if($rsr_project_count == 1){
					?>
					<div class="container rsr-project-block-header hneue-bold">
						<div class="row-fluid">
							<div class="span1">
								
							</div>
							<div class="span3">
								Title
							</div>
							<div class="span2">
								Location
							</div>
							<div class="span2">
								Status
							</div>
							<div class="span2">
								Budget
							</div>
							<div class="span2">
								Funded
							</div>
						</div>
					</div>
					<div class="page-full-width-line"></div>

					<?php
				}


				?>

				<div class="container rsr-project-block">
						<div class="row-fluid">
							<div class="span1">
								<img src="http://rsr.akvo.org<?php echo $rsr_project->current_image->thumbnails->map_thumb; ?>" width="120" height="90" />
							</div>
							<div class="span3">
								<a target="_blank" href="http://rsr.akvo.org<?php echo $rsr_project->absolute_url; ?>"><div class="rsr-title"><?php echo $rsr_project->title; ?></div></a>
								<div class="rsr-description"><?php echo $rsr_project->project_plan_summary; ?></div>
							</div>

							<div class="span2">
								<?php echo $rsr_project->primary_location->city; ?>
							</div>
							<div class="span2">
								<?php $status = $rsr_project->status; 

								switch ($status)
								{
								case "A":
								  echo "Active";
								  break;
								case "C":
								  echo "Completed";
								  break;
								case "H":
								  echo "Needs funding";
								  break;
								default:
								  echo $status;
								}



								?>
							</div>
							<div class="span2">

								<?php 
								$funds = (int)$rsr_project->funds;
								$funds_needed = (int)$rsr_project->funds_needed;
								$budget = (int)$rsr_project->budget;
								$currency = $rsr_project->currency;

								if($currency == "EUR"){
									echo '€ ';
								};

								if($budget < $funds){
									$budget = $funds;
								}

								echo round($budget, 0); ?>

							</div>
							<div class="span2">
								<?php 
									  if($currency == "EUR"){
									  	echo '€ ';
									  };

									  if ($funds_needed == "0"){
									  	echo $funds . ' (100%)';
									  } else {
									  	echo $funds . ' (' . round( ($funds/$budget) * 100, 2) . '%)';
									  }

								 ?>
							</div>
						

						</div>
				</div>
				<div class="page-full-width-line"></div>





				<?php

				break;
			}		
		}
	}



	if($rsr_project_count == 0){
	?>
		<div class="container rsr-project-block">
			<div class="row-fluid">
				<div class="span12">
					No RSR project data found.
				</div>
			</div>
		</div>
	<?php
	}
}
?>
