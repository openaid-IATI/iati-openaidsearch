
<?php
$project_id = $_REQUEST['id'];
$activity = wp_get_activity($project_id);
?>
<div id="project-commitments">
	<div id="commitments-placeholder"></div>
	 <script type='text/javascript' src='https://www.google.com/jsapi'></script>
    <script type='text/javascript'>
      google.load('visualization', '1', {packages:['table']});
      google.setOnLoadCallback(drawTable);
      function drawTable() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Provider org');
        data.addColumn('string', 'Receiver org');
        data.addColumn('number', 'Value');
        data.addColumn('string', 'Transaction date');









        <?php

	foreach($activity->activity_transactions AS $at) {

		$currency = '';
		switch($at->currency) {
			case 'USD':
				$currency = 'US$ ';
				break;
			case 'EUR':
				$currency = '&euro; ';
				break;
			case 'GBP':
				$currency = '£ ';
				break;
		}
		$value = format_custom_number($at->value);
		$provider_org = EMPTY_LABEL;
		if(!empty($activity->reporting_organisation->org_name)) {
			$provider_org = $activity->reporting_organisation->org_name;
		}
		$reciver_org = EMPTY_LABEL;
		if(!empty($activity->participating_organisations)) {
			$receiver_org = $activity->participating_organisations[0]->org_name;
		}
		?>
        
        data.addRow(["<?php echo $provider_org; ?>","<?php echo $receiver_org; ?>", <?php echo $at->value; ?>, "<?php echo $at->transaction_date; ?>" ]);
         

				
				//<td>{$currency}{$value}</td>						
			
			
		<?php } ?>







        var table = new google.visualization.Table(document.getElementById('commitments-placeholder'));
        table.draw(data, {showRowNumber: true});
      }
    </script>





</div>
