<?php
$page_name = "access_ip_config";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$login_ip_mgt_seq = intVal($_REQUEST["login_ip_mgt_seq"]);
$ip_addr = $_REQUEST["ip_addr"];
$allow_id = $_REQUEST["allow_id"];
$memo = $_REQUEST["memo"];
$proc = $_REQUEST["proc"];
$proc_name = $_REQUEST["proc_name"];



$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,$proc);





if($proc == "CREATE" && $login_ip_mgt_seq <> "") {
	printJson($msg=$_LANG_TEXT['wrongdatatranstext'][$lang_code]);
} else if ( ($proc == "UPDATE" || $proc == "DELETE" ) && $login_ip_mgt_seq == "") {
	printJson($msg=$_LANG_TEXT['wrongdatatranstext'][$lang_code]);
}

if ($proc != "DELETE") {
	//IP 유효성체크
	if($ip_addr > ""){
		$check_ip = filter_Var($ip_addr, FILTER_VALIDATE_IP); 

		if(!$check_ip){
			$msg = $_LANG_TEXT['notvalidiptext'][$lang_code];
			printJson($msg);
		}
	}
}


if ($proc == "CREATE") {

	$qry_params = array("ip_addr"=>$ip_addr,"allow_id"=>$allow_id,"memo"=>$memo,"admin_seq"=>$_ck_user_seq); 
	$qry_label = QRY_LOGIN_IP_LIMIT_INSERT;
	$sql = query($qry_label,$qry_params);

	$result = @sqlsrv_query($wvcs_dbcon, $sql);

	if($result){
		
		$qry_params = array();
		$qry_label = QRY_COMMON_IDENTITY_ACCESS;
		$sql = query($qry_label,$qry_params);

		$result = @sqlsrv_query($wvcs_dbcon, $sql);

		$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		
		$login_ip_mgt_seq = $row['seq'];
	}
				
}else if ($proc == "UPDATE") {

	$qry_params = array("ip_addr"=>$ip_addr,"allow_id"=>$allow_id,"memo"=>$memo,"admin_seq"=>$_ck_user_seq,"login_ip_mgt_seq"=>$login_ip_mgt_seq);
	$qry_label = QRY_LOGIN_IP_LIMIT_UPDATE;
	$sql = query($qry_label,$qry_params);

	$result = @sqlsrv_query($wvcs_dbcon, $sql);

}else if ($proc == "DELETE") {

	# 삭제 로그정보
	$qry_params = array("login_ip_mgt_seq"=>$login_ip_mgt_seq);

	$qry_label = QRY_LOGIN_IP_LIMIT_SELECT_BY_SEQ;
	$sql = query($qry_label,$qry_params);
	$result = @sqlsrv_query($wvcs_dbcon, $sql);

	$log_contents = "";
	while($row=@sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
		$log_contents .= ($log_contents? ", ":"")."{".$row['ip_addr']." | ".$row['allow_id']." | ".$row['memo']."}";
	}

	$qry_params = array("login_ip_mgt_seq"=>$login_ip_mgt_seq,"Contents"=>$log_contents);
	$qry_label = QRY_LOGIN_IP_LIMIT_DELETE_BY_SEQ;
	$sql = query($qry_label,$qry_params);	
	$result = @sqlsrv_query($wvcs_dbcon, $sql);

}

if($result){
	$msg = $proc=="DELETE" ? "delete_ok" : "save_ok";
	printJson_OK($msg,$data=$login_ip_mgt_seq);
}else{
	printJson('proc_error');
}
?>