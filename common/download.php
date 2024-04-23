<?php
$page_name = "app_update";
$page_required_auth = "P";	//필요권한(다운로드/인쇄)

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";


$_file = $_REQUEST['file'];
$_filename = $_REQUEST['filename'];

//테스트
//$_file = "D:\\DPTServer\\VCSDATA\\20231226SEQ4496\\4496.7z";
//echo $_file."<BR>";
//echo  filesize($_file);
//exit;


if($_filename==""){
	$_filename =basename($_file);
}

if ( file_exists($_file) ) {
    
	/*
	$filesize   = filesize($_file);

	# 공통으로 사용되는 헤더
	header('Content-Type: application/x-octetstream');
	//header("Content-type: application/pdf; charset=utf-8");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: $filesize");
	header("Cache-Control: cache,must-revalidate");
	header("Pragma: no-cache");
	header("Expires: 0");
	header("Content-Disposition: attachment; filename=\"$_filename\"");

	ob_clean();
	flush();
	readfile($_file);
	*/
	
	
	//파일다운로드 프로그래스바를 표시할려면 파일 사이즈 정보를 표시해야 한다.
	/* 32bit php에서 파일 사이즈 정보는 2G까지만 얻을수 있다..
	  Because PHP's integer type is signed and many platforms use 32bit integers
	 , some filesystem functions may return unexpected results for files which are larger than 2GB.*/
	//$filesize   = filesize($_file);
	//$filesize = 5239352400;
	

	header("Content-Type: application/octet-stream; name=" . $_file);
	header("Content-Disposition: attachment; filename=" . rawurlencode($_filename));
	header("Content-Transfer-Encoding: binary");
	//header("Content-Length: $filesize");
	header("Pragma: no-cache");
    header("Expires: 0");

	$bufsize = 2000000;
	
	if($_HTTP_HTTPS=="https"){
		
		$opts = array(
			"ssl"=>array(
				"verify_peer"=>false,
				"verify_peer_name"=>false
			)
		);

		$fh = @fopen($_file  , "rb",false, stream_context_create($opts));

	}else{
		
		$fh = @fopen($_file  , "rb");
	}

	if ($fh){
		while($buf = fread($fh, $bufsize)) {
			print $buf;
		}
		fclose($fh);
	}
	

}
else {
   echo "File not exists";
    exit;
}

	




?>


