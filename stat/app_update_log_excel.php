<?php
$page_name = "app_update_log";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$searchopt = $_REQUEST[searchopt];	// 검색옵션
$searchkey = $_REQUEST[searchkey];	// 검색어

$orderby = $_REQUEST[orderby];		// 정렬순서
$page = $_REQUEST[page];			// 페이지
$paging = $_REQUEST[paging];
$start_date = $_REQUEST[start_date];	
$end_date = $_REQUEST[end_date];

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');

//lang codes
$numtext = $_LANG_TEXT["numtext"][$lang_code];
$update_target = $_LANG_TEXT["update_target"][$lang_code];
$appnametext = $_LANG_TEXT["appnametext"][$lang_code];
$versiontext = $_LANG_TEXT["versiontext"][$lang_code];
$update_date = $_LANG_TEXT["update_date"][$lang_code];



$Model_Stat= new Model_Stat();	
			//검색항목
			$search_sql = "";
			if ($start_date != "" && $end_date != "") {
				$search_sql .= " and a1.update_time between '".str_replace('-', '', $start_date)."000000' AND '".str_replace('-', '', $end_date)."235959' ";
			}
			// 키워드검색
			if($searchkey != ""){

     if($searchopt == "app_name"){

		$search_sql .= " and a1.app_name like '%$searchkey%' ";

				}else if($searchopt=="ver"){
			$search_sql .= " and a1.ver = '$searchkey' ";

		}
			}
				if($orderby != "") {
					$order_sql = " ORDER BY $orderby";
				} else {
					$order_sql = " ORDER BY a1.app_update_log_seq DESC ";
		
				}	
				$start = 0;
				$rowcount = $_POST["record_count"];
				$lastPageNo = ceil($rowcount / RECORD_LIMIT_PER_FILE);
		
$j=1;

for ($i = $start; $i < $lastPageNo; $i ++) {

	
	$end = RECORD_LIMIT_PER_FILE*($i+1);

	$data = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start,"excel_download_flag"=>"1");			
	
	$result = $Model_Stat->getAppUpdateLogList($data);

	if ($result) {
		$rows = [];
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$rows[] = $row;
		}
	}

	$splitHTML[$i] = '<table id="tblList" class="list" style="	border-collapse: collapse;
border:1px solid black;
	width: 100%;">
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black; ">'.$numtext.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black; ">'.trsLang('검사장','scancentertext').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black; ">'.$update_target.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black; ">'.$appnametext.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black; ">'.$versiontext.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black; ">'.trsLang('업데이트일자','update_date').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black; ">'.trsLang('시작시간','starttime').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black; ">'.trsLang('완료시간','end_date_text').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black; ">'.trsLang('결과','result_text').'</th>


            </tr>';
	foreach ($rows as $row) {

						$app_update_log_seq = $row['app_update_log_seq'];
						$kiosk_name = $row['kiosk_name'];
						$scan_center_name = $row['scan_center_name'];
						$result_text = $row['result'];

						$app_name = $row['app_name'];
						$ver = $row['ver'];
				    $str_update_time=setDateFormat($row['update_time'],"Y-m-d");
						$str_start_time=setDateFormat($row['update_time'],"Y-m-d H:i");
						  $end_time=$row['end_time'];
					if (!empty($end_time) && $end_time !== 'null') {
						$str_end_time = date('Y-m-d H:i', strtotime($end_time));
					} else {
						$str_end_time = '';
					}


		$splitHTML[$i] .= '<tr>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $j . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $kiosk_name . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $scan_center_name . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $app_name . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $ver . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  " >' . $str_update_time . '</td>							
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  " >' . $str_start_time . '</td>							
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  " >' . $str_end_time . '</td>							
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  " >' . $result_text . '</td>							
     																						
            </tr>';

						$j++;

	}


  $splitHTML[$i] .= '</table>';
	$start = $start + RECORD_LIMIT_PER_FILE;

}

print json_encode($splitHTML);

if($result) sqlsrv_free_stmt($result);  
if($wvcs_dbcon) sqlsrv_close($wvcs_dbcon);
exit;
?>


