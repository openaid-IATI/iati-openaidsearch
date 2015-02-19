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

	$search_url = $activity->transactions;
	$content = file_get_contents($search_url);
	$objects = json_decode($content);
	$transactions = $objects->results;

	foreach($transactions as $at) {
		if(isset($at->transaction_type->code)){
			$type = $transaction_type[$at->transaction_type->code];
		} else {
			$type = "";
		}
		$currency = currencyCodeToSign($at->currency->code);
		$value = number_format($at->value, 0, ".", "");

		$provider_org = '';
		if(!empty($at->provider_organisation->organisation->name)) {
			$provider_org = $at->provider_organisation->organisation->name;
		} elseif (!empty($at->provider_organisation_name)) {
			$provider_org = $at->provider_organisation_name;
		} elseif(!empty($at->provider_organisation->organisation->code)) {
				$provider_org = $at->provider_organisation->code;
		}
		
		$receiver_org = '';
		if(!empty($at->receiver_organisation->organisation->name)) {
			$receiver_org = $at->receiver_organisation->organisation->name;
		} elseif (!empty($at->receiver_organisation_name)) {
			$receiver_org = $at->receiver_organisation_name;
		} elseif(!empty($at->receiver_organisation->organisation->code)) {
				$receiver_org = $at->receiver_organisation->code;
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
	    cssClassNames: {headerRow: 'unh-table-header', tableRow: 'unh-table-cells'},
	    sortColumn: 4,
	    sortAscending: true
	  }
	});

	tableChart.draw();
}
</script>
</div>