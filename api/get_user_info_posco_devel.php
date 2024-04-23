<?php
	/* Description
	* 포스코 물품반출입시스템에서 반입물품 정보 가져오기
	*/
	include  $_server_path."/dpt/visit/posco_ora_conn_io.inc";
	
	//개발테스트
	//http://192.168.169.2/wvcs/api/get_user_info.php?p_in_num=S241MkZVSFRkR09zVXlZREo3MDN3Zz09&company_code=19

	$p_in_num =  AES_Rijndael_Decript(base64_decode($_REQUEST['p_in_num']), $_AES_KEY, $_AES_IV);	//물품반입번호
	$ymd = date("Ymd");
	
	/*
	* 반입가능기간 : 입문전확인일자(입문대기일자)로부터 30일 이내 반입가능
	* 물품유형구분(OP_MAT_TP) :15 -  노트북/태블릿PC ,  91 - USB ,  92  - 외장하드 ,  93 -  SD카드 ,  19 -  촬영기기  ,  99 - 기타
	*/

	$sql = "
		Select   t20.FULL_NAME,t20.MOBILE_PHONE,t20.VENDOR_NAME
			,t20.BRI_NO, t20.BRI_COMMODITY_SEQ, t20.DESCRIPTION , t10.CRROT_APRL_DT
			,t20.ITAST_MODEL_NAME, t20.ITAST_EQUIP_SERIAL_NO
		From TB_P30_NEW_IREQ010 t10
			inner join TB_P30_NEW_IREQ020 t20 ON t10.BRI_NO = t20.BRI_NO
		Where  t10.BRI_NO = '{$p_in_num}'
			AND t10.BRI_CRROT_TP = 'II'
			AND t20.OP_MAT_TP in ('91','92','93','99')
			--AND '{$ymd}'  >= TO_CHAR(CRROT_APRL_DT,'YYYYMMDD') AND  '{$ymd}' <= TO_CHAR(CRROT_APRL_DT+29,'YYYYMMDD') ";

	//echo nl2br($sql);

	$result = oci_parse($connora, $sql);
	oci_execute($result);

	while (($row = oci_fetch_array($result, OCI_BOTH)) != false) {

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