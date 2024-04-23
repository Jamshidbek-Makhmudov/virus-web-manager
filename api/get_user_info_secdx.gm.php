<?php

	include $_server_path."/dpt/visit/smdx_gm_conn.php";
	
	/*
	Description 
	 1. 방문신청여부와 상관없이 검사가 가능해야 한다.
	 2. 방문자신청정보가 있으면 신청정보를 가져오고 없으면 입력받은 전화번호 정보으로 신청정보를 임의로 생성해 검사 진행
	 3. USB는 방문자정보만 있고 장치정보는 없다.
	*/
	
	$phone_num =  AES_Rijndael_Decript(base64_decode($_REQUEST['phone_num']), $_AES_KEY, $_AES_IV);
	
	$today = date("Ymd");

	$sql = "
		SELECT
			a.USER_ID, a.USER_NAME, a.EMAIL, a.PHONE, a.COMPANY_NAME,a.MGR_NAME, a.MGR_DEPT, a.BEGIN_DATE, a.BEGIN_DATE,a.VISIT_NUM
		FROM SCMY_VW_DPT_VISIT a
		Where REPLACE(PHONE,'-','') ='{$phone_num}'
			and '{$today}' BETWEEN BEGIN_DATE and END_DATE ";

	//echo nl2br($sql);

	$result = @odbc_exec($conn_tibero, $sql);
	
	$existed = false;
	while($row=odbc_fetch_array($result)){


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
		$ENC_PC_SN = urlencode($PC_SN);
		
		$PC_MODEL = 'UnKnown';
		$ENC_PC_MODEL = urlencode($PC_MODEL);

		$DEV_TYPE = 'unKnown';

		//한글이 깨질경우 변환처리
		$UNAME = iconv("CP949","UTF-8",$UNAME);
		$COMPANY_NAME = iconv("CP949","UTF-8",$COMPANY_NAME);
		$MGR_NAME = iconv("CP949","UTF-8",$MGR_NAME);
		$MGR_DEPT = iconv("CP949","UTF-8",$MGR_DEPT);

		$dev[] = array( "visit_num"=> $VISIT_NUM, "visit_dev_num"=> $VISIT_DEV_NUM, "dev_type" => $DEV_TYPE, "serial_number"=> $ENC_PC_SN,"model_name"=> $ENC_PC_MODEL	);

		$data = array("visit_num"=> $VISIT_NUM, "visit_dev_num"=>$VISIT_DEV_NUM, "visitor_id"=>$USER_ID, "email" => $EMAIL, "phone_num" => $PHONE, "user_name"=> $UNAME, "company_name"=>$COMPANY_NAME, "manager_name"=>$MGR_NAME, "manger_department"=>$MGR_DEPT, "dev_cnt"=> 1, "dev_list" => $dev  );


		$existed = true;
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