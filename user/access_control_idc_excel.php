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

		$Model_User= new Model_User();	

$searchopt = $_REQUEST[searchopt];	
$searchkey = $_REQUEST[searchkey];	
$orderby = $_REQUEST[orderby];		
 $page = $_REQUEST[page];			// 페이지
$paging = $_REQUEST[paging];
$start_date = $_REQUEST[start_date];	
$end_date = $_REQUEST[end_date];
$v_user_type = $_REQUEST[v_user_type];
$v_user_seq = $_REQUEST[v_user_seq];
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


$idc_center = $_REQUEST[idc_center];
$visit_status = $_REQUEST[visit_status];	
		

			//검색항목
			 $search_sql = " and v2.v_type ='VISIT_IDC' ";

			if ($start_date != "" && $end_date != "") {
				$search_sql .= " and v2.visit_date between '" . str_replace('-', '', $start_date) . "' AND '" . str_replace('-', '', $end_date)."'";
			}
			if($scan_center_code !=""){ 

				$search_sql .= " and v2.in_center_code = '{$scan_center_code}'  ";
			}

			if($v_user_seq !=""){
				$search_sql .= " and v1.v_user_seq = '{$v_user_seq}'  ";
			}

			
			if($v_user_type != ""){
				$search_sql .= " and v2.v_user_type like '{$v_user_type}%'  ";
			}

			if($idc_center !=""){
				$search_sql .= " and CHARINDEX('{$idc_center}',v3.visit_center_desc) > 0  ";
			}

			if($visit_status != ""){
				$search_sql .= " and v2.visit_status = '{$visit_status}'  ";
			}
			
			//키워드검색
			$searchkey_sql= array(
				"v_user_belong" => " v2.v_user_belong like '%{?}%' "
				,"mgr_dept" => " v2.manager_dept like '%{?}%' "
				,"pass_number" => " v3.pass_card_no = '{?}' "
				,"elec_doc_number" => " v3.elec_doc_number = '{?}' "
				,"work_number" => " exists (select 1 from tb_v_user_list_work where v_user_list_seq = v2.v_user_list_seq and work_number = '{?}' ) "
				,"confirmer" => " ( vi.access_emp_name ='{?}' or vo.access_emp_name ='{?}' ) "
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
			
					if($_searchopt=="v_phone"){
						
						if($_encryption_kind=="1"){

							$keyword_search_sql .= $_searchandor." dbo.fn_DecryptString(v1.v_phone) like '%{?}%' ";

						}else if($_encryption_kind=="2"){

							$keyword_search_sql .= $_searchandor." v1.v_phone = '".aes_256_enc($_searchkey)."' ";
						}
					}else if($_searchopt=="v_user_name"){
						
						if($_encryption_kind=="1"){

							$keyword_search_sql .= $_searchandor." (dbo.fn_DecryptString(v2.v_user_name) like '%{?}%' or  v2.v_user_name_en like '%{$_searchkey}%') ";

						}else if($_encryption_kind=="2"){

							$keyword_search_sql .= $_searchandor." (v2.v_user_name = '".aes_256_enc($_searchkey)."' or  v2.v_user_name_en like '%{$_searchkey}%') ";
						}
					}else if($_searchopt=="mgr_name"){
						
						if($_encryption_kind=="1"){

							$keyword_search_sql .= $_searchandor." (dbo.fn_DecryptString(v2.manager_name) like '%{?}%' or  v2.manager_name_en like '%{$_searchkey}%') ";

						}else if($_encryption_kind=="2"){

							$keyword_search_sql .= $_searchandor." (v2.manager_name = '".aes_256_enc($_searchkey)."' or  v2.manager_name_en like '%{$_searchkey}%') ";
						}
					}else{

						$keyword_search_sql .= " {$_searchandor} ".str_replace('{?}', $_searchkey, $searchkey_sql[$_searchopt]);
					}
				}

			}

			//echo $search_sql ;

			if($keyword_search_sql != ""){
				$search_sql .= $keyword_search_sql.")";
			}

			//order
			if ($orderby != "") {
				$order_sql = " ORDER BY $orderby";
			} else {
				$order_sql = " ORDER BY v2.v_user_list_seq DESC ";
			}

				$start = 0;
				$rowcount = $_POST["record_count"];
				$lastPageNo = ceil($rowcount / RECORD_LIMIT_PER_FILE);

$j=1;

for ($i = $start; $i < $lastPageNo; $i ++) {

	
	$end = RECORD_LIMIT_PER_FILE*($i+1);

	$args = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start,"excel_download_flag"=>"1");			

	
	$Model_User->SHOW_DEBUG_SQL = false;

	$result = $Model_User->getUserVistList_IDC($args);

	if ($result) {
		$rows = [];
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$rows[] = $row;
		}
	}

	$splitHTML[$i] = '<table id="tblList" class="list" style="	border-collapse: collapse;border:1px solid black;width: 100%;">
     <tr>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('방문일자','visitdatetext').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('방문자','visitortext').'('.trsLang('소속구분','belongdivtext').')</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('방문자','visitortext').'('.trsLang('소속','belongtext').')</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('방문자','visitortext').'('.trsLang('이름','nametext').')</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('방문자','visitortext').'('.trsLang('연락처','contactphonetext').')</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('방문목적','purpose_visit').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('검사장','inspection_center').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('센터위치','center_location').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('설명','descriptiontext').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('작업번호','worknumbertext').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('작업번호','worknumbertext').'('.trsLang('인증','certify_text').')</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('입실시간','inofficetimetext').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('입실확인자','inofficeconfirmertext').'('.trsLang('아이디','empnotext').')</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('입실확인자','inofficeconfirmertext').'('.trsLang('이름','nametext').')</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('퇴실시간','outofficetimetext').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('퇴실확인자','outofficeconfirmertext').'('.trsLang('아이디','empnotext').')</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('퇴실확인자','outofficeconfirmertext').'('.trsLang('이름','nametext').')</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('상태','statustext').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('서약서 작성여부','write_pledge_text').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('서약서 계약명','pledge_contract_name_text').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('서약서 회사명','pledge_contract_company_text').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('서약서 이름','pledge_author_text').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('서약서 작성일','pledge_date_text').'</th>
								
             
								

            </tr>';

	if (count($rows) > 0) {
		foreach ($rows as $row) {

			$v_user_name = aes_256_dec($row['v_user_name']);
			$v_user_name_en = $row['v_user_name_en'];
			$v_phone = $row['v_phone'];
			$v_company = $row['v_company'];
			$v_purpose = $row['v_purpose'];
			$memo = $row['memo'];
			$in_time = $row['in_time'];
			$elec_doc_number = $row['elec_doc_number'];
			$in_center_name = $row['in_center_name'];

			$work_number = $row['work_number'];
			$in_access_time = setDateFormat($row['in_access_time'],'Y-m-d H:i');
			$out_time_confirmer_id = $row['out_access_emp_id'];
			$out_time_confirmer_name = $row['out_access_emp_name'];
			$out_access_time = setDateFormat($row['out_access_time'],'Y-m-d H:i');
			$visit_date = setDateFormat($row['visit_date']);
			if($visit_date=="") $visit_date = setDateFormat($row['in_time']);
				$visit_status = strVal($row['visit_status']);
			if($visit_status=="") $visit_status = "9";	//입실대기

			$str_visit_status = $_CODE_VISIT_STATUS[$visit_status];


			if( $row['security_agree_yn']=="Y"){
				$security_agree_yn = trsLang('제출','submit_text');
			}else{
				$security_agree_yn = trsLang('제출안함','unsubmit_text');

			};


			$visit_center_desc = $row['visit_center_desc'];
			$in_time_confirmer_id = $row['in_access_emp_id'];
			$in_time_confirmer_name = $row['in_access_emp_name'];

			$v_user_type = $row['v_user_type'];
			$str_v_user_type = $_CODE_V_USER_TYPE_DETAILS[$v_user_type];
			if($str_v_user_type=="") $str_v_user_type = $v_user_type;

			$v_user_belong = $row['v_user_belong'];

			if (!empty($memo) && $memo !== 'null') {
				$_memo_text = $memo;
			} else {
				$_memo_text = "";
			}

			//phone
			if ($_encryption_kind == "1") {

				$email = $row['v_email'];
				$phone_no = $row['v_phone'];

			} else if ($_encryption_kind == "2") {

				if ($row['v_email'] != "") {
					$email = aes_256_dec($row['v_email']);
				}

				if ($row['v_phone'] != "") {
					$phone_no = aes_256_dec($row['v_phone']);
				}
			}

			if($v_user_type=="EMP"){
				$phone_no = "";
			}



			$splitHTML[$i] .= '<tr>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $visit_date . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $str_v_user_type . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $v_user_belong . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $v_user_name . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  " >' . $phone_no . '</td>							
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $v_purpose . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $in_center_name . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $visit_center_desc . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $_memo_text . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $elec_doc_number . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $work_number . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $in_access_time . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $in_time_confirmer_id . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $in_time_confirmer_name . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $out_access_time . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $out_time_confirmer_id . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $out_time_confirmer_name . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $str_visit_status . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $security_agree_yn . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $v_purpose . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $v_user_belong . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $v_user_name . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $visit_date . '</td>



																								
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





