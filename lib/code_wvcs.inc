<?
if($lang_code=="KR"){

	$_CODE = array (
		
		"month"=>array("01"=>"01월","02"=>"02월","03"=>"03월","04"=>"04월","05"=>"05월","06"=>"06월","07"=>"07월","08"=>"08월","09"=>"09월","10"=>"10월","11"=>"11월","12"=>"12월"),
		"menu"=>array("U1000"=>"방문객","R1000"=>"점검결과","S1000"=>"조회/통계","M1000"=>"운영관리"),
		"asset_type"=>array("NOTEBOOK"=>"노트북","RemovableDevice"=>"저장매체"),
		"storage_device_type"=>array("HDD"=>"HDD","Removable"=>"이동식디스크","CD/DVD"=>"CD/DVD","DEVICE_ETC"=>"기타"),
		"app_gubun"=>array("ALL"=>"전체","32bit"=>"32bit","64bit"=>"64bit"),
		"app_file_type"=>array("ZIP"=>"ZIP","EXE"=>"EXE","IMAGE"=>"IMAGE","ETC"=>"ETC"),
		"vaccine_scan_type"=>array("QUICK"=>"빠른검사","TOTAL"=>"전체검사"),
		"web_type"=>array("HTTP"=>"HTTP","HTTPS"=>"HTTPS"),
		"ftp_type"=>array("FTP"=>"FTP","SFTP"=>"SFTP"),
		"mail_type"=>array("POP3"=>"POP3","SMTP"=>"SMTP"),
		"sms_type"=>array("DB"=>"DB","WEB"=>"WEB"),
		"vcs_type"=>array("DOWNLOAD"=>"DOWNLOAD","DPTKIOSK"=>"DPTKIOSK","KIOSK"=>"KIOSK"),
		"vcs_status"=>array("CHECK"=>"점검완료","IN"=>"반입완료","OUT"=>"반출완료")
	);

}else if($lang_code=="EN"){

	$_CODE = array (

		"month"=>array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May","06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December"),
		"menu"=>array("U1000"=>"Visitor","R1000"=>"Results","S1000"=>"Query / Statistics ","M1000"=>"Operation / Management"),
		
		"asset_type"=>array("NOTEBOOK"=>"노트북","RemovableDevice"=>"저장매체"),
		"storage_device_type"=>array("HDD"=>"HDD","Removable"=>"이동식디스크","CD/DVD"=>"CD/DVD","DEVICE_ETC"=>"기타"),
		"app_gubun"=>array("ALL"=>"전체","32bit"=>"32bit","64bit"=>"64bit"),
		"app_file_type"=>array("EXE"=>"EXE","IMAGE"=>"IMAGE","ETC"=>"ETC"),
		"vaccine_scan_type"=>array("QUICK"=>"빠른검사","TOTAL"=>"전체검사"),
		"web_type"=>array("HTTP"=>"HTTP","HTTPS"=>"HTTPS"),
		"ftp_type"=>array("FTP"=>"FTP","SFTP"=>"SFTP"),
		"mail_type"=>array("POP3"=>"POP3","SMTP"=>"SMTP"),
		"sms_type"=>array("DB"=>"DB","WEB"=>"WEB"),
		"vcs_type"=>array("DOWNLOAD"=>"DOWNLOAD","DPTKIOSK"=>"DPTKIOSK","KIOSK"=>"KIOSK"),
		"vcs_status"=>array("CHECK"=>"점검완료","IN"=>"반입완료","OUT"=>"반출완료")
	);
		

}else if($lang_code=="CN"){

	$_CODE = array (

		"month"=>array("01"=>"1月","02"=>"2月","03"=>"3月","04"=>"4月","05"=>"5月","06"=>"6月","07"=>"7月","08"=>"8月","09"=>"9月","10"=>"10月","11"=>"11月","12"=>"12月"),
		"menu"=>array("U1000"=>"방문객","R1000"=>"점검결과","S1000"=>"统计/分析","M1000"=>"运营/管理"),

		"asset_type"=>array("NOTEBOOK"=>"노트북","RemovableDevice"=>"저장매체"),
		"storage_device_type"=>array("HDD"=>"HDD","Removable"=>"이동식디스크","CD/DVD"=>"CD/DVD","DEVICE_ETC"=>"기타"),
		"app_gubun"=>array("ALL"=>"전체","32bit"=>"32bit","64bit"=>"64bit"),
		"app_file_type"=>array("EXE"=>"EXE","IMAGE"=>"IMAGE","ETC"=>"ETC"),
		"vaccine_scan_type"=>array("QUICK"=>"빠른검사","TOTAL"=>"전체검사"),
		"web_type"=>array("HTTP"=>"HTTP","HTTPS"=>"HTTPS"),
		"ftp_type"=>array("FTP"=>"FTP","SFTP"=>"SFTP"),
		"mail_type"=>array("POP3"=>"POP3","SMTP"=>"SMTP"),
		"sms_type"=>array("DB"=>"DB","WEB"=>"WEB"),
		"vcs_type"=>array("DOWNLOAD"=>"DOWNLOAD","DPTKIOSK"=>"DPTKIOSK","KIOSK"=>"KIOSK"),
		"vcs_status"=>array("CHECK"=>"점검완료","IN"=>"반입완료","OUT"=>"반출완료")

	);

}

/*
* excel file download page size:
*/
define("RECORD_LIMIT_PER_FILE", 1000);

//관리자등급 및 권한 설정
$_CODE["admin_level"] = array("SUPER"=>"최고관리자"
	,"MAJOR"=>"기관관리자"
	,"SECURITOR"=>"보안관리자"
	,"MAJOR_S"=>"기관관리자(조회용)"
	,"SECURITOR_S"=>"보안관리자(조회용)");

$_CODE["admin_menu_auth"] = array("SUPER"=>"U1000,R1000,S1000,M1000"
	,"MAJOR"=>"U1000,R1000,S1000"
	,"MAJOR_S"=>"U1000,R1000,S1000"
	,"SECURITOR"=>"R1000"
	,"SECURITOR_S"=>"R1000");

//대메뉴 페이지 링크 설정
$_CODE_PAGE_LINK = array("MAIN"=>$_www_server."/main.php",
	"U1000"=>$_www_server."/user/user_list.php",
	"R1000"=>$_www_server."/result/result_list.php",
	"S1000"=>$_www_server."/stat/vcs_stat_period.php",
	"M1000"=>$_www_server."/manage/admin_list.php",
);

//앱업데이트 프로그램 구분
$_CODE_UPDATE_APP_NAME = array(
	"V3" => "AhnLab V3 Internet Security 9.0"
	,"VCS"=> "VCS Client program"
);

//동의서 언어 선택
$_CODE_AGREEMENT_LANG = array(
	"KR" => "Korea",
	"EN" => "English"
);

//파일예외반입 예외대상
$_CODE_FILE_EXCEPTION_TARGET = array(
	"ALL" => $_LANG_TEXT['alltext'][$lang_code],
	"DEPT" => $_LANG_TEXT['depttext'][$lang_code],
	"EMP" => $_LANG_TEXT['usertext'][$lang_code]
);

//파일예외반입승인상태
$_CODE_FILE_EXCEPTION_APPRV_STATUS = array(
	"WAIT"=>$_LANG_TEXT['approvewaittext'][$lang_code],
	"APPRV"=>$_LANG_TEXT['approvedtext'][$lang_code],
	"REJECT"=>$_LANG_TEXT['rejecttext'][$lang_code],
	"CANCEL"=>$_LANG_TEXT['canceltext'][$lang_code]
);

/*
* 파일검사결과 코드값
*/
$_CODE_FILE_SCAN_RESULT = array(
	"BAD_EXT"=>"위변조의심",
	"VIRUS"=> "악성코드"
);

/*방문자 구분*/
$_CODE_V_USER_TYPE = array(
	"OUT"=>"방문객",
	"EMP"=> "임직원"
);


//코드관리항목
$_CODE_LIST = array("NOTICE_CATEGORY","FAQ_CATEGORY");


//검사옵션
$_CODE_INSPECT_OPTION = array("VIRUS","WEAK");			//바이러스,윈도우즈 취약점


//검사로그목록
$_CODE_SCAN_EVENT_LOG_LIST = array(
	"VIRUS_SCAN"=> $_LANG_TEXT['virusscantext'][$lang_code]
	,"BAD_SCAN" => $_LANG_TEXT['badfilescantext'][$lang_code]  
	,"FILE_COPY" => $_LANG_TEXT['filecopytext'][$lang_code]  
//	,"FILE_SEND" => $_LANG_TEXT['fileserversend'][$lang_code]  
);

//작업로그
$_CODE_SYSTEM_LOG_LIST = array(
	"emp_sync_batch" => $_LANG_TEXT['hdbsyncwork'][$lang_code]
	,"user_data_delete_batch"=> $_LANG_TEXT['userdatadeletework'][$lang_code]
	,"visitor_checkout_batch"=> $_LANG_TEXT['visitorcheckoutprocess'][$lang_code]
);

//입/퇴실옵션
$_CODE_VISIT_STATUS = array(
	"9"=>$_LANG_TEXT['inofficewaittext'][$lang_code]		//입실대기
	,"1"=>$_LANG_TEXT['inofficeoktext'][$lang_code]		//입실완료
	,"0"=>$_LANG_TEXT['outofficeoktext'][$lang_code]	//퇴실완료
);

//접속아이피제한시 예외 아이피 설정
$_CODE_ALLWAYS_ALLOW_IP = array(
	"0.0.0.0"
);

/*
----------------------------------
tb_v_wvcs_ldisk.drive_type 구분값
----------------------------------
'UNKNOWN'
'DRIVE_NO_ROOT_DIR'
'HDD'
'REMOTE'	--이동식 디스크 ex)USB...
'CD/DVD'
'RAMDISK'
'Removable'
*/

/*
----------------------------------
tb_v_wvcs_pdisk.media_type 구분값
----------------------------------
'HDD'
'Removable' --이동식 디스크 ex)USB...
*/

/*
------------------------------------------
tb_v_wvcs_info_detail.os_ver_name  구분값
-----------------------------------------
'HDD'
'Removable' 
'CD/DVD'
'그외 - pdisk.media_type'
*/


/*회사별 코드 설정*/
include "code_wvcs_600.inc";	//카뱅
?>