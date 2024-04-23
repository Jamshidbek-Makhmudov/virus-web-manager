<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$Model_Utils = new Model_Utils();

$searchkey = $_POST['searchkey']; 
$query_title = $_POST['query_title']; 

// $proc = $_REQUEST["proc"];

$custom_query_seq=$_REQUEST["custom_query_seq"];

$json = array (
	 'msg' => "[$proc] ".$_LANG_TEXT['procfail'][$lang_code]
	,'data' => ""
	,'status' => false
);

if($searchkey ) {
	//$decodedString = html_entity_decode($searchkey);

	$proc = "SAVE";
  // $Model_Utils->SHOW_DEBUG_SQL = true;
	$args = array("searchkey" => $searchkey, "query_title" => $query_title);
	$result = $Model_Utils->querySave($args);
	
	// $data_value="query_title";
}else if ($custom_query_seq) {
	$proc = "Delete";
	$args = array("custom_query_seq"=>$custom_query_seq);
	$result = $Model_Utils->queryDelete($args);

}

if($result){
	printJson_OK('proc_ok');
}else{
	printJson_ERROR('proc_error');
}
?>

