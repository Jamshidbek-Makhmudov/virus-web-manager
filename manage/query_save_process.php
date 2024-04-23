<?php
$page_name = "custom_query";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$proc = $_POST[proc];
$custom_query_seq = $_POST[custom_query_seq];
$query_enc = $_POST[query_enc];
$query_title =  $_POST[query_title];
$query = htmlentities(base64_decode($query_enc),ENT_QUOTES);

if($proc=="DELETE"){
	if($custom_query_seq=="") printJson_ERROR('invalid_data');
}else{
	if($query_enc == "" || $query_title==""){
		printJson_ERROR('invalid_data');
	}
}

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,$proc);

$Model_Utils = new Model_Utils();

if($proc=="CREATE" ) {

	//$Model_Utils->SHOW_DEBUG_SQL = true;
	$args = array("query_content" => $query_enc, "query_title" => $query_title);
	$result = $Model_Utils->queryRegist($args);
	
	// $data_value="query_title";

	$proc_msg = 'save_ok';

}else if($proc=="UPDATE" ) {

	//$Model_Utils->SHOW_DEBUG_SQL = true;
	$args = array("custom_query_seq"=>$custom_query_seq,"query_content" => $query_enc, "query_title" => $query_title);
	$result = $Model_Utils->queryUpdate($args);

	$proc_msg = 'update_ok';

}else if ($proc=="DELETE") {
	$args = array("custom_query_seq"=>$custom_query_seq);
	$result = $Model_Utils->queryDelete($args);

	$proc_msg = 'delete_ok';
}

if($result){
	printJson_OK($proc_msg);
}else{
	printJson_ERROR('proc_error');
}
?>

