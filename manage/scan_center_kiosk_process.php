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

$scan_center_code = $_POST["scan_center_code2"];
$kiosk_name= $_POST["kiosk_name"];
$kiosk_id= $_POST["kiosk_id"];
$kiosk_ip= $_POST["kiosk_ip"];
$kiosk_memo= $_POST["kiosk_memo"];
$kiosk_menu= $_POST["kiosk_menu"];
$kiosk_link = $_POST['kiosk_link'];

if($scan_center_code == "") {
	printJson_ERROR('invalid_data');
}

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'CREATE');

$Model_manage = new Model_manage();

//kiosk id 중복체크
if(count($kiosk_id) > 0){
	$str_kiosk_id = " '".implode("','",$kiosk_id)."' ";
	$args = array("scan_center_code"=>$scan_center_code,"kiosk_id"=>$str_kiosk_id);
	$result = $Model_manage->checkExistsKioskID($args);
	if($result){
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$exists_kiosk_id[] = $row[kiosk_id];
		}
		$str_exists_kiosk_id = implode(",",$exists_kiosk_id);
		$msg = trsLang('중복된데이타입니다','duplicatedatatext')." ( KIOSK ID : ".$str_exists_kiosk_id." )";
		printJson_ERROR($msg);
	}
}

$args = array("scan_center_code"=>$scan_center_code);
$result = $Model_manage->deleteScanCenterKiosk($args);

if(!$result) printJson_ERROR('proc_error');

for($i = 0 ; $i <sizeof($kiosk_id) ; $i++){
	
	$str_kiosk_id = $kiosk_id[$i];
	$str_kiosk_name = $kiosk_name[$i];
	$str_kiosk_ip = $kiosk_ip[$i];
	$str_kiosk_memo = $kiosk_memo[$i];
	$str_kiosk_menu = $kiosk_menu[$i];
	$str_kiosk_link = $kiosk_link[$i];

	$kioskData = array();

	$kioskData['scan_center_code'] = $scan_center_code;
	$kioskData['kiosk_id'] = $str_kiosk_id;
	$kioskData['kiosk_name'] = $str_kiosk_name;
	$kioskData['kiosk_ip_addr'] = $str_kiosk_ip;
	$kioskData['kiosk_menu'] = $str_kiosk_menu;
	$kioskData['memo'] = $str_kiosk_memo;


	$kiosk_seq = $Model_manage->saveScanCenterKiosk($kioskData);

	if($kiosk_seq==0) printJson_ERROR('proc_error');

	if($str_kiosk_link !=""){
		
		$json=json_decode(htmlspecialchars_decode($str_kiosk_link),true);

		for($j = 0 ; $j < sizeof($json) ; $j++){
			
			$str_link_name = $json[$j]['name'];
			$str_link_url = $json[$j]['url'];		


			if($str_link_name != "" && $str_link_url != ""){

				$linkData = array();
				$linkData['kiosk_seq'] = $kiosk_seq;
				$linkData['link_name'] = $str_link_name;
				$linkData['link_url'] = $str_link_url;
				
				//$Model_manage->SHOW_DEBUG_SQL = true;
				$result = $Model_manage->saveScanCenterKioskLink($linkData);	

				if(!$result) printJson_ERROR('proc_error');

			}

		}
		
	}
}

printJson_OK('save_ok');
?>