<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

/* Description
*  방문자 VCS 검사파일 해시정보 업데이트    
*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
//include  $_server_path . "/lib/dpt25_config.inc";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";
include "./common.php";

		/*
		ALTER PROCEDURE [dbo].[up_UpdateUserScanFileArray]            
		@v_wvcs_seq bigint,        
		@array_file dbo.[fileIndexArray] READONLY -- 테이블 값 파라미터 선언          
		 */
		//$V_WVCS_SEQ =  AES_Rijndael_Decript( base64_decode($_REQUEST['v_wvcs_seq']) , $_AES_KEY, $_AES_IV);
		//$ARRAY_FILE =  AES_Rijndael_Decript( base64_decode($_REQUEST['array_file']) , $_AES_KEY, $_AES_IV);

		$raw_value = $_REQUEST['json'];
		
		$str_value = unQuotChars($raw_value);

		$json_value = json_decode($str_value, true);
		
		
		$V_WVCS_SEQ =  AES_Rijndael_Decript($json_value['v_wvcs_seq'], $_AES_KEY, $_AES_IV);
		$ARRAY_FILE =  AES_Rijndael_Decript($json_value['array_file'], $_AES_KEY, $_AES_IV);
		

		$arr_file = json_decode($ARRAY_FILE, true);

		$sql_user = " DECLARE @pCodes dbo.fileIndexArray ";

		for($i = 0; $i < count($arr_file); $i++) {

			$data = $arr_file[$i];

			$file_index = $data['idx'];
			$md5 = $data['md5'];
			$sha256 = $data['sha256'];
			$file_send_status = $data['file_send_status'];

			$sql_user .= " INSERT INTO @pCodes (file_index, md5,sha256 ,file_send_status ) 
						VALUES('{$file_index}', '{$md5}','{$sha256}',  '{$file_send_status}')
					";
		}

		$sql_user .= " EXEC dbo.up_UpdateUserScanFileArray  '$V_WVCS_SEQ', @array_file = @pCodes ";

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

		@sqlsrv_free_stmt($result);  
		@sqlsrv_close($wvcs_dbcon);


		//$data = array( "RESULT"=> $RESULT);

		//$json_data = json_encode($data);

		//echo $json_data;
		//echo AES_Rijndael_Encript($json_data, $_AES_KEY, $_AES_IV);

?>