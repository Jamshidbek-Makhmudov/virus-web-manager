<?php
$page_name = "document_list";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$Model_manage = new Model_manage();

$p_code_seq = $_POST["p_code_seq"];
$code_seq = $_POST["code_seq"];
$code_name = $_POST["code_name"];
$code_key = $_POST["code_key"];
$sort = $_POST['sort'];
$use_yn = $_POST['use_yn'];

$proc_name = $_POST["proc_name"];
$proc_exec = $_POST["proc_exec"];
$work_log_seq  = WriteAdminActLog($proc_name, $proc_exec);
$login_emp_seq = $_ck_user_seq;


$args = compact("code_seq", "code_key", "code_name", "sort", "use_yn", "p_code_seq", "login_emp_seq");
$proc_result = "save_ok";

if($proc_exec == "CREATE"){
	$code_seq = $Model_manage->createDocumentChecklist($args);
	$result = ($code_seq > 0);
}else if($proc_exec=="UPDATE"){
	$result = $Model_manage->updateDocumentChecklist($args);
}else if($proc_exec=="DELETE"){
	$proc_result = "delete_ok";
	$result = $Model_manage->deleteDocumentChecklist($args);
}

if($result){
	printJson_OK($proc_result, $code_seq);
}else{
	printJson_OK('proc_error');
}
?>