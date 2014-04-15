<?php
include('constants.php');
ini_set('zend.ze1_compatibility_mode', '0');
include 'inc/PHPExcel.php';
include 'inc/PHPExcel/Writer/Excel2007.php';


$FILTER = getFilter($_GET);

if(!empty($FILTER['id'])) {

	$search_url = SEARCH_URL . "activities/{$FILTER['id']}/?format=json";
	$content = file_get_contents($search_url);
	$result = json_decode($content);
	$activity = objectToArray($result);
	if(empty($activity)) exit;
	generate_activity_export($activity);
	
} else {
	
	
	$FILTER['limit'] = intval($FILTER['limit']);
	if($FILTER['limit']<=0) $FILTER['limit'] = 20;

	$FILTER['offset'] = intval($FILTER['offset']);
	if($FILTER['offset']<0) $FILTER['offset'] = 0;

	$rep_org_str = "";
	if (DEFAULT_ORGANISATION_ID){ $rep_org_str = "&reporting_organisation__in=" . DEFAULT_ORGANISATION_ID; }
	$search_url = SEARCH_URL . "activities/?format=json" . $rep_org_str . "&limit={$FILTER['limit']}&offset={$FILTER['offset']}";
	
	if(!empty($FILTER['query'])) {
		$search_url .= "&query={$FILTER['query']}";
	}

	if(!empty($FILTER['countries'])) {
		$search_url .= "&countries__in={$FILTER['countries']}";
	}

	if(!empty($FILTER['regions'])) {
		$search_url .= "&regions__in={$FILTER['regions']}";
	}

	if(!empty($FILTER['sectors'])) {
		$search_url .= "&sectors__in={$FILTER['sectors']}";
	}

	if(!empty($FILTER['order_by'])) {
		$search_url .= "&order_by={$FILTER['order_by']}";
	}
	
	$content = file_get_contents($search_url);
	$result = json_decode($content);
	$objects = $result->objects;
	$activities = objectToArray($objects);
	if(empty($activities)) exit;
	generate_search_export($activities);
}

function generate_activity_export($activity) {
	
	$objPHPExcel = new PHPExcel();

	$author = $_REQUEST['author'];

	$objPHPExcel->getProperties()->setCreator( $author );
	$objPHPExcel->getProperties()->setLastModifiedBy( $author );
	$objPHPExcel->getProperties()->setTitle($activity['titles'][0]['title']);
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
	$row=1;
	$objPHPExcel->getActiveSheet()->mergeCells("A{$row}:D{$row}");
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", $activity['titles'][0]['title']);
	
	$fill = array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => 'DADADA') );
	$objPHPExcel->getActiveSheet()->getStyle("A{$row}")->getFill()->applyFromArray($fill);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setSize(14);
	$objPHPExcel->getActiveSheet()->getStyle("A{$row}:D{$row}")->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle("A{$row}:D{$row}")->getFont()->setBold(true);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Countries:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	$sep = '';
	$countries = '';
	if(empty($activity['countries'])) {
		$countries = EMPTY_LABEL;
	} else {
		foreach($activity['countries'] AS $country) {
			$countries .= $sep . $country['name'];
			$sep = ', ';
		}
	}
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $countries);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Principal Sector:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	$sep = '';
	$sectors = '';
	if(empty($activity['sectors'])) {
		$sectors = EMPTY_LABEL;
	} else {
		foreach($activity['sectors'] AS $sector) {
			$sectors .= $sep . $sector['name'];
			$sep = ', ';
		}
	}
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $sectors);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'IATI identifier:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['iati_identifier'])) $activity['iati_identifier'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['iati_identifier']);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Reporting organisation:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['reporting_organisation']['name'])) $activity['reporting_organisation']['name'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['reporting_organisation']['name']);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Start-date:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['start_actual'])) $activity['start_actual'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['start_actual']);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Sector code:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['sectors'])) $activity['sectors'][0]['code'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['sectors'][0]['code']);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Last updated:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['last_updated_datetime'])) $activity['last_updated_datetime'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['last_updated_datetime']);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Start date planned:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['start_planned'])) $activity['start_planned'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['start_planned']);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'End date planned:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['end_planned'])) $activity['end_planned'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['end_planned']);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'End date actual:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['end_actual'])) $activity['end_actual'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['end_actual']);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Collaboration type:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['collaboration_type']['name'])) {
		$collaboration_type = EMPTY_LABEL;
	} else {
		$collaboration_type = $activity['collaboration_type']['name'];
		if(!empty($activity['collaboration_type']['code'])) {
			$collaboration_type = $activity['collaboration_type']['code'] . '. ' .$collaboration_type;
		}
	}
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $collaboration_type);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Flow type:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['default_flow_type']['name'])) $activity['default_flow_type']['name'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['default_flow_type']['name']);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Aid type:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	
	if(empty($activity['default_aid_type']['name'])) {
		$default_aid_type = EMPTY_LABEL;
	} else {
		$default_aid_type = $activity['default_aid_type']['name'];
		if(!empty($activity['default_aid_type']['code'])) {
			$default_aid_type = $activity['default_aid_type']['code'] . '. ' .$default_aid_type;
		}
	}
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $default_aid_type);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Finance type:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['default_finance_type']['name'])) $activity['default_finance_type']['name'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['default_finance_type']['name']);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Tying status:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['default_tied_status']['name'])) $activity['default_tied_status']['name'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['default_tied_status']['name']);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Activity status:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['activity_status']['name'])) $activity['activity_status']['name'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['activity_status']['name']);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Name participating organisation:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['reporting_organisation']['org_name'])) $activity['reporting_organisation']['org_name'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['reporting_organisation']['org_name']);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Organisation reference code:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['reporting_organisation']['ref'])) $activity['reporting_organisation']['ref'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['reporting_organisation']['ref']);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Description:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle("B{$row}")->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
	$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(100);
	if(empty($activity['descriptions'][0]['description'])) $activity['descriptions'][0]['description'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['descriptions'][0]['description']);
	$row++;
	// $objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Documents:');
	// $objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	// $objPHPExcel->getActiveSheet()->setCellValue("B{$row}", EMPTY_LABEL);
	// $row++;
	// $objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Commitments:');
	// $objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	// $objPHPExcel->getActiveSheet()->setCellValue("B{$row}", EMPTY_LABEL);
	// $row++;
	
	$objPHPExcel->setActiveSheetIndex(0);
	// header('Content-Type: application/vnd.ms-excel');

	// header('Content-Disposition: attachment;filename="'. $author.'.xls"');

	// header('Cache-Control: max-age=0');
	// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

    header('Content-Type: CSV');
    header('Content-Disposition: attachment;filename="'. $author.'.csv"');
    header('Cache-Control: max-age=0');

	$objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
    $objWriter->setPreCalculateFormulas($precalculate);


	$objWriter->save('php://output');

	exit;
}

function generate_search_export($activities) {

	$objPHPExcel = new PHPExcel();

	$author = $_REQUEST['author'];

	$objPHPExcel->getProperties()->setCreator( $author );
	$objPHPExcel->getProperties()->setLastModifiedBy( $author );
	$objPHPExcel->getProperties()->setTitle("Search results");

	$objPHPExcel->getActiveSheet()->setCellValue("A1", "Iati identifier");
	$objPHPExcel->getActiveSheet()->setCellValue("B1", "Title");
	$objPHPExcel->getActiveSheet()->setCellValue("C1", "Description");
	$objPHPExcel->getActiveSheet()->setCellValue("D1", "Countries");
	$objPHPExcel->getActiveSheet()->setCellValue("E1", "Regions");
	$objPHPExcel->getActiveSheet()->setCellValue("F1", "sectors");
	$objPHPExcel->getActiveSheet()->setCellValue("G1", "Start planned");
	$objPHPExcel->getActiveSheet()->setCellValue("H1", "End planned");
	$objPHPExcel->getActiveSheet()->setCellValue("I1", "Start actual");
	$objPHPExcel->getActiveSheet()->setCellValue("J1", "End actual");
	$objPHPExcel->getActiveSheet()->setCellValue("K1", "Last_updated");
	$objPHPExcel->getActiveSheet()->setCellValue("L1", "Collaboration type");
	$objPHPExcel->getActiveSheet()->setCellValue("M1", "Default flow type");
	$objPHPExcel->getActiveSheet()->setCellValue("N1", "Default aid type");
	$objPHPExcel->getActiveSheet()->setCellValue("O1", "Tied status");
	$objPHPExcel->getActiveSheet()->setCellValue("P1", "Activity status");
	$objPHPExcel->getActiveSheet()->setCellValue("Q1", "Participating organisations");
	$objPHPExcel->getActiveSheet()->setCellValue("R1", "Reporting organisation");

	$row=2;

	foreach($activities AS $a) {
		$sep = '';
		$countries = '';
		if(empty($a['countries'])) {
			$countries = EMPTY_LABEL;
		} else {
			foreach($a['countries'] AS $country) {
				$countries .= $sep . $country['name'];
				$sep = ', ';
			}
		}

		$sep = '';
		$regions = '';
		if(empty($a['regions'])) {
			$regions = EMPTY_LABEL;
		} else {
			foreach($a['regions'] AS $region) {
				$regions .= $sep . $region['name'];
				$sep = ', ';
			}
		}

		$sep = '';
		$sectors = '';
		if(empty($a['sectors'])) {
			$sectors = EMPTY_LABEL;
		} else {
			foreach($a['sectors'] AS $sector) {
				$sectors .= $sep . $sector['name'];
				$sep = ', ';
			}
		}

		$sep = '';
		$participating_organisations = '';
		if(empty($a['participating_organisations'])) {
			$participating_organisations = EMPTY_LABEL;
		} else {
			foreach($a['participating_organisations'] AS $participating_organisation) {
				$participating_organisations .= $sep . $participating_organisation['name'];
				$sep = ', ';
			}
		}	

		if (!empty($a['iati_identifier'])){ $objPHPExcel->getActiveSheet()->setCellValue("A{$row}", $a['iati_identifier']); } 
		if (!empty($a['titles'][0]['title'])){ $objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $a['titles'][0]['title']); }
		if (!empty($a['descriptions'][0]['description'])){ $objPHPExcel->getActiveSheet()->setCellValue("C{$row}", $a['descriptions'][0]['description']); }
		$objPHPExcel->getActiveSheet()->setCellValue("D{$row}", $countries);
		$objPHPExcel->getActiveSheet()->setCellValue("E{$row}", $regions);
		$objPHPExcel->getActiveSheet()->setCellValue("F{$row}", $sectors);
		if (!empty($a['start_planned'])){ $objPHPExcel->getActiveSheet()->setCellValue("G{$row}", $a['start_planned']); }
		if (!empty($a['end_planned'])){ $objPHPExcel->getActiveSheet()->setCellValue("H{$row}", $a['end_planned']); }
		if (!empty($a['start_actual'])){ $objPHPExcel->getActiveSheet()->setCellValue("I{$row}", $a['start_actual']); }
		if (!empty($a['end_actual'])){ $objPHPExcel->getActiveSheet()->setCellValue("J{$row}", $a['end_actual']); }
		if (!empty($a['last_updated_datetime'])){ $objPHPExcel->getActiveSheet()->setCellValue("K{$row}", $a['last_updated_datetime']); }
		if (!empty($a['collaboration_type']['name'])){ $objPHPExcel->getActiveSheet()->setCellValue("L{$row}", $a['collaboration_type']['name']); }
		if (!empty($a['default_flow_type']['name'])){ $objPHPExcel->getActiveSheet()->setCellValue("M{$row}", $a['default_flow_type']['name']); }
		if (!empty($a['default_aid_type']['name'])){ $objPHPExcel->getActiveSheet()->setCellValue("N{$row}", $a['default_aid_type']['name']); }
		if (!empty($a['default_tied_status']['name'])){ $objPHPExcel->getActiveSheet()->setCellValue("O{$row}", $a['default_tied_status']['name']); }
		if (!empty($a['activity_status']['name'])){ $objPHPExcel->getActiveSheet()->setCellValue("P{$row}", $a['activity_status']['name']); }
		$objPHPExcel->getActiveSheet()->setCellValue("Q{$row}", $participating_organisations);
		if (!empty($a['reporting_organisation'][0]['name'])){ $objPHPExcel->getActiveSheet()->setCellValue("R{$row}", $a['reporting_organisation'][0]['name']); }
		
		$row++;
	}


	$objPHPExcel->setActiveSheetIndex(0);
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'. $author.'.xls"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
}

function getFilter(&$DATA, $format=1) {
	if (empty($DATA)) return false;
	if($format>2) return false;
	
	foreach ($DATA AS $key=>$value) {
		if($format==2) {
			$tmp->$key = $value;
		}elseif($format==1){
			$tmp["$key"] = $value;
		}
	}
	
	return $tmp;
}

function objectToArray($d) {
	if (is_object($d)) {
		// Gets the properties of the given object
		// with get_object_vars function
		$d = get_object_vars($d);
	}

	if (is_array($d)) {
		/*
		* Return array converted to object
		* Using __FUNCTION__ (Magic constant)
		* for recursive call
		*/
		return array_map(__FUNCTION__, $d);
	}
	else {
		// Return array
		return $d;
	}
}

function format_custom_number($num) {
	
	$s = explode('.', $num);
	
	$parts = "";
	if(strlen($s[0])>3) {
		$parts = "." . substr($s[0], strlen($s[0])-3, 3);
		$s[0] = substr($s[0], 0, strlen($s[0])-3);
		
		if(strlen($s[0])>3) {
			$parts = "." . substr($s[0], strlen($s[0])-3, 3) . $parts;
			$s[0] = substr($s[0], 0, strlen($s[0])-3);
			if(strlen($s[0])>3) {
				$parts = "." . substr($s[0], strlen($s[0])-3, 3) . $parts;
				$s[0] = substr($s[0], 0, strlen($s[0])-3);
			} else {
				$parts = $s[0] . $parts;
			}
		} else {
			$parts = $s[0] . $parts;
		}
	} else {
		$parts = $s[0] . $parts;
	}
	
	
	$ret = $parts;
	
	if(isset($s[1])) {
		if($s[1]!="00") {
			$ret .= "," + $s[1];
		}
	}
	
	return $ret;
}
?>