<?
$otp_code = generateOtpCode();

$RECIEVE_TELNO	= preg_replace("/[^0-9]*/s", "", $_phone_no);  //숫자만 추출
$SEND_TELNO		= preg_replace("/[^0-9]*/s", "", $_sms_send_telno);

if(strlen($RECIEVE_TELNO) >= 10 ) {

	$send_text = $_LANG_TEXT['loginauthenticationnumbersmstext'][$lang_code];
	$send_text = str_replace("{#}","[{$otp_code}]",$send_text);

	$cdate = date("YmdHis");

	if($_sms_type == "DB") {

		include $_server_path . "/" . $_site_path . "/inc/mysqlconnect.php";

		if(!$dbmysql){
			printJson($msg='OTP Certification Error : Failed Connect DB',$data=$redirect,$status=false,$result,$wvcs_dbcon);
		}

		//myql sms 서버에 데치터를 전송한다.
		$qry_params = array(
				"sms_table"=>$_sms_table
				,"receiver"=>$RECIEVE_TELNO
				,"sender"=>$SEND_TELNO
				,"send_msg"=>$send_text
			);

		$qry_label = QRY_SMS_SEND;
		$sql = query($qry_label,$qry_params);	
		
		@mysql_query($sql, $dbmysql);  

		if(mysql_affected_rows() <= 0){
			printJson($msg='OTP Certification Error : Failed Send Sms',$data=$redirect,$status=false,$result,$wvcs_dbcon);
		}

			
	}else if($_sms_type == "WEB") {
		//웹방식일 경우는 
		
		$param_sub = ParamEnCoding("rcvnum=".$RECIEVE_TELNO."&msg=".$send_text) ;
		$param = $_sms_url . "?enc=" . $param_sub;

		$rtnstr =  @file_get_contents($param);

		if($rtnstr != "success"){
			printJson($msg=$rtnstr,$data=$redirect,$status=false,$result,$wvcs_dbcon);
		}
	
	}else{
		printJson($msg='OTP Certification Error : Check Policy - SMS_TYPE',$data=$redirect,$status=false,$result,$wvcs_dbcon);
	}

	$qry_params = array("admin_seq"=>$_emp_seq,"otp_code"=>$otp_code);
	$qry_label = QRY_OTP_LOG_INSERT;
	$sql = query($qry_label,$qry_params);


	$result = @sqlsrv_query($wvcs_dbcon, $sql);

	if($result){

		if($OTP_LOAD_UPDATE=="Y"){

			$qry_params = array();
			$qry_label = QRY_COMMON_IDENTITY;
			$sql = query($qry_label,$qry_params);
			$result = sqlsrv_query($wvcs_dbcon, $sql);
			$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
			$_admin_otp_log_seq = $row['seq'];
		
			$qry_params = array("admin_otp_log_seq"=>$_admin_otp_log_seq);
			$qry_label = QRY_OTP_LOG_UPDATE;
			$sql = query($qry_label,$qry_params);

			$result = sqlsrv_query($wvcs_dbcon, $sql);

		}

		printJson($msg=$_LANG_TEXT['sendotpcodetext'][$lang_code],$data='LOGIN_OTP',$status=true,$result,$wvcs_dbcon);

	}else{
		printJson($msg=$_LANG_TEXT["connectfail"][$lang_code]);
	}

}else{
	printJson($msg='OTP Certification Error : Wrong Phone_Number!',$data=$redirect,$status=false,$result,$wvcs_dbcon);
}
?>