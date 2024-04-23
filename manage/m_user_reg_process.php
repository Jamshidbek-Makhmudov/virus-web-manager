<?php
$page_name = "tree_list";
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
$proc = $_POST["proc"];
$src = $_POST["src"];

if($proc == "CREATE" && $emp_seq <> "") {
	printJson($_LANG_TEXT['wrongdatatranstext'][$lang_code]);
} else if ( ($proc == "UPDATE" || $proc == "DELETE" ) && $emp_seq == "") {
	printJson($_LANG_TEXT['wrongdatatranstext'][$lang_code]);
}

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,$proc);

//sha256 ��ȣȭ
$emp_pwd_hash = base64_encode(hash($password_hash_algo, $emp_pwd, true));

//������ ��ȿ�� üũ
if($proc != "DELETE"){

	//��й�ȣ ��ȿ��üũ
	if($emp_pwd != ""){
		
		list($result,$msg) = validCheck_Password($emp_pwd);
		
		if(!$result){
			//��й�ȣ�� �����빮��,�����ҹ���, ����, Ư������ �� �������� ������ 8~16�� �̳��� �Է��ϼ���
			printJson($msg=$_LANG_TEXT['passwordrules'][$lang_code]);
		}
		
		//���� ��й�ȣ�� ��
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

	//��ȭ��ȣ ��ȿ�� üũ
	if($phone_no != ""){
		
		list($result,$msg) = validCheck_Phone($phone_no);
		
		if(!$result){
			
			printJson($msg=$_LANG_TEXT['notvalidphonetext'][$lang_code]);
		}	
	}

	//�̸��� ��ȿ�� üũ
	if($email != ""){
		
		list($result,$msg) = validCheck_Email($email);
		
		if(!$result){
			
			printJson($msg=$_LANG_TEXT['notvalidemailtext'][$lang_code]);
		}	
	}

}



if($_encryption_kind=="1"){
	
	$emp_name_encrypt = "dbo.fn_EncryptString('".$emp_name."')";
	$phone_no_encrypt = "dbo.fn_EncryptString('".$phone_no."')";
	$email_encrypt = "dbo.fn_EncryptString('".$email."')";

}else if($_encryption_kind=="2"){
	
	$emp_name_encrypt = aes_256_enc($emp_name);
	$phone_no_encrypt = aes_256_enc($phone_no);
	$email_encrypt = aes_256_enc($email);

//	$phone_no_encrypt = "CAST('".$phone_no_encrypt."' AS VARBINARY(MAX))";
//	$email_encrypt = "CAST('".$email_encrypt."' AS VARBINARY(MAX))";
}

$c_date = date("Y-m-d H:i:s");

$login_emp_seq = $_ck_user_seq;

if ($proc == "CREATE") {

	$isExistedEmpNo = isExistedEmpNo($emp_no,$wvcs_dbcon);

	if($isExistedEmpNo){

		printJson($_LANG_TEXT['existedempno'][$lang_code]);
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

	//printJson($sql);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

	if($result){

		$qry_params = array();
		$qry_label = QRY_COMMON_IDENTITY;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);

	}

	if($result){

		$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		$emp_seq = $row['seq'];

		if($admin_level != ""){

			$strMenu = is_array($menu) ? implode(",", $menu) : $menu;

			$qry_params = array("login_emp_seq"=>$login_emp_seq,"emp_seq"=>$emp_seq,"strMenu"=>$strMenu);
			$qry_label = QRY_ADMIN_MENU_INSERT;
			$sql = query($qry_label,$qry_params);
			
			$result = sqlsrv_query($wvcs_dbcon, $sql);

			echo $sql;

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

		}//if($admin_level != ""){
	}
	
				
}else if ($proc == "UPDATE") {


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

		/* �޴����� �������� �ʰ� �α׷� ������ �Ѵ�. 2024-02-14
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

		
		if($admin_level != ""){

			if($result){

				$strMenu = is_array($menu) ? implode(",", $menu) : $menu;

				$qry_params = array("login_emp_seq"=>$login_emp_seq,"emp_seq"=>$emp_seq,"strMenu"=>$strMenu);
				$qry_label = QRY_ADMIN_MENU_INSERT;
				$sql = query($qry_label,$qry_params);

				$result = sqlsrv_query($wvcs_dbcon, $sql);

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

		} //if($admin_level != ""){
	}


}else if ($proc == "DELETE") {


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

	if($result){

		$qry_params = array("emp_seq"=>$emp_seq);
		$qry_label = QRY_EMP_DELETE;
		$sql = query($qry_label,$qry_params);


		$result = sqlsrv_query($wvcs_dbcon, $sql);
	}



}

if($result) {
	$data = array("src"=>$src,"emp_seq"=>$emp_seq);
	$status = true;
	$msg= $proc=="DELETE" ? "delete_ok" : "save_ok";
}else{
	$status = false;
	$msg = "proc_error";
}

printJson($msg,$data,$status,$result,$wvcs_dbcon);
?>