<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$v_user_seq = $_REQUEST[data1];
$v_user_name = $_REQUEST[data2];
$v_asset_type = $_REQUEST[data3];
$v_notebook_key = $_REQUEST[data4];

$param_enc = ParamEnCoding("src=USER_VCS_LOG&v_user_seq=".$v_user_seq."&v_asset_type=".$v_asset_type."&v_notebook_key=".$v_notebook_key);
?>
<script language="javascript">
$("document").ready(function(){

	var param_enc = "enc=<?=$param_enc?>";

	LoadPageDataList('user_check_list',SITE_NAME+'/user/get_visitor_check_list.php',param_enc);
	// LoadPageDataList('user_check_list',SITE_NAME+'/result/get_user_check_list.php',param_enc);

});
</script>
<div id="mark">
	<div class="content">
		<div class='tit'>
			<div class='txt'><?=$_LANG_TEXT["logviewtext"][$lang_code];?></div>
			<div class='right'>
				<div class='close' onClick="ClosepopContent();"></div>
			</div>
		</div>
		<div class='wrapper2'>
			<div class="sub_tit"> > <?=$v_user_name?> <?=$_LANG_TEXT["checklisttext"][$lang_code];?></div>
			<div id='user_check_list'></div>
		</div>
	</div>
</div>
