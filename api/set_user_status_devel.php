<?php
//header('Authorization: Basic bm90ZWJvb2s6OTI1YmEyYTQtMmRhZi00NzYyLTk0ODAtMjgyNWM5MzFlMTI2');
//header('Content-Type: application/json;charset=UTF-8');
//header('Content-Type:text/html;charset=UTF-8');
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


		
		$http_method = "GET";//GET, POST
		//$_baseurl = $_HTTP_HTTPS . "://". $_SERVER['SERVER_NAME'].$_PORT;
		//echo "_baseurl = " . $_baseurl . "<br>";
		$url = $_baseurl . "/dpt/visit/set_visitor_status.php?VISIT_DEV_NUM=".$visit_dev_num."&VISIT_NUM=".$visit_num."&DPT_STATUS=".$vcs_status_code;

	
		//echo $url;
		//$contents = file_get_contents($url);  // url이나 파일 위치
		$contents = getSslPage($url, $http_method, $data);
		//$contents = send('JSON', $url);
		//var_dump($contents);

		if(!$contents) {
			//$contents = file_get_contents($url);  // url이나 파일 위치
			$contents = getSslPage($url, $http_method, $data);
		}

		$json_value = json_decode($contents, true);

		if($json_value == null) {
			$contents = getSslPage($url, $http_method, $data);
			$json_value = json_decode($contents, true);
		}
		//===== 연동  정보요청 : 끝 =====//


		$resultCode = $dec_contents['code'];
		$resultMsg = $dec_contents['message'];
		
		echo $resultCode."<br/>".$resultMsg;
?>