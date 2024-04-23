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
$month = $_POST['month'];
$scan_center_code = $_POST['scan_center_code'];
$searchopt = $_POST['searchopt'];
$searchkey = $_POST['searchkey'];

$param = "";
if ($searchopt != "") $param .= ($param == "" ? "" : "&") . "searchopt=" . $searchopt;
if ($searchkey != "") $param .= ($param == "" ? "" : "&") . "searchkey=" . $searchkey;
if ($year != "") $param .= ($param == "" ? "" : "&") . "year=" . $year;
if ($month != "") $param .= ($param == "" ? "" : "&") . "month=" . $month;
if ($scan_center_code != "") $param .= ($param == "" ? "" : "&") . "scan_center_code=" . $scan_center_code;

if($year=="" || $month==""){
	printJson_ERROR('invalid_data');
}

$data = array(
	"data_value"=> array_fill(0, 31, "0"),
	"data_label"=>range(1, 31),
	"link"=>array()
);


	//**data link
	$url = $_www_server."/user/rental_details.php?enc=".ParamEnCoding($param);

	$ym = $year.$month;

	$search_sql = "";
	 if($searchkey != "" && $searchopt != "") {

		 if ($searchopt == "v_user_name") {

			$search_sql .= " and (rt.user_name = '".aes_256_enc($searchkey)."'  or rt.user_name_en = '$searchkey')  ";
		} else if ($searchopt == "v_user_belong") {

			$search_sql .= " and rt.user_belong = N'$searchkey'  ";
		}
	}

	if($scan_center_code != ""){

		$search_sql .= " and c.scan_center_code = '{$scan_center_code}' ";	
	}

	$search_sql2 = $search_sql;
	$search_sql2 .= " and rt.rent_date like '{$ym}%'";
	
	$Model_Stat->SHOW_DEBUG_SQL=false;
	
	//물품항목가져오기
	$args=array("search_sql"=>$search_sql2);
	$result = $Model_Stat->getRentalItem($args);

	if($result){

		while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){

			$item_name = $row[item_name];

			$data_label = array();
			$data_value = array();
			$data_link = array();
			
			//항목별 통계 가져오기
			$search_sql3 = $search_sql;
			$search_sql3 .= " and rt.item_name =N'{$item_name}' ";
			$args=array("ym"=>$ym,"search_sql"=>$search_sql3);
			$result2 = $Model_Stat->getRentalStatDaily($args); 
			if($result2){

				while($row = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC)){
				
				$data_label[] = setDateFormat($row['label'],"d");
				$data_value[] = $row['cnt'];
				}
			}

			$data['data_label'] = $data_label;
			$data['data_value'] = $data_value;
			$data['link'] =	$data_link;					//차트를 눌렀을때 link 페이지 주소


			$rent_data[] = array("label"=>$item_name, "dataset"=>$data);		

		}

	}else{
		$rent_data[] =array("label"=>trsLang('대여건수','rentalcount'), "dataset"=>$data);	
	}

printJson($msg='',$rent_data,$status=true,$result,$wvcs_dbcon);
?>