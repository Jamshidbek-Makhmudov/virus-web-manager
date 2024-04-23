<?php

/*
Description 
 1. 방문신청여부와 상관없이 검사가 가능해야 한다.
 2. 방문자신청정보가 있으면 신청정보를 가져오고 없으면 입력받은 전화번호 정보으로 신청정보를 임의로 생성해 검사 진행
 3. USB는 방문자정보만 있고 장치정보는 없다.
*/
	
	$phone_num =  AES_Rijndael_Decript(base64_decode($_REQUEST['phone_num']), $_AES_KEY, $_AES_IV);
	
	$_url = "https://smartcitydev.sec.samsung.net/common/anonymous/sysif/getVisitorByID.do";

	$today = date("Ymd");
	$data["PHONE"] = $phone_num;
	
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$postData = json_encode($data);	

	//$headers[] = "Accept:application/json";
	$headers[] = "Content-Type: application/json;charset=UTF-8";
	$headers[] = "user-agent:".$user_agent;

	$ch = curl_init($_url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	//curl_setopt($ch, CURLOPT_VERBOSE, true);

	$response = curl_exec($ch);

	if (curl_errno($ch)) {
		$msg = curl_error($ch);
		echo AES_Rijndael_Encript($msg, $_AES_KEY, $_AES_IV);
		exit;
	} else {
		$data = json_decode($response,true);
	}

	curl_close($ch);


	//방문기간체크
	foreach($data as $no=>$row){
		if ($row["BEGIN_DATE"] > $today || $row["END_DATE"] < $today){		//방문기간이 아님
			unset($data[$no]);	//삭제
		}
	}

	$existed = sizeof($data) > 0 ? true : false;
	foreach($data as $row){


		$VISIT_NUM = $row['VISIT_NUM'];
		$VISIT_DEV_NUM = "0";
		$USER_ID =  $row['USER_ID'];
		$PHONE = $row['PHONE'];
		$EMAIL = $row['EMAIL'];
		$UNAME = $row['USER_NAME'];
		$COMPANY_NAME = $row['COMPANY_NAME'];
		$MGR_NAME =$row['MGR_NAME'];
		$MGR_DEPT = $row['MGR_DEPT'];
		$BEGIN_DATE = $row["BEGIN_DATE"];
		$END_DATE = $row["END_DATE"];

		$PC_SN = 'UnKnown';
		$PC_MODEL = 'UnKnown';
		$DEV_TYPE = 'unKnown';


		$dev[] = array( "visit_num"=> $VISIT_NUM, "visit_dev_num"=> $VISIT_DEV_NUM, "dev_type" => $DEV_TYPE, "serial_number"=> $PC_SN,"model_name"=> $PC_MODEL	);

		$data = array("visit_num"=> $VISIT_NUM, "visit_dev_num"=>$VISIT_DEV_NUM, "visitor_id"=>$USER_ID, "email" => $EMAIL, "phone_num" => $PHONE, "user_name"=> $UNAME, "company_name"=>$COMPANY_NAME, "manager_name"=>$MGR_NAME, "manger_department"=>$MGR_DEPT, "dev_cnt"=> sizeof($dev), "dev_list" => $dev  );
	}

	if($existed==false){	//방문자 신청정보가 없는 경우 신청정보를 임의로 생성해 검사를 진행한다.

		$VISIT_NUM = 'GUEST';
		$VISIT_DEV_NUM = "1";
		$USER_ID = "";
		$EMAIL = $phone_num."@guest";
		$PHONE = $phone_num;
		$UNAME = "방문자";
		$COMPANY_NAME = "";
		$MGR_NAME = "";
		$MGR_DEPT = "";
		
		$dev[] = array( "visit_num"=> $VISIT_NUM, "visit_dev_num"=> $VISIT_DEV_NUM, "dev_type" => 'unknown', "serial_number"=> "unKnown","model_name"=> "unKnown"	);
		
		$dev_cnt = sizeof($dev);
	
		$data = array("visit_num"=> $VISIT_NUM, "visit_dev_num"=>$VISIT_DEV_NUM, "visitor_id"=>$USER_ID, "email" => $EMAIL, "phone_num" => $PHONE, "user_name"=> $UNAME, "company_name"=>$COMPANY_NAME, "manager_name"=>$MGR_NAME, "manger_department"=>$MGR_DEPT, "dev_cnt"=> $dev_cnt, "dev_list" => $dev  );
		
	
	}
	
	$json_data = json_encode($data);
	
	echo AES_Rijndael_Encript($json_data, $_AES_KEY, $_AES_IV);
	exit;

?>