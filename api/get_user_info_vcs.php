<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

/* Description
*  VCS 출입/검사 사용자정보 가져오기
*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
include_once  $_server_path . "/".$_site_path."/lib/lib.inc";
include_once  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";
include_once "./common.php";

		
		$v_user_list_seq =  AES_Rijndael_Decript(base64_decode($_REQUEST['v_user_list_seq']), $_AES_KEY, $_AES_IV);
		
		$sql_user = "
				Select	v1.v_user_seq,v1.v_email,v1.v_phone,v2.v_user_name,v2.v_company,v2.manager_name,v2.manager_name_en,v2.manager_dept
				From tb_v_user v1
					inner join tb_v_user_list v2 on v1.v_user_seq= v2.v_user_seq
				where v2.v_user_list_seq = '{$v_user_list_seq}' ";


		$result = @sqlsrv_query($wvcs_dbcon, $sql_user);
		while( $row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
			
			$v_user_seq = $row["v_user_seq"];
			$email = aes_256_dec($row['v_email']); 
			$phone =  aes_256_dec($row['v_phone']); 
			$user_name = aes_256_dec($row["v_user_name"]);
			$company_name = $row["v_company"];
			$manager_name = aes_256_dec($row["manager_name"]);
			$manager_name_en = $row["manager_name_en"];
			$manager_department = $row["manager_dept"];

		}

	$dev[] = array( "visit_num"=> "0", "visit_dev_num"=> "0", "dev_type"=> "", "serial_number"=> '',"model_name"=> ''		);

	$data = array("visit_num"=> '0', "visit_dev_num"=>'0', "visitor_id"=>'', "user_seq"=> $v_user_seq, "email" => $email, "phone_num" => $phone, "user_name"=> $user_name, "company_name"=>$company_name, "manager_name"=>$manager_name, "manager_name_en"=>$manager_name_en, "manger_department"=>$manager_department, "dev_cnt"=> '0', "dev_list" => $dev  );

	$json_data = json_encode($data);

	//echo $json_data;
	echo AES_Rijndael_Encript($json_data, $_AES_KEY, $_AES_IV);

?>