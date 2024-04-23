<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

/* Description
*  앱(백신) 업데이트 로그 기록하기
*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
//include  $_server_path . "/lib/dpt25_config.inc";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";
include "./common.php";

		
		/*
		ALTER PROCEDURE [dbo].[up_InsertAppUpdateLog]
		@kiosk_id varchar(50),
		@app_name nvarchar(50),
		@ver varchar(50),
		@file_path nvarchar(300),
		@update_time varchar(14)
		*/
		/*
		$kiosk_id =  AES_Rijndael_Decript( base64_decode($_REQUEST['kiosk_id']) , $_AES_KEY, $_AES_IV);
		$app_name =  AES_Rijndael_Decript( base64_decode($_REQUEST['app_name']) , $_AES_KEY, $_AES_IV);
		$ver =  AES_Rijndael_Decript( base64_decode($_REQUEST['ver']) , $_AES_KEY, $_AES_IV);
		$file_path =  AES_Rijndael_Decript( base64_decode($_REQUEST['file_path']) , $_AES_KEY, $_AES_IV);
		$update_time =  AES_Rijndael_Decript( base64_decode($_REQUEST['update_time']) , $_AES_KEY, $_AES_IV);
		*/

		$raw_value = $_POST['json'];
		$str_value = unQuotChars($raw_value);
		$json_value = json_decode($str_value, true);
		
		$kiosk_id =  AES_Rijndael_Decript($json_value['kiosk_id'], $_AES_KEY, $_AES_IV);
		$app_name =  AES_Rijndael_Decript($json_value['app_name'], $_AES_KEY, $_AES_IV);
		$ver      =  AES_Rijndael_Decript($json_value['ver'], $_AES_KEY, $_AES_IV);
		$file_path =  AES_Rijndael_Decript($json_value['file_path'], $_AES_KEY, $_AES_IV);
		$update_time =  AES_Rijndael_Decript($json_value['update_time'], $_AES_KEY, $_AES_IV);
		$end_time =  AES_Rijndael_Decript($json_value['end_time'], $_AES_KEY, $_AES_IV);
		$result =  $json_value['result']=="" ? "" : AES_Rijndael_Decript($json_value['result'], $_AES_KEY, $_AES_IV);
		$result_msg =  $json_value['result_msg']=="" ? "" : AES_Rijndael_Decript($json_value['result_msg'], $_AES_KEY, $_AES_IV);
		$app_seq =  AES_Rijndael_Decript($json_value['app_seq'], $_AES_KEY, $_AES_IV);

		$sql_user = " EXEC dbo.up_InsertAppUpdateLog  '$kiosk_id', '$app_name', '$ver', '$file_path', '$update_time', '$end_time' ,'$result' , '$result_msg' , '$app_seq' ";	

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