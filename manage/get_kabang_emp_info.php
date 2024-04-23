<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$emp_id = $_POST['emp_id'];

$args = array("emp_id"=>$emp_id);
$Model_manage = new Model_manage;
$result = $Model_manage->findKabangEmpInfoByID($args);

if($result){
	while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
		$data = array(
				"emp_id"=>$row['emp_id'],
				"emp_name"=>aes_256_dec($row['emp_name']),
				"dept_name"=>$row['dept_name'],
				"status"=>$row['status']
			);
	}
}else{
	printJson_ERROR('임직원 정보를 찾을 수 없습니다.');
}

printJson_OK('ok',$data);
?>