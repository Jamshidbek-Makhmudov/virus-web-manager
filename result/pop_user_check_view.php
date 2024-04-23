<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$v_wvcs_seq = $_REQUEST[v_wvcs_seq];
$src = $_REQUEST[src];

$search_sql = " AND vcs.v_wvcs_seq= '$v_wvcs_seq' ";

if($orderby != "") {
	$order_sql = " ORDER BY $orderby";
} else {
	$order_sql = " ORDER BY vcs.v_wvcs_seq DESC ";
}

$qry_params = array("search_sql"=>$search_sql,"order_sql"=>$order_sql,"v_wvcs_seq"=>$v_wvcs_seq);
$qry_label = QRY_RESULT_PC_CHECK_INFO;
$sql = query($qry_label,$qry_params);


$result = @sqlsrv_query($wvcs_dbcon, $sql,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
$row_count = @sqlsrv_num_rows( $result );  
$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

if($row){
	
	$v_user_seq = $row['v_user_seq'];
	$check_date = $row['check_date'];
	$in_available_date = $row['checkin_available_dt'];
	if($in_available_date){
		
		$hour = substr($in_available_date,8,2);
		$min = substr($in_available_date,10,2);

		$in_available_date = substr($in_available_date,0,4)."-".substr($in_available_date,4,2)."-".substr($in_available_date,6,2);
		
		$in_available_date = $in_available_date." ".($hour? $hour : "00").":".($min? $min : "00");
	}
	$in_date = substr($row['wvcs_authorize_dt'],0,16);
	$out_date = substr($row['checkout_dt'],0,16);
	$device_gubun = $row['v_asset_type'];
	$os_ver_name = $row['os_ver_name'];
	$model_name = $row['v_model_name'];
	$manufacturer = $row['v_manufacturer'];
	$ip_addr = $row['ip_addr'];
	$mac_addr = $row['mac_addr'];
	$v_user_name = aes_256_dec($row['v_user_name']);
	$v_com_name = $row['v_com_name'];
	$mngr_name = aes_256_dec($row['mngr_name']);
	// $mngr_name = $row['mngr_name'];
	$mngr_dept = $row['mngr_department'];
	$scan_center_code = $row['scan_center_code'];
	$v_user_belong = $row['v_user_belong'];
	$scan_center_name = $row['scan_center_name'];

	$raw_v_user_name = $v_user_name;

	
	if($_encryption_kind=="1"){

		$phone_no = $row['v_phone_decript'];
		$email = $row['v_email_decript'];

	}else if($_encryption_kind=="2"){

		$phone_no = aes_256_dec($row['v_phone']);
		$email = aes_256_dec($row['v_email']);
	}

	if($_cfg_user_identity_name=="phone"){
		$v_user_name_com = $phone_no;
		$v_user_name= $phone_no;
	}else if($_cfg_user_identity_name=="email"){
		$v_user_name_com =$email;
		$v_user_name= $email;
	}else{
		if($v_com_name=="-") $v_com_name="";
		$v_user_name_com = $v_user_name.($v_com_name? "/" :"").$v_com_name;
	}

	$check_type = $row['wvcs_type'];
	$org_name = $row['org_name'];
	
	$apprv_yn = $row['wvcs_authorize_yn'];
	
	$apprv_dt = ($apprv_yn=="Y") ? $row['wvcs_authorize_dt'] : "";
	$apprv_name = aes_256_dec($row['wvcs_authorize_name']);

	$memo = $row['memo_text'];
	if ($row['make_winpe']=="1") {

				$make_winpe = trsLang('제작','produce_text');
		} else {
				$make_winpe = "";
		}

	$vcs_status = $row['vcs_status'];
	$str_vcs_status = $_CODE['vcs_status'][$vcs_status];

	$last_check_date = $row['last_check_date'];
	$rnum = $row['rnum'];

	$disk_cnt = $row['disk_cnt'];

	$file_send_status = $row['file_send_status'];
	$file_delete_flag = $row['file_delete_flag'];
}

	//�˻���å��������
	$_POLICY= getPolicy('checkin_file_send_type,vaccine_check_yn,file_scan_yn');

	if($_POLICY['checkin_file_send_type']=="") $_POLICY['checkin_file_send_type']= "N";	//�������ۿ���
	if($_POLICY['vaccine_check_yn']=="") $_POLICY['vaccine_check_yn'] = "Y";	//��Ű˻翩��
	if($_POLICY['file_scan_yn']=="") $_POLICY['file_scan_yn'] = "N";	//���ϰ˻翩��

	if(!is_instance($Model_result)){
		$Model_result = new Model_result();
	}	
	//$Model_result->SHOW_DEBUG_SQL = true;

	//**화면열람로그 기록
	$page_title = "[{$v_user_name}] ".$_LANG_TEXT["result_list_details"][$lang_code];
	$work_log_seq = WriteAdminActLog($page_title,'VIEW');
?>
<script language="javascript">
$("document").ready(function(){

	var device_gubun = "<?=$device_gubun?>";


	if(device_gubun=="NOTEBOOK"){

		LoadWeaknessInfo(<?=$v_wvcs_seq?>);	

	}else{

		<? if($_POLICY['vaccine_check_yn']=="Y"){	//�Ǽ��ڵ�
			echo "LoadVaccineInfo({$v_wvcs_seq});";
		}else if($_POLICY['file_scan_yn']=="Y"){		//������ �ǽ�
			echo "LoadBadFileInfo({$v_wvcs_seq});";
		}else if($_POLICY['checkin_file_send_type'] != "N"){	//������������
			echo "LoadImportFileInfo({$v_wvcs_seq});";
		}?>
	}
});
	$(function() {
		$( ".tab>li>a" ).click(function() {
			$(this).parent().addClass("on").siblings().removeClass("on");
			return false;
		});
	});

</script>
<div id="mark">
	<div class="content">
		<div class='tit'>
			<div class='txt'><span id='pop_page_title'><?=$page_title;?></span></div>
			<div class='right'>
				<div class='close' onClick="ClosepopContent();"></div>
			</div>
		</div>
		<div class='wrapper2'>
			<div style='min-width:940px;'>
				
				<?
				if(COMPANY_CODE=="600"){	//īī����ũ
					include "./pop_user_check_view_form_600.php";
				}else{
					include "./pop_user_check_view_form_0.php";
				}
				?>
				

<?
	//�����,���̷��� ����

	if($device_gubun=="NOTEBOOK"){

		$qry_params = array("v_wvcs_seq"=>$v_wvcs_seq);
		$qry_label = QRY_RESULT_WEAKNESS_COUNT;
		$sql = query($qry_label,$qry_params);

		
		$result = sqlsrv_query($wvcs_dbcon, $sql);
		$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		$weakness_cnt = $row['weakness_cnt'];

	}

	$qry_params = array("v_wvcs_seq"=>$v_wvcs_seq);
	$qry_label = QRY_RESULT_VIRUS_COUNT;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);
	$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
	$virus_cnt = $row['virus_cnt'];
?>
				<div style='margin-top:10px;'>
					<ul class="tab">

					<?if($device_gubun=="NOTEBOOK"){?>
						<li class="on">
							<a href="javascript:void(0)" onclick="LoadWeaknessInfo(<?=$v_wvcs_seq?>);"><?=$_LANG_TEXT['weaknesstext'][$lang_code]?>(<?=$weakness_cnt?>)</a>
							<div id='weakness_info'></div>
						</li>
						<li>
							<a href="javascript:void(0)" onclick="LoadVaccineInfo(<?=$v_wvcs_seq?>);"><?=$_LANG_TEXT['virustext'][$lang_code]?>(<?=$virus_cnt?>)</a>
							<div id='vaccine_info'></div>
						</li>
						<li>
							<a href="javascript:void(0)" onclick="LoadWindowUpdateInfo(<?=$v_wvcs_seq?>);"><?=$_LANG_TEXT['windowupdatetext'][$lang_code]?></a>
							<div id='window_update_info'></div>
						</li>
						
					<?}else{?>

						<li class="on">
							<a href="javascript:void(0)" onclick="LoadVaccineInfo(<?=$v_wvcs_seq?>);"><?=$_LANG_TEXT['virustext'][$lang_code]?>(<?=$virus_cnt?>)</a>
							<div id='vaccine_info'></div>
						</li>
						
						<? //�������ǽ�
						if($_POLICY['file_scan_yn']=="Y"){
							
							$search_sql = " and v1.v_wvcs_seq = '{$v_wvcs_seq}' ";
							$args = array("search_sql" => $search_sql);
							$file_bad_ext_count = $Model_result->getUserVCSBadFileListCount($args);
						?>
						<li>
							<a href="#" onclick="LoadBadFileInfo(<?=$v_wvcs_seq?>);"><? echo trsLang('�������ǽ�','suspectforgerytext');?>(<?=$file_bad_ext_count?>)</a>
							<div id='file_bad_list'><table class="view"><tr><td></td></tr></table></div>
						</li>
						<?}?>

						<? //�˻�����
						if($_POLICY['checkin_file_send_type'] != "N"){

							$search_sql = " and v1.v_wvcs_seq = '{$v_wvcs_seq}' ";
			
							$args = array("search_sql"=>$search_sql);
							$file_import_count = $Model_result->getVCSFileImportListCount($args);
							
							$args = array("search_sql"=>$search_sql);
							$file_scan_count = $Model_result->getVCSScanListCount($args);

							$search_sql = " and t1.v_wvcs_seq = '{$v_wvcs_seq}' ";
							$args = array("search_sql"=>$search_sql);
							$file_apply_count=$Model_result->getFileInApplyFileListCount($args);

						?>
						<li>
							<a href="javscript:void(0)" onclick="LoadImportFileInfo(<?=$v_wvcs_seq?>);"><? echo trsLang('��������','importfiletext');?>(<?=number_format($file_import_count)?>)</a>
							<div id='file_import_list'><table class="view"><tr><td class='text-center bg' >Loading..</td></tr></table></div>
						</li>
						<li>
							<a href="javscript:void(0)" onclick="LoadScanFileInfo(<?=$v_wvcs_seq?>);"><? echo trsLang('�˻�����','scanfiletext');?>(<?=number_format($file_scan_count)?>)</a>
							<div id='file_scan_list'><table class="view"><tr><td class='text-center bg' >Loading..</td></tr></table></div>
						</li>
						<li>
						<a href="javscript:void(0)" onclick="LoadFileApplyInfo(<?= $v_wvcs_seq ?>);"><? echo trsLang('반입예외신청', 'importapplytext'); ?>(<? echo number_format($file_apply_count);?>)</a>
						<div id='file_apply_list'><table class="view"><tr><td class='text-center bg' >Loading..</td></tr></table></div>
					</li>
						<?}?>


					<?}?>
					</ul>
				</div>
			</div>	
		</div>
	</div>
</div>
