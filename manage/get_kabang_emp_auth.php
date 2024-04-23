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

$emp_seq = $_REQUEST["emp_seq"];

if(!empty($emp_seq)) {
	$args = array("emp_seq"=>$emp_seq);

	$auth_info = array("org"=>array()
		, "scan_center"=>array()
		, "auth"=>array()
		, "menu"=>array());

	/*관리기관*/
	$query  = query(QRY_ADMIN_MNG_ORG, $args);
	$result = sqlsrv_query($wvcs_dbcon, $query);

	if($result){
		while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
			$auth_info["org"][] = $row['org_id'];
		}
	}

	/*관리스캔센터*/
	$query  = query(QRY_ADMIN_MNG_SCAN_CENTER, $args);
	$result = sqlsrv_query($wvcs_dbcon, $query);
		
	if($result){
		while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
			$auth_info["scan_center"][] = $row['scan_center_code'];
		}
	}
	/*메뉴권한*/

	/*메뉴정보*/
	$auth_info["auth"] = $Model_manage->getAdminMenuAuth($args);
	$auth_info["menu"] = $Model_manage->getAdminMenuCustomized($args);

	printJson_OK('ok',$auth_info);
} else {
	printJson_ERROR('임직원 정보를 찾을 수 없습니다.');
}
