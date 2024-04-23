<?php
$page_name = "kabang_emp_list";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$proc = $_POST['proc'];
$emp_seq = $_POST["emp_seq"];
$emp_id_list = $_POST["emp_id"];
$emp_name_list = $_POST["emp_name"];
$dept_name = $_POST["dept_name"];
$admin_level = $_POST["admin_level"];
$work_yn = $_POST["work_yn"];

$org_id = $_POST["org_id"];

$admin_level = $_POST["admin_level"];
$menu = $_POST["menu"];
$mng_org = $_POST['mng_org'];
$mng_scan_center = $_POST['mng_scan_center'];

$admin_auth_type = $_POST["admin_auth_type"];
$admin_auth_preset_seq = $_POST["admin_auth_preset_seq"];

if ($emp_seq == "" && $emp_id_list=="") {
	printJson_ERROR('invalid_data');
}

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,$proc);

$login_emp_seq = $_ck_user_seq;

$create_emp_seq = array();
if ($proc == "CREATE") {

	for($i = 0 ; $i < sizeof($emp_id_list) ; $i++){	

			$emp_id = $emp_id_list[$i];
			$emp_name  = $emp_name_list[$i];

			$isExistedEmpNo = isExistedEmpNo($emp_id,$wvcs_dbcon,false);

			if($isExistedEmpNo){

				printJson_ERROR($_LANG_TEXT['existedempno'][$lang_code]."(".$emp_id.")");
			}

			$c_date = date("Y-m-d H:i:s");
			
			//sha256 암호화
			$emp_pwd = "kabang@!004";
			$emp_pwd_hash = base64_encode(hash($password_hash_algo, $emp_pwd, true));

			$qry_params = array(
					"emp_no"=>$emp_id
					,"emp_name_encrypt"=>aes_256_enc($emp_name)
					,"emp_pwd_hash"=>$emp_pwd_hash
					,"phone_no_encrypt"=>''
					,"email_encrypt"=>''
					,"org_id"=>$org_id
					,"dept_seq"=>''
					,"jpos_code"=>''
					,"jgrade_code"=>''
					,"jduty_code"=>''
					,"use_lang"=>'KR'
					,"work_yn"=>'Y'
					,"agree_yn"=>''
					,"login_emp_seq"=>$login_emp_seq
					,"c_date"=>$c_date
					,"admin_level"=>$admin_level
				);
			$qry_label = QRY_EMP_INSERT;
			$sql = query($qry_label,$qry_params);
			
			$qry_params = array();
			$qry_label = QRY_COMMON_IDENTITY;
			$sql .= query($qry_label,$qry_params);

			$result = sqlsrv_query($wvcs_dbcon, $sql);

			if ($result) {

				@sqlsrv_next_result($result);
				@sqlsrv_fetch($result);
				$emp_seq = @sqlsrv_get_field($result, 0);

				$create_emp_seq[] = $emp_seq;
			
				// 메뉴 권한 그룹 정보 저장
				$args = array("emp_seq"=>$emp_seq
					, "auth_type"=>$admin_auth_type
					, "auth_preset_seq"=>$admin_auth_preset_seq
					, "create_emp_seq"=>$login_emp_seq
					, "admin_level"=>$admin_level
				);
				$result = $Model_manage->insertAdminMenuAuth($args);
		
				//관리기관정보
				if ($result) {

					$strMngOrg = is_array($mng_org) ? implode(",", $mng_org) : $mng_org;
					$qry_params = array("login_emp_seq"=>$login_emp_seq,"emp_seq"=>$emp_seq,"strMngOrg"=>$strMngOrg);
					$qry_label = QRY_ADMIN_MNG_ORG_INSERT;
					$sql = query($qry_label,$qry_params);
					$result = sqlsrv_query($wvcs_dbcon, $sql);

				}

				//관리센터정보
				if ($result) {

					$strMngScanCenter = is_array($mng_scan_center) ? implode(",", $mng_scan_center) : $mng_scan_center;

					$qry_params = array("login_emp_seq"=>$login_emp_seq,"emp_seq"=>$emp_seq,"strMngScanCenter"=>$strMngScanCenter);
					$qry_label = QRY_ADMIN_MNG_SCAN_CENTER_INSERT;
					$sql = query($qry_label,$qry_params);

					$result = sqlsrv_query($wvcs_dbcon, $sql);
				}
		}

	}
		
}else if($proc=="UPDATE"){

	//관리자정보 가져오기
	$Model_manage = new Model_manage();
	$Model_manage->SHOW_DEBUG_SQL = false;

	$args = array("emp_seq"=>$emp_seq);
	$result_m = $Model_manage->getEmpInfoBySeq($args);
	if($result_m){
		while ($row_m = sqlsrv_fetch_array($result_m,SQLSRV_FETCH_ASSOC)) {
			$old_admin_level = $row_m['admin_level'];
		}
	}

	$args = array(
		"emp_seq"=>$emp_seq
		,"work_yn"=>$work_yn
		,"admin_level"=>$admin_level
	);
	$Model_manage = new Model_manage;
	$result = $Model_manage->updateEmpInfo($args);


	if ($result) {
		/* 메뉴권한 삭제하지 않고 로그로 남도록 한다. 2024-02-14
			$qry_params = array("emp_seq"=>$emp_seq);
			$qry_label  = QRY_ADMIN_MENU_DELETE;
			$sql = query($qry_label,$qry_params);
			$result = sqlsrv_query($wvcs_dbcon, $sql);
		*/
		$qry_params = array("emp_seq"=>$emp_seq);
		$qry_label  = QRY_ADMIN_MNG_ORG_DELETE;
		$sql = query($qry_label,$qry_params);
		$result = sqlsrv_query($wvcs_dbcon, $sql);
		
		if ($result) {
			$qry_params = array("emp_seq"=>$emp_seq);
			$qry_label  = QRY_ADMIN_MNG_SCAN_CENTER_DELETE;
			$sql = query($qry_label,$qry_params);
			$result = sqlsrv_query($wvcs_dbcon, $sql);
		}
		
		if ($result) {
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

	
		
		//관리기관정보
		if ($result) {

			$strMngOrg = is_array($mng_org) ? implode(",", $mng_org) : $mng_org;

			$qry_params = array("login_emp_seq"=>$login_emp_seq,"emp_seq"=>$emp_seq,"strMngOrg"=>$strMngOrg);
			$qry_label = QRY_ADMIN_MNG_ORG_INSERT;
			$sql = query($qry_label,$qry_params);

			//printJson($sql);

			$result = sqlsrv_query($wvcs_dbcon, $sql);

		}

		//관리센터정보
		if ($result) {

			$strMngScanCenter = is_array($mng_scan_center) ? implode(",", $mng_scan_center) : $mng_scan_center;

			$qry_params = array("login_emp_seq"=>$login_emp_seq,"emp_seq"=>$emp_seq,"strMngScanCenter"=>$strMngScanCenter);
			$qry_label = QRY_ADMIN_MNG_SCAN_CENTER_INSERT;
			$sql = query($qry_label,$qry_params);

			$result = sqlsrv_query($wvcs_dbcon, $sql);
		}
	}

}else if($proc=="DELETE"){

	$emp_no = $_POST['emp_id'];
	
	$qry_params = array("emp_seq"=>$emp_seq,"emp_no"=>$emp_no);
	$qry_label = QRY_ADMIN_DELETE_LOG;
	$sql = query($qry_label,$qry_params);

	//printJson($sql);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

	if ($result) {

		$qry_params = array("emp_seq"=>$emp_seq);
		$qry_label = QRY_EMP_DELETE;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);
	}
}

if($result) {
	if($proc=='DELETE'){
		printJson_OK('delete_ok');
	}else if($proc=='CREATE'){
		$str_emp_list_seq = implode(",",$create_emp_seq);
		printJson_OK('insert_ok',$data=$str_emp_list_seq);
	}else {
		printJson_OK('save_ok');
	}
}else{
	printJson_ERROR('proc_error');
}
?>