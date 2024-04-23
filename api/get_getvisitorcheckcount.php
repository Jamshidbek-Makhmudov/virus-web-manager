<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

/* Description
* VCS 파일검사여부(카운트) 가져오기
*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
//include  $_server_path . "/lib/dpt25_config.inc";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";
include "./common.php";

		
		//ALTER PROCEDURE [dbo].[up_GetVisitorCheckCount](@visitor_Id varchar(50), @phone_num_enc varchar(100)='')
		//$VISITOR_ID =  AES_Rijndael_Decript( base64_decode($_REQUEST['visitor_Id']) , $_AES_KEY, $_AES_IV);
		//$PHONE_NUM_ENC =  AES_Rijndael_Decript( base64_decode($_REQUEST['phone_num_enc']) , $_AES_KEY, $_AES_IV);

		$raw_value = $_POST['json'];
		$str_value = unQuotChars($raw_value);
		$json_value = json_decode($str_value, true);
		
		$VISITOR_ID =  AES_Rijndael_Decript($json_value['visitor_Id'], $_AES_KEY, $_AES_IV);
		$PHONE_NUM_ENC =  AES_Rijndael_Decript($json_value['phone_num_enc'], $_AES_KEY, $_AES_IV);

		
		$sql_user = " EXEC dbo.up_GetVisitorCheckCount  '$VISITOR_ID', '$PHONE_NUM_ENC'  ";
		/*
		$sql_user = "	
						Declare @CHECKIN_TERM int;

						SELECT top 1 @CHECKIN_TERM=checkin_kiosk_term
						FROM   tb_policy
						ORDER BY policy_seq desc

						
						if ('$VISITOR_ID' ='') 
						begin
							SELECT count(*) CNT 
							FROM tb_v_user u
								inner join tb_v_wvcs_info w ON u.v_user_seq = w.v_user_seq
							WHERE u.v_phone= '$PHONE_NUM_ENC' 
								and dateadd( HOUR, @CHECKIN_TERM, w.wvcs_dt) > getdate()
						end
						else 
						begin
							SELECT count(*) CNT 
							FROM tb_v_user u
								inner join tb_v_wvcs_info w ON u.v_user_seq = w.v_user_seq
							WHERE u.visitor_id='$VISITOR_ID'
								and dateadd( HOUR, @CHECKIN_TERM, w.wvcs_dt) > getdate()
						end;
					";
		*/
		//echo nl2br($sql_user);
		$result = @sqlsrv_query($wvcs_dbcon, $sql_user);
		while( $row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
			
			$CNT = $row["CNT"];
			
			
		}
		if($CNT = "") {
			$CNT = 0;
		}
		
		$data = array( "CNT"=> $CNT);

		$json_data = json_encode($data);

		echo $json_data;
		//echo AES_Rijndael_Encript($json_data, $_AES_KEY, $_AES_IV);
		


?>