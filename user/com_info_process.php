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
$com_name = $_POST["com_name"];
$com_use_yn = $_POST["com_use_yn"];
$ceo_name = $_POST["ceo_name"];
$com_code1 = $_POST["com_code1"];
$com_code2 = $_POST["com_code2"];
$com_code3 = $_POST["com_code3"];
$com_gubun1 = $_POST["com_gubun1"];
$com_gubun2 = $_POST["com_gubun2"];

if($com_seq == "") {
	printJson($_LANG_TEXT['wrongdatatranstext'][$lang_code]);
}

if($com_seq==""){

	if($com_name !=""){

		$qry_params = array(
			"com_name"=> $com_name
			,"ceo_name"=>$ceo_name
			,"com_code1"=>$com_code1
			,"com_code2"=>$com_code2
			,"com_code3"=>$com_code3
			,"com_gubun1"=>$com_gubun1
			,"com_gubun2"=>$com_gubun2
			,"com_use_yn"=>$com_use_yn
		);
		$qry_label = QRY_USER_COM_INSERT;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);

		if($result){
				
			$qry_label = QRY_COMMON_IDENTITY;
			$sql = query($qry_label,$qry_params);
			$result = sqlsrv_query($wvcs_dbcon, $sql);
			
			if($result){
				$row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
				$com_seq = $row['seq'];
			}else{
				printJson($_LANG_TEXT['procfail'][$lang_code]."-2");
			}

		}else{
			printJson($_LANG_TEXT['procfail'][$lang_code]."-1");	
		}

	}//if($com_name !=""){

}else{

	$qry_params = array(
			"com_seq"=>$com_seq
			,"com_name"=> $com_name
			,"ceo_name"=>$ceo_name
			,"com_code1"=>$com_code1
			,"com_code2"=>$com_code2
			,"com_code3"=>$com_code3
			,"com_gubun1"=>$com_gubun1
			,"com_gubun2"=>$com_gubun2
			,"com_use_yn"=>$com_use_yn
		);

	$qry_label = QRY_USER_COM_UPDATE;
	$sql = query($qry_label,$qry_params);

	//printJson($sql);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

	if(!$result) printJson($_LANG_TEXT['procfail'][$lang_code]."-3");

}//if($com_seq==""){


if($result) {
	$status = true;
	$msg = $_LANG_TEXT['procsuccess'][$lang_code];
}else{
	$status = false;
	$msg = $_LANG_TEXT['procfail'][$lang_code];
}

printJson($msg,$data,$status,$result,$wvcs_dbcon);
?>