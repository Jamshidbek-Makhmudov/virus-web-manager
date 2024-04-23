<?php
ini_set('max_execution_time','10800');
ini_set('memory_limit','10240M');

//ini_set 설정 적용되지 않음.
//ini_set('max_input_time','-1');
//ini_set('post_max_size','200G');
//ini_set('upload_max_filesize','200G');
//ini_set('post_max_size','0');
//ini_set('upload_max_filesize','0');

//ini_set("display_startup_errors", "1");
//ini_set("display_errors", "1");
//error_reporting(E_ALL);

ignore_user_abort(true);
set_time_limit(0);
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

/*
* vcs 점검 반입파일 서버 전송
*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";
include "./common.php";

$v_wvcs_seq = $_POST['v_wvcs_seq'];
$_files = $_FILES['upload_file'];

/*ini 설정정보*/
/*
echo "<div>post_max_size:".ini_get('post_max_size')."</div>";
echo "<div>upload_max_filesize:".ini_get('upload_max_filesize')."</div>";
echo "<div>max_input_time:".ini_get('max_input_time')."</div>";
echo "<div>max_execution_time:".ini_get('max_execution_time')."</div>";
var_dump($_files);

echo "<div>start_time : ".date("YmdHis")."</div>";
*/

/*파일 업로드 로그 기록*/
function write_file_send_log($send_result,$send_result_msg){
	
	global $wvcs_dbcon;
	global $v_wvcs_seq;
	
	$file_send_status = ($send_result=="OK" ? "1" : "-1");
	$file_send_date = date("YmdHis");
	$refer ='WEB';

	$params = array( 
							 array(intval($v_wvcs_seq), SQLSRV_PARAM_IN),
							 array($file_send_status, SQLSRV_PARAM_IN),
							 array($file_send_date, SQLSRV_PARAM_IN),
							 array($send_result_msg, SQLSRV_PARAM_IN),
							 array($refer, SQLSRV_PARAM_IN)
						   );
	@sqlsrv_query($wvcs_dbcon, '{CALL up_UpdateFIleSendInfo(?, ?, ?, ?,?)}', $params);

	echo $send_result_msg;
	exit;
}

if($v_wvcs_seq==""){
	write_file_send_log('FAIL',"FALSE:INVALIED_DATA");
}

//업로드 허용 파일 확장자
$allow_ext = array("zip","7z");

if($_files["name"]) {
	
	$file = $_files["tmp_name"];
	$file_name = $_files["name"];
	$file_size = $_files['size'];

	$e_pos = strripos($file_name,  "."); //끝에서 . 를 찾는다.
	$file_ext = substr($file_name , $e_pos+1);
	
	/* 허용 확장자
	if(in_array($file_ext,$allow_ext)==false){
		write_file_send_log('FAIL',"FALSE:FILE_NOTALLOWED_EXTENSION");
	}
	*/

	$phpFileUploadErrors = array(
		0 => 'There is no error, the file uploaded with success',
		1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
		2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
		3 => 'The uploaded file was only partially uploaded',
		4 => 'No file was uploaded',
		6 => 'Missing a temporary folder',
		7 => 'Failed to write file to disk.',
		8 => 'A PHP extension stopped the file upload.'
	);
	
    if($file){
		$file_save_name = $v_wvcs_seq.".".$file_ext;
		//$file_save_name = $file_name;
		$save_folder = $_file_local_path."\\".date("Ymd")."SEQ".$v_wvcs_seq;
		$file_save_path = $save_folder."\\".$file_save_name;

		if(Is_Dir($save_folder)) {
			$makeDir = true;
		}else{
			$makeDir = mkdir($save_folder,777);
		}

		if($makeDir==false){
			write_file_send_log('FAIL',"FALSE:MKDIR");
		}
		
		/*
		if(move_uploaded_file($file,$file_save_path)){
			echo "TRUE:".$file_save_path;
			exit;
		}else{
			echo "FALSE:FILE_UPLOAD_FAIL";
			exit;
		}
		*/
		
		@move_uploaded_file($file,$file_save_path);

	}

	$result_code =$_files['error'];

	if($result_code==0){
		write_file_send_log('OK',"TRUE:".$file_save_path);
	}else{
		write_file_send_log('FAIL',"FALSE:".$phpFileUploadErrors[$result_code]);
	}

}else{
	write_file_send_log('FAIL',"FALSE:INVALIED_DATA");
}
exit;
?>