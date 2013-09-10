
<?php
$project_id = $_REQUEST['id'];
$activity = wp_get_activity($project_id);
?>
<div id="project-financials">
	<div id="financials-placeholder"></div>
	<script type='text/javascript' src='https://www.google.com/jsapi'></script>

	<script type='text/javascript'>

	function DotFormattedProjectFinancials(n) {
	  	var sRegExp = new RegExp('(-?[0-9]+)([0-9]{3})'),
		sValue=n+'';
		var sep='.';
		while(sRegExp.test(sValue)) {
			sValue = sValue.replace(sRegExp, '$1'+sep+'$2');
		}
		// unhabitat ticket 276 says use , instead of .
		sValue = sValue.replace(".", ",");
		return sValue;
	}

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
		$value = str_replace(".00", "", $value);
		$provider_org = '';
		if(!empty($activity->reporting_organisation->org_name)) {
			$provider_org = (string)$activity->reporting_organisation->org_name;
		}
		$receiver_org = '';
		if(!empty($activity->participating_organisations)) {
			$receiver_org = (string)$activity->participating_organisations[0]->org_name;
		}

		echo 'var stringvalue = "' . $currency . '" + DotFormattedProjectFinancials(' . $value . ');';
		echo 'data.addRow(["' . $type . '", "' . $provider_org . '", "' . $receiver_org . '", {v: ' . $value . ', f: stringvalue }, "' . $at->transaction_date . '"]);';
     
	}

	
	?>

	var tableChart = new google.visualization.ChartWrapper({
	    chartType: 'Table',
	    containerId: 'financials-placeholder',
	    dataTable: data,
	    options: {
	      showRowNumber: false,
	      cssClassNames: {headerRow: 'unh-table-header', tableRow: 'unh-table-cells'}
	    }
	  });

	  tableChart.draw();

}
</script>



</div>
