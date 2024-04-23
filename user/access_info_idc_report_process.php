<?php
$page_name = "access_control_idc";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI']) - 1);
$_apos = stripos($_REQUEST_URI, "/");
if ($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$Model_User = new Model_User();
$Model_User->SHOW_DEBUG_SQL = false;

{
	$v_user_list_seq = intVal($_REQUEST["v_user_list_seq"]);
	$user_doc_seq = intVal($_REQUEST["user_doc_seq"]);
	$doc_div = $_REQUEST["doc_div"];
	$doc_title = htmlentities(base64_decode($_REQUEST["doc_title_enc"]));
	$doc_content = base64_decode($_REQUEST["doc_content_enc"]);
}

{
	$proc_name = htmlentities(base64_decode($_REQUEST["proc_name"]));
	$proc_exec = $_REQUEST["proc_exec"];

	$work_log_seq = WriteAdminActLog($proc_name, $proc_exec);
}

if($proc_exec == "CREATE"){
	$create_emp_seq = $_ck_user_seq;
	$args = compact("v_user_list_seq", "doc_div", "doc_title", "doc_content", "create_emp_seq");

	$user_doc_seq = $Model_User->createUserVisitListReport_IDC($args);
	$result = ($user_doc_seq > 0);
} else if ($proc_exec == "UPDATE") {
	$args = compact("v_user_list_seq", "user_doc_seq", "doc_title", "doc_content");

	$result = $Model_User->updateUserVisitListReport_IDC($args);
}

if($result){
	printJson_OK('save_ok', $user_doc_seq);
}else{
	printJson_OK('proc_error');
}
?>