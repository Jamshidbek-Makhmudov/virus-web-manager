<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

/* Description
*  VCS 사용자 seq 값 가져오기
*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
//include  $_server_path . "/lib/dpt25_config.inc";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";
include "./common.php";

		if($_REQUEST['phone_num']==""){
			$phone_num =  "";
		}else{
			//$phone_num =  base64_decode($_REQUEST['phone_num']);
			$phone_num = AES_Rijndael_Decript(base64_decode($_REQUEST['phone_num']) , $_AES_KEY, $_AES_IV);
		}

       //	$phone_num = aes_256_dec($phone_num);
       
		if($_REQUEST['visitor_id'] == ""){
			$visitor_id = "";
		}else{
			$visitor_id =  AES_Rijndael_Decript(base64_decode($_REQUEST['visitor_id']) , $_AES_KEY, $_AES_IV);
		}
		

		if($_REQUEST['email']==""){
			$email = "" ;
		}else{
			$email =  base64_decode($_REQUEST['email']);
		}

		$email = aes_256_dec($email);
		

		//실트론은 방문포탈에서 ID에 "_" 가 붙어서 넘어오기때문에, 여기서 붙여서 비교해야한다.
		if(COMPANY_CODE==50 && substr($visitor_id, 0,1) <> "_" ) {
			if( gethostname() == "dataprotecs" ) {
				//do nothing!
			}else{
				$visitor_id = "_".$visitor_id;
			}
		}

        //echo AES_Rijndael_Encript($phone_num, $_AES_KEY, $_AES_IV);

		if($phone_num <> "") {
			$sql_where = " WHERE v_phone = '".aes_256_enc($phone_num)."' ";
		}else if($visitor_id <> "") {
			$sql_where = " WHERE visitor_id = '{$visitor_id}' ";
		}else if($email <> "") {
			$sql_where = " WHERE v_email = '".aes_256_enc($email)."' ";
		}

		$sql_user = "SELECT v_user_seq,visitor_id
						  FROM  tb_v_user
						".$sql_where;
		
		$result = sqlsrv_query($wvcs_dbcon, $sql_user);
		$_user_seq = 0;	
		$_visitor_id = "";
		while( $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
			$_user_seq = $row["v_user_seq"];
			$_visitor_id = $row['visitor_id'];
		}
		
		//전화번호(Uniq key)는 동일한데 방문자 아이디가 다른 경우 방문자정보 업데이트
		if($_user_seq > 0){
			if($visitor_id <> $_visitor_id){
				$sql = "Update tb_v_user Set visitor_id = '{$visitor_id}' Where v_user_seq = '{$_user_seq}' ";
				@sqlsrv_query($wvcs_dbcon, $sql);
			}
		}

		$data = array("user_seq"=> $_user_seq );

		$json_data = json_encode($data);


		//echo $json_data;
		echo AES_Rijndael_Encript($json_data, $_AES_KEY, $_AES_IV);

?>