<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");


$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
//include  $_server_path . "/lib/dpt25_config.inc";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";
include "./common.php";


		$wvcs_seq =  $_REQUEST['vcskey'];
		$notebook_key =  $_REQUEST['pckey'];
		
		$wvcs_seq =  base64_decode($wvcs_seq);
		$notebook_key =  base64_decode($notebook_key);

		//echo $wvcs_seq.$notebook_key;

		//$notebook_key = AES_Rijndael_Decript($notebook_key, $_AES_KEY, $_AES_IV);
		$now_ymd = date("YmdHis");

		if($notebook_key <> "") {
				$sql_rs = "
									SELECT top 1 v_wvcs_seq
									FROM   tb_v_wvcs_info
									WHERE v_notebook_key='".$notebook_key."' AND checkin_available_dt >= '".$now_ymd."'
									ORDER BY v_wvcs_seq desc; ";
				//echo $sql_rs;

				$result = sqlsrv_query($wvcs_dbcon, $sql_rs);
				$wvcs_seq = 0;	
				while( $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
					$wvcs_seq = $row["v_wvcs_seq"];
				}


				echo $wvcs_seq;
		} else {

					$sql_complete = "UPDATE tb_v_wvcs_info
						  SET       wvcs_authorize_yn='Y', wvcs_authorize_name='".aes_256_enc('DPT')."', wvcs_authorize_date=getdate()
						  WHERE  v_wvcs_seq=$wvcs_seq ; ";
					//echo $sql_complete;

					if( sqlsrv_query($wvcs_dbcon, $sql_complete ) ) {
						echo "TRUE";
					}else{
						echo "FALSE";
					}
		}

		exit;

		/*
		$data = array("user_seq"=> $user_seq );
	
		$json_data = json_encode($data);

		//echo $json_data;
		echo AES_Rijndael_Encript($json_data, $_AES_KEY, $_AES_IV);
		*/

?>