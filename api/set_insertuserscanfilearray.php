<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

/* Description
*  방문자 vcs 검사파일정보 저장
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

		 https://192.168.169.2:1443/wvcs/api/set_insertuserscanfilearray.php?json={"v_wvcs_seq":"4278","array_file":{["idx":"68ROc6F2O6BHhjTs7s3uTQ==","file_name":"BCpT/Zg4drBIiBmAHAUKng==","file_name_org":"T6vBtda6Zqx3+zIiJm7YIx1Kik60D0Z3MJeZytNaA9s=","file_path":"4Z5eNKyHcG75EuSmQzLxIQ==","file_size":"1ZOyfRsnIviO/XSwkZSEgg==","file_ext":"U3SDGUu3tmMDNr7p0ejnhQ==","file_create_date":"+OjVt8QNJEWfoEqOlTiDmE0nMxa8XbtAjpdD1hgY2M0=","file_update_date":"NabuQXOSqZ1U/yF2FOeCFLiwSeT4YHisP1O2Jj3uLNk=","file_type":"pRy6usQHd6riQXXrWM/sUpJVHSSapBDxzhGD4Ht7Tl8=","file_signature":"mhyvnwW+hPS7oE6yTDL33tXk1QUak9TXk9xoIjHhOoU=","file_id":"k/+RzB3RV5R0aO10IYJgMg==","file_scan_result":"QSaVfz2v9POvnM64c9jD+A==","md5":"G2hQJY95UeQ8vrDa66bH/Jr8JltwseTHCLV4A4m2QQjBip+YPtluUHMP5VsghnHv","sha256":"jj8SPIdVkKwap3odmLwE9b1rjlNOfD7OVA1ohu4zSL8pJuGagjDRctR5TaaOS/GW2WgfR8or2yFvPulGu1tk1udSWLrOly27kj6ZcKv5Cluk6TkN+FU/S5CDXYtbohiQ"]}}


		 */
		//$V_WVCS_SEQ =  AES_Rijndael_Decript( base64_decode($_REQUEST['v_wvcs_seq']) , $_AES_KEY, $_AES_IV);
		//$ARRAY_FILE =  AES_Rijndael_Decript( base64_decode($_REQUEST['array_file']) , $_AES_KEY, $_AES_IV);

		$raw_value = $_REQUEST['json'];
		
		$str_value = unQuotChars($raw_value);

		$json_value = json_decode($str_value, true);
		
		
		$V_WVCS_SEQ =  AES_Rijndael_Decript($json_value['v_wvcs_seq'], $_AES_KEY, $_AES_IV);
		$ARRAY_FILE =  AES_Rijndael_Decript($json_value['array_file'], $_AES_KEY, $_AES_IV);
		

		$arr_file = json_decode($ARRAY_FILE, true);


		$sql_user = " DECLARE @pCodes dbo.fileInfoArray 
					";

		for($i = 0; $i < count($arr_file); $i++) {

			$data = $arr_file[$i];

			$file_name = $data['file_name'];
			$file_name_org = str_replace("'","''",$data['file_name_org']);  

			$file_path = str_replace("/","\\",$data['file_path']);
			$file_path = str_replace("'","''",$file_path);  

			$file_ext = str_replace("'","''",$data['file_ext']); 

			$file_size = $data['file_size'];
			$file_create_date = $data['file_create_date'];
			$file_update_date = $data['file_update_date'];
			$file_type = $data['file_type'];
			$file_signature = $data['file_signature'];
			$file_id = $data['file_id'];
			$file_scan_result = $data['file_scan_result'];
			$md5 = $data['md5'];
			$sha256 = $data['sha256'];
			$file_index = $data['idx'];

			$sql_user .= " INSERT INTO @pCodes (file_name,file_name_org,file_path,file_size,file_ext
							,file_create_date,file_update_date      
							,file_type, file_signature
							, file_id,file_scan_result,md5,sha256 ,file_index ) 
						VALUES(N'{$file_name}',N'{$file_name_org}',N'{$file_path}','{$file_size}',N'{$file_ext}'
							,'{$file_create_date}','{$file_update_date}'      
							,N'{$file_type}', '{$file_signature}'
							, '{$file_id}','{$file_scan_result}','{$md5}','{$sha256}' ,'{$file_index}')
					";

		}
			

		$sql_user .= " EXEC dbo.up_InsertUserScanFileArray  '$V_WVCS_SEQ', @array_file = @pCodes ";

		
		//echo $sql_user;
		//$sql_user .= " EXEC dbo.up_InsertUserScanFileArray  '$V_WVCS_SEQ', '$ARRAY_FILE' ";

		
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