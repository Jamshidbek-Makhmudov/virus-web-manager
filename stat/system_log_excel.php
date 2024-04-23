<?php
$page_name = "system_log";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$log_div = $_REQUEST[log_div];	

$orderby = $_REQUEST[orderby];		// 정렬순서
$page = $_REQUEST[page];			// 페이지
$paging = $_REQUEST[paging];
$start_date = $_REQUEST[start_date];	
$end_date = $_REQUEST[end_date];

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');

//lang codes
$numtext = $_LANG_TEXT["numtext"][$lang_code];
$work_classification = $_LANG_TEXT["work_classification"][$lang_code];
$workresulttext = $_LANG_TEXT["workresulttext"][$lang_code];
$worktimetext = $_LANG_TEXT["worktimetext"][$lang_code];
$work_log_text = $_LANG_TEXT["workresultdetails"][$lang_code];



$Model_Stat= new Model_Stat();	
			//검색항목
			$search_sql = "";
			if ($start_date != "" && $end_date != "") {
				$search_sql .= " and s.create_date between '".str_replace('-', '', $start_date)."000000' AND '".str_replace('-', '', $end_date)."235959' ";
			}

			
			 if($log_div != ""){

				$search_sql .= " and s.log_div = '{$log_div}' ";

			}
				if($orderby != "") {
					$order_sql = " ORDER BY $orderby";
				} else {
					$order_sql = " ORDER BY s.system_log_seq DESC ";
		
				}	
				$start = 0;
				$rowcount = $_POST["record_count"];
				$lastPageNo = ceil($rowcount / RECORD_LIMIT_PER_FILE);
		
$j=1;

for ($i = $start; $i < $lastPageNo; $i ++) {

	
	$end = RECORD_LIMIT_PER_FILE*($i+1);

	$data = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start,"excel_download_flag"=>"1");			
	
	$result = $Model_Stat->getSystemLogList($data);

	if ($result) {
		$rows = [];
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$rows[] = $row;
		}
	}

	$splitHTML[$i] = '<table id="tblList" class="list" style="	border-collapse: collapse;
border:1px solid black;
	width: 100%;">
     <tr>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black; ">'.$numtext.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black; ">'.$worktimetext.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black; ">'.$work_classification.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black; ">'.$workresulttext.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black; ">'.$work_log_text.'</th>


            </tr>';
	foreach ($rows as $row) {

						$system_log_seq = $row['system_log_seq'];
						$log_div = $row['log_div'];
						$str_log_div = $_CODE_SYSTEM_LOG_LIST[$log_div];
						if ($str_log_div=="") $str_log_div = $log_div;
						$workresult = $row['result'];

						$workresult = "completed";

						$content = $row['content'];

				    $str_create_date=setDateFormat($row['create_date'],"Y-m-d");


		$splitHTML[$i] .= '<tr>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $j . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $str_create_date . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  " >' . $str_log_div . '</td>	
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $workresult . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $content . '</td>
     																						
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


