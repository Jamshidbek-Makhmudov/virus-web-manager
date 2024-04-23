<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

/* Description
* DPT 반입여부 체크
*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
//include  $_server_path . "/lib/dpt25_config.inc";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";
include "./common.php";


	$vol_sn =  AES_Rijndael_Decript(base64_decode($_REQUEST['vol_sn']), $_AES_KEY, $_AES_IV);

	$company_code =  $_REQUEST["company_code"]; 
	if($company_code == "") {
		$company_code = COMPANY_CODE;	
	}

	$sql = "SELECT count(*) as IN_CNT 
			   FROM  DPT_USER_INOUT_INFO 
			   WHERE INOUT_STATUS IN (2,3)
							AND user_key like '%_".$vol_sn."' ";
	$result = sqlsrv_query($dpt_dbcon, $sql);
	while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
			$check_cnt = $row['IN_CNT'];				
	}


	$data = array("carryin_cnt"=> $check_cnt );

	$json_data = json_encode($data);

	//echo $json_data;
	echo AES_Rijndael_Encript($json_data, $_AES_KEY, $_AES_IV);

?>
