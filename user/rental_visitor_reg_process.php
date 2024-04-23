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

$memo = $_REQUEST['memo']; 
$v_user_list_seq = $_REQUEST['v_user_list_seq']; //출입관리
$rent_list_seq = $_REQUEST['rent_list_seq']; 
$ticket_list_seq = $_REQUEST['ticket_list_seq']; 
$train_seq = $_REQUEST['train_seq']; 

$proc = "UPDATE";

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,$proc);


$Model_User = new Model_User();

$json = array (
	 'msg' => "[$proc] ".$_LANG_TEXT['procfail'][$lang_code]
	,'data' => ""
	,'status' => false
);


if ($rent_list_seq ) {
// $Model_User->SHOW_DEBUG_SQL = true;
	$args = array("memo" => $memo, "rent_list_seq" => $rent_list_seq);
	$result = $Model_User->updateRentListMemo($args);

	$data_value="rent_list_seq";

}else if ($ticket_list_seq) {

	$args = array("memo" => $memo, "ticket_list_seq" => $ticket_list_seq);
	$result = $Model_User->updateParingListMemo($args);

	$data_value="ticket_list_seq";

}else if ($train_seq) {

	$args = array("memo" => $memo, "train_seq" => $train_seq);
	$result = $Model_User->updateTrainListMemo($args);

	$data_value="train_seq";

}else if ($v_user_list_seq) {

	$args = array("memo" => $memo, "v_user_list_seq" => $v_user_list_seq);
	$result = $Model_User->updateUserVistListMemo($args);

	$data_value="v_user_list_seq";

}

if($result){
	printJson_OK('save_ok');
}else{
	printJson_ERROR('proc_error');
}
?>





