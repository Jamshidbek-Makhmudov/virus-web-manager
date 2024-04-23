<?php
$page_name = "kabang_emp_list";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$emp_seq = $_POST["emp_seq"];

$proc_name = $_POST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,"UPDATE");

$menu_auth = array();
foreach($_PAGE as $cate=>$menu){

	if($cate=="MAIN") continue;

	$menu_code = $menu['MENU_CODE'];

	$checked_pagelist = $_POST["page_auth_{$menu_code}"];

	if(is_array($checked_pagelist)==true){
		foreach($checked_pagelist as $page_code){
			$checked_exec_auth = $_POST["exec_auth_".$menu_code."_".$page_code];
			$str_exec_auth = implode(",",$checked_exec_auth);
			$menu_auth[$menu_code][$page_code] = $str_exec_auth;
		}
	}

}

$arr_emp_seq = explode(",",$emp_seq);	//일괄설정인 경우

for($i = 0 ; $i < count($arr_emp_seq) ; $i++){

	$_emp_seq = $arr_emp_seq[$i];

	$Model_manage = new Model_manage();
	$args = array("emp_seq"=>$_emp_seq, "menu_auth"=>$menu_auth);
	$result = $Model_manage->saveEmpMenuDetailAuth($args);

	if(!$result){
		printJson_ERROR("proc_error");
	}

}

printJson_OK("save_ok");
?>