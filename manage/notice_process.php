<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$proc = $_POST['proc'];
$notice_seq = $_POST['n_seq'];
$gubun = $_POST['n_gubun'];
$title = $_POST['n_title'];
$contents = $_POST['n_contents'];

$title = str_replace("'", "''", $title);
$title = str_replace("\\", "", $title);

$contents = str_replace("'", "''", $contents);
$contents = str_replace("\\", "", $contents);

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,$proc);

if($_FILES['n_file']["name"]) {
	$file_real_name = $_FILES['n_file']["name"];
	$up_file = upload_file($_FILES['n_file']['tmp_name'],$_FILES['n_file']['name'],$_FILES['n_file']['size']);
	$up_savedir = $_data_folder;
	$sql_upfile = ", pds_file_path = '$up_savedir'		, pds_file_name = '$up_file' , pds_file_real_name = '$file_real_name' ";
}

if($notice_seq != "") {
		
		
		$qry_params = array("notice_seq"=>$notice_seq);
		$qry_label = QRY_NOTICE_FILE;
		$sql = query($qry_label,$qry_params);
		
		$result = sqlsrv_query($wvcs_dbcon, $sql );
		
		if($result){
			while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
					
					$old_file = $_SERVER['DOCUMENT_ROOT'].$row['pds_file_path']."/".$row['pds_file_name'];
			}
		}
}

$emp_seq = $_ck_user_seq;
//$ip = $_SERVER['REMOTE_ADDR'];

if($proc == "CREATE") {

	$qry_params = array("gubun"=>$gubun,"title"=>$title,"contents"=>$contents,"emp_seq"=>$emp_seq,"file_real_name"=>$file_real_name,"up_file"=>$up_file,"up_savedir"=>$up_savedir);
	$qry_label = QRY_NOTICE_INSERT;
	$sql = query($qry_label,$qry_params);
	
	$result = sqlsrv_query($wvcs_dbcon, $sql);

} else if ($proc == "UPDATE") {
	
	if($up_file != "" && $old_file != "") @unlink("$old_file");
	
	$qry_params = array("notice_seq"=>$notice_seq,"sql_upfile"=>$sql_upfile,"title"=>$title,"contents"=>$contents,"emp_seq"=>$emp_seq);
	$qry_label = QRY_NOTICE_UPDATE;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

} else if ($proc == "DELETE") {

	//파일 삭제
	if($old_file != "") @unlink("$old_file");


	$qry_params = array("notice_seq"=>$notice_seq);
	$qry_label = QRY_NOTICE_DELETE;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);
}

if($result) {
	echo "[$proc] ".$_LANG_TEXT['procsuccess'][$lang_code];
}else{
	echo "[$proc] ".$_LANG_TEXT['procfail'][$lang_code];
}

if($result) sqlsrv_free_stmt($result);  
sqlsrv_close($wvcs_dbcon);
?>