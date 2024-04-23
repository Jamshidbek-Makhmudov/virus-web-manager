<?php
$page_name = "code_list";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$proc = $_POST['proc'];
$p_code_seq = $_POST["p_code_seq"];
$code_seq = $_POST["code_seq"];
$code_name = $_POST["code_name"];
$code_key = $_POST["code_key"];
$sort = $_POST['sort'];
$useyn = $_POST['useyn'];
$scan_center_code = $_POST['scan_center_code'];

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,$proc);

$login_emp_seq = $_ck_user_seq;

$depth = ($p_code_seq==0) ? "1" : "2";
$fix_yn = "N";

if($proc=="CREATE"){
		
	$qry_params = array(
			"code_key"=>$code_key
			,"code_name"=>$code_name
			,"depth"=>$depth
			,"sort"=>$sort
			,"fix_yn"=>$fix_yn
			,"useyn"=>$useyn
			,"p_code_seq"=>$p_code_seq
			,"login_emp_seq"=>$login_emp_seq
			,"refer_val"=>$scan_center_code
		);
	$qry_label = QRY_CODE_INSERT;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

}else if($proc=="UPDATE"){
	
	$qry_params = array(
			"code_key"=>$code_key
			,"code_name"=>$code_name
			,"sort"=>$sort
			,"useyn"=>$useyn
			,"p_code_seq"=>$p_code_seq
			,"login_emp_seq"=>$login_emp_seq
			,"code_seq"=>$code_seq
			,"refer_val"=>$scan_center_code
		);
	$qry_label = QRY_CODE_UPDATE;
	$sql = query($qry_label,$qry_params);
	
	$result = sqlsrv_query($wvcs_dbcon, $sql);


}else if($proc=="DELETE"){


	$qry_params = array("code_seq"=>$code_seq);
	$qry_label = QRY_CODE_SUBCODE_COUNT;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);
	$row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

	if($row['cnt'] > 0){

		printJson($msg="[$proc]".$_LANG_TEXT["nodeletecode"][$lang_code],$data='',$status=false,$result,$wvcs_dbcon);

	}else{


		$qry_params = array("code_seq"=>$code_seq);
		$qry_label = QRY_CODE_DELETE;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);
	}

}


//printJson($msg=$sql);


if($result) {

	$status = true;
	$msg = $proc=="DELETE" ? "delete_ok" : "save_ok";

}else{
	
	$status = false;
	$msg = "proc_error";
}

printJson($msg,$data='',$status,$result,$wvcs_dbcon);
?>