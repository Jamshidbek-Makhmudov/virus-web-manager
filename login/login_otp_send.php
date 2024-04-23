<?php
//session_start();

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common2.inc";

$login_id = $_POST["login_id"];


if($login_id == ""){
	printJson($msg=$_LANG_TEXT['wrongdatatranstext'][$lang_code]);
}

$qry_params = array("search_sql"=>" AND emp_no = '{$login_id}' ");
$qry_label = QRY_USER_LOGIN;
$sql = query($qry_label,$qry_params);

$result = sqlsrv_query($wvcs_dbcon, $sql, array(),array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

if($result){

	$row_count = sqlsrv_num_rows($result);

	if($row_count==0){
		 $proc_result = $_LANG_TEXT["notfoundlogininfotext"][$lang_code];
		 printJson($msg=$proc_result,$data='',$status=false,$result,$wvcs_dbcon);
	}else{

		$row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

		$_emp_seq = $row['emp_seq'];
		$_emp_no = $row['emp_no'];
		$_emp_name = aes_256_dec($row['emp_name']);
		

		if($_encryption_kind=="1"){

			$_phone_no = $row['phone_no_decript'];
			
		}else if($_encryption_kind=="2"){

			$_phone_no = aes_256_dec($row['phone_no']);
		}

		
		//**LOGIN Policy Check
		$qry_params = array();
		$qry_label = QRY_POLICY;
		$sql = query($qry_label,$qry_params);
		$result = sqlsrv_query($wvcs_dbcon, $sql);

		if($result){

			$row = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

			$_otp_yn = $row['otp_yn'];

			$_sms_type = $row['sms_type'];
			$_sms_server = $row['sms_server'];
			$_sms_port = $row['sms_port'];
			$_sms_id = $row['sms_id'];
			$_sms_pwd = $row['sms_pwd'];
			$_sms_db = $row['sms_db'];
			$_sms_table = $row['sms_table'];
			$_sms_url = $row['sms_url'];
			$_sms_send_telno = $row['sms_send_telno'];

		}

		//**OTP ¿Œ¡ı
		if($_otp_yn=="Y"){

			$OTP_LOAD_UPDATE = "Y";

			include "./inc_send_otp.php";

		}

	}//if($row_count==0){

}else{

	printJson($msg=$_LANG_TEXT["procfail"][$lang_code]);
}