<?php
$page_name = "result_list";

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$src = $_REQUEST[src];
$v_user_seq = $_REQUEST[v_user_seq];
$storage_device_type = $_REQUEST[storage_device_type];
$scan_center_code = $_REQUEST[scan_center_code];
$check_result2 = $_REQUEST[check_result2];
$checkdate1 = $_REQUEST[checkdate1];
$checkdate2 = $_REQUEST[checkdate2];
$status = $_REQUEST[status];
$searchopt = $_REQUEST[searchopt];	// 검색옵션
$searchkey = $_REQUEST[searchkey];	// 검색어
$orderby = $_REQUEST[orderby];		// 정렬순서

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');

//상세검색
for($i = 1 ; $i < 4 ; $i++){

	${"searchopt".$i} = $_REQUEST['searchopt'.$i];			// 검색옵션
	${"searchandor".$i} = $_REQUEST['searchandor'.$i];	// 검색연결자
	${"searchkey".$i} = $_REQUEST['searchkey'.$i];		// 검색어
}

if($checkdate1=="") $checkdate1 = date("Y-m-d",strtotime("-1 months"));
if($checkdate2=="") $checkdate2 = date("Y-m-d");

$Model_result = new Model_result();


$search_sql = "";

if($v_user_seq != ""){
	$search_sql .=  " AND us.v_user_seq = '$v_user_seq' ";
}

if($storage_device_type != ""){

	$search_sql .=  " AND vcs.v_asset_type = 'RemovableDevice' ";

	if($storage_device_type=='DEVICE_ETC'){

	  $search_sql .= " AND exists (select value from dbo.fn_split(vcd.os_ver_name,',') WHERE value not in ('Removable','HDD','CD/DVD') and value > '' ) ";

	}else{

		$search_sql .=  " AND CHARINDEX('".$storage_device_type."',vcd.os_ver_name) > 0 ";

	}//if($storage_device_type=='DEVICE_ETC'){
}

if($scan_center_code != ""){

   $search_sql .=  " AND vcs.scan_center_code = '".$scan_center_code."' ";
}


if($checkdate1 != "" && $checkdate2 !=""){

	$search_sql .= " AND vcs.wvcs_dt between '$checkdate1 00:00:00.000' and '$checkdate2 23:59:59.999' ";
}

if($status !=""){

	$search_sql .= " AND vcs.vcs_status = '$status' ";
	
}


//키워드검색
$searchkey_sql= array(
	"USER_BELONG" => " vl.v_user_belong like N'%{?}%' "
	,"MANAGER_DEPT" => " vl.manager_dept like '%{?}%' "
	,"SN" => " vcd.v_sys_sn like '%{?}%' "
	,"DOC_NO" => " vi.elec_doc_number like '%{?}%' "
	,"USB_MGT_NO" => " (vi.label_name='ITEM_MGT_NO' and vi.label_value like N'%{?}%' ) "
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
			$keyword_search_sql .= " {$_searchandor} vl.v_user_name  = '".aes_256_enc($_searchkey)."'";
		}else if($_searchopt=="MANAGER"){
			$keyword_search_sql .= " {$_searchandor} ( vl.manager_name  = '".aes_256_enc($_searchkey)."' or  vl.manager_name_en like '%{$_searchkey}%' ) ";
		}else {
			$keyword_search_sql .= " {$_searchandor} ".str_replace('{?}', $_searchkey, $searchkey_sql[$_searchopt]);
		}

	}

}

if($keyword_search_sql != ""){
	$search_sql .= $keyword_search_sql.")";
}

if($check_result2=="weak"){

	$search_sql .= " and exists (SELECT TOP 1 weakness_seq FROM tb_v_wvcs_weakness WHERE vcs.v_wvcs_seq = v_wvcs_seq ) ";

}else if($check_result2=="virus"){

	$search_sql .= " and exists (
							SELECT TOP 1 vcc.vaccine_seq 
							FROM tb_v_wvcs_vaccine vcc
								INNER JOIN tb_v_wvcs_vaccine_detail vccd
									ON vcc.vaccine_seq = vccd.vaccine_seq
							WHERE vcs.v_wvcs_seq = v_wvcs_seq ) ";

}else if($check_result2=="bad_ext"){	//위변조의심
	
	$search_sql .= " and exists (
							SELECT TOP 1 f.v_wvcs_file_seq
							from tb_v_wvcs_info_file f
							WHERE f.v_wvcs_seq = vcs.v_wvcs_seq
								AND f.file_scan_result ='BAD_EXT' ) ";
}

if($orderby != "") {
	$order_sql = " ORDER BY $orderby";
} else {
	$order_sql = " ORDER BY vcs.v_wvcs_seq DESC ";
}

$start = 0;
$rowcount = $_POST["record_count"];

$lastPageNo = ceil($rowcount / RECORD_LIMIT_PER_FILE);
		
for ($i = $start; $i < $lastPageNo; $i ++) {

	$end = RECORD_LIMIT_PER_FILE*($i+1);

	$args = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start,"excel_download_flag"=>"1");		
	$Model_result->SHOW_DEBUG_SQL = false;
	$result = $Model_result->getVCSList($args);

	$style['th'] ='background-color: #D4D0C8; border:0.5px solid black;';
	$style['td'] ='text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black; ';

	$splitHTML[$i] = '<table id="tblList" style="border-collapse: collapse;border:1px solid black;width: 100%;">
		 <tr>
			<th style="width:50px; '.$style['th'].'">'.$_LANG_TEXT['numtext'][$lang_code].'</th>
			<th style="width:100px; '.$style['th'].'">'.$_LANG_TEXT['visitor_name'][$lang_code].'</th>
			<th style="width:200px; '.$style['th'].'">'.$_LANG_TEXT['belongtext'][$lang_code].'</th>
			<th style="width:100px; '.$style['th'].'">'.$_LANG_TEXT['contactphonetext'][$lang_code].'</th>
			<th style="width:200px; '.$style['th'].'">'.$_LANG_TEXT['checkdatetext'][$lang_code].'</th>
			<th style="width:150px; '.$style['th'].'">'.$_LANG_TEXT['scancentertext'][$lang_code].'</th>
			<th style="width:150px; '.$style['th'].'">'.$_LANG_TEXT['devicegubuntext'][$lang_code].'</th>
			<th style="width:150px; '.$style['th'].'">'.$_LANG_TEXT['serialnumbertext'][$lang_code].'</th>

			<th style="width:150px; '.$style['th'].'">'.trsLang('임직원','executives').'</th>
			<th style="width:150px; '.$style['th'].'">'.trsLang('담당자영문명','manager_en_text').'</th>
			<th style="width:150px; '.$style['th'].'">'.trsLang('담당부서','managedepartmenttext').'</th>
			<th style="width:150px; '.$style['th'].'">'.trsLang('전자문서번호','electronic_payment_document_number').'</th>
			<th style="width:150px; '.$style['th'].'">USB '.trsLang('관리번호','managenumber').'</th>
			
			<th style="width:150px; '.$style['th'].'">'.$_LANG_TEXT['progressstatustext'][$lang_code].'</th>
			<th style="width:150px; '.$style['th'].'">'.$_LANG_TEXT['scanfilecount'][$lang_code].'</th>
			<th style="width:150px; '.$style['th'].'">'.$_LANG_TEXT['importfilecount'][$lang_code].'</th>
			<th style="width:150px; '.$style['th'].'">'.trsLang('위변조의심','suspectforgerytext').'</th>
			<th style="width:150px; '.$style['th'].'">'.trsLang('악성코드발견','viruscleantext').'</th>
			<th style="width:150px; '.$style['th'].'">'.trsLang('PE 제작','madeby_pe').'</th>
			<th style="width:150px; '.$style['th'].'">'.trsLang('실물반입','carryinmediatext').'</th>
		</tr>';

	if ($result) {
		$no = 1;
		while ($row = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

			$v_wvcs_seq = $row['v_wvcs_seq'];

			$check_date = $row['check_date'];
			$in_available_date  = $row['checkin_available_dt'];
			
			$in_date	= $row['in_date'];
			$out_date = $row['out_date'];
			
			$v_user_seq = $row['v_user_seq'];
			$v_user_list_seq = $row['v_user_list_seq'];
			$v_asset_type = $row['v_asset_type'];
			$v_scan_center_name = $row['scan_center_name'];
			$sys_sn = $row['v_sys_sn'];
			$manager_dept = $row['manager_dept'];
			$manager_name = aes_256_dec($row['manager_name']);
			$manager_name_en = $row['manager_name_en'];
			$v_user_name = aes_256_dec($row['v_user_name']);
			$v_user_sq = $row['v_user_seq'];
			$weak_cnt = $row['weak_cnt'];
			$virus_cnt = $row['virus_cnt'];
			$wvcs_authorize_yn = $row['wvcs_authorize_yn'];
			$vacc_scan_count = $row['vacc_scan_count'];	//바이러스검사파일
			$file_bad_cnt = $row['file_bad_cnt'];		
			$scan_file_cnt = $row['scan_file_cnt'];
			$os_ver_name = $row['os_ver_name'];

			$v_user_belong = $row['v_user_belong'];
			$usb_mgt_no = $row['label_value'];
			$elec_doc_number = $row['elec_doc_number'];
				if ($row['make_winpe']=="1") {

							$make_winpe = trsLang('제작','produce_text');
						} else {
							$make_winpe = "";
						}

			//파일정보를 서버로 전송하는 경우는 바이러스 검사파일수 대신 전송된 파일정보수를 표시해 준다.
			if($scan_file_cnt > 0){
				$vacc_scan_count = $scan_file_cnt;
			}

			$disk_cnt = $row['disk_cnt'];
			$import_file_cnt = $row['import_file_cnt'];
			
			if($_encryption_kind=="1"){

				$phone_no = $row['v_phone_decript'];
				$email = $row['v_email_decript'];

			}else if($_encryption_kind=="2"){
				
				if($row['v_phone'] != "") $phone_no = aes_256_dec($row['v_phone']);
				if($row['v_email'] != "")  $email = aes_256_dec($row['v_email']);
			}

			$vcs_status = $row['vcs_status'];
			$str_vcs_status = $_CODE['vcs_status'][$vcs_status];

			if($row['device_in_flag']=="1"){
				$str_device_in_flag = trsLang('반입','intext');
			}else{
				$str_device_in_flag = "";
			}

			
			$splitHTML[$i] .= '<tr>
                <td   style="'.$style['td'].'">' . $no . '</td>
                <td   style="'.$style['td'].'">' . $v_user_name . '</td>
                <td   style="'.$style['td'].'">' . $v_user_belong . '</td>
                <td   style="'.$style['td'].'">' . $phone_no . '</td>
                <td   style="'.$style['td'].'">' . $check_date . '</td>
                <td   style="'.$style['td'].'">' . $v_scan_center_name . '</td>
                <td   style="'.$style['td'].'">' . $os_ver_name . '</td>
                <td   style="'.$style['td'].'">' . $sys_sn . '</td>							
                <td   style="'.$style['td'].'">' . $manager_name . '</td>
                <td   style="'.$style['td'].'">' . $manager_name_en . '</td>
                <td   style="'.$style['td'].'">' . $manager_dept . '</td>	
                <td   style="'.$style['td'].'">' . $elec_doc_number . '</td>		
                <td   style="'.$style['td'].'">' . $usb_mgt_no . '</td>		
                <td   style="'.$style['td'].'">' . $str_vcs_status . '</td>		
                <td   style="'.$style['td'].'">' . $scan_file_cnt . '</td>		
                <td   style="'.$style['td'].'">' . $import_file_cnt . '</td>	
                <td   style="'.$style['td'].'">' . $file_bad_cnt . '</td>	
                <td   style="'.$style['td'].'">' . $virus_cnt . '</td>																				
                <td   style="'.$style['td'].'">' . $make_winpe . '</td>																			
                <td   style="'.$style['td'].'">' . $str_device_in_flag . '</td>																				
            </tr>';

			$no++;
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
