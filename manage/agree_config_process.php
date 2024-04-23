<?php
$page_name = "agree_list";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$_agree_config_seq = $_POST["agree_config_seq"];
$_agree_div = $_POST["agree_div"];
$_agree_title_enc = $_POST["agree_title_enc"];
$_agree_content_enc = $_POST["agree_content_enc"];
$_agree_bottom_enc = $_POST["agree_bottom_enc"];
$_agree_lang = $_POST["agree_lang"];
$_request_consent_yn  = $_POST["request_consent_yn"];
$_use_yn = $_POST["use_yn"];

if($_request_consent_yn=="") $_request_consent_yn = "N";

$proc_name = $_REQUEST[proc_name];
$proc = ($_agree_config_seq > 0) ? "UPDATE" : "CREATE";
$work_log_seq = WriteAdminActLog($proc_name,$proc);

$Model_manage = new Model_manage();

$args = array("agree_config_seq"=>$_agree_config_seq
	,"agree_div"=>$_agree_div
	,"agree_title"=>htmlentities(base64_decode($_agree_title_enc),ENT_QUOTES)
	,"agree_content"=>htmlentities(base64_decode($_agree_content_enc),ENT_QUOTES)
	,"agree_bottom"=>htmlentities(base64_decode($_agree_bottom_enc),ENT_QUOTES)
	,"agree_lang"=>$_agree_lang
	,"request_consent_yn"=>$_request_consent_yn
	,"use_yn"=>$_use_yn
);

if($_agree_config_seq > 0){
	$result = $Model_manage->updateAgreeContent($args);
}else{
	$_agree_config_seq = $Model_manage->registAgreeContent($args);
	$result = ($_agree_config_seq > 0);
}

if($result){
	printJson_OK('save_ok',$_agree_config_seq);
}else{
	printJson_OK('proc_error');
}
?>