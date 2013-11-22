
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

	foreach($activity->transactions as $at) {
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

		$currency = currencyCodeToSign($at->currency);

		$value = $at->value;
		$value = str_replace(".00", "", $value);
		$provider_org = '';
		if(!empty($at->provider_organisation->name)) {
			$provider_org = $at->provider_organisation->name;
		} else {
			if(!empty($at->provider_organisation->code)) {
				$provider_org = $at->provider_organisation->code;
			}
		}
		
		$receiver_org = '';
		if(!empty($at->receiver_organisation->name)) {
			$receiver_org = $at->receiver_organisation->name;
		} else {
			if(!empty($at->receiver_organisation->code)) {
				$receiver_org = $at->receiver_organisation->code;
			}
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
