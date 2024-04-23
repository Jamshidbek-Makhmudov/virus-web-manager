<?php
/*
Description 
 1. 방문신청여부와 상관없이 검사가 가능해야 한다.
 2. 방문자신청정보가 있으면 신청정보를 가져오고 없으면 입력받은 전화번호 정보으로 신청정보를 임의로 생성해 검사 진행
 3. USB는 방문자정보만 있고 장치정보는 없다.
*/
	
	$phone_num =  AES_Rijndael_Decript(base64_decode($_REQUEST['phone_num']), $_AES_KEY, $_AES_IV);

	//$phone_num = "010-2222-3333";

	$today = date("Ymd");
	$ymd = date("Ymd");
	$ymdhis = date("YmdHis");
	$uid = "id".rand();
	$user_name = "홍길동".rand();

	$testData = '[{"USER_ID":"'.$uid.'","USER_NAME":"'.$user_name.'","EMAIL":"'.$uid.'@partner.samsung.com","PHONE":"'.$phone_num.'","MGR_NAME":"정보완","MGR_DEPT":"ICTO 1 Group","BEGIN_DATE":"'.$ymd.'","END_DATE":"'.$ymd.'","VISIT_NUM":"AKM'.$ymdhis.'","VISIT_DEV_NUM":1,"SERIAL_NUMBER":"sn","MODEL_NAME":"갤럭시북","MEDIA":{"OPEN_BIT":1,"USB":1,"LAN":1,"WIFI":1,"CD":0,"SDCARD":1,"SERIAL":1,"WEBCAM":1,"BLUETOOTH":1,"VOL_UNMOUNT":1}},{"USER_ID":"'.$uid.'","USER_NAME":"'.$user_name.'","EMAIL":"'.$uid.'@partner.samsung.com","PHONE":"'.$phone_num.'","MGR_NAME":"정보완","MGR_DEPT":"ICTO 1 Group","BEGIN_DATE":"'.$ymd.'","END_DATE":"'.$ymd.'","VISIT_NUM":"AKM'.$ymdhis.'","VISIT_DEV_NUM":2,"SERIAL_NUMBER":"sn","MODEL_NAME":"model","MEDIA":{"OPEN_BIT":1,"USB":1,"LAN":1,"WIFI":1,"CD":0,"SDCARD":1,"SERIAL":1,"WEBCAM":1,"BLUETOOTH":1,"VOL_UNMOUNT":1}},{"USER_ID":"'.$uid.'","USER_NAME":"'.$user_name.'","EMAIL":"'.$uid.'@partner.samsung.com","PHONE":"'.$phone_num.'","MGR_NAME":"정보완","MGR_DEPT":"ICTO 1 Group","BEGIN_DATE":"20240301","END_DATE":"20240301","VISIT_NUM":"AKM'.$ymdhis.'","VISIT_DEV_NUM":3,"SERIAL_NUMBER":"sn","MODEL_NAME":"model3","MEDIA":{"OPEN_BIT":1,"USB":1,"LAN":1,"WIFI":1,"CD":0,"SDCARD":1,"SERIAL":1,"WEBCAM":1,"BLUETOOTH":1,"VOL_UNMOUNT":1}}]';

	$data = json_decode($testData,true);

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
		$COMPANY_NAME = $row['COMPANY_NAME'];	//없음. 확인필요!!
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

		$VISIT_NUM = "GUEST";
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
	
	//echo $json_data;
	echo AES_Rijndael_Encript($json_data, $_AES_KEY, $_AES_IV);
	exit;

?>