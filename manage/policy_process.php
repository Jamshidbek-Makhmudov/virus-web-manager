<?php
$page_name = "policy";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
//james
$login_attempt_cnt = $_REQUEST["login_attempt_cnt"];
$login_ip_limit_yn = $_REQUEST["login_ip_limit_yn"];

$_win_update_yn = $_REQUEST["win_update_yn"];
$_win_weak_chk_yn = $_REQUEST["win_weak_chk_yn"];
$_vacc_chk_yn = $_REQUEST["vacc_chk_yn"];
$_vacc_scan_type = $_REQUEST["vacc_scan_type"];
$_vacc_patch_term = $_REQUEST["vacc_patch_term"];
$_win_update_term = $_REQUEST["win_update_term"];
$_pc_chkin_term = $_REQUEST["pc_chkin_availabled_term"];
$_kiosk_chkin_term = $_REQUEST["kiosk_chkin_availabled_term"];
$_scrlock_yn = $_REQUEST["scrlock_yn"];
$_scrlock_warn_msg = $_REQUEST["scrlock_warn_msg"];
$_admin_pwd_change_term = $_REQUEST["admin_pwd_change_term"];
$_web_type = $_REQUEST["web_type"];
$_web_server = $_REQUEST["web_server"];
$_web_port = $_REQUEST["web_port"];
$_ftp_type = $_REQUEST["ftp_type"];
$_ftp_server = $_REQUEST["ftp_server"];
$_ftp_port = $_REQUEST["ftp_port"];
$_mail_type = $_REQUEST["mail_type"];
$_mail_server = $_REQUEST["mail_server"];
$_mail_port = $_REQUEST["mail_port"];
$_mail_id = $_REQUEST["mail_id"];
$_mail_pwd = $_REQUEST["mail_pwd"];
$_sms_type = $_REQUEST["sms_type"];
$_sms_server = $_REQUEST["sms_server"];
$_sms_port = $_REQUEST["sms_port"];
$_sms_url = $_REQUEST["sms_url"];
$_sms_db = $_REQUEST["sms_db"];
$_sms_table = $_REQUEST["sms_table"];
$_sms_id = $_REQUEST["sms_id"];
$_sms_pwd = $_REQUEST["sms_pwd"];
$_sms_send_telno = $_REQUEST["sms_send_telno"];
$_app_update_server = $_REQUEST["app_update_server"];
$_app_api_server = $_REQUEST["app_api_server"];
$_connection_yn = $_REQUEST["connection_yn"];
$_otp_yn = $_REQUEST["otp_yn"];
$_data_keep_day = $_REQUEST["data_keep_day"];
$_checkin_kiosk_in_type = $_REQUEST["checkin_kiosk_in_type"];
$_checkin_file_send_type = $_REQUEST["checkin_file_send_type"];
$_file_scan_yn = $_REQUEST["file_scan_yn"];
$_db_encription_kind = $_REQUEST["db_encription_kind"];
$_db_encription_flag = $_REQUEST["db_encription_flag"];

$_v3_use_yn = $_REQUEST["v3_use_yn"];
if($_v3_use_yn=="") $_v3_use_yn = "N";

$_eset_use_yn = $_REQUEST["eset_use_yn"];
if($_eset_use_yn=="") $_eset_use_yn = "N";

if($_db_encription_kind=="") $_db_encription_kind=="2";	//AES_Rijndael_Encript
if($_db_encription_flag=="") $_db_encription_flag=="1";	//전화번호,이메일 암호화

if($login_attempt_cnt =="") $login_attempt_cnt = "0";
if($login_ip_limit_yn =="") $login_ip_limit_yn = "N";
if($_file_scan_yn=="") $_file_scan_yn = "N";

$_file_scan_device = $_POST['file_scan_device'];
if($_file_scan_device=="") {
	$_file_scan_device = "ALL";
}else{
	$_file_scan_device = implode(",",$_file_scan_device);
}

$_checkin_file_send_device = $_POST['checkin_file_send_device'];
if($_checkin_file_send_device=="") {
	$_checkin_file_send_device = "ALL";
}else{
	$_checkin_file_send_device = implode(",",$_checkin_file_send_device);
}

$_kiosk_data_delete_day= $_POST['kiosk_data_delete_day'];
if($_kiosk_data_delete_day=="") $_kiosk_data_delete_day = "0";


$_visit_checkout_batch_yn = $_POST['visit_checkout_batch_yn'];
if($_visit_checkout_batch_yn=="") $_visit_checkout_batch_yn = "N";

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'CREATE');

# 작업로그기록
//$_work_log_seq = WriteAdminActLog($proc_name,'CREATE');

$qry_params = array(
		"admin_pwd_change_term"=>$_admin_pwd_change_term
		,"connection_yn"=>$_connection_yn
		,"scrlock_yn"=>$_scrlock_yn
		,"checkin_pc_term"=>$_pc_chkin_term
		,"checkin_kiosk_term"=>$_kiosk_chkin_term
		,"windows_update_yn"=>$_win_update_yn
		,"windows_update_term"=>$_win_update_term
		,"vaccine_check_yn"=>$_vacc_chk_yn
		,"vacc_patch_term"=>$_vacc_patch_term
		,"vacc_scan_type"=>$_vacc_scan_type
		,"window_weakness_check_yn"=>$_win_weak_chk_yn
		,"scrlock_warn_comment"=>$_scrlock_warn_msg
		,"ftp_type"=>$_ftp_type
		,"ftp_server"=>$_ftp_server
		,"ftp_port"=>$_ftp_port
		,"web_type"=>$_web_type
		,"web_server"=>$_web_server
		,"web_port"=>$_web_port
		,"app_update_server"=>$_app_update_server
		,"app_api_server"=>$_app_api_server
		,"mail_server"=>$_mail_server
		,"mail_port"=>$_mail_port
		,"mail_id"=>$_mail_id
		,"mail_pwd"=>$_mail_pwd
		,"mail_type"=>$_mail_type
		,"sms_type"=>$_sms_type
		,"sms_server"=>$_sms_server
		,"sms_port"=>$_sms_port
		,"sms_id"=>$_sms_id
		,"sms_pwd"=>$_sms_pwd
		,"sms_db"=>$_sms_db
		,"sms_table"=>$_sms_table
		,"sms_url"=>$_sms_url
		,"sms_send_telno"=>$_sms_send_telno
		,"otp_yn"=>$_otp_yn
		,"data_keep_day"=>$_data_keep_day
		,"checkin_kiosk_in_type"=>$_checkin_kiosk_in_type
		,"checkin_file_send_type"=>$_checkin_file_send_type
		,"create_emp_seq"=>$_ck_user_seq
		,"login_attempt_cnt"=>$login_attempt_cnt,"login_ip_limit_yn"=>$login_ip_limit_yn
		,"file_scan_yn"=>$_file_scan_yn
		,"db_encription_kind"=>$_db_encription_kind
		,"db_encription_flag"=>$_db_encription_flag
		,"v3_use_yn"=>$_v3_use_yn
		,"eset_use_yn"=>$_eset_use_yn
		,"kiosk_data_delete_day"=>$_kiosk_data_delete_day
		,"file_scan_device"=>$_file_scan_device
		,"checkin_file_send_device"=>$_checkin_file_send_device
		,"visit_checkout_batch_yn"=>$_visit_checkout_batch_yn
	);

$qry_label = QRY_POLICY_INSERT;
$sql = query($qry_label,$qry_params);

//echo $sql;

$result = sqlsrv_query($wvcs_dbcon, $sql);

if($result){
	printJson_OK('save_ok');
}else{
	printJson_OK('proc_error');
}
?>