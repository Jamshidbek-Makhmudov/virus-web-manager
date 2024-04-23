<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$v_user_seq = $_POST['v_user_seq'];

$data = array(
	"weak_data"=>array(),
	"virus_data"=>array()
);

//**보안 취약현황
$search_sql = " AND vcs.v_user_seq = '{$v_user_seq}'";

$qry_params = array("search_sql"=>$search_sql);
$qry_label = QRY_STAT_PC_CHECK_WEAKNESS;
$sql = query($qry_label,$qry_params);

//printJson($sql);

$result = sqlsrv_query($wvcs_dbcon, $sql);

if($result){

	while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		
		$data['weak_data']['id'][] = $row['weakness_name'];
		$data['weak_data']['label'][] = $row['weakness_name'];
		$data['weak_data']['value'][] = $row['cnt']; 
		$data['weak_data']['link'][] = "";//$url."?enc=".ParamEnCoding($param."&check_result2=weak");

	}

}



//**악성코드 발견현황
$search_sql = " AND vcs.v_user_seq = '{$v_user_seq}'";

$qry_params = array("search_sql"=>$search_sql);
$qry_label = QRY_STAT_PC_CHECK_VIRUS;
$sql = query($qry_label,$qry_params);

//echo $sql;

$result = sqlsrv_query($wvcs_dbcon, $sql);

if($result){

	while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		
		$data['virus_data']['id'][] = $row['virus_name'];
		$data['virus_data']['label'][] = $row['virus_name'];
		$data['virus_data']['value'][] = $row['cnt']; 
		$data['virus_data']['link'][] = "";//$url."?enc=".ParamEnCoding($param."&check_result2=virus");

	}

}

//printJson($sql);

printJson($msg='',$data,$status=true,$result,$wvcs_dbcon);
?>