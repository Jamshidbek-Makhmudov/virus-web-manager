<?php
header('Content-Type: text/html; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
   

//*** 언어 설정 ***//
include "lang_wvcs.inc";
include "lang_wvcs2.inc";
include "lang_wvcs3.inc";
include "crypto.inc";

$_AES_KEY = get_cfg_var('AES_KEY');					//24byte
$_AES_KEY_256 = get_cfg_var('AES_KEY_256');		//32byte
$_AES_IV = get_cfg_var('AES_IV');						//16byte

$lang_code = $_REQUEST['lang_code'];

if($lang_code == "") $lang_code = $_COOKIE['user_lang'];
if($lang_code == "") $lang_code = "KR";
if($lang_code == "CN"){
	$lang_img = "cn_";
} else if($lang_code == "EN"){
	$lang_img = "en_";
} else if($lang_code =="JP"){
	$lang_img = "jp_";
}
//*** 언어 설정 ***//


/*** 
8 : 한국중부발전
12: 삼성디스플레이 베트남,
14: 경북도청,
19: 포스코,
50: SK 실트론 한국,
81: 삼성전자-국내,
82: 삼성전자-시안, 
71: 하이닉스-본사,
72: 하이닉스-우시,
73: 하이닉스-충칭
91: 삼성전자DX
600 : 카카오뱅크
***/
DEFINE ("COMPANY_CODE",600);
DEFINE ("COMPANY_NAME","kakaobank");

//*** 로고 설정 ***//
$_logo_img = "logo.png";
$_logo_img_login = "logo_login.png";
$_logo_img_report = "logo_report.png";


$_main_title = "WVCS";

$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
if($_site_path == "") {
	$_apos = stripos($_REQUEST_URI,  "/");
	if($_apos > 0) {
		$_REQUEST_URI =substr($_REQUEST_URI, 0, $_apos);
	}
	$_site_path = $_REQUEST_URI;
}

#DB connect
include "wvcs_conn.inc";


/*BCRYPT 비밀번호 암호화 옵션*/
$passsword_bcrypt_cost = 12;

/*비밀번호 암호화 HASH 알고리즘*/
$password_hash_algo = "sha256";

/*초기비밀번호*/
$_initialpassword ="wvcs@1004";


/*페이징*/
$_paging = 20;
$_list = 10;

/*정책*/
$sql = "SELECT TOP 1 * FROM tb_policy ORDER BY policy_seq DESC ";

$result = @sqlsrv_query($wvcs_dbcon, $sql);

if($result){
	$row=@sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

	$_HTTP_HTTPS = strtolower($row['web_type']);
	$_PORT = ":".$row['web_port'];
	
	//저장매체 반입형태
	$_P_CHECK_IN_KIOSK_IN_TYPE = $row['checkin_kiosk_in_type'];
	if($_P_CHECK_IN_KIOSK_IN_TYPE=="") $_P_CHECK_IN_KIOSK_IN_TYPE = "DEVICE";

	$_P_CHECK_FILE_SEND_TYPE = $row['checkin_file_send_type'];
	if($_P_CHECK_FILE_SEND_TYPE=="") $_P_CHECK_FILE_SEND_TYPE = "N";
	
	/*암호화방식(encryption_kind)
	* 1 - db SYMMETRIC KEY encrypt - aes 256
	* 2 - AES Rijndael 256  (32byte key)
	* 3 - AES Rijndael 192  (24byte key) - 기존방식
	*/
	$_encryption_kind = $row[db_encription_kind];
	
	/*
	*암호화값 (db_encription_fla)
	* 1 - 전화번호,이메일 암호화
	* 2 - 이름,전화번호,이메일 암호화
	*/
	$_db_encription_flag = $row[db_encription_flag];
	
}

//**default value;
if($_encryption_kind=="") $_encryption_kind = "3";	
if($_encription_flag=="") $_encription_flag = "1";



/*
***  디비 개인정보 암호화 방식
1 : Symmetric Key를 이용한 암호화 (현재사용안함...api도 2번 방식으로 암호화되어 있어 변경할려면 api 수정필요..)
2 : 암호화 함수 사용(AES_Rijndael_Encript) 
*/

if($_encryption_kind =="1"){		//DB  SYMMETRIC KEY  -AES256

	$_sql = "OPEN SYMMETRIC KEY User_data_Key  
				DECRYPTION BY CERTIFICATE User_Certificate
				WITH PASSWORD = '".$WVCS_DB_SYMMETRIC_KEY_ENC."'	
				 ";

	@sqlsrv_query($wvcs_dbcon,$_sql);
}

/*
DPT 디비 암호화 방식
*/
$_dpt_encryption_kind = "1";	 //AES256

if($_dpt_encryption_kind == "1") {

	$_sql = "OPEN SYMMETRIC KEY User_data_Key  
				DECRYPTION BY CERTIFICATE User_Certificate
				WITH PASSWORD = '".$DB_SYMMETRIC_KEY_ENC."'	
				 ";

	@sqlsrv_query($dpt_dbcon, $_sql);
}


if($_HTTP_HTTPS==""){

	//=== https / http 에 따라서 링크(inclue)된 스크립트의 경로도 맞춰 줘야 한다. ===//
	if (isset($_SERVER['HTTPS']) &&
		($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
		isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
		$_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
	  $_HTTP_HTTPS = 'https';
	}
	else {
	  $_HTTP_HTTPS = 'http';
	}
}

if($_PORT==""){
	if($_HTTP_HTTPS=="https"){
		$_PORT = ":1443";
	}else $_PORT = "";
}


$_baseurl = $_HTTP_HTTPS."://". $_SERVER['SERVER_NAME'].$_PORT;
$_baseurl80 = "http://". $_SERVER['SERVER_NAME'];

$_www_server_https = "https://". $_SERVER['SERVER_NAME'].":1443/" . $_site_path . "";
$_www_server = $_baseurl . "/" . $_site_path . "";
$_img_server = $_baseurl . "/" . $_site_path . "/images";
$_img_server_80 = $_baseurl80 . "/" . $_site_path . "/images";

$_data_folder = "/data/diskdata";
$_data_url = $_baseurl . $_data_folder ;
$_data_path = $_SERVER['DOCUMENT_ROOT'] . $_data_folder;



$_js_server = $_baseurl . "/" . $_site_path . "/js";
$_js_server80 = $_baseurl80 . "/" . $_site_path . "/js";
$_css_server = $_baseurl . "/" . $_site_path . "/css";

//개발테스트
if (gethostname() =="dataprotecs") {
	$baseurl = $_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'];

	$_www_server    = "//{$baseurl}/{$_site_path}";
	$_img_server    = "//{$baseurl}/{$_site_path}/images";
	$_img_server_80 = "//{$baseurl}/{$_site_path}/images";

	$_data_url    = "//{$baseurl}{$_data_folder}";

	$_js_server   = "//{$baseurl}/{$_site_path}/js";
	$_js_server80 = "//{$baseurl}/{$_site_path}/js";
	$_css_server  = "//{$baseurl}/{$_site_path}/css";
}

if($_site_path == "") {
	$_login_server = "./";
} else {
	$_login_server = "/" . $_site_path . "/";
}



/*
* VCS 점검 반입파일 저장경로 지정
*/
if (strpos($_SERVER['windir'], "Windows") || strpos($_SERVER['WINDIR'], "Windows")) {
	$_OS_KIND = "WINDOWS";
} else {
	$_OS_KIND = "LINUX";
}
if($_OS_KIND == "LINUX") {
	$_driver_path = "/DPT";
	$_file_local_path = $_driver_path . "/DPTServer/VCSDATA";
	$_api_local_path = $_driver_path . "/DPTWebManager/htdocs/".$_site_path."/api";
	$_log_local_path = "/DPT/DPTWebManager/htdocs/logs/vcs";
} else {
	$_driver_path = "D:";
	$_file_local_path = $_driver_path . "\DPTServer\VCSDATA";
	$_api_local_path = $_driver_path . "\DPTWebManager\htdocs\\".$_site_path."\api";
	$_log_local_path = "D:\DPTWebManager\htdocs\logs/vcs";
}

//*** 코드 설정 ***//
include "code_wvcs.inc";
?>