<?php
$page_name = "parking_ticket_payment";

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

$searchandor1 = $_REQUEST[searchandor1];
$searchandor2 = $_REQUEST[searchandor2];
$searchandor3 = $_REQUEST[searchandor3];
$searchoptm1 = $_REQUEST[searchoptm1];
$searchoptm2 = $_REQUEST[searchoptm2];
$searchoptm3 = $_REQUEST[searchoptm3];
$searchkeym1 = $_REQUEST[searchkeym1];
$searchkeym2 = $_REQUEST[searchkeym2];
$searchkeym3 = $_REQUEST[searchkeym3];

$orderby = $_REQUEST[orderby];		// 정렬순서
$page = $_REQUEST[page];			// 페이지
$paging = $_REQUEST[paging];
$start_date = $_REQUEST[start_date];	
$end_date = $_REQUEST[end_date];

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');

//lang codes
$numtext = $_LANG_TEXT["numtext"][$lang_code];
$nametext = $_LANG_TEXT["nametext"][$lang_code];
$englishname = $_LANG_TEXT["english_name"][$lang_code];
$belongtext = $_LANG_TEXT["belongtext"][$lang_code]; 
$purpose_visit = $_LANG_TEXT["purpose_visit"][$lang_code];
$carnumber = $_LANG_TEXT["carnumber"][$lang_code];
$requesttime = $_LANG_TEXT["requesttime"][$lang_code];
$payment_date = $_LANG_TEXT["payment_date"][$lang_code];
$memotext = $_LANG_TEXT["memotext"][$lang_code];
$belongdivtext = $_LANG_TEXT["belongdivtext"][$lang_code];
$carouttime  = $_LANG_TEXT["carouttime"][$lang_code];


$Model_User= new Model_User();	
// $Model_User->SHOW_DEBUG_SQL = true;	
			// seachpopt
			//검색항목
						 $search_sql = "";
							if ($start_date != "" && $end_date != "") {
				$search_sql .= " and create_date between '".str_replace('-', '', $start_date)."000000' AND '".str_replace('-', '', $end_date)."235959' ";
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

							 $keyword_search_sql .= "  dbo.fn_DecryptString(".$searchopt.") = '$searchkey' ";
							 

						  }else if($_encryption_kind=="2"){

							 $keyword_search_sql .= "  $searchopt = '".aes_256_enc($searchkey)."' ";
						  }
					} else if ($searchopt_i == "user_company") {
						$keyword_search_sql .= " (user_company like '%$searchkey_i%') ";
					}else if ($searchopt_i == "car_number") {
							if($_encryption_kind=="1"){

								 $keyword_search_sql .= " dbo.fn_DecryptString(".$searchopt_i.") like '%$searchkey_i%' ";
							  }else if($_encryption_kind=="2"){

								 $keyword_search_sql .= " $searchopt_i = '".aes_256_enc($searchkey_i)."' ";
							  }
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
					$order_sql = " ORDER BY ticket_list_seq DESC ";
		
				}	



				$start = 0;
				$rowcount = $_POST["record_count"];
				$lastPageNo = ceil($rowcount / RECORD_LIMIT_PER_FILE);
		
$j=1;

for ($i = $start; $i < $lastPageNo; $i ++) {

	
	$end = RECORD_LIMIT_PER_FILE*($i+1);

	$data = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start,"excel_download_flag"=>"1");			
	
	$result = $Model_User->getItemParkingDetailsList($data);

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
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black; ">'.$belongdivtext.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$nametext.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$englishname.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$belongtext.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$purpose_visit.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black; ">'.$carnumber.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$requesttime.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$carouttime.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$payment_date.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$memotext.'</th>

            </tr>';
	foreach ($rows as $row) {

						$ticket_list_seq = $row['ticket_list_seq'];

						$user_name_en = $row['user_name_en'];
						$user_name = aes_256_dec($row['user_name']);
						$serve_time = $row['serve_time'];
						$car_number = $row['car_number'];
						$ticket_desc = $row['ticket_desc'];
						$user_company = $row['user_company'];
						$user_belong = $row['user_belong'];
						
						$memo = $row['memo'];											
					  $user_agree_yn = $row['user_agree_yn'];

					$user_type = $row['user_type'];
					$str_user_type = $_CODE_V_USER_TYPE[$user_type];
						
						$create_date = $row['create_date'];
						 $formatted_create_date = date('Y-m-d H:i', strtotime($create_date));

						if (!empty($memo) && $memo !== 'null') {
							$_memo_text=$memo;
						}else{
							$_memo_text="";
						}
						$out_time = $row['out_time'];

							//car number
						if($_encryption_kind=="1"){
						$car_num = $row['car_number'];

						
					}else if($_encryption_kind=="2"){
					
						if($row['car_number'] != ""){
							$car_num = aes_256_dec($row['car_number']);
						}
					}


		$splitHTML[$i] .= '<tr>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $j . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $str_user_type . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $user_name . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $user_name_en . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  " >' . $user_belong . '</td>							
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $ticket_desc . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $car_num . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $serve_time . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $out_time . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $formatted_create_date . '</td>							
             
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


