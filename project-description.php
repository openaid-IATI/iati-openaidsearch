
<?php
$project_id = $_REQUEST['id'];
$activity = wp_get_activity($project_id);
?>
<div id="project-description">
	<div class="project-title">
		<?php echo $activity->titles[0]->title; ?>
	</div>
	<div class="project-description-text">
		<?php echo $activity->descriptions[0]->description; ?>
	</div>
</div>
