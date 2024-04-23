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

$v_user_list_seq = $_POST['v_user_list_seq']; 
$pass_card_no = $_POST['pass_card_no']; 
$pass_card_return_schedule_date = $_POST['pass_card_return_schedule_date']; 

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'UPDATE');

//임시출입증 발급정보 업데이트
$Model_User = new Model_User();

$args = array("pass_card_no" => $pass_card_no,"pass_card_return_schedule_date"=>$pass_card_return_schedule_date,
"v_user_list_seq" => $v_user_list_seq);

$result = $Model_User->updatepassCardInfo($args);

if($result){
	printJson_OK('save_ok');
}else{
	printJson_ERROR('proc_error');
}
?>