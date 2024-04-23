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

$login_id = $_POST['login_id'];
$redirect = $_POST['redirect'];

if($login_id==""){

	error($_LANG_TEXT['wrongdatatranstext'][$lang_code],$_www_server);
	exit;
}

//새로고침 방지
$qry_params = array("login_id"=>$login_id);
$qry_label = QRY_OTP_LOG_INFO;
$sql = query($qry_label,$qry_params);

$result = sqlsrv_query($wvcs_dbcon, $sql);
$row = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

$_admin_otp_log_seq = $row['admin_otp_log_seq'];
$_check_count = $row['check_count'];

if($_check_count > 0){

	echo "<script type='text/javascript'>location.href='".$_www_server."';</script>";
	exit;

}else{

	$qry_params = array("admin_otp_log_seq"=>$_admin_otp_log_seq);
	$qry_label = QRY_OTP_LOG_UPDATE;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

}
?>
<script type='text/javascript'>

	var timer;
	$("document").ready(function(){
		OtpTimeOutCounter();
	});

	function LoginOtpSend(){

		var login_id = $("#login_id").val();

		$.post(
			SITE_NAME+'/login/login_otp_send.php',
			{"login_id" : login_id},
			function(data) {
				alert(data.msg);
			},
			'json'
		);

		OtpTimeOutCounter();
		return false;
	}

</script>
<div id="login">
	<div class="logo">
		<img src="<?php echo $_www_server; ?>/images/<?=$_logo_img_login?>" alt="logo" >
	</div>
	<div class="login_box">
		<form id="frmLoginOtp" name="frmLoginOtp" method="post">
			<input type='hidden' name='clock' id='clock' value='120'>
			<input type='hidden' name='login_id' id='login_id' value='<?=$login_id?>'>
			<input type='hidden' name='redirect' id='redirect' value='<?=$redirect?>'>
			<div class='otp_msg'><?=$_LANG_TEXT['sendotpcodetext'][$lang_code]?><BR><?=$_LANG_TEXT['inputotpcodetext'][$lang_code]?></div>
			
			<label style="padding-top:8px;margin-top:20px;"><?=$_LANG_TEXT['remainingtimetext'][$lang_code]?> : <span id='time'>2<?=$_LANG_TEXT['mintext'][$lang_code]?> 0<?=$_LANG_TEXT['secondtext'][$lang_code]?></span></label>
			<div class="field2">
				<img src="<?php echo $_www_server; ?>/images/pw.png"><input type="text" name="otp_code" id="otp_code" class='frm_input2'  maxlength="20"><button class='resend' onclick="return LoginOtpSend();"><?=$_LANG_TEXT['btnresend'][$lang_code]?></button>
			</div>
			
			<div class="btn2">
				<input type="submit" value="<?=$_LANG_TEXT['btnconfirm'][$lang_code]?>" class="submit" onclick="return LoginOtpSubmit();">
				<input type="button" value="<?=$_LANG_TEXT['btncancel'][$lang_code]?>" class="submit" onclick="javascript:top.location.href='<?=$_www_server?>'">
			</div>
		</form>
	</div>
	<p><img src="<?php echo $_www_server; ?>/images/copyright.png"></p>
</div>
</body>
</html>