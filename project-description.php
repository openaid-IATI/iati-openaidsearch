<div id="project-description">
	<div class="project-title">
		<?php if (!empty($activity->titles)) { 
			echo $activity->titles[0]->title; 
			} else {
				echo "Unknown title";
			}

		?>
	</div>
	<div class="project-description-text">
	<?php 	if (!empty($activity->descriptions)) { 


				foreach($activity->descriptions AS $description) {
					echo  $description->description; #$description->$description;
					echo '<br>&nbsp;<br>';
				}
	 		} else {
	 			echo "No description given.";
	 		}




	 		?>
	</div>
</div>