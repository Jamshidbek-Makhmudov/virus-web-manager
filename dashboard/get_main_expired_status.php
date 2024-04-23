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
"not_return_stat_data"=>array(),

);

$search_sql = "";
if($scan_center_code !=""){ 

  $search_sql .= " and v2.in_center_code = '{$scan_center_code}'  ";
}
//**data link

$file_url = $_www_server."/user/access_control_file.php";
$pass_url = $_www_server."/user/access_control_pass.php";
$goods_url = $_www_server."/user/access_control_goods.php";
$rental_url = $_www_server."/user/rental_details.php";
$uncovered = true;

$param = "";
if ($uncovered) $param .= ($param == "" ? "" : "&") . "uncovered=" . $uncovered."&start_date=2020-01-01";
if ($scan_center_code != "") $param .= ($param == "" ? "" : "&") . "scan_center_code=" . $scan_center_code;
	#기간출입현황 - 파일반입
	 $Model_Dashboard->SHOW_DEBUG_SQL=false;
	 $args=array("search_sql"=>$search_sql);

	$result = $Model_Dashboard->getNotReturnStat($args); 
	if($result){

		while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		
	
	$data['not_return_stat_data']['id'][] = trsLang('임시 출입증','pass_count');
	$data['not_return_stat_data']['label'][] = trsLang('임시 출입증','pass_count');
	$data['not_return_stat_data']['value'][] = $row['not_return_pass_count'];
	$data['not_return_stat_data']['link'][] = $pass_url."?enc=".ParamEnCoding($param);
	

	$data['not_return_stat_data']['id'][] = trsLang('보안 USB','usb_count');
	$data['not_return_stat_data']['label'][] = trsLang('보안 USB','usb_count');
	// $data['not_return_stat_data']['value'][] = $row[' '];
	$data['not_return_stat_data']['value'][] = $row['not_return_usb_count'];
	$data['not_return_stat_data']['link'][] = $file_url."?enc=".ParamEnCoding($param);; 

	$data['not_return_stat_data']['id'][] = trsLang('자산 미반출','goods_count');
	$data['not_return_stat_data']['label'][] = trsLang('자산 미반출','goods_count');
	$data['not_return_stat_data']['value'][] = $row['not_export_goods_count']; 
	$data['not_return_stat_data']['link'][] = $goods_url."?enc=".ParamEnCoding($param);

		}
	}

#대여물품 - 미반납 현황 
if($scan_center_code !=""){ 

	$search_sql_return .= " and rt.rent_center_code = '{$scan_center_code}'  ";
}
		$Model_Dashboard->SHOW_DEBUG_SQL=false;
	 $args=array("search_sql"=>$search_sql_return);


	$result = $Model_Dashboard->getNotReturnStats($args); 
	if($result){

		while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		

	$data['not_return_stat_data']['id'][] = trsLang('물품 미회수','rent_count');
	$data['not_return_stat_data']['label'][] = trsLang('물품 미회수','rent_count');
	$data['not_return_stat_data']['value'][] = $row['not_return_rent_cnt'];
	$data['not_return_stat_data']['link'][] = $rental_url."?enc=".ParamEnCoding($param);


		}
	}


printJson($msg='',$data,$status=true,$result,$wvcs_dbcon);

?>
