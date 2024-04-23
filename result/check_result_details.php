<?php
$page_name = "result_list_details";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI']) - 1);
$_apos = stripos($_REQUEST_URI,  "/");
if ($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
include_once $_server_path . "/" . $_site_path . "/inc/header.inc";
include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";

$view_src = $_REQUEST[view_src];
$searchopt = $_REQUEST[searchopt];	// �˻��ɼ�
$searchkey = $_REQUEST[searchkey];	// �˻���
$asset_type = $_REQUEST[asset_type];
$storage_device_type = $_REQUEST[storage_device_type];
$vcs_type = $_REQUEST[vcs_type];
$scan_center_code = $_REQUEST[scan_center_code];
$check_result1 = $_REQUEST[check_result1];
$check_result2 = $_REQUEST[check_result2];
$checkdate1 = $_REQUEST[checkdate1];
$checkdate2 = $_REQUEST[checkdate2];
$io_gubun = $_REQUEST[io_gubun];
$iodate1 = $_REQUEST[iodate1];
$iodate2 = $_REQUEST[iodate2];
$status = $_REQUEST[status];
$org_name = $_REQUEST[org_name];
$v_user_seq = $_REQUEST[v_user_seq];
$orderby = $_REQUEST[orderby];		// ���ļ���
$page = $_REQUEST[page];			// ������
$v_wvcs_seq =  $_REQUEST[v_wvcs_seq];
$user_check_list_page = $_REQUEST[user_check_list_page];
if ($user_check_list_page == "") $user_check_list_page = 1;


$param = "";
if ($view_src != "") $param .= ($param == "" ? "" : "&") . "view_src=" . $view_src;
if ($searchopt != "") $param .= ($param == "" ? "" : "&") . "searchopt=" . $searchopt;
if ($searchkey != "") $param .= ($param == "" ? "" : "&") . "searchkey=" . $searchkey;
if ($asset_type != "") $param .= ($param == "" ? "" : "&") . "asset_type=" . $asset_type;
if ($storage_device_type != "") $param .= ($param == "" ? "" : "&") . "storage_device_type=" . $storage_device_type;
if ($vcs_type != "") $param .= ($param == "" ? "" : "&") . "vcs_type=" . $vcs_type;
if ($scan_center_code != "") $param .= ($param == "" ? "" : "&") . "scan_center_code=" . $scan_center_code;
if ($check_result1 != "") $param .= ($param == "" ? "" : "&") . "check_result1=" . $check_result1;
if ($check_result2 != "") $param .= ($param == "" ? "" : "&") . "check_result2=" . $check_result2;
if ($checkdate1 != "") $param .= ($param == "" ? "" : "&") . "checkdate1=" . $checkdate1;
if ($checkdate2 != "") $param .= ($param == "" ? "" : "&") . "checkdate2=" . $checkdate2;
if ($io_gubun != "") $param .= ($param == "" ? "" : "&") . "io_gubun=" . $io_gubun;
if ($iodate1 != "") $param .= ($param == "" ? "" : "&") . "iodate1=" . $iodate1;
if ($iodate2 != "") $param .= ($param == "" ? "" : "&") . "iodate2=" . $iodate2;
if ($status != "") $param .= ($param == "" ? "" : "&") . "status=" . $status;
if ($org_name != "") $param .= ($param == "" ? "" : "&") . "org_name=" . $org_name;
if ($v_user_seq != "") $param .= ($param == "" ? "" : "&") . "v_user_seq=" . $v_user_seq;
if ($page != "") $param .= ($param == "" ? "" : "&") . "page=" . $page;

// if (isset($v_wvcs_seq)) {

// 	$search_sql = "";

// 	if ($asset_type != "") {

// 		$search_sql .=  " AND vcs.v_asset_type = '" . $asset_type . "' ";
// 	}

// 	if ($storage_device_type != "") {

// 		if ($storage_device_type == 'DEVICE_ETC') {

// 			$search_sql .= " AND exists (select value from dbo.fn_split(vcd.os_ver_name,',') WHERE value not in ('Removable','HDD','CD/DVD') and value > '' ) ";
// 		} else {

// 			$search_sql .=  " AND CHARINDEX('" . $storage_device_type . "',vcd.os_ver_name) > 0 ";
// 		}
// 	}

// 	if ($vcs_type != "") {

// 		$search_sql .=  " AND vcs.wvcs_type = '" . $vcs_type . "' ";
// 	}

// 	if ($scan_center_code != "") {

// 		$search_sql .=  " AND vcs.scan_center_code = '" . $scan_center_code . "' ";
// 	}


// 	if ($checkdate1 != "" && $checkdate2 != "") {

// 		$search_sql .= " AND vcs.wvcs_dt between '$checkdate1 00:00:00.000' and '$checkdate2 23:59:59.999' ";
// 	}

// 	if ($io_gubun == "indate" && $iodate1 != "" && $iodate2 != "") {

// 		$search_sql .= " AND vcs.wvcs_authorize_dt between '$iodate1 00:00:00.000' and '$iodate2 23:59:59.999' ";
// 	}

// 	if ($io_gubun == "outdate" && $iodate1 != "" && $iodate2 != "") {

// 		$search_sql .= " AND vcs.checkout_dt between '$iodate1 00:00:00.000' and '$iodate2 23:59:59.999' ";
// 	}

// 	if ($status != "") {

// 		$search_sql .= " and vcs.vcs_status = '$status' ";
// 	} //if($status !=""){

// 	if ($v_user_seq != "") {

// 		$search_sql .= " AND us.v_user_seq = '$v_user_seq' ";
// 	}


// 	if ($searchkey != "") {

// 		if ($searchopt == "USER_NAME") {

// 			$search_sql .= " and us.v_user_name like '%$searchkey%' ";
// 		} else if ($searchopt == "CHECK_APRV_NAME") {

// 			$search_sql .= " and vcs.wvcs_authorize_name like '%$searchkey%' ";
// 		} else if ($searchopt == "OS") {

// 			$search_sql .= " and vcd.os_ver_name like '%$searchkey%' ";
// 		} else if ($searchopt == "MODEL") {

// 			$search_sql .= " and vcd.v_model_name like '%$searchkey%' ";
// 		} else if ($searchopt == "MANUFACTURER") {

// 			$search_sql .= " and vcd.v_manufacturer like '%$searchkey%' ";
// 		} else if ($searchopt == "SN") {

// 			$search_sql .= " and vcd.v_sys_sn like '%$searchkey%' ";
// 		} else if ($searchopt == "MANAGER") {

// 			$search_sql .= " and vcs.mngr_name like '%$searchkey%' ";
// 		} else if ($searchopt == "MANAGER_DEPT") {

// 			$search_sql .= " and vcs.mngr_department like '%$searchkey%' ";
// 		} else if ($searchopt == "ORG_NAME") {

// 			$search_sql .= " and org.org_name like '%$searchkey%' ";
// 		} else if ($searchopt == "SN") {

// 			$search_sql .= " and vcd.v_sys_sn like '%$searchkey%' ";
// 		} else if ($searchopt == "COM_NAME") {

// 			$search_sql .= " and vc.v_com_name like '%$searchkey%' ";
// 		} else if ($searchopt == "COM_SEQ") {

// 			$search_sql .= " and vc.v_com_seq = '{$searchkey}' ";
// 		}
// 	} //if($searchkey != ""){


// 	if ($check_result2 == "weak") {

// 		$search_sql .= " and exists (SELECT TOP 1 weakness_seq FROM tb_v_wvcs_weakness WHERE vcs.v_wvcs_seq = v_wvcs_seq) ";
// 	} else if ($check_result2 == "virus") {

// 		$search_sql .= " and exists (
// 							SELECT	TOP 1 vcc.vaccine_seq 
// 							FROM	tb_v_wvcs_vaccine vcc
// 								INNER JOIN tb_v_wvcs_vaccine_detail vccd
// 									ON vcc.vaccine_seq = vccd.vaccine_seq
// 							WHERE	vcs.v_wvcs_seq = v_wvcs_seq ) ";
// 	}


// 	if ($orderby != "") {
// 		$order_sql = " ORDER BY $orderby";
// 	} else {
// 		$order_sql = " ORDER BY vcs.v_wvcs_seq DESC ";
// 	}

// 	$qry_params = array("search_sql" => $search_sql, "order_sql" => $order_sql, "v_wvcs_seq" => $v_wvcs_seq);
// 	$qry_label = QRY_RESULT_PC_CHECK_INFO;
// 	$sql = query($qry_label, $qry_params);

// 	//echo nl2br($sql);
// 	$result = @sqlsrv_query($wvcs_dbcon, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
// 	$row = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
// 	$row_count = @sqlsrv_num_rows($result);


// 	if ($row) {

// 		$v_user_seq = $row['v_user_seq'];
// 		$check_date = $row['check_date'];
// 		$in_available_date = $row['checkin_available_dt'];
// 		if ($in_available_date) {

// 			$hour = substr($in_available_date, 8, 2);
// 			$min = substr($in_available_date, 10, 2);

// 			$in_available_date = substr($in_available_date, 0, 4) . "-" . substr($in_available_date, 4, 2) . "-" . substr($in_available_date, 6, 2);

// 			$in_available_date = $in_available_date . " " . ($hour ? $hour : "00") . ":" . ($min ? $min : "00");
// 		}

// 		$in_date = substr($row['wvcs_authorize_dt'], 0, 16);
// 		$out_date = substr($row['checkout_dt'], 0, 16);
// 		$device_gubun = $row['v_asset_type'];
// 		$os_ver_name = $row['os_ver_name'];
// 		$model_name = $row['v_model_name'];
// 		$manufacturer = $row['v_manufacturer'];
// 		$ip_addr = $row['ip_addr'];
// 		$mac_addr = $row['mac_addr'];
// 		$v_user_name = $row['v_user_name'];
// 		$v_com_name = $row['v_com_name'];
// 		$mngr_name = $row['mngr_name'];
// 		$mngr_dept = $row['mngr_department'];
// 		$scan_center_code = $row['scan_center_code'];
// 		$scan_center_name = $row['scan_center_name'];
// 		$barcode = $row['barcode'];

// 		$v_notebook_key = $row['v_notebook_key'];
// 		$v_asset_type = $row['v_asset_type'];



// 		if ($_encryption_kind == "1") {

// 			$phone_no = $row['v_phone_decript'];
// 			$email = $row['v_email_decript'];
// 		} else if ($_encryption_kind == "2") {

// 			$phone_no = AES_Rijndael_Decript($row['v_phone'], $_AES_KEY, $_AES_IV);
// 			$email = AES_Rijndael_Decript($row['v_email'], $_AES_KEY, $_AES_IV);
// 		}

// 		if ($_cfg_user_identity_name == "phone") {
// 			$v_user_name_com = $phone_no;
// 			$v_user_name = $phone_no;
// 		} else if ($_cfg_user_identity_name == "email") {
// 			$v_user_name_com = $email;
// 			$v_user_name = $email;
// 		} else {
// 			if ($v_com_name == "-") $v_com_name = "";
// 			$v_user_name_com = $v_user_name . ($v_com_name ? "/" : "") . $v_com_name;
// 		}

// 		$check_type = $row['wvcs_type'];
// 		$org_name = $row['org_name'];

// 		$apprv_yn = $row['wvcs_authorize_yn'];

// 		$apprv_dt = ($apprv_yn == "Y") ? $row['wvcs_authorize_dt'] : "";
// 		$apprv_name = $row['wvcs_authorize_name'];

// 		$memo = $row['memo_text'];

// 		$vcs_status = $row['vcs_status'];
// 		$str_vcs_status = $_CODE['vcs_status'][$vcs_status];

// 		$last_check_date = $check_date; //$row['last_check_date'];
// 		$rnum = $row['rnum'];

// 		$disk_cnt = $row['disk_cnt'];

// 		$vacc_scan_count = $row['vacc_scan_count'];

// 		$import_file_cnt = $row['import_file_cnt'];
// 	}

// 	//echo nl2br($sql);

// 	//����,����
// 	$qry_params = array("search_sql" => $search_sql, "order_sql" => $order_sql, "rnum" => $rnum);
// 	$qry_label = QRY_RESULT_PC_CHECK_INFO_PREV;
// 	$sql = query($qry_label, $qry_params);

// 	$result = sqlsrv_query($wvcs_dbcon, $sql);
// 	$row = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
// 	$prev_v_wvcs_seq = $row['v_wvcs_seq'];
// 	$prev_v_asset_type = $row['v_asset_type'];

// 	$qry_params = array("search_sql" => $search_sql, "order_sql" => $order_sql, "rnum" => $rnum);
// 	$qry_label = QRY_RESULT_PC_CHECK_INFO_NEXT;
// 	$sql = query($qry_label, $qry_params);

// 	$result = sqlsrv_query($wvcs_dbcon, $sql);
// 	$row = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
// 	$next_v_wvcs_seq = $row['v_wvcs_seq'];
// 	$next_v_asset_type = $row['v_asset_type'];


// 	if ($prev_v_asset_type == 'NOTEBOOK') {
// 		$prev_url = $_www_server . "/result/result_view_pc.php";
// 	} else {
// 		$prev_url = $_www_server . "/result/result_view_storage.php";
// 	}

// 	if ($next_v_asset_type == 'NOTEBOOK') {
// 		$next_url = $_www_server . "/result/result_view_pc.php";
// 	} else {
// 		$next_url = $_www_server . "/result/result_view_storage.php";
// 	}

// 	//	echo "prev_v_asset_type:".$prev_v_asset_type;
// 	//	echo "next_v_asset_type:".$next_v_asset_type;

// }

//�˻���å��������
$_POLICY = getPolicy('checkin_file_send_type,vaccine_check_yn,file_scan_yn');

if ($_POLICY['checkin_file_send_type'] == "") $_POLICY['checkin_file_send_type'] = "N";	//�������ۿ���
if ($_POLICY['vaccine_check_yn'] == "") $_POLICY['vaccine_check_yn'] = "Y";	//��Ű˻翩��
if ($_POLICY['file_scan_yn'] == "") $_POLICY['file_scan_yn'] = "N";	//���ϰ˻翩��

if (!is_instance($Model_result)) {
	$Model_result = new Model_result();
}
//$Model_result->SHOW_DEBUG_SQL = true;


?>
<script language="javascript">
	$("document").ready(function() {
		<? if ($_POLICY['vaccine_check_yn'] == "Y") {	//�Ǽ��ڵ�
			echo "CheckLoadVaccineInfo({$v_wvcs_seq});";
		} else if ($_POLICY['file_scan_yn'] == "Y") {		//������ �ǽ�
			echo "CheckLoadBadFileInfo({$v_wvcs_seq});";
		} else if ($_POLICY['checkin_file_send_type'] != "N") {	//������������
			echo "CheckLoadImportFileInfo({$v_wvcs_seq});";
		} ?>
		LoadPageDataList('user_check_list', SITE_NAME + '/result/get_user_check_list.php', "enc=<?= ParamEnCoding('src=RESULT_VIEW&v_user_seq=' . $v_user_seq . '&page=' . $user_check_list_page . '&v_asset_type=' . $v_asset_type . '&v_notebook_key=' . $v_notebook_key . '&view_v_wvcs_seq=' . $v_wvcs_seq) ?>");

	});

	function CheckLoadBadFileInfo(v_wvcs_seq) {

		$.post(
			SITE_NAME + '/result/check_result_get_bad_file.php', {
				v_wvcs_seq: v_wvcs_seq
			},
			function(data) {
				$('#file_bad_list').html(data);
			},
			'text'
		);
	}

	function CheckLoadImportFileInfo(v_wvcs_seq) {

		$.post(
			SITE_NAME + '/result/check_result_get_import_file.php', {
				v_wvcs_seq: v_wvcs_seq
			},
			function(data) {
				$('#file_import_list').html(data);
			},
			'text'
		);
	}

	function CheckLoadScanFileInfo(v_wvcs_seq) {

		$.post(
			SITE_NAME + '/result/check_result_get_scan_file.php', {
				v_wvcs_seq: v_wvcs_seq
			},
			function(data) {
				$('#file_scan_list').html(data);
			},
			'text'
		);
	}
</script>
<div id="result_view">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["result_list_details"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>

		<div class="page_right">
			<? if ($view_src == "RESULT_LIST") { ?>
				<span style='cursor:pointer' onclick="location.href='./result_list.php?enc=<?= ParamEnCoding($param) ?>'"><?= $_LANG_TEXT['btngobeforepage'][$lang_code] ?></span>
			<? } else { ?>
				<span style='cursor:pointer' onclick="history.back();"><?= $_LANG_TEXT['btngobeforepage'][$lang_code] ?></span>
			<? } ?>
		</div>
		<div style="margin-top:50px">
			<ul class="tab">

				<li class="on">
					<a href="#" onclick=""><?= $_LANG_TEXT['checkinfotext'][$lang_code] ?></a>
					<div>
						<form name='frmVCS' id='frmVCS' method='POST'>
							<input type='hidden' id='v_wvcs_seq' name='v_wvcs_seq' value='<?= $v_wvcs_seq ?>'>
							<input type='hidden' id='proc' name='proc'>
							<table class="view">
								<tr>
									<th style='width:150px'><?= $_LANG_TEXT['inspection_site_date'][$lang_code] ?></th>
									<td style='width:350px'><?= $check_date ?></td>
									<th class="line" style='width:150px'><?= $_LANG_TEXT['inspection_equipment'][$lang_code] ?></th>
									<td><?= $last_check_date ?></td>
								</tr>
								<tr class="bg">
									<th><?= $_LANG_TEXT['visitor_affiliation'][$lang_code] ?></th>
									<td><?= $_CODE['asset_type'][$device_gubun] ?><span class='blue'>(<?= $disk_cnt ?>)</span></td>
									
									<th class="line" style='width:150px'><?= $_LANG_TEXT['contactphonetext'][$lang_code] ?></th>
									<td><?= $os_ver_name ?></td>
								</tr>
		
								<tr>
									<th><?= $_LANG_TEXT['progressstatustext'][$lang_code] ?></th>
									<td><?= $v_user_name_com ?></td>
									<th class="line"><?= $_LANG_TEXT['contact_person_or_department'][$lang_code] ?></th>
									<td><?= $check_type ?></td>
								</tr>

								<tr>
									<th><?= $_LANG_TEXT['memotext'][$lang_code] ?></th>
									<td colspan="3">
										<? if ($_ck_user_level == "SECURITOR_S") { ?>
											<?= $memo ?>
										<? } else { ?>
											<input type='text' id='memo' name='memo' class='frm_input' value='<?= $memo ?>' style='width:90%' maxlength="250">
										<? } ?>
									</td>
								</tr>
							</table>
						</form>
					</div>
				</li>
				<li>
					<a href="javascript:" onclick="LoadDiskInfoDetails(<?= $v_wvcs_seq ?>);"><?= $_LANG_TEXT['diskinfotext'][$lang_code] ?></a>
					<div id='disk_info'></div>
				</li>

			</ul>

		</div>

		<div class="btn_wrap">
			<!-- <div class="left">
				<a href="<?// if (empty($prev_v_wvcs_seq)) { ?>javASCript:alert(nodatatext[lang_code])<? //} else {
																																														//echo $prev_url . "?enc=" . ParamEnCoding("v_wvcs_seq=" . $prev_v_wvcs_seq . ($param ? "&" : "") . $param);
																																												///	} ?>" class="btn" id='btnPrev'><?//= $_LANG_TEXT["btnprev"][$lang_code]; ?></a>
				<a href="<? //if (empty($next_v_wvcs_seq)) { ?>javASCript:alert(nodatatext[lang_code])<? //} else {
																																														//echo $next_url . "?enc=" . ParamEnCoding("v_wvcs_seq=" . $next_v_wvcs_seq . ($param ? "&" : "") . $param);
																																													//} ?>" class="btn" id='btnNext'><?//= $_LANG_TEXT["btnnext"][$lang_code]; ?><a>
			</div> -->
			<div class="right">
				<? //if ($_ck_user_level == "SECURITOR_S") { ?>
					<!-- <a href="./result_list.php?enc=<?= ParamEnCoding("asset_type=" . $asset_type) ?>" class="btn"><?= $_LANG_TEXT['btnlisttext'][$lang_code] ?></a> -->
				<? //} else {

					// if ($in_date == "") {
					// 	$btnintext =  $_LANG_TEXT['btnin'][$lang_code];
					// } else {
					// 	$btnintext =  $_LANG_TEXT['btnincancel'][$lang_code];
					// }

					// if ($out_date == "") {
					// 	$btnouttext =  $_LANG_TEXT['btnout'][$lang_code];
					// } else {
					// 	$btnouttext =  $_LANG_TEXT['btnoutcancel'][$lang_code];
					// }
				?>
					<!-- <a href="#" id='btnApprvIn' onClick="return ResultCheckInSubmit()" class="btn2 <? echo $_CODE_CSS['display_inout_info']; ?>"><?= $btnintext ?></a>
					<a href="#" id='btnApprvOut' onClick="return ResultCheckOutSubmit()" class="btn2 <? echo $_CODE_CSS['display_inout_info']; ?>"><?= $btnouttext ?></a>
					<a href="#" onClick="popVcsScanResultPrint(<?= $v_wvcs_seq ?>)" class="btn gray"><?= $_LANG_TEXT['btnscanresultprint'][$lang_code] ?></a> -->
					<a href="./result_list.php?enc=<?= ParamEnCoding("asset_type=" . $asset_type) ?>" class="btn"><?= $_LANG_TEXT['btnlisttext'][$lang_code] ?></a>
					<a href="#" onClick="return ResultSubmit('UPDATE')" class="btn"><?= $_LANG_TEXT['btnsavetext'][$lang_code] ?></a>
					<a href="#" onClick="return ResultSubmit('DELETE')" class="btn"><?= $_LANG_TEXT['btndeletetext'][$lang_code] ?></a>
				<? //} ?>
			</div>
		</div>

		<div style='margin-top:30px;'>
			<ul class="tab">
				<? //�Ǽ��ڵ�
				// if ($_POLICY['vaccine_check_yn'] == "Y") {

				// 	$qry_params = array("v_wvcs_seq" => $v_wvcs_seq);
				// 	$qry_label = QRY_RESULT_VIRUS_COUNT;
				// 	$sql = query($qry_label, $qry_params);

				// 	$result = sqlsrv_query($wvcs_dbcon, $sql);
				// 	$row = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
				// 	$virus_cnt = $row['virus_cnt'];

				//test:
				// $v_wvcs_seq ='test'
				?>
					<li class='on' >
						<a href="#" onclick="CheckLoadVaccineInfo(<?= $v_wvcs_seq ?>);"><?= $_LANG_TEXT['virustext'][$lang_code] ?>(<?= $virus_cnt ?>)</a>
						<div id='vaccine_info'>
							
							<table class="view"></table>
						</div>
					</li>
				<? //} ?>

				<? //�������ǽ�
				if ($_POLICY['file_scan_yn'] == "Y") {

					$args = array("v_wvcs_seq" => $v_wvcs_seq);
					$file_bad_ext_count = $Model_result->getUserVCSBadFileCount($args);
					
					// var_dump($args);				
					//  echo  $file_bad_ext_count;

					// 					$Model_result->SHOW_DEBUG_SQL = true;
					// $result = $Model_result->getUserVCSBadFileCount($args);

				?>
					<li>
						<a href="#" onclick="CheckLoadBadFileInfo(<?= $v_wvcs_seq ?>);"><? echo trsLang('�������ǽ�', 'suspectforgerytext'); ?>(<?= $file_bad_ext_count ?>)</a>
						<div id='file_bad_list'>
							<table class="view">
								<tr>
									<td></td>
								</tr>
							</table>
						</div>
					</li>
				<? } ?>
				<? //�˻�����
				if ($_POLICY['checkin_file_send_type'] != "N") {

					$search_sql = " and v1.v_wvcs_seq = '{$v_wvcs_seq}' ";

					$args = array("search_sql" => $search_sql);
					$file_import_count = $Model_result->getVCSFileImportListCount($args);
	



					
					$args = array("search_sql" => $search_sql);
					$file_scan_count = $Model_result->getVCSScanListCount($args);



				?>
					<li>
						<a href="#" onclick="CheckLoadImportFileInfo(<?= $v_wvcs_seq ?>);"><? echo trsLang('��������', 'importfiletext'); ?>(<?= number_format($file_import_count) ?>)</a>
						<div id='file_import_list'>
							<table class="view">
								<tr>
									<td></td>
								</tr>
							</table>
						
						</div>
					</li>
					<li>
						<a href="#" onclick="CheckLoadScanFileInfo(<?= $v_wvcs_seq ?>);"><? echo trsLang('�˻�����', 'scanfiletext'); ?>(<?= number_format($file_scan_count) ?>)</a>
						<div id='file_scan_list'>

							<table class="view">
								<tr>
									<td></td>
								</tr>
							</table>
						</div>
					</li>
				<? } ?>
			</ul>
		</div>

		<!-- <div class="sub_tit"> > <?//= $v_user_name ?> <?= $_LANG_TEXT['checklisttext'][$lang_code] ?></div>
		<div id='user_check_list'></div> -->
	</div>

</div>
<div id='popContent' style='display:none'></div>
<?php
if ($result) sqlsrv_free_stmt($result);
sqlsrv_close($wvcs_dbcon);

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>