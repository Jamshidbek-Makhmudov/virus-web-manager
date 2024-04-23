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


if($v_wvcs_seq !=""){

	$search_sql .= " AND vcs.v_wvcs_seq = '".$v_wvcs_seq."' ";
}

$qry_params = array(
	"search_sql"=> $search_sql
);

$qry_label = QRY_RESULT_VCS_INFO;
$sql = query($qry_label,$qry_params);
$result = @sqlsrv_query($wvcs_dbcon, $sql); 


 if($result){
  while($row=@sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
		$v_user_name = aes_256_dec($row['v_user_name']);
		$check_date = $row['check_date'];
		$os_ver_name = $row['os_ver_name'];
		$v_sys_sn = $row['v_sys_sn'];

		$v_user_sq = $row['v_user_seq'];
		$v_asset_type = $row['v_asset_type'];
		$v_notebook_key = $row['v_notebook_key'];
		
  }
}
$param_enc = ParamEnCoding("src=".$src."&v_wvcs_seq=".$v_wvcs_seq);
?>
<script language="javascript">
$("document").ready(function(){

	var param_enc = "enc=<?=$param_enc?>";

	LoadPageDataList('user_file_list',SITE_NAME+'/result/get_user_file_list.php',param_enc);

});
</script>
<div id="mark">
	<div class="content">
		<div class='tit'>
			<div class='txt'><?=$_LANG_TEXT["logviewtext"][$lang_code];?></div>
			<div class='right'>
				<?if($src=="USER_VCS_LOG"){?>
					<a href="javascript:" onclick="return popUserVcsLog('<?=$v_user_sq?>','<?=$v_user_name?>','<?=$v_notebook_key?>','<?=$v_asset_type?>');"><div class="prev_page"><?=$_LANG_TEXT['btngobeforepage'][$lang_code]?></div></a>
				<?}?>
				<div class='close' onClick="ClosepopContent();"></div>
			</div>
		</div>
		<div class='wrapper2'>
			<div class="sub_tit"> > <?=$v_user_name?> <? echo trsLang('반입파일내역','importfilehistory');?></div>
			<div class="wrapper">
				<div style='float:right;padding:0px 3px; '>
					<? echo trsLang('검사일','checkdatetext');?> : <? echo $check_date;?> / <? echo $os_ver_name?> / S/N : <? echo $v_sys_sn;?>
				</div>
			</div>
			<div id='user_file_list'></div>
		</div>
	</div>
</div>
