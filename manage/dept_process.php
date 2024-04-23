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

$org_id = $_REQUEST["sel_org"];
$dept_seq = $_REQUEST["dept_seq"];
$p_dept_seq = $_REQUEST["p_dept_seq"];
$dept_name = $_REQUEST["dept_name"];
$dept_chief_seq = $_REQUEST["dept_chief"];
$use_yn = $_REQUEST["use_yn"];
$sort = $_REQUEST["sort"];
$dept_auth1 = $_REQUEST["dept_auth1"];
$dept_auth2 = $_REQUEST["dept_auth2"];
$dept_auth3 = $_REQUEST["dept_auth3"];
$proc = $_REQUEST["proc"];
$src = $_REQUEST["src"];

 if (($proc == "UPDATE" || $proc == "DELETE" ) && $dept_seq == "") {
	 
	 printJson($msg=$_LANG_TEXT["wrongdatatranstext"][$lang_code]);
}

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,$proc);

$c_date = date("Y-m-d H:i:s");
$dept_chief_seq = ($dept_chief_seq=="" ? "NULL" : $dept_chief_seq);
$sort = ($sort=="" ? "NULL" : $sort);
if($p_dept_seq == "") $p_dept_seq = "0";

$login_emp_seq = $_ck_user_seq;

if ($proc == "CREATE") {

	if($p_dept_seq=="0"){

		$depth = "1";

		$qry_params = array(
				"org_id"=>$org_id
				,"dept_name"=>$dept_name
				,"p_dept_seq"=>$p_dept_seq
				,"depth"=>$depth
				,"dept_chief_seq"=>$dept_chief_seq
				,"use_yn"=>$use_yn
				,"sort"=>$sort
				,"dept_auth1"=>$dept_auth1
				,"dept_auth2"=>$dept_auth2
				,"dept_auth3"=>$dept_auth3
				,"create_emp_seq"=>$create_emp_seq
				,"create_dt"=>$create_dt
			);
		$qry_label = QRY_DEPT_PARENT_INSERT;
		$sql = query($qry_label,$qry_params);

	}else{

		$qry_params = array(
				"org_id"=>$org_id
				,"dept_name"=>$dept_name
				,"p_dept_seq"=>$p_dept_seq
				,"dept_chief_seq"=>$dept_chief_seq
				,"use_yn"=>$use_yn
				,"sort"=>$sort
				,"dept_auth1"=>$dept_auth1
				,"dept_auth2"=>$dept_auth2
				,"dept_auth3"=>$dept_auth3
				,"create_emp_seq"=>$create_emp_seq
				,"create_dt"=>$create_dt
				,"p_dept_seq"=>$p_dept_seq
			);

		$qry_label = QRY_DEPT_INSERT;
		$sql = query($qry_label,$qry_params);

	}

	//printJson($sql);
	$result = sqlsrv_query($wvcs_dbcon, $sql);

	if($result) {

		$qry_params = array();
		$qry_label = QRY_COMMON_IDENTITY;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);
		$row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

		$dept_seq = $row['seq'];

	}
			
}else if ($proc == "UPDATE") {
		
		$depth_sql = ($p_dept_seq=="0" ? "'1'" : "(select depth+1 from tb_department where dept_seq = '".$p_dept_seq."')");

		$qry_params = array(
				"org_id"=>$org_id
				,"p_dept_seq"=>$p_dept_seq
				,"dept_name"=>$dept_name
				,"dept_chief_seq"=>$dept_chief_seq
				,"use_yn"=>$use_yn
				,"sort"=>$sort
				,"depth_sql"=>$depth_sql
				,"dept_auth1"=>$dept_auth1
				,"dept_auth2"=>$dept_auth2
				,"dept_auth3"=>$dept_auth3
				,"login_emp_seq"=>$login_emp_seq
				,"c_date"=>$c_date
				,"dept_seq"=>$dept_seq

			);
		$qry_label = QRY_DEPT_UPDATE;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);

}else if ($proc == "DELETE") {

	$qry_params = array("dept_seq"=>$dept_seq);
	$qry_label = QRY_DEPT_SUB_COUNT;
	$sql = query($qry_label,$qry_params);
	
	$result = sqlsrv_query($wvcs_dbcon, $sql);
	$row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

	if($row['cnt'] > 0){
		
		printJson($_LANG_TEXT["nodeletedept"][$lang_code]);
	}

	$qry_params = array("dept_seq"=>$dept_seq);
	$qry_label = QRY_DEPT_EMP_COUNT;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);
	$row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

	if($row['cnt'] > 0){

		printJson($_LANG_TEXT["nodeletedeptemp"][$lang_code]);
	}

	$qry_params = array("dept_seq"=>$dept_seq);
	$qry_label = QRY_DEPT_DELETE;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

}

if($result) {
	$data = array("src"=>$src,"dept_seq"=>$dept_seq);
	$msg= $proc=="DELETE" ? "delete_ok" : "save_ok";
	$status = true;
}else{
	$msg= "proc_error";
	$status = false;
}

printJson($msg,$data,$status,$result,$wvcs_dbcon);
?>