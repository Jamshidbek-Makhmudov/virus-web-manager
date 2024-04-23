<?
/*
* Description: 회사별로 설정이 다른 경우 여기에 설정..
*/

if(COMPANY_CODE=="600"){	//카카오뱅크

	//코드관리항목
	$_CODE_LIST = array("RENT_ITEM","IDC_CENTER");	

	//방문자구분상세
	$_CODE_V_USER_TYPE_DETAILS = array(
		"OUT"=>"방문객",
		"EMP"=> "임직원",
		"EMP_T"=> "임직원(임시)",
		"EMP_R"=> "임직원(상시)"
	);
	
	//검사옵션 - 바이러스 검사, 위변조 의심 검사
	$_CODE_INSPECT_OPTION = array("VIRUS","BAD_EXT");	
	
	//관리자등급 및 메뉴 권한 설정
	$_CODE["admin_level"] = array("SUPER"=>"최고관리자",
		"MAJOR"=>"기관관리자",
		"MANAGER"=>"매니저"
	);

	$_CODE["admin_menu_auth"] = array("SUPER"=>"U1000,R1000,S1000,M1000",
		"MAJOR"=>"U1000,R1000,S1000,M1000",
		"MANAGER"=>"U1000,R1000,S1000");
		
	//사용자 등급별 로그인 페이지 구분
	$_CODE_LOGIN_MODE = array("MANAGER"=>$_www_server."/login/login_kabang.php",
		"MANAGER_S"=>$_www_server."/login/login_kabang.php");	

	/*
	*사용자 등급별 메인 페이지 구분
	* access_deny : 메인 페이지 접근 권한 없음.
	*/
	$_CODE_MAIN_MODE = array("MANAGER"=>"access_deny",
		"MANAGER_S"=>"access_deny");	

	//대메뉴 페이지 링크 설정
	$_CODE_PAGE_LINK = array("MAIN"=>$_www_server."/main.php",
		"U1000"=>$_www_server."/user/access_control.php",
		"R1000"=>$_www_server."/result/result_list.php",
		"S1000"=>$_www_server."/stat/rental_info_change_history.php",
		"M1000"=>$_www_server."/manage/scan_center_list.php"
	);

	//앱업데이트 프로그램 구분
	$_CODE_UPDATE_APP_NAME = array(
		"V3" => "AhnLab V3 Internet Security 9.0"
		,"ESET" => "ESET Internet Security"
	);

	//검사장분류
	$_CODE_SCAN_CENTER_DIV = array(
		"IDC" => "IDC"
	);

}
?>