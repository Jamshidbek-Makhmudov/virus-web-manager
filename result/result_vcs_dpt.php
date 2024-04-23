<?php
$page_name = "result_list";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common2.inc";
include_once $_server_path . "/" . $_site_path . "/inc/header.inc";

$v_wvcs_seq =  $_REQUEST[vcs_seq];

//$v_wvcs_seq = 129;

if($v_wvcs_seq == "") {
	echo $_LANG_TEXT['wrongdatatranstext'][$lang_code];
	exit;
}

$qry_params = array("v_wvcs_seq"=>$v_wvcs_seq);
$qry_label = QRY_VCS_RESULT_INFO;
$sql = query($qry_label,$qry_params);

//echo nl2br($sql);
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
	$device_gubun = $row['v_asset_type'];
	$os_ver_name = $row['os_ver_name'];
	$model_name = $row['v_model_name'];
	$manufacturer = $row['v_manufacturer'];
	$ip_addr = $row['ip_addr'];
	$mac_addr = $row['mac_addr'];
	$v_user_name = aes_256_dec($row['v_user_name']);
	$v_com_name = $row['v_com_name'];
	$mngr_name = aes_256_dec($row['mngr_name']);
	$mngr_dept = $row['mngr_department'];
	$scan_center_code = $row['scan_center_code'];
	$scan_center_name = $row['scan_center_name'];

	$v_notebook_key = $row['v_notebook_key'];
	$v_asset_type = $row['v_asset_type'];

	$v_user_name_com = $v_user_name.($v_com_name? "/" :"").$v_com_name;

	$check_type = $row['wvcs_type'];
	$org_name = $row['org_name'];
	
	$apprv_yn = $row['wvcs_authorize_yn'];
	
	$apprv_dt = ($apprv_yn=="Y") ? $row['wvcs_authorize_dt'] : "";
	$apprv_name = aes_256_dec($row['wvcs_authorize_name']);

	$memo = $row['memo_text'];

	if($wvcs_authorize_yn=="Y"){
		$vcs_status = $_CODE['vcs_status']['SUCCESS'];
	}else{
		$vcs_status = $_CODE['vcs_status']['CHECK'];
	}

	$last_check_date = $row['last_check_date'];


}

//echo nl2br($sql);


?>
<script language="javascript">
$("document").ready(function(){
	
	var device_gubun="<?=$device_gubun?>";
	
	if(device_gubun=="NOTEBOOK"){
		LoadWeaknessInfo(<?=$v_wvcs_seq?>);
	}else{
		LoadVaccineInfo(<?=$v_wvcs_seq?>);
	}

	
});
</script>
<div id="result_view">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_result"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>

		<div style="margin-top:50px">
			<ul class="tab">

				<li class="on">
					<a href="#" onclick=""><?=$_LANG_TEXT['checkinfotext'][$lang_code]?></a>
					<div>
						<form name='frmVCS' id='frmVCS' method='POST'>
						<input type='hidden' id='v_wvcs_seq' name='v_wvcs_seq' value='<?=$v_wvcs_seq?>'>
					<?if($device_gubun=="NOTEBOOK"){?>
						<!--노트북-->
						<table class="view">
						<tr>
							<th style='width:150px'><?=$_LANG_TEXT['checkdatetext'][$lang_code]?></th>
							<td style='width:300px'><?=$check_date?></td>
							<th class="line" style='width:150px'><?=$_LANG_TEXT['lastcheckdatetext'][$lang_code]?></th>
							<td ><?=$last_check_date?></td>
						</tr>
						<tr class="bg">
							<th><?=$_LANG_TEXT['devicegubuntext'][$lang_code]?></th>
							<td><?=$_CODE['asset_type'][$device_gubun]?></td>
							<th class="line"><?=$_LANG_TEXT['ostext'][$lang_code]?></th>
							<td><?=$os_ver_name?></td>
						</tr>
						<tr>
							<th><?=$_LANG_TEXT['modeltext'][$lang_code]?></th>
							<td><?=$model_name?></td>
							<th class="line"><?=$_LANG_TEXT['manufacturertext'][$lang_code]?></th>
							<td><?=$manufacturer?></td>
						</tr>
						<tr class="bg">
							<th><?=$_LANG_TEXT['ipaddresstext'][$lang_code]?></th>
							<td><?=$ip_addr?></td>
							<th class="line"><?=$_LANG_TEXT['macaddresstext'][$lang_code]?></th>
							<td><?=$mac_addr?></td>
						</tr>
						<tr>
							<th><?=$_LANG_TEXT['visitortext'][$lang_code]?></th>
							<td><?=$v_user_name_com?></td>
							<th class="line"><?=$_LANG_TEXT['checkgubuntext'][$lang_code]?></th>
							<td><?=$check_type?></td>
						</tr>
						<tr class="bg" >
							<th><?=$_LANG_TEXT['executives'][$lang_code]?> / <?=$_LANG_TEXT['depttext'][$lang_code]?></th>
							<td><?=$mngr_name?> / <?=$mngr_dept?></td>
							<th class="line"><?=$_LANG_TEXT['scancentertext'][$lang_code]?></th>
							<td><?=$org_name?> <?=$scan_center_name?> </td>
						</tr>
						<tr >
							<th><?=$_LANG_TEXT['inlimitdatetext'][$lang_code]?></th>
							<td><?=$in_available_date?></td>
							<th class="line"><?=$_LANG_TEXT['indatetext'][$lang_code]?></th>
							<td><span id='in_date'><?=$in_date?></span></td>
						</tr>
						<tr class="bg">
							<th><?=$_LANG_TEXT['checkapprovertext'][$lang_code]?> </th>
							<td>
								<span id='apprv_info'><?=$apprv_name?> <?if($apprv_name){ echo "(".$apprv_dt.")"; }?></span>
							</td>
							<th class="line"><?=$_LANG_TEXT['progressstatustext'][$lang_code]?></th>
							<td><span id='vcs_status'><?=$vcs_status;?></span></td>
						</tr>
						<tr>
							<th><?=$_LANG_TEXT['memotext'][$lang_code]?></th>
							<td colspan="3"><?=$memo?></td>
						</tr>
						</table>
					<?}else{?>
						<!--저장매체-->
						<table class="view">
						<tr>
							<th style='width:150px'><?=$_LANG_TEXT['checkdatetext'][$lang_code]?></th>
							<td style='width:350px'><?=$check_date?></td>
							<th class="line" style='width:150px'><?=$_LANG_TEXT['lastcheckdatetext'][$lang_code]?></th>
							<td ><?=$last_check_date?></td>
						</tr>
						<tr class="bg">
							<th><?=$_LANG_TEXT['devicegubuntext'][$lang_code]?></th>
							<td><?=$_CODE['asset_type'][$device_gubun]?></td>
							<th class="line">Device</th>
							<td><?=$os_ver_name?></td>
						</tr>
						<!-- Machine model/manufacturer
						<tr>
							<th><?=$_LANG_TEXT['modeltext'][$lang_code]?></th>
							<td><?=$model_name?></td>
							<th class="line"><?=$_LANG_TEXT['manufacturertext'][$lang_code]?></th>
							<td><?=$manufacturer?></td>
						</tr>
						-->
						<tr >
							<th><?=$_LANG_TEXT['visitortext'][$lang_code]?></th>
							<td><?=$v_user_name_com?></td>
							<th class="line"><?=$_LANG_TEXT['checkgubuntext'][$lang_code]?></th>
							<td><?=$check_type?></td>
						</tr>
						<tr  class="bg">
							<th><?=$_LANG_TEXT['executives'][$lang_code]?> / <?=$_LANG_TEXT['depttext'][$lang_code]?></th>
							<td><?=$mngr_name?> / <?=$mngr_dept?></td>
							<th class="line"><?=$_LANG_TEXT['scancentertext'][$lang_code]?></th>
							<td><?=$org_name?> <?=$scan_center_name?></td>
						</tr>
						<tr  >
							<th><?=$_LANG_TEXT['inlimitdatetext'][$lang_code]?></th>
							<td><?=$in_available_date?></td>
							<th class="line"><?=$_LANG_TEXT['indatetext'][$lang_code]?></th>
							<td><span id='in_date'><?=$in_date?></span></td>
						</tr>
						<tr class="bg">
							<th><?=$_LANG_TEXT['checkapprovertext'][$lang_code]?> </th>
							<td>
								<span id='apprv_info'><?=$apprv_name?> <?if($apprv_name){ echo "(".$apprv_dt.")"; }?></span>
							</td>
							<th class="line"><?=$_LANG_TEXT['progressstatustext'][$lang_code]?></th>
							<td><span id='vcs_status'><?=$vcs_status;?></span></td>
						</tr>
						<tr >
							<th><?=$_LANG_TEXT['memotext'][$lang_code]?></th>
							<td colspan="3"><?=$memo?></td>
						</tr>
						</table>
					<?}?>
						</form>
					</div>	
				</li>
			<?if($device_gubun=="NOTEBOOK"){?>
				<li>
					<a href="#" onclick="LoadPcInfo(<?=$v_wvcs_seq?>);"><?=$_LANG_TEXT['pcinfotext'][$lang_code]?></a>
					<div id='pc_info'></div>
				</li>
			<?}?>
				<li>
					<a href="#" onclick="LoadDiskInfo(<?=$v_wvcs_seq?>);"><?=$_LANG_TEXT['diskinfotext'][$lang_code]?></a>
					<div id='disk_info'></div>
				</li>
			<?if($device_gubun=="NOTEBOOK"){?>
				<li>
					<a href="#" onclick="LoadInstallProgramInfo(<?=$v_wvcs_seq?>);"><?=$_LANG_TEXT['programinstalledinfotext'][$lang_code]?></a>
					<div id='install_program_info'></div>
				</li>
			<?}?>
			</ul>
		</div>

		
<?
//취약점,바이러스 집계
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
		<div style='margin-top:30px;'>
			<ul class="tab">
			<?if($device_gubun=="NOTEBOOK"){?>
				<li class="on">
					<a href="#" onclick="LoadWeaknessInfo(<?=$v_wvcs_seq?>);"><?=$_LANG_TEXT['weaknesstext'][$lang_code]?>(<?=$weakness_cnt?>)</a>
					<div id='weakness_info'></div>
				</li>
			<?}?>
				<li <?if($device_gubun <> "NOTEBOOK") echo "class='on'";?>>
					<a href="#" onclick="LoadVaccineInfo(<?=$v_wvcs_seq?>);"><?=$_LANG_TEXT['virustext'][$lang_code]?>(<?=$virus_cnt?>)</a>
					<div id='vaccine_info'></div>
				</li>
			<?if($device_gubun=="NOTEBOOK"){?>
				<li>
					<a href="#" onclick="LoadWindowUpdateInfo(<?=$v_wvcs_seq?>);"><?=$_LANG_TEXT['windowupdatetext'][$lang_code]?></a>
					<div id='window_update_info'></div>
				</li>
			<?}?>
			</ul>
		</div>
		
	</div>
</div>
<p></p>	
<?php
if($result) sqlsrv_free_stmt($result);  
sqlsrv_close($wvcs_dbcon);
?>