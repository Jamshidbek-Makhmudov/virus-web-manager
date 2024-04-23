<?php
$page_name = "tree_list";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$proc = $_REQUEST["proc"];
$org_id = $_REQUEST["org_id"];
$org_name = $_REQUEST["org_name"];
$use_yn = $_REQUEST["use_yn"];
$memo = $_REQUEST["memo"];

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,$proc);

$memo = str_replace("'", "''", $memo);
$memo = str_replace("\\", "", $memo);

$com_id = COMPANY_CODE;

$login_emp_seq = $_ck_user_seq;

if ($proc=="CREATE") {

	$qry_params = array("org_id"=>$org_id);
	$qry_label = QRY_TREE_ORG_COUNT;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

	$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

	if($row['cnt'] > 0){
		printJson($msg=$_LANG_TEXT['existedorgid'][$lang_code]);
	}

	$qry_params = array(
			"org_id"=>$org_id
			,"org_name"=>$org_name
			,"com_id"=>$com_id
			,"memo"=>$memo
			,"login_emp_seq"=>$login_emp_seq
			,"use_yn"=>$use_yn
		);
	$qry_label = QRY_TREE_ORG_INSERT;
	$sql = query($qry_label,$qry_params);
	
	$result = sqlsrv_query($wvcs_dbcon, $sql);

				
}else if($proc=="UPDATE"){
	
	$qry_params = array(
			"org_id"=>$org_id
			,"org_name"=>$org_name
			,"com_id"=>$com_id
			,"memo"=>$memo
			,"login_emp_seq"=>$login_emp_seq
			,"use_yn"=>$use_yn
		);
	$qry_label = QRY_TREE_ORG_UPDATE;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

}else if($proc =="DELETE"){
	
	//소속 사용자 체크
	$qry_params = array("org_id"=>$org_id);
	$qry_label = QRY_TREE_ORG_EMP_COUNT;
	$sql = query($qry_label,$qry_params);
	
	$result = sqlsrv_query($wvcs_dbcon, $sql);

	$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

	if($row['cnt'] > 0){
		printJson($msg=$_LANG_TEXT['nodeleteorgemp'][$lang_code]);
	}
	
	//소속 부서 체크
	$qry_params = array("org_id"=>$org_id);
	$qry_label = QRY_TREE_ORG_DEPT_COUNT;
	$sql = query($qry_label,$qry_params);
	
	$result = sqlsrv_query($wvcs_dbcon, $sql);

	$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

	if($row['cnt'] > 0){
		printJson($msg=$_LANG_TEXT['nodeleteorgdept'][$lang_code]);
	}

	$qry_params = array("org_id"=>$org_id);
	$qry_label = QRY_TREE_ORG_DELETE;
	$sql = query($qry_label,$qry_params);
	
	$result = sqlsrv_query($wvcs_dbcon, $sql);
	
}

if($result){
	$msg = $proc=="DELETE" ? "delete_ok" : "save_ok";
	printJson_OK($msg);
}else{
	printJson_ERROR('proc_error');
}
?>