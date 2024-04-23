<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$job_gubun = $_POST["job_gubun"];
$code_name = $_POST["code_name"];
$sort = $_POST["sort"];
$useyn = $_POST["useyn"];
$proc = $_POST['proc'];
$val_seq = $_POST['val_seq'];

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,$proc);

$login_emp_seq = $_ck_user_seq;


if($proc=="CREATE"){

	if($job_gubun=="P"){

		$qry_params = array("code_name"=>$code_name,"sort"=>$sort,"useyn"=>$useyn,"login_emp_seq"=>$login_emp_seq,"job_gubun"=>$job_gubun);
		$qry_label = QRY_JOBPOS_INSERT;
		$sql = query($qry_label,$qry_params);

	}else if($job_gubun=="D"){

		$qry_params = array("code_name"=>$code_name,"sort"=>$sort,"useyn"=>$useyn,"login_emp_seq"=>$login_emp_seq,"job_gubun"=>$job_gubun);
		$qry_label = QRY_JOBDUTY_INSERT;
		$sql = query($qry_label,$qry_params);

	}else if($job_gubun=="G"){

		
		$qry_params = array("code_name"=>$code_name,"sort"=>$sort,"useyn"=>$useyn,"login_emp_seq"=>$login_emp_seq,"job_gubun"=>$job_gubun);
		$qry_label = QRY_JOBGRADE_INSERT;
		$sql = query($qry_label,$qry_params);

	}


	$result = sqlsrv_query($wvcs_dbcon, $sql);

}else if($proc=="UPDATE"){
	

	if($job_gubun=="P"){

		$qry_params = array("code_name"=>$code_name,"sort"=>$sort,"useyn"=>$useyn,"login_emp_seq"=>$login_emp_seq,"val_seq"=>$val_seq);
		$qry_label = QRY_JOBPOS_UPDATE;
		$sql = query($qry_label,$qry_params);


	}else if($job_gubun=="D"){

		$qry_params = array("code_name"=>$code_name,"sort"=>$sort,"useyn"=>$useyn,"login_emp_seq"=>$login_emp_seq,"val_seq"=>$val_seq);
		$qry_label = QRY_JOBDUTY_UPDATE;
		$sql = query($qry_label,$qry_params);

	}else if($job_gubun=="G"){

		$qry_params = array("code_name"=>$code_name,"sort"=>$sort,"useyn"=>$useyn,"login_emp_seq"=>$login_emp_seq,"val_seq"=>$val_seq);
		$qry_label = QRY_JOBGRADE_UPDATE;
		$sql = query($qry_label,$qry_params);


	}

	$result = sqlsrv_query($wvcs_dbcon, $sql);


}else if($proc=="DELETE"){

	if($job_gubun=="P"){

		$qry_params = array("val_seq"=>$val_seq);
		$qry_label = QRY_JOBPOS_DELETE;
		$sql = query($qry_label,$qry_params);


	}else if($job_gubun=="D"){

		$qry_params = array("val_seq"=>$val_seq);
		$qry_label = QRY_JOBDUTY_DELETE;
		$sql = query($qry_label,$qry_params);

		

	}else if($job_gubun=="G"){

		$qry_params = array("val_seq"=>$val_seq);
		$qry_label = QRY_JOBGRADE_DELETE;
		$sql = query($qry_label,$qry_params);

		

	}

	$result = sqlsrv_query($wvcs_dbcon, $sql);

}

if($result) {
	$status = true;
	$msg = "[$proc]".$_LANG_TEXT["procsuccess"][$lang_code];
}else{
	$status = false;
	$msg = "[$proc]".$_LANG_TEXT["procfail"][$lang_code];
}

printJson($msg,$data='',$status,$result,$wvcs_dbcon);
?>