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
$emp_seq_list = $_POST["emp_seq_list"];
$admin_level = $_POST["admin_level"];

$org_id = $_POST["org_id"];
$menu = $_POST["menu"];	//메뉴권한은 별도 설정
$mng_org = $_POST['mng_org'];
$mng_scan_center = $_POST['mng_scan_center'];

$admin_auth_type = $_POST["admin_auth_type"];
$admin_auth_preset_seq = $_POST["admin_auth_preset_seq"];

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'UPDATE');

$Model_manage = new Model_manage;

if($emp_seq_list==""){	//전체

	$result = $Model_manage->getKabangEmpListAll($args);
	if($result){
		while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
			$arr_emp_seq_list[] = $row['emp_seq'];
			$emp_admin_level[] = $row['admin_level'];
		}
	}
}else{

	$args = array("search_sql"=>" and emp_seq in (".$emp_seq_list.")");
	$result = $Model_manage->getEmpInfoBySeq($args);
	if($result){
		while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

			$arr_emp_seq_list[] = $row['emp_seq'];
			$emp_admin_level[] = $row['admin_level'];
		}
	}
}

$login_emp_seq = $_ck_user_seq;

for($i = 0 ; $i < count($arr_emp_seq_list) ; $i++){

		$emp_seq = $arr_emp_seq_list[$i];
		$old_admin_level = $emp_admin_level[$i];

		$args = array(
			"emp_seq"=>$emp_seq
			,"admin_level"=>$admin_level
		);

		$result = $Model_manage->updateEmpLevel($args);

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
			
			//접근 메뉴정보
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
			
			//관리기관정보
			if($result){
				$strMngOrg = is_array($mng_org) ? implode(",", $mng_org) : $mng_org;
				$qry_params = array("login_emp_seq"=>$login_emp_seq,"emp_seq"=>$emp_seq,"strMngOrg"=>$strMngOrg);
				$qry_label = QRY_ADMIN_MNG_ORG_INSERT;
				$sql = query($qry_label,$qry_params);

				$result = sqlsrv_query($wvcs_dbcon, $sql);

			}

			//관리센터정보
			if($result){
				$strMngScanCenter = is_array($mng_scan_center) ? implode(",", $mng_scan_center) : $mng_scan_center;
				$qry_params = array("login_emp_seq"=>$login_emp_seq,"emp_seq"=>$emp_seq,"strMngScanCenter"=>$strMngScanCenter);
				$qry_label = QRY_ADMIN_MNG_SCAN_CENTER_INSERT;
				$sql = query($qry_label,$qry_params);

				$result = sqlsrv_query($wvcs_dbcon, $sql);
			}
		}

}

if($result) {
	printJson_OK('save_ok');
}else{
	printJson_ERROR('proc_error');
}
?>