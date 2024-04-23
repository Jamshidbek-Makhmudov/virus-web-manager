<?php
$page_name = "admin_list";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$emp_seq = $_POST["emp_seq"];
$emp_name = $_POST["emp_name"];
$emp_no = $_POST["emp_no"];
$emp_pwd = $_POST["emp_pwd"];
$phone_no = $_POST["phone_no"];
$email = $_POST["email"];

$phone_no = preg_replace("/[^0-9-]*/s", "", $phone_no);

$org_id = $_POST["org_id"];
$dept_seq = $_POST["dept_seq"];
$jgrade_code = $_POST["jgrade_code"];
$jduty_code = $_POST["jduty_code"];
$jpos_code = $_POST["jpos_code"];

$work_yn = $_POST["work_yn"];
$use_lang = $_POST["rdoLang"];
$admin_level = $_POST["admin_level"];
$menu = $_POST["menu"];
$mng_org = $_POST['mng_org'];
$mng_scan_center = $_POST['mng_scan_center'];

$admin_auth_type = $_POST["admin_auth_type"];
$admin_auth_preset_seq = $_POST["admin_auth_preset_seq"];

$proc = $_POST["proc"];

if($proc == "CREATE" && $emp_seq <> "") {
	printJson($msg=$_LANG_TEXT['wrongdatatranstext'][$lang_code]);
} else if ( ($proc == "UPDATE" || $proc == "DELETE" ) && $emp_seq == "") {
	printJson($msg=$_LANG_TEXT['wrongdatatranstext'][$lang_code]);
}

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,$proc);

//sha256 암호화
$emp_pwd_hash = base64_encode(hash($password_hash_algo, $emp_pwd, true));

//아이디유효성체크
if($proc=="CREATE"){
	
	list($result,$msg) = validCheck_UserID($emp_no);
	
	if(!$result){
		//아이디는 영문 대문자,소문자,숫자를 사용해 5~12자리로 입력해야합니다
		printJson($msg=$_LANG_TEXT['idruletext'][$lang_code]);
	}

}

//데이터 유효성 체크
if($proc != "DELETE"){

	//비밀번호 유효성체크
	if($emp_pwd != ""){
		
		list($result,$msg) = validCheck_Password($emp_pwd);
		
		if(!$result){
			//비밀번호는 영문대문자,영문소문자, 숫자, 특수문자 중 세가지를 포함해 8~16자 이내로 입력하세요
			printJson($msg=$_LANG_TEXT['passwordrules'][$lang_code]);
		}
		
		//이전 비밀번호와 비교
		if($proc=="UPDATE"){
			
			$qry_params = array("emp_seq"=> $emp_seq);
			$qry_label = QRY_COMMON_EMP_INFO_PW;
			$sql = query($qry_label,$qry_params);

			$result = sqlsrv_query($wvcs_dbcon, $sql);

			if($result) {
				while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
					
					$_emp_pwd = $row['emp_pwd'];
				}
			}

			if($emp_pwd_hash==$_emp_pwd){

				printJson($msg=$_LANG_TEXT['samepwdchangetext'][$lang_code]);
			}	
		}

	}

	//전화번호 유효성 체크
	if($phone_no != ""){
		
		list($result,$msg) = validCheck_Phone($phone_no);
		
		if(!$result){
			
			printJson($msg=$_LANG_TEXT['notvalidphonetext'][$lang_code]);
		}	
	}

	//이메일 유효성 체크
	if($email != ""){
		
		list($result,$msg) = validCheck_Email($email);
		
		if(!$result){
			
			printJson($msg=$_LANG_TEXT['notvalidemailtext'][$lang_code]);
		}	
	}

}


$login_emp_seq = $_ck_user_seq;

if($_encryption_kind=="1"){

	$emp_name_encrypt = "dbo.fn_EncryptString('".$emp_name."')";
	$phone_no_encrypt = "dbo.fn_EncryptString('".$phone_no."')";
	$email_encrypt = "dbo.fn_EncryptString('".$email."')";

}else if($_encryption_kind=="2"){
	
	$emp_name_encrypt = aes_256_enc($emp_name);
	$phone_no_encrypt = aes_256_enc($phone_no);
	$email_encrypt = aes_256_enc($email);
}

$c_date = date("Y-m-d H:i:s");


$Model_manage = new Model_manage();

if ($proc == "CREATE") {
	
	$isExistedEmpNo = isExistedEmpNo($emp_no,$wvcs_dbcon);

	if($isExistedEmpNo){

		printJson($msg=$_LANG_TEXT['existedempno'][$lang_code]);
	}


	$qry_params = array(
			"emp_no"=>$emp_no
			,"emp_name_encrypt"=>$emp_name_encrypt
			,"emp_pwd_hash"=>$emp_pwd_hash
			,"phone_no_encrypt"=>$phone_no_encrypt
			,"email_encrypt"=>$email_encrypt
			,"org_id"=>$org_id
			,"dept_seq"=>$dept_seq
			,"jpos_code"=>$jpos_code
			,"jgrade_code"=>$jgrade_code
			,"jduty_code"=>$jduty_code
			,"use_lang"=>$use_lang
			,"work_yn"=>$work_yn
			,"agree_yn"=>$agree_yn
			,"login_emp_seq"=>$login_emp_seq
			,"c_date"=>$c_date
			,"admin_level"=>$admin_level
		);

	$qry_label = QRY_EMP_INSERT;
	$sql = query($qry_label,$qry_params);
	$qry_label = QRY_COMMON_IDENTITY_ACCESS;
	$sql .= query($qry_label, array());
	
	$result = sqlsrv_query($wvcs_dbcon, $sql);
	
	if($result){
		@sqlsrv_next_result($result);
		@sqlsrv_fetch($result);
		$emp_seq = @sqlsrv_get_field($result, 0);

		$args = array("emp_seq"=>$emp_seq
			, "auth_type"=>$admin_auth_type
			, "auth_preset_seq"=>$admin_auth_preset_seq
			, "create_emp_seq"=>$login_emp_seq
			, "admin_level"=>$admin_level
		);
		$result = $Model_manage->insertAdminMenuAuth($args);


		//�������
		if($result){
			$strMngOrg = is_array($mng_org) ? implode(",", $mng_org) : $mng_org;

			$qry_params = array("login_emp_seq"=>$login_emp_seq,"emp_seq"=>$emp_seq,"strMngOrg"=>$strMngOrg);
			$qry_label = QRY_ADMIN_MNG_ORG_INSERT;
			$sql = query($qry_label,$qry_params);

			$result = sqlsrv_query($wvcs_dbcon, $sql);
		}

		//������ĵ����
		if($result){
			$strMngScanCenter = is_array($mng_scan_center) ? implode(",", $mng_scan_center) : $mng_scan_center;

			$qry_params = array("login_emp_seq"=>$login_emp_seq,"emp_seq"=>$emp_seq,"strMngScanCenter"=>$strMngScanCenter);
			$qry_label = QRY_ADMIN_MNG_SCAN_CENTER_INSERT;
			$sql = query($qry_label,$qry_params);

			$result = sqlsrv_query($wvcs_dbcon, $sql);
		}
	}
				
}else if ($proc == "UPDATE") {
	//관리자정보 가져오기
	$args = array("emp_seq"=>$emp_seq);
	$Model_manage->SHOW_DEBUG_SQL = false;
	$result_m = $Model_manage->getEmpInfoBySeq($args);
	if($result_m){
		while ($row_m = sqlsrv_fetch_array($result_m,SQLSRV_FETCH_ASSOC)) {
			$old_admin_level = $row_m['admin_level'];
		}
	}

	$qry_params = array(
			"emp_seq"=>$emp_seq
			,"emp_pwd"=>$emp_pwd
			,"emp_name_encrypt"=>$emp_name_encrypt
			,"emp_pwd_hash"=>$emp_pwd_hash
			,"phone_no_encrypt"=>$phone_no_encrypt
			,"email_encrypt"=>$email_encrypt
			,"org_id"=>$org_id
			,"dept_seq"=>$dept_seq
			,"jpos_code"=>$jpos_code
			,"jgrade_code"=>$jgrade_code
			,"jduty_code"=>$jduty_code
			,"use_lang"=>$use_lang
			,"work_yn"=>$work_yn
			,"agree_yn"=>$agree_yn
			,"login_emp_seq"=>$login_emp_seq
			,"admin_level"=>$admin_level
		);
	$qry_label = QRY_EMP_UPDATE;
	$sql = query($qry_label,$qry_params);

	//printJson($sql);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

	if($result){
		
		/* 메뉴권한 삭제하지 않고 로그로 남도록 한다. 2024-02-14
		$qry_params = array("emp_seq"=>$emp_seq);
		$qry_label = QRY_ADMIN_MENU_DELETE;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);
		*/

		$qry_params = array("emp_seq"=>$emp_seq);
		$qry_label = QRY_ADMIN_MNG_ORG_DELETE;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);

		if($result){

			$qry_params = array("emp_seq"=>$emp_seq);
			$qry_label = QRY_ADMIN_MNG_SCAN_CENTER_DELETE;
			$sql = query($qry_label,$qry_params);

			$result = sqlsrv_query($wvcs_dbcon, $sql);
		}

		if($result){
			$is_update_auth = true;

			if ($admin_auth_type == "CUSTOMIZE") {
				$args = array("emp_seq"=>$emp_seq);
				$old_auth = $Model_manage->getAdminMenuAuth($args);

				if ($old_auth["auth_type"] == "CUSTOMIZE") {
					$is_update_auth = false;
				}
			}

			if ($is_update_auth) {
				// 메뉴 권한 그룹 정보 저장
				$args = array("emp_seq"=>$emp_seq
					, "auth_type"=>$admin_auth_type
					, "auth_preset_seq"=>$admin_auth_preset_seq
					, "create_emp_seq"=>$login_emp_seq
					, "admin_level"=>$admin_level
				);
				$result = $Model_manage->insertAdminMenuAuth($args);
			}
		}

		if($result){
			$strMngOrg = is_array($mng_org) ? implode(",", $mng_org) : $mng_org;

			$qry_params = array("login_emp_seq"=>$login_emp_seq,"emp_seq"=>$emp_seq,"strMngOrg"=>$strMngOrg);
			$qry_label = QRY_ADMIN_MNG_ORG_INSERT;
			$sql = query($qry_label,$qry_params);

			//printJson($sql);

			$result = sqlsrv_query($wvcs_dbcon, $sql);
		}

		//������ĵ����
		if($result){

			$strMngScanCenter = is_array($mng_scan_center) ? implode(",", $mng_scan_center) : $mng_scan_center;

			$qry_params = array("login_emp_seq"=>$login_emp_seq,"emp_seq"=>$emp_seq,"strMngScanCenter"=>$strMngScanCenter);
			$qry_label = QRY_ADMIN_MNG_SCAN_CENTER_INSERT;
			$sql = query($qry_label,$qry_params);

			$result = sqlsrv_query($wvcs_dbcon, $sql);
		}
	}


}else if ($proc == "DELETE") {
	
	//관리자는 삭제시 로그를 삭제하지 않는다. 2022-03-16
	/*
	$qry_params = array("emp_seq"=>$emp_seq);
	$qry_label = QRY_ADMIN_MENU_DELETE;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

	if($result){

		$qry_params = array("emp_seq"=>$emp_seq);
		$qry_label = QRY_ADMIN_MNG_ORG_DELETE;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);
	}

	if($result){

		$qry_params = array("emp_seq"=>$emp_seq);
		$qry_label = QRY_ADMIN_MNG_SCAN_CENTER_DELETE;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);
	}
	*/

	$qry_params = array("emp_seq"=>$emp_seq,"emp_no"=>$emp_no);
	$qry_label = QRY_ADMIN_DELETE_LOG;
	$sql = query($qry_label,$qry_params);

	//printJson($sql);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

	if($result){

		$qry_params = array("emp_seq"=>$emp_seq);
		$qry_label = QRY_EMP_DELETE;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);
	}

}

if($result) {
	if($proc=="CREATE"){
		$msg = "insert_ok";
	}else if($proc=="UPDATE"){
		$msg = "save_ok";
	}else if($proc=="DELETE"){
		$msg = "delete_ok";
	}
}else{
	$msg = "proc_error";
}

printJson($msg,$data=$emp_seq,$status=true,$result,$wvcs_dbcon);
?>