<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$user_id = $_POST['user_id'];

$qry_params = array("user_id"=>$user_id);
$qry_label = QRY_USER_PCID;
$sql = query($qry_label,$qry_params);

$result = sqlsrv_query($wvcs_dbcon, $sql);

if($result){
	$cnt = 0;
	while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

		$data[$cnt] = array(
				"pc_seq"=>$row['pc_seq'],
				"pc_id"=>$row['pc_id'],
				"pc_model"=>$row['pc_model'],
				"pc_manufacturer"=>$row['pc_manufacturer']
			);
		$cnt++;
	}
}

if(sizeof($data) > 0){
	$status = true;
	$msg = "";
}else{
	$status = false;
	$msg = $_LANG_TEXT["nouserpc"][$lang_code];
}

printJson($msg,$data,$status,$result,$wvcs_dbcon);
?>