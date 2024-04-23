<?php

	$visitor_id =  AES_Rijndael_Decript(base64_decode($_REQUEST['visitor_id']), $_AES_KEY, $_AES_IV);
	$id =  $_REQUEST["id"];

	if($_REQUEST['visitor_id'] =="" && $id != "") {
			$uid = $id;
	}else{
			$uid = $visitor_id; //strtoupper($_REQUEST["id"]);
	}

	$VISIT_NUM = date("ymd");	//방문번호

	if(COMPANY_CODE==50 && substr($uid, 0,1) <> "_" ) {
		$uid = "_".$uid;
	}

	
	$result = array();

	//장치코드값
	/*
	PCLTP	LapTop(notebook)
	PCDTP	DeskTop
	PSUSB	USB
	PSHDD	HDD
	PSCFC	CF
	PSSDC	SD card
	PSMSD	Micro SD
	PSMSC	MS
	PSXDC	XD
	PSMMC	MMC
	PSMDC	MD
	*/

	/*
	$result[] = array(
		"USER_ID"=> $visitor_id,
		"SERIAL_NUMBER"=> "4D530001070625112064",
		"MODEL"=> "SanDisk Ultra USB Device",
		"VISIT_NUM"=> $VISIT_NUM,
		"USER_NAME"=> "홍길동_{$VISIT_NUM}",
		"EMAIL"=> "test@aa.com",
		"PHONE"=> "01044446666",
		"COMPANY_NAME"=> "데이타프로텍",
		"MGR_NAME"=> "보안관",
		"MGR_DEPT"=> "정보보안",
		"BEGIN_DATE"=> "2022-03-16",
		"END_DATE"=> "2022-05-16",
		"DEV_TYPE"=> "PS0",
		"VISIT_DEV_NUM"=> "1",
		"OPEN_BIT"=> "1",
		"USB"=> "0",
		"LAN"=> "0",
		"WIFI"=> "0",
		"BLUETOOTH"=> "1",
		"CDROM"=> "1",
		"WEBCAM"=> "1",
		"SERIAL"=> "1",
		"SDCARD"=> "1"
	);

	$result[] = array(
		"USER_ID"=> $visitor_id,
		"SERIAL_NUMBER"=> "4C530001070625112064",
		"MODEL"=> "SanDisk Ultra USB Device",
		"VISIT_NUM"=> $VISIT_NUM,
		"USER_NAME"=> "홍길동_{$VISIT_NUM}",
		"EMAIL"=> "test@aa.com",
		"PHONE"=> "01044446666",
		"COMPANY_NAME"=> "데이타프로텍",
		"MGR_NAME"=> "보안관",
		"MGR_DEPT"=> "정보보안",
		"BEGIN_DATE"=> "2022-03-16",
		"END_DATE"=> "2022-05-16",
		"DEV_TYPE"=> "PS0",
		"VISIT_DEV_NUM"=> "2",
		"OPEN_BIT"=> "1",
		"USB"=> "0",
		"LAN"=> "0",
		"WIFI"=> "0",
		"BLUETOOTH"=> "1",
		"CDROM"=> "1",
		"WEBCAM"=> "1",
		"SERIAL"=> "1",
		"SDCARD"=> "1"
	);
	*/

		$result[] = array(
		"USER_ID"=> $uid,
		"SERIAL_NUMBER"=> "5D530001070625112064",
		"MODEL"=> "SanDisk Ultra USB Device",
		"VISIT_NUM"=> $VISIT_NUM,
		"USER_NAME"=> "홍길1235_{$VISIT_NUM}",
		"EMAIL"=> "test1235@aa3.com",
		"PHONE"=> "01012351234",
		"COMPANY_NAME"=> "데이타프로텍",
		"MGR_NAME"=> "보안관",
		"MGR_DEPT"=> "정보보안",
		"BEGIN_DATE"=> "2022-03-16",
		"END_DATE"=> "2022-05-16",
		"DEV_TYPE"=> "PS0",
		"VISIT_DEV_NUM"=> "1",
		"OPEN_BIT"=> "1",
		"USB"=> "0",
		"LAN"=> "0",
		"WIFI"=> "0",
		"BLUETOOTH"=> "1",
		"CDROM"=> "1",
		"WEBCAM"=> "1",
		"SERIAL"=> "1",
		"SDCARD"=> "1"
	);
	

	$result[] = array(
		"USER_ID"=> $uid,
		"SERIAL_NUMBER"=> "N/A",
		"MODEL"=> "SanDisk Ultra USB Device",
		"VISIT_NUM"=> $VISIT_NUM,
		"USER_NAME"=> "홍길1235_{$VISIT_NUM}",
		"EMAIL"=> "test1235@aa3.com",
		"PHONE"=> "01012351234",
		"COMPANY_NAME"=> "데이타프로텍",
		"MGR_NAME"=> "보안관",
		"MGR_DEPT"=> "정보보안",
		"BEGIN_DATE"=> "2022-03-16",
		"END_DATE"=> "2022-05-16",
		"DEV_TYPE"=> "PSMSC",
		"VISIT_DEV_NUM"=> "2",
		"OPEN_BIT"=> "1",
		"USB"=> "0",
		"LAN"=> "0",
		"WIFI"=> "0",
		"BLUETOOTH"=> "1",
		"CDROM"=> "1",
		"WEBCAM"=> "1",
		"SERIAL"=> "1",
		"SDCARD"=> "1"
	);

$index =0;
	for($i = 0 ; $i < sizeof($result) ; $i++){
			
			$row = $result[$i];


				$USER_ID = $row['USER_ID'];
				$PC_SN = trim($row['SERIAL_NUMBER']);
				$ENC_PC_SN = urlencode($PC_SN);
				
				$PC_MODEL = trim($row['MODEL']);
				$ENC_PC_MODEL = urlencode($PC_MODEL);
				$VISIT_NUM = $row['VISIT_NUM'];

				$UNAME = $row["USER_NAME"];
				$EMAIL = $row["EMAIL"];
				$PHONE = $row["PHONE"];
				$COMPANY_NAME = $row["COMPANY_NAME"];
				$MGR_NAME = $row["MGR_NAME"];
				$MGR_DEPT = $row["MGR_DEPT"];
				$BEGIN_DATE = $row["BEGIN_DATE"];
				$END_DATE = $row["END_DATE"];
				
				$DEV_TYPE = $row["DEV_TYPE"];
				$VISIT_DEV_NUM = $row["VISIT_DEV_NUM"];
				$OPEN_BIT = $row["OPEN_BIT"];
				$USB = $row["USB"];
				$LAN = $row["LAN"];
				$WIFI = $row["WIFI"];
				$BLUETOOTH = $row["BLUETOOTH"];
				$CDROM = $row["CDROM"];
				$WEBCAM = $row["WEBCAM"];
				$SERIAL = $row["SERIAL"];
				$SDCARD = $row["SDCARD"];

				//$VISIT_TYPE = $row["VISIT_TYPE"];
				if($OPEN_BIT=="") $OPEN_BIT=0;

				
				$visitNumPlus = $VISIT_NUM."_".$VISIT_DEV_NUM;
				$dev[$index] = array( "visit_num"=> $VISIT_NUM, "visit_dev_num"=> $VISIT_DEV_NUM, "dev_type" => $DEV_TYPE, "serial_number"=> $ENC_PC_SN,"model_name"=> $ENC_PC_MODEL	);

				$index = $index+1;

	}	

		$data = array("visit_num"=> $VISIT_NUM, "visit_dev_num"=>$VISIT_DEV_NUM, "visitor_id"=>$USER_ID, "email" => $EMAIL, "phone_num" => $PHONE, "user_name"=> $UNAME, "company_name"=>$COMPANY_NAME, "manager_name"=>$MGR_NAME, "manger_department"=>$MGR_DEPT, "dev_cnt"=> 2, "dev_list" => $dev  );
		
		//var_dump($data);

		$json_data = json_encode($data);

//		echo $json_data;
//		exit;

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
*/

?>