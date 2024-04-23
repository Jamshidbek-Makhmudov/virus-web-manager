<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include $_server_path . "/" . $_site_path ."/lib/wvcs_config.inc"; 
$_ck_user_lsq = isset($_COOKIE['user_lsq'])? AES_Rijndael_Decript($_COOKIE['user_lsq'],$_AES_KEY,$_AES_IV) : "";

/* 회사별로 사용자 등급별 로그인 페이지 설정 체크
* 사용자 등급별로 설정된 로그인 페이지가 있는지 체크한다. 
* /lib/code_wvcs_company.inc
*/
$login_url = $_CODE_LOGIN_MODE[$_ck_user_level];

if(basename($login_url) == ""){
	$go_url = $_www_server;
}else{
	$go_url = $login_url;
}

//로그아웃 시간 기록
$sql = "UPDATE tb_login_log Set logout_dt = getdate() where login_seq = '{$_ck_user_lsq}' ";
@sqlsrv_query($wvcs_dbcon, $sql);

if(strpos(__DIR__,"wvcs_manager")!==false){	//카카오뱅크 매니저 접속 로그인 분리
	include "./inc_cookie_clear_kabang.php";
}else{
	include "./inc_cookie_clear.php";
}

?>
<script language="javascript">
	top.location.href="<?php echo $go_url;?>";
</script>