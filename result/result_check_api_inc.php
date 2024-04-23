<?
if(!$wvcs_dbcon) return;

# include 사용 페이지
#--------------------------------------
# result/result_checkin_process.php
# result/result_checkout_process.php
# result/get_scan_vcs_info.php
#--------------------------------------

#반입/반출 연동
$qry_params = array(
	"v_wvcs_seq"=>$v_wvcs_seq
);
$qry_label = QRY_RESULT_PC_INFO;
$sql = query($qry_label,$qry_params);
$result2 = @sqlsrv_query($wvcs_dbcon, $sql);

if($result2){

	$row=sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);
	
	$arrDevNum = explode( ',', $row['visit_dev_num_list'] );

	for($i=0; $i < count($arrDevNum); $i++) {

			$_visit_dev_num = base64_encode(AES_Rijndael_Encript($arrDevNum[$i], $_AES_KEY, $_AES_IV));
			$_visit_num = base64_encode(AES_Rijndael_Encript($row['visit_num'], $_AES_KEY, $_AES_IV));
			$_vcs_status = base64_encode(AES_Rijndael_Encript($vcs_status, $_AES_KEY, $_AES_IV));
			
			//$visit_dev_num = base64_encode(AES_Rijndael_Encript($row['visit_dev_num'], $_AES_KEY, $_AES_IV));
			//$visit_num = base64_encode(AES_Rijndael_Encript($row['visit_num'], $_AES_KEY, $_AES_IV));
			//$vcs_status = base64_encode(AES_Rijndael_Encript($vcs_status, $_AES_KEY, $_AES_IV));

			$url = $_www_server."/api/set_user_status.php";
			$send_data = array('visit_dev_num' => $_visit_dev_num, "visit_num" => $_visit_num, "vcs_status" => $_vcs_status);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url); 
			curl_setopt($ch, CURLOPT_POST, TRUE); 
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send_data)); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			$ret = curl_exec($ch);
			curl_close($ch);

			//$resultCode = substr($ret,0,1);

			//if($resultCode != "0"){

				//printJson($_LANG_TEXT['procfail'][$lang_code]);
			//}
	}

}
?>