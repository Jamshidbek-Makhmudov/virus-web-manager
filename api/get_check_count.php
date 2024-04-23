<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
//include  $_server_path . "/lib/dpt25_config.inc";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";
include "./common.php";


	$visitor_id =  AES_Rijndael_Decript(base64_decode($_REQUEST['visitor_id']), $_AES_KEY, $_AES_IV);
	$phone_num =  base64_decode($_REQUEST['phone_num']); //전화번호는 암호화되어 저장되어 복호화하지 않고 비교한다

	$company_code =  $_REQUEST["company_code"]; 
	if($company_code == "") {
		$company_code = COMPANY_CODE;	
	}

		
	//없으면 전체 정책으로 세팅

	$params = array( 
							 array($visitor_id, SQLSRV_PARAM_IN),
							 array($phone_num, SQLSRV_PARAM_IN),
						   );

	$result = sqlsrv_query($wvcs_dbcon, '{CALL up_GetVisitorCheckCount (?,?)}', $params);

	while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
			$check_cnt = $row['CNT'];				
	}


	$data = array("check_cnt"=> $check_cnt );

	$json_data = json_encode($data);

	//echo $json_data;
	echo AES_Rijndael_Encript($json_data, $_AES_KEY, $_AES_IV);

?>
