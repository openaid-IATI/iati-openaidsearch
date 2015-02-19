<div id="project-description">
	<div class="project-title">
		<?php 

		// set var, so this can be used later as disqus title
		$project_title = "No title";
		if (!empty($activity->title)){ 
			$project_title = $activity->title->narratives[0]->text; 
		}
		echo $project_title; 
		?>
	</div>
	<div class="project-description-text">
	<?php
	if (!empty($activity->descriptions)){ 
		foreach($activity->descriptions as $key => $description){
			echo $description->narratives->text;
		}
	} else {
		echo "No description given.";
	} ?>
	</div>
</div>
