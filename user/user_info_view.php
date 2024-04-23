<?php
$page_name = "user_info";
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

$v_user_seq = $_REQUEST[v_user_seq];
$searchopt = $_REQUEST[searchopt];	// 검색옵션
$searchkey = $_REQUEST[searchkey];	// 검색어
$useyn = $_REQUEST[useyn];
$orderby = $_REQUEST[orderby];		// 정렬순서

$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($useyn!="") $param .= ($param==""? "":"&")."useyn=".$useyn;
if($page!="") $param .= ($param==""? "":"&")."page=".$page;

if($v_user_seq <> "") {

		$qry_params = array("v_user_seq"=> $v_user_seq);
		$qry_label = QRY_USER_INFO;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);

		if($result){

			$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
			$user_name = aes_256_dec($row['v_user_name']);
		
			$use_yn = $row['v_use_yn'];
			$user_com_name = $row['v_com_name'];

			$approvaltext = ($use_yn=="Y") ? $_LANG_TEXT["approvedtext"][$lang_code] : $_LANG_TEXT["unapprovedtext"][$lang_code];

			$com_use_yn = $row['com_use_yn'];
			$comapprovaltext = ($com_use_yn=="Y") ? $_LANG_TEXT["approvedtext"][$lang_code] : $_LANG_TEXT["unapprovedtext"][$lang_code];

			if($_encryption_kind=="1"){

				$email = $row['v_email_decript'];
				$phone_no = $row['v_phone_decript'];
				
			}else if($_encryption_kind=="2" || $_encryption_kind=="3"){

				$email = aes_256_dec($row['v_email']);
				$phone_no = aes_256_dec($row['v_phone']);
			}

		}
		
		$title_pw_word = $_LANG_TEXT["resettext"][$lang_code];

		

}

$param_enc = ParamEnCoding("src=USER_INFO_VIEW&v_user_seq=".$v_user_seq."&v_notebook_key=".$v_notebook_key);
?>
<script language="javascript">
$("document").ready(function(){
	
	var param_enc = "enc=<?=$param_enc?>";

	LoadPageDataList('user_check_list',SITE_NAME+'/result/get_user_check_list.php',param_enc);

	StatisticsUserVcsData('<?=$v_user_seq?>');
});
</script>
<div id="user_input">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
					<h1><span id='page_title'><?=$_LANG_TEXT["m_visitor_info"][$lang_code];?></span></h1>

			</div>
			<span class="line"></span>
		</div>
		<div class="page_right" ><span style='cursor:pointer' onclick="location.href='./user_list.php?enc=<?=ParamEnCoding($param)?>'"><?=$_LANG_TEXT['btngobeforepage'][$lang_code]?></span></div>

		<table class="view">
		<tr>
			<th style='width:150px'><?=$_LANG_TEXT["visitortext"][$lang_code];?></th>
			<td style='width:350px'><?=$user_name?>(<?=$approvaltext?>)</td>
			<th class="line" style='width:150px'><?=$_LANG_TEXT["usercompanynametext"][$lang_code];?></th>
			<td ><?=$user_com_name? $user_com_name."(".$comapprovaltext.")" : ""?></td>
		</tr>
		<tr class="bg">
			<th><?=$_LANG_TEXT["emailtext"][$lang_code];?></th>
			<td><?=$email?></td>
			<th class="line"><?=$_LANG_TEXT["contactphonetext"][$lang_code];?></th>
			<td><?=$phone_no?></td>
		</tr>
		</table>
		<div class="btn_wrap">
			<div class="right">
					<a href="./user_list.php"   class="btn"><?=$_LANG_TEXT["btnlist"][$lang_code];?></a>
					<a href="./user_info_edit.php?enc=<?=ParamEnCoding("v_user_seq=".$v_user_seq)?>"   class="btn"><?=$_LANG_TEXT["btnvisitorinfoedit"][$lang_code];?></a>
			</div>
		</div>
		<!--User Vcs Summary-->
		<div class='outline' style='height:230px'>
			<div class='section'>
				<div class="section01">
					<h1><?=$_LANG_TEXT['usercheckstatustext'][$lang_code]?></h1>
					<div class='ch'>
						<div class='wrap1'><div class='one'><div id='ChartVcsData' class="txt2 cap1">0</div><canvas id="ChartVcs" width='250%' height='250%' /></canvas><div class="txt cap1"><?=$_LANG_TEXT['checkstatustext'][$lang_code]?></div></div></div>
						<div class='wrap1'><div class='two'><div id='ChartWeakData' class="txt2 cap2">0</div><canvas id="ChartWeak" width='250%' height='250%' /></canvas><div class="txt cap2"  onclick="return popUserVcsWeakVirus('<?=$v_user_seq?>','<?=$page_name?>');" style='cursor:pointer;'><?=$_LANG_TEXT['weaknessdetectiontext'][$lang_code]?>(<? echo trsLang('상세','detailstext');?>)</div></div></div>
						<div class='wrap1'><div class='three'><div id='ChartVirusData' class="txt2 cap3">0</div><canvas id="ChartVirus" width='250%' height='250%'/></canvas><div class="txt cap3" style='cursor:pointer' onclick="return popUserVcsWeakVirus('<?=$v_user_seq?>','<?=$page_name?>');"><?=$_LANG_TEXT['virusdetectiontext'][$lang_code]?>(<? echo trsLang('상세','detailstext');?>)</div></div></div>
					</div>
				</div>
				<div class="section02">
					<h1><?=$_LANG_TEXT['checkdevicestatustext'][$lang_code]?></h1>
					<div class="ch">
						<div class='wrap1'><div class='one'><div id='ChartNotebookData' class="txt2 cap1">0</div><canvas id="ChartNotebook" width='250%' height='250%' /></canvas><div class="txt cap1" style='cursor:pointer' onclick="return popUserVcsDevice('<?=$v_user_seq?>','NOTEBOOK','<?=$page_name?>');"><?=$_LANG_TEXT['laptoptext'][$lang_code]?>(<span id='NoteBookCnt'>0</span>)</div></div></div>
						<div class='wrap1'><div class='two'><div id='ChartHddData' class="txt2 cap2">0</div><canvas id="ChartHdd" width='250%' height='250%' /></canvas><div class="txt cap2" style='cursor:pointer' onclick="return popUserVcsDevice('<?=$v_user_seq?>','HDD','<?=$page_name?>');"><?=$_CODE['storage_device_type']['HDD']?>(<span id='HddCnt'>0</span>)</div></div></div>
						<div class='wrap1'><div class='three'><div id='ChartRemovableData' class="txt2 cap3">0</div><canvas id="ChartRemovable" width='250%' height='250%'/></canvas><div class="txt cap3" style='cursor:pointer' onclick="return popUserVcsDevice('<?=$v_user_seq?>','Removable','<?=$page_name?>');"><?=$_CODE['storage_device_type']['Removable']?>(<span id='RemovableCnt'>0</span>)</div></div></div>
						<!--<div class='wrap1'><div class='four'><div id='ChartCdDvdData' class="txt2 cap3">0</div><canvas id="ChartCdDvd" width='250%' height='250%'/></canvas><div class="txt cap3"><?=$_CODE['storage_device_type']['CD/DVD']?>(<span id='CdDvdCnt'>0</span>)</div></div></div>-->
						<div class='wrap1'><div class='five'><div id='ChartEtcData' class="txt2 cap3">0</div><canvas id="ChartEtc" width='250%' height='250%'/></canvas><div class="txt cap3" style='cursor:pointer' onclick="return popUserVcsDevice('<?=$v_user_seq?>','ETC','<?=$page_name?>');"><?=$_CODE['storage_device_type']['DEVICE_ETC']?>(<span id='EtcCnt'>0</span>)</div></div></div>
					</div>
				</div>
			</div>
		</div>

		<div class="sub_tit"> > <?=$user_name?> <span id='vcs_title' style='color:#e51010'></span> <?=$_LANG_TEXT['checklisttext'][$lang_code]?></div>
		<div id='user_check_list'></div>
		
	</div>
	

</div>
<div id='popContent' style='display:none'></div>
<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>