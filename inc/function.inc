<?php
/*GET PARAM값 디코딩처리*/
if($_GET['enc']){

	$dec_param = urldecode(base64_decode($_GET['enc']));
	$arr_dec_param  = explode("&",$dec_param);

	for($i = 0 ; $i < sizeof($arr_dec_param) ; $i++){
		list($name,$value) = explode("=",$arr_dec_param[$i]);
		$_REQUEST[$name] = $value;
		$_GET[$name] = $value;

		if($name!="gate"){
			$gate_param .= "&".$name."=".$value;
		}
	}
}

/*파일 업로드*/
function upload_file($file01,$file01_name,$size,$savedir='') { //파일 업로드 펑션 업로드 파일, 실제 파일명

  global $_data_path;

 /*
  if($size > 5000000){
      echo "Max File Size 5Mbyte";
      exit;
  }
 */
    if($savedir==""){

		$savedir = $_data_path;

	}else{

		$_data_path = $_SERVER['DOCUMENT_ROOT'] . $savedir;

		$savedir = $_data_path;
	}

	if(is_dir($savedir)==false){
		@mkdir($savedir);
	}

	 //파일 확장자 분리
	$totLen = strLen($file01_name);
	$e_pos = strripos($file01_name,  "."); //끝에서 . 를 찾는다.
	$file_ext = substr ($file01_name , $e_pos+1);

    if($file01_name){
      $file01_name = date("YmdHis").".".$file_ext;
      copy ($file01,"$savedir/$file01_name");
    }
    return $file01_name;


}
/*소속기관체크*/
function getCheckOrgAuthQuery($column){

	global $_ck_user_level;
	global $_ck_user_org;
	global $_ck_user_seq;
	global $_ck_user_mng_org_auth;


	if($_ck_user_level == "SUPER"){
	
		$check_org = ($_ck_user_org!='');

	}else{

		$check_org = true;
	}

	if($check_org){
		
		if($_ck_user_org==""){
			
			//** 관리기관 전체
			if($_ck_user_mng_org_auth){

				$str_mng_org  = "'".implode("','",$_ck_user_mng_org_auth)."'";

			}else{

				$str_mng_org = "'0'";
			}
			
			if(stripos($column,"org_id")!==false){

				$sql = $column." in (".$str_mng_org.") ";

			}else if(stripos($column,"org_name")!==false){

				$sql = $column." in (SELECT org_name FROM tb_organ WHERE org_id in (".$str_mng_org.") ) ";

			}else if(stripos($column,"emp_seq")!==false){

				if($_ck_user_level){

					$sql = $column." in (SELECT emp_seq FROM tb_employee WHERE org_id in (".$str_mng_org.")  ) ";

				}else{
					$sql = $column." = '$_ck_user_seq' ";
				}


			}else{

				$sql = " 1 = 2 ";
			}

		}else{

			if(stripos($column,"org_id")!==false){

				$sql = $column." = '$_ck_user_org' ";

			}else if(stripos($column,"org_name")!==false){

				$sql = $column." = (SELECT org_name FROM tb_organ WHERE org_id='$_ck_user_org') ";

			}else if(stripos($column,"emp_seq")!==false){

				if($_ck_user_level){

					$sql = $column." in (SELECT emp_seq FROM tb_employee WHERE org_id='$_ck_user_org') ";

				}else{
					$sql = $column." = '$_ck_user_seq' ";
				}


			}else{

				$sql = " 1 = 2 ";
			}
		}
		
	}else{

		$sql = " 1 = 1 ";
	}

	return $sql;
}

/*스캔센터권한체크*/
function getCheckScanCenterAuthQuery($organ_check_column,$scan_center_check_column){

	global $_ck_user_level;
	global $_ck_user_seq;
	global $_ck_user_org;
	global $_ck_user_mng_scan_center_auth;

	//기관선택이 있으면 선택기관내에서 스캔센터 권한 체크한다.
	if($_ck_user_org){

		if(stripos($organ_check_column,"org_id")!==false){

			$sql = $organ_check_column." = '$_ck_user_org' ";

		}else if(stripos($organ_check_column,"org_name")!==false){

			$sql = $organ_check_column." = (SELECT org_name FROM tb_organ WHERE org_id='$_ck_user_org') ";

		}else if(stripos($organ_check_column,"emp_seq")!==false){

			if($_ck_user_level){

				$sql = $organ_check_column." in (SELECT emp_seq FROM tb_employee WHERE org_id='$_ck_user_org') ";

			}else{
				$sql = $organ_check_column." = '$_ck_user_seq' ";
			}


		}else{

			$sql = " 1 = 2 ";
		}

	}//if($_ck_user_org){

	
	$sql .= $sql? " AND " : "";

	
	//스캔센터권한체크
	$check_scan_center = ($_ck_user_level != "SUPER");

	if($check_scan_center){

		if($_ck_user_mng_scan_center_auth){

			$str_mng_scan_center  = "'".implode("','",$_ck_user_mng_scan_center_auth)."'";

		}else{

			$str_mng_scan_center = "'0'";
		}
		
		if(stripos($scan_center_check_column,"scan_center_code")!==false){

			$sql .= $scan_center_check_column." in (".$str_mng_scan_center.") ";

		}else if(stripos($scan_center_check_column,"scan_center_name")!==false){

			$sql .= $scan_center_check_column." in (SELECT scan_center_name FROM tb_scan_center WHERE scan_center_code in (".$str_mng_scan_center.") ) ";

		}else{

			$sql .= " 1 = 2 ";
		}
		
	}else{

		$sql .= " 1 = 1 ";
	}

	return $sql;
}

function isExistedEmpNo($id,$dbcon,$check_kabang=true){

	//카뱅 Sync 인사디비 계정 중복 체크
	if(COMPANY_CODE==600 && $check_kabang==true){

		$sql = "SELECT count(*) as cnt FROM tb_emp_kakaobank WHERE emp_id = '$id'";
		$result = sqlsrv_query($dbcon, $sql);
		$row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

		$result  = $row['cnt'] > 0 ? true : false;

		if($result){
			return $result;
		}
	}

	$sql = "SELECT count(*) as cnt FROM tb_employee WHERE emp_no = '$id'";
	$result = sqlsrv_query($dbcon, $sql);
	$row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

	$result  = $row['cnt'] > 0 ? true : false;

	return $result;

}

function printJson($msg,$data='',$status=false,$result='',$dbcon=''){

	$msg = getAlertMsg2($msg);

	$json_array = array(
		'status' => $status,
		'msg' => $msg,
		'result' => $data
	);
	
	if($result) sqlsrv_free_stmt($result);  
	if($dbcon) sqlsrv_close($dbcon);

	echo json_encode($json_array);
	exit;
}

function ConvertToMonthNumber($str){
	
	if($lang_code=="EN"){

		$month = array(
			"January"=>"01",
			"February"=>"02",
			"March"=>"03",
			"April"=>"04",
			"May"=>"05",
			"June"=>"06",
			"July"=>"07",
			"August"=>"08",
			"September"=>"09",
			"October"=>"10",
			"November"=>"11",
			"December"=>"12"
		);

	}else if($lang_code=="KR"){

		$month = array(
			"1월"=>"01",
			"2월"=>"02",
			"3월"=>"03",
			"4월"=>"04",
			"5월"=>"05",
			"6월"=>"06",
			"7월"=>"07",
			"8월"=>"08",
			"9월"=>"09",
			"10월"=>"10",
			"11월"=>"11",
			"12월"=>"12"
		);


	}else if($lang_code=="CN"){

		$month = array(
			"1月"=>"01",
			"2月"=>"02",
			"3月"=>"03",
			"4月"=>"04",
			"5月"=>"05",
			"6月"=>"06",
			"7月"=>"07",
			"8月"=>"08",
			"9月"=>"09",
			"10月"=>"10",
			"11月"=>"11",
			"12月"=>"12"
		);


	}

	return $month[$str];
}

function ParamEnCoding($str){

	return base64_encode(urlencode($str));
}

function dateDiff ($d1, $d2,$unit='day') {
	// Return the number of days between the two dates:

	if($unit=='d'){

		$diff = round(abs(strtotime($d1)-strtotime($d2))/86400);

	}else if($unit=='time'){	//H:i:s
		
		$diff = abs(strtotime($d1)-strtotime($d2));

		$hours = floor($diff/3600);

		$diff = $diff-($hours*3600);

		$min = floor($diff/60);

		$sec = $diff - ($min*60);

		$diff = sprintf("%02d:%02d:%02d", $hours, $min, $sec); 
	}	

	return $diff;
}

# 언어 변환 출력
function trsLang($str,$key='',$lang=''){
	
	global $_LANG_TEXT;
	global $lang_code;

	if($lang_code=="") $lang_code = "KR";

	if($lang==""){
		$trs_lang = $lang_code;
	}else{
		$trs_lang = $lang;
	}

	if($key ==""){	//키값을 모르는 경우 안내정보 출력

		//$index = array_search($str, array_column($_LANG_TEXT, 'KR'));
		//$retVal = array_column($_LANG_TEXT, $trs_lang)[$index];
		
		$lang_kr = array_column($_LANG_TEXT, 'KR');
		
		$arr = array_filter($lang_kr, function($v, $k) use ($str) {
			return str_replace(" ","",$v)==str_replace(" ","",$str);
		}, ARRAY_FILTER_USE_BOTH);

		if(sizeof($arr)==1){

			$index = key($arr);	//첫번째 배열 키값
			$key = array_keys($_LANG_TEXT)[$index];

			return "trsLang('$str','$key')";

		}else{

			foreach($arr as $index => $value){
				
				$key = array_keys($_LANG_TEXT)[$index];
				echo "_LANG_TEXT[$key] => ";
				print_r($_LANG_TEXT[$key]);
				echo "<BR>";
			}
			return;
		}
		
	}else{

		$retVal = $_LANG_TEXT[$key][$trs_lang];

		return $retVal;
	}

	
}


function authIPAllowIDcheck($IP_ADDR, $ID) {

	global $wvcs_dbcon;
	global $_CODE_ALLWAYS_ALLOW_IP;

	if($ID=="wvcsadmin" || $ID=="dptadmin") return $CNTSTR = 1;		//접속허용

	//**항상접속이 허용되는 IP  체크
	if(in_array($IP_ADDR,$_CODE_ALLWAYS_ALLOW_IP)) return $CNTSTR = 1;		//접속허용

	//가장 먼저 IP 차단 정책을 포함 하고 있는지를 본다.
	$sql = "SELECT TOP 1 login_ip_limit_yn FROM tb_policy ORDER BY policy_seq DESC ";
	
	$result1 = @sqlsrv_query($wvcs_dbcon, $sql);

	$row=@sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC);
	
	$login_ip_limit_yn = $row['login_ip_limit_yn'];
	
	if($login_ip_limit_yn == "Y") { //차단 정책 사용함

		$CNTSTR = 0;	//차단

		//만약 차단 정책을 사용하는데 tb_login_ip_mgt에 데이터가 없을 경우는 전체 차단함.
		$sql = "SELECT COUNT(*) AS CNTSTR1 FROM tb_login_ip_mgt ";
		$resultR = @sqlsrv_query($wvcs_dbcon, $sql);
		$cnt = 0;
		while($rowR=@sqlsrv_fetch_array($resultR, SQLSRV_FETCH_ASSOC)){
			$CNTSTR1 = $rowR['CNTSTR1'];
			$cnt++;
		}
		
		if($CNTSTR1 > 0) {
			
			//전체에 적용된 허용 아이피 제한이 있는지 체크
			$sql = "SELECT ip_addr  FROM tb_login_ip_mgt WHERE ALLOW_ID='ALL' ";

			$result = @sqlsrv_query($wvcs_dbcon, $sql);
			$cnt = 0;
			while($row=@sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

				$ALLOW_IP_ADDR = $row['ip_addr'];

				if(stripos($ALLOW_IP_ADDR,$IP_ADDR,0)!== false){	//접속허용
					return $CNTSTR = 1;
				}
			}

			//접속자에게 부여된 허용 아이피 제한이 있는지 체크
			$sql = "SELECT ip_addr FROM tb_login_ip_mgt WHERE ALLOW_ID = N'".$ID."' ";
			$result = @sqlsrv_query($wvcs_dbcon, $sql);

			while($row=@sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

				$CNTSTR = 0;
				$ALLOW_IP_ADDR = $row['ip_addr'];

				if(stripos($ALLOW_IP_ADDR,$IP_ADDR,0)!== false){	//접속허용
					return $CNTSTR = 1;
				}
			}

		}

	} else {	//if($LOGIN_IP_LIMIT_YN == "Y") {

		$CNTSTR = 1;	//접속허용
	}
	
	return $CNTSTR;

}

function getPolicy($columns,$default=''){
	
	global $wvcs_dbcon;

	if(is_array($colums)){
		$str_columns = implode(",",$columns);
	}else{
		$str_columns = $columns;
	}

	$sql = "SELECT TOP 1 {$str_columns} FROM tb_policy ORDER BY policy_seq DESC ";

	//echo $sql;
	
	$result1 = @sqlsrv_query($wvcs_dbcon, $sql);

	$row=@sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC);

	if(sizeof($row)==1 && $default != ""){
		if($row[$columns]=="" || isset($row[$columns])==false || $row[$columns] == null){
			$row[$columns] = $default;
		}
	}
	return $row;
}

function is_instance($x) {
    // not an object, not an instance
    if (!is_object($x)) return false;
    return ($x = get_class($x)) && $x !== 'stdClass';
}

function printJson_OK($msg,$data=''){

	//global $result;
	global $wvcs_dbcon;
	
	$msg = getAlertMsg2($msg);

	$json_array = array(
		'status' => true,
		'msg' => $msg,
		'result' => $data
	);

	//if($result) sqlsrv_free_stmt($result);  
	if($wvcs_dbcon) sqlsrv_close($wvcs_dbcon);

	echo json_encode($json_array);
	exit;
}

function printJson_ERROR($msg='',$data=''){

	//global $result;
	global $wvcs_dbcon;

	$msg = getAlertMsg2($msg);

	$json_array = array(
		'status' => false,
		'msg' => $msg,
		'result' => $data
	);

	//if($result) sqlsrv_free_stmt($result);  
	if($wvcs_dbcon) sqlsrv_close($wvcs_dbcon);

	echo json_encode($json_array);
	exit;
}

function getAlertMsg2($gubun){
	
	global $_LANG_TEXT;
	global $lang_code;

	if($lang_code=="") $lang_code = "KR";

	if($gubun=="save_ok"){

		$str = $_LANG_TEXT['savetext'][$lang_code];

	}else if($gubun=="insert_ok"){

		$str = $_LANG_TEXT['registerationoknntext'][$lang_code];

	}else if($gubun=="update_ok"){

		$str = $_LANG_TEXT['updateoknntext'][$lang_code];

	}else if($gubun=="delete_ok"){

		$str = $_LANG_TEXT['deleteoknntext'][$lang_code];
	
	}else if($gubun=="cancel_ok"){

		$str = $_LANG_TEXT['canceloknntext'][$lang_code];
	
	}else if($gubun=="proc_error"){

		$str = $_LANG_TEXT['proc_error'][$lang_code];

	}else if($gubun=="proc_ok"){

		$str = $_LANG_TEXT['proc_ok'][$lang_code];

	}else if($gubun=="invalid_data"){

		$str = $_LANG_TEXT['wrongdatatranstext'][$lang_code];
	
	}else if($gubun=="dupli_data"){

		$str = $_LANG_TEXT['duplicatedatatext'][$lang_code];

	}else{

		$str = $gubun;
	}

	return $str;
}

/*
* 시스템 작업 로그 기록 (배치잡 실행 등등..)
*/
function writeSystemLog($log_div,$result,$log_content){

	global $wvcs_dbcon;

	$ymdhis = date("YmdHis");

	$base64_encode = base64_encode($log_content);

	$sql = "Insert into tb_system_log (
				log_div, result, content, create_date
			)Values(
				'{$log_div}' ,'{$result}' , '{$base64_encode}', '{$ymdhis}'
			)";

	//echo nl2br($sql);

	@sqlsrv_query($wvcs_dbcon, $sql);

	if($exit) exit;
}

# 작업로그기록
function WriteAdminActLog($log_title,$gubun){

	global $wvcs_dbcon;
	global $_ck_user_id,$_ck_user_name;
	global $_POST;
	global $_GET;
	
	if($log_title==""){
		return 0;
	}

	//전송 데이터
	$recv_get_data = array();
	foreach($_GET as $key=>$value){
		if($value !=""){
			$recv_get_data[$key] =  $value;
		}
	}

	$recv_post_data = array();
	foreach($_POST as $key=>$value){
		if($value !=""){
			$recv_post_data[$key] =  $value;
		}
	}


	$recv_data = array_merge($recv_get_data, $recv_post_data);
	if(sizeof($recv_data) > 0){
		$recv_data_enc= base64_encode(json_encode($recv_data,true));
	}

	if(sizeof($recv_get_data) > 0 && sizeof($recv_post_data) > 0){
		$recv_type = "REQUEST";
	}else if(sizeof($recv_get_data) > 0) {
		$recv_type = "GET";
	}else if(sizeof($recv_post_data) > 0) {
		$recv_type = "POST";
	}else{
		$recv_type = "";
	}

	$log_admin_id = $_ck_user_id;
	$log_admin_name = $_ck_user_name;
	$log_admin_name_enc = aes_256_enc($log_admin_name);

	$ip = $_SERVER['REMOTE_ADDR'];
	$source_name = $_SERVER['PHP_SELF'];
	$referer = $_SERVER['HTTP_REFERER'];

	$referer = str_replace("'","''",$referer);
	$log_date = date("YmdHis");

	$sql = "
		INSERT INTO tb_admin_act_log (
			log_title,emp_no,emp_name,ip_addr,act_type,source_name,referer,recv_data,recv_type,log_date
		)VALUES(
			'".$log_title."','".$log_admin_id."','".$log_admin_name_enc."','".$ip."','".$gubun."','".$source_name."','".$referer."'
			,'".$recv_data_enc."','".$recv_type."','".$log_date."'
		);
	";

	//echo $sql;

	$result = @sqlsrv_query($wvcs_dbcon, $sql);

	if($result){

		$result2 = @sqlsrv_query($wvcs_dbcon, "SELECT @@IDENTITY AS seq");

		if($result2){

			$row = @sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);
			$log_seq = $row['seq'];

		}else $log_seq = 0;
	
	}else{
		printJson("Write Act Log Error");
	}

	return $log_seq;
}

# 작업로그상세 기록
function WriteAdminActDetailLog($work_log_seq,$sql,$params){

	global $wvcs_dbcon;

	if($work_log_seq > 0){

		$_work_logging = true;
		
		$log_query = trim($sql);
		$log_query = trim($log_query,'\t');							//앞뒤 한칸 띄움 삭제
		$log_query = str_replace("\r\n","",$log_query);			//줄바꿈삭제
		$log_query = preg_replace('/\t+/', ' ', $log_query);		//여러칸 띄움->한칸 띄움
		$log_query = str_replace("'","''",$log_query);

		$log_gubun = substr($log_query,0,6);

		if(is_array($params)){

			//로그 메시지 확인 쉽도록 사용자 아이디값  추가하기
			$get_emp_id = false;
			if($params["admin_seq"] != "" && $params["admin_id"]==""){
				$get_emp_id = true;
				$arg_emp_seq = $params["admin_seq"];
				$emp_seq_param_name = 'admin_seq';
				$emp_name_param_name = 'admin_id';
			}

			if($params["emp_seq"] != "" && $params["emp_id"]==""){
				$get_emp_id = true;
				$arg_emp_seq = $params["emp_seq"];
				$emp_seq_param_name = 'emp_seq';
				$emp_name_param_name = 'emp_id';
			}

			if($get_emp_id){
				$sql = "Select emp_no from tb_employee where emp_seq='{$arg_emp_seq}'";
				$result = @sqlsrv_query($wvcs_dbcon, $sql);
				$emp_id =@sqlsrv_get_field( $result, 0);
				if($emp_id){
					$pos = array_search($emp_seq_param_name, array_keys($params));
					$params = insertArray($params,$pos,array($emp_name_param_name=>$emp_id));
				}
			}

			$log_msg = json_encode($params,true);

		}

		if($_work_logging){

			$log_msg_enc = base64_encode($log_msg);
			$log_query_enc =  base64_encode($log_query);

			$log_sql = "
				INSERT INTO tb_admin_act_log_detail (
					act_log_seq,log_msg,query
				)VALUES(
					{$work_log_seq},'{$log_msg_enc}','{$log_query_enc}'
				)
			";

			//echo $log_sql;

			//printJson($log_sql);

			@sqlsrv_query($wvcs_dbcon, $log_sql);

		}

	}//if($work_log_seq > 0){

}

//아이디 유효성 체크
//아이디는 영문 대문자,소문자,숫자를 사용해 5~12자리로 입력해야합니다.	
function validCheck_UserID($_str)
{
	$id = $_str;
	$num = preg_match('/[0-9]/u', $id);
	$eng = preg_match('/[a-z]/u', $id);
	$spe = preg_match('/[`~!@#$%^&*|\\\'\";:\/?^=^+_()<>]/u', $id);

	if(strlen($id) < 5 ){
		
		return array(false,"INVALID_LENGTH_SHORT");
	
	}

	if(strlen($id) > 12){
		
		return array(false,"INVALID_LENGTH_LONG");
	
	}

	if(preg_match("/\s/u", $id) == true){
		
		//return array(false,"공백없이 입력해주세요.");
		return array(false,"INVALID_BLANK");
	
	}

	if($spe > 0){
		
		//return array(false,"특수문자를 사용할 수 없습니다.");
		return array(false,"INVALID_CHAR_MIX");
	
	}
	
	return array(true,"OK");
}

 //비밀번호 유효성 체크
 //비밀번호는 영문대문자,영문소문자, 숫자, 특수문자 중 세가지를 포함해 8~16자 이내로 입력하세요.
function validCheck_Password($_str)
{
	$pw = $_str;
	$num = preg_match('/[0-9]/u', $pw);
	$eng = preg_match('/[a-z]/u', $pw);
	$spe = preg_match('/[\!\@\#\$\%\^\&\*]/u', $pw);

	if(strlen($pw) < 8 ){
		
		//return array(false,"비밀번호 길이는 8자리 이상입니다.");
		return array(false,"INVALID_LENGTH_SHORT");
	
	}

	if(strlen($pw) > 16){
		
		//return array(false,"비밀번호 길이는  최대 16자리입니다.");
		return array(false,"INVALID_LENGTH_LONG");
	
	}

	if(preg_match("/\s/u", $pw) == true){
		
		//return array(false,"비밀번호는 공백없이 입력해주세요.");
		return array(false,"INVALID_BLANK");
	
	}

	if($num == 0 || $eng == 0 || $spe == 0){
		
		//return array(false,"비밀번호는 영문대문자,영문소문자, 숫자, 특수문자 사이에서  세가지종류의 문자가 포함되어야 합니다.");
		return array(false,"INVALID_CHAR_MIX");
	
	}
	
	return array(true,"OK");
}

//전화번호 유효성 체크
function validCheck_Phone($_str)
{
	//$regExp = '/^[0-9]{2,3}-[0-9]{3,4}-[0-9]{4}/';	//휴대전화(한국에서만)
	$regExp = '/[0-9-]/';		//해외포함체크

	if(strlen($_str) < 10){
		return array(false,"INVALID_DATA");
	}

	if(!preg_match($regExp,$_str)){
		return array(false,"INVALID_DATA");
	}

	return array(true,"OK");
}
//이메일 유효성 체크
function validCheck_Email($_str){

	$regExp = '/^[a-zA-Z]{1}[a-zA-Z0-9.\-_]+@[a-z0-9]{1}[a-z0-9\-]+[a-z0-9]{1}\.(([a-z]{1}[a-z.]+[a-z]{1})|([a-z]+))$/';

	if(!preg_match($regExp,$_str)){
		return array(false,"INVALID_DATA");
	}
	return array(true,"OK");
}
# 문자열 숨김 표시
function replaceHiddenChar($val,$type=0,$start=0){
	
	$str = "";
	$str_len = mb_strlen($val,'utf-8');

	if($type==0){

		//start 길이만큼 문자가 보이고 나머지 *처리
		$hidden_length = $str_len-$start;
		$str = mb_substr($val,0,$start,'utf-8').str_pad('',$hidden_length,"*",STR_PAD_LEFT);

	}else if($type==1){
		
		//처음과 끝문자만 보이고 나머지 *처리
		if($str_len > 2){
			
			$hidden_length = $str_len - 2;
			$str = mb_substr($val,0,1,'utf-8').str_pad('',$hidden_length,"*",STR_PAD_LEFT).mb_substr($val,$str_len-1,1,'utf-8');

		}else{
			
			if($str_len==1){
				$str = "*";
			}else{
				$str = "*".mb_substr($val,$str_len-1,1,'utf-8');
			}
		}
	}

	return $str;
}

/**
 * 페이지 파라미터 값 적용
 * $_REQUEST 에 값이 있으면 해당 값을 적용, 없을경우 기본 값을 적용
 */
function setPageParams(&$param, $name, $default = "") {
	$value = $_REQUEST[$name];

	if (empty($value)) {
		$value = $default;
	}

	if (!empty($value)) {
		$GLOBALS[$name] = $value;

		$param = $param . (empty($param) ? "" : "&") . "{$name}={$value}";
	}
}

?>