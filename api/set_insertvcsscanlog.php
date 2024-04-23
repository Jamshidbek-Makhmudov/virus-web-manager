<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

/* Description
*  방문자 vcs 검사결과로그 저장
*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
//include  $_server_path . "/lib/dpt25_config.inc";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";
include "./common.php";

		/*
		ALTER PROCEDURE [dbo].[up_InsertVcsScanLog]
			@v_wvcs_seq bigint
			,@event_div varchar(30)
			,@event_name varchar(30)
			,@event_time varchar(14)        
		 */
		/*
		$v_wvcs_seq =  AES_Rijndael_Decript( base64_decode($_REQUEST['v_wvcs_seq']) , $_AES_KEY, $_AES_IV);
		$event_div =  AES_Rijndael_Decript( base64_decode($_REQUEST['event_div']) , $_AES_KEY, $_AES_IV);
		$event_name =  AES_Rijndael_Decript( base64_decode($_REQUEST['event_name']) , $_AES_KEY, $_AES_IV);
		$event_time =  AES_Rijndael_Decript( base64_decode($_REQUEST['event_time']) , $_AES_KEY, $_AES_IV);
		*/

		$raw_value = $_POST['json'];
		$str_value = unQuotChars($raw_value);
		$json_value = json_decode($str_value, true);
		
		$v_wvcs_seq =  AES_Rijndael_Decript($json_value['v_wvcs_seq'], $_AES_KEY, $_AES_IV);
		$event_div =  AES_Rijndael_Decript($json_value['event_div'], $_AES_KEY, $_AES_IV);
		$event_name =  AES_Rijndael_Decript($json_value['event_name'], $_AES_KEY, $_AES_IV);
		$event_time =  AES_Rijndael_Decript($json_value['event_time'], $_AES_KEY, $_AES_IV);

		
		$sql_user = " EXEC dbo.up_InsertVcsScanLog  '$v_wvcs_seq', '$event_div', '$event_name', '$event_time' ";

		
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