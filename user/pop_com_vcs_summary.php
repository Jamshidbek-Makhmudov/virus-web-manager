<?php
$_section_name = "pop_com_vcs_summary";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$v_com_seq = $_REQUEST[v_com_seq];

$qry_params = array("com_seq"=>$v_com_seq);
$qry_label = QRY_USER_COM_INFO;
$sql = query($qry_label,$qry_params);
$result = sqlsrv_query($wvcs_dbcon, $sql);
$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

$v_com_name = $row['v_com_name'];
$param_enc = ParamEnCoding("src=".$_section_name."&v_com_seq=".$v_com_seq);

?>
<script language="javascript">
$("document").ready(function(){

	var param_enc = "enc=<?=$param_enc?>";
	LoadPageDataList('com_device_vcs_list',SITE_NAME+'/user/get_com_user_list.php',param_enc);

	StatisticsComDeviceVcsData('<?=$v_com_seq?>');
});
</script>
<div id="mark">
	<div class="content">
		<div class='tit'>
			<div class='txt'><?=$v_com_name?> <?=$_LANG_TEXT["checkstatustext"][$lang_code];?></div>
			<div class='right'>
				<div class='close' onClick="ClosepopContent();"></div>
			</div>
		</div>
		<div class='wrapper2'>
<?
//직원수
$qry_params = array("v_com_seq"=>$v_com_seq);
$qry_label = QRY_STAT_COM_USER_CNT;
$sql = query($qry_label,$qry_params);

$result = sqlsrv_query($wvcs_dbcon, $sql);

$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

$str_user_cnt = number_format($row['cnt']);

//점검장비 수량
$search_sql = " AND vcs1.v_user_seq in (SELECT v_user_seq FROM tb_v_user WHERE v_com_seq='{$v_com_seq}') ";
$qry_params = array("search_sql"=>$search_sql);
$qry_label = QRY_STAT_USER_DEVICE;
$sql = query($qry_label,$qry_params);

$result = sqlsrv_query($wvcs_dbcon, $sql);

$str_notebook_cnt = 0;
$str_hdd_cnt = 0;
$str_removable_cnt = 0;
//$str_cddvd_cnt = 0;
$str_etc_cnt = 0;

//echo nl2br($sql);

if($result){

	$all_cnt = 0;

	while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		
		$device_gubun = $row['device_gubun'];
		$device_cnt = $row['device_cnt'];

		if($device_gubun=="NOTEBOOK"){
			$str_notebook_cnt = number_format($device_cnt); 
		}else if($device_gubun=="HDD"){
			$str_hdd_cnt = number_format($device_cnt); 
		}else if($device_gubun=="Removable"){
			$str_removable_cnt = number_format($device_cnt); 
//		}else if($device_gubun=="CD/DVD"){
//			$str_cddvd_cnt = number_format($device_cnt); 
		}else if($device_gubun=="ETC"){
			$str_etc_cnt = number_format($device_cnt); 
		}

		$all_cnt += $device_cnt;

	}

	$str_all_cnt = number_format($all_cnt);
}

if($result){

	while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		
		$device_gubun = $row['device_gubun'];
		$device_cnt = $row['device_cnt'];

		if($device_gubun=="NOTEBOOK"){
			$str_notebook_cnt = number_format($device_cnt); 
		}else if($device_gubun=="HDD"){
			$str_hdd_cnt = number_format($device_cnt); 
		}else if($device_gubun=="Removable"){
			$str_removable_cnt = number_format($device_cnt); 
//		}else if($device_gubun=="CD/DVD"){
//			$str_cddvd_cnt = number_format($device_cnt); 
		}else if($device_gubun=="ETC"){
			$str_etc_cnt = number_format($device_cnt); 
		}

	}
}

//취약점,악성코드
$search_sql = " AND vcs1.v_user_seq in (SELECT v_user_seq FROM tb_v_user WHERE v_com_seq='{$v_com_seq}') ";
$qry_params = array("search_sql"=>$search_sql);
$qry_label = QRY_STAT_USER_WV;
$sql = query($qry_label,$qry_params);

$result = sqlsrv_query($wvcs_dbcon, $sql);

//echo nl2br($sql);

$str_weak_cnt = 0;
$str_virus_cnt = 0;

if($result){

	while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		
		$str_weak_cnt = number_format($row['weak_cnt']);
		$str_virus_cnt = number_format($row['virus_cnt']);

	}
}

//취약점,악성코드(1개월이내)
$search_sql .= " AND vcs1.wvcs_dt > dateadd(m,-1,getdate()) ";
$qry_params = array("search_sql"=>$search_sql);
$qry_label = QRY_STAT_USER_WV;
$sql = query($qry_label,$qry_params);

$result = sqlsrv_query($wvcs_dbcon, $sql);

$str_weak_1m_cnt = 0;
$str_virus_1m_cnt = 0;

if($result){

	while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		
		$str_weak_1m_cnt = number_format($row['weak_cnt']);
		$str_virus_1m_cnt = number_format($row['virus_cnt']);

	}
}

?>	
			<div class='outline'>
				<div class="section06">
					<h1><?=$_LANG_TEXT['visitortext'][$lang_code]?></h1>
					<div class="ch">
						<div class='wrap1'><div class='one'><div id='ChartUserData' class="txt2 cap1"><?=$str_user_cnt?></div><!--<canvas id="ChartUser" width='250%' height='250%'/></canvas>--><div class="txt cap1"><?=$_LANG_TEXT["employeetext"][$lang_code];?></div></div></div>
					</div>
				</div>
				<div class="section07">
					<h1><?=$_LANG_TEXT['registdevicelisttext'][$lang_code]?></h1>
					<div class="ch">
						<div class='wrap1'><div class='two'><div id='ChartAllData' class="txt2 cap2"><?=$str_all_cnt?></div><canvas id="ChartAll" width='250%' height='250%'/></canvas><div class="txt cap1"><?=$_LANG_TEXT["alltext"][$lang_code];?></div></div></div>

						<div class='wrap1'><div class='three'><div id='ChartNotebookData' class="txt2 cap3"><?=$str_notebook_cnt?></div><canvas id="ChartNotebook" width='250%' height='250%' /></canvas><div class="txt cap2" ><?=$_LANG_TEXT['laptoptext'][$lang_code]?></div></div></div>

						<div class='wrap1'><div class='four'><div id='ChartHddData' class="txt2 cap4"><?=$str_hdd_cnt?></div><canvas id="ChartHdd" width='250%' height='250%' /></canvas><div class="txt cap3" ><?=$_CODE['storage_device_type']['HDD']?></div></div></div>

						<div class='wrap1'><div class='five'><div id='ChartRemovableData' class="txt2 cap5"><?=$str_removable_cnt?></div><canvas id="ChartRemovable" width='250%' height='250%'/></canvas><div class="txt cap4"><?=$_CODE['storage_device_type']['Removable']?></div></div></div>

						<div class='wrap1'><div class='six'><div id='ChartEtcData' class="txt2 cap6"><?=$str_etc_cnt?></div><canvas id="ChartEtc" width='250%' height='250%'/></canvas><div class="txt cap5" ><?=$_CODE['storage_device_type']['DEVICE_ETC']?></div></div></div>
					</div>
				</div>
				<div class="section08">
					<h1><?=$_LANG_TEXT['usercheckstatustext'][$lang_code]?></h1>
					<div class='ch'>
						<div class='wrap1'>
							<div class='one'>
								<div id='ChartWeakData' class="txt2 cap1" style='cursor:pointer' onclick="popComVcsWeakVirus('<?=$v_com_seq?>','<?=$_section_name?>')"><?=$str_weak_cnt?></div>
								<div class='txt3'><?=$_LANG_TEXT['withinonemonthtext'][$lang_code]?> : <?=$str_weak_1m_cnt?></div>
								<canvas id="ChartWeak" width='250%' height='250%' /></canvas>
								<div class="txt cap1"><?=$_LANG_TEXT['weaknessdetectiontext'][$lang_code]?></div>
							</div>
						</div>
						<div class='wrap1'>
							<div class='two'>
								<div id='ChartVirusData' class="txt2 cap2" style='cursor:pointer' onclick="popComVcsWeakVirus('<?=$v_com_seq?>','<?=$_section_name?>')"><?=$str_virus_cnt?></div>
								<div class='txt3'><?=$_LANG_TEXT['withinonemonthtext'][$lang_code]?> : <?=$str_virus_1m_cnt?></div>
								<canvas id="ChartVirus" width='250%' height='250%'/></canvas>
								<div class="txt cap2"><?=$_LANG_TEXT['virusdetectiontext'][$lang_code]?></div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!--등록장비리스트-->
			<div id='com_device_vcs_list' style='margin-top:15px;height:500px;padding:5px;'></div>

		</div>
		<!--//<div class='wrapper2'>-->
	</div>
</div>
