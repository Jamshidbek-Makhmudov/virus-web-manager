<?php
$page_name = "idc_checkinout_list";
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

$Model_Stat= new Model_Stat();	

$searchopt = $_REQUEST[searchopt];	
$searchkey = $_REQUEST[searchkey];	
$orderby = $_REQUEST[orderby];		
 $page = $_REQUEST[page];			// 페이지
$paging = $_REQUEST[paging];
$start_date = $_REQUEST[start_date];	
$end_date = $_REQUEST[end_date];

$scan_center_code = $_REQUEST[scan_center_code];
$v_user_type =  $_REQUEST[v_user_type];
$idc_center =  $_REQUEST[idc_center];


	//검색항목
		//  $search_sql = "";
		 $search_sql = " and v2.v_type ='VISIT_IDC' ";
		if ($start_date != "" && $end_date != "") {
				$search_sql .= " and v2.visit_date between '" . str_replace('-', '', $start_date) . "000000' AND '" . str_replace('-', '', $end_date) . "235959' ";
			}

				if($scan_center_code !=""){ 

				$search_sql .= " and v2.in_center_code = '{$scan_center_code}'  ";
			}

			if($v_user_type != ""){
				$search_sql .= " and v2.v_user_type like '{$v_user_type}%'  ";
			}
			
			if($idc_center !=""){
				$search_sql .= " and CHARINDEX('{$idc_center}',v3.visit_center_desc) > 0  ";
			}
		  
		if($searchkey != ""){
			 if($searchopt=="v_user_name"){
						
						if($_encryption_kind=="1"){

							$search_sql .= "  AND dbo.fn_DecryptString(v2.v_user_name) like '%{?}%' or  v2.v_user_name_en like '%{$searchkey}%' ";

						}else if($_encryption_kind=="2"){

							$search_sql .= "  AND v2.v_user_name = '".aes_256_enc($searchkey)."' or  v2.v_user_name_en like '%{$searchkey}%' ";
						}
					}else if($searchopt=="v_user_belong"){
				$search_sql .= " AND v2.v_user_belong like '%{$searchkey}%' ";

			}else if($searchopt=="elec_doc_number"){
				$search_sql .= " AND v3.elec_doc_number like '%{$searchkey}%' ";

			}else if($searchopt=="work_number"){
				$search_sql .= " AND v3.work_number like '%{$searchkey}%' ";

			}else if($searchopt=="confirmer"){
				$search_sql .= " AND vi.access_emp_name like '%{$searchkey}%' or vi.access_emp_id like '%{$searchkey}%'  ";

			}

		}
			
//order
		if($orderby != "") {
			$order_sql = " ORDER BY $orderby";
		} else {
			$order_sql = " ORDER BY vi.v_user_list_inout_log_seq DESC ";
		}

				$start = 0;
				$rowcount = $_POST["record_count"];
				$lastPageNo = ceil($rowcount / RECORD_LIMIT_PER_FILE);

$j=1;

for ($i = $start; $i < $lastPageNo; $i ++) {

	$end = RECORD_LIMIT_PER_FILE*($i+1);

	$args = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start,"excel_download_flag"=>"1");			

	
	$Model_Stat->SHOW_DEBUG_SQL = false;

	$result = $Model_Stat->getUserVistInoutList_IDC($args);

	if ($result) {
		$rows = [];
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$rows[] = $row;
		}
	}

	$splitHTML[$i] = '<table id="tblList" class="list" style="	border-collapse: collapse;border:1px solid black;width: 100%;">
     <tr>
                <th  style="width:100px; background-color: #D4D0C8; border:0.5px solid black;">'.$_LANG_TEXT['numtext'][$lang_code].'</th>
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('소속구분','belongdivtext').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('이름','nametext').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('영문 이름','engnameid').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('소속','belongtext').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('검사장','inspection_center').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('센터위치','centerpositiontext').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('작업번호','worknumbertext').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('작업번호','worknumbertext').'('.trsLang('인증','certify_text').')</th>
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('방문일자','visitdatetext').'</th>
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black;">'. trsLang('상태','statustext').'</th>
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black;">'. trsLang('처리 시간','process_time').'</th>
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black;">'. trsLang('확인자 이름','verifier_name').'</th>
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black;">'. trsLang('확인자 아이디','verifier_id').'</th>

            </tr>';

	if (count($rows) > 0) {
		foreach ($rows as $row) {

							$v_user_list_seq = $row['v_user_list_seq'];
				$v_user_name = aes_256_dec($row['v_user_name']);
				$v_user_name_en = $row['v_user_name_en'];
				$in_center_name = $row['in_center_name'];
				$v_user_belong = $row['v_user_belong'];
				  $v_user_type = $row['v_user_type'];
					
					$str_v_user_type = $_CODE_V_USER_TYPE_DETAILS[$v_user_type];
					if($str_v_user_type=="") $str_v_user_type = $v_user_type;
					
					$visit_center_desc = $row['visit_center_desc'];
					$elec_doc_number = $row['elec_doc_number'];
					$work_number = $row['work_number'];
					$visit_date = setDateFormat($row['visit_date']);
					if($visit_date=="") $visit_date = setDateFormat($row['in_time']);
					$visit_status = strVal($row['visit_status']);
					if($visit_status=="") $visit_status = "9";	//입실대기
					
					$str_visit_status = $_CODE_VISIT_STATUS[$visit_status];
					
					
				  $access_date = setDateFormat($row['access_date']);
				  $access_emp_name = $row['access_emp_name'];
				  $access_emp_id = $row['access_emp_id'];


			$splitHTML[$i] .= '<tr>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $j . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $str_v_user_type . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $v_user_name . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $v_user_name_en . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $v_user_belong . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $in_center_name . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $visit_center_desc . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $elec_doc_number . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $work_number . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $visit_date . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $str_visit_status . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $access_date . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $access_emp_name . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $access_emp_id . '</td>
					
																								
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





