<?php
if(strpos(__DIR__,"\\wvcs_manager\\")!==false){	//카카오뱅크 매니저 접속 로그인 분리
	header("Location:login_kabang.php");
	exit;
}

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common2.inc";

/*
* Description : login_mode 
* 회사별로 사용자 등급별로 로그인 접속 페이지를 구분하여 사용하는 경우 lib/code_wvcs_회사코드.inc 에서 설정한다.
*/
$vcs_login_mode = strtoupper($_GET['vcs_login_mode']);
if($vcs_login_mode !=""){
	$login_url = $_CODE_LOGIN_MODE[$vcs_login_mode];
}
if($login_url=="") $login_url  = $_www_server."/login/login.php";

if(basename($login_url) != basename(__FILE__)){
	header("Location:".$login_url);
	exit;
}

include_once $_server_path . "/" . $_site_path . "/inc/header.inc";
$redirect = $_REQUEST['redirect'];

if(COMPANY_CODE=="600"){	//카카오뱅크 로그인
	$url = "login_kabang_process.php";
}else{
	$url = "login_process.php";
}
?>
<div id="login">
	<div class="logo">
		<img src="<?php echo $_www_server; ?>/images/<?=$_logo_img_login?>" alt="logo">
	</div>
	<div class="login_box">
		<form id="frmLogin" name="frmLogin" method="post" action="<? echo $url ?>">
			<input type='hidden' name='redirect' id='redirect' value='<?=$redirect?>'>
			<label>User Name</label>
			<div class="field">
				<img src="<?php echo $_www_server; ?>/images/id.png"><input type="text" name="login_id" id="login_id" class="frm_input" value="<?php if( gethostname() == "dataprotecs" ) { echo "wvcsadmin"; } ?>" maxlength="30">
			</div>
			<label style="padding-top:8px">Password</label>
			<div class="field">
				<img src="<?php echo $_www_server; ?>/images/pw.png">
				<input type="password" name="login_pw_fake" id="login_pw_fake" style="width:0px;"  tabindex="-1" >
				<input type="password" name="login_pw" id="login_pw" class="frm_input" value="<?php if( gethostname() == "dataprotecs" ) { echo "pcbank!3%909"; } ?>" maxlength="30" autocomplete="new-password" >
			</div>
			<div class="btn">
				<input type="submit" value="<?=$_LANG_TEXT['btnlogin'][$lang_code]?>" class="submit" onclick="return LoginSubmit();">
			</div>
		</form>
	</div>
	<!--
	<div style='text-align:center;padding:5px;border:1px solid #666666;'>
		<a href="">OneClick 다운로드</a> |
		<a href="">VCS 이용가이드</a>
	</div>
	-->
	<p><img src="<?php echo $_www_server; ?>/images/copyright.png"></p>
</div>
</body>
</html>