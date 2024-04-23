<?php
$page_name = "access_control";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$v_user_list_goods_seq = $_POST['v_user_list_goods_seq']; 
$memo = $_POST['memo']; 

if($v_user_list_goods_seq=="") printJson_ERROR('invalid_data');
 
$proc_name = $_REQUEST[proc_names];
$work_log_seq = WriteAdminActLog($proc_name,'UPDATE');

$Model_User = new Model_User();
$args = array("memo" => $memo, "v_user_list_goods_seq" => $v_user_list_goods_seq);
$result = $Model_User->updateUserImportGoodsMemo($args);

//printJson($result);

if($result){
	printJson_OK('save_ok');
}else{
	printJson_ERROR('proc_error');
}
?>





