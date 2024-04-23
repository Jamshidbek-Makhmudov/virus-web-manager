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
$g_name = $_POST['g_name']; 
$g_mgt_no = $_POST['g_mgt_no']; 
$g_doc_no = $_POST['g_doc_no']; 
$g_model = $_POST['g_model']; 
$g_sn = $_POST['g_sn']; 
$g_out_schedule_date = $_POST['g_out_schedule_date']; 
$g_memo = $_POST['g_memo'];  

if($v_user_list_goods_seq=="") printJson_ERROR('invalid_data');

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'UPDATE');

$Model_User = new Model_User();
$args = array("v_user_list_goods_seq" => $v_user_list_goods_seq
	,"g_name" => $g_name
	,"g_mgt_no" => $g_mgt_no
	,"g_doc_no" => $g_doc_no
	,"g_model" => $g_model
	,"g_sn" => $g_sn
	,"g_out_schedule_date" => $g_out_schedule_date
	,"g_memo" => $g_memo
);
$result = $Model_User->updateUserImportGoods($args);

if($result){
	printJson_OK('save_ok');
}else{
	printJson_ERROR('proc_error');
}
?>





