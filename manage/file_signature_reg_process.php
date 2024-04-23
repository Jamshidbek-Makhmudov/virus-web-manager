<?php
$page_name = "file_signature";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$sign_id_seq = intVal($_REQUEST["sign_id_seq"]);
$file_id = $_REQUEST["file_id"];
$str_name = $_REQUEST["str_name"];
//$create_date = $_REQUEST["create_date"];
$use_yn = $_REQUEST["use_yn"];
$proc = $_REQUEST["proc"];
$create_date=date("YmdHis");

$proc_name = $_REQUEST["proc_name"];
$work_log_seq = WriteAdminActLog($proc_name,$proc);

if($proc == "CREATE" && $sign_id_seq <> "") {
	printJson($msg=$_LANG_TEXT['wrongdatatranstext'][$lang_code]);
} else if ( ($proc == "UPDATE" || $proc == "DELETE" ) && $sign_id_seq == "") {
	printJson($msg=$_LANG_TEXT['wrongdatatranstext'][$lang_code]);
}

//validation
if ($proc != "DELETE") {
	//IP 유효성체크
	if($file_id == ""){
		 
			$msg = $_LANG_TEXT['file_id_validate'][$lang_code];
		printJson($msg);
		
	}else if($str_name==""){
			$msg = $_LANG_TEXT['file_sig_validate'][$lang_code];
		printJson($msg);

	}

}




//date("YmdHis");

if ($proc == "CREATE") {

	$qry_params = array("file_id"=>$file_id,"str_name"=>$str_name,"use_yn"=>$use_yn,"create_date"=>$create_date);  
	$qry_label = QRY_FILE_SIGNATURE_INSERT;
	$sql = query($qry_label,$qry_params);

	$qry_params = array();
	$qry_label = QRY_COMMON_IDENTITY_ACCESS;
	$sql .= query($qry_label,$qry_params);

	$result = @sqlsrv_query($wvcs_dbcon, $sql);
	
	if($result){
		
		@sqlsrv_next_result($result);
		@sqlsrv_fetch($result);
		$sign_id_seq = @sqlsrv_get_field($result, 0);
	}
				
}else if ($proc == "UPDATE") {

	$qry_params = array("file_id"=>$file_id,"str_name"=>$str_name,"use_yn"=>$use_yn,"sign_id_seq"=>$sign_id_seq,"create_date"=>$create_date);
	$qry_label = QRY_FILE_SIGNATURE_UPDATE;
	$sql = query($qry_label,$qry_params);

	$result = @sqlsrv_query($wvcs_dbcon, $sql);

}else if ($proc == "DELETE") {

// if ($proc == "DELETE") {
// 	if (!confirm($qdeleteconfirm[$lang_code])) {
// 		return false;
// 	}
// }

	
	

	# 삭제 로그정보
	$qry_params = array("sign_id_seq"=>$sign_id_seq);

	$qry_params = array("sign_id_seq"=>$sign_id_seq,"Contents"=>$log_contents);
	$qry_label = QRY_FILE_SIGNATURE_DELETE;
	$sql = query($qry_label,$qry_params);	
	$result = @sqlsrv_query($wvcs_dbcon, $sql);
		

}

if($result){
	$msg = $proc=="DELETE" ? "delete_ok" : "save_ok";
	printJson_OK($msg,$data=$sign_id_seq);
}else{
	printJson_ERROR('proc_error');
}
?>