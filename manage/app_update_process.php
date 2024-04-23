<?php
$page_name = "app_update";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$proc = $_POST['proc'];
$app_seq = $_POST['app_seq'];
$gubun = $_POST['gubun'];
$file_type = $_REQUEST['file_type'];
$app_name = $_POST['app_name'];
$patch_dt = $_POST['patch_dt'];
$patch_dt_div = $_POST['patch_dt_div'];
$patch_time = $_POST['patch_time'];
$install_path = $_POST['install_path'];
$memo = $_POST['memo'];
$app_ver = $_POST['app_ver'];
$use_yn = $_POST['use_yn'];
$kiosk =$_POST['kiosk'];


$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,$proc);

if(is_array($kiosk)){
	$str_kiosk = implode(",",$kiosk);
}else{
	$str_kiosk = "";
}

$app_name = str_replace("'", "''", $app_name);
$app_name = str_replace("\\", "", $app_name);

$memo = str_replace("'", "''", $memo);
$memo = str_replace("\\", "", $memo);

$up_savedir = "/data/dfence_update/";

if($patch_dt_div=="every"){	//매일 특정시간
	$patch_dt = "1900-01-01 {$patch_time}:00:00";
}

if($_FILES['app_file']["name"]) {
	$file_real_name = $_FILES['app_file']["name"];
	$file_size = $_FILES['app_file']['size'];

	$up_savedir .= $gubun."/";
	$up_file = upload_file($_FILES['app_file']['tmp_name'],$_FILES['app_file']['name'],$_FILES['app_file']['size'],$up_savedir);
	$sql_upfile = ", server_path = '$up_savedir'		, file_name = '$up_file' , real_name = '$file_real_name' ";
}

if($app_seq != "") {

		$qry_params = array("app_seq"=>$app_seq);
		$qry_label = QRY_APP_UPDATE_FILE;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql );

		if($result){

			while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
					
					$old_file = $_SERVER['DOCUMENT_ROOT'].$row['server_path']."/".$row['file_name'];
			}
		}
}

$emp_seq = $_ck_user_seq;
//$ip = $_SERVER['REMOTE_ADDR'];

$com_id = COMPANY_CODE;

if($proc == "CREATE") {
	
	$qry_params = array(
			"com_id"=>$com_id
			,"app_name"=>$app_name
			,"app_ver"=>$app_ver
			,"file_size"=>$file_size
			,"up_savedir"=>$up_savedir
			,"install_path"=>$install_path
			,"patch_dt"=>$patch_dt
			,"use_yn"=>$use_yn
			,"emp_seq"=>$emp_seq
			,"file_real_name"=>$file_real_name
			,"memo"=>$memo
			,"gubun"=>$gubun
			,"up_file"=>$up_file
			,"file_type"=>$file_type
			,"kiosk"=>$str_kiosk
		);

	$qry_label = QRY_APP_UPDATE_INSERT;
	$sql = query($qry_label,$qry_params);

	
	$result = sqlsrv_query($wvcs_dbcon, $sql);

} else if ($proc == "UPDATE") {
	
	if($up_file != "" && $old_file != "") @unlink("$old_file");


	$qry_params = array(
			"app_seq"=>$app_seq
			,"gubun"=>$gubun
			,"file_type"=>$file_type
			,"app_name"=>$app_name
			,"app_ver"=>$app_ver
			,"sql_upfile"=>$sql_upfile
			,"patch_dt"=>$patch_dt
			,"install_path"=>$install_path
			,"memo"=>$memo
			,"use_yn"=>$use_yn
			,"emp_seq"=>$emp_seq
			,"kiosk"=>$str_kiosk
		);
	$qry_label = QRY_APP_UPDATE_UPDATE;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

} else if ($proc == "DELETE") {

	//파일 삭제
	if($old_file != "") @unlink("$old_file");

	$qry_params = array("app_seq"=>$app_seq);
	$qry_label = QRY_APP_UPDATE_DELETE;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);
}

if($result){
	$msg = $proc=="DELETE" ? "delete_ok" : "save_ok";
	printJson_OK($msg);
}else{
	printJson_OK('proc_error');
}

?>