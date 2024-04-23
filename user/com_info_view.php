<?php
$page_name = "com_info";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
include_once $_server_path . "/" . $_site_path . "/inc/header.inc";
include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";

$v_com_seq = $_REQUEST[v_com_seq];

$param = "";
if($page!="") $param .= ($param==""? "":"&")."page=".$page;

if($v_com_seq <> "") {

		$qry_params = array("com_seq"=> $v_com_seq);
		$qry_label = QRY_USER_COM_INFO;
		$sql = query($qry_label,$qry_params);

		//echo nl2br($sql);

		$result = sqlsrv_query($wvcs_dbcon, $sql);

		if($result){

			$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
			$v_com_name = $row['v_com_name'];
			$use_yn = $row['use_yn'];
			$v_ceo_name = $row['v_ceo_name'];
			$v_com_code = $row['v_com_code_1']."-".$row['v_com_code_2']."-".$row['v_com_code_3'];
			$v_com_gubun_1 = $row['v_com_gubun_1'];
			$v_com_gubun_2 = $row['v_com_gubun_2'];

			$approvaltext = ($use_yn=="Y") ? $_LANG_TEXT["approvedtext"][$lang_code] : $_LANG_TEXT["unapprovedtext"][$lang_code];

		}

}

$param_enc = ParamEnCoding("src=COM_INFO_VIEW&v_com_seq=".$v_com_seq);
?>
<script language="javascript">
$("document").ready(function(){
	
	var param_enc = "enc=<?=$param_enc?>";

	LoadPageDataList('user_check_list',SITE_NAME+'/result/get_user_check_list.php',param_enc);

	StatisticsComVcsData('<?=$v_com_seq?>');
});
</script>
<div id="user_input">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">

				 <h1><span id='page_title'><?=$_LANG_TEXT["m_com_info"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		<div class="page_right" ><span style='cursor:pointer' onclick="location.href='./user_list.php?enc=<?=ParamEnCoding($param)?>'"><?=$_LANG_TEXT['btngobeforepage'][$lang_code]?></span></div>

		<table class="view">
		<tr>
			<th style='width:150px'><?=$_LANG_TEXT["usercompanynametext"][$lang_code];?></th>
			<td style='width:350px'>
				<?=$v_com_name?>
			</td>
			<th class="line" style='width:150px'><?=$_LANG_TEXT["approvedyesnotext"][$lang_code];?></th>
			<td >
				<?=$approvaltext?>
			</td>
		</tr>
		<tr class="bg">
			<th><?=$_LANG_TEXT["ceotext"][$lang_code];?></th>
			<td><?=$v_ceo_name?></td>
			<th class="line"><?=$_LANG_TEXT["companyregistrationnumbertext"][$lang_code];?></th>
			<td>
				<?=$v_com_code?>
			</td>
		</tr>
		<tr>
			<th><?=$_LANG_TEXT["companyindustrytext"][$lang_code];?></th>
			<td><?=$v_com_gubun_1?></td>
			<th class="line"><?=$_LANG_TEXT["companycategorytext"][$lang_code];?></th>
			<td>
				<?=$v_com_gubun_2?>
			</td>
		</tr>
		</table>
		<div class="btn_wrap">
			<div class="right">
					<a href="./user_list.php"   class="btn"><?=$_LANG_TEXT["btnlist"][$lang_code];?></a>
					<a href="./com_info_edit.php?enc=<?=ParamEnCoding("v_com_seq=".$v_com_seq)?>"   class="btn"><?=$_LANG_TEXT["btncompanyinfoedit"][$lang_code];?></a>
			</div>
		</div>
		<!--User Vcs Summary-->
		<div class='outline' style='height:230px'>
			<div class='section'>
				<div class="section01">
					<h1><?=$_LANG_TEXT['usercheckstatustext'][$lang_code]?></h1>
					<div class='ch'>
						<div class='wrap1'><div class='one'><div id='ChartVcsData' class="txt2 cap1">0</div><canvas id="ChartVcs" width='250%' height='250%' /></canvas><div class="txt cap1"><?=$_LANG_TEXT['checkstatustext'][$lang_code]?></div></div></div>
						<div class='wrap1'><div class='two'><div id='ChartWeakData' class="txt2 cap2">0</div><canvas id="ChartWeak" width='250%' height='250%' /></canvas><div class="txt cap2"><?=$_LANG_TEXT['weaknessdetectiontext'][$lang_code]?> <img src="<?=$_www_server?>/images/chart.png" style='cursor:pointer' onclick="return popComVcsWeakVirus('<?=$v_com_seq?>','<?=$page_name?>');"></div></div></div>
						<div class='wrap1'><div class='three'><div id='ChartVirusData' class="txt2 cap3">0</div><canvas id="ChartVirus" width='250%' height='250%'/></canvas><div class="txt cap3"><?=$_LANG_TEXT['virusdetectiontext'][$lang_code]?> <img src="<?=$_www_server?>/images/chart.png" style='cursor:pointer' onclick="return popComVcsWeakVirus('<?=$v_com_seq?>','<?=$page_name?>');"></div></div></div>
					</div>
				</div>
				<div class="section02">
					<h1><?=$_LANG_TEXT['checkdevicestatustext'][$lang_code]?></h1>
					<div class="ch">
						<div class='wrap1'><div class='one'><div id='ChartNotebookData' class="txt2 cap1">0</div><canvas id="ChartNotebook" width='250%' height='250%' /></canvas><div class="txt cap1" style='cursor:pointer' onclick="return popComVcsDevice('<?=$v_com_seq?>','NOTEBOOK','<?=$page_name?>');"><?=$_LANG_TEXT['laptoptext'][$lang_code]?>(<span id='NoteBookCnt'>0</span>)</div></div></div>
						<div class='wrap1'><div class='two'><div id='ChartHddData' class="txt2 cap2">0</div><canvas id="ChartHdd" width='250%' height='250%' /></canvas><div class="txt cap2" style='cursor:pointer' onclick="return popComVcsDevice('<?=$v_com_seq?>','HDD','<?=$page_name?>');"><?=$_CODE['storage_device_type']['HDD']?>(<span id='HddCnt'>0</span>)</div></div></div>
						<div class='wrap1'><div class='three'><div id='ChartRemovableData' class="txt2 cap3">0</div><canvas id="ChartRemovable" width='250%' height='250%'/></canvas><div class="txt cap3" style='cursor:pointer' onclick="return popComVcsDevice('<?=$v_com_seq?>','Removable','<?=$page_name?>');"><?=$_CODE['storage_device_type']['Removable']?>(<span id='RemovableCnt'>0</span>)</div></div></div>
						<!--<div class='wrap1'><div class='four'><div id='ChartCdDvdData' class="txt2 cap3">0</div><canvas id="ChartCdDvd" width='250%' height='250%'/></canvas><div class="txt cap3"><?=$_CODE['storage_device_type']['CD/DVD']?>(<span id='CdDvdCnt'>0</span>)</div></div></div>-->
						<div class='wrap1'><div class='five'><div id='ChartEtcData' class="txt2 cap3">0</div><canvas id="ChartEtc" width='250%' height='250%'/></canvas><div class="txt cap3" style='cursor:pointer' onclick="return popComVcsDevice('<?=$v_com_seq?>','ETC','<?=$page_name?>');"><?=$_CODE['storage_device_type']['DEVICE_ETC']?>(<span id='EtcCnt'>0</span>)</div></div></div>
					</div>
				</div>
			</div>
		</div>

		<div class="sub_tit"> > <?=$v_com_name?> <span id='vcs_title' style='color:#e51010'></span> <?=$_LANG_TEXT['checklisttext'][$lang_code]?></div>
		<div id='user_check_list'></div>
		
	</div>
	

</div>
<div id='popContent' style='display:none'></div>
<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>