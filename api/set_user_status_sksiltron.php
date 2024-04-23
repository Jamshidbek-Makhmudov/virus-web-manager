<?php

header('Authorization: Basic bm90ZWJvb2s6OTI1YmEyYTQtMmRhZi00NzYyLTk0ODAtMjgyNWM5MzFlMTI2');
//header('Content-Type: application/json;charset=UTF-8');
header('Content-Type:text/html;charset=UTF-8');
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
//include  $_server_path . "/lib/dpt25_config.inc";
//include  $_server_path . "/".$_site_path."/lib/lib.inc";
//include  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";

		
		/* === 읽은 내용 ===
		$visitor_id =  AES_Rijndael_Decript(base64_decode($_REQUEST['visitor_id']), $_AES_KEY, $_AES_IV);
		$visit_num =  AES_Rijndael_Decript(base64_decode($_REQUEST['visit_num']), $_AES_KEY, $_AES_IV);
		$visit_dev_num =  AES_Rijndael_Decript(base64_decode($_REQUEST['visit_dev_num']), $_AES_KEY, $_AES_IV);
		$vcs_status =  AES_Rijndael_Decript(base64_decode($_REQUEST['vcs_status']), $_AES_KEY, $_AES_IV);

		$now = date("Y-m-d H:i:s");
		*/
		
	
		$sql = "select a.v_asset_type,os_ver_name 
			from tb_v_wvcs_info a
				inner join tb_v_wvcs_info_detail b on a.v_wvcs_seq = b.v_wvcs_seq
			where a.visit_num = '{$visit_num}' and a.visit_dev_num = '{$visit_dev_num}' ";

		$result = sqlsrv_query($wvcs_dbcon, $sql);
		while( $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {

			$v_asset_type = $row['v_asset_type'];
			$os_ver_name = $row['os_ver_name'];

			if(strpos($os_ver_name, "CD") !==false){
				$device_kind = "CD";
			}else if(strpos($os_ver_name, "Removable") !==false){
				$device_kind = "USB";
			}else if(strpos($os_ver_name, "HDD") !==false){
				$device_kind = "HDD";
			}
		}
		
		$http_method = "GET";//GET, POST
		$data = array('VISIT_DEV_NUM' => $visit_dev_num, "VISIT_NUM" => $visit_num, "DPT_STATUS" => $vcs_status_code);
		if($_LINKED_URL == "") {
			//운영서버
			$url = "https://welcome.sksiltron.co.kr/app/common/bizTalk/dptResult";
		}else{
			$url = $_LINKED_URL;
		}
		
		//USB,HDD는 DPT에서 반출입처리된다.
		if($device_kind =="CD"){
			
			if($http_method == "GET") $url= $url."?VISIT_DEV_NUM=".$visit_dev_num."&VISIT_NUM=".$visit_num."&DPT_STATUS=".$vcs_status_code;  //DB에 설정되어 있음

			if($url == "") {
				echo "-1";
				exit;
			}

		}

		
		//echo $url."<br/>";
		//$contents = file_get_contents($url);  // url이나 파일 위치
		//$contents = getSslPage($url, $http_method, $data);
		$contents = new_get_file_contents_return($url); 
		
		if(!$contents) {
			//$contents = getSslPage($url, $http_method, $data);
			//$contents = file_get_contents($url);  // url이나 파일 위치
			$contents = new_get_file_contents_return($url); 
		}
		
		//var_dump($contents);

		$json_value = json_decode($contents, true);

		if($json_value == null) {
			$contents = getSslPage($url, $http_method, $data);
			$json_value = json_decode($contents, true);
		}
		//===== 연동  정보요청 : 끝 =====//


		$resultCode = $json_value['code'];
		$resultMsg = $json_value['message'];
		
		echo $resultCode."<br/>".$resultMsg;
?>