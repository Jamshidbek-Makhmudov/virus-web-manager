<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
 $Model_Dashboard=new Model_Dashboard();

$scan_center_code = $_POST['scan_center_code'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

if($start_date=="" || $end_date==""){
	printJson_ERROR('invalid_data');
}

$data = array(
"virus_data"=>array(),

);
$search_sql = "";
if($scan_center_code !=""){ 

  $search_sql .= " and v2.in_center_code = '{$scan_center_code}'  ";
}
	//점검현황 악성코드 발견
	$Model_Dashboard->SHOW_DEBUG_SQL=false;
 $args=array("start_date"=>$start_date,"end_date"=>$end_date,"search_sql"=>$search_sql);

	$result = $Model_Dashboard->getVisitPeriodVirusStat($args); 
	if($result){

	while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){

	$data['virus_data']['id'][] = $row['virus_name'];
	$data['virus_data']['label'][] = $row['virus_name'];
	$data['virus_data']['value'][] = $row['cnt']; 
	$data['virus_data']['link'][] = "";//$url."?enc=".ParamEnCoding($param."&check_result2=virus");

		}
	}


printJson($msg='',$data,$status=true,$result,$wvcs_dbcon);

?>

