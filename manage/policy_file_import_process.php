<?php
$page_name = "policy";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$proc = $_POST["proc"];
$policy_file_in_seq = $_POST["policy_file_in_seq"];
$policy_name = $_POST["policy_name"];
$start_date = $_POST["start_date"];
$end_date = $_POST["end_date"];
$target = $_POST["target"];
$target_value = $_POST["target_value"];
$target_name = $_POST["target_name"];
$file_div = $_POST["file_div"];
$file_send_status = $_POST["file_send_status"];
$file_hash = $_POST["file_hash"];
$file_name = $_POST["file_name"];
$file_comment = $_POST["file_comment"];


if($file_send_status =="") $file_send_status  = "0"; //기본값(서버미전송)

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,$proc);

$start_date = preg_replace("/[^A-Za-z0-9]/", "",$start_date);
$end_date = preg_replace("/[^A-Za-z0-9]/", "",$end_date);

$ymdhis = date("YmdHis");

//시작일이 오늘이전 또는 오늘이면 현재시점부터 정책을 적용한다.
if($start_date > date("Ymd")){	
	$start_time = $start_date."000000";
}else{
	$start_time =$ymdhis;
}
$end_time = $end_date."235959";

$Model_manage = new Model_manage();

$args = array("policy_name"=>$policy_name
	,"start_date"=>$start_time
	,"end_date"=>$end_time
	,"target"=>$target
	,"target_name"=>aes_256_enc($target_name)
	,"target_value"=>$target_value
	,"file_div"=>$file_div
	,"file_send_status"=>$file_send_status
	,"create_emp_seq"=>$_ck_user_seq
	,"policy_file_in_seq"=>$policy_file_in_seq
);	



//현재정책 정보 가져오기
if($policy_file_in_seq > 0){
	
	$args2 = array("policy_file_in_seq"=>$policy_file_in_seq);	
	$result = $Model_manage->GetFileInPolicyInfo($args2);	
	if($result){
		while($row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
			$old_start_date = $row['start_date'];
			$old_end_date = $row['end_date'];
		}
	}
}




//등록
function fn_reg($args){

	global $Model_manage;
	global $file_hash, $file_name, $file_comment;

	$Model_manage->SHOW_DEBUG_SQL = false;
	$policy_file_in_seq = $Model_manage->SaveFileInPolicy($args);

	if($policy_file_in_seq==0) printJson_ERROR('proc_error');
	
	//지정파일이 있으면
	if($args['file_div']=="FILE"){

		$args = array("policy_file_in_seq"=>$policy_file_in_seq);
		$result = $Model_manage->DeleteFileInPolicyFileList($args);

		if(!$result) 	printJson_ERROR('proc_error');
		   echo "szie: " . sizeof($file_hash). "\n";

		for($i = 0 ; $i < sizeof($file_hash) ; $i++){
				  // Print current iteration and values
    echo "Iteration: " . $i . "\n";
 
    echo "ip_addr: " . $file_hash[$i] . "\n";
    echo "allow_id: " . $file_name[$i] . "\n";

			$args = array("policy_file_in_seq"=>$policy_file_in_seq
				,"file_hash"=>$file_hash[$i]
				,"file_name"=>$file_name[$i]
				,"file_comment"=>$file_comment[$i]
				);
			
			$result = $Model_manage->SaveFileInPolicyFileList($args);
			
			if(!$result) printJson_ERROR('proc_error');
		}

	}
	
	printJson_OK('save_ok',$data=$policy_file_in_seq);
}

//수정
function fn_update($args){



	global $Model_manage;
	global $file_hash, $file_name, $file_comment;


	$Model_manage->SHOW_DEBUG_SQL = false;
	$result = $Model_manage->UpdateFileInPolicy($args);

	if(!$result) printJson_ERROR('proc_error');
	
	//지정파일이 있으면
	if($file_div=="FILE"){

		$args = array("policy_file_in_seq"=>$policy_file_in_seq);
		$result = $Model_manage->DeleteFileInPolicyFileList($args);

		if(!$result) 	printJson_ERROR('proc_error');

		for($i = 0 ; $i < sizeof($file_hash) ; $i++){


			$args = array("policy_file_in_seq"=>$policy_file_in_seq
				,"file_hash"=>$file_hash[$i]
				,"file_name"=>$file_name[$i]
				,"file_comment"=>$file_comment[$i]
				);
			

			$result = $Model_manage->SaveFileInPolicyFileList($args);
			
			if(!$result) printJson_ERROR('proc_error');
		}

	}

	return true;
}


if($proc=="CREATE"){

	fn_reg($args);
	
}else if($proc=="UPDATE"){

	if($policy_file_in_seq == "" ) {
		printJson_ERROR('invalid_data');
	}

	/* 정책 수정시
	* 기존 적용기간 체크 
	*  ㄴ 적용 전이면 수정 허용
	*	ㄴ 적용 중이면 기존 정책 적용기간을 현재시점 까지로 종료 처리하고 새롭게 등록
	*	ㄴ 적용기간 경과이면 수정 불가
	*/
	
	if($ymdhis < $old_start_date){		//적용전이면 수정가능
		
		$updated = fn_update($args);
		if($updated) printJson_OK('update_ok');
		
	}else if($ymdhis > $old_start_date && $ymdhis < $old_end_date){	//적용중이면 현재 정책은 종료하고 새롭게 등록

		$args['end_date'] = $ymdhis;	
		$updated = fn_update($args);

		if($updated){
			$args['start_date'] = date("YmdHis", strtotime("+1 seconds"));
			$args['end_date'] = $end_time;	
			fn_reg($args);
		}

	}else{	//적용기간 경과이면 수정 불가

		printJson_ERROR(trsLang('적용기간이 종료되어 수정할 수 없습니다','cantupdate_applydateexceed'));
	}

}else if($proc=="DELETE"){

	if($policy_file_in_seq == "" ) {
		printJson_ERROR('invalid_data');
	}

	/* 정책 삭제시
	* 기존 적용기간 체크 
	*  ㄴ 적용 전이면 삭제 허용
	*	ㄴ 적용 중이면 기존 정책 적용기간을 현재시점 까지로 종료 처리
	*	ㄴ 적용기간 경과이면 삭제 불가
	*/
	
	if($ymdhis < $old_start_date){		//적용전이면 삭제가능

		$args = array("policy_file_in_seq"=>$policy_file_in_seq);
		$result = $Model_manage->DeleteFileInPolicy($args);

		if($result){ 
			printJson_OK('delete_ok');
		}else printJson_ERROR('proc_error');		

	}else if($ymdhis > $old_start_date && $ymdhis < $old_end_date){	//적용중이면 현재 정책은 종료처리
		
		$args = array("policy_file_in_seq"=>$policy_file_in_seq,"end_date"=>$ymdhis);
		$result = $Model_manage->UpdateFileInPolicyEndDate($args);

		if($result) {
			printJson_ERROR(trsLang('적용기간이 종료되었습니다','applydateclosed'));
		}else printJson_ERROR('proc_error');	
	
	}else{	//적용기간 경과이면 삭제 불가

		printJson_ERROR(trsLang('적용기간이 종료되어 삭제할 수 없습니다','cantdelete_applydateexceed'));
	}

	
}




?>