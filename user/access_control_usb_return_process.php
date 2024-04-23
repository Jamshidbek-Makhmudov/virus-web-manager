<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
include_once $_server_path . "/" . $_site_path . "/api/common.php";
include_once $_server_path . "/" . $_site_path . "/api/kabang/api.safeconsole.php";

$_v_user_list_seq = $_POST['v_user_list_seq'];
$_proc = $_POST['proc'];

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'UPDATE');

$Model_User = new Model_User();
$args = array("v_user_list_seq"=>$_v_user_list_seq);

/*보안USB정보가져오기
* 보안USB정보가 있는 경우 api 호출해서 카뱅시스템에도 반납처리가 되도록 한다.
*/
$result = $Model_User->getUserUSBInfo($args);

if($result){

	while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
		$usb_id = $row['usb_id'];
		$user_id = $row['user_id'];
	}

	$call_api = true;

}else{
	$call_api = false;
}

if($_proc=="return"){

	if($call_api==true){
		
		$SAFECONSOLE = new SAFECONSOLE();

		//회수처리 대상 USB인지 체크한다.
		$checkAP = $SAFECONSOLE->checkUSB($user_id);

		if($checkAP['status']==true){

			$resultAP = $SAFECONSOLE->returnUSB($usb_id,$user_id);

			if($resultAP['status']==false){
			
				$logMsg = print_r($resultAP,true);
				writeLog($logMsg,'USB API 반납처리 실패1');
				printJson_ERROR($_LANG_TEXT["proc_error"][$lang_code].' - api_error');
			
			}else{
			
				//반납처리결과
				$success = $resultAP['result']['success'];

				if($success==false){
					$logMsg = print_r($resultAP,true);
					writeLog($logMsg,'USB API 반납처리 실패2');
					printJson_ERROR($_LANG_TEXT["proc_error"][$lang_code].' - api_result_failed');
				}
			}

		}else{

			$logMsg = print_r($checkAP,true);
			writeLog($logMsg,'USB 회수처리 대상인지 체크');
		}
	}
	
	$result = $Model_User->updateReturnUsbProc($args);

}else{
	$result = $Model_User->cancelReturnUsbProc($args);
}

if($result){
	printJson_OK('proc_ok');
}else{
	printJson_ERROR('proc_error');
}
?>