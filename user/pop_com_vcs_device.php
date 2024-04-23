<?php
$_section_name = "pop_user_vcs_device";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$device_gubun = $_REQUEST[device_gubun];
$src = $_REQUEST[src];

$v_com_seq = $_REQUEST[v_com_seq];

$qry_params = array("com_seq"=>$v_com_seq);
$qry_label = QRY_USER_COM_INFO;
$sql = query($qry_label,$qry_params);
$result = sqlsrv_query($wvcs_dbcon, $sql);
$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

$v_com_name = $row['v_com_name'];


if($device_gubun=='NOTEBOOK'){
	$str_device_gubun = $_LANG_TEXT["laptoptext"][$lang_code];
}else if($device_gubun=='HDD'){
	$str_device_gubun =  $_CODE['storage_device_type']['HDD'];
}else if($device_gubun=='Removable'){
	$str_device_gubun =  $_CODE['storage_device_type']['Removable'];
}else{
	$str_device_gubun =  $_CODE['storage_device_type']['DEVICE_ETC'];
}


$param_enc = ParamEnCoding("src=".$_section_name."&v_com_seq=".$v_com_seq."&device_gubun=".$device_gubun);

?>
<script language="javascript">
$("document").ready(function(){

	var param_enc = "enc=<?=$param_enc?>";


	LoadPageDataList('com_device_vcs_list',SITE_NAME+'/user/get_com_device_vcs_list.php',param_enc);
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
			<div class="sub_tit"> > <font color='#e51010'><?=$str_device_gubun?></font> <?=$_LANG_TEXT["checklisttext"][$lang_code];?></div>
			<div id='com_device_vcs_list'></div>
		</div>
	</div>
</div>
