
<?php
$project_id = $_REQUEST['id'];
$activity = wp_get_activity($project_id);
?>
<div id="project-disbursements">
	<div id="disbursements-placeholder"></div>
	 <script type='text/javascript' src='https://www.google.com/jsapi'></script>
	 <?php /*
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

		$value = $at->value;
		$provider_org = EMPTY_LABEL;
		if(!empty($activity->reporting_organisation->org_name)) {
			$provider_org = $activity->reporting_organisation->org_name;
		}
		$receiver_org = EMPTY_LABEL;
		if(!empty($activity->participating_organisations)) {
			$receiver_org = $activity->participating_organisations[0]->org_name;
		}
	}
	?>
	console.log(" {v: 7000,  f: '$7,000'}");

    data.addRow(["<?php echo $provider_org; ?>","<?php echo $receiver_org; ?>", "{v: " + <?php echo $at->value; ?> + ", f: '" + <?php echo $currency; echo "test"; ?> + "'}", "<?php echo $at->transaction_date; ?>" ]);
     


    var table = new google.visualization.Table(document.getElementById('commitments-placeholder'));
    table.draw(data, {showRowNumber: true});
}
</script>


*/ ?>


</div>
