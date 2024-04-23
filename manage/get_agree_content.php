<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$_agree_div = $_POST['agree_div'];

$Model_manage = new Model_manage();
$args = array("agree_div"=>$_agree_div,"agree_lang"=>"KR");
$result = $Model_manage->getAgreeContent($args);

$data = array("agree_title"=>"","agree_content"=>"","agree_bottom"=>"","request_consent_yn"=>"","use_yn"=>"");
if($result){
	while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
		$data['agree_config_seq'] = $row['agree_config_seq'];
		$data['agree_title'] = html_entity_decode($row['agree_title']);
		$data['agree_content'] = html_entity_decode($row['agree_content']);
		$data['agree_bottom'] = html_entity_decode($row['agree_bottom']);
		$data['request_consent_yn'] = $row['request_consent_yn'];
		$data['use_yn'] = $row['use_yn'];
	}
}
printJson_OK('ok',$data);
?>