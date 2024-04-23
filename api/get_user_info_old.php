<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

/* Description
*  방문자정보 비연동일때 호출 페이지
*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];
include  $_server_path . "/lib/dpt25_config.inc";
include "./common.php";


		$phone_num = $_REQUEST['phone_num'];
		if($phone_num != ""){
			$phone_num =  AES_Rijndael_Decript(base64_decode($_REQUEST['phone_num']), $_AES_KEY, $_AES_IV);
		}
		$email = $_REQUEST['email'];
		if($email != ""){
			$email =AES_Rijndael_Decript(base64_decode($_REQUEST['email']), $_AES_KEY, $_AES_IV);
		}
		//$user_name = AES_Rijndael_Decript($json_value['user_name'], $_AES_KEY, $_AES_IV);
		
		if($_encryption_yn == "Y") {
			$qry_Decrypt_EMAIL = " dbo.fn_DecryptString(EMAIL) ";
			$qry_Decrypt_PHONE = " dbo.fn_DecryptString(PHONE) ";
		} else {
			$qry_Decrypt_EMAIL = " EMAIL ";
			$qry_Decrypt_PHONE = " PHONE ";
		}

		if($_encryption_yn == "Y" && $_encryption_flag == "2") {
			$qry_Decrypt_USER_NAME = " dbo.fn_DecryptString(USER_NAME) ";
			$qry_Decrypt_MANAGER_NAME = " dbo.fn_DecryptString(MANAGER_NAME) ";
		} else {
			$qry_Decrypt_USER_NAME = " USER_NAME ";
			$qry_Decrypt_MANAGER_NAME = " MANAGER_NAME ";
		}

		if($phone_num != ""){
			$search_sql = " AND Replace( {$qry_Decrypt_PHONE}, '-','') = '$phone_num'  ";
		}else if($email != ""){
			$search_sql =  " AND  {$qry_Decrypt_EMAIL} = '$email'  ";
		}else{
			$search_sql = " AND 1=2";
		}

		

		$sql_user = "	SELECT top 1 USER_INFO_LIST_SEQ, Replace( {$qry_Decrypt_PHONE}, '-','') as PHONE,  {$qry_Decrypt_EMAIL} as EMAIL, {$qry_Decrypt_USER_NAME} AS USER_NAME, COMPANY_NAME
								, {$qry_Decrypt_MANAGER_NAME} AS MANAGER_NAME, MANAGER_DEPARTMENT 
							FROM  DPT_USER_INFO_LIST
							WHERE 1 = 1 ".$search_sql."
							ORDER BY USER_INFO_LIST_SEQ desc";

		//echo nl2br($sql_user);
	
		$result = sqlsrv_query($dbcon, $sql_user);
		$user_seq = -1;	
		while( $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
			//USER_INFO_LIST_SEQ, Replace( dbo.fn_DecryptString(phone), '-','') as PHONE,  dbo.fn_DecryptString(EMAIL) as EMAIL, USER_NAME, COMPANY_NAME, MANAGER_NAME, MANAGER_DEPARTMENT 
			$user_seq = $row["USER_INFO_LIST_SEQ"];
			$email = $row["EMAIL"];
			$phone = $row["PHONE"];
			$user_name = $row["USER_NAME"];
			$company_name = $row["COMPANY_NAME"];
			$manager_name = $row["MANAGER_NAME"];
			$manager_department = $row["MANAGER_DEPARTMENT"];
		}

	$data = array("visit_num"=> '0', "visit_dev_num"=>'0', "visitor_id"=>'', "email" => $email, "phone_num" => $phone, "user_name"=> $user_name, "company_name"=>$company_name, "manager_name"=>$manager_name, "manger_department"=>$manager_department, "dev_cnt"=> '0', "dev_list" => $dev  );

		$json_data = json_encode($data);

		//echo $json_data;
		echo AES_Rijndael_Encript($json_data, $_AES_KEY, $_AES_IV);

?>