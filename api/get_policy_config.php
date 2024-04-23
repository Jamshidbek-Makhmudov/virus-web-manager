<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

/* Description
*  VCS 정책가져오기
*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
//include  $_server_path . "/lib/dpt25_config.inc";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";
include "./common.php";


		$sql_pol = "	SELECT top 1 windows_update_yn, vaccine_check_yn, vacc_scan_type, window_weakness_check_yn
								,vacc_patch_term, windows_update_term, checkin_pc_term, checkin_kiosk_term
								,checkin_kiosk_in_type,checkin_file_send_type,file_scan_yn,v3_use_yn, eset_use_yn
								,file_scan_device,checkin_file_send_device,kiosk_data_delete_day
							FROM tb_policy 
							ORDER BY policy_seq desc";

		$result = sqlsrv_query($wvcs_dbcon, $sql_pol);

		while( $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
			$windows_update_yn = $row["windows_update_yn"];
			$vaccine_check_yn = $row["vaccine_check_yn"];
			$vacc_scan_type = $row["vacc_scan_type"];
			$windows_weakness_check_yn = $row["windows_weakness_check_yn"];
			$vacc_patch_term = $row["vacc_patch_term"];
			$windows_update_term = $row["windows_update_term"];
			$checkin_pc_term = $row["checkin_pc_term"];
			$checkin_kiosk_term = $row["checkin_kiosk_term"];
			$checkin_kiosk_in_type = $row["checkin_kiosk_in_type"];		//저장매체 반입 형태
			$checkin_file_send_type = $row["checkin_file_send_type"];	//저장매체 파일 전송 타입

			$file_scan_yn = $row[file_scan_yn];		//파일검사여부(위변조검사)
			if($file_scan_yn=="") $file_scan_yn = "N";

			$v3_use_yn = $row[v3_use_yn];		//v3 검사여부
			if($v3_use_yn=="") $v3_use_yn = "Y";

			$eset_use_yn = $row[eset_use_yn];	//eset 검사여부
			if($eset_use_yn=="") $eset_use_yn = "N";

			$file_scan_device = $row[file_scan_device];	//파일검사를 하는 대상 매체
			if($file_scan_device=="") $file_scan_device = "ALL";

			$checkin_file_send_device = $row[checkin_file_send_device];	//파일을 서버로 전송해야 하는 대상 매체
			if($checkin_file_send_device=="") $checkin_file_send_device = "ALL";

			$kiosk_data_delete_day = $row[kiosk_data_delete_day];	//키오스크 데이터 삭제주기
			if($kiosk_data_delete_day=="") $kiosk_data_delete_day = "0";
		}

		$data = array("windows_update_yn"=> $windows_update_yn
							, "vaccine_check_yn" => $vaccine_check_yn
							, "vacc_scan_type" => $vacc_scan_type
							, "windows_weakness_check_yn"=> $windows_weakness_check_yn
							, "vacc_patch_term"=>$vacc_patch_term
							, "windows_update_term"=>$windows_update_term
							, "checkin_pc_term"=>$checkin_pc_term
							, "checkin_kiosk_term"=>$checkin_kiosk_term 
							, "checkin_kiosk_in_type"=>$checkin_kiosk_in_type
							, "checkin_file_send_type"=>$checkin_file_send_type
							, "file_scan_yn"=>$file_scan_yn
							, "v3_use_yn"=>$v3_use_yn
							, "eset_use_yn"=>$eset_use_yn
							, "file_scan_device"=>$file_scan_device
							, "checkin_file_send_device"=>$checkin_file_send_device
							, "kiosk_data_delete_day"=>$kiosk_data_delete_day
			);
	
		$json_data = json_encode($data);

		//echo $json_data;
		echo AES_Rijndael_Encript($json_data, $_AES_KEY, $_AES_IV);

?>