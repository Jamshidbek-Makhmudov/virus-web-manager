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

if($start_date=="") $start_date = $year.$month."01";
if($end_date=="") $end_date =   $year.$month.DATE('t', strtotime($start_date));

$file_import_visit_checked = $_REQUEST[file_import_visit_checked];	
$pass_visit_checked = $_REQUEST[pass_visit_checked];

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');

//lang codes
$gubuntext = $_LANG_TEXT["gubuntext"][$lang_code];
$sum_text = $_LANG_TEXT["sum_text"][$lang_code];
$count_enter_exit = $_LANG_TEXT["count_enter_exit"][$lang_code];

	// seachpopt
	 if($searchkey != "" && $searchopt != "") {

		if ($searchopt == "mgr_name") {
			$search_sql .= " and (v2.manager_name = '".aes_256_enc($searchkey)."' or v2.manager_name_en = '$searchkey' ) ";
		} else if ($searchopt == "v_user_name") {

			$search_sql .= " and (v2.v_user_name = '".aes_256_enc($searchkey)."' or v2.v_user_name_en = '$searchkey')  ";
		} else if ($searchopt == "v_user_belong") {

			$search_sql .= " and v2.v_user_belong = N'$searchkey'  ";
		}  else if ($searchopt == "mgr_dept") {

			$search_sql .= " and v2.manager_dept = N'$searchkey'  ";
		} 

	}

	if($scan_center_code != ""){

		$search_sql .= " and c.scan_center_code = '{$scan_center_code}' ";	
	}

	//파일반입 출입건
	if($file_import_visit_checked =="Y"){
		$search_sql .= " and v3.elec_doc_number > '' ";	
	}
	
	//임시출입증발급 출입건
	if($pass_visit_checked =="Y"){
		$search_sql .= " and v3.pass_card_no > '' ";	
	}

	$args=array("start_date"=>$start_date,"end_date"=>$end_date,"search_sql"=>$search_sql);

$start = 0;
$rowcount = $_POST["record_count"];
$lastPageNo = ceil($rowcount / RECORD_LIMIT_PER_FILE);
		
$j=1;
for ($i = $start; $i < $lastPageNo; $i ++) {

	
	//$end = RECORD_LIMIT_PER_FILE*($i+1);
	$Model_Stat->SHOW_DEBUG_SQL=false;
	$result = $Model_Stat->getVisitStatDaily($args);

	if ($result) {
		$rows = [];
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$rows[] = $row;
		}
	}
	
	$splitHTML[$i] = '<table id="tblList" class="list" style="	border-collapse: collapse;border:1px solid black;width: 100%;">
     <tr>
			<th  style="width:80px; background-color: #D4D0C8; border:0.5px solid black; ">'.$gubuntext.'</th>';
			foreach ($rows as $row) {
				$day = setDateFormat($row['label'],"d");				
				$splitHTML[$i] .='<th  style="width:80px; background-color: #D4D0C8; border:0.5px solid black; ">' . $day . '</th>';								
			}
			$splitHTML[$i] .='<th  style="width:80px; background-color: #D4D0C8; border:0.5px solid black; ">'.$sum_text.'</th>
	</tr>
	<tr>
		<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $count_enter_exit . '</td>
		';
		foreach ($rows as $row) {
			$cnt = $row['cnt'];
			$over_all += $cnt;
			$splitHTML[$i] .= '<td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black ">' . $cnt . '</td>';
		}
		$splitHTML[$i] .= '<td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black ">' . $over_all . '</td>
	</tr>
</table>
';

	$start = $start + RECORD_LIMIT_PER_FILE;

}

print json_encode($splitHTML);

if($result) sqlsrv_free_stmt($result);  
if($wvcs_dbcon) sqlsrv_close($wvcs_dbcon);
exit;
?>


