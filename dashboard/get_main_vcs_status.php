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
	"data_value"=>array(),
	"data_label"=>array(),
	"link"=>array(),

	"data_file_value"=>array(),
	"data_file_label"=>array(),
	"file_link"=>array(),
);
$search_sql = "";
if($scan_center_code !=""){ 

  $search_sql .= " and v2.in_center_code = '{$scan_center_code}'  ";
}
//**data link
$visit_url = $_www_server."/user/access_control.php";
$file_url = $_www_server."/user/access_control_file.php";

#기간 출입현황 - 방문자출입
	$Model_Dashboard->SHOW_DEBUG_SQL=false;
	$args=array("start_date"=>$start_date,"end_date"=>$end_date,"search_sql"=>$search_sql);

	$result = $Model_Dashboard->getVisitPeriodStat($args); 
	if($result){

		while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		
			$data_label[] = setDateFormat($row['label'],"m/d");
			$data_value[] = $row['cnt'];

		$data_date_label = setDateFormat($row['label'],"Y-m-d");
		$param = "start_date=".$data_date_label."&end_date=".$data_date_label."&scan_center_code=".$scan_center_code."&visit_div=OUT_VISIT";

		$link['visit_user'][] = $visit_url."?enc=".ParamEnCoding($param);

		}
	}

	$data['data_label'] = $data_label;
	$data['data_value'] = $data_value;
	$data['link']	=	$link;		//차트를 눌렀을때 link 페이지 주소
	
	#기간출입현황 - 파일반입
	$Model_Dashboard->SHOW_DEBUG_SQL=false;
	$args=array("start_date"=>$start_date,"end_date"=>$end_date,"search_sql"=>$search_sql);
	
	$result = $Model_Dashboard->getVisitPeriodStat_File($args); 
	if($result){
		
		while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
			
			$data_file_label[] = setDateFormat($row['label'],"m/d");
			$data_file_value[] = $row['cnt'];

			$data_date_file_label = setDateFormat($row['label'],"Y-m-d");
		$param = "start_date=".$data_date_file_label."&end_date=".$data_date_file_label."&scan_center_code=".$scan_center_code;
			$link['file_in'][] = $file_url."?enc=".ParamEnCoding($param);
			
		}
	}
	
	$data['data_file_label'] = $data_file_label;
	$data['data_file_value'] = $data_file_value;
	$data['link']	=	$link;		//차트를 눌렀을때 link 페이지 주소


printJson($msg='',$data,$status=true,$result,$wvcs_dbcon);
?>