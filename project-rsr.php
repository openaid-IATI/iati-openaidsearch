<div id="project-rsr">

<?php
	
	$rsrfilename = TEMPLATEPATH . '/rsrdata.php';
	echo $rsrfilename;
	
	
	include( $rsrfilename );
	echo $rsrdata1;


	// echo $_test;
	// echo $_TEST3;
	// echo 'test99999';
	//global $rsrdata;

	//$iati_id = $_REQUEST['id'];

	//$data = $rsrdata;
	// $search_url = "http://www.akvo.org/api/v1/partnership/?format=json&iati_activity_id__contains=1&distinct=True&limit=0";
	// $content = file_get_contents($search_url);
	// echo $content;
	//echo $data;
	//$result = json_decode($rsrdata);
	//$objects = $result->objects;

	// foreach($objects AS $idx=>$rsr_project) {
	// 		echo $rsr_project->title;
	// 		echo '<div class="page-full-width-line"></div>';
		
	// }
	?>


</div>
