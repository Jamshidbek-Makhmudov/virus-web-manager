<?php
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

//검색항목
		 $search_sql = "";
		if ($start_date != "" && $end_date != "") {
				$search_sql .= " and v2.visit_date between '" . str_replace('-', '', $start_date) . "000000' AND '" . str_replace('-', '', $end_date) . "235959' ";
			}

				if($scan_center_code !=""){ 

				$search_sql .= " and v2.in_center_code = '{$scan_center_code}'  ";
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

			}

		}
			
//order
		if($orderby != "") {
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

	
	$Model_Stat->SHOW_DEBUG_SQL = false;

	$result = $Model_Stat->getUserAgreeList($args);

	if ($result) {
		$rows = [];
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$rows[] = $row;
		}
	}

	$splitHTML[$i] = '<table id="tblList" class="list" style="	border-collapse: collapse;border:1px solid black;width: 100%;">
     <tr>
                <th  style="width:100px; background-color: #D4D0C8; border:0.5px solid black;">'.$_LANG_TEXT['numtext'][$lang_code].'</th>
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('방문일자','visitdatetext').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('이름','nametext').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('영문 이름','engnameid').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('소속','belongtext').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('검사장','inspection_center').'</th>
								
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('연락처','contactphonetext').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('개인정보 동의서 작성여부','personel_info_consent_text').'</th>
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('개인정보 동의서 작성일자','personel_info_date_text').'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('정보보호서약서 작성여부','info_protec_pledge_text').'</th>
                <th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('정보보호서약서 작성일자','info_protec_pledge_date_text').'</th>

            </tr>';

	if (count($rows) > 0) {
		foreach ($rows as $row) {

				$v_user_list_seq = $row['v_user_list_seq'];
				$v_user_name = aes_256_dec($row['v_user_name']);
				$v_user_name_en = $row['v_user_name_en'];
				$in_center_name = $row['in_center_name'];

				$v_user_belong = $row['v_user_belong'];
				$visit_date = setDateFormat($row['visit_date']);

				
				
				
				$v_agree_date = setDateFormat($row['v_agree_date'],'Y-m-d H:i');
				if( $row['v_agree_yn']=="Y"){
					$v_agree_yn = trsLang('동의','agree_text');
				}else{
					$v_agree_yn = trsLang('미동의','dis_agree_text');
					
				};
				
				$security_agree_date = setDateFormat($row['security_agree_date'],'Y-m-d H:i');
				if( $row['security_agree_yn']=="Y"){
				$security_agree_yn = trsLang('작성완료','writeoktext');
			}else{
				$security_agree_yn = trsLang('미작성','notwritten');

			};

				//phone
					if($_encryption_kind=="1"){
						$phone_no = $row['v_phone'];
						
					}else if($_encryption_kind=="2"){
					
						if($row['v_phone'] != ""){
							$phone_no = aes_256_dec($row['v_phone']);
						}
					}



			$splitHTML[$i] .= '<tr>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $j . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $visit_date . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $v_user_name . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $v_user_name_en . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $v_user_belong . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $in_center_name . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $phone_no . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $v_agree_yn . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $v_agree_date . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $security_agree_yn . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $security_agree_date . '</td>



																								
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





