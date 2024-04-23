<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$date = $_POST['date'];

if($date=="") $date = date("Y-m-d");

//$date = "2019-01-18";

$start_date = $date." 00:00:00.000";
$end_date = $date." 23:59:59.999";

$qry_params = array("start_date"=>$start_date,"end_date"=>$end_date);
$qry_label = QRY_STAT_PC_CHECK_STATUS;
$sql = query($qry_label,$qry_params);

//printJson($sql);

$result = sqlsrv_query($wvcs_dbcon, $sql);

if($result){

	while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		
		$pc_check_data = $row['check_cnt'];
		$pc_weak_data = $row['weak_cnt'];
		$pc_virus_data = $row['virus_cnt'];

	}
	
	$data['pc_check_data'] = $pc_check_data;
	$data['pc_weak_data'] = $pc_weak_data;
	$data['pc_virus_data'] = $pc_virus_data;

}else{

	printJson('Get Data Error');
}

$qry_params = array("start_date"=>$start_date,"end_date"=>$end_date);
$qry_label = QRY_STAT_STORAGE_CHECK_STATUS;
$sql = query($qry_label,$qry_params);

$result = sqlsrv_query($wvcs_dbcon, $sql);

if($result){

	while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		
		$storage_check_data = $row['check_cnt'];
		$storage_virus_data = $row['virus_cnt'];
	}
	
	$data['storage_check_data'] = $storage_check_data;
	$data['storage_virus_data'] = $storage_virus_data;

}else{

	printJson('Get Data Error');
}

printJson($msg='',$data,$status=true,$result,$dbcon);
?>