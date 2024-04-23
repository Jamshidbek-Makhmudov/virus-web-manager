<?php
$page_name = "access_control_idc";
$page_tab_name = "access_control_idc";

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";


$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');

		$Model_manage=new Model_manage();	

$searchopt = $_REQUEST[searchopt];	
$searchkey = $_REQUEST[searchkey];	
$orderby = $_REQUEST[orderby];		
 $page = $_REQUEST[page];			// 페이지
$paging = $_REQUEST[paging];



if($searchkey != ""){

		if($searchopt=="user_id"){

		$search_sql .= " and user_id like '%$searchkey%' ";

		}else if($searchopt == "usb_id"){

		$search_sql .= " and usb_id like '%$searchkey%' ";

		}
	}
			//order
			if($orderby != "") {
					$order_sql = " ORDER BY $orderby";
				} else {
					$order_sql = " ORDER BY usb_seq DESC ";
		
				}	

				$start = 0;
				$rowcount = $_POST["record_count"];
				$lastPageNo = ceil($rowcount / RECORD_LIMIT_PER_FILE);

$j=1;

for ($i = $start; $i < $lastPageNo; $i ++) {

	
	$end = RECORD_LIMIT_PER_FILE*($i+1);

	$args = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start,"excel_download_flag"=>"1");			

	
	$Model_manage->SHOW_DEBUG_SQL = false;

	$result = $Model_manage->getUsbList($args);

	if ($result) {
		$rows = [];
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$rows[] = $row;
		}
	}

	$splitHTML[$i] = '<table id="tblList" class="list" style="	border-collapse: collapse;border:1px solid black;width: 100%;">
  <tr>
		 <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('User ID','user_id_key_text').'('.trsLang('KEY','key_text').')</th>
     <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('USB ID','usb_id_text').'</th>
     <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('수정일자','updated_date_text_value').'</th>
     <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('처리자','manager_text').'</th>

  </tr>';

	if (count($rows) > 0) {
		foreach ($rows as $row) {

			$usb_id = $row['usb_id'];
			$user_id = $row['user_id'];
			$create_date = setDateFormat($row['create_date'], "Y-m-d H:i");
      $access_date = setDateFormat($row['access_date'], "Y-m-d H:i");
			$access_emp_seq = $row['access_emp_seq'];
			$emp_name=aes_256_dec($row['emp_name']);



			$splitHTML[$i] .= '<tr>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $user_id . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $usb_id . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $access_date . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $emp_name . '</td>
																													
				</tr>';

			$j++;

		}
	}


	$splitHTML[$i] .= '</table>';
	$start = $start + RECORD_LIMIT_PER_FILE;

}

print json_encode($splitHTML);

if($result) sqlsrv_free_stmt($result);  
if($wvcs_dbcon) sqlsrv_close($wvcs_dbcon);
exit;
?>





