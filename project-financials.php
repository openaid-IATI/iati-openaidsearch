
<?php
$project_id = $_REQUEST['id'];
$activity = wp_get_activity($project_id);
?>
<div id="project-financials">
	<div id="financials-placeholder"></div>
	 <script type='text/javascript' src='https://www.google.com/jsapi'></script>

	  <script type='text/javascript'>
      google.load('visualization', '1', {packages:['table']});
      google.setOnLoadCallback(drawTable);
      function drawTable() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Transaction type');
        data.addColumn('string', 'Provider org');
        data.addColumn('string', 'Receiver org');
        data.addColumn('number', 'Value');
        data.addColumn('string', 'Transaction date');


        <?php

	foreach($activity->activity_transactions AS $at) {
		echo $at->transaction_type;

		$type = '';
		switch($at->transaction_type){
			case 'C':
				$type = 'Commitment';
				break;
			case 'D':
				$type = 'Disbursement';
				break;
			case 'E':
				$type = 'Expenditure';
				break;
			case 'IF':
				$type = 'Incoming funds';
				break;
		}

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

		$value = $at->value;
		$provider_org = '';
		if(!empty($activity->reporting_organisation->org_name)) {
			$provider_org = (string)$activity->reporting_organisation->org_name;
		}
		$receiver_org = '';
		if(!empty($activity->participating_organisations)) {
			$receiver_org = (string)$activity->participating_organisations[0]->org_name;
		}


		echo 'data.addRow([' . $type . ',' . $provider_org . ',' . $receiver_org; ?>", "{v: " + <?php echo $at->value; ?> + ", f: '" + <?php echo $currency; echo "test"; ?> + "'}", "<?php echo $at->transaction_date; ?>" ]);
     
	}

	
	?>
	console.log(" {v: 7000,  f: '$7,000'}");

    


    var table = new google.visualization.Table(document.getElementById('commitments-placeholder'));
    table.draw(data, {showRowNumber: true});
}
</script>



</div>
