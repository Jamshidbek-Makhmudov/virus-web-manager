<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

/*
Description : 방문자 데이터 모두 삭제(v_user_seq)
*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];
/*
*DPT25 DB 암호화방식을 aes256을 사용할 경우 반드시 dpt25_config.inc 파일을 참조해야 한다.  --open key설정..
*/
include  $_server_path . "/lib/dpt25_config.inc";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include "./common.php";

		/*
		 ALTER PROCEDURE [dbo].[up_UserDataDelete]
			@v_user_seq integer
		 */
		
		//$v_user_seq =  AES_Rijndael_Decript( base64_decode($_REQUEST['v_user_seq']) , $_AES_KEY, $_AES_IV);
		$raw_value = $_POST['json'];
		$str_value = unQuotChars($raw_value);
		$json_value = json_decode($str_value, true);
		
		$v_user_seq =  AES_Rijndael_Decript($json_value['v_user_seq'], $_AES_KEY, $_AES_IV);
		

		
		$sql_user = " EXEC dbo.up_UserDataDelete  $v_user_seq ";


		
		$result = @sqlsrv_query($wvcs_dbcon, $sql_user);

		if ($result === false) {
			//die(print_r(sqlsrv_errors(), true));
			$RESULT = "FALSE:ERROR";
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