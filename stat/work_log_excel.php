<?php
$page_name = "work_log";
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
$page = $_REQUEST[page];				// 페이지
$start_date = $_REQUEST[start_date];	
$end_date = $_REQUEST[end_date];

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');

//lang codes
$numtext = $_LANG_TEXT["numtext"][$lang_code];
$nametext = $_LANG_TEXT["nametext"][$lang_code];
$idtext = $_LANG_TEXT["idtext"][$lang_code];
$work_detail = $_LANG_TEXT["work_detail"][$lang_code];
$ipaddresstext = $_LANG_TEXT["ipaddresstext"][$lang_code];
$work_date = $_LANG_TEXT["work_date"][$lang_code];
$work_classification  = $_LANG_TEXT["work_classification"][$lang_code];



$Model_Stat= new Model_Stat();	
	// $Model_Stat->SHOW_DEBUG_SQL = true;
		 $search_sql = "";
		if ($start_date != "" && $end_date != "") {
				$search_sql .= " a1.log_date between '" . str_replace('-', '', $start_date) . "000000' AND '" . str_replace('-', '', $end_date) . "235959' ";
			}
		  
		if($searchkey != ""){
			if($searchopt=="id"){
				$search_sql .= " AND a1.emp_no like '%$searchkey%' ";

			}else if($searchopt=="name"){
				$search_sql .= " AND a1.emp_name like '".aes_256_enc($searchkey)."' ";

			}else if($searchopt == "ip"){

				$search_sql .= " and a1.ip_addr like N'%$searchkey%' ";

			}else if($searchopt == "title"){

				$search_sql .= " and a1.log_title like N'%$searchkey%' ";

			}

		}


		if($orderby != "") {
			$order_sql = " ORDER BY $orderby";
		} else {
			$order_sql = " ORDER BY a1.act_log_seq DESC ";
		}

 

				$start = 0;
				$rowcount = $_POST["record_count"];
				$lastPageNo = ceil($rowcount / RECORD_LIMIT_PER_FILE);
		
$j=1;
for ($i = $start; $i < $lastPageNo; $i ++) {

	
	$end = RECORD_LIMIT_PER_FILE*($i+1);

	$data = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start,"excel_download_flag"=>"1");			
	

	$result = $Model_Stat->getAdminActLogList($data);

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
                <th  style="width:100px; background-color: #D4D0C8; border:0.5px solid black; ">'.$numtext.'</th>
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black; ">'.$work_date.'</th>
                <th  style="width:100px; background-color: #D4D0C8; border:0.5px solid black; ">'.$nametext.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black; ">'.$idtext.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black; ">'.$work_classification.'</th>
                <th  style="width:500px; background-color: #D4D0C8; border:0.5px solid black; ">'.$work_detail.'</th>
                <th  style="width:100px; background-color: #D4D0C8; border:0.5px solid black; ">'.$ipaddresstext.'</th>


            </tr>';
	foreach ($rows as $row) {

				$act_log_seq = $row['act_log_seq'];
				// $admin_name = $row['emp_name'];
				$admin_id = $row['emp_no'];
				$log_title = $row['log_title'];
				$ip_addr = $row['ip_addr'];
				$act_type = $row['act_type'];
				$referer = $row['referer'];
				$rnum = $row['rnum'];
				$log_date = $row['log_date'];
				$act_type= $row['act_type'];

				$admin_name = aes_256_dec($row['emp_name']);


						//  $log_dt = date('Y-m-d', strtotime($log_date));
						$log_dt=setDateFormat($row['log_date'],"Y-m-d H:i");





		$splitHTML[$i] .= '<tr>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $j . '</td>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $log_dt . '</td>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $admin_name . '</td>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $admin_id . '</td>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $act_type . '</td>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $log_title . '</td>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $ip_addr . '</td>
 
     																						
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


