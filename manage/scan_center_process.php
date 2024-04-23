<?php
$page_name = "scan_center_list";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$org_id = $_REQUEST['org_id'];
$scan_center_seq = $_REQUEST["scan_center_seq"];
$scan_center_code = $_REQUEST["scan_center_code"];
$scan_center_name = $_REQUEST["scan_center_name"];
$scan_center_div = $_REQUEST["scan_center_div"];
$use_yn = $_REQUEST["use_yn"];
$sort = $_REQUEST["sort"];
$proc = $_REQUEST["proc"];

$json = array (
	 'msg' => "[$proc] ".$_LANG_TEXT['procfail'][$lang_code]
	,'data' => ""
	,'status' => false
);


if($proc == "CREATE" && $scan_center_seq <> "") {
	printJson($msg=$_LANG_TEXT['wrongdatatranstext'][$lang_code]);
} else if ( ($proc == "UPDATE" || $proc == "DELETE" ) && $scan_center_seq == "") {
	printJson($msg=$_LANG_TEXT['wrongdatatranstext'][$lang_code]);
}

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,$proc);

if ($proc == "CREATE") {

	//Center Code �ߺ�üũ
	$search_sql = " AND scan_center_code = '{$scan_center_code}' ";
	$qry_params = array("search_sql"=>$search_sql);
	$qry_label = QRY_SCAN_CENTER_CHECK;
	$sql = query($qry_label,$qry_params);
	$result = sqlsrv_query($wvcs_dbcon, $sql);

	$rows = @sqlsrv_has_rows( $result );
	if ($rows === true){
		printJson($_LANG_TEXT['duplicatecodetext'][$lang_code]);
	}
	
	$qry_params = array(
		"org_id"=>$org_id,
		"scan_center_code"=>$scan_center_code,
		"scan_center_name"=>$scan_center_name,
		"scan_center_div"=>$scan_center_div,
		"use_yn"=>$use_yn,
		"sort"=>$sort
		);
	$qry_label = QRY_SCAN_CENTER_INSERT;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

	//printJson($sql);

	if($result){
		
		$qry_params = array();
		$qry_label = QRY_COMMON_IDENTITY;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);

		$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		
		$center_seq = $row['seq'];
	}
				
}else if ($proc == "UPDATE") {

	$qry_params = array(
			"scan_center_seq"=>$scan_center_seq,
			"org_id"=>$org_id,
			"scan_center_name"=>$scan_center_name,
			"scan_center_div"=>$scan_center_div,
			"use_yn"=>$use_yn,
			"sort"=>$sort
		);
	$qry_label = QRY_SCAN_CENTER_UPDATE;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

}else if ($proc == "DELETE") {


	//kiosk_id, kiosk_link  ����
	$Model_manage = new Model_manage();
	$args = array("scan_center_seq"=>$scan_center_seq);
	$result = $Model_manage->deleteScanCenterKioskBySeq($args);

	if($result){
		$qry_params = array("scan_center_seq"=>$scan_center_seq);
		$qry_label = QRY_SCAN_CENTER_DELETE;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);
	}

}

if($result){
	$msg = $proc=="DELETE" ? "delete_ok" : "save_ok";
	printJson($msg,$data=$center_seq,$status=true,$result,$wvcs_dbcon);
}else{
	printJson_ERROR('proc_error');
}
?>