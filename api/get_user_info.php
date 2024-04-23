<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

/* Description
* 방문자 연동정보 가져오기
* DPT25 DB 암호화방식을 aes256을 사용할 경우 반드시 dpt25_config.inc 파일을 참조해야 한다.  --open key설정..
*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];
include  $_server_path . "/wvcs/lib/wvcs_config.inc";
include "./common.php";

$company_code =  $_REQUEST["company_code"]; 
if($company_code == "") {
	$company_code = COMPANY_CODE;	
}


	if( $company_code == "19") { //포스코(물품반입번호연동)
		
		if( gethostname() == "dataprotecs" ) {
			include  "get_user_info_posco_devel.php";
			//include  "get_user_info_posco_demo.php"; 
		}else{
			include  "get_user_info_posco.php";
		}

	}else if($company_code == "50") {	//실트론(아이디연동)
		
		if( gethostname() == "dataprotecs" ) {
			include  "get_user_info_sksiltron_devel.php";
		}else{
			include  "get_user_info_sksiltron.php";
		}
	
	}else if( $company_code == "91") {	//삼성전자 구미DX(전화번호연동)
		
		/*디비연동*/
		/*
		if( gethostname() == "dataprotecs" ) {
			include  "get_user_info_secdx.gm_devel.php";
		}else{
			include  "get_user_info_secdx.gm.php";
		}
		*/

		/*API 연동*/
		if( gethostname() == "dataprotecs" ) {
			include  "get_user_info_secdx.gm_devel_api.php";
		}else{
			include  "get_user_info_secdx.gm_api.php";
		}
	
	}else if($company_code=="600"){	//카카오뱅크
		
		include  "get_user_info_vcs.php";

	}else {
		include  "get_user_info_devel.php";
	}

?>