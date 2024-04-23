<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

/* Description
*  방문자정보 등록
*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
//include  $_server_path . "/lib/dpt25_config.inc";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";
include "./common.php";

		$raw_value = $_POST['json'];
		$str_value = unQuotChars($raw_value);
		$json_value = json_decode($str_value, true);
		
		//$phone_num =  AES_Rijndael_Decript($json_value['phone_num'], $_AES_KEY, $_AES_IV);
		//$email = AES_Rijndael_Decript($json_value['email'], $_AES_KEY, $_AES_IV);
		/*파라메타값은 평문*/
		$phone_num =  preg_replace ('/ 0-9]*/s', '', $json_value['phone_num']);
		$email = $json_value['email'];
		$user_name = $json_value['user_name'];
		$company_name = $json_value['company_name'];
		$manager_name = $json_value['manager_name'];
		$manager_dept = $json_value['manager_dept'];
		$visitor_id = $json_value['visitor_id'];
		$v_com_seq = 0;
	
		if($phone_num==""){	//전화번호값은 Uniq key, 없으면 임의생성
			$phone_num = date("YmdHis");	
		}

		$sql_comp = "SELECT v_com_seq FROM tb_v_company WHERE v_com_name='".$company_name."' ";
		$result = sqlsrv_query($wvcs_dbcon, $sql_comp);

		while( $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
			$v_com_seq = $row["v_com_seq"];
		}

		if($v_com_seq == 0) {
			
			//1. 업체등록
			/* v_wvcs_seq, v_user_seq, v_notebook_key, checkin_available_dt, mngr_name, mngr_department, wvcs_dt, wvcs_success_yn, wvcs_authorize_yn, wvcs_authorize_name, wvcs_authorize_dt
			v_asset_type, memo_text, 	ip_addr, 	create_dt, org_name, wvcs_type			*/
			$sql_info = " INSERT INTO tb_v_company ( v_com_name, v_ceo_name, use_yn, create_dt )   
								VALUES ( N'".$company_name."', '', 'Y', getdate() ); 
								select SCOPE_IDENTITY() as id; ";
			
			$rscom = sqlsrv_query($wvcs_dbcon, $sql_info );
			sqlsrv_next_result($rscom);
			sqlsrv_fetch($rscom);
			$v_com_seq = sqlsrv_get_field($rscom, 0);
		}
			
		$sql_user = "INSERT INTO tb_v_user ( v_user_name, v_email, v_phone, use_yn, visitor_id, create_dt, v_com_seq,v_com_name ) 
					VALUES ( '".aes_256_enc($user_name)."', '".aes_256_enc($email)."', '".aes_256_enc($phone_num)."', 'Y', '".$visitor_id."', getdate(), '".$v_com_seq."', N'{$company_name}') ; 
					select SCOPE_IDENTITY() as id; ";

		$result = @sqlsrv_query($wvcs_dbcon, $sql_user );
		@sqlsrv_next_result($result);
		@sqlsrv_fetch($result);
		$user_seq = @sqlsrv_get_field($result, 0);

		if($user_seq > 0) {
			echo "TRUE:".$user_seq;
		}else{
			echo "FALSE:0";
		}

?>