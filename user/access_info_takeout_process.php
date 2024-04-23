<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$v_user_list_goods_seq = intVal($_REQUEST["v_user_list_goods_seq"]);
$proc = $_REQUEST["proc"];

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'UPDATE');

$Model_User = new Model_User();

if ($proc == "TAKE_OUT_PROCESS") {
	//자산반입정보->반출처리
	$args = array("v_user_list_goods_seq" => $v_user_list_goods_seq);
	$result = $Model_User->updateTakeOutProc($args);


} else if ($proc == "TAKE_OUT_CANCELATION") {
		
	$args = array("v_user_list_goods_seq" => $v_user_list_goods_seq);
	$result = $Model_User->cancelTakeOutProc($args);


}

if($result){
	printJson_OK('proc_ok');
}else{
	printJson_ERROR('proc_error');
}
?>