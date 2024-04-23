<?php
$page_name = "access_control_idc";
$page_tab_name = "access_control_goods_idc";

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
//$Model_User->SHOW_DEBUG_SQL = true;

$searchopt = $_REQUEST[searchopt];	// 검색옵션
$searchkey = $_REQUEST[searchkey];	// 검색어
$orderby = $_REQUEST[orderby];		// 정렬순서
$page = $_REQUEST[page];			// 페이지
$paging = $_REQUEST[paging];
$start_date = $_REQUEST[start_date];	
$end_date = $_REQUEST[end_date];
$v_user_type = $_REQUEST[v_user_type];//////
$uncovered = $_REQUEST['uncovered'];

$v_user_seq = $_REQUEST[v_user_seq]; //////
$searchandor0 = $_REQUEST[searchandor0];

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
$belongtext = $_LANG_TEXT["belongtext"][$lang_code];

$contactphonetext = $_LANG_TEXT["contactphonetext"][$lang_code]; 

$purpose_visit = $_LANG_TEXT["purpose_visit"][$lang_code];
$scancentertext = $_LANG_TEXT["scancentertext"][$lang_code];
$importdatetimetext = $_LANG_TEXT["importdate"][$lang_code];
$executives = $_LANG_TEXT["executives"][$lang_code];
$executives_en = $_LANG_TEXT["executives_en"][$lang_code];


$product_name = $_LANG_TEXT["product_name"][$lang_code];
$model_name = $_LANG_TEXT["model_name"][$lang_code];
$serialnumber = $_LANG_TEXT["serialnumber"][$lang_code];
$electronic_payment_document_number = $_LANG_TEXT["electronic_payment_document_number"][$lang_code];
$item_management_number = $_LANG_TEXT["item_management_number"][$lang_code];
$expected_takeout_date = $_LANG_TEXT["expected_takeout_date"][$lang_code];
$date_out = $_LANG_TEXT["date_out"][$lang_code];
$outer_name = $_LANG_TEXT["outer_name"][$lang_code];

$employee_affiliation = $_LANG_TEXT["employee_affiliation"][$lang_code];
$import_information = $_LANG_TEXT["import_information"][$lang_code];
$memotext = $_LANG_TEXT["memotext"][$lang_code];
// seachpopt
			//검색항목
			$search_sql = " and v2.v_type in ('VISIT_IDC','VCS_IDC')  ";	
			 if ($start_date != "" && $end_date != "") {
				$search_sql .= " and v2.visit_date between '" . str_replace('-', '', $start_date) . "' AND '" . str_replace('-', '', $end_date) . "' ";
			}

			if($scan_center_code !=""){ 

				$search_sql .= " and v2.in_center_code = '{$scan_center_code}'  ";
			}

			if($v_user_type != ""){
				$search_sql .= " and v2.v_user_type like '{$v_user_type}%'  ";
			}

			if($uncovered != ""){
				$search_sql .= " and isnull(g.out_date,'') = ''  ";
			}

			//키워드검색
			$searchkey_sql= array(
				"v_user_belong" => " v2.v_user_belong like '%{?}%' "
				,"mgr_dept" => " v2.manager_dept like '%{?}%' "
				,"elec_doc_number" => " g.elec_doc_number = '{?}' "
				,"goods_name" => " g.goods_name like '%{?}%' "
				,"item_mgt_number" => " g.item_mgt_number = '{?}' "
			);
			 
			$searchandor0 = " and (";
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

	$args = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start,"excel_download_flag"=>"1");
	

	

	$result = $Model_User->getUserImportGoodsList($args);


	if ($result) {
		$rows = [];
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$rows[] = $row;
		}
	}

	$splitHTML[$i] = '<table id="tblList" class="list" style="	border-collapse: collapse;border:1px solid black;width: 100%;">
     <tr>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black; ">'.$numtext.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('소속구분','belongdivtext').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$visitor_name.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$visitor_name_en.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$belongtext.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$contactphonetext.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$scancentertext.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$importdatetimetext.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$executives.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$executives_en.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$employee_affiliation.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$product_name.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$model_name.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$serialnumber.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$electronic_payment_document_number.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$item_management_number.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$expected_takeout_date.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$date_out.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$outer_name.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$memotext.'</th>

            </tr>';
	foreach ($rows as $row) {

					$v_user_type = $row['v_user_type'];
					$str_v_user_type = $_CODE_V_USER_TYPE_DETAILS[$v_user_type];

					$v_user_list_seq = $row['v_user_list_seq'];

					$v_user_name = aes_256_dec($row['v_user_name']);

					$v_user_name_en = $row['v_user_name_en'];

					$v_user_belong = $row['v_user_belong'];

					$elec_doc_number = $row['elec_doc_number'];
					$memo = $row['memo'];
					$rnum = $row['rnum'];

					$v_phone = $row['v_phone'];


					//new added
					$goods_name = $row['goods_name'];
					$model_name = $row['model_name'];
					$serial_number = $row['serial_number'];
					$item_mgt_number = $row['item_mgt_number'];
					$out_schedule_date = $row['out_schedule_date'];
					$inout_status = $row['inout_status'];
					$out_date = $row['out_date'];
					$out_emp_name = aes_256_dec($row['out_emp_name']);

					$in_time = $row['in_time'];
					$in_center_name = $row['in_center_name'];
					$manager_name = aes_256_dec($row['manager_name']);
					$manager_name_en = $row['manager_name_en'];
					$manager_dept = $row['manager_dept'];
				

			if (!empty($memo) && $memo !== 'null') {
			$_memo_text=$memo;
		}else{
			$_memo_text="";
		}

						//phone
						if($_encryption_kind=="1"){
						$phone_no = $row['v_phone'];
						
					}else if($_encryption_kind=="2"){
					
						if($row['v_phone'] != ""){
							$phone_no = aes_256_dec($row['v_phone']);
						}
					}

					if($v_user_type=="EMP"){
						$phone_no = "";
					}			

					$str_in_time = setDateFormat($row[visit_date]);
					if (!empty($out_schedule_date) && $out_schedule_date !== 'null') {
						$str_out_schedule_date = date('Y-m-d', strtotime($out_schedule_date));
					} else {
						$str_out_schedule_date = '';
					}

					if (!empty($out_date) && $out_date !== 'null') {
						$str_out_date = date('Y-m-d H:i', strtotime($out_date));
					} else {
						$str_out_date = '';
						$out_emp_name = '';
					}



				

		$splitHTML[$i] .= '<tr>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $j . '</td>

                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $str_v_user_type . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $v_user_name . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $v_user_name_en . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $v_user_belong . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  " >' . $phone_no . '</td>							
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $in_center_name . '</td>

                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $str_in_time . '</td>		
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $manager_name . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $manager_name_en . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $manager_dept . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $goods_name . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $model_name . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $serial_number . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $elec_doc_number . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $item_mgt_number . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $str_out_schedule_date . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $str_out_date . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $out_emp_name . '</td>
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


