<?php
$page_name = "usb_list";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";



$usb_seq = intVal($_POST["usb_seq"]);
$proc = $_POST["proc"];
$user_id = $_POST["user_id"];
$usb_id = $_POST["usb_id"];

$proc_name = $_REQUEST["proc_name"];

// $proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,$proc);

$Model_manage=new Model_manage();
if($proc == "CREATE" && $usb_seq <> "") {
	printJson($msg=$_LANG_TEXT['wrongdatatranstext'][$lang_code]);
} else if ( ($proc == "UPDATE" || $proc == "DELETE" ) && $usb_seq == "") {
	printJson($msg=$_LANG_TEXT['wrongdatatranstext'][$lang_code]);
}
//validation
if ($proc != "DELETE") {
	//IP 유효성체크
	if(!$user_id){
		 
			$msg = $_LANG_TEXT['user_id_validate'][$lang_code];
		printJson($msg);
		
	}else if($usb_id==""){
			$msg = $_LANG_TEXT['usb_id_validate'][$lang_code];
		printJson($msg);

	}

}


$msg_user_id = trsLang('중복된데이타입니다', 'duplicatedatatext').'(' . $_LANG_TEXT['user_id_key_text'][$lang_code] .')';
$msg_usb_id = trsLang('중복된데이타입니다', 'duplicatedatatext').'(' . $_LANG_TEXT['usb_id_text'][$lang_code] .')';
if ($proc == "CREATE") {
	//보안USB관리 중복체크
	$search_sql = " user_id = '$user_id'";
	$args = array("search_sql"=>$search_sql);
	$total = $Model_manage->checkExistsUsbList($args);

	if ($total > 0) {
		   printJson_ERROR($msg_user_id);

	} else {
		
		$search_sql = " usb_id = '$usb_id'";
		$args = array("search_sql"=>$search_sql);
		$total = $Model_manage->checkExistsUsbList($args);
		
		if ($total > 0) {
			printJson_ERROR($msg_usb_id);
			
		} else {
	  $args = array("user_id"=>$user_id,"usb_id"=>$usb_id);
		$result = $Model_manage->createUsbList($args);
	}
	
} 


} else if ($proc == "UPDATE") {
//본인인증
	 $Model_manage->SHOW_DEBUG_SQL = false;
	 $order_sql = " ORDER BY usb_seq DESC ";
		$args = array("order_sql"=>$order_sql, "usb_seq" => $usb_seq);
		$result = $Model_manage->getUsbListInfo($args);

			$row = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
				$exists_usb_id = $row['usb_id'];
				$exists_user_id = $row['user_id'];
				if($exists_usb_id==$usb_id && $exists_user_id==$user_id){
					// $Model_manage->SHOW_DEBUG_SQL = true;
					   $args = array("user_id" => $user_id, "usb_id" => $usb_id, "usb_seq" => $usb_seq);
		         $result = $Model_manage->updateUsbList($args);
				
	} else {

	//보안USB관리 중복체크
	$search_sql = " user_id = '$user_id'";
	$args = array("search_sql"=>$search_sql);
	$total = $Model_manage->checkExistsUsbList($args);

	if ($total > 0) {
		   printJson_ERROR($msg_user_id);

	} else {
		
		$search_sql = " usb_id = '$usb_id'";
		$args = array("search_sql"=>$search_sql);
		$total = $Model_manage->checkExistsUsbList($args);
		
		if ($total > 0) {
			printJson_ERROR($msg_usb_id);
			
		} else {
		$args = array("user_id" => $user_id, "usb_id" => $usb_id, "usb_seq" => $usb_seq);
		$result = $Model_manage->updateUsbList($args);
		}
		
	} 

 } //본인인증


}else if ($proc == "DELETE") {

	$args = array("usb_seq"=>$usb_seq);
	$result = $Model_manage->deleteUsbList($args);

}

if($result){
	$msg = $proc=="DELETE" ? "delete_ok" : "save_ok";
	printJson_OK($msg,$data=$usb_seq);
}else{
	printJson_ERROR('proc_error');
}
?>