<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$id = $_REQUEST["id"];
$gubun = $_REQUEST["gubun"]; //user org dept


if($gubun == "org") { //기관일 경우
	include "./inc_org.php";
}
if($gubun == "dept") { //부서일 경우
	include_once "./inc_dept.php";
}
if($gubun == "user") { //사원일 경우
	include_once "./inc_user.php";
}
?>