<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$use_yn = $_REQUEST["use_yn"];

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'UPDATE');

$use_wk_seq = implode( ',', $use_yn );

$qry_params = array("use_wk_seq"=>$use_wk_seq);

$qry_label = QRY_POLICY_WEAKNESS_USE_UPDATE;
$sql = query($qry_label,$qry_params);

//printJson($sql);

$result = sqlsrv_query($wvcs_dbcon, $sql);

//printJson($sql);

if($result){
	printJson($msg=$_LANG_TEXT['procsuccess'][$lang_code],$data='',$status=true,$result,$wvcs_dbcon);
}else{
	printJson($msg=$_LANG_TEXT['procfail'][$lang_code]);
}
?>