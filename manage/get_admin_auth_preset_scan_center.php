<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$preset_seq = $_POST['preset_seq'];
$use_yn = "Y";

$Model_manage = new Model_manage();
$args = compact("preset_seq","use_yn");
$preset = $Model_manage->getAdminMenuAuthPreset($args);

if($preset){
	printJson_OK('ok', $preset);
}
?>