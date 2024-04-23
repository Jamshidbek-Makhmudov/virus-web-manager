<?php
$page_name = "result_list";
// $page_name = "access_control";
// $page_tab_name = "access_control_file";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$v_wvcs_seq = $_POST['v_wvcs_seq'];
$mngr_name = $_POST['mngr_name'];
$mngr_dept = $_POST['mngr_dept'];
$scan_center_code = $_POST['scan_center_code'];
$memo = $_POST['memo'];
$proc = $_POST['proc'];

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');

$memo = str_replace("'", "''", $memo);
$memo = str_replace("\\", "", $memo);

if($proc=="UPDATE"){

	$qry_params = array(
		"v_wvcs_seq"=>$v_wvcs_seq
		,"mngr_name"=>aes_256_enc($mngr_name)
		,"mngr_dept"=>$mngr_dept
		,"scan_center_code"=>$scan_center_code
		,"memo"=>$memo
	);
	$qry_label = QRY_RESULT_PC_CHECK_UPDATE;
	$sql = query($qry_label,$qry_params);

	//printJson($sql);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

}else if($proc=="DELETE"){
	
	$qry_params = array("v_wvcs_seq"=>$v_wvcs_seq);
	$qry_label = QRY_VCS_RESULT_DELETE;
	$sql = query($qry_label,$qry_params);

	//printJson($sql);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

}

if($result) {
	$msg = $proc=="DELETE" ? "delete_ok" : "save_ok";
	printJson_OK($msg);
}else{
	printJson_ERROR('proc_error');
}

?>