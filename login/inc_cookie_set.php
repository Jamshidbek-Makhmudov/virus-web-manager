<?php
	session_start();
	session_regenerate_id();	//�� ���� ����

	//��Ű ��ȣȭ
	$_AES_IV_COOKIE = $_login_log_seq ? $_login_log_seq.substr($_AES_IV,strlen($_login_log_seq)) : $_AES_IV;
	
	//��Ű����ð�
	$time = 0; // time()+60*60*24; //�Ϸ�

	//if($_admin_level=="SUPER") $_org_id = "";

	$_org_id = "";	//**������ð��� ���� user_org ��Ű����(authcheck.inc���� ��Ű �����)

	$_SESSION['user_seq'] = $_emp_seq;

	$path = "/{$_site_path}; samesite=strict";	//php ver 7.3 ���� ���� ���
	$secure = ($_HTTP_HTTPS=="https");
	$httponly = true;

	setcookie("user_seq", AES_Rijndael_Encript($_emp_seq,$_AES_KEY,$_AES_IV_COOKIE),$time,$path,"",$secure,$httponly);
	setcookie("user_id", AES_Rijndael_Encript($_emp_no,$_AES_KEY,$_AES_IV_COOKIE),$time,$path,"",$secure,$httponly);
	setcookie("user_name", AES_Rijndael_Encript($_emp_name,$_AES_KEY,$_AES_IV_COOKIE),$time,$path,"",$secure,$httponly);
	setcookie("user_org", AES_Rijndael_Encript($_org_id,$_AES_KEY,$_AES_IV_COOKIE),$time,$path,"",$secure,$httponly);
	setcookie("user_dept", AES_Rijndael_Encript($_dept_seq,$_AES_KEY,$_AES_IV_COOKIE),$time,$path,"",$secure,$httponly);
	setcookie("user_level", AES_Rijndael_Encript($_admin_level,$_AES_KEY,$_AES_IV_COOKIE),$time,$path,"",$secure,$httponly);
	setcookie("user_mauth", AES_Rijndael_Encript($_m_auth,$_AES_KEY,$_AES_IV_COOKIE),$time,$path,"",$secure,$httponly);
	setcookie("user_mng_org_auth", AES_Rijndael_Encript($_mng_org_auth,$_AES_KEY,$_AES_IV_COOKIE),$time,$path,"",$secure,$httponly);
	setcookie("user_mng_scan_center_auth", AES_Rijndael_Encript($_mng_scan_center_auth,$_AES_KEY,$_AES_IV_COOKIE),$time,$path,"",$secure,$httponly);
	setcookie("user_pwd_change", AES_Rijndael_Encript($_emp_pwd_change,$_AES_KEY,$_AES_IV_COOKIE),$time,$path,"",$secure,$httponly);
	setcookie("user_lsq", AES_Rijndael_Encript($_login_log_seq,$_AES_KEY,$_AES_IV),$time,$path,"",$secure,$httponly);
	setcookie("user_lang", $_use_lang,$time,$path,"",$secure,$httponly);

?>