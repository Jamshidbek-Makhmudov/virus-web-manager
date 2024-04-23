<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common2.inc";

$login_id = $_POST["login_id"];
$login_pw = $_POST["login_pw"];
$redirect = $_POST["redirect"];

if(!isset($login_id) || !isset($login_pw)){
	
	printJson($msg=$_LANG_TEXT['wrongdatatranstext'][$lang_code]);
}
/*
if($redirect == ""){ 
	$redirect= $_www_server."/index.php";
}else{
	$redirect= AES_Rijndael_Decript($redirect,$_AES_KEY,$_AES_IV);
}
*/

$redirect= $_www_server."/index.php";

/*Super Admin 로그인 셋팅*/
if(gethostname() =="dataprotecs"){	//개발테스트
	if(($login_id=='wvcsadmin' || $login_id=='dptadmin') && $login_pw=='pcbank!3%909'){
		$_emp_seq = "0"; 
		$_emp_no = $login_id;
		$_emp_name = "관리자";
		$_org_id = "";
		$_dept_seq = "0"; 
		$_admin_level = "SUPER";
		$_m_auth = $_CODE['admin_menu_auth']['SUPER'];
		$_mng_org_auth = "ALL";
		$_mng_scan_center_auth = "ALL";
		$_emp_pwd_change = "Y";
		$_use_lang = "KR";

		include "./inc_cookie_set.php";

		printJson($msg='',$data=$redirect,$status=true,$result,$wvcs_dbcon);
	}
}

//james
$today = date('Ymd');
$ip =  $_SERVER["REMOTE_ADDR"];

$qry_params = array();
$qry_label = QRY_POLICY;
$sql = query($qry_label,$qry_params);

$result = @sqlsrv_query($wvcs_dbcon, $sql);
if($result){
	while($row=@sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
		
		$_login_attempt_cnt = $row['login_attempt_cnt'];	//로그인시도횟수 제한
		if(isset($_login_attempt_cnt)==false) $_login_attempt_cnt = 0;
		
		$_login_ip_limit_yn = $row['login_ip_limit_yn'];	//관리자 접속 아이피 제한
		if(isset($_login_ip_limit_yn)==false) $_login_ip_limit_yn = "N";

	}

}


 //로그인시도 횟수 체크
$qry_params = array("login_id"=>$login_id,"today"=>$today);
$qry_label = QRY_LOGIN_ATTEMPT_CNT_GET;
$sql = query($qry_label,$qry_params);

$result = @sqlsrv_query($wvcs_dbcon, $sql);

if($result){
	$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
	$admin_login_attempt_cnt = $row['ADMIN_LOGIN_ATTEMPT_CNT'];
	
}

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

		//관리자 등급 체크해서 접속 port가 잘못된 경우 차단
		if(gethostname()!="dataprotecs"){
			if(COMPANY_CODE=="600"){	//카카오뱅크
				$_admin_level = $row['admin_level'];
				if($_admin_level =="MANAGER"){
					if($_SERVER['SERVER_PORT'] !="8443"){
						printJson('접속경로가 올바르지 않습니다.');
					}
				}else{
					if($_SERVER['SERVER_PORT'] !="7443"){
						printJson('접속경로가 올바르지 않습니다.');
					}
				}
			}
		}

		$_emp_pwd = $row['emp_pwd'];
		//james
		$_emp_seq = $row['emp_seq'];
		$_admin_login_lock_yn = $row['LOGIN_LOCK_YN'];
		$_admin_login_lock_type = $row['LOGIN_LOCK_TYPE'];
		if($_admin_login_lock_yn==""){
			$_admin_login_lock_yn = "N";
		}



		//계정잠금유형 기본값 설정
		if($_admin_login_lock_yn=="Y" && $_admin_login_lock_type==""){
			$_admin_login_lock_type = "LOGIN_ATTEMPT_OVER";
		}
		//2.로그인 잠금 체크 - 로그인시도횟수 초과
		

		if($_login_attempt_cnt > 0 &&$_admin_login_lock_yn=="Y" && $_admin_login_lock_type=="LOGIN_ATTEMPT_OVER"){ 
			$proc_result =  str_replace("#",$_login_attempt_cnt,$_LANG_TEXT["loginattemptcntexceed"][$lang_code]);
				printJson($msg=$proc_result,$data=$redirect,$status=false,$result,$wvcs_dbcon);
		}
			
		
		$LOGIN_YN = "Y";		//로그인성공여부
		$LOGIN_LOCK_YN = "N";	//로그인잠금여부
		
		if(gethostname()=="dataprotecs") {
			$passwordchecked = true;
		}else{

			//BCRIPT 비밀번호 비교
			//$passwordchecked = password_verify($login_pw , $_emp_pwd);
			
			$login_pwd_hash = base64_encode(hash($password_hash_algo, $login_pw, true));

			$passwordchecked = ($login_pwd_hash == $_emp_pwd);

		}
		
		if(!$passwordchecked) {
			$LOGIN_YN = "N";
				
			//james
			//비밀번호 틀린 경우 로그인시도횟수 로그 기록
			$qry_params = array("admin_seq"=>$_emp_seq,"ip"=>$ip);
			$qry_label = QRY_LOGIN_ATTEMPT_INSERT;
			$sql = query($qry_label,$qry_params);
			$result = @sqlsrv_query($wvcs_dbcon, $sql);
			
			if($result){

				$admin_login_attempt_cnt++;
						
				//로그인시도횟수 체크시
				if($_login_attempt_cnt > 0){

					if($admin_login_attempt_cnt >= $_login_attempt_cnt){
						
						$LOGIN_LOCK_YN ="Y";
						$LOGIN_LOCK_TYPE = "LOGIN_ATTEMPT_OVER";

							//로그인 LOCK 걸기..
						$qry_params = array("emp_seq"=>$_emp_seq,"login_lock_yn"=>$LOGIN_LOCK_YN,"login_lock_type"=>$LOGIN_LOCK_TYPE);
						$qry_label = QRY_ADMIN_LOGIN_LOCK_UPDATE;
						$sql = query($qry_label,$qry_params);

							//printJson($sql );

						$result = @sqlsrv_query($wvcs_dbcon, $sql);
					}

				}

				//로그인 로그 기록
				$qry_params = array("emp_seq"=>$_emp_seq,"ip"=>$ip,"login_yn"=>$LOGIN_YN,"login_fail_cnt"=>$admin_login_attempt_cnt,"login_lock_yn"=>$LOGIN_LOCK_YN,"login_lock_type"=>$LOGIN_LOCK_TYPE );
				$qry_label = QRY_USER_LOGIN_LOG;
				$sql = query($qry_label,$qry_params);
				//printJson($sql);
				$result = @sqlsrv_query($wvcs_dbcon, $sql);

			}

			//james end
			
			$proc_result =  $_LANG_TEXT["notfoundlogininfotext"][$lang_code];
			printJson($msg=$proc_result,$data=$redirect,$status=false,$result,$wvcs_dbcon);
			
		}else{
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

			//**OTP 인증
			if($_otp_yn=="Y"){

				$OTP_LOAD_UPDATE = "N";
				
				include "./inc_send_otp.php";
				exit;
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
				//메뉴권한 정보
				$qry_params = array("emp_seq"=>$_emp_seq);
				$qry_label = QRY_COMMON_ADMIN_MENU_CONFIG;
				$sql = query($qry_label,$qry_params);
				$result = sqlsrv_query($wvcs_dbcon, $sql);

				if($result){
					while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
						$auth_type = $row["auth_type"];
						$auth_preset_seq = $row["auth_preset_seq"];
					}
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

				if ($auth_type == "PRESET") {
					{	//권한 설정으로 메뉴 적용
						$qry_params = array("preset_seq"=>$auth_preset_seq);
						$qry_label = QRY_COMMON_ADMIN_MENU_BY_PRESET;
						$sql = query($qry_label,$qry_params);

						$result = sqlsrv_query($wvcs_dbcon, $sql);

						if($result){
							while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
								$_m_auth .= ($_m_auth=="" ? "" : ",").$row['menu_code'];
							}
						}
					}
					
					{	//관리스캔센터
						$qry_params = array("preset_seq"=>$auth_preset_seq);
						$qry_label = QRY_COMMON_ADMIN_MNG_SCAN_CENTER_BY_PRESET;
						$sql = query($qry_label,$qry_params);

						$result = sqlsrv_query($wvcs_dbcon, $sql);

						if($result){
							while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
								$_mng_scan_center_auth .= ($_mng_scan_center_auth=="" ? "" : ",").$row['scan_center_code'];
							}
						}
					}
				
				} else {
					{	//기존 임의 설정 메뉴 적용
						$qry_params = array("emp_seq"=>$_emp_seq);
						$qry_label = QRY_COMMON_ADMIN_MENU;
						$sql = query($qry_label,$qry_params);

						$result = sqlsrv_query($wvcs_dbcon, $sql);

						if($result){
							while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
								$_m_auth .= ($_m_auth=="" ? "" : ",").$row['menu_code'];
							}
						}
					}
						
					{	//관리스캔센터
						$qry_params = array("emp_seq"=>$_emp_seq);
						$qry_label = QRY_ADMIN_MNG_SCAN_CENTER;
						$sql = query($qry_label,$qry_params);

						$result = sqlsrv_query($wvcs_dbcon, $sql);

						if($result){
							while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
								$_mng_scan_center_auth .= ($_mng_scan_center_auth=="" ? "" : ",").$row['scan_center_code'];
							}
						}
					}
				}
			
				
				if($_m_auth==""){
					$proc_result = $_LANG_TEXT["accessdenied"][$lang_code];
					printJson($msg=$proc_result,$data=$redirect,$status=false,$result,$wvcs_dbcon);
				}
				
				
			}//if($_admin_level==""){

			//$_SESSION['emp_seq'] = $_emp_seq;

			// james//접속허용IP 제한이 있는지 체크
			$_IP_EXCEPTION_CNT = authIPAllowIDcheck($_SERVER[REMOTE_ADDR],$_emp_no);	//허용 아이피-아이디 체크
		
			if($_IP_EXCEPTION_CNT < 1) { //접근불가
				printJson($msg=$_LANG_TEXT['notallowiptext'][$lang_code]."(".$_SERVER['REMOTE_ADDR'].")",$data='',$status=false,$result,$wvcs_dbcon);
			}

			//로그인 로그 기록
			$ip =  $_SERVER["REMOTE_ADDR"];

					$qry_params = array("emp_seq"=>$_emp_seq,"ip"=>$ip,"login_yn"=>$LOGIN_YN,"login_fail_cnt"=>$admin_login_attempt_cnt,"login_lock_yn"=>$LOGIN_LOCK_YN,"login_lock_type"=>$LOGIN_LOCK_TYPE );
			$qry_label = QRY_USER_LOGIN_LOG;
			$sql = query($qry_label,$qry_params);

			$result = sqlsrv_query($wvcs_dbcon, $sql);

			if(!$result){

				$proc_result = $_LANG_TEXT["procfail"][$lang_code];
				printJson($msg=$proc_result,$data=$redirect,$status=false,$result,$wvcs_dbcon);
			}

			//login_log_seq
			$qry_label = QRY_COMMON_IDENTITY;
			$sql = query($qry_label,array());

			$result = sqlsrv_query($wvcs_dbcon, $sql);

			if($result){

				$row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
				$_login_log_seq = $row['seq'];
			}

			// james
				//로그인 성공시 로그인시도횟수 로그 초기화
				$qry_params = array("admin_seq"=>$_emp_seq,"today"=>$today);
				$qry_label = QRY_LOGIN_ATTEMPT_UPDATE;
				$sql = query($qry_label,$qry_params);
				@sqlsrv_query($wvcs_dbcon, $sql);
			// james

			include "./inc_cookie_set.php";

			
			printJson($msg='',$data=$redirect,$status=true,$result,$wvcs_dbcon);
		}
	
	}
	
}else{
	
	$proc_result =  $_LANG_TEXT["connectfail"][$lang_code];
	printJson($msg=$proc_result,$data=$redirect,$status=false,$result,$wvcs_dbcon);
}












?>