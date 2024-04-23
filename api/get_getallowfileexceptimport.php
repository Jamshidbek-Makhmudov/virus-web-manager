<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");


/* Description
* 방문자 파일예외반입신청허용정보 가져오기
*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
//include  $_server_path . "/lib/dpt25_config.inc";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";
include "./common.php";

		//getallowfileexceptimport 프로시져 대체
		/*
		ALTER PROCEDURE [dbo].[up_GetAllowFileExceptImport]
				@v_user_list_seq bigint
			AS
		*/
		//$V_USER_LIST_SEQ =  AES_Rijndael_Decript( base64_decode($_REQUEST['v_user_list_seq']) , $_AES_KEY, $_AES_IV);

		$raw_value = $_POST['json'];
		$str_value = unQuotChars($raw_value);
		$json_value = json_decode($str_value, true);

		$V_USER_LIST_SEQ =  AES_Rijndael_Decript($json_value['v_user_list_seq'], $_AES_KEY, $_AES_IV);

		$sql_user = "EXEC up_GetAllowFileExceptImport  {$V_USER_LIST_SEQ} ";

		//$sql_user = "EXEC up_GetAllowFileExceptImport  652";

		$result = @sqlsrv_query($wvcs_dbcon, $sql_user);

		while( $row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {

			$md5 = $row["md5"];
			$sha256 =  $row["sha256"];
			$file_send_status = $row["file_send_status"];

			$data[] = array( "md5"=> $md5, "sha256" => $sha256, "file_send_status" => $file_send_status );

		}

		$data_result = array("data"=>$data);

		$json_data = json_encode($data_result);

		//echo $json_data;
		
		echo AES_Rijndael_Encript($json_data, $_AES_KEY, $_AES_IV);

?>