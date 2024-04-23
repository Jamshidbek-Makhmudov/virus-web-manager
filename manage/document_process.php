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

$form_seq   = $_POST["form_seq"];
$form_div   = $_POST["form_div"];
$form_lang  = $_POST["form_lang"];
$form_title = htmlentities(base64_decode($_POST["form_title_enc"]));
$form_content = base64_decode($_POST["form_content_enc"]);
$use_yn = $_POST["use_yn"];

$proc_name = htmlentities(base64_decode($_REQUEST[proc_name]));
$proc_exec = ($form_seq > 0) ? "UPDATE" : "CREATE";
$work_log_seq = WriteAdminActLog($proc_name, $proc_exec);

$args = array("form_seq"=>$form_seq
			, "form_div"=>$form_div
			, "form_title"=>$form_title
			, "form_content"=>$form_content
			, "form_lang"=>$form_lang
			, "use_yn"=>$use_yn
		);


if($form_seq > 0){
	$result = $Model_manage->updateDocumentContent($args);
}else{
	$form_seq = $Model_manage->registDocumentContent($args);
	$result = ($form_seq > 0);
}

if($result){
	printJson_OK('save_ok', $form_seq);
}else{
	printJson_OK('proc_error');
}
?>