<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$data = array(
	"os_data"=>array(),
	"maker_data"=>array()
);

//**data link
$url = $_www_server."/result/result_list.php";
$param = "src=chart&asset_type=NOTEBOOK&check_result1=last";


//**운영체제별 PC현황
$qry_params = array();
$qry_label = QRY_STAT_USER_PC_OS;
$sql = query($qry_label,$qry_params);

//printJson($sql);

$result = sqlsrv_query($wvcs_dbcon, $sql);

if($result){

	while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		
		$data['os_data']['id'][] = $row['os_name'];
		$data['os_data']['label'][] = $row['os_name'];
		$data['os_data']['value'][] = $row['cnt']; 
		$data['os_data']['link'][] = $url."?enc=".ParamEnCoding($param."&searchopt=OS&searchkey=".$row['os_name']);

	}

}


//**제조사별 PC현황
$qry_params = array();
$qry_label = QRY_STAT_USER_PC_MAKER;
$sql = query($qry_label,$qry_params);

//printJson($sql);

$result = sqlsrv_query($wvcs_dbcon, $sql);

if($result){

	while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		
		$data['maker_data']['id'][] = $row['v_manufacturer'];
		$data['maker_data']['label'][] = $row['v_manufacturer'];
		$data['maker_data']['value'][] = $row['cnt']; 
		$data['maker_data']['link'][] = $url."?enc=".ParamEnCoding($param."&searchopt=MANUFACTURER&searchkey=".$row['v_manufacturer']);

	}

}

printJson($msg='',$data,$status=true,$result,$wvcs_dbcon);
?>