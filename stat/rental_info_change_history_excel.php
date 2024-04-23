<?php
$page_name = "rental_info_change_history";

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$searchandor1 = $_REQUEST[searchandor1];
$searchandor2 = $_REQUEST[searchandor2];
$searchandor3 = $_REQUEST[searchandor3];
$searchoptm1 = $_REQUEST[searchoptm1];
$searchoptm2 = $_REQUEST[searchoptm2];
$searchoptm3 = $_REQUEST[searchoptm3];
$searchkeym1 = $_REQUEST[searchkeym1];
$searchkeym2 = $_REQUEST[searchkeym2];
$searchkeym3 = $_REQUEST[searchkeym3];

$uncovered = $_REQUEST[uncovered];	// 검색옵션

$searchopt = $_REQUEST[searchopt];	// 검색옵션
$searchkey = $_REQUEST[searchkey];	// 검색어
$orderby = $_REQUEST[orderby];		// 정렬순서
$page = $_REQUEST[page];			// 페이지
$paging = $_REQUEST[paging];
$start_date = $_REQUEST[start_date];
$end_date = $_REQUEST[end_date];

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');

if ($paging == "") $paging = $_paging;
if ($useyn == "") $useyn = "Y";

if ($start_date == "") $start_date = date("Y-m-d", strtotime(date("Y-m-d") . " -1 month"));
if ($end_date == "") $end_date = date("Y-m-d");

//lang codes
$numtext = $_LANG_TEXT["numtext"][$lang_code];
$nametext = $_LANG_TEXT["nametext"][$lang_code];
$contactphonetext = $_LANG_TEXT["contactphonetext"][$lang_code]; 
$belongtext = $_LANG_TEXT["belongtext"][$lang_code];
$rentalItems = $_LANG_TEXT["rentalItems"][$lang_code];
$itemnumber = $_LANG_TEXT["itemnumber"][$lang_code];
$rentaldate = $_LANG_TEXT["rentaldate"][$lang_code];
$returndate = $_LANG_TEXT["returndate"][$lang_code];
$recoveryprocessing = $_LANG_TEXT["recoveryprocessing"][$lang_code];
$accessdatetext = $_LANG_TEXT["accessdatetext"][$lang_code];
$accessusertext = $_LANG_TEXT["accessusertext"][$lang_code];
$belongdivtext = $_LANG_TEXT["belongdivtext"][$lang_code];
$process_type_text = $_LANG_TEXT["process_type_text"][$lang_code];
$ipaddresstext = $_LANG_TEXT["ipaddresstext"][$lang_code];
$memotext = $_LANG_TEXT["memotext"][$lang_code];
$contactphonetext = $_LANG_TEXT["contactphonetext"][$lang_code];
$rental_location = $_LANG_TEXT["rental_location"][$lang_code];
$rental_purpose = $_LANG_TEXT["rental_purpose"][$lang_code];
$engnameid = $_LANG_TEXT["engnameid"][$lang_code];
$return_schedule_date_text = $_LANG_TEXT["return_schedule_date_text"][$lang_code];


$Model_Stat= new Model_Stat();	
			  // $Model_Stat->SHOW_DEBUG_SQL = true;
			// seachpopt
			//검색항목
			$search_sql .= " and user_agree_yn = 'Y' ";
			if ($start_date != "" && $end_date != "") {
				$search_sql .= " and rent_date between '" . str_replace('-', '', $start_date) . "000000' AND '" . str_replace('-', '', $end_date) . "235959' ";
			}

			// 키워드검색
			$searchandor0 = " and ( ";
			$searchoptm0 = $searchopt;
			$searchkeym0 = $searchkey;
			$keyword_search_sql = "";

			for ($i = 0; $i < 4; $i++) {
				
				$searchopt_i = ${"searchoptm".$i};	
				$searchkey_i = ${"searchkeym".$i};	
				$searchandor_i = ${"searchandor".$i};	

				if (!empty($searchopt_i) && !empty($searchkey_i)) {
					
					$keyword_search_sql .= " $searchandor_i ";

					if ($searchopt_i == "user_name") {
						if($_encryption_kind=="1"){

							 $keyword_search_sql .= " dbo.fn_DecryptString(user_name) like N'%$searchkey_i%' ";

						  }else if($_encryption_kind=="2"){

							 $keyword_search_sql .= " user_name= '".aes_256_enc($searchkey_i)."' ";
						  }
					} else if ($searchopt_i == "user_phone") {
							$searchkey_i = preg_replace("/[^0-9-]*/s", "", $searchkey_i); 
							if($_encryption_kind=="1"){

								 $keyword_search_sql .= " dbo.fn_DecryptString(user_phone) like N'%$searchkey_i%' ";

							  }else if($_encryption_kind=="2"){

								 $keyword_search_sql .= " user_phone= '".aes_256_enc($searchkey_i)."' ";
							  }

						// $keyword_search_sql .= " (user_phone like '%$searchkey_i%') ";
					} else if ($searchopt_i == "user_belong") {
						$keyword_search_sql .= " (user_belong like N'%$searchkey_i%') ";
					}else if ($searchopt_i == "item_name") {
						$keyword_search_sql .= " (item_name like N'%$searchkey_i%') ";
					}  else if ($searchopt_i == "item_mgt_number") {
						$keyword_search_sql .= " (item_mgt_number like '%$searchkey_i%') ";
					}
				}
			}

			if($keyword_search_sql != ""){
				$search_sql .= $keyword_search_sql.")";
			}


			if($uncovered!=""){
				$search_sql .= " AND (return_date IS NULL OR return_date = '') ";
			}	

			//order
			if($orderby != "") {
				$order_sql = " ORDER BY $orderby";
			} else {
				$order_sql = " ORDER BY h_seq DESC ";
	
			}	



				$start = 0;
				$rowcount = $_POST["record_count"];
				$lastPageNo = ceil($rowcount / RECORD_LIMIT_PER_FILE);
		
$j=1;
for ($i = $start; $i < $lastPageNo; $i ++) {

	
	$end = RECORD_LIMIT_PER_FILE*($i+1);

	$data = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start,"excel_download_flag"=>"1");			
	
  //$Model_Stat->SHOW_DEBUG_SQL = true;
	$result = $Model_Stat->getRentalInfoChangeHistoryDetails($data);

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
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black; ">'.$numtext.'</th>
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black; ">'.$belongdivtext.'</th>
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black;">'.$belongtext.'</th>
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black;">'.$nametext.'</th>
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black;">'.$engnameid.'</th>
								
								
								
								
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black; ">'.$rentalItems.'</th>
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black;">'.$itemnumber.'</th>
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black;">'.$rentaldate.'</th>
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black;">'.$return_schedule_date_text.'</th>
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black;">'.$returndate.'</th>
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black;">'.$rental_location.'</th>
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black;">'.$rental_purpose.'</th>
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black;">'.$contactphonetext.'</th>
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black;">'.$accessusertext.'</th>
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black;">'.$accessdatetext.'</th>
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black;">'.$process_type_text.'</th>
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black;">'.$ipaddresstext.'</th>
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black;">'.$memotext.'</th>



            </tr>';
	foreach ($rows as $row) {

					$item_name = $row['item_name'];
					$item_mgt_number = $row['item_mgt_number'];
					$rent_date = $row['rent_date'];
					$return_date = $row['return_date'];
					$action = $row['action'];
					$history_date = $row['history_date'];
					$emp_no = $row['emp_no'];
					$emp_name = aes_256_dec($row['emp_name']);
					$user_name = aes_256_dec($row['user_name']);
					$user_name_en = $row['user_name_en'];
					$user_belong = $row['user_belong'];

					$rent_list_seq = $row['rent_list_seq'];
					$user_type = $row['user_type'];
					$str_user_type = $_CODE_V_USER_TYPE[$user_type];

					//not used
					$user_phone = $row['user_phone'];
					$user_company = $row['user_company'];
					$access_ip_addr = $row['access_ip_addr'];
					$access_date = $row['access_date'];
					$access_emp_seq = $row['access_emp_seq'];				
					$return_schedule_date = $row['return_schedule_date'];
					$user_dept = $row['user_dept'];
					$rent_purpose = $row['rent_purpose'];
					$rent_center_code = $row['rent_center_code'];
					$return_emp_seq = $row['return_emp_seq'];
					$create_date = $row['create_date'];
					$memo = $row['memo'];
					$user_agree_yn = $row['user_agree_yn'];
					$rnum = $row['rnum'];


						 /** 
							* converts the DateTime object 
						  */
						 //$formatted_date = $rent_date->format('Y-m-d H:i');

						 /**
							*Converts the string to a timestamp and format
							 
							*/
						 $formatted_rent_date = date('Y-m-d H:i', strtotime($rent_date));

						if (!empty($return_date) && $return_date !== 'null') {
							$formatted_return_date = date('Y-m-d H:i', strtotime($return_date));
						} else {
								$formatted_return_date = ''; 
						}
						if (!empty($history_date) && $history_date !== 'null') {
							$formatted_history_date = date('Y-m-d H:i', strtotime($history_date));
						} else {
							$formatted_history_date = '';
						}
						if (!empty($return_schedule_date) && $return_schedule_date !== 'null') {
							$formatted_return_schedule_date = date('Y-m-d', strtotime($return_schedule_date));
						} else {
							$formatted_return_schedule_date = '';
						}

						if (!empty($memo) && $memo !== 'null') {
							$_memo_text=$memo;
						}else{
							$_memo_text="";
						}

						//phone
						if($_encryption_kind=="1"){
					
							$phone_no = $row['user_phone'];
							
						}else if($_encryption_kind=="2"){

							if($row['user_phone'] != ""){
								$phone_no = aes_256_dec($row['user_phone']);
							}
						}



		$splitHTML[$i] .= '<tr>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $j . '</td>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $str_user_type . '</td>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  " >' . $user_belong . '</td>							
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $user_name . '</td>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $user_name_en . '</td>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $item_name . '</td>
								
								
								
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $item_mgt_number . '</td>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $formatted_rent_date . '</td>							
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $formatted_return_schedule_date . '</td>							
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $formatted_return_date . '</td>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' .  $rent_center_code . '</td>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' .  $rent_purpose . '</td>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' .  $phone_no . '</td>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $emp_name . '</td>	
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' .  $formatted_history_date . '</td>							
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' .  $action . '</td>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' .  $access_ip_addr . '</td>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' .  $_memo_text . '</td>

     																						
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


