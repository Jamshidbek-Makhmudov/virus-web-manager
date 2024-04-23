<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$rent_list_seq = intVal($_REQUEST["rent_list_seq"]);
$v_user_list_seq = intVal($_REQUEST["v_user_list_seq"]);//임시출입증발급
$proc = $_REQUEST["proc"];
$proc_name = $_REQUEST["proc_name"];
$work_log_seq = WriteAdminActLog($proc_name,'UPDATE');

$Model_User = new Model_User();

if ($proc == "RECOVERY") {//물품대여회수
	
	$args = array("rent_list_seq" => $rent_list_seq,"return_emp_seq" => $_ck_user_seq);
	$result = $Model_User->updateRentRecoveryList($args);

	$data_value="rent_list_seq"; 

} else if ($proc == "CANCELATION") {
		
	$args = array("rent_list_seq" => $rent_list_seq);
	$result = $Model_User->cancelRentCollection($args);

	$data_value="rent_list_seq"; 

} else if ($proc == "RETURN_PROCESS") {
	//임시출입증발급회수
	$args = array("v_user_list_seq" => $v_user_list_seq,"return_emp_seq" => $_ck_user_seq);
	$result = $Model_User->updateReturnTempopraryProc($args);

	$data_value="v_user_list_seq"; 

} else if ($proc == "RETURN_CANCELATION") {
		
	$args = array("v_user_list_seq" => $v_user_list_seq);
	$result = $Model_User->cancelReturnTempopraryProc($args);

	$data_value="v_user_list_seq"; 

}

if($result){
	printJson_OK('proc_ok');
}else{
	printJson_ERROR('proc_error');
}
?>