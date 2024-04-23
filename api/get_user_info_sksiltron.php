<?php

	include $_server_path."/dpt/visit/sksiltron_ora_conn.inc";

	/*============================================= 
	- 암호화된 값으로 방문신청 검색 Parameter : visitor_id
	- 암호화된 값으로 방문 지점(공장) 검색 Parameter : scan_center
	   각 ScanCenter에 대응되는 공장코드값 : SC01 - ?공장, SC02 - ?공장, SC03 - ?공장
	- 평문으로 사용자 방문신청 검색 Parameter : id 
	==============================================*/
	
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

	$visitor_id =  AES_Rijndael_Decript(base64_decode($_REQUEST['visitor_id']), $_AES_KEY, $_AES_IV);
	$id =  $_REQUEST["id"];

	if($_REQUEST['visitor_id'] =="" && $id != "") {
			$uid = $id;
	}else{
			$uid = $visitor_id; //strtoupper($_REQUEST["id"]);
	}

	if(COMPANY_CODE==50 && substr($uid, 0,1) <> "_" ) {
		$uid = "_".$uid;
	}
	
	$todayYmd = date("Y-m-d");
	
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

	/* PC 반입은 dpt에서 연동
	* htdocs/dpt/visit/sksiltron_get_visitor.php
	*/
	
	$sql = "SELECT count(*) as VW_CNT
			FROM V_VISIT_INFO V
				INNER JOIN V_APP_DEVICE D ON V.VISIT_NUM = D.VISIT_NUM
					AND D.DEV_TYPE like 'PS%'
			WHERE V.VISIT_NUM in (SELECT max(VISIT_NUM) FROM V_VISIT_INFO WHERE  USER_ID='".$uid."' AND '".$todayYmd."' >= START_DATE AND '".$todayYmd."' <= END_DATE) 
					and USER_ID='".$uid."'".$sql_where;
	//$sql = "SELECT COUNT(*) AS CNT FROM VW_IPTFT_HR_INFO ORDER BY ORG_NUMBER ASC ";
	$result = oci_parse($connora, $sql);
	oci_execute($result);
	
	while (($row0 = oci_fetch_array($result, OCI_BOTH)) != false) {
		$rs_cnt = $row0['VW_CNT'];
	}
	//echo $rs_cnt."<br>";


	$sql = "SELECT V.USER_ID, V.USER_NAME, V.VISIT_NUM, V.USER_NAME, V.EMAIL, V.PHONE, V.COMPANY_NAME, V.MGR_NAME, V.MGR_DEPT, V.START_DATE, V.END_DATE
					, D.VISIT_DEV_NUM , D.DEV_TYPE, D.MODEL_NAME, D.SERIAL_NUMBER
					, USB,  LAN,  WIFI,  '1' as BLUETOOTH,  CD,  '1' as WEBCAM,  SERIAL, SDCARD, OPEN_BIT	
			FROM V_VISIT_INFO V
					INNER JOIN V_APP_DEVICE D ON V.VISIT_NUM = D.VISIT_NUM
						AND D.DEV_TYPE like 'PS%'
			WHERE V.VISIT_NUM in (SELECT max(VISIT_NUM) FROM V_VISIT_INFO WHERE  USER_ID='".$uid."' AND '".$todayYmd."' >= START_DATE AND '".$todayYmd."' <= END_DATE )  
					and USER_ID='".$uid."' ".$sql_where;
	$result = oci_parse($connora, $sql);
	oci_execute($result);
	$index = 0;

	while (($row = oci_fetch_array($result, OCI_BOTH)) != false) {

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
				$CDROM = $row["CD"];
				$WEBCAM = $row["WEBCAM"];
				$SERIAL = $row["SERIAL"];
				$SDCARD = $row["SDCARD"];

				//$VISIT_TYPE = $row["VISIT_TYPE"];
				if($OPEN_BIT=="") $OPEN_BIT=0;

				
				$visitNumPlus = $VISIT_NUM."_".$VISIT_DEV_NUM;
				$dev[$index] = array( "visit_num"=> $VISIT_NUM, "visit_dev_num"=> $VISIT_DEV_NUM, "dev_type" => $DEV_TYPE, "serial_number"=> $ENC_PC_SN,"model_name"=> $ENC_PC_MODEL		);

				$index = $index+1;

	}	

		$data = array("visit_num"=> $VISIT_NUM, "visit_dev_num"=>$VISIT_DEV_NUM, "visitor_id"=>$USER_ID, "email" => $EMAIL, "phone_num" => $PHONE, "user_name"=> $UNAME, "company_name"=>$COMPANY_NAME, "manager_name"=>$MGR_NAME, "manger_department"=>$MGR_DEPT, "dev_cnt"=> $rs_cnt, "dev_list" => $dev  );
		
		//var_dump($data);

		$json_data = json_encode($data);

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
*/

?>