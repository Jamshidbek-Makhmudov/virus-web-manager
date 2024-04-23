<?php
$page_name = "scan_center_list";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$kiosk_seq = $_POST["kiosk_seq"];


if($kiosk_seq == "") {
	printJson_ERROR('invalid_data');
}

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DELETE');

$Model_manage = new Model_manage();

$args = array("kiosk_seq"=>$kiosk_seq);
$result = $Model_manage->deleteKiosk($args);

if(!$result) printJson_ERROR('proc_error');

printJson_OK('delete_ok');
?>