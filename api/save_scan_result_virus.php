<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header('Authorization: Basic bm90ZWJvb2s6OTI1YmEyYTQtMmRhZi00NzYyLTk0ODAtMjgyNWM5MzFlMTI2');
header('Content-Type: application/json;charset=UTF-8');
header("Content-Type: application/json; charset=UTF-8"); 

/* Description
*  VCS 바이러스 검사결과 저장하기(ESET)
*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";
include "./common.php";

	$company_code =  $_REQUEST["company_code"]; 
	if($company_code == "") {
		$company_code = COMPANY_CODE;	
	}

	$raw_value = $_POST['json'];
	$str_value = unQuotChars($raw_value);
	$json_value = json_decode($str_value, true);

	$wvcs_seq = $json_value['wvcs_seq'];
	$vacc_name =  $json_value['vacc_name'];
	$vacc_ver = $json_value['vacc_ver'];
	$vacc_update_date = getDefineDateFormatDot($json_value['vacc_update_date']);
	$vacc_scan_date = getDefineDateFormatDot($json_value['vacc_scan_date']);

	//2023-11-22 바이러스 검사 시작시간,종료시간 추가
	$vacc_scan_start_date = $json_value['vacc_scan_start_date'];
	$vacc_scan_end_date = $json_value['vacc_scan_end_date'];

	$vacc_scan_count = $json_value['vacc_scan_count'];
	if($vacc_scan_count=="") $vacc_scan_count = 0;
	$virus_list = $json_value['virus_list'];

	//3. 바이러스 검출내역
	$sql_vacc = " INSERT INTO tb_v_wvcs_vaccine ( v_wvcs_seq, vaccine_name, vaccine_update_date, scan_date, scan_start_date,scan_end_date, success_yn, create_dt )
						VALUES ( $wvcs_seq, '$vacc_name', '$vacc_update_date', '$vacc_scan_date', '$vacc_scan_start_date', '$vacc_scan_end_date', 'Y', getdate() ); 
						select SCOPE_IDENTITY() as id; ";


	//writeLog($sql_vacc,'INSERT INTO tb_v_wvcs_vaccine');

	$result = @sqlsrv_query($wvcs_dbcon, $sql_vacc );
	@sqlsrv_next_result($result);
	@sqlsrv_fetch($result);
	$vacc_seq = @sqlsrv_get_field($result, 0);

	if($vacc_seq > 0){
		foreach ($virus_list as $key => $value){

					$virus_name = $value['virus_name']=="" ? "" : AES_Rijndael_Decript($value['virus_name'], $_AES_KEY, $_AES_IV);
					$virus_path = $value['virus_path']=="" ? "" : AES_Rijndael_Decript($value['virus_path'], $_AES_KEY, $_AES_IV);
					$virus_status = $value['virus_status']=="" ? "" : AES_Rijndael_Decript($value['virus_status'], $_AES_KEY, $_AES_IV);
					$vol_letter = substr( $virus_path, 0, 2 );

					$sql_viruslist = " INSERT INTO tb_v_wvcs_vaccine_detail ( vaccine_seq, virus_name, virus_path,  virus_status, create_dt, vol_letter) 
											VALUES ( $vacc_seq , '$virus_name', '$virus_path', '$virus_status', getdate(), '$vol_letter') 	";
					
					//echo $sql_viruslist;
					//echo $sql_viruslist;
					sqlsrv_query($wvcs_dbcon, $sql_viruslist );

					//writeLog($sql_vacc,'INSERT INTO tb_v_wvcs_vaccine_detail');
		}
	}else{
		echo "FALSE:ERROR";
		exit;
	}

	echo "TRUE:".$vacc_seq;
	exit;
?>

