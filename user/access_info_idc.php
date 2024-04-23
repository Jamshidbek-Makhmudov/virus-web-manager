<?php
$page_name = "access_control_idc";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI']) - 1);
$_apos = stripos($_REQUEST_URI, "/");
if ($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
include_once $_server_path . "/" . $_site_path . "/inc/header.inc";
include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";

$tab = $_REQUEST['tab'];
$v_user_list_seq = intVal($_REQUEST["v_user_list_seq"]);
$additional_cnt = $_REQUEST[additional_cnt];	// 검색옵션

$Model_User = new Model_User();

if ($v_user_list_seq <> "") {

	if ($orderby != "") {
		$order_sql = " ORDER BY $orderby";
	} else {
		$order_sql = " ORDER BY v2.v_user_list_seq DESC ";
	}
	$args = array("order_sql" => $order_sql, "v_user_list_seq" => $v_user_list_seq);
	$Model_User->SHOW_DEBUG_SQL = false;
	$result = $Model_User->getUserVistListDetailsInfo_IDC($args);

	$row = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);


	$v_user_seq = $row['v_user_seq'];
	$v_user_list_seq = $row['v_user_list_seq'];
	$v_user_name =  aes_256_dec($row['v_user_name']);
	$v_user_name_en = $row['v_user_name_en'];
	$v_phone = $row['v_phone'];
	$v_email = $row['v_email'];
	$v_company = $row['v_company'];
	$v_purpose = $row['v_purpose'];
	$manager_name =  aes_256_dec($row['manager_name']);
	$manager_name_en = $row['manager_name_en'];
	$manager_dept = $row['manager_dept'];
	$additional_cnt = $row['additional_cnt'];
	$memo = $row['memo'];
	$in_time = $row['in_time'];
	$out_time = $row['out_time'];
	$in_center_code = $row['in_center_code'];
	$in_center_name = $row['in_center_name'];
	$pass_card_no = $row['pass_card_no'];
	$label_name = $row['label_name'];
	$label_value = $row['label_value'];
	$elec_doc_number = $row['elec_doc_number'];
	$v_user_belong = $row['v_user_belong'];
	$policy_file_in_seq = $row['policy_file_in_seq'];		//파일 반입 예외 정책이 있는지
	$rnum = $row['rnum'];


	$pass_card_return_date = setDateFormat($row['pass_card_return_date'], 'Y-m-d H:i');
	$pass_card_return_schedule_date = setDateFormat($row['pass_card_return_schedule_date'], 'Y-m-d');
	$emp_name =  aes_256_dec($row['emp_name']);

	$usb_return_date = setDateFormat($row['usb_return_date'], 'Y-m-d H:i');
	$usb_return_schedule_date = setDateFormat($row['usb_return_schedule_date'], 'Y-m-d');
	$usb_return_emp_name =  aes_256_dec($row['usb_return_emp_name']);

	$v_user_type = $row['v_user_type'];
	$str_v_user_type = $_CODE_V_USER_TYPE_DETAILS[$v_user_type];

	// $str_memo = $memo;

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


	if ($v_user_type == "EMP") {
		$phone_no = "";
	}

	$visit_status = strVal($row[visit_status]);
	$visit_center_desc = $row[visit_center_desc];
	$visit_date = setDateFormat($row[visit_date]);
	$work_number = $row[work_number];

	$in_access_emp_name = $row[in_access_emp_name];
	$in_access_emp_id = $row[in_access_emp_id];
	$out_access_emp_name = $row[out_access_emp_name];
	$out_access_emp_id = $row[out_access_emp_id];

	$security_agree_yn = $row['security_agree_yn'];
	$str_security_agree_yn = $security_agree_yn == "Y" ? "<a href='javascript:void(0)' onclick='popSecurityAgree()' data-seq='{$v_user_list_seq}' class='text_link'>" . trsLang('작성완료', 'writeoktext') . "</a>" : trsLang('미작성', 'notwritten');
	if ($security_agree_yn == "Y") {
		$security_agree_date = setDateFormat($row['security_agree_date']);
	}


	if ($visit_status == "1") {
		$in_time_vl = setDateFormat($in_time, "Y-m-d H:i");
		$out_time_vl = "";
	} else if ($visit_status == "0") {
		$in_time_vl = setDateFormat($in_time, "Y-m-d H:i");
		$out_time_vl = setDateFormat($out_time, "Y-m-d H:i");
	}

	{
		$args = compact("v_user_list_seq");
		$report = $Model_User->getUserVisitListReportSeq_IDC($args);
		@extract($report); // vsr_doc_seq, mgr_doc_seq

		$str_no_written = trsLang('미작성', 'notwritten');
		$str_write_ok = trsLang('작성완료', 'writeoktext');
		
		$str_vsr_doc = empty($vsr_doc_seq) ? $str_no_written : "<a href='javascript:void(0)' onclick='popUserIdcReport()' onclick='' data-seq='{$v_user_list_seq}' data-doc-seq='{$vsr_doc_seq}' class='text_link'>{$str_write_ok}</a>";
		$str_mgr_doc = empty($mgr_doc_seq) ? $str_no_written : "<a href='javascript:void(0)' onclick='popUserIdcReport()' onclick='' data-seq='{$v_user_list_seq}' data-doc-seq='{$mgr_doc_seq}' class='text_link'>{$str_write_ok}</a>";
	}
}


if ($v_user_name_en != "") {
	$user_name_kr_en = $v_user_name . "(" . $v_user_name_en . ")";
} else {
	$user_name_kr_en = $v_user_name;
}

$param_enc = ParamEnCoding("src=VISIT_INFO_VIEW&tab=" . $tab . "&v_user_seq=" . $v_user_seq . "&v_user_list_seq=" . $v_user_list_seq);

//**화면열람로그 기록
$page_title = "[{$v_user_name}] " . $_LANG_TEXT["m_visitor_info"][$lang_code];
$work_log_seq = WriteAdminActLog($page_title, 'VIEW');
?>
<script language="javascript">
	$(function() {
		var param_enc = "enc=<?= $param_enc ?>";
		LoadPageDataList('user_check_list', SITE_NAME + '/result/get_user_check_list.php', param_enc);
		LoadPageDataList('user_file_apply_list', SITE_NAME + '/result/get_user_file_apply_list.php', param_enc);
	});
</script>
<div id="result_view">
	<div class="container">
		<div id="tit_area">
			<div class="tit_line">
				<h1><span id='page_title'><? echo $page_title; ?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		<div class="page_right">
			<? $tab_url = $tab == "" ? "access_control_idc.php" : $tab . ".php"; ?>
			<span style='cursor:pointer' onclick="location.href='<? echo $_www_server ?>/user/<? echo $tab_url ?>'">
				<?= $_LANG_TEXT["btngobeforepage"][$lang_code]; ?>
			</span>
		</div>

		<ul class='tab'>
			<li id="" class="on ">
				<a href="javascript:void(0)" onclick="location.reload(true)">
					<?= $_LANG_TEXT['access_info_theme'][$lang_code] ?>
				</a>
			</li>
		</ul>
		<div>
			<div>
				<form name='frmMemo' id='frmMemo' method='POST'>
					<input type='hidden' id='v_user_list_seq' name='v_user_list_seq' value='<?= $v_user_list_seq ?>'>
					<input type='hidden' name='proc_name'>
					<input type='hidden' id='proc' name='proc'>
					<table class="view">
						<tr>
							<th style='width:180px'>
								<? echo trsLang('소속구분', 'belongdivtext'); ?>
							</th>
							<td>
								<select name="v_user_type" id="v_user_type" style='width:122px;'>
									<?
									foreach($_CODE_V_USER_TYPE_DETAILS as $key => $value) {
										if ($v_user_type == "OUT") {
											if ($key != "OUT") {
												continue;
											}
										} else {
											if (($key == "OUT") || ($key == "EMP")) {
												continue;
											}
										}
										$selected = ($v_user_type == $key) ? "selected" : "";

										echo "<option value='{$key}' {$selected} >{$value}</option>";
									}
									?>
								</select>
							</td>
							<th class="line" style='width:180px'>
								<?= $_LANG_TEXT['belongtext'][$lang_code] ?>
							</th>
							<td>
								<input type="text" name="v_user_belong" id="v_user_belong" class="frm_input required_auth" value="<?php echo $v_user_belong; ?>" style="width:250px" maxlength="50">
							</td>
						</tr>
						<tr>
							<th style='width:150px'>
								<?= $_LANG_TEXT['nametext'][$lang_code] ?>
							</th>
							<td style='width:500px;display:inline- block; vertical-align: middle;'>

								<input type="text" name="v_user_name" id="v_user_name" class="frm_input required_auth" value="<?php echo $v_user_name; ?>" style="width:100px;" maxlength="50">
								<input type="text" name="v_user_name_en" id="v_user_name_en" class="frm_input required_auth" value="<?php echo $v_user_name_en; ?>" style="width:200px; margin:0 10px;" maxlength="50" placeholder="<? echo trsLang('영문이름', 'engnameid'); ?>">
							</td>
							<th class="line">
								<?= $_LANG_TEXT['contactphonetext'][$lang_code] ?>
							</th>
							<td style='max-width:150px; display:inline- block; vertical-align: middle;'>
								<input type="text" name="v_phone" id="v_phone" class="frm_input required_auth" value="<?php echo replaceHiddenChar($phone_no, 0, 3); ?>" style="width:250px" maxlength="400">
								<a href="javascript:void(0)" onclick="showHiddenInfo('v_phone','<? echo $v_phone ?>')"><i class="fa fa-eye-slash"></i></a>
							</td>
						</tr>
						<tr>
							<th>
								<?= trsLang('검사장', 'inspection_center'); ?>
							</th>
							<td> <? echo $in_center_name; ?>
								<!--
									<select name='in_center_name' id='in_center_name'  style='width:350px' class='required_auth'>
										<option value=''><?= $_LANG_TEXT["alltext"][$lang_code]; ?></option>
										<?php
										$qry_params = array();
										$qry_label = QRY_COMMON_SCAN_CENTER_USE_ALL;
										$sql = query($qry_label, $qry_params);

										$result = sqlsrv_query($wvcs_dbcon, $sql);

										if ($result) {
											while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
												$_scan_center_code = $row['scan_center_code'];
												$_scan_center_name = $row['scan_center_name'];
										?>
										<option value='<?= $_scan_center_code ?>' <? if ($_scan_center_code == $in_center_code) echo "selected"; ?>
											><?= $_scan_center_name ?></option>
										<?php
											}
										}
										?>
									</select>
									-->
							</td>
							<th class="line"><?= $_LANG_TEXT['center_location'][$lang_code] ?></th>
							<td>
								<?
								echo $visit_center_desc;
								/*
									$Model_manage = new Model_manage;
									$args = array("search_sql"=>" and code_key='IDC_CENTER' and use_yn='Y' and depth > 1 ");
									$result = $Model_manage->getCodeList($args);

									if($result){
										$idx = 0;
										while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
											$_code_name = $row['code_name'];

											$checked = in_array($_code_name,$visit_center_desc) ? "checked" : "";
									?>
									<input type='checkbox' name='idc_center[]' id='idc_center<? echo $idx;?>' value='<? echo $_code_name?>' <? echo $checked;?>> <label for='idc_center<? echo $idx?>'><? echo $_code_name?></label>
									<?php
											$idx++;
										}
									}
									*/
								?>
							</td>
						</tr>
						<tr>
							<th>
								<?= $_LANG_TEXT['purpose_visit'][$lang_code] ?>
							</th>
							<td><? echo $v_purpose; ?>
								<!--<input type="text" name="v_purpose" id="v_purpose" class="frm_input required_auth" value="<?php echo $v_purpose; ?>"
									style="width:95%" maxlength="100">-->
							</td>
							<th class='line'><? echo trsLang('정보보호서약서', 'informationprotectionpledge'); ?></th>
							<td>
								<? echo $str_security_agree_yn ?>
							</td>
						</tr>
						<tr>
							<th><?= $_LANG_TEXT['visitdatetext'][$lang_code] ?></th>
							<td><? echo $visit_date ?>
								<!--<input type="text" name="visit_date" id="visit_date" class="frm_input  required_auth datepicker"
									value="<?php echo $visit_date; ?>" style="width:100px;" maxlength="10">-->
							</td>
							<th class='line'><? echo trsLang('상태', 'statustext'); ?></th>
							<td><? echo $_CODE_VISIT_STATUS[$visit_status] ?></td>
						</tr>
						<tr>
							<th><? echo trsLang('유지보수결과서', 'idcvisitorreporttext'); ?></th>
							<td><? echo $str_vsr_doc; ?></td>
							<th class='line'><? echo trsLang('외부출입지원 체크리스트', 'idcmanagerreporttext'); ?></th>
							<td><? echo $str_mgr_doc; ?></td>
						</tr>
						<tr>
							<th><? echo trsLang('입실시간', 'inofficetimetext') ?></th>
							<td>
								<!--<input type="text" name="in_time" id="in_time" class="frm_input  required_auth readonly"
									value="<?php echo $in_time_vl; ?>" style="width:120px;" maxlength="12" readonly>-->
								<? if ($visit_status == "1" || $visit_status == "0") {
									echo  $in_time_vl . ", " . trsLang("입실확인자", "inofficeconfirmertext") . " : " . $in_access_emp_name . "(" . $in_access_emp_id . ")";
								} ?>
							</td>
							<th class='line'><? echo trsLang('퇴실시간', 'outofficetimetext') ?></th>
							<td>
								<!--<input type="text" name="out_time" id="out_time" class="frm_input  required_auth readonly"
									value="<?php echo $out_time_vl; ?>" style="width:120px;" maxlength="12" readonly>-->
								<? if ($visit_status == "0") {
									echo  $out_time_vl . ", " . trsLang("퇴실확인자", "outofficeconfirmertext") . " : " . $out_access_emp_name . "(" . $out_access_emp_id . ")";
								} ?>
							</td>
						</tr>
						<tr>
							<th>
								<?= $_LANG_TEXT['worknumbertext'][$lang_code] ?>
							</th>
							<td>
								<input type="text" name="elec_doc_number" id="elec_doc_number" class="frm_input required_auth" value="<?php echo $elec_doc_number; ?>" style="width:325px" maxlength="30">
							</td>
							<th>
								<?= $_LANG_TEXT['worknumbertext'][$lang_code] ?>(<?= $_LANG_TEXT['certify_text'][$lang_code] ?>)
							</th>
							<td>
								<input type="text" name="work_number" id="work_number" class="frm_input required_auth" value="<?php echo $work_number; ?>" style="width:90%" maxlength="100">
							</td>
						</tr>
						<tr>
							<th>
								<?= $_LANG_TEXT['note'][$lang_code] ?>
							</th>
							<td colspan="3">
								<input type='text' id='memo' name='memo' class='frm_input required_auth' value='<?= $memo ?>' style='width:95%' maxlength="100">
							</td>
						</tr>
					</table>
				</form>
			</div>

			<? if ($v_user_list_seq > 0) { ?>
				<div class="btn_wrap right" style='margin-bottom:10px;'>
					<?php if ($visit_status == "1") { ?>
						<!-- 입실처리취소 와 퇴실처리 버튼 -->
						<a href='javascript:void(0)' class='btn required-update-auth hide' onclick='cancelVisitIn()' data-seq='<?= $v_user_list_seq ?>' 
						title='<?= trsLang('입실처리취소', 'inofficeaccesscanceltext') ?>'>
							<?= trsLang('입실처리취소', 'inofficeaccesscanceltext') ?></a>

						<a href='javascript:void(0)' class='btn required-update-auth hide' onclick='procVisitOut()' data-seq='<?= $v_user_list_seq ?>' 
						title='<?= trsLang('퇴실처리', 'outofficeaccesstext') ?>'>
							<?= trsLang('퇴실처리', 'outofficeaccesstext') ?></a>

					<?php } else if ($visit_status == "0") { ?>
						<!-- 퇴실처리취소 버튼 -->
						<a href='javascript:void(0)' class='btn required-update-auth hide' onclick='cancelVisitOut()' data-seq='<?= $v_user_list_seq ?>' 
						title='<?= trsLang('퇴실처리취소', 'outofficeaccesscanceltext') ?>'>
							<?= trsLang('퇴실처리취소', 'outofficeaccesscanceltext') ?></a>
					<?php } else { ?>
						<!-- 입실처리 버튼 -->
						<a href='javascript:void(0)' class='btn required-update-auth hide' onclick='procVisitIn()' data-seq='<?= $v_user_list_seq ?>' 
						title='<?= trsLang('입실처리', 'inofficeaccesstext') ?>'>
							<?= trsLang('입실처리', 'inofficeaccesstext') ?></a>
					<?php } ?>
					
					<?php if ($visit_status != "9") { ?>
						<!-- 유지보수결과서 버튼 -->
						<?php if ($vsr_doc_seq) { ?>
						<?php 
							$params = ParamEnCoding("v_user_list_seq={$v_user_list_seq}&user_doc_seq={$vsr_doc_seq}&tab={$tab}");
						?>
						<a href='javascript:void(0)' class='btn required-update-auth hide' style="width:140px;" onclick="sendPostForm('./access_info_idc_visit_report.php?enc=<?= $params; ?>')" title='<?= trsLang('유지보수결과서수정', 'modifyidcvisitreport') ?>'><?= trsLang('유지보수결과서수정', 'modifyidcvisitreport') ?></a>
						<?php } ?>

						<!-- 체크리스트 버튼 -->
						<?php 
							$params = ParamEnCoding("v_user_list_seq={$v_user_list_seq}&user_doc_seq={$mgr_doc_seq}&tab={$tab}");
							$text = empty($mgr_doc_seq) ? trsLang('체크리스트작성', 'writeidcmanagerreport') : trsLang('체크리스트수정', 'modifyidcmanagerreport');
						?>
						<a href='javascript:void(0)' class='btn required-update-auth hide' onclick="sendPostForm('./access_info_idc_report.php?enc=<?= $params; ?>')" title='<?= $text ?>'><?= $text ?></a>
						
					<?php } ?>
					<!-- 저장하기 버튼 -->
					<a href="javascript:void(0)" onclick="VisitorRegProcess_IDC()" class="btn required-update-auth hide">
						<?= $_LANG_TEXT['save_file'][$lang_code] ?>
					</a>
					<a href="javascript:void(0)" onclick="deleteVisitInfo()" class="btn required-delete-auth hide">
						<?= $_LANG_TEXT['btndeletetext'][$lang_code] ?>
					</a>
				</div>
			<? } ?>
			<BR><BR>

			<div>
				<!--사내 USB 지급정보-->
				<div class="<? if ($label_value == "")
											echo "display-none"; ?>">
					<div class="sub_tit"> >
						<?= trsLang('사내usb지급정보', 'usbofferinfotext') ?>
					</div>
					<form name='frmUsb' id='frmUsb' method='POST'>
						<input type='hidden' id='v_user_list_seq' name='v_user_list_seq' value='<?= $v_user_list_seq ?>'>
						<input type='hidden' id='proc' name='proc'>
						<input type='hidden' name='proc_name'>

						<table class="view" style=" margin-top:10px">
							<tr>
								<th style='min-width:150px'>USB
									<?= trsLang('관리번호', 'managenumber'); ?>
								</th>
								<td style='width:500px'>
									<input type="text" name="label_value" id="label_value" class="frm_input required_auth" value="<?php echo $label_value; ?>" style="width:350px;" maxlength="30">

								</td>
								<th class='line' style='min-width:150px'>
									<?= trsLang('반납예정일', 'return_schedule_date_text') ?>
								</th>
								<td>

									<input type="text" name="usb_return_schedule_date" id="usb_return_schedule_date" class="frm_input datepicker required_auth" value="<?php echo $usb_return_schedule_date; ?>" style="width:100px;" maxlength="10">

								</td>
							</tr>
							<tr class='bg'>
								<th style='min-width:150px'>
									<?= trsLang('회수일자', 'collection_date') ?>
								</th>
								<td>
									<?= $usb_return_date ?>
								</td>
								<th class='line' style='min-width:150px'>
									<?= trsLang('처리자', 'accessusertext') ?>
								</th>
								<td>
									<? if ($usb_return_date != "") echo $usb_return_emp_name; ?>
								</td>
							</tr>
						</table>
					</form>

					<div class="btn_wrap right" style='margin-bottom:10px;'>

						<? if ($usb_return_date == "") { ?>
							<a href="javascript:void(0)" onclick="visitorUsbReturn('<? echo $v_user_list_seq; ?>')" class="btn required-update-auth hide" title='USB <? echo trsLang('회수처리', 'recoveryprocessing'); ?>'>
								<? echo trsLang('회수처리', 'recoveryprocessing'); ?>
							</a>
						<? } else { ?>
							<a href="javascript:void(0)" onclick="visitorUsbReturnCancel('<? echo $v_user_list_seq; ?>')" class="btn required-update-auth hide" title='USB <? echo trsLang('회수취소', 'unrecover_text'); ?>'>
								<? echo trsLang('회수취소', 'unrecover_text'); ?>
							</a>
						<? } ?>


						<a href="javascript:void(0)" onclick="usbInfoUpdate()" class="btn required-update-auth hide"><?= $_LANG_TEXT['save_file'][$lang_code] ?></a>
					</div><BR><BR>

				</div>

				<!--자산반입정보-->
				<?
				$args = array("v_user_list_seq" => $v_user_list_seq);
				$g_result = $Model_User->getImportInfo($args);
				?>
				<div class="<? if ($g_result == false) echo "display-none"; ?>">
					<div class="sub_tit"> >
						<?= trsLang('자산반입정보', 'assetimportinfo'); ?>
					</div>

					<table class="list" style=" margin-top:10px">
						<tr>
							<th class="num">
								<?= $_LANG_TEXT['numtext'][$lang_code] ?>
							</th>
							<th style='min-width:200px'>
								<?= $_LANG_TEXT['product_name'][$lang_code] ?>
							</th>
							<th style='min-width:100px'>
								<?= trsLang('관리번호', 'managenumber') ?>
							</th>
							<th style='min-width:100px'>
								<?= trsLang('전자문서번호', 'electronic_payment_document_number') ?>
							</th>
							<th style='min-width:200px'>
								<?= $_LANG_TEXT['model'][$lang_code] ?>
							</th>
							<th style='min-width:200px'>
								<?= $_LANG_TEXT['serial_number'][$lang_code] ?>
							</th>
							<th style='min-width:100px'>
								<?= $_LANG_TEXT['scheduled_text'][$lang_code] ?>
							</th>
							<th style='min-width:100px'>
								<?= $_LANG_TEXT['outdatetext'][$lang_code] ?>
							</th>
							<th style='min-width:100px'>
								<?= $_LANG_TEXT['accessusertext'][$lang_code] ?>
							</th>
							<th style='min-width:100px'>
								<?= $_LANG_TEXT['btnout'][$lang_code] ?>
							</th>
							<th style='min-width:60px'>
								<?= trsLang('메모', 'memotext'); ?>
							</th>
							<th style='min-width:100px'>
								<?= trsLang('수정', 'btnupdate'); ?>
							</th>

						</tr>
						<?php
						if ($g_result) {
							$no = 1;
							while ($row = @sqlsrv_fetch_array($g_result, SQLSRV_FETCH_ASSOC)) {

								$v_user_list_goods_seq = $row['v_user_list_goods_seq'];
								$out_emp_seq = $row['out_emp_seq']; //반출처리 직원
								$out_schedule_date = setDateFormat($row['out_schedule_date'], 'Y-m-d');
								$out_date = setDateFormat($row['out_date'], 'Y-m-d H:i'); //반출일
								$in_date = setDateFormat($row['in_date'], 'Y-m-d H:i');
								$inout_status = $row['inout_status'];
								$item_mgt_number = $row['item_mgt_number'];
								$elec_doc_number = $row['elec_doc_number'];
								$serial_number = $row['serial_number'];
								$model_name = $row['model_name'];
								$goods_name = $row['goods_name'];
								$goods_kind = $row['goods_kind'];
								$v_user_list_seq = $row['v_user_list_seq'];
								$v_user_list_goods_seq = $row['v_user_list_goods_seq'];
								$emp_name =  aes_256_dec($row['emp_name']);
								$memo = $row['memo'];

						?>
								<tr>
									<td>
										<?= $no ?>
										<span class='display-none' data-name='v_user_list_goods_seq'><? echo $v_user_list_goods_seq; ?></span>
									</td>
									<td>
										<span data-name='g_name'><?= $goods_name ?></span>
									</td>
									<td>
										<span data-name='g_mgt_no'><?= $item_mgt_number ?></span>
									</td>
									<td>
										<span data-name='g_doc_no'><?= $elec_doc_number ?></span>
									</td>
									<td>
										<span data-name='g_model'><?= $model_name ?></span>
									</td>
									<td>
										<span data-name='g_sn'><?= $serial_number ?></span>
									</td>
									<td>
										<span data-name='g_out_schedule_date'><?= $out_schedule_date ?></span>
									</td>
									<td>
										<?= $out_date ?>
									</td>
									<td>
										<? if ($out_date != "") echo $emp_name; ?>
									</td>
									<td>
										<? if ($out_date == "") { ?>
											<span class='text_link required-update-auth hide' onclick="takeOutProc('<? echo $v_user_list_goods_seq; ?>')" title='<? echo trsLang('자산반출처리', 'assetoutaccesstext'); ?>'>
												<? echo trsLang('반출처리', 'outaccesstext'); ?>
											</span>
										<? } else { ?>
											<span class='text_link required-update-auth hide' onclick="canceltakeOutProc('<? echo $v_user_list_goods_seq; ?>')" title='<? echo trsLang('자산반출취소', 'assetoutcancelaccesstext'); ?>'>
												<? echo trsLang('반출취소', 'btnoutcancel'); ?>
											</span>
										<? } ?>

									</td>
									<td>
										<span class='text_link' onmouseover="viewlayer(true, 'moverlayerLock_<? echo $no ?>');" onmouseout="viewlayer(false, 'moverlayerLock_<? echo $no ?>');"><? echo $memo == "" ? "" : "<i class='fa fa-comments'></i>" ?></span>
										<? if ($memo > "") { ?>
											<div id="moverlayerLock_<? echo $no ?>" class="viewlayer" style="display: none;"><span style='color:#fff' data-name='g_memo'><? echo $memo; ?></span></div>
										<? } ?>
									</td>
									<td>
										<a href="javascript:void(0)" class='text_link required-update-auth hide' onclick="popImportGoodsInfo()"><?= trsLang('수정', 'btnupdate'); ?></a>
									</td>
								</tr>
						<?php
								$no++;
							}
						}
						?>

					</table>
				</div>

				<!-- 2 파일반입 점검결과-->
				<?
				//파일반입 탭은 점검결과 메뉴 권한이 있어야 볼수 있다.
				if (in_array("R1000", $_ck_user_mauth)) { ?>
					<div class="<? if ($label_value == "")
												echo "display-none"; ?>">
						<div class="sub_tit"> >
							<?= $_LANG_TEXT['import_data_inspection_results'][$lang_code] ?>
						</div>
						<div id='user_check_list'></div>
					</div>
				<? } ?>


				<!-- 3 파일 반입 예외 신청 내역-->
				<?
				//파일반입 탭은 점검결과 메뉴 권한이 있어야 볼수 있다.
				if (in_array("R1000", $_ck_user_mauth)) { ?>
					<div class="<? if ($policy_file_in_seq == "") echo "display-none"; ?>">
						<div class="sub_tit"> >
							<?= $_LANG_TEXT['fileimportapplylist'][$lang_code] ?>
						</div>
						<div id='user_file_apply_list'></div>
					</div>
				<? } ?>

			</div>



		</div>

	</div>
</div>
<!--자산반입 정보 수정 모달팝업-->
<div id="modal_goods" class="modal" style='display:none'>
	<div class="modal-content" style='height:350px'>
		<div class="" style="display:flex; align-items: center; justify-content:space-between; width:100%">
			<strong class="modal-title" id='pop_page_title'><? echo trsLang('자산반입정보', 'assetimportinfo'); ?></strong>
			<span class="close">&times;</span>
		</div>
		<form id="frmGoods" name="frmGoods" method="POST">
			<input type='hidden' name='v_user_list_goods_seq' id='v_user_list_goods_seq'>
			<input type='hidden' name='proc_name'>
			<input type='hidden' name='proc'>
			<div class="form-group">
				<table class='view'>
					<tr>
						<th style='text-align:left'><label for='g_name'><? echo trsLang('물품명', 'product_name'); ?></label></th>
						<td><input style="width:90%" class="frm_input required_auth" type="text" id="g_name" name="g_name" maxlength="50"></td>
					</tr>
					<tr>
						<th style='text-align:left'><label for='g_mgt_no'><? echo trsLang('관리번호', 'managenumber'); ?></label></th>
						<td><input style="width:90%" class="frm_input required_auth" type="text" id="g_mgt_no" name="g_mgt_no" maxlength="30"></td>
					</tr>
					<tr>
						<th style='text-align:left'><label for='g_doc_no'><? echo trsLang('전자문서번호', 'electronic_payment_document_number'); ?></label></th>
						<td><input style="width:90%" class="frm_input required_auth" type="text" id="g_doc_no" name="g_doc_no" maxlength="30"></td>
					</tr>
					<tr>
						<th style='text-align:left'><label for='g_model'>Model</label></th>
						<td><input style="width:90%" class="frm_input required_auth" type="text" id="g_model" name="g_model" maxlength="200"></td>
					</tr>
					<tr>
						<th style='text-align:left'><label for='g_sn'>Serial Number</label></th>
						<td><input style="width:90%" class="frm_input required_auth" type="text" id="g_sn" name="g_sn" maxlength="200"></td>
					</tr>
					<tr>
						<th style='text-align:left'><? echo trsLang('반출예정', 'scheduled_text'); ?></th>
						<td><input style="width:100px" class="frm_input datepicker required_auth" type="text" id="g_out_schedule_date" name="g_out_schedule_date" maxlength="10"></td>
					</tr>
					<tr>
						<th style='text-align:left'><label for='g_memo'><? echo trsLang('메모', 'memotext'); ?></label></th>
						<td><input style="width:90%" class="frm_input required_auth" type="text" id="g_memo" name="g_memo" maxlength="300"></td>
					</tr>
				</table>
				<div class="btn_wrap ">
					<a href="javascript:void(0)" class="btn required-update-auth hide" onclick="return submitFrmGoods()"><? echo trsLang('수정', 'btnupdate'); ?></a>
				</div>
			</div>
		</form>
	</div>

</div>
</div>


</div>
<!--검사결과 팝업-->
<div id='popContent' style='display:none'></div>
<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>