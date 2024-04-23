<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$form_div = $_POST['form_div'];
$form_lang = "KR";
$use_yn = "Y";

$Model_manage = new Model_manage();
$args = compact("form_div","form_lang");
$result = $Model_manage->getDocumentContent($args);

$data = array(
			"form_seq"=>0,
			"form_title"=>"",
			"form_content"=>"[]",
			"use_yn"=>$use_yn
		);

if($result){
	while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
		$data['form_seq'] = $row['form_seq'];
		$data['form_title'] = html_entity_decode($row['form_title']);
		$data['form_content'] = html_entity_decode($row['form_content']);
		$data['use_yn'] = $row['use_yn'];
	}
} else {
	if (($form_div == "VSR_IDC_REPORT") || ($form_div == "MGR_IDC_REPORT")) {
		$tasks = array();
		$lists = array();

		$form_content = json_encode(array("tasks"=>$tasks, "lists"=>$lists));

		$data['form_title'] = $form_title;
		$data['form_content'] = $form_content;
	}
}

printJson_OK('ok', $data);
?>