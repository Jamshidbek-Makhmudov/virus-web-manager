<?
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$v_wvcs_seq = $_POST[v_wvcs_seq];
$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');

$Model_result = new Model_result();
$args = array("v_wvcs_seq"=>$v_wvcs_seq);
$result = $Model_result->getImportFileInfo($args);

if($result){
	while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){

		$file_send_status = $row['file_send_status'];
		$file_send_date = $row['file_send_date'];
		$file_delete_flag = $row['file_delete_flag'];
		$manager_name_en = $row['manager_name_en'];

		$file_ext = ".7z";

		$file_path = $_file_local_path."/".substr($file_send_date,0,8)."SEQ".$v_wvcs_seq."/".$v_wvcs_seq.$file_ext;

		if($file_delete_flag=="1"){
			printJson_ERROR('File deleted');
		}

		
		//전자문서번호를 다운로드 파일이름으로 한다.
		$file_down_name = $manager_name_en."_".$row['in_time']."_".$v_wvcs_seq.$file_ext;

		// check whether the file is exists:
		if(file_exists($file_path)==false){
			printJson_ERROR('File not exists');
		}

		$file_info = array("file_path"=>$file_path,"file_down_name"=>$file_down_name);

		if($file_send_status =="1"){
			printJson_OK('ok',$data=$file_info);
		}else{
			printJson_ERROR('File not exists');
		}

	}
}else{
	printJson_ERROR('File download failed');
}
?>