<?php
	/* Description
	* 포스코 물품반출입시스템에서 반입물품 정보 가져오기
	*/

	$p_in_num =  AES_Rijndael_Decript(base64_decode($_REQUEST['p_in_num']), $_AES_KEY, $_AES_IV);	//물품반입번호
	$ymd = date("Ymd");

	$jsonData ='[{"0":"\ud64d\uae38\ub3d9","FULL_NAME":"\ud64d\uae38\ub3d9","1":"017-627-5949","MOBILE_PHONE":"017-627-5949","2":"\ub3d9\uc11c\ud1b5\uc6b4","VENDOR_NAME":"\ub3d9\uc11c\ud1b5\uc6b4","3":"PI040100001","BRI_NO":"PI040100001","4":"1","BRI_COMMODITY_SEQ":"1","5":"\ubc14\uc774\ube0c\ub85c\ud568\ub9c8 (5ton)","DESCRIPTION":"\ubc14\uc774\ube0c\ub85c\ud568\ub9c8 (5ton)","7":"MODEL1","ITAST_MODEL_NAME":"MODEL1","8":"SN1","ITAST_EQUIP_SERIAL_NO":"SN1"},{"0":"\ud64d\uae38\ub3d9","FULL_NAME":"\ud64d\uae38\ub3d9","1":"017-627-5949","MOBILE_PHONE":"017-627-5949","2":"\ub3d9\uc11c\ud1b5\uc6b4","VENDOR_NAME":"\ub3d9\uc11c\ud1b5\uc6b4","3":"PI040100001","BRI_NO":"PI040100001","4":"2","BRI_COMMODITY_SEQ":"2","5":"\uc804\uc120 (60SQ, 50M)","DESCRIPTION":"\uc804\uc120 (60SQ, 50M)","7":"MODEL2","ITAST_MODEL_NAME":"MODEL2","8":"SN2","ITAST_EQUIP_SERIAL_NO":"SN2"},{"0":"\ud64d\uae38\ub3d9","FULL_NAME":"\ud64d\uae38\ub3d9","1":"017-627-5949","MOBILE_PHONE":"017-627-5949","2":"\ub3d9\uc11c\ud1b5\uc6b4","VENDOR_NAME":"\ub3d9\uc11c\ud1b5\uc6b4","3":"PI040100001","BRI_NO":"PI040100001","4":"3","BRI_COMMODITY_SEQ":"3","5":"\uc720\uc555\ucee8\ud2b8\ub864\ubc15\uc2a4","DESCRIPTION":"\uc720\uc555\ucee8\ud2b8\ub864\ubc15\uc2a4","7":"MODEL3","ITAST_MODEL_NAME":"MODEL3","8":"SN3","ITAST_EQUIP_SERIAL_NO":"SN3"},{"0":"\ud64d\uae38\ub3d9","FULL_NAME":"\ud64d\uae38\ub3d9","1":"017-627-5949","MOBILE_PHONE":"017-627-5949","2":"\ub3d9\uc11c\ud1b5\uc6b4","VENDOR_NAME":"\ub3d9\uc11c\ud1b5\uc6b4","3":"PI040100001","BRI_NO":"PI040100001","4":"4","BRI_COMMODITY_SEQ":"4","5":"\ud06c\ub808\uc778\ubd90(3M)","DESCRIPTION":"\ud06c\ub808\uc778\ubd90(3M)","7":"MODEL4","ITAST_MODEL_NAME":"MODEL4","8":"SN4","ITAST_EQUIP_SERIAL_NO":"SN4"},{"0":"\ud64d\uae38\ub3d9","FULL_NAME":"\ud64d\uae38\ub3d9","1":"017-627-5949","MOBILE_PHONE":"017-627-5949","2":"\ub3d9\uc11c\ud1b5\uc6b4","VENDOR_NAME":"\ub3d9\uc11c\ud1b5\uc6b4","3":"PI040100001","BRI_NO":"PI040100001","4":"5","BRI_COMMODITY_SEQ":"5","5":"H-\ube54 (300*300)-13M","DESCRIPTION":"H-\ube54 (300*300)-13M","7":"MODEL5","ITAST_MODEL_NAME":"MODEL5","8":"SN5","ITAST_EQUIP_SERIAL_NO":"SN5"},{"0":"\ud64d\uae38\ub3d9","FULL_NAME":"\ud64d\uae38\ub3d9","1":"017-627-5949","MOBILE_PHONE":"017-627-5949","2":"\ub3d9\uc11c\ud1b5\uc6b4","VENDOR_NAME":"\ub3d9\uc11c\ud1b5\uc6b4","3":"PI040100001","BRI_NO":"PI040100001","4":"6","BRI_COMMODITY_SEQ":"6","5":"H-\ube54 (300*300)-12M","DESCRIPTION":"H-\ube54 (300*300)-12M","7":"MODEL6","ITAST_MODEL_NAME":"MODEL6","8":"SN6","ITAST_EQUIP_SERIAL_NO":"SN6"},{"0":"\ud64d\uae38\ub3d9","FULL_NAME":"\ud64d\uae38\ub3d9","1":"017-627-5949","MOBILE_PHONE":"017-627-5949","2":"\ub3d9\uc11c\ud1b5\uc6b4","VENDOR_NAME":"\ub3d9\uc11c\ud1b5\uc6b4","3":"PI040100001","BRI_NO":"PI040100001","4":"7","BRI_COMMODITY_SEQ":"7","5":"H-\ube54 (300*300)-2M","DESCRIPTION":"H-\ube54 (300*300)-2M","7":"MODEL7","ITAST_MODEL_NAME":"MODEL7","8":"SN7","ITAST_EQUIP_SERIAL_NO":"SN7"},{"0":"\ud64d\uae38\ub3d9","FULL_NAME":"\ud64d\uae38\ub3d9","1":"017-627-5949","MOBILE_PHONE":"017-627-5949","2":"\ub3d9\uc11c\ud1b5\uc6b4","VENDOR_NAME":"\ub3d9\uc11c\ud1b5\uc6b4","3":"PI040100001","BRI_NO":"PI040100001","4":"8","BRI_COMMODITY_SEQ":"8","5":"\uac00\uc774\ub4dc\ube54 (13M)","DESCRIPTION":"\uac00\uc774\ub4dc\ube54 (13M)","7":"MODEL8","ITAST_MODEL_NAME":"MODEL8","8":"SN8","ITAST_EQUIP_SERIAL_NO":"SN8"}]';
	
	$rowData = json_decode($jsonData, true);
	
	foreach($rowData as $row){

		$USER_ID = "";
		$SERIAL_NUMBER = trim($row['ITAST_EQUIP_SERIAL_NO']);
		$ENC_SERIAL_NUMBER = urlencode($SERIAL_NUMBER);
		
		$MODEL_NAME = trim($row['ITAST_MODEL_NAME']);
		$ENC_MODEL_NAME = urlencode($MODEL_NAME);

		$VISIT_NUM = $row['BRI_NO'];
		$VISIT_DEV_NUM = $row['BRI_COMMODITY_SEQ'];

		$USER_NAME = $row['FULL_NAME'];
		$PHONE = preg_replace("/[^0-9]*/s", "", $row['MOBILE_PHONE']);
		$EMAIL = $PHONE."@visitor.posco";
		$COMPANY_NAME =  $row['VENDOR_NAME'];
		$DEV_TYPE =  $row['DESCRIPTION'];	//물품명
		
		$MGR_NAME = "";
		$MGR_DEPT = "";
		$BEGIN_DATE = "";
		$END_DATE = "";

		$visitNumPlus = $VISIT_NUM."_".$VISIT_DEV_NUM;
		
		//DPT 검사여부를 체크하고 검사된 항목은 vcs 검사시 선택불가처리한다.(CD는 제외-inout_status = 4)
		$sql = "Select 1 From DPT_USER_INOUT_INFO Where REF_VAL1 = '{$visitNumPlus}' and inout_status ='2' ";

		$dpt_rs = @sqlsrv_query($dpt_dbcon, $sql);
		$check_yn = (@sqlsrv_has_rows($dpt_rs)===true ? "Y" : "N" );
		
		$dev[] = array( "visit_num"=> $VISIT_NUM, "visit_dev_num"=> $VISIT_DEV_NUM, "dev_type" => $DEV_TYPE, "serial_number"=> $ENC_SERIAL_NUMBER,"model_name"=> $ENC_MODEL_NAME	,"check_yn"=>$check_yn);
	}


	$data = array("visit_num"=> $VISIT_NUM, "visit_dev_num"=>$VISIT_DEV_NUM, "visitor_id"=>$USER_ID, "email" => $EMAIL, "phone_num" => $PHONE, "user_name"=> $USER_NAME, "company_name"=>$COMPANY_NAME, "manager_name"=>$MGR_NAME, "manger_department"=>$MGR_DEPT, "dev_cnt"=> sizeof($dev), "dev_list" => $dev  );
	
	$json_data = json_encode($data);
	
	echo AES_Rijndael_Encript($json_data, $_AES_KEY, $_AES_IV);


?>