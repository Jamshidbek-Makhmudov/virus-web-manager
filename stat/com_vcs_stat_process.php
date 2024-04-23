<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$v_com_seq = $_POST['v_com_seq'];

$search_sql = " AND vcs1.v_user_seq in (SELECT v_user_seq FROM tb_v_user WHERE v_com_seq = '{$v_com_seq}') ";

//*점검현황
$qry_params = array("search_sql"=>$search_sql);
$qry_label = QRY_STAT_USER_VCS_STATUS;
$sql = query($qry_label,$qry_params);

//printJson($sql);

$result = sqlsrv_query($wvcs_dbcon, $sql);

if($result){

	while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		
		$vcs_cnt = $row['vcs_cnt'];
		$weak_cnt = $row['weak_cnt'];
		$virus_cnt = $row['virus_cnt'];

	}
	
	$data['vcs_cnt'] = $vcs_cnt;
	$data['weak_cnt'] = $weak_cnt;
	$data['virus_cnt'] = $virus_cnt;

}else{

	printJson('Get Data Error1');
}

//*장비별 점검현황
$device_gubun = array("NOTEBOOK"=>'notebook',"HDD"=>'hdd',"Removable"=>'removable',"ETC"=>'etc');

$data['notebook'] = array("device_cnt"=>0,"vcs_cnt"=>0);
$data['hdd'] = array("device_cnt"=>0,"vcs_cnt"=>0);
$data['removable'] = array("device_cnt"=>0,"vcs_cnt"=>0);
//$data['cddvd'] = array("device_cnt"=>0,"vcs_cnt"=>0);
$data['etc'] = array("device_cnt"=>0,"vcs_cnt"=>0);

//점검장비 수량
$qry_params = array("search_sql"=>$search_sql);
$qry_label = QRY_STAT_USER_DEVICE;
$sql = query($qry_label,$qry_params);

$result = sqlsrv_query($wvcs_dbcon, $sql);

//printJson($sql);

if($result){

	while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		
		$device = $device_gubun[$row['device_gubun']];
		$device_cnt = $row['device_cnt'];

		$data[$device]['device_cnt'] = $device_cnt;
	}

}else{

	printJson('Get Data Error2');
}

//*장비별 점검현황
$qry_params = array("search_sql"=>$search_sql);
$qry_label = QRY_STAT_USER_DEVICE_VCS_STATUS;
$sql = query($qry_label,$qry_params);

$result = sqlsrv_query($wvcs_dbcon, $sql);

//printJson($sql);

if($result){

	while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		
		$device = $device_gubun[$row['device_gubun']];
		$vcs_cnt = $row['vcs_cnt'];

		$data[$device]['vcs_cnt'] = $vcs_cnt;
	}

}else{

	printJson('Get Data Error3');
}

printJson($msg='',$data,$status=true,$result,$dbcon);
?>