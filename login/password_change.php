<?php
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

$_ck_user_seq = isset($_COOKIE['user_seq'])? AES_Rijndael_Decript($_COOKIE['user_seq'],$_AES_KEY,$_AES_IV) : "";
$_ck_user_pwd_change = isset($_COOKIE['user_pwd_change'])? AES_Rijndael_Decript($_COOKIE['user_pwd_change'],$_AES_KEY,$_AES_IV) : "";


if($_ck_user_pwd_change=="N1"){	//최초 로그인시 비밀번호 변경

	$guide_msg = $_LANG_TEXT['passwordchangerequesttext1'][$lang_code];

}else if($_ck_user_pwd_change=="N2"){ //비밀번호 변경 주기 경과

	$guide_msg = $_LANG_TEXT['passwordchangerequesttext2'][$lang_code];

}else if($_ck_user_pwd_change=="N3"){ //관리자 비밀번호 초기화

	$guide_msg = $_LANG_TEXT['passwordchangerequesttext3'][$lang_code];
}

?>
<div id="login">
	<div class="logo">
		<img src="<?php echo $_www_server; ?>/images/<?=$_logo_img_login?>" alt="logo" >
	</div>
	<div class="login_box">
		<form id="frmPassword" name="frmPassword" method="post">
			<input type='hidden' name='emp_seq' id='emp_seq' value='<?=$_ck_user_seq?>'>
			<input type='hidden' name='redirect' id='redirect' value='<?=$redirect?>'>
			
			<div class="guide_message"><?=$guide_msg?></div>
			
			<label style="padding-top:15px"><?=$_LANG_TEXT['newpasswordtext'][$lang_code]?></label>
			<div class="field fd1">
				<img src="<?php echo $_www_server; ?>/images/pw.png"><input type="password" name="new_pw" id="new_pw" class="frm_input bg1"  maxlength="30">
			</div>
			
			<label style="padding-top:8px"><?=$_LANG_TEXT['newpasswordconfirmtext'][$lang_code]?></label>
			<div class="field fd2">
				<img src="<?php echo $_www_server; ?>/images/pw.png"><input type="password" name="new_pw_confirm" id="new_pw_confirm" class="frm_input bg2"  maxlength="30">
			</div>
			
			<div class="btn">
				<input type="submit" value="<?=$_LANG_TEXT['btnchangepassword'][$lang_code]?>" class="submit" onclick="return PasswordSubmit();">
			</div>
		</form>
	</div>
	<p><img src="<?php echo $_www_server; ?>/images/copyright.png"></p>
</div>
</body>
</html>