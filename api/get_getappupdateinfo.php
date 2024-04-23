<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

/* Description
* 앱 업데이트정보 가져오기
*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
//include  $_server_path . "/lib/dpt25_config.inc";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";
include "./common.php";

		/*
		ALTER PROCEDURE [dbo].[up_GetAppUpdateInfo]      
		 @kiosk_id varchar(30),  
		 @app_name varchar(50)=''   --VCS, V3, ESET     
 		*/
		//$KIOSK_ID =  AES_Rijndael_Decript( base64_decode($_REQUEST['kiosk_id']) , $_AES_KEY, $_AES_IV);
		//$APP_NAME =  AES_Rijndael_Decript( base64_decode($_REQUEST['app_name']) , $_AES_KEY, $_AES_IV);

		$raw_value = $_POST['json'];
		$str_value = unQuotChars($raw_value);
		$json_value = json_decode($str_value, true);
		
		$KIOSK_ID =  AES_Rijndael_Decript($json_value['kiosk_id'], $_AES_KEY, $_AES_IV);
		$APP_NAME =  AES_Rijndael_Decript($json_value['app_name'], $_AES_KEY, $_AES_IV);

		
		$sql_user = " EXEC dbo.up_GetAppUpdateInfo  '$KIOSK_ID', '$APP_NAME'  ";

		
		$result = @sqlsrv_query($wvcs_dbcon, $sql_user);
		while( $row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
			
			$app_seq = $row['app_seq'];
			$app_name = $row["app_name"];
			$ver =  $row["ver"];
			$update_dt = $row["update_dt"];
			$server_path = $row["server_path"];
			$update_file_name = $row["update_file_name"];
			$update_file_name_org = $row["update_file_name_org"];

			$data[] = array("app_seq"=>$app_seq,"app_name"=> $app_name, "ver" => $ver, "update_dt" => $update_dt, "server_path" => $server_path, "update_file_name" => $update_file_name, "update_file_name_org" => $update_file_name_org );
		}


		$data_result = array("data"=>$data);

		$json_data = json_encode($data_result);


		//echo $json_data;
		echo AES_Rijndael_Encript($json_data, $_AES_KEY, $_AES_IV);
		


?>