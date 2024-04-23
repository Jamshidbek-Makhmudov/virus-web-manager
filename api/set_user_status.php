<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

/*
Description : 방문자 반출입정보 업체 연동
*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
include  $_server_path . "/lib/dpt25_config.inc";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";
include "./common.php";


//		$visitor_id =  AES_Rijndael_Decript(base64_decode($_REQUEST['visitor_id']), $_AES_KEY, $_AES_IV);
		$visit_num =  AES_Rijndael_Decript(base64_decode($_REQUEST['visit_num']), $_AES_KEY, $_AES_IV);
		$visit_dev_num =  AES_Rijndael_Decript(base64_decode($_REQUEST['visit_dev_num']), $_AES_KEY, $_AES_IV);
		$vcs_status =  AES_Rijndael_Decript(base64_decode($_REQUEST['vcs_status']), $_AES_KEY, $_AES_IV);
		

		if($vcs_status=="IN") {
			$vcs_status_code = "2";
		}else if($vcs_status=="OUT"){
			$vcs_status_code = "4";
		}else if($vcs_status=="CHECK"){
			$vcs_status_code = "6";
		}


		$company_code =  $_REQUEST["company_code"]; 
		if($company_code == "") {
			$company_code = COMPANY_CODE;	
		}

		if( $company_code == "50") {
			if( gethostname() == "dataprotecs" ) {
				include  "set_user_status_devel.php";
			}else{
				include  "set_user_status_sksiltron.php";
			}
		}else {

			//include  "set_user_status_devel.php";
		}




?>