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
$otp_code = $_POST["otp_code"];
$redirect = $_POST["redirect"];

if($login_id == ""){
	printJson($msg=$_LANG_TEXT['wrongdatatranstext'][$lang_code]);
}

if($redirect == ""){ 
	$redirect= $_www_server."/index.php";
}else{
	$redirect= AES_Rijndael_Decript($redirect,$_AES_KEY,$_AES_IV);
}

$qry_params = array("login_id"=>$login_id);
$qry_label = QRY_OTP_LOG_INFO;
$sql = query($qry_label,$qry_params);

$result = sqlsrv_query($wvcs_dbcon, $sql);
if($result){
	$row = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

	$_admin_otp_log_seq = $row['admin_otp_log_seq'];
	$_check_count = $row['check_count'];
	$_otp_code = $row['otp_code'];
}


//인증실패
if($otp_code != $_otp_code || $_check_count > 1){ // $_check_count > 1 은 새로고침한 경우!

	$proc_result = $_LANG_TEXT["incorrectotpcodetext"][$lang_code];
	printJson($msg=$proc_result,$data=$redirect,$status=false,$result,$wvcs_dbcon);
}

//인증성공 업데이트
$qry_params = array("admin_otp_log_seq"=>$_admin_otp_log_seq );
$qry_label = QRY_OTP_LOGIN_OK;
$sql = query($qry_label,$qry_params);

$result = sqlsrv_query($wvcs_dbcon, $sql);

if(!$result){

	printJson($msg=$_LANG_TEXT["connectfail"][$lang_code],$data=$redirect,$status=false,$result,$wvcs_dbcon);
}

//로그인처리
$qry_params = array("search_sql"=>" AND emp_no = '{$login_id}' ");
$qry_label = QRY_USER_LOGIN;
$sql = query($qry_label,$qry_params);

$result = sqlsrv_query($wvcs_dbcon, $sql, array(),array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

if($result) {

	$row_count = sqlsrv_num_rows($result);

	if($row_count==0){
		 $proc_result = $_LANG_TEXT["notfoundlogininfotext"][$lang_code];
		 printJson($msg=$proc_result,$data=$redirect,$status=false,$result,$wvcs_dbcon);
	}else{

		$row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

		$_emp_seq = $row['emp_seq'];
		$_emp_no = $row['emp_no'];
		$_emp_name = aes_256_dec($row['emp_name']);
		$_org_id = $row['org_id'];
		$_dept_seq = $row['dept_seq'];
		$_use_lang = trim($row['use_lang']);
		$_admin_level = $row['admin_level'];
		$_pwd_change_emp = isset($row['pwd_change_emp'])? $row['pwd_change_emp'] : $row['emp_seq'];
		$_pwd_change_date = $row['pwd_change_date'];

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

			$_admin_pwd_change_term = $row['admin_pwd_change_term'];
			$_otp_yn = $row['otp_yn'];

		}


		//**비밀번호변경체크
		$_emp_pwd_change = "Y";

		if($_pwd_change_emp==$_emp_seq){

			if($_pwd_change_date==""){
				
				$_emp_pwd_change = "N1";	//N1 : 최초 로그인시 비밀번호 변경

			}else{
			
				$pw_changed_days = 0;
				if($_admin_pwd_change_term > 0){

					$d1 = ($_pwd_change_date==""? $_emp_create_dt : $_pwd_change_date);
					$d2 = date("Y-m-d H:i:s");

					$pw_changed_days = dateDiff($d1,$d2);

					if($pw_changed_days >= $_admin_pwd_change_term){
						
						$_emp_pwd_change = "N2";	//N2 : 비밀번호 변경주기 경과
					}
				}

			}
		
		}else{

			$_emp_pwd_change = "N3";	//N3 : 관리자 비밀번호 초기화
		}

		if($_emp_pwd_change != "Y"){

			include "./inc_cookie_set.php";
			printJson($msg='',$data=$redirect,$status=true,$result,$wvcs_dbcon);
		}

		
		//**메뉴권한
		if($_admin_level==""){
		//**일반사용자

			 $_m_auth = "";

			 $proc_result = $_LANG_TEXT["accessdenied"][$lang_code];
			  printJson($msg=$proc_result,$data=$redirect,$status=false,$result,$wvcs_dbcon);

		}else{
		//**관리자
			
			//메뉴권한
			$qry_params = array("emp_seq"=>$_emp_seq);
			$qry_label = QRY_COMMON_ADMIN_MENU;
			$sql = query($qry_label,$qry_params);

			$result = sqlsrv_query($wvcs_dbcon, $sql);

			if($result){

				while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
					
					$_m_auth .= ($_m_auth=="" ? "" : ",").$row['menu_code'];
				}

			}
			
			if($_m_auth==""){

				$proc_result = $_LANG_TEXT["accessdenied"][$lang_code];
				printJson($msg=$proc_result,$data=$redirect,$status=false,$result,$wvcs_dbcon);
			}
			
			//관리기관
			$qry_params = array("emp_seq"=>$_emp_seq);
			$qry_label = QRY_ADMIN_MNG_ORG;
			$sql = query($qry_label,$qry_params);

			$result = sqlsrv_query($wvcs_dbcon, $sql);

			if($result){

				while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
					
					$_mng_org_auth .= ($_mng_org_auth=="" ? "" : ",").$row['org_id'];
				}
			}
			

		}//if($_admin_level==""){

		//$_SESSION['emp_seq'] = $_emp_seq;

		//로그인 로그 기록
		$ip =  $_SERVER["REMOTE_ADDR"];

		$qry_params = array("emp_seq"=>$_emp_seq,"ip"=>$ip);
		$qry_label = QRY_USER_LOGIN_LOG;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);

		if(!$result){

			$proc_result = $_LANG_TEXT["procfail"][$lang_code];
			printJson($msg=$proc_result,$data=$redirect,$status=false,$result,$wvcs_dbcon);
		}
		
		include "./inc_cookie_set.php";

		printJson($msg='',$data=$redirect,$status=true,$result,$wvcs_dbcon);
		
	
	}
	
}else{
	
	$proc_result =  $_LANG_TEXT["connectfail"][$lang_code];
	printJson($msg=$proc_result,$data=$redirect,$status=false,$result,$wvcs_dbcon);
}
?>