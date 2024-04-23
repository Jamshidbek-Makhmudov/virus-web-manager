<?php
$page_name = "k_report";

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";


$filename = "Report_excel[".date('Ymd')."].xls";

header( "Content-type: application/vnd.ms-excel; charset=utf-8" ); 
header( "Content-Disposition: attachment; filename=$filename" ); 
header( "Content-Description: PHP5 Generated Data" );

$now_year = date("Y");
$now_month = date("m");
$today = date("Y-m-d");

$title = $_REQUEST[title];
$reporter = $_REQUEST[reporter];
$reportdate = $_REQUEST[reportdate];
$report_image_data = $_REQUEST[report_image_data];

$printdate1 = $_REQUEST[printdate1];
$printdate2 = $_REQUEST[printdate2];
$scan_center_code = $_REQUEST[scan_center_code];
$options = $_REQUEST[options];

$start_date =  preg_replace("/[^0-9]*/s", "", $printdate1); 
$end_date = preg_replace("/[^0-9]*/s", "", $printdate2);    

//출력옵션
$print_option = array(
	"DAILY_VISIT_STAT"=>trsLang("일별출입통계",'dailyvisitstatisticstext')
	,"VISIT_LIST"=>trsLang("출입내역",'entryExitHistory')
	,"DAILY_VCS_STAT"=>trsLang("일별점검통계",'dailyscanstatisticstext')
	,"VCS_LIST"=>trsLang("점검내역",'checklisttext')
	,"VCS_RESULT_STAT"=>trsLang("점검결과통계",'scanresultstatisticstext')
	,"BAD_FILE_LIST"=>trsLang("위변조의심내역",'badextentionlisttext')
	,"VIRUS_FILE_LIST"=>trsLang("악성코드내역",'viruslisttext')
);

if(is_array($options) ==false) return;
$style_th = " style='background-color:#D4D0C8' ";
$paging = "999999";

//다운로드 로그 기록
$proc_name = $_POST['proc_name'];
if($proc_name != ""){
	$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');
}

$Model_Stat = new Model_Stat();

?>
<html>
<head>
	<title><?=$title?></title>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
</head>
<body topmargin='0' leftmargin='0'>

<?
//**일별 출입통계
	if(in_array("DAILY_VISIT_STAT",$options)){	

		echo "<div>".$print_option['DAILY_VISIT_STAT']."</div>";

		$search_sql = "";

		if($scan_center_code != ""){

			$search_sql .= " and c.scan_center_code = '{$scan_center_code}' ";	
		}

		$Model_Stat->SHOW_DEBUG_SQL=false;
		$args=array("start_date"=>$start_date,"end_date"=>$end_date,"search_sql"=>$search_sql);

		$result = $Model_Stat->getVisitStatDaily($args); 
		$total = 0;
		if($result){

			while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
			
				$data_label[] = setDateFormat($row['label'],"m/d");
				$data_value[] = $row['cnt'];
				$total += intVal($row['cnt']);
			}
		}

	

		echo "<table border='1'>";
		echo "<tr>";
		echo "<th {$style_th}>".$_LANG_TEXT["gubuntext"][$lang_code]."</th>";
		foreach($data_label as $label) echo "<th {$style_th}>".$label."</th>";
		echo "<th {$style_th}>Total</th>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>".trsLang('출입건수','count_enter_exit')."</td>";
		foreach($data_value as $value) echo "<td>".number_format($value)."</td>";
		echo "<td>".number_format($total)."</td>";
		echo "</tr>";
		echo "</table>";
		echo "</p>";

	}
?>

<?
//**출입 내역
	if(in_array("VISIT_LIST",$options)){	

		echo "<div>".$print_option['VISIT_LIST']."</div>";

		$search_sql = "";
		if($start_date != "" && $end_date != ""){

			$str_start_date = preg_replace("/[^0-9]*/s", "", $start_date)."000000";
			$str_end_date = preg_replace("/[^0-9]*/s", "", $end_date)."235959";

			$search_sql .= " AND v2.in_time between '{$str_start_date}' and '{$str_end_date}' ";
		}

		if($scan_center_code != "" ){
			$search_sql .= " AND v2.in_center_code = '{$scan_center_code}'  ";
		}

		$Model_User = new Model_User();

		$args = array("search_sql" => $search_sql);
		$Model_User->SHOW_DEBUG_SQL = false;
		$total = $Model_User->getUserVistListCount($args);
		$rows = $paging;			// 페이지당 출력갯수
		$lists = $_list;				// 목록수
		$page_count = ceil($total / $rows);
		if (!$page || $page > $page_count) $page = 1;
		$start = ($page - 1) * $rows;
		$no = $total - $start;
		$end = $start + $rows;

		$order_sql = " ORDER BY v2.v_user_list_seq DESC ";

		$args = array("order_sql" => $order_sql, "search_sql" => $search_sql, "end" => $end, "start" => $start);
		$Model_User->SHOW_DEBUG_SQL = false;
		$result = $Model_User->getUserVistList($args);
		

		echo "<table border='1'>";
		echo "	<tr>
			<th {$style_th}>".$_LANG_TEXT['numtext'][$lang_code] ."</th>
			<th {$style_th}>".trsLang('소속구분','belongdivtext')."</th>
			<th {$style_th}>".$_LANG_TEXT['visitor_name'][$lang_code] ."</th>
			<th {$style_th}>".$_LANG_TEXT['belongtext'][$lang_code] ."</th>
			<th {$style_th}>".$_LANG_TEXT['entry_time'][$lang_code] ."</th>
			<th {$style_th}>".$_LANG_TEXT['purpose_visit'][$lang_code] ."</th>
			<th {$style_th}>".trsLang('검사장','scancentertext')."</th>
			<th {$style_th}>".$_LANG_TEXT['managertext'][$lang_code] ."</th>
			<th {$style_th}>".$_LANG_TEXT['managedepartmenttext'][$lang_code] ."</th>
			<th {$style_th}>".trsLang('임시출입증번호','temppassnumber')."</th>
			<th {$style_th}>".trsLang('자산반입','assetimporttext')."</th>
			<th {$style_th}>".trsLang('파일반입','fileimport')."</th>
		</tr>";

		if ($result) {
			while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

				$v_user_list_seq = $row['v_user_list_seq'];

				$v_user_name = aes_256_dec($row['v_user_name']);

				$v_user_name_en = $row['v_user_name_en'];

				$v_phone = $row['v_phone'];
				$v_email = $row['v_email'];

				//not used
				$v_company = $row['v_company'];
				$v_purpose = $row['v_purpose'];
				$manager_name = aes_256_dec($row['manager_name']);
				$manager_name_en = $row['manager_name_en'];

				$manager_dept = $row['manager_dept'];
				$additional_cnt = $row['additional_cnt'];

				$memo = $row['memo'];

				$in_time = $row['in_time'];

				$in_center_code = $row['in_center_code'];

				$in_center_name = $row['in_center_name'];

				$pass_card_no = $row['pass_card_no'];
					
				$in_goods_doc_no = $row['in_goods_doc_no'];
				$elec_doc_number = $row['elec_doc_number'];
				$label_name = $row['label_name'];
				$label_value = $row['label_value'];

				$in_file_cnt = "0";		

				$v_user_type = $row['v_user_type'];
				$str_v_user_type = $_CODE_V_USER_TYPE_DETAILS[$v_user_type];

				$v_user_belong = $row['v_user_belong'];

				
				$rnum = $row['rnum'];


				if (!empty($in_time) && $in_time !== 'null') {
					$in_time_vl = date('Y-m-d H:i', strtotime($in_time));
				} else {
					$in_time_vl = '';
				}

				$param_enc = ParamEnCoding("v_user_list_seq=" . $v_user_list_seq . ($param ? "&" : "") . $param);
				$str_memo = $memo;

				//phone
				if($_encryption_kind=="1"){
					$phone_no = $row['v_phone'];
					
				}else if($_encryption_kind=="2"){
				
					if($row['v_phone'] != ""){
						$phone_no = aes_256_dec($row['v_phone']);
					}
				}

		?>

		<tr>
			<td><?= $no ?></td>
			<td><?= $str_v_user_type ?></td>
			<td>
					<?= $v_user_name ?><? if($v_user_name_en != "") echo " ($v_user_name_en)"; ?>
					<?if($additional_cnt > 0) echo " (+{$additional_cnt})";?>
			</td>
			<td><?= $v_user_belong ?></td>
			<td><?= $in_time_vl ?></td>
			<td><?= $v_purpose ?></td>
			<td><?= $in_center_name ?></td>
			<td><?= $manager_name ?> (<?= $manager_name_en ?>)</td>
			<td><?= $manager_dept ?></td>
			<td><? echo ($pass_card_no=="" ? "-" : $pass_card_no)  ?></td>
			<td><? echo ($in_goods_doc_no=="" ? "-" : $in_goods_doc_no) ?></td>
			<td><? echo ($elec_doc_number=="" ? "-" : $elec_doc_number )?></td>

		</tr>

		<?php

				$no--;
			}

		}

		echo "</table></p>";
		
	}
?>

<?
//**일별 점검통계
	if(in_array("DAILY_VCS_STAT",$options)){	
		
		echo "<div>".$print_option['DAILY_VCS_STAT']."</div>";

		$search_sql = "";

		if($scan_center_code != ""){

			$search_sql .= " and c.scan_center_code = '{$scan_center_code}' ";	
		}

		$Model_Stat->SHOW_DEBUG_SQL=false;
		$args=array("start_date"=>$start_date,"end_date"=>$end_date,"search_sql"=>$search_sql);

		$result = $Model_Stat->getVisitVcsStatDaily($args); 
		$total = 0;
		if($result){

			while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
			
				$data_label[] = setDateFormat($row['label'],"m/d");
				$data_value[] = $row['cnt'];
				$total += intVal($row['cnt']);
			}
		}

	

		echo "<table border='1'>";
		echo "<tr>";
		echo "<th {$style_th}>".$_LANG_TEXT["gubuntext"][$lang_code]."</th>";
		foreach($data_label as $label) echo "<th {$style_th}>".$label."</th>";
		echo "<th {$style_th}>Total</th>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>".trsLang('점검건수','scancounttext')."</td>";
		foreach($data_value as $value) echo "<td>".number_format($value)."</td>";
		echo "<td>".number_format($total)."</td>";
		echo "</tr>";
		echo "</table>";
		echo "</p>";

	}
?>

<?
//**점검 내역
	if(in_array("VCS_LIST",$options)){	

		echo "<div>".$print_option['VCS_LIST']."</div>";

		$search_sql = "";
		if($start_date != "" && $end_date != ""){

			$str_start_date = preg_replace("/[^0-9]*/s", "", $start_date)."000000";
			$str_end_date = preg_replace("/[^0-9]*/s", "", $end_date)."235959";

			$search_sql .= " AND vl.in_time between '{$str_start_date}' and '{$str_end_date}' ";
		}

		if($scan_center_code != "" ){
			$search_sql .= " AND vl.in_center_code = '{$scan_center_code}'  ";
		}

		$Model_result = new Model_result();
		$args = array("search_sql"=> $search_sql);

		$total = $Model_result->getVCSListCount($args);

		$rows = $paging;			// 페이지당 출력갯수
		$lists = $_list;			// 목록수
		$page_count = ceil($total/$rows);
		if(!$page || $page > $page_count) $page = 1;
		$start = ($page-1)*$rows;
		$no = $total-$start;
		$end = $start + $rows;

		$order_sql = " ORDER BY vcs.v_wvcs_seq DESC ";

		$args = array(
			"end"=> $end
			,"start"=>$start
			,"order_sql"=>$order_sql
			,"search_sql"=> $search_sql
		);

		$Model_result->SHOW_DEBUG_SQL = false;
		$result = $Model_result->getVCSList($args);
		

		echo "<table border='1'>";
		echo "	<tr>
				<th {$style_th}>".$_LANG_TEXT["numtext"][$lang_code]."</th>
				<th {$style_th}>".$_LANG_TEXT["checkdatetext"][$lang_code]."</th>
				<th {$style_th}>".$_LANG_TEXT["scancentertext"][$lang_code]."</th>
				<th {$style_th}>".$_LANG_TEXT["devicegubuntext"][$lang_code]."</th>
				<th {$style_th}>".$_LANG_TEXT["serialnumbertext"][$lang_code]."</th>
				<th {$style_th}>".trsLang('담당자','managertext')."</th>
				<th {$style_th}>".trsLang('담당부서','managedepartmenttext')."</th>
				<th {$style_th}>".trsLang('전자문서번호','electronic_payment_document_number')."</th>
				<th {$style_th}>USB ".trsLang('관리번호','managenumber')."</th>
				<th {$style_th}>".$_LANG_TEXT["progressstatustext"][$lang_code]."</th>
				<th {$style_th}>".$_LANG_TEXT["checkresulttext"][$lang_code]."</th>
				<th {$style_th}>".$_LANG_TEXT["scanfilecount"][$lang_code]."</th> ";

			if($_P_CHECK_FILE_SEND_TYPE !="N"){
				echo "<th {$style_th}>".$_LANG_TEXT["importfilecount"][$lang_code]."</th>";
			}

		echo "</tr>";

		 if($result){
			  while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

					$v_wvcs_seq = $row['v_wvcs_seq'];

					$check_date = $row['check_date'];
					$in_available_date  = $row['checkin_available_dt'];
					
					if($in_available_date){

						$hour = substr($in_available_date,8,2);
						$min = substr($in_available_date,10,2);

						$in_available_date = substr($in_available_date,0,4)."-".substr($in_available_date,4,2)."-".substr($in_available_date,6,2);
						
						$in_available_date = $in_available_date." ".($hour? $hour : "00").":".($min? $min : "00");
						
					}
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

					if($manager_name_en==""){
						$str_manager_name = $manager_name;
					}else{
						$str_manager_name = $manager_name." (".$manager_name_en.")";
					}
					
					//파일정보를 서버로 전송하는 경우는 바이러스 검사파일수 대신 전송된 파일정보수를 표시해 준다.
					if($scan_file_cnt > 0){
						$vacc_scan_count = $scan_file_cnt;
					}

					$disk_cnt = $row['disk_cnt'];
					$import_file_cnt = $row['import_file_cnt'];
					
					$param_enc = ParamEnCoding("v_wvcs_seq=".$v_wvcs_seq.($param==""? "":"&").$param);

					$check_result = array();
					
					//위변조의심
					if(in_array("BAD_EXT",$_CODE_INSPECT_OPTION)){

						if($file_bad_cnt > 0){
							$check_result[] =trsLang('위변조의심','suspected_forgery');
						}
					
					}

					if(in_array("WEAK",$_CODE_INSPECT_OPTION)){
					
						if($weak_cnt > 0){
							$check_result[] =trsLang('취약점','weaknesstext');
						}

					}

					if(in_array("VIRUS",$_CODE_INSPECT_OPTION)){

						if($virus_cnt > 0){
							$check_result[] =trsLang('악성코드발견','virusdetectiontext');
						}

					}
					
					$str_check_result  =implode(",",$check_result);
					if($str_check_result=="") {
						$str_check_result =trsLang('클린','cleantext');
					}

					if($_encryption_kind=="1"){

						$phone_no = $row['v_phone_decript'];
						$email = $row['v_email_decript'];

					}else if($_encryption_kind=="2"){

						$phone_no = aes_256_dec($row['v_phone']);
						$email = aes_256_dec($row['v_email']);
					}

					$vcs_status = $row['vcs_status'];
					$str_vcs_status = $_CODE['vcs_status'][$vcs_status];
					
			  ?>	
				<tr>
					<td><?php echo $no; ?></td>
					<td><?=$check_date?></td>
					<td><?=$v_scan_center_name?></td>
					<td><?=$os_ver_name?></td>
					<td><?=$sys_sn?></td>
					<td><?=$str_manager_name?></td>
					<td><?=$manager_dept?></td>
					<td><?=$elec_doc_number?></td>
					<td><?=$usb_mgt_no?></td>
					<td><?=$str_vcs_status?></td>
					<td><?=$str_check_result?></td>
					<td><?=number_format($scan_file_cnt);?></td>
					<?if($_P_CHECK_FILE_SEND_TYPE !="N"){?>
					<td><?=number_format($import_file_cnt);?></td>
					<?}?>
				</tr>
				<?php
				
					$no--;
				}
			
			echo "</table></p>";		
		}
		
	}
?>

<?
//**점검결과 통계
	if(in_array("VCS_RESULT_STAT",$options)){	

		echo "<div>".$print_option['VCS_RESULT_STAT']."</div>";

		$search_sql = "";

		if($scan_center_code != ""){

			$search_sql .= " and v2.in_center_code = '{$scan_center_code}' ";	
		}

		//**악성코드 발견
		$Model_Stat->SHOW_DEBUG_SQL=false;
		$args=array("start_date"=>$start_date,"end_date"=>$end_date,"search_sql"=>$search_sql);
		$result = $Model_Stat->getVisitVirusStat($args); 
		if($result){
			$virus_total = 0;
			while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){

				$data['virus_data'][] = array("virus_name"=>$row['virus_name'], "cnt"=>$row['cnt']);
				$virus_total  += intVal($row[cnt]);
			}
		}

		echo "<table border='1'>";
		echo "<tr>";
		echo "<th {$style_th}>".$_LANG_TEXT["virusnametext"][$lang_code]."</th>";
		echo "<th {$style_th}>".$_LANG_TEXT["detectcounttext"][$lang_code]."</th>";
		echo "<th {$style_th}>Percent</th>";
		echo "</tr>";
		
		foreach($data['virus_data'] as $data) {
			echo "<tr>";
			$percent = round($data['cnt']/$virus_total*100,0);

			echo "<td>".$data['virus_name']."</td>";
			echo "<td>".number_format($data['cnt'])."</td>";
			echo "<td>".$percent."</td>";
			echo "</tr>";
		}
		
		echo "<tr>";
		echo "<td>total</td>";
		echo "<td>".number_format($virus_total)."</td>";
		echo "<td>100</td>";
		echo "</tr>";
		echo "</table>";
		echo "</p>";


		//**위변조 의심
		$Model_Stat->SHOW_DEBUG_SQL=false;
		$args=array("start_date"=>$start_date,"end_date"=>$end_date,"search_sql"=>$search_sql);
		$result = $Model_Stat->getVisitBadExtionStat($args); 
		if($result){
			$bad_total = 0;
			while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){

				$data['bad_data'][] = array("file_signature"=>$row['file_signature'], "cnt"=>$row['cnt']);
				$bad_total  += intVal($row[cnt]);
			}
		}

		echo "<table border='1'>";
		echo "<tr>";
		echo "<th {$style_th}>".$_LANG_TEXT["suspectforgerytext"][$lang_code]."</th>";
		echo "<th {$style_th}>".$_LANG_TEXT["detectcounttext"][$lang_code]."</th>";
		echo "<th {$style_th}>Percent</th>";
		echo "</tr>";
		
		foreach($data['bad_data'] as $data) {
			echo "<tr>";
			$percent = round($data['cnt']/$bad_total*100,0);

			echo "<td>".$data['file_signature']."</td>";
			echo "<td>".number_format($data['cnt'])."</td>";
			echo "<td>".$percent."</td>";
			echo "</tr>";
		}
		
		echo "<tr>";
		echo "<td>total</td>";
		echo "<td>".number_format($bad_total)."</td>";
		echo "<td>100</td>";
		echo "</tr>";
		echo "</table>";
		echo "</p>";
	}
?>

<?
//**위변조의심내역
	if(in_array("BAD_FILE_LIST",$options)){	

		echo "<div>".$print_option['BAD_FILE_LIST']."</div>";

		$Model_result = new Model_result();

		$order_sql = " ORDER BY v1.v_wvcs_seq DESC "; 

		$search_sql = "";
		if($start_date != "" && $end_date != ""){

			$str_start_date = preg_replace("/[^0-9]*/s", "", $start_date)."000000";
			$str_end_date = preg_replace("/[^0-9]*/s", "", $end_date)."235959";

			$search_sql .= " AND v20.in_time between '{$str_start_date}' and '{$str_end_date}' ";
		}

		if($scan_center_code != "" ){
			$search_sql .= " AND v20.in_center_code = '{$scan_center_code}'  ";
		}

		$args = array("search_sql"=>$search_sql);
		$total = $Model_result->getUserVCSBadFileListCount($args);

		$rows = $paging;			// 페이지당 출력갯수

		$lists = $_list;				// 목록수
		$page_count = ceil($total / $rows);
		if (!$page || $page > $page_count) $page = 1;

		$start = ($page - 1) * $rows;
		$no = $total - $start;
		$end = $start + $rows;

		$args = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start);
		$result = $Model_result->getUserVCSBadFileList($args);
		

		echo "<table border='1'>";
		echo "	<tr>
						<th {$style_th}>". $_LANG_TEXT['numtext'][$lang_code] ."</th>
						<th {$style_th}>". $_LANG_TEXT['visitor_name'][$lang_code] ."</th>
						<th {$style_th}>". $_LANG_TEXT['belongtext'][$lang_code] ."</th>
						<th {$style_th}>". trsLang('방문일시','date_visit') ."</th>
						<th {$style_th}>". $_LANG_TEXT['purpose_visit'][$lang_code] ."</th>
						<th {$style_th}>". $_LANG_TEXT['inspection_center'][$lang_code] ."</th>
						<th {$style_th}>". $_LANG_TEXT['filepathtext'][$lang_code] ."</th>
						<th {$style_th}>". $_LANG_TEXT['filesizetext'][$lang_code] ."</th>
						<th {$style_th}>". $_LANG_TEXT["filesignature"][$lang_code] ."</th>
						<th {$style_th}>". trsLang('파일해시','filehash') ."(md5)</th>
					</tr>";

		if ($result) {
			while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
				
				$v_user_name = aes_256_dec($row[v_user_name]);
				$v_user_name_en = $row[v_user_name_en];
				$v_user_belong = $row[v_user_belong];
				$v_purpose = $row[v_purpose];
				$scan_center_name = $row[scan_center_name];
				$in_time = setDateFormat($row[in_time]);

				$v_wvcs_file_seq = $row['v_wvcs_file_seq'];
				$file_path = $row['file_path'].$row['file_name_org'];
				$file_size = getSizeCheck($row['file_size']);
				$file_signature = $row['file_signature'];
				$file_id  = $row['file_id'];
				$md5  = $row['md5'];

				$v_phone = $row['v_phone'];

		?>
				<tr>
					<td><?php echo $no; ?></td>
					<td><?= $v_user_name ?><? if($v_user_name_en != "") echo " ($v_user_name_en)"; ?></td>
					<td><?= $v_user_belong ?></td>
					<td><?= $in_time ?></td>
					<td><?= $v_purpose ?></td>
					<td><?= $scan_center_name ?></td>
					<td><?= $file_path ?></td>
					<td><?= $file_size ?></td>
					<td><?= $file_signature ?></td>
					<td><?= $md5 ?></td>
				</tr>
			<?php

				$no--;
			}
		}

		echo "</table></p>";		
		
	}
?>

<?
//**악성코드발견내역
	if(in_array("VIRUS_FILE_LIST",$options)){	

		echo "<div>".$print_option['VIRUS_FILE_LIST']."</div>";

		$Model_result = new Model_result();

		$order_sql = " ORDER BY v1.v_wvcs_seq DESC "; 

		$search_sql = "";
		if($start_date != "" && $end_date != ""){

			$str_start_date = preg_replace("/[^0-9]*/s", "", $start_date)."000000";
			$str_end_date = preg_replace("/[^0-9]*/s", "", $end_date)."235959";

			$search_sql .= " AND v20.in_time between '{$str_start_date}' and '{$str_end_date}' ";
		}

		if($scan_center_code != "" ){
			$search_sql .= " AND v20.in_center_code = '{$scan_center_code}'  ";
		}

		//바이러스상세정보
		$args = array("search_sql"=>$search_sql);
		$result = $Model_result->getVCSVirusFileDetailList($args);
		if($result){
			while($row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){

				$virus_info[$row[v_wvcs_file_seq]][] = array(
					"vaccine_name"=>$row[vaccine_name]
					,"scan_date"=> $row[scan_date]
					,"virus_name"=>$row[virus_name]
					);
				

			}
		}

		$args = array("search_sql"=>$search_sql);
		$total = $Model_result->getUserVCSVirusFileListCount($args);

		$rows = $paging;			// 페이지당 출력갯수

		$lists = $_list;				// 목록수
		$page_count = ceil($total / $rows);
		if (!$page || $page > $page_count) $page = 1;

		$start = ($page - 1) * $rows;
		$no = $total - $start;
		$end = $start + $rows;

		$args = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start);
		$result = $Model_result->getUserVCSVirusFileList($args);
		

		echo "<table border='1'>";
		echo "	<tr>
						<th {$style_th}>". $_LANG_TEXT['numtext'][$lang_code] ."</th>
						<th {$style_th}>". $_LANG_TEXT['visitor_name'][$lang_code] ."</th>
						<th {$style_th}>". $_LANG_TEXT['belongtext'][$lang_code] ."</th>
						<th {$style_th}>". trsLang('방문일시','date_visit') ."</th>
						<th {$style_th}>". $_LANG_TEXT['purpose_visit'][$lang_code] ."</th>
						<th {$style_th}>". $_LANG_TEXT['inspection_center'][$lang_code] ."</th>
						<th {$style_th}>". $_LANG_TEXT['filepathtext'][$lang_code] ."</th>
						<th {$style_th}>". trsLang('점검결과','checkresulttext') ."</th>
						<th {$style_th}>". trsLang('파일해시','filehash')."(md5)</th>
					</tr>";

		if($result){

			$no = $total;

			while($row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){

				$v_user_name = aes_256_dec($row[v_user_name]);
				$v_user_name_en = $row[v_user_name_en];
				$v_user_belong = $row[v_user_belong];
				$v_purpose = $row[v_purpose];
				$scan_center_name = $row[scan_center_name];
				$v_wvcs_file_seq = $row[v_wvcs_file_seq];
				$md5  = $row['md5'];
				$in_time = setDateFormat($row[in_time]);

				$file_path = $row[file_path].$row[file_name_org];
				
				$str_virus_info= "";
				for($i =0 ; $i < count($virus_info[$v_wvcs_file_seq]) ;$i++){	
					$_virus_info = $virus_info[$v_wvcs_file_seq][$i];
					//$scan_date = setDateFormat($_virus_info[scan_date]);
					$str_virus_info .= "<li>".$_virus_info[vaccine_name]." - ".$_virus_info[virus_name]."</li>";
				}
				
			?>
				<tr>
					<td><?php echo $no; ?></td>
					<td><?= $v_user_name ?><? if($v_user_name_en != "") echo " ($v_user_name_en)"; ?></td>
					<td ><?= $v_user_belong ?></td>
					<td><?= $in_time ?></td>
					<td ><?= $v_purpose ?></td>
					<td><?= $scan_center_name ?></td>
					<td><?= $file_path ?></td>
					<td><?= $str_virus_info ?></td>
					<td><?= $md5 ?></td>
				</tr>
			<?
				$no--;
			}
		}

		echo "</table></p>";		
		
	}
?>

<?
	if($result) sqlsrv_free_stmt($result);  
	if($wvcs_dbcon) sqlsrv_close($wvcs_dbcon);

	exit;
?>
</body>
</html>