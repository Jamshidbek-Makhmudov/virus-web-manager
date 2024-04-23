<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$emp_seq = $_POST["emp_seq"];
$emp_name = $_POST["emp_name"];
$emp_no = $_POST["emp_no"];
$emp_pwd = $_POST["emp_pwd"];
$phone_no = $_POST["phone_no"];
$email = $_POST["email"];

if($emp_seq == "") {
	printJson($_LANG_TEXT['wrongdatatranstext'][$lang_code]);
}


if($emp_pwd){
	
	//BCRYPT 암호화
	//$emp_pwd_hash = password_hash($emp_pwd, PASSWORD_BCRYPT,array("cost" => $passsword_bcrypt_cost));
	////$emp_pwd_hash = "CAST('".$emp_pwd_hash."' AS VARBINARY(MAX))";

	//SHA256 암호화
	$emp_pwd_hash = base64_encode(hash($password_hash_algo, $emp_pwd, true));
}

if($_encryption_kind=="1"){

	$phone_no_encrypt = "dbo.fn_EncryptString('".$phone_no."')";
	$email_encrypt = "dbo.fn_EncryptString('".$email."')";

}else if($_encryption_kind=="2"){
		
	$phone_no_encrypt = aes_256_enc($phone_no);
	$email_encrypt = aes_256_enc($email);

//	$phone_no_encrypt = "CAST('".$phone_no_encrypt."' AS VARBINARY(MAX))";
//	$email_encrypt = "CAST('".$email_encrypt."' AS VARBINARY(MAX))";
}


$c_date = date("Y-m-d H:i:s");

$login_emp_seq = $_ck_user_seq;

//비밀번호 유효성체크
if($emp_pwd != ""){
	
	list($result,$msg) = validCheck_Password($emp_pwd);
	
	if(!$result){
		//비밀번호는 영문대문자,영문소문자, 숫자, 특수문자 중 세가지를 포함해 8~16자 이내로 입력하세요
		printJson($msg=$_LANG_TEXT['passwordrules'][$lang_code]);
	}

	/*이전비밀번호 비교*/
	$qry_params = array("emp_seq"=> $login_emp_seq);
	$qry_label = QRY_COMMON_EMP_INFO_PW;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

	if($result) {
		while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
			
			$_emp_pwd = $row['emp_pwd'];
		}
	}

	if($emp_pwd_hash==$_emp_pwd){

		printJson($msg=$_LANG_TEXT['samepwdchangetext'][$lang_code]);
	}

}

//전화번호 유효성 체크
if($phone_no != ""){
	
	list($result,$msg) = validCheck_Phone($phone_no);
	
	if(!$result){
		
		printJson($msg=$_LANG_TEXT['notvalidphonetext'][$lang_code]);
	}	
}

//이메일 유효성 체크
if($email != ""){
	
	list($result,$msg) = validCheck_Email($email);
	
	if(!$result){
		
		printJson($msg=$_LANG_TEXT['notvalidemailtext'][$lang_code]);
	}	
}



$qry_params = array("emp_seq"=> $login_emp_seq,"email_encrypt"=>$email_encrypt,"phone_no_encrypt"=>$phone_no_encrypt,"emp_pwd_hash"=>$emp_pwd_hash);
$qry_label = QRY_MY_INFO_UPDATE;
$sql = query($qry_label,$qry_params);

$result = sqlsrv_query($wvcs_dbcon, $sql);

if($result) {
	$status = true;
	$msg = $_LANG_TEXT['procsuccess'][$lang_code];
}else{
	$status = false;
	$msg = $_LANG_TEXT['procfail'][$lang_code];
}

printJson($msg,$data,$status,$result,$wvcs_dbcon);
?>