<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

/* Description
* 파일 시그니처 매핑정보 가져오기
*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
//include  $_server_path . "/lib/dpt25_config.inc";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";
include "./common.php";

		//ALTER PROCEDURE [dbo].[up_GetSignatureMap]
		
		$sql_user = " EXEC dbo.up_GetSignatureMap  ";
		
		/*
		$sql_user = "	
						Select file_id,ext_name,str_name
						From tb_signature_map
					";

		*/
		//echo nl2br($sql_user);
		$result = @sqlsrv_query($wvcs_dbcon, $sql_user);
		while( $row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
			
			$file_id = $row["file_id"];
			$ext_name =  $row["ext_name"];
			$str_name = $row["str_name"];
			
			$data[] = array( "file_id"=> $file_id, "ext_name" => $ext_name, "str_name" => $str_name );
			
		}

		
		

		$data_result = array("data"=>$data);

		$json_data = json_encode($data_result);

		//echo $json_data;
		echo AES_Rijndael_Encript($json_data, $_AES_KEY, $_AES_IV);
		


?>