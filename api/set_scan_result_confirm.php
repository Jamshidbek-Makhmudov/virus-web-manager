<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

/*
Description : VCS 검사승인처리 및 pe 제작 여부,실물매체 반입여부 업데이트
*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
include  $_server_path . "/lib/dpt25_config.inc";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include "./common.php";

	$raw_value = $_POST['json'];
	$str_value = unQuotChars($raw_value);
	$json_value = json_decode($str_value, true);	
	
	$v_wvcs_seq =  AES_Rijndael_Decript($json_value['v_wvcs_seq'], $_AES_KEY, $_AES_IV);
	$make_winpe =  $json_value['make_winpe']=="" ? "" : AES_Rijndael_Decript($json_value['make_winpe'], $_AES_KEY, $_AES_IV);
	$device_in_flag =$json_value['device_in_flag']=="" ? "" :   AES_Rijndael_Decript($json_value['device_in_flag'], $_AES_KEY, $_AES_IV);

	if($make_winpe=="") $make_winpe = "0";		//PE제작 : 1 
	if($device_in_flag=="") $device_in_flag = "0";	//실물매체 반입(1), 미반입(0)

	$sql = "update tb_v_wvcs_info
				set wvcs_authorize_yn = 'Y'
					,wvcs_authorize_name =mngr_name
					,wvcs_authorize_dt = getdate()
				from tb_v_wvcs_info 
				where v_wvcs_seq = '{$v_wvcs_seq}' ; ";

	$sql .= "update tb_v_wvcs_info_detail
				set make_winpe = '{$make_winpe}'
					,device_in_flag = '{$device_in_flag}'
				where v_wvcs_seq = '{$v_wvcs_seq}' ; ";


	//echo nl2br($sql);
	//exit;
   
	$result = @sqlsrv_query($wvcs_dbcon, $sql);

	if(@sqlsrv_rows_affected($result) > 0){
		$RESULT = "TRUE:OK";
	}else{
		$RESULT = "FALSE:ERROR";
		writeLog($sql);
	}
	echo $RESULT;
?>