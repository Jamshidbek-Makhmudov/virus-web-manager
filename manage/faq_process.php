<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$proc = $_POST['proc'];
$faq_seq = $_POST['f_seq'];
$gubun = $_POST['f_gubun'];
$title = $_POST['f_title'];
$contents = $_POST['f_contents'];

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,$proc);

$title = str_replace("'", "''", $title);
$title = str_replace("\\", "", $title);

$contents = str_replace("'", "''", $contents);
$contents = str_replace("\\", "", $contents);



$emp_seq = $_ck_user_seq;
//$ip = $_SERVER['REMOTE_ADDR'];

if($proc == "CREATE") {

	$qry_params = array("gubun"=>$gubun,"title"=>$title,"contents"=>$contents,"emp_seq"=>$emp_seq);
	$qry_label = QRY_FAQ_INSERT;
	$sql = query($qry_label,$qry_params);
	
	$result = sqlsrv_query($wvcs_dbcon, $sql);

} else if ($proc == "UPDATE") {
	
	
	$qry_params = array("faq_seq"=>$faq_seq,"title"=>$title,"contents"=>$contents,"emp_seq"=>$emp_seq);
	$qry_label = QRY_FAQ_UPDATE;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

} else if ($proc == "DELETE") {

	$qry_params = array("faq_seq"=>$faq_seq);
	$qry_label = QRY_FAQ_DELETE;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);
}

if($result) {
	$msg = "[$proc] ".$_LANG_TEXT['procsuccess'][$lang_code];
	$status = true;
}else{
	$msg =  "[$proc] ".$_LANG_TEXT['procfail'][$lang_code];
	$status = false;
}
printJson($msg,$data='',$status,$result,$wvcs_dbcon);
?>