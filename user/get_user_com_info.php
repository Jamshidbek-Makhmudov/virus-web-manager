<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$com_seq = $_POST["com_seq"];

$qry_params = array("com_seq"=> $com_seq);
$qry_label = QRY_USER_COM_INFO;
$sql = query($qry_label,$qry_params);

$result = sqlsrv_query($wvcs_dbcon, $sql);

if($result) {

	while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
		
		$data['com_name'] = $row['v_com_name'];
		$data['ceo_name'] = $row['v_ceo_name'];
		$data['com_code1'] = $row['v_com_code_1'];
		$data['com_code2'] = $row['v_com_code_2'];
		$data['com_code3'] = $row['v_com_code_3'];
		$data['com_gubun1'] = $row['v_com_gubun_1'];
		$data['com_gubun2'] = $row['v_com_gubun_2'];
		$data['com_use_yn'] = $row['use_yn'];
	}


	$status = true;
	$msg = $_LANG_TEXT['procsuccess'][$lang_code];

}else{
	$status = false;
	$msg = $_LANG_TEXT['procfail'][$lang_code];
}

printJson($msg,$data,$status,$result,$wvcs_dbcon);
?>