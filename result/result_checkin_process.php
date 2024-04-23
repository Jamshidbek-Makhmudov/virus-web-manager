<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$v_wvcs_seq = $_POST['v_wvcs_seq'];
$vcs_status = $_POST['vcs_status'];

if($vcs_status=="IN"){
	$apprv_yn = "Y";
	$apprv_name = aes_256_enc($_ck_user_name);
	$apprv_dt = "getdate()";
}else{
	$apprv_yn = "N";
	$apprv_name = "";
	$apprv_dt = "NULL";
}

$qry_params = array(
	"v_wvcs_seq"=>$v_wvcs_seq
	,"wvcs_authorize_yn"=>$apprv_yn
	,"wvcs_authorize_name_encrypt"=>$apprv_name
	,"wvcs_authorize_dt"=>$apprv_dt
	,"vcs_status"=>$vcs_status
);
$qry_label = QRY_RESULT_PC_CHECKIN;
$sql = query($qry_label,$qry_params);

//printJson($sql);

$result = @sqlsrv_query($wvcs_dbcon, $sql);

if($result) {
	$msg = $_LANG_TEXT['procsuccess'][$lang_code];
	
	if($apprv_yn=="Y"){
		$data['apprv_name'] = $_ck_user_name;
		$data['apprv_dt'] = date("Y-m-d H:i:s");
	}else{
		$data['apprv_name'] = "";
		$data['apprv_dt'] = "";
	}
	
	#반입/반출 연동
	include "./result_check_api_inc.php";

	#반입/반출 연동
	/*
	$qry_params = array(
		"v_wvcs_seq"=>$v_wvcs_seq
	);
	$qry_label = QRY_RESULT_PC_INFO;
	$sql = query($qry_label,$qry_params);
	$result2 = @sqlsrv_query($wvcs_dbcon, $sql);

	if($result2){

		$row=sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);
		
		$arrDevNum = explode( ',', $row['visit_dev_num'] );

		for($i=0; $i < count($arrDevNum); $i++) {

				$visit_dev_num = base64_encode(AES_Rijndael_Encript($arrDevNum[$i], $_AES_KEY, $_AES_IV));
				$visit_num = base64_encode(AES_Rijndael_Encript($row['visit_num'], $_AES_KEY, $_AES_IV));
				$vcs_status = base64_encode(AES_Rijndael_Encript($vcs_status, $_AES_KEY, $_AES_IV));
				
				//$visit_dev_num = base64_encode(AES_Rijndael_Encript($row['visit_dev_num'], $_AES_KEY, $_AES_IV));
				//$visit_num = base64_encode(AES_Rijndael_Encript($row['visit_num'], $_AES_KEY, $_AES_IV));
				//$vcs_status = base64_encode(AES_Rijndael_Encript($vcs_status, $_AES_KEY, $_AES_IV));

				$url = $_www_server."/api/set_user_status.php";
				$send_data = array('visit_dev_num' => $visit_dev_num, "visit_num" => $visit_num, "vcs_status" => $vcs_status);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url); 
				curl_setopt($ch, CURLOPT_POST, TRUE); 
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send_data)); 
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				$ret = curl_exec($ch);
				curl_close($ch);

				$resultCode = substr($ret,0,1);

				if($resultCode != "0"){

					//printJson($_LANG_TEXT['procfail'][$lang_code]);
				}
		}

	}
	*/
	
	$status = true;
}else{
	$msg = $_LANG_TEXT['procfail'][$lang_code];
	$status = false;
}
printJson($msg,$data,$status,$result,$wvcs_dbcon);
?>