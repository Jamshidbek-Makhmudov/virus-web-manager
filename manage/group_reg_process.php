<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

//group_seq,group_name,org_id,memo,create_admin,create_dt,modify_admin,modify_dt

$group_seq = $_REQUEST["group_seq"];
$group_name = $_REQUEST["group_name"];
$org_id = $_REQUEST["org_id"];
$memo = $_REQUEST["memo"];
$proc = $_REQUEST["proc"];

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,$proc);

if ($proc <> "DELETE") {
	$org_id_insert = "'".implode("','", $org_id)."'";
}

$login_emp_seq = $_ck_user_seq;


$c_date = date("Y-m-d H:i:s");

if ($proc == "CREATE") {
	
	$qry_params = array("group_name"=>$group_name,"memo"=>$memo,"login_emp_seq"=>$login_emp_seq,"c_date"=>$c_date,"org_id_insert"=>$org_id_insert);
	$qry_label = QRY_GROUP_INSERT;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);
				
} else if ($proc == "UPDATE") {

	$qry_params = array("group_seq"=>$group_seq,"group_name"=>$group_name,"memo"=>$memo,"login_emp_seq"=>$login_emp_seq,"c_date"=>$c_date,"org_id_insert"=>$org_id_insert);
	$qry_label = QRY_GROUP_UPDATE;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

}else if ($proc == "DELETE") {

	$qry_params = array("group_seq"=>$group_seq);
	$qry_label = QRY_GROUP_DELETE;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

}

if($result) {
	echo "[$proc] ".$_LANG_TEXT['procsuccess'][$lang_code];
}else{
	echo "[$proc] ".$_LANG_TEXT['procfail'][$lang_code];
}
?>