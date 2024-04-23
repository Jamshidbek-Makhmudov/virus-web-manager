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

$sign_map_seq = intVal($_REQUEST["sign_map_seq"]);
$ext_name = $_REQUEST["ext_name"];
$file_id = $_REQUEST["file_id"];
$str_name = $_REQUEST["str_name"];
$search_flag = $_REQUEST["search_flag"];
$fake_check = $_REQUEST["fake_check"];
$proc = $_REQUEST["proc"];
$create_date=date("YmdHis");

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');

if($proc == "CREATE" && $sign_map_seq <> "") {
	printJson($msg=$_LANG_TEXT['wrongdatatranstext'][$lang_code]);
} else if ( ($proc == "UPDATE" || $proc == "DELETE" ) && $sign_map_seq == "") {
	printJson($msg=$_LANG_TEXT['wrongdatatranstext'][$lang_code]);
}

//validation
if ($proc != "DELETE") {
	//IP 유효성체크
	if($ext_name=="" || $file_id == ""||$str_name==""){
		 
			$msg = $_LANG_TEXT['inputvalidate'][$lang_code];
		printJson($msg);
		
	}
}
if ($proc == "CREATE") {

	$qry_params = array("ext_name"=>$ext_name,"file_id"=>$file_id,"str_name"=>$str_name,"search_flag"=>$search_flag,"fake_check"=>$fake_check,"create_date"=>$create_date);  
	$qry_label = QRY_SIGNATURE_MAPPING_INSERT;
	$sql = query($qry_label,$qry_params);

	$qry_params = array();
	$qry_label = QRY_COMMON_IDENTITY_ACCESS;
	$sql .= query($qry_label,$qry_params);

	$result = @sqlsrv_query($wvcs_dbcon, $sql);
	
	if($result){
		
		@sqlsrv_next_result($result);
		@sqlsrv_fetch($result);
		$sign_map_seq = @sqlsrv_get_field($result, 0);
	}
				
}else if ($proc == "UPDATE") {

	$qry_params = array("ext_name"=>$ext_name,"file_id"=>$file_id,"str_name"=>$str_name,"search_flag"=>$search_flag,"fake_check"=>$fake_check,"sign_map_seq"=>$sign_map_seq,"create_date"=>$create_date);
	$qry_label = QRY_SIGNATURE_MAPPING_UPDATE;
	$sql = query($qry_label,$qry_params);

	$result = @sqlsrv_query($wvcs_dbcon, $sql);

}else if ($proc == "DELETE") {

		// if ($proc == 'DELETE') {
		// if (!confirm($qdeleteconfirm[$lang_code])) {
		// 	return false;
		// }
		// }

	# 삭제 로그정보
	$qry_params = array("sign_map_seq"=>$sign_map_seq);

	$qry_params = array("sign_map_seq"=>$sign_map_seq,"Contents"=>$log_contents);
	$qry_label = QRY_SIGNATURE_MAPPING_DELETE;
	$sql = query($qry_label,$qry_params);	
	$result = @sqlsrv_query($wvcs_dbcon, $sql);

}

if($result){
	$msg = $proc=="DELETE" ? "delete_ok" : "save_ok";
	printJson_OK($msg,$data=$sign_map_seq);
}else{
	printJson_ERROR('proc_error');
}
?>