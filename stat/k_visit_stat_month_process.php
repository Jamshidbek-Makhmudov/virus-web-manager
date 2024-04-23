<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
$Model_Stat=new Model_Stat();

$year = $_POST['year'];
$scan_center_code = $_POST['scan_center_code'];
$searchopt = $_POST['searchopt'];
$searchkey = $_POST['searchkey'];
$file_import_visit_checked = $_REQUEST[file_import_visit_checked];	
$pass_visit_checked = $_REQUEST[pass_visit_checked];

$param = "";
if ($searchopt != "") $param .= ($param == "" ? "" : "&") . "searchopt=" . $searchopt;
if ($searchkey != "") $param .= ($param == "" ? "" : "&") . "searchkey=" . $searchkey;
if ($year != "") $param .= ($param == "" ? "" : "&") . "year=" . $year;
if ($scan_center_code != "") $param .= ($param == "" ? "" : "&") . "scan_center_code=" . $scan_center_code;
if ($file_import_visit_checked != "") $param .= ($param == "" ? "" : "&") . "file_import_visit_checked=" . $file_import_visit_checked;
if ($pass_visit_checked != "") $param .= ($param == "" ? "" : "&") . "pass_visit_checked=" . $pass_visit_checked;

if($year=="" ){
	printJson_ERROR('invalid_data');
}

$data = array(
	"data_value"=>array(),
	"data_label"=>array(),
	"link"=>array()
);

	//**data link
	$url = $_www_server."/user/access_control.php?enc=".ParamEnCoding($param);

	$search_sql = "";
	 if($searchkey != "" && $searchopt != "") {

		if ($searchopt == "mgr_name") {

		 $search_sql .= " and (v2.manager_name = '".aes_256_enc($searchkey)."' or v2.manager_name_en = '$searchkey')  ";
		} else if ($searchopt == "v_user_name") {

			$search_sql .= " and (v2.v_user_name = '".aes_256_enc($searchkey)."' or v2.v_user_name_en = '$searchkey')  ";
		} else if ($searchopt == "v_user_belong") {

			$search_sql .= " and v2.v_user_belong = N'$searchkey'  ";
		}  else if ($searchopt == "mgr_dept") {

			$search_sql .= " and v2.manager_dept = N'$searchkey'  ";
		} 
	}

	if($scan_center_code != ""){

		$search_sql .= " and c.scan_center_code = '{$scan_center_code}' ";	
	}
	
	//파일반입 출입건
	if($file_import_visit_checked =="Y"){
		$search_sql .= " and v3.label_value > '' ";	
	}
	
	//임시출입증발급 출입건
	if($pass_visit_checked =="Y"){
		$search_sql .= " and v3.pass_card_no > '' ";	
	}


	$Model_Stat->SHOW_DEBUG_SQL=false;
	$args=array("year"=>$year,"search_sql"=>$search_sql);

	$result = $Model_Stat->getVisitStatMonthly($args); 
	if($result){

		while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		
		$data_label[] = $row['label'];
		$data_value[] = $row['cnt'];

		}
	}

	$data['data_value'] = $data_value;
	$data['data_label'] = $data_label;
	$data['link']	=	'';		//차트를 눌렀을때 link 페이지 주소

printJson($msg='',$data,$status=true,$result,$wvcs_dbcon);
?>