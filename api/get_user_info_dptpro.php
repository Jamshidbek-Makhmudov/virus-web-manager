<?php

/* Description
* DPTPRO 방문자 연동정보 가져오기
*/

// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

$_server_path = $_SERVER['DOCUMENT_ROOT'];
/*
*DPT25 DB 암호화방식을 aes256을 사용할 경우 반드시 dpt25_config.inc 파일을 참조해야 한다.  --open key설정..
*/
include  $_server_path . "/lib/dpt25_config.inc";
include "./common.php";

	//$visitor_id =  $_REQUEST['visitor_id'];

	$company_code =  $_REQUEST["company_code"]; 
	if($company_code == "") {
		$company_code = $_user_type_flag;	
	}
	
	$v_user_list_seq_enc = $_REQUEST["v_user_list_seq"]; //strtoupper($_REQUEST["id"]);
	$v_user_list_seq =  AES_Rijndael_Decript(base64_decode($_REQUEST["v_user_list_seq"]), $_AES_KEY, $_AES_IV); 
    
	$vul_dev_seq_enc = $_REQUEST["vul_dev_seq"]; 
	$vul_dev_seq =  AES_Rijndael_Decript(base64_decode($_REQUEST["vul_dev_seq"]), $_AES_KEY, $_AES_IV); 

	$todayYmd = date("Ymd");

	if($_encryption_yn == "Y") {
		$qry_Decrypt_EMAIL = " dbo.fn_DecryptString(b.EMAIL) ";
		$qry_Decrypt_PHONE = " dbo.fn_DecryptString(b.PHONE) ";
	} else {
		$qry_Decrypt_EMAIL = " b.EMAIL ";
		$qry_Decrypt_PHONE = " b.PHONE ";
	}

	if($_encryption_yn == "Y" && $_encryption_flag == "2") {
		$qry_Decrypt_USER_NAME = " dbo.fn_DecryptString(b.USER_NAME) ";
		$qry_Decrypt_MANAGER_NAME = " dbo.fn_DecryptString(a.MANAGER_NAME) ";
	} else {
		$qry_Decrypt_USER_NAME = " b.USER_NAME ";
		$qry_Decrypt_MANAGER_NAME = " a.MANAGER_NAME ";
	}
	
	if($v_user_list_seq_enc != "") { 
			$sql = "SELECT a.V_USER_LIST_SEQ
								, c.V_USER_LIST_DEVICE_SEQ
								, a.V_USER_SEQ
								, {$qry_Decrypt_EMAIL} as EMAIL
								, {$qry_Decrypt_PHONE} as PHONE
								, {$qry_Decrypt_USER_NAME} AS USER_NAME
								, V_COMPANY
								, {$qry_Decrypt_MANAGER_NAME} AS MANAGER_NAME
								, MANAGER_DEPT
								, c.DEVICE
						FROM DPT_V_USER_LIST a
							INNER JOIN DPT_V_USER b ON a.V_USER_SEQ=b.V_USER_SEQ
							LEFT JOIN DPT_V_USER_LIST_DEVICE c ON c.V_USER_LIST_SEQ = a.V_USER_LIST_SEQ
						WHERE a.V_USER_LIST_SEQ = ".$v_user_list_seq;
			
			//echo $sql;
	}else if($vul_dev_seq_enc != ""){
			$sql = "SELECT a.V_USER_LIST_SEQ
								, c.V_USER_LIST_DEVICE_SEQ
								, a.V_USER_SEQ
								, {$qry_Decrypt_EMAIL} as EMAIL
								, {$qry_Decrypt_PHONE} as PHONE
								, {$qry_Decrypt_MANAGER_NAME} AS USER_NAME
								, V_COMPANY
								, {$qry_Decrypt_MANAGER_NAME} AS MANAGER_NAME
								, MANAGER_DEPT
								, c.DEVICE
						FROM DPT_V_USER_LIST a
							INNER JOIN DPT_V_USER b ON a.V_USER_SEQ=b.V_USER_SEQ
							INNER JOIN DPT_V_USER_LIST_DEVICE c ON c.V_USER_LIST_SEQ = a.V_USER_LIST_SEQ
						WHERE c.V_USER_LIST_DEVICE_SEQ = ".$vul_dev_seq;
			
			//echo $sql;
	}

	//로그파일생성
//	$logFile = @fopen('debug'.date('Ymd').'.txt', "a+") or die("Unable to open file!");
//	fwrite($logFile, $sql."\r\n\r\n");
//	fclose($logFile);

	$result = @sqlsrv_query($dbcon, $sql);
	$index = 0;

	while( $row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
		$V_USER_LIST_SEQ = $row["V_USER_LIST_SEQ"];
		$V_USER_LIST_DEVICE_SEQ = $row["V_USER_LIST_DEVICE_SEQ"];
		$V_USER_SEQ = $row["V_USER_SEQ"];
		$EMAIL = $row["EMAIL"];
		$PHONE = $row["PHONE"];
		$USER_NAME = $row["USER_NAME"];
		$V_COMPANY = $row["V_COMPANY"];
		$MANAGER_NAME = $row["MANAGER_NAME"];
		$MANAGER_DEPT = $row["MANAGER_DEPT"];
		$DEVICE = $row["DEVICE"];

		$dev[$index] = array( "visit_num"=> $V_USER_LIST_SEQ, "visit_dev_num"=> $V_USER_LIST_DEVICE_SEQ, "dev_type"=> $DEVICE, "serial_number"=> '',"model_name"=> ''		);
		$index = $index+1;
	}


	$data = array("visit_num"=> $V_USER_LIST_SEQ, "visit_dev_num"=>$V_USER_LIST_DEVICE_SEQ, "visitor_id"=>"", "email" => $EMAIL, "phone_num" => $PHONE, "user_name"=> $USER_NAME, "company_name"=>$V_COMPANY, "manager_name"=>$MANAGER_NAME, "manger_department"=>$MANAGER_DEPT, "dev_cnt"=> $index, "dev_list" => $dev  );




	//var_dump($data);
	$json_data = json_encode($data);


	//로그파일생성
//	$logFile = @fopen('debug'.date('Ymd').'.txt', "a+") or die("Unable to open file!");
//	fwrite($logFile, $json_data."\r\n\r\n");
//	fclose($logFile);

	//echo $json_data;
	echo AES_Rijndael_Encript($json_data, $_AES_KEY, $_AES_IV);
	exit;


?>