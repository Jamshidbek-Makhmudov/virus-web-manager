<?php
if(strpos(__DIR__,"\\wvcs\\")!==false){	//카카오뱅크 매니저 접속 로그인 분리
	header("Location:login.php");
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
include_once $_server_path . "/" . $_site_path . "/inc/header.inc";

$redirect = $_REQUEST['redirect'];
?>

<div id="login">
	<div class="logo">
		<img src="<?php echo $_www_server; ?>/images/<?=$_logo_img_login?>" alt="logo">
	</div>
	<div class="login_box" style='background-color:#fee300'>
		<form id="frmLogin" name="frmLogin" method="post">
			<input type='hidden' name='redirect' id='redirect' value='<?=$redirect?>'>
			<label>User Name</label>
			<div class="field"  style='background-color:#fff'>
				<img src="<?php echo $_www_server; ?>/images/id.png"><input type="text" name="login_id" id="login_id" class="frm_input" maxlength="30" placeholder="아이디를 입력하세요" >
			</div>
			<label style="padding-top:8px">Password</label>
			<div class="field"  style='background-color:#fff'>
				<img src="<?php echo $_www_server; ?>/images/pw.png">
				<input type="password" name="login_pw_fake" id="login_pw_fake" style="width:0px;"  tabindex="-1" >
				<input type="password" name="login_pw" id="login_pw" class="frm_input"  maxlength="30" autocomplete="new-password"  placeholder="비밀번호를 입력하세요">
			</div>
			<div class="btn">
				<input type="submit" value="<?=$_LANG_TEXT['btnlogin'][$lang_code]?>" class="submit" onclick="return LoginKabangSubmit();" style='background-color:#f8d90c;color:#1d1d1d;cursor:pointer;'>
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