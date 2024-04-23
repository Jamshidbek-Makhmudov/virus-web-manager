<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header('Authorization: Basic bm90ZWJvb2s6OTI1YmEyYTQtMmRhZi00NzYyLTk0ODAtMjgyNWM5MzFlMTI2');
header('Content-Type: application/json;charset=UTF-8');
header('Content-Type:text/html;charset=UTF-8');

/* Description
*  VCS 검사결과 저장하기
*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
//include  $_server_path . "/lib/dpt25_config.inc";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";
include "./common.php";


//$visitor_id =  $_REQUEST['visitor_id'];

$company_code =  $_REQUEST["company_code"]; 
if($company_code == "") {
	$company_code = COMPANY_CODE;	
}

	if( $company_code == "19") { //포스코
		include  "save_scan_result_posco.php";
	}else {
		include  "save_scan_result_comm.php";
	}
?>

