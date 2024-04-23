<?php
// //$page_name = "admin_reg";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$admin_seq = $_REQUEST['admin_seq'];
$proc_name = $_REQUEST["proc_name"];

// printJson($admin_seq);

$json = array (
	 'msg' => $_LANG_TEXT['procfail'][$lang_code]
	,'data' => ""
	,'status' => false
);

# 작업로그기록
//$_work_log_seq = WriteAdminActLog($proc_name,'UPDATE');

$qry_params = array("admin_seq"=>$admin_seq,"today"=>date("Ymd"));
$qry_label = QRY_LOGIN_ATTEMPT_UPDATE;
$sql = query($qry_label,$qry_params);

$result = @sqlsrv_query($wvcs_dbcon, $sql);
if($result){
	//로그인 LOCK 해제..
	$search_sql = " AND isnull(LOGIN_LOCK_TYPE,'') in ('','LOGIN_ATTEMPT_OVER') ";
	$qry_params = array("emp_seq"=>$admin_seq,"login_lock_yn"=>"N","login_lock_type"=>"", "search_sql"=> $search_sql);
	$qry_label = QRY_ADMIN_LOGIN_LOCK_UPDATE;
	$sql = query($qry_label,$qry_params);

	$result = @sqlsrv_query($wvcs_dbcon, $sql);
}

if($result){
	printJson($msg=$_LANG_TEXT['procsuccess'][$lang_code],$data='',$status=true,$result,$wvcs_dbcon);

}else{
	printErrJson($msg=$json['msg'],$result,$wvcs_dbcon);
}


?>