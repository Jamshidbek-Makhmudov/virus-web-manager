<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include $_server_path . "/" . $_site_path ."/lib/wvcs_config.inc"; 
include $_server_path . "/" . $_site_path ."/lib/lib.inc"; 
include $_server_path . "/" . $_site_path . "/inc/function.inc";
include $_server_path . "/" . $_site_path . "/inc/query.php";

$rcvnum = $_REQUEST["rcvnum"];
$msg = $_REQUEST["msg"];

$qry_params = array();
$qry_label = QRY_POLICY;
$sql = query($qry_label,$qry_params);
$result = sqlsrv_query($wvcs_dbcon, $sql);
$row = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

$_sms_server = $row['sms_server'];
$_sms_port = $row['sms_port'];
$_sms_id = $row['sms_id'];
$_sms_pwd = $row['sms_pwd'];
$_sms_db = $row['sms_db'];
$_sms_table = $row['sms_table'];
$_sms_send_telno = $row['sms_send_telno'];

include $_server_path . "/" . $_site_path . "/inc/mysqlconnect.php";

if(!$dbmysql){
	echo "Failed Connect DB";
	exit;
}

$qry_params = array(
		"sms_table"=>$_sms_table
		,"receiver"=>$rcvnum
		,"sender"=>$_sms_send_telno
		,"send_msg"=>$msg
	);
$qry_label = QRY_SMS_SEND;
$sql = query($qry_label,$qry_params);	

@mysql_query($sql, $dbmysql);  

if(mysql_affected_rows() > 0){
	echo "success";
}else{
	echo "failed";
};
?>