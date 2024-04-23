<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$v_user_list_seq = intVal($_REQUEST["v_user_list_seq"]);
$proc = $_REQUEST["proc"];

if($v_user_list_seq == "" || $proc==""){
	printJson_ERROR('invalid_data');
}

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'UPDATE');

$Model_User = new Model_User();

if ($proc == "VISIT_IN_PROC") {	//입실처리

	$args = array("v_user_list_seq" => $v_user_list_seq, "visit_status"=>"1", "memo"=>$proc_name);
	$result = $Model_User->UpdateVisitInoutProc($args);


} else if ($proc == "VISIT_IN_PROC_CANCEL") {	//입실취소처리
		
	$args = array("v_user_list_seq" => $v_user_list_seq, "visit_status"=>"9","memo"=>$proc_name);
	$result = $Model_User->UpdateVisitInoutProc($args);

}else if($proc=="VISIT_OUT_PROC"){	//퇴실처리

	$args = array("v_user_list_seq" => $v_user_list_seq, "visit_status"=>"0","memo"=>$proc_name);
	$result = $Model_User->UpdateVisitInoutProc($args);

}else if($proc=="VISIT_OUT_PROC_CANCEL"){	//퇴실처리취소

	$args = array("v_user_list_seq" => $v_user_list_seq, "visit_status"=>"1","memo"=>$proc_name);
	$result = $Model_User->UpdateVisitInoutProc($args);

}

if($result){
	printJson_OK('proc_ok');
}else{
	printJson_ERROR('proc_error');
}
?>