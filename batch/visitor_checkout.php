<?
/*
* 밤 12시 오피스 입문자 자동 퇴실처리 배치 
* 퇴실처리자는 system 으로 처리 - system 계정생성 필요.
*/
ini_set('memory_limit', '1024M');

if (strpos($_SERVER['windir'], "Windows") || strpos($_SERVER['WINDIR'], "Windows")) {
	$_server_path = "D:/DPTWebManager/htdocs";
} else {
	$_server_path = "/DPT/DPTWebManager/htdocs";
}

$_site_path = "wvcs";

include $_server_path . "/" . $_site_path ."/lib/wvcs_config.inc"; 
include $_server_path . "/" . $_site_path ."/lib/lib.inc"; 
include $_server_path . "/" . $_site_path ."/inc/function.inc"; 

//방문자퇴실처리배치실행여부 정책
$sql = "select top 1 visit_checkout_batch_yn from tb_policy order by policy_seq desc";

$result = sqlsrv_query($wvcs_dbcon, $sql);

while( $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
	$visit_checkout_batch_yn = $row['visit_checkout_batch_yn'];
}

$ymd = date("Ymd");
$ymdhis = date("YmdHis");
$ip_addr = $_SERVER['REMOTE_ADDR'];

//방문자퇴실처리배치실행
if($visit_checkout_batch_yn=="Y"){

	$sql = "select v_user_list_seq 
			from tb_v_user_list
			where v_type='VISIT'
				and visit_status = '1'  
				and in_time like '{$ymd}%' ";

	$result = sqlsrv_query($wvcs_dbcon, $sql);

	while( $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
		$target_v_user_list_seq[] = $row['v_user_list_seq'];
	}

	if(count($target_v_user_list_seq) > 0){

		$str_v_user_list = implode(',',$target_v_user_list_seq);
		
		//퇴실처리
		$sql = "update tb_v_user_list
			set out_time = '{$ymdhis}'
				,visit_status = '0'
				,access_date = '{$ymdhis}'
				,access_emp_seq = isnull((select emp_seq from tb_employee where emp_no='system' ),0)
			where v_user_list_seq in (".$str_v_user_list."); ";

		//퇴실로그
		$sql .= "Insert Into tb_v_user_list_inout_log (
					v_user_list_seq,visit_status,access_date,access_emp_name,access_emp_id,access_ip_addr,memo,create_date )
				select value,'0','{$ymdhis}','system','system','{$ip_addr}','배치 - 퇴실처리','{$ymdhis}'	
				from dbo.fn_split('".$str_v_user_list."',',') ";

		//echo nl2br($sql);

		$result = sqlsrv_query($wvcs_dbcon, $sql);

		$str_result = $result ? "success" : "fail";

		$log_div = "visitor_checkout_batch";
		writeSystemLog($log_div,$str_result,$msg=$str_result);
		echo $str_result;

	}else{
		echo "데이터가 없습니다";
	}

}else{
	echo "방문자퇴실처리배치실행 정책 - No";
}
?>