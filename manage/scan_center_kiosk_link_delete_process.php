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

$link_kiosk_seq = $_POST["link_kiosk_seq"];
$link_checked = $_POST["link_checked"];
$link_name = $_POST["link_name"];
$link_url = $_POST["link_url"];

if($link_name == "" || $link_url == "" || $link_kiosk_seq=="") {
	printJson_ERROR('invalid_data');
}

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DELETE');

$Model_manage = new Model_manage();

for($i = 0 ; $i < sizeof($link_kiosk_seq) ; $i++){

	$kiosk_seq = $link_kiosk_seq[$i];
	
	$args = array("kiosk_seq"=>$kiosk_seq);
	
	for($j = 0 ; $j < sizeof($link_checked); $j++){

		if($link_checked[$j] !="Y") continue;

		$str_link_name = $link_name[$j];
		$str_link_url = $link_url[$j];
		
		$Model_manage->SHOW_DEBUG_SQL = false;
		$args = array("kiosk_seq"=>$kiosk_seq,"link_name"=>$str_link_name,"link_url"=>$str_link_url);
		$result = $Model_manage->deleteKioskLink($args);

		if(!$result) printJson_ERROR('proc_error');
			
	}	

}

printJson_OK('delete_ok');
?>