<?
if(session_id() == '') {
	session_start();
}

//session_start();

//쿠키 복호화
$_ck_user_lsq = isset($_COOKIE['user_lsq'])? AES_Rijndael_Decript($_COOKIE['user_lsq'],$_AES_KEY,$_AES_IV) : "";
$_AES_IV_COOKIE = $_ck_user_lsq? $_ck_user_lsq.substr($_AES_IV,strlen($_ck_user_lsq)) : $_AES_IV;

//**COOKIE 변수
$_ck_user_id = isset($_COOKIE['user_id'])? AES_Rijndael_Decript($_COOKIE['user_id'],$_AES_KEY,$_AES_IV_COOKIE) : "";
$_ck_user_seq = isset($_COOKIE['user_seq'])? AES_Rijndael_Decript($_COOKIE['user_seq'],$_AES_KEY,$_AES_IV_COOKIE) : "";
$_ck_user_name = isset($_COOKIE['user_name'])? AES_Rijndael_Decript($_COOKIE['user_name'],$_AES_KEY,$_AES_IV_COOKIE) : "";
$_ck_user_level = isset($_COOKIE['user_level'])? AES_Rijndael_Decript($_COOKIE['user_level'],$_AES_KEY,$_AES_IV_COOKIE) : "";
$_ck_user_mauth = (isset($_COOKIE['user_mauth'])? explode(",", AES_Rijndael_Decript($_COOKIE['user_mauth'],$_AES_KEY,$_AES_IV_COOKIE)) : "");
$_ck_user_mng_org_auth = (isset($_COOKIE['user_mng_org_auth'])? explode(",",AES_Rijndael_Decript($_COOKIE['user_mng_org_auth'],$_AES_KEY,$_AES_IV_COOKIE)) : "");
$_ck_user_mng_scan_center_auth = (isset($_COOKIE['user_mng_scan_center_auth'])? explode(",",AES_Rijndael_Decript($_COOKIE['user_mng_scan_center_auth'],$_AES_KEY,$_AES_IV_COOKIE)) : "");
$_ck_user_pwd_change = isset($_COOKIE['user_pwd_change'])? AES_Rijndael_Decript($_COOKIE['user_pwd_change'],$_AES_KEY,$_AES_IV_COOKIE) : "";

$gate = $_GET['gate'];


//**최고관리자(SUPER) - Select Gate 선택값에 따라 $_COOKIE['user_org'] 값 변경.
if($_ck_user_level=="SUPER" || $_ck_user_mng_org_auth != ""){

	if($gate != "") {

		if($gate=="all") $gate = "";

		$path = "/; samesite=strict";	//php ver 7.3 이하 설정 방식
		$secure = ($_HTTP_HTTPS=="https");
		$httponly = true;
		
		setcookie("user_org", AES_Rijndael_Encript($gate,$_AES_KEY,$_AES_IV_COOKIE),$time,$path,"",$secure,$httponly);
		$_COOKIE['user_org'] = AES_Rijndael_Encript($gate,$_AES_KEY,$_AES_IV_COOKIE);
		
	}

}

$_ck_user_org = isset($_COOKIE['user_org'])? AES_Rijndael_Decript($_COOKIE['user_org'],$_AES_KEY,$_AES_IV_COOKIE) : "";

if($_SERVER['REQUEST_URI']==$_www_server."/" || $_SERVER['REQUEST_URI']==$_www_server."/index.php") {
	
	return;

}else{

	$redirect = urlencode(AES_Rijndael_Encript($_SERVER['REQUEST_URI'],$_AES_KEY,$_AES_IV));
	
	if($_ck_user_seq=="") {

		echo "<meta http-equiv='refresh' content='0;url=".$_www_server."/login/login.php?redirect=".$redirect."'>";
		exit;
	
	}else if($_SESSION['user_seq'] != $_ck_user_seq){	//세션 만료

		echo "<meta http-equiv='refresh' content='0;url=".$_www_server."/login/logout.php'>";
		exit;

	}else{

		//세션만료테스트
		//$_SESSION['user_seq'] = '';
	}

	//**비밀번호 변경 체크
	if($_ck_user_pwd_change !="Y" ){

		echo "<meta http-equiv='refresh' content='0;url=".$_www_server."/login/password_change.php?redirect=".$redirect."'>";
		exit;
	}
}

if($_ck_user_org){

	$qry_params = array("org_id"=> $_ck_user_org);
	$qry_label = QRY_COMMON_ORG_INFO;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

	while($row=@sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
		$gate_name = $row['org_name'];
	}
}
if($gate_name=="") $gate_name = $_LANG_TEXT['alltext'][$lang_code];


//**메뉴리스트
require_once (__DIR__."/menulist.inc");

//**페이지 접근 권한 체크
if(isset($page_name)){

	//일반사용자,관리자 공통 사용 페이지
	$common_page = array("main","my_info");
	
	//1.대메뉴 접근 권한 체크
	if(in_array($page_name,$common_page)==false) {

		foreach($_PAGE as $cate => $arr){

			$page_arr = $arr["PAGE"];

			if(array_key_exists($page_name,$page_arr)){
				$current_page_menu_code = $_PAGE[$cate]["MENU_CODE"];
				break;
			}
		}

		if(!in_array($current_page_menu_code,$_ck_user_mauth)){

			$msg = $_LANG_TEXT["accessdenied"][$lang_code];	
			alert($msg);
			exit;
		}
	}

	//**사용자 페이지 접근 권한 가져오기
	$Model_manage = new Model_manage();
	$args = array("emp_seq"=>$_ck_user_seq);
	$result = $Model_manage->getEmpMenuDetailAuth($args);

	$user_mauth = array();
	$comm_user_page_auth = array();
	if($result){
		while($row=@sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
			$comm_user_page_auth[$row['menu_code']][$row['page_code']] = $row['exec_auth'];
			
			if (!in_array($row['menu_code'], $user_mauth)) {
				array_push($user_mauth, $row['menu_code']);
			}
		}
	}

	//**2.개별 페이지 접근 권한 체크
	if($_ck_user_level == "SUPER"){
		
		$comm_current_user_page_exec_auth = "C,R,U,D,P";

	}else if(substr($_ck_user_level,-2)=="_S"){		//조회만 가능

		$comm_current_user_page_exec_auth = "R";

	}else{
		// $_ck_user_mauth = $user_mauth;

		if($current_page_menu_code == ""){
			$comm_current_user_page_exec_auth = "C,R,U,D,P";
		}else{
			if($comm_user_page_auth[$current_page_menu_code]["all"]==""){
				$comm_current_user_page_exec_auth = $comm_user_page_auth[$current_page_menu_code][$page_name];
			}else{
				$comm_current_user_page_exec_auth = $comm_user_page_auth[$current_page_menu_code]["all"];
			}
			if($comm_current_user_page_exec_auth==""){
				$msg = $_LANG_TEXT["accessdenied"][$lang_code];	
				alert($msg);
				exit;
			}
		}
	}

	//**최고관리자만 접근되는 메뉴
	/*
	$super_access_menu = array("policy","group_list","report","login_log");
	if($_ck_user_level == "SUPER"){
		
		$_total_policy_auth = true;
		$_group_list_auth = true;

	}else{

		$_total_policy_auth = false;
		$_group_list_auth = false;

		if(in_array($page_name,$super_access_menu)){
			$msg = $_LANG_TEXT["accessdenied"][$lang_code];	
			alert($msg);
			exit;
		}
	}
	*/

}

//**페이지 엑세스권한(생성,수정,삭제,다운로드/인쇄) 체크
$file_name = @basename(get_included_files()[0]);
if(stripos($file_name,"_excel.php")!==false){		//엑셀다운로드 페이지
	$page_required_auth = "P";
}else{

	$current_page_proc = $_POST['proc'];

	if($current_page_proc=="CREATE"){
		$page_required_auth = "C";
	}else if($current_page_proc=="UPDATE"){
		$page_required_auth = "U";
	}else if($current_page_proc=="DELETE"){
		$page_required_auth = "D";
	}
}

if(gethostname()=="dataprotecs"){	//페이지권한작업이 완료되면 기능오픈하기.

	if($page_required_auth != ""){
		if(strpos($comm_current_user_page_exec_auth,$page_required_auth)===false){	
			$msg = $_LANG_TEXT["accessdenied"][$lang_code];	
			alert($msg);
			exit;
		}
	}

}

/*
* 사용자 등급별 메인 페이지 설정이 있을 경우 해당 페이지로 이동한다.
* /lib/code_wvcs_company.inc
*/
if($page_name=="main"){
	if($_CODE_MAIN_MODE[$_ck_user_level] !=""){

		//메인페이지 접근 권한이 없으면 첫번째 메뉴 페이지로 이동한다.
		if($_CODE_MAIN_MODE[$_ck_user_level]=="access_deny"){
			list($code, $pages) = array_first($comm_user_page_auth);
			list($page, $auth)  = array_first($pages);
			
			$main_url = getMenuPageLink($code, $page);
		}else{
			$main_url = $_CODE_MAIN_MODE[$_ck_user_level];
		}

		$main_file_name = @basename(get_included_files()[0]);

		if($main_file_name != basename($main_url)){
			echo "<meta http-equiv='refresh' content='0;url=".$main_url."'>";
			exit;
		}
	
	}
}


//**소속기관체크
//function.inc 함수 getCheckOrgAuthQuery($column) 호출
?>