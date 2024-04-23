<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

/* Description
*  방문자 vcs 반입파일정보 저장
*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
//include  $_server_path . "/lib/dpt25_config.inc";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";
include "./common.php";





		/*
		ALTER PROCEDURE [dbo].[up_InsertUserImportFileArray]              
		 @v_wvcs_seq bigint,        
		 @file_send_status varchar(1),       
		 @array_file dbo.[fileIndexArray] READONLY -- 테이블 값 파라미터 선언     
		 
		 */
		$raw_value = $_POST['json'];
		$str_value = unQuotChars($raw_value);
		$json_value = json_decode($str_value, true);
		
		$v_wvcs_seq =  AES_Rijndael_Decript($json_value['v_wvcs_seq'], $_AES_KEY, $_AES_IV);
		$file_send_status =  AES_Rijndael_Decript($json_value['file_send_status'], $_AES_KEY, $_AES_IV);
		if($json_value['copy_device_instance_path'] != ""){
			$copy_device_instance_path =  AES_Rijndael_Decript($json_value['copy_device_instance_path'], $_AES_KEY, $_AES_IV);
		}
		$array_file =  AES_Rijndael_Decript($json_value['array_file'], $_AES_KEY, $_AES_IV);


		$arr_file = json_decode($array_file, true);

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
			
		$sql_user .= " 
					EXEC dbo.up_InsertUserImportFileArray  '$v_wvcs_seq', '$file_send_status'
						, @array_file = @pCodes, @copy_device_instance_path= '$copy_device_instance_path'
					";

		//echo $sql_user;

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

        //$RESULT = "FALSE:ERROR";
		echo $RESULT;

		@sqlsrv_free_stmt($result);  
		@sqlsrv_close($wvcs_dbcon);
		
		//$data = array( "RESULT"=> $RESULT);

		//$json_data = json_encode($data);

		//echo $json_data;
		//echo AES_Rijndael_Encript($json_data, $_AES_KEY, $_AES_IV);

?>