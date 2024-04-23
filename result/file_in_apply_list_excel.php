<?php
$page_name = "file_in_apply_list";

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";


$searchopt = $_REQUEST[searchopt];	
$searchkey = $_REQUEST[searchkey];	
$orderby = $_REQUEST[orderby];		
$page = $_REQUEST[page];			
$paging = $_REQUEST[paging];
$start_date = $_REQUEST[start_date];	
$end_date = $_REQUEST[end_date];

$searchandor0 = $_REQUEST[searchandor0];

$searchandor = $_REQUEST[searchandor];
$scan_center_code = $_REQUEST[scan_center_code];	
$searchopt1 = $_REQUEST[searchopt1];	
$searchandor1 = $_REQUEST[searchandor1];
$searchkey1 = $_REQUEST[searchkey1];	
$searchopt2 = $_REQUEST[searchopt2];	
$searchandor2 = $_REQUEST[searchandor2];
$searchkey2 = $_REQUEST[searchkey2];	
$searchopt3 = $_REQUEST[searchopt3];	
$searchandor3 = $_REQUEST[searchandor3];
$searchkey3 = $_REQUEST[searchkey3];	

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');


$Model_result= new Model_result();	
			//검색항목
			$search_sql = "";

			if ($start_date != "" && $end_date != "") {
				$search_sql .= " and v1.in_time between '".str_replace('-', '', $start_date)."000000' AND '".str_replace('-', '', $end_date)."235959' ";
			}
			//키워드검색
			$searchkey_sql= array(
				"APPLY_SEQ" => " t1.refer_apply_seq = '{?}' "
				,"USER_BELONG" => " v1.v_user_belong like N'%{?}%' "
				,"MANAGER_DEPT" => " v1.manager_dept like '%{?}%' "
				,"DOC_NO" => " v2.elec_doc_number like '%{?}%' "
				,"FILE_NAME" => " t2.file_name like N'%{?}%' "
				,"FILE_HASH" => " t2.file_hash like '%{?}%' "
			);

			$searchandor0 = " and ( ";
			$searchopt0 = $searchopt;
			$searchkey0 = $searchkey;	
			$keyword_search_sql = "";

			for($i = 0 ; $i < 4 ;$i++){

				$_searchopt = ${"searchopt".$i};	
				$_searchkey = ${"searchkey".$i};	
				$_searchandor = ${"searchandor".$i};	

				if($_searchopt != "" && $_searchkey != ""){

					if($_searchopt=="USER_NAME"){
						$keyword_search_sql .= " {$_searchandor} v1.v_user_name  = '".aes_256_enc($_searchkey)."'";
					}else if($_searchopt=="MANAGER"){
						$keyword_search_sql .= " {$_searchandor} ( v1.manager_name  = '".aes_256_enc($_searchkey)."' or  v1.manager_name_en like '%{$_searchkey}%' ) ";
					}else {
						$keyword_search_sql .= " {$_searchandor} ".str_replace('{?}', $_searchkey, $searchkey_sql[$_searchopt]);
					}

				}

			}

			if($keyword_search_sql != ""){
				$search_sql .= $keyword_search_sql.")";
			}

//order
			if($orderby != "") {
				$order_sql = " ORDER BY $orderby";
			} else {
				$order_sql = " ORDER BY t1.file_in_apply_seq DESC "; 
			}	

				$start = 0;
				$rowcount = $_POST["record_count"];
				$lastPageNo = ceil($rowcount / RECORD_LIMIT_PER_FILE);
		
$j=1;
for ($i = $start; $i < $lastPageNo; $i ++) {

	$end = RECORD_LIMIT_PER_FILE*($i+1);

	$args = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start,"excel_download_flag"=>"1");
	
  $Model_result->SHOW_DEBUG_SQL = false;
	$result = $Model_result->getFileInApplyFileList($args);


	if ($result) {
		$rows = [];
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$rows[] = $row;
		}
	}

	$splitHTML[$i] = '<table id="tblList" class="list" style="	border-collapse: collapse;border:1px solid black;width: 100%;">
     <tr>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('번호','numtext').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('신청번호','applynumbertext').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('방문자명','visitor_name').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('소속','belongtext').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('검사일','checkdatetext').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('검사장','scancentertext').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('파일명','filenametext').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('파일해시','filehash').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('임직원','executives').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('임직원 소속','employee_affiliation').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('전자문서번호','electronic_payment_document_number').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('신청사유','applyreason').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('적용기간','application_period').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('승인여부','approvedyesnotext').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('승인일자','approvedate').'</th>


            </tr>';
	foreach ($rows as $row) {

					$v_user_name = aes_256_dec($row['v_user_name']);
					$v_user_belong = $row['v_user_belong'];
					$file_name = $row['file_name'];
					$file_hash = $row['file_hash'];
					$manager_dept = $row['manager_dept'];
					$manager_name = aes_256_dec($row['manager_name']);
					$manager_name_en = $row['manager_name_en'];
					$elec_doc_number = $row['elec_doc_number'];
					$file_comment = $row['file_comment'];
					$apprv_emp_name = aes_256_dec($row['apprv_emp_name']);
					$scan_center_name = $row['scan_center_name'];
					if($scan_center_name=="") $scan_center_name = $row['scan_center_code'];
					$in_time = setDateFormat($row['in_time']);
					$approve_date = setDateFormat($row['approve_date']);
					$str_approve_status = $_CODE_FILE_EXCEPTION_APPRV_STATUS[$row['approve_status']];
					$refer_apply_seq = $row[refer_apply_seq];
					
					if($str_approve_status=="") $str_approve_status = $row['approve_status'];
					
					$start_date = setDateFormat($row['start_date']);
					$end_date = setDateFormat($row['end_date']);
					
					if($manager_name_en==""){
						$str_manager_name = $manager_name;
					}else{
						$str_manager_name = $manager_name." (".$manager_name_en.")";
					}


				

		$splitHTML[$i] .= '<tr>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $j . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $refer_apply_seq . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $v_user_name . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $v_user_belong . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $in_time. '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $scan_center_name . '</td>
								<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  " >' . $file_name . '</td>							
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $file_hash . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $str_manager_name . '</td>		
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $manager_dept . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $elec_doc_number . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $file_comment . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $start_date .' ~ '.$end_date. '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $str_approve_status . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $approve_date . '</td>
     																						
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


