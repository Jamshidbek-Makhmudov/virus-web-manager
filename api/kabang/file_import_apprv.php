<?
/*
* Description : 외부 파일 반입 예외 신청 승인 정보 수신, 업데이트
* 카뱅에서 호출한다.
*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";
include "./../common.php";

//method 체크
$method =  $_SERVER['REQUEST_METHOD'];
if($method !="POST") exit;

/* token 발급
* L1BJQ01TcTFpMTY0TllvOGo3TytrTjc3TnRGZ2wxdWxxcUJqd09kUm1GTT0=
*/
$token = base64_encode(AES_Rijndael_Encript("KAKAOBANK VCS-API-KEY", $_AES_KEY_256, $_AES_IV));
$header_auth = array_change_key_case(apache_request_headers(), CASE_LOWER)['authorization'];

//token 체크
/*
if (! preg_match('/Token\s(\S+)/', $header_auth, $matches)) {
    header('HTTP/1.0 400 Bad Request');
	exit;
}
if($matches[1]!=$token){
	header('HTTP/1.0 400 Bad Request');
	exit;
}
*/

if($header_auth != $token){
	header('HTTP/1.0 400 Bad Request');
	exit;
}

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

function printJson($result,$msg){
	$rtn = array("result"=>$result
		,"msg"=>$msg);

	echo json_encode($rtn);
	exit;
}

$json = file_get_contents("php://input");
$data = json_decode($json,true);

/* Test Data
if(gethostname()=="dataprotecs"){
	$data = array(
		"request_no"=>"M-REQ-231220-0015",
		"apprv_status"=>"APPRV",
		"file_send_status"=>"0",
		"emp_id"=>"jorba"
	);
}
*/

$apply_seq = $data['request_no'];
$apprv_status = $data['apprv_status'];
$file_send_status = '0';	//예외파일반입 기본값(서버미전송)
$emp_id = $data['emp_id'];

//**데이타 유효성 체크
if(in_array($apprv_status, array("APPRV","REJECT","CANCEL"))==false){
	printJson('fail','invalid data - apprv_status');
}

if(in_array($file_send_status,array("0","1"))==false){
	printJson('fail','invalid data - file_send_status');
}


$apprv_date = date("YmdHis");

$sql = "Update tb_v_wvcs_info_file_in_apply
	set approve_status = '{$apprv_status}'
		,approve_date = '{$apprv_date}'
		,approver_id='{$emp_id}'
		,approver_name = (select top 1 emp_name from tb_emp_kakaobank where emp_id='{$emp_id}')
	where refer_apply_seq = '{$apply_seq}'
	    and approve_status= 'WAIT' ";

$result = @sqlsrv_query($wvcs_dbcon, $sql);

$rows_affected = @sqlsrv_rows_affected($result);

if( $rows_affected === false) {
	writeLog($sql);
	printJson("fail",print_r( sqlsrv_errors(), true));
} elseif( $rows_affected <= 0) {
	writeLog($sql);
    printJson("fail","not found data");
} else {
	
	//**승인이면 파일 반입 예외 정책으로 등록한다.
	if($apprv_status=='APPRV'){

		$policy_name = "외부 파일 반입 예외 신청 승인";
		$target = "EMP";		//사용자(임직원)
		$file_div = "FILE";	//지정파일
		$create_emp_seq = "0";	//등록자
		$create_date = date("YmdHis");
		$refer = "API";
		$today = date("Ymd");

		$sql = "";		
		
		/*
		* 정책 적용 시작일은 승인일시를 시작일로 설정한다.
		*/
		$sql .= " Insert Into tb_policy_file_in (
						policy_name,start_date,end_date,target,target_value,target_name,file_div
						,create_emp_seq,create_date,refer,file_send_status
						,v_user_list_seq,v_wvcs_seq,file_in_apply_seq)
					Select '{$policy_name}' as policy_name
							,(case when start_date > '{$today}' then start_date+'000000' else '{$create_date}' end) as start_date
							, end_date+'235959' as end_date
							,'{$target}', manager_id as target_value, manager_name as target_name
							,'{$file_div}','{$create_emp_seq}','{$create_date}','{$refer}',file_send_status
							,(select v_user_list_seq from tb_v_wvcs_info where v_wvcs_seq=t.v_wvcs_seq)
							,v_wvcs_seq,file_in_apply_seq
					From tb_v_wvcs_info_file_in_apply t
					where refer_apply_seq = '{$apply_seq}'; ";

		$sql .= "Insert Into tb_policy_file_in_list (
						policy_file_in_seq,file_hash,file_name,file_comment,create_date)
					Select  (Select scope_identity()) as policy_file_in_seq, t2.file_hash, t2.file_name,t1.reason,'{$create_date}'
					From tb_v_wvcs_info_file_in_apply t1 
						inner join tb_v_wvcs_info_file_in_apply_detail t2 on t1.file_in_apply_seq = t2.file_in_apply_seq
					where refer_apply_seq = '{$apply_seq}'; ";

		$result = @sqlsrv_query($wvcs_dbcon, $sql);


		if(!$result){	//실패이면

			writeLog($sql);

			$sql = " Update tb_v_wvcs_info_file_in_apply
				set approve_status = 'WAIT'
					,approve_date = null
					,approver_id=null
					,approver_name = null
				where refer_apply_seq = '{$apply_seq}' ";
			
			@sqlsrv_query($wvcs_dbcon, $sql);

			printJson("fail","proc err");
		}

	}

	printJson("ok","success");
}
?>