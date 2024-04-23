<?php

/* Description
* 방문자 연동정보 가져오기(개발)
*/

include $_server_path."/dpt/visit/sksiltron_ora_conn.inc";

// id, sn을 받아서 있으면 'Y'
//없으면 'N'^{시리얼번호∏∏시리얼번호∏∏시리얼번호}
	$model = $_REQUEST["modelnm"];
    $model = urlencode($model);
	$sn_list = "";
	$match_flag = "N";
	

	$visitor_id =  AES_Rijndael_Decript(base64_decode($_REQUEST['visitor_id']), $_AES_KEY, $_AES_IV);
	$id =  $_REQUEST["id"];

	/*============================================= 
	- 암호화된 값으로 방문신청 검색 Parameter : visitor_id
	- 평문으로 사용자 방문신청 검색 Parameter : id 
	==============================================*/
	if($_REQUEST['visitor_id'] =="" && $id != "") {
		$uid = $id;
	}else{
		$uid = $visitor_id; //strtoupper($_REQUEST["id"]);
	}

	//--- 방문 공장 지정 조회---//
	//- 컬럼 : PLACE_TYPE
    //- 데이터 : gumi1, gumi2, gumi3, seoul
    //- 방문 장소 여러 공장 선택 시 gumi3,gumi1,gumi2 표기
	if ($_REQUEST['scan_center'] <> "") $scan_center = AES_Rijndael_Decript(base64_decode($_REQUEST['scan_center']), $_AES_KEY, $_AES_IV);

	if($scan_center == "SC01") {
			$visit_place = 'gumi1';
	}else if($scan_center == "SC02") {
			$visit_place = 'gumi2';		
	}else if($scan_center == "SC03") {
			$visit_place = 'gumi3';	
	}
	if($visit_place <> "") $sql_where = " AND PLACE_TYPE like '%".$visit_place."%' "; 

	$todayYmd = date("Ymd");
	
	/*
	$sql = "SELECT count(*) as VW_CNT
			FROM DPT.TB_VISITOR V
					INNER JOIN DPT.TB_VISITOR_DEVICE D ON V.VISIT_NUM = D.VISIT_NUM
			WHERE V.VISIT_NUM in (SELECT max(VISIT_NUM) FROM DPT.TB_VISITOR WHERE  USER_ID='".$uid."' AND '".$todayYmd."' >= BEGIN_DATE AND '".$todayYmd."' <= END_DATE )  
					and USER_ID='".$uid."' ";
	*/
	$sql = "SELECT count(*) as VW_CNT
			FROM DPT.TB_VISITOR V
					INNER JOIN DPT.TB_VISITOR_DEVICE D ON V.VISIT_NUM = D.VISIT_NUM
			WHERE  V.USER_ID='".$uid."' AND '".$todayYmd."' >= BEGIN_DATE AND '".$todayYmd."' <= END_DATE  ".$sql_where;
	$result = oci_parse($connora, $sql);
	oci_execute($result);
	
	//echo $sql;

	while (($row0 = oci_fetch_array($result, OCI_BOTH)) != false) {
		$rs_cnt = $row0['VW_CNT'];
	}
	//echo $rs_cnt."<br>";

/*
	$sql = "SELECT V.USER_ID, V.USER_NAME, V.VISIT_NUM, V.USER_NAME, V.EMAIL, V.PHONE, V.COMPANY_NAME, V.MANAGER_NAME, V.MANAGER_DEPARTMENT, V.BEGIN_DATE, V.END_DATE
					, D.VISIT_DEV_NUM, D.DEVICE_TYPE, D.MODEL, D.SERIAL_NUMBER, D.USB, D.LAN, D.WIFI, D.BLUETOOTH, D.CDROM, D.WEBCAM, D.SERIAL, D.SDCARD, D.OPEN_BIT					
			FROM DPT.TB_VISITOR V
					INNER JOIN DPT.TB_VISITOR_DEVICE D ON V.VISIT_NUM = D.VISIT_NUM
			WHERE V.VISIT_NUM in (SELECT max(VISIT_NUM) FROM DPT.TB_VISITOR WHERE  USER_ID='".$uid."' AND '".$todayYmd."' >= BEGIN_DATE AND '".$todayYmd."' <= END_DATE )  
					and USER_ID='".$uid."' ";
*/

		$sql = "SELECT V.USER_ID, V.USER_NAME, V.VISIT_NUM, V.USER_NAME, V.EMAIL, V.PHONE, V.COMPANY_NAME, V.MANAGER_NAME, V.MANAGER_DEPARTMENT, V.BEGIN_DATE, V.END_DATE
					, D.VISIT_DEV_NUM, D.DEVICE_TYPE, D.MODEL, D.SERIAL_NUMBER, D.USB, D.LAN, D.WIFI, D.BLUETOOTH, D.CDROM, D.WEBCAM, D.SERIAL, D.SDCARD, D.OPEN_BIT					
			FROM DPT.TB_VISITOR V
					INNER JOIN DPT.TB_VISITOR_DEVICE D ON V.VISIT_NUM = D.VISIT_NUM
			WHERE  V.USER_ID='".$uid."' AND '".$todayYmd."' >= BEGIN_DATE AND '".$todayYmd."' <= END_DATE ".$sql_where;
	//echo $sql;

	$result = oci_parse($connora, $sql);
	oci_execute($result);
	$index = 0;
	
	while (($row = oci_fetch_array($result, OCI_BOTH)) != false) {

				$USER_ID = $row['USER_ID'];
				$PC_SN = trim($row['SERIAL_NUMBER']);
				
				$PC_MODEL = trim($row['MODEL']);
				$PC_MODEL = urlencode($PC_MODEL);
				$VISIT_NUM = $row['VISIT_NUM'];

				$UNAME = $row["USER_NAME"];
				$EMAIL = $row["EMAIL"];
				$PHONE = $row["PHONE"];
				$COMPANY_NAME = $row["COMPANY_NAME"];
				//$EMP_ID = $row["EMP_ID"];
				$MGR_NAME = $row["MANAGER_NAME"];
				$MGR_DEPT = $row["MANAGER_DEPARTMENT"];
				$BEGIN_DATE = $row["BEGIN_DATE"];
				$END_DATE = $row["END_DATE"];
				
				$DEV_TYPE = $row["DEVICE_TYPE"];
				$VISIT_DEV_NUM = $row["VISIT_DEV_NUM"];
				$OPEN_BIT = $row["OPEN_BIT"];
				$USB = $row["USB"];
				$LAN = $row["LAN"];
				$WIFI = $row["WIFI"];
				$BLUETOOTH = $row["BLUETOOTH"];
				$CD = $row["CDROM"];
				$WEBCAM = $row["WEBCAM"];
				$SERIAL = $row["SERIAL"];
				$SDCARD = $row["SDCARD"];

				//$VISIT_TYPE = $row["VISIT_TYPE"];
				if($OPEN_BIT=="") $OPEN_BIT=0;

				
				$visitNumPlus = $VISIT_NUM."_".$VISIT_DEV_NUM;

				$dev[$index] = array( "visit_num"=> $VISIT_NUM, "visit_dev_num"=> $VISIT_DEV_NUM, "dev_type"=> $DEV_TYPE, "serial_number"=> $PC_SN,"model_name"=> $PC_MODEL		);

				$index = $index+1;

	}

	$data = array("visit_num"=> $VISIT_NUM, "visit_dev_num"=>$VISIT_DEV_NUM, "visitor_id"=>$USER_ID, "email" => $EMAIL, "phone_num" => $PHONE, "user_name"=> $UNAME, "company_name"=>$COMPANY_NAME, "manager_name"=>$MGR_NAME, "manger_department"=>$MGR_DEPT, "dev_cnt"=> $rs_cnt, "dev_list" => $dev  );

	//var_dump($data);
	$json_data = json_encode($data);

			//로그파일생성
	$logFile = @fopen('debug'.date('Ymd').'.txt', "a+") or die("Unable to open file!");
	fwrite($logFile, $json_data."\r\n\r\n");
	fclose($logFile);

	//echo $json_data;
	/*============================================= 
	- 암호화된 값으로 방문신청 검색 Parameter : visitor_id
	- 평문으로 사용자 방문신청 검색 Parameter : id 
	==============================================*/
	if($_REQUEST['visitor_id'] =="" && $id != "") {
			echo $json_data;
	}else{
			echo AES_Rijndael_Encript($json_data, $_AES_KEY, $_AES_IV);
	}

/*
		$sql_user = " SELECT top 1 USER_ID, USER_NAME, PC_SN, PC_MODEL, EMAIL, PHONE, COMPANY_NAME, MANAGER_NAME, MANAGER_DEPARTMENT, MANAGER_EMAIL, BEGIN_DATE, END_DATE
									, VISIT_NUM, VISIT_NUM_SEQ, USB, LAN, WIFI, BLUETOOTH, CDROM, WEBCAM, SERIALPORT, SDCARD, OPEN_BIT
							FROM   DPT_VISITOR_TYPE2
							WHERE VISIT_NUM_SEQ=0
									AND Replace(convert(varchar(10), getdate(), 21), '-', '')  BETWEEN BEGIN_DATE AND END_DATE
									AND USER_ID='".$visitor_id."'
							ORDER BY VISITOR_SEQ desc
						";

		$result = sqlsrv_query($dpt_dbcon, $sql_user);
		$visit_num = -1;	
		while( $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {

			$visit_num = $row["VISIT_NUM"];
			$email = $row["EMAIL"];
			$user_name = $row["USER_NAME"];
			$phone_num = $row["PHONE"];
			$company_name = $row["COMPANY_NAME"];
			$manager_name = $row["MANAGER_NAME"];
			$manager_department = $row["MANAGER_DEPARTMENT"];
		}

		$data = array("visit_num"=> $visit_num, "email" => $email, "phone_num" => $phone_num, "user_name"=> $user_name, "company_name"=>$company_name, "manager_name"=>$manager_name, "manger_department"=>$manager_department );
	
		$json_data = json_encode($data);

		//echo $json_data;
		echo AES_Rijndael_Encript($json_data, $_AES_KEY, $_AES_IV);
*/
?>