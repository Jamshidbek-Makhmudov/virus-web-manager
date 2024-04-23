<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$com_id = $_REQUEST["com_id"];
$v_seq = $_REQUEST["v_seq"];
$v_name = $_REQUEST["v_name"];
$v_ver = $_REQUEST["v_ver"];
$v_desc = $_REQUEST["v_desc"];
$p_name = $_REQUEST["p_name"];
$link = $_REQUEST["link"];
$use_yn = $_REQUEST["use_yn"];
$sort = $_REQUEST["sort"];
$proc = $_REQUEST["proc"];

$v_desc = str_replace("'", "''", $v_desc);
$v_desc = str_replace("\\", "", $v_desc);

if($sort=="") $sort = "1";

if($proc == "CREATE" && $v_seq <> "") {
	printJson($msg=$_LANG_TEXT['wrongdatatranstext'][$lang_code]);
} else if ( ($proc == "UPDATE" || $proc == "DELETE" ) && $v_seq == "") {
	printJson($msg=$_LANG_TEXT['wrongdatatranstext'][$lang_code]);
}

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,$proc);

$gubun = "";

$com_id = COMPANY_CODE;

if ($proc == "CREATE") {
	
	$qry_params = array(
		"com_id"=>$com_id
		,"v_name"=>$v_name
		,"v_ver"=>$v_ver
		,"use_yn"=>$use_yn
		,"sort"=>$sort
		,"v_desc"=>$v_desc
		,"gubun"=>$gubun
		,"p_name"=>$p_name
		,"link"=>$link
		,"emp_seq"=>$_ck_user_seq
	);
	$qry_label = QRY_VACCINE_INSERT;
	$sql = query($qry_label,$qry_params);

	//printJson($sql);

	$result = sqlsrv_query($wvcs_dbcon, $sql);
				
}else if ($proc == "UPDATE") {


	$qry_params = array(
		"v_seq"=>$v_seq
		,"com_id"=>$com_id
		,"v_name"=>$v_name
		,"v_ver"=>$v_ver
		,"use_yn"=>$use_yn
		,"sort"=>$sort
		,"v_desc"=>$v_desc
		,"gubun"=>$gubun
		,"p_name"=>$p_name
		,"link"=>$link
		,"emp_seq"=>$_ck_user_seq
	);
	$qry_label = QRY_VACCINE_UPDATE;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

}else if ($proc == "DELETE") {

	$qry_params = array("v_seq"=>$v_seq);
	$qry_label = QRY_VACCINE_DELETE;
	$sql = query($qry_label,$qry_params);
	
	

	$result = sqlsrv_query($wvcs_dbcon, $sql);

}

if($result){
	printJson($msg="[$proc] ".$_LANG_TEXT['procsuccess'][$lang_code],$data='',$status=true,$result,$wvcs_dbcon);
}else{
	printJson($msg="[$proc] ".$_LANG_TEXT['procfail'][$lang_code]);
}
?>