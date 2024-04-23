<?php

/*로그인인증체크 안함.(외부참조페이지경우)*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];

include $_server_path . "/" . $_site_path ."/lib/wvcs_config.inc"; 
include $_server_path . "/" . $_site_path ."/lib/lib.inc"; 
include $_server_path . "/" . $_site_path . "/lib/class/load.php";
include $_server_path . "/" . $_site_path ."/lib/password.php"; 
include $_server_path . "/" . $_site_path . "/inc/function.inc";
include $_server_path . "/" . $_site_path . "/inc/query.php";
include $_server_path . "/" . $_site_path . "/inc/code.inc";
?>