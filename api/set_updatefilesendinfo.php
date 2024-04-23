<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

/* Description
*  파일 서버 전송 결과 업데이트  
*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
//include  $_server_path . "/lib/dpt25_config.inc";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";
include "./common.php";

		/*
		ALTER PROCEDURE [dbo].[up_UpdateFIleSendInfo]
		@v_wvcs_seq bigint,
		@file_send_status varchar(1),
		@file_send_date varchar(14),
		@file_send_result_msg nvarchar(500)='',
		@refer varchar(10) ='KIOSK'	-- 웹에서 호출(WEB), 키오스크에서 호출(KIOSK)      
		 */
		 /*
		$v_wvcs_seq =  AES_Rijndael_Decript( base64_decode($_REQUEST['v_wvcs_seq']) , $_AES_KEY, $_AES_IV);
		$file_send_status =  AES_Rijndael_Decript( base64_decode($_REQUEST['file_send_status']) , $_AES_KEY, $_AES_IV);
		$file_send_date =  AES_Rijndael_Decript( base64_decode($_REQUEST['file_send_date']) , $_AES_KEY, $_AES_IV);
		$file_send_result_msg =  AES_Rijndael_Decript( base64_decode($_REQUEST['file_send_result_msg']) , $_AES_KEY, $_AES_IV);
		$refer =  AES_Rijndael_Decript( base64_decode($_REQUEST['refer']) , $_AES_KEY, $_AES_IV);
		*/
		$raw_value = $_POST['json'];
		$str_value = unQuotChars($raw_value);
		$json_value = json_decode($str_value, true);

		$v_wvcs_seq =  AES_Rijndael_Decript($json_value['v_wvcs_seq'], $_AES_KEY, $_AES_IV);
		$file_send_status =  AES_Rijndael_Decript($json_value['file_send_status'], $_AES_KEY, $_AES_IV);
		$file_send_date =  AES_Rijndael_Decript($json_value['file_send_date'], $_AES_KEY, $_AES_IV);
		$file_send_result_msg =  $json_value['file_send_result_msg']=="" ? "" : AES_Rijndael_Decript($json_value['file_send_result_msg'], $_AES_KEY, $_AES_IV);
		$refer =  $json_value['refer']=="" ? "" : AES_Rijndael_Decript($json_value['refer'], $_AES_KEY, $_AES_IV);

		$sql_user = " EXEC dbo.up_UpdateFIleSendInfo  '$v_wvcs_seq', '$file_send_status', '$file_send_date', '$file_send_result_msg', '$refer' ";

		$result = @sqlsrv_query($wvcs_dbcon, $sql_user);

		if ($result === false) {
			//die(print_r(sqlsrv_errors(), true));
			$RESULT = "FALSE:ERROR";
			writeLog($sql_user);
		} else {
			//echo "<br>쿼리는 정상 실행됨<br>";
			$RESULT = "TRUE:OK";
			while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
				$RESULT = $row["RESULT"];
			}

		}

		echo $RESULT;

		//$data = array( "RESULT"=> $RESULT);

		//$json_data = json_encode($data);

		//echo $json_data;
		//echo AES_Rijndael_Encript($json_data, $_AES_KEY, $_AES_IV);

?>