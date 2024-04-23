<?php
$page_name = "access_control";
$page_tab_name = "access_control_pass";

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

$searchopt = $_REQUEST[searchopt];	// 검색옵션
$searchkey = $_REQUEST[searchkey];	// 검색어
$orderby = $_REQUEST[orderby];		// 정렬순서
$page = $_REQUEST[page];			// 페이지
$paging = $_REQUEST[paging];
$start_date = $_REQUEST[start_date];	
$end_date = $_REQUEST[end_date];
$v_user_type = $_REQUEST[v_user_type];
$uncovered = $_REQUEST['uncovered'];

$searchandor = $_REQUEST[searchandor];
$scan_center_code = $_REQUEST[scan_center_code];	
$searchopt1 = $_REQUEST[searchopt1];	// 검색옵션
$searchandor1 = $_REQUEST[searchandor1];
$searchkey1 = $_REQUEST[searchkey1];	// 검색어
$searchopt2 = $_REQUEST[searchopt2];	// 검색옵션
$searchandor2 = $_REQUEST[searchandor2];
$searchkey2 = $_REQUEST[searchkey2];	// 검색어
$searchopt3 = $_REQUEST[searchopt3];	// 검색옵션
$searchandor3 = $_REQUEST[searchandor3];
$searchkey3 = $_REQUEST[searchkey3];	// 검색어

//lang codes
$numtext = $_LANG_TEXT["numtext"][$lang_code];
$visitor_name = $_LANG_TEXT["nametext"][$lang_code];
$visitor_name_en = $_LANG_TEXT["engnameid"][$lang_code];
$contactphonetext = $_LANG_TEXT["contactphonetext"][$lang_code]; 
$belongtext = $_LANG_TEXT["belongtext"][$lang_code];

$purpose_visit = $_LANG_TEXT["issuepurposetext"][$lang_code];
$scancentertext = $_LANG_TEXT["scancentertext"][$lang_code];
$entry_time = $_LANG_TEXT["entry_time"][$lang_code];
$executives = $_LANG_TEXT["executives"][$lang_code];
$manager_en_text = $_LANG_TEXT["manager_en_text"][$lang_code];
$employee_affiliation = $_LANG_TEXT["employee_affiliation"][$lang_code];
$import_information = $_LANG_TEXT["import_information"][$lang_code];
$memotext = $_LANG_TEXT["memotext"][$lang_code];
// seachpopt
			//검색항목
			 $search_sql = "and v3.pass_card_no > '' ";
			 if ($start_date != "" && $end_date != "") {
				$search_sql .= " and v2.in_time between '" . str_replace('-', '', $start_date) . "000000' AND '" . str_replace('-', '', $end_date) . "235959' ";
			}
			if($scan_center_code !=""){ 

				$search_sql .= " and v2.in_center_code = '{$scan_center_code}'  ";
			}

			if($v_user_type != ""){
				$search_sql .= " and v2.v_user_type = '{$v_user_type}'  ";
			}

			if($uncovered != ""){
				$search_sql .= " and isnull(v3.pass_card_return_date,'') = ''  ";
			}
			
			//키워드검색
			$searchkey_sql= array(
				"v_user_belong" => " v2.v_user_belong like '%{?}%' "
				,"mgr_dept" => " v2.manager_dept like '%{?}%' "
				,"pass_number" => " v3.pass_card_no = '{?}' "
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

	$data = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start,"excel_download_flag"=>"1");			
	$VCSScanList= new Model_User();	

	$result = $VCSScanList->getUserVistList_Pass($data);

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
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('소속구분','belongdivtext').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$visitor_name.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$visitor_name_en.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$contactphonetext.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$belongtext.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black; ">'.$purpose_visit.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$scancentertext.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$entry_time.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$executives.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$manager_en_text.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$employee_affiliation.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('임시출입증번호','temppassnumber').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('회수일자','collection_date').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('처리자','accessusertext').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$memotext.'</th>

            </tr>';
	foreach ($rows as $row) {

					$v_user_list_seq = $row['v_user_list_seq'];

					$v_user_name =  aes_256_dec($row['v_user_name']);

					$v_user_name_en = $row['v_user_name_en'];

					$v_phone = $row['v_phone'];
					$v_email = $row['v_email'];

					//not used
					$v_company = $row['v_company'];
					$v_purpose = $row['v_purpose'];
					$manager_name =  aes_256_dec($row['manager_name']);
					$manager_name_en = $row['manager_name_en'];

					$manager_dept = $row['manager_dept'];
					$additional_cnt = $row['additional_cnt'];

					$memo = $row['memo'];

					$in_time = $row['in_time'];

					$in_center_code = $row['in_center_code'];

					$in_center_name = $row['in_center_name'];

					$pass_card_no = $row['pass_card_no'];
					$pass_card_return_date = setDateFormat($row['pass_card_return_date'],'Y-m-d H:i');
					if($pass_card_return_date=="") $emp_name = "";
					else $emp_name =  aes_256_dec($row['emp_name']);

					$in_goods_cnt = $row['in_goods_cnt'];
					$elec_doc_number = $row['elec_doc_number'];
					$label_name = $row['label_name'];
					$label_value = $row['label_value'];		
					

					$v_user_type = $row['v_user_type'];
					$str_v_user_type = $_CODE_V_USER_TYPE[$v_user_type];

					$v_user_belong = $row['v_user_belong'];

					$rnum = $row['rnum'];
						
					if (!empty($in_time) && $in_time !== 'null') {
						$in_time_vl = date('Y-m-d H:i', strtotime($in_time));
					} else {
						$in_time_vl = '';
					}

			if (!empty($memo) && $memo !== 'null') {
			$_memo_text=$memo;
		}else{
			$_memo_text="";
		}

							//phone
						if($_encryption_kind=="1"){

						$email = $row['v_email'];
						$phone_no = $row['v_phone'];
						
					}else if($_encryption_kind=="2"){
						
						if($row['v_email'] != ""){
							$email = aes_256_dec($row['v_email']);
						}

						if($row['v_phone'] != ""){
							$phone_no = aes_256_dec($row['v_phone']);
						}
					}

				if($v_user_type=="EMP"){
					$phone_no = "";
				}





		$splitHTML[$i] .= '<tr>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $j . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $str_v_user_type . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $v_user_name . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $v_user_name_en . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  " >' . $phone_no . '</td>							
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $v_user_belong . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $v_purpose . '</td>

                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $in_center_name . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $in_time_vl . '</td>							
             
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $manager_name . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $manager_name_en . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $manager_dept . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $pass_card_no . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $pass_card_return_date . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $emp_name . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $_memo_text . '</td>
     																						
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


