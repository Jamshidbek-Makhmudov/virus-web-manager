<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$_v_user_list_seq = $_POST['v_user_list_seq'];
$_item =  $_POST['item'];
$_proc = $_POST['proc'];

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'UPDATE');

$Model_User = new Model_User();
$args = array("v_user_list_seq"=>$_v_user_list_seq);

if($_item=="pass"){

	if($_proc=="return"){
		$result = $Model_User->updateReturnTempopraryProc($args);
	}else{
		$result = $Model_User->cancelReturnTempopraryProc($args);
	}

}else if($_item=="usb"){

	if($_proc=="return"){
		$result = $Model_User->updateReturnUsbProc($args);
	}else{
		$result = $Model_User->cancelReturnUsbProc($args);
	}
// $Model_User->SHOW_DEBUG_SQL = true;
} else if ($_item == "goods") {
	/*자산반입 회수처리*/

	if($_proc=="out"){
		$result = $Model_User->updateOutGoodsProc($args);
	}else{
		$result = $Model_User->cancelOutGoodsProc($args);
	}
}


if($result){
	printJson_OK('proc_ok');
}else{
	printJson_ERROR('proc_error');
}
?>