<?php
//session_start();

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common2.inc";

$emp_seq = $_POST['emp_seq'];
$new_pw = $_POST["new_pw"];
$redirect = $_POST["redirect"];

if(!isset($new_pw) || !isset($emp_seq)){
	printJson($msg=$_LANG_TEXT['wrongdatatranstext'][$lang_code]);
}

if($redirect == ""){ 
	$redirect= $_www_server."/index.php";
}else{
	$redirect= AES_Rijndael_Decript($redirect,$_AES_KEY,$_AES_IV);
}

/*이전비밀번호 비교*/
$qry_params = array("emp_seq"=>$emp_seq);
$qry_label = QRY_COMMON_EMP_INFO_PW;
$sql = query($qry_label,$qry_params);

$result = sqlsrv_query($wvcs_dbcon, $sql);
if($result){
	while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
		
		$emp_pwd = $row['emp_pwd'];
	}
}

//BCRYPT 비밀번호 비교
//if(password_verify($new_pw , $emp_pwd)){
//
//	printJson($msg=$_LANG_TEXT['samepwdchangetext'][$lang_code]);
//}

//BCRYPT 암호화
//$emp_pwd_hash = password_hash($new_pw, PASSWORD_BCRYPT,array("cost" => $passsword_bcrypt_cost));
////$emp_pwd_hash = "CAST('".$emp_pwd_hash."' AS VARBINARY(MAX))";


//SHA256 암호화
$emp_pwd_hash = base64_encode(hash($password_hash_algo, $new_pw, true));

if($emp_pwd_hash==$emp_pwd){

	printJson($msg=$_LANG_TEXT['samepwdchangetext'][$lang_code]);
}

$qry_params = array("emp_seq"=>$emp_seq,"emp_pwd_hash"=>$emp_pwd_hash,"pwd_change_emp_seq"=>$emp_seq);
$qry_label = QRY_USER_PW_CHANGE;
$sql = query($qry_label,$qry_params);

//printJson($sql);

$result = sqlsrv_query($wvcs_dbcon, $sql);

if($result) {
	
	include "./inc_cookie_clear.php";

	$status = true;
	$msg = $_LANG_TEXT['pwdchangeoknlogintext'][$lang_code];

}else{
	$status = false;
	$msg = $_LANG_TEXT['procfail'][$lang_code];
}

printJson($msg,$data=$redirect,$status,$result,$wvcs_dbcon);

?>