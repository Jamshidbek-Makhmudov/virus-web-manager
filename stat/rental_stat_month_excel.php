<?php
$page_name = "visit_stat";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
$Model_Stat= new Model_Stat();

$searchopt = $_REQUEST[searchopt];	// 검색옵션
$searchkey = $_REQUEST[searchkey];	// 검색어

$year = $_REQUEST[year];	
$month = $_REQUEST[month];
$ym = $year.$month;
$scan_center_code = $_REQUEST[scan_center_code];	

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');

//lang codes
$gubuntext = $_LANG_TEXT["gubuntext"][$lang_code];/////
$sum_text = $_LANG_TEXT["sum_text"][$lang_code];//////
$count_enter_exit = $_LANG_TEXT["count_enter_exit"][$lang_code];/////


	$start = 0;
	$j=1;
	$rowcount = $_POST["record_count"];
	$lastPageNo = ceil($rowcount / RECORD_LIMIT_PER_FILE);
	// seachpopt
	$search_sql = "";
	 if($searchkey != "" && $searchopt != "") {

		 if ($searchopt == "v_user_name") {

			$search_sql .= " and (rt.user_name = '".aes_256_enc($searchkey)."' or rt.user_name_en = '$searchkey')  ";
		} else if ($searchopt == "v_user_belong") {

			$search_sql .= " and rt.user_belong = N'$searchkey'  ";
		}
	}

	if($scan_center_code != ""){

		$search_sql .= " and c.scan_center_code = '{$scan_center_code}' ";	
	}

	$search_sql2 = $search_sql;
	$search_sql2 .= " and rt.rent_date like '{$year}%'";

	$Model_Stat->SHOW_DEBUG_SQL=false;
			
for ($i = $start; $i < $lastPageNo; $i ++) {  // record_count

		//물품항목가져오기
	$args=array("search_sql"=>$search_sql2);
	$result = $Model_Stat->getRentalItem($args);
if ($result) {
	$rowsRentalItem = [];
	while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
		$rowsRentalItem[] = $row;
		$item_name = $row['item_name'];


			//항목별 통계 가져오기
			$search_sql3 = $search_sql;
			$search_sql3 .= " and rt.item_name =N'{$item_name}' ";
			$args=array("year"=>$year,"search_sql"=>$search_sql3);
			$result2 = $Model_Stat->getRentalStatMonthly($args); 

			if($result2){
				while($row = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC)){
					$rows[$item_name][] = $row;
		}
	}//항목별 통계 가져오기

	
		}
}//물품항목
	

$columnTotals = array_fill(0, count($rows), 0);

$splitHTML[$i] = '<table id="tblList" class="list" style="border-collapse: collapse;border:1px solid black;width: 100%;">
    <tr>
        <th style="width:80px; background-color: #D4D0C8; border:0.5px solid black;">'.$gubuntext.'</th>';

$row_item_data = $rows[$item_name];
	if (count($row_item_data) > 0) {
		foreach ($row_item_data as $row) {
				$day = $row['label'];
			$splitHTML[$i] .= '<th style="width:80px; background-color: #D4D0C8; border:0.5px solid black;">' . $day . '</th>';
		}
	}

$splitHTML[$i] .= '<th style="width:80px; background-color: #D4D0C8; border:0.5px solid black;">Total</th></tr>';

$over_all = 0;
	if (count($rowsRentalItem) > 0) {
		foreach ($rowsRentalItem as $rowItem) {
			$item_name = $rowItem['item_name'];
			$splitHTML[$i] .= '<tr><td style="text-align: center; vertical-align: middle;min-height:30px; border:0.5px solid black;">' . $item_name . '</td>';

			$subTotal = 0;

			$columnIndex = 0;

			$row_item_data = $rows[$item_name];

			foreach ($row_item_data as $rowData) {
				$cnt = $rowData['cnt'];
				$subTotal += $cnt;
				$columnTotals[$columnIndex] += $cnt;
				$splitHTML[$i] .= '<td style="text-align: center; vertical-align: middle;min-height:30px; border:0.5px solid black;">' . $cnt . '</td>';
				$columnIndex++;
			}

			$over_all += $subTotal;
			$splitHTML[$i] .= '<td style="text-align: center; vertical-align: middle;min-height:30px; border:0.5px solid black;">' . $subTotal . '</td></tr>';
		}
	}

$splitHTML[$i] .= '<tr><td style="text-align: center; vertical-align: middle;min-height:30px; border:0.5px solid black;">'.$sum_text.'</td>';
foreach ($columnTotals as $columnTotal) {
    $splitHTML[$i] .= '<td style="text-align: center; vertical-align: middle;min-height:30px; border:0.5px solid black;">' . $columnTotal . '</td>';
}
$splitHTML[$i] .= '<td style="text-align: center; vertical-align: middle;min-height:30px; border:0.5px solid black;">' . $over_all . '</td></tr></table>';





	$start = $start + RECORD_LIMIT_PER_FILE;

	
} // record_count

print json_encode($splitHTML);

if($result) sqlsrv_free_stmt($result);  
if($wvcs_dbcon) sqlsrv_close($wvcs_dbcon);
exit;
?>


