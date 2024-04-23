<?php
$page_name = "access_control_idc";

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";


$v_user_list_seq = $_POST['v_user_list_seq']; 
$v_user_type = $_POST['v_user_type']; 
$v_user_name = $_POST['v_user_name']; 
$v_user_name_en = $_POST['v_user_name_en']; 
$v_user_belong = $_POST['v_user_belong']; 
$v_phone = $_POST['v_phone']; 
$elec_doc_number = $_POST['elec_doc_number']; 
$work_number = $_POST['work_number']; 
$memo = $_POST['memo']; 
$proc = $_POST['proc']; 

$proc_name = $_REQUEST[proc_name];
$work_log_seq =	WriteAdminActLog($proc_name,$proc);

$Model_User = new Model_User();

if($proc=="DELETE"){

	$args = array("v_user_list_seq"=>$v_user_list_seq);

	$result = $Model_User->deleteUserVisitInfo($args);

	if($result){
		printJson_OK('delete_ok');
	}else{
		printJson_ERROR('proc_error');
	}

}else{

	//IDC 출입정보 상세 업데이트하기
	$args = array("v_user_type" => $v_user_type,"v_user_list_seq" => $v_user_list_seq,"v_user_name" => $v_user_name,"v_user_name_en" => $v_user_name_en,"v_user_belong" => $v_user_belong,"v_phone" => $v_phone,"elec_doc_number" => $elec_doc_number,"work_number"=>$work_number,"memo"=>$memo);

	$result = $Model_User->updateVisitUser($args);
	$result = $Model_User->updateVisitUserList_IDC($args);
	$result = $Model_User->updateVisitUserListInfo($args);
	$result = $Model_User->updateVisitUserListWorkInfo($args);

	if($result){
		printJson_OK('save_ok');
	}else{
		printJson_ERROR('proc_error');
	}

}
?>