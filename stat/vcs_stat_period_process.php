<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$gubun = $_POST['gubun'];
$year = $_POST['year'];
$month = $_POST['month'];
$vcs_status = $_POST['vcs_status'];
$v_com_seq = $_POST['v_com_seq'];
$asset_type = $_POST['asset_type'];
$org_check_result = $_POST['org_check_result'];
$dept_check_result = $_POST['dept_check_result'];

$data = array(

	"date_check_data"=>array(),
	"date_weak_data"=>array(),
	"date_virus_data"=>array(),
	"date_unit"=>array(),
	"link"=>array(),

	"device_date_notebook_data"=>array(),
	"device_date_hdd_data"=>array(),
	"device_date_removable_data"=>array(),
	"device_date_etc_data"=>array(),
	"device_date_unit"=>array(),

	"org_data"=>array(),
	"dept_data"=>array(),
	"weak_data"=>array(),
	"virus_data"=>array()
);


if($month=="" || $month=="00"){
	$sdate = $year."-01-01 00:00:00.000";
	$edate = $year."-12-31 23:59:59.999";
}else{
	$sdate = $year."-".$month."-01 00:00:00.000";
	$edate = $year."-".$month."-".date('t', strtotime($sdate) )." 23:59:59.999";
}

//**data link
$url = $_www_server."/result/result_list.php";
$param = "src=chart&gubun=".$gubun."&asset_type=".$asset_type."&status=".$vcs_status."&check_result1=all";

if($v_com_seq != ""){
	$param .="&searchopt=COM_SEQ&searchkey=".$v_com_seq;
}

/**�Ϻ� ��Ȳ*/
if($gubun=="DAY"){

	if($year=="" || $month==""){
		printJson($msg='no data',$data,$status=true);
	}

	$cur_ym = date("Ym");
	
	if($cur_ym < $year.$month){

		printJson($msg='no data',$data,$status=true);

	}else if($cur_ym==$year.$month){

		//$last_day = date("d");
		$last_day = date('t',strtotime($year."-".$month."-01"));

	}else if($cur_ym > $year.$month){

		$last_day = date('t',strtotime($year."-".$month."-01"));
	}

	
	for($i = 1 ; $i <= $last_day ; $i++){
			
		$day = strlen($i)==1 ? "0".$i : $i;
		
		$date_unit[] = $day;
		$date = $year."-".$month."-".$day;
		$str_date .= ($str_date=="" ? "" : ",").$date;

	}

	$today = date("Y-m-d");

	if($asset_type !=""){
		$search_sql = " AND vcs.v_asset_type = '$asset_type' ";
	}

	if($vcs_status !=""){

		$search_sql .= " AND vcs.vcs_status = '$vcs_status' ";
		
	}//if($status !=""){

	if($v_com_seq != ""){

		$search_sql .= " AND vcs.v_user_seq IN (SELECT v_user_seq FROM tb_v_user WHERE v_com_seq = '{$v_com_seq}' ) ";
	}

	$qry_params = array("base_date"=>$today,"str_date"=>$str_date,"sdate"=>$sdate,"edate"=>$edate,"search_sql"=>$search_sql);
	$qry_label = QRY_STAT_PC_CHECK_DAY;
	$sql = query($qry_label,$qry_params);


	//printJson($sql);

	$result = sqlsrv_query($wvcs_dbcon, $sql);
	

/**���� ��Ȳ*/
}else if($gubun=="MONTH"){

	if($year==""){
		printJson($msg='no data',$data,$status=true);
	}

	$cur_year = date("Y");

	if($cur_year < $year){

		printJson($msg='no data',$data,$status=true);

	}else if($cur_year==$year){

		//$last_month = date("m");
		$last_month = "12";

	}else if($cur_year > $year){

		$last_month = "12";
	}

	for($i = 1 ; $i <= $last_month ; $i++){
			
		$m = strlen($i)==1 ? "0".$i : $i;

		$date_unit[] = $m;

		$ym = $year."-".$m;

		$str_ym .= ($str_ym=="" ? "" : ",").$ym;
	}

	$now_ym = date("Y-m");

	if($asset_type !=""){
		$search_sql = " AND vcs.v_asset_type = '$asset_type' ";
	}

	if($vcs_status !=""){

		$search_sql .= " AND vcs.vcs_status = '$vcs_status' ";
		
	}//if($status !=""){

	if($v_com_seq != ""){

		$search_sql .= " AND vcs.v_user_seq IN (SELECT v_user_seq FROM tb_v_user WHERE v_com_seq = '{$v_com_seq}' ) ";
	}

	$qry_params = array("base_month"=>$now_ym,"str_ym"=>$str_ym,"sdate"=>$sdate,"edate"=>$edate,"search_sql"=>$search_sql);
	$qry_label = QRY_STAT_PC_CHECK_MONTH;
	$sql = query($qry_label,$qry_params);

	//printJson($sql);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

}

if($result){

	while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){

		$date_check_data[] = $row['check_cnt'];
		$date_weak_data[] = $row['weak_cnt'];
		$date_virus_data[] = $row['virus_cnt'];

		//**chart data link param
		if($gubun=="ALL" || $gubun=="DAY"){
			
			$param .= "&checkdate1=".$row['label']."&checkdate2=".$row['label'];

		}else if($gubun=="MONTH"){

			$data_sdate = $row['label']."-01";
			$data_edate = $row['label']."-".date('t',strtotime($row['label']."-01"));

			$param .= "&checkdate1=".$data_sdate."&checkdate2=".$data_edate;
		}

		$link['date_check'][] = $url."?enc=".ParamEnCoding($param."&check_result2=");
		$link['date_weak'][] = $url."?enc=".ParamEnCoding($param."&check_result2=weak");
		$link['date_virus'][] = $url."?enc=".ParamEnCoding($param."&check_result2=virus");

	}
	
	$data['date_unit'] = $date_unit;
	$data['date_check_data'] = $date_check_data;
	$data['date_weak_data'] = $date_weak_data;
	$data['date_virus_data'] = $date_virus_data;
	$data['link'] = $link;

	//if($result) sqlsrv_free_stmt($result);  

}


/**��� �Ϻ� ��Ȳ*/
if($gubun=="DAY_DEVICE"){
	
	if($year=="" || $month==""){
		printJson($msg='no data',$data,$status=true);
	}

	$cur_ym = date("Ym");
	
	if($cur_ym < $year.$month){

		printJson($msg='no data',$data,$status=true);

	}else if($cur_ym==$year.$month){

		//$last_day = date("d");
		$last_day = date('t',strtotime($year."-".$month."-01"));

	}else if($cur_ym > $year.$month){

		$last_day = date('t',strtotime($year."-".$month."-01"));
	}

	
	for($i = 1 ; $i <= $last_day ; $i++){
			
		$day = strlen($i)==1 ? "0".$i : $i;
		
		$date_unit[] = $day;
		$date = $year."-".$month."-".$day;
		$str_date .= ($str_date=="" ? "" : ",").$date;

	}


	$today = date("Y-m-d");

	if($asset_type !=""){
		$search_sql = " AND vcs1.v_asset_type = '$asset_type' ";
	}

	if($vcs_status !=""){

		$search_sql .= " AND vcs1.vcs_status = '$vcs_status' ";
		
	}//if($status !=""){

	if($v_com_seq != ""){

		$search_sql .= " AND vcs1.v_user_seq IN (SELECT v_user_seq FROM tb_v_user WHERE v_com_seq = '{$v_com_seq}' ) ";
	}


	$qry_params = array("base_date"=>$today,"str_date"=>$str_date,"sdate"=>$sdate,"edate"=>$edate,"search_sql"=>$search_sql);
	$qry_label = QRY_STAT_DEVICE_CHECK_DAY;
	$sql = query($qry_label,$qry_params);
	
	//printJson($sql);

	$result = sqlsrv_query($wvcs_dbcon, $sql);
	

/**��� ���� ��Ȳ*/
}else if($gubun=="MONTH_DEVICE"){

	if($year==""){
		printJson($msg='no data',$data,$status=true);
	}

	$cur_year = date("Y");

	if($cur_year < $year){

		printJson($msg='no data',$data,$status=true);

	}else if($cur_year==$year){

		//$last_month = date("m");
		$last_month = "12";

	}else if($cur_year > $year){

		$last_month = "12";
	}

	for($i = 1 ; $i <= $last_month ; $i++){
			
		$m = strlen($i)==1 ? "0".$i : $i;

		$date_unit[] = $m;

		$ym = $year."-".$m;

		$str_ym .= ($str_ym=="" ? "" : ",").$ym;
	}

	$now_ym = date("Y-m");

	if($asset_type !=""){
		$search_sql = " AND vcs1.v_asset_type = '$asset_type' ";
	}

	if($vcs_status !=""){

		$search_sql .= " AND vcs1.vcs_status = '$vcs_status' ";
		
	}//if($status !=""){

	if($v_com_seq != ""){

		$search_sql .= " AND vcs1.v_user_seq IN (SELECT v_user_seq FROM tb_v_user WHERE v_com_seq = '{$v_com_seq}' ) ";
	}

	$qry_params = array("base_month"=>$now_ym,"str_ym"=>$str_ym,"sdate"=>$sdate,"edate"=>$edate,"search_sql"=>$search_sql);
	$qry_label = QRY_STAT_DEVICE_CHECK_MONTH;
	$sql = query($qry_label,$qry_params);

	//printJson($sql);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

}

if($result){

	while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){

		$date_notebook_data[] = $row['notebook_cnt'];
		$date_hdd_data[] = $row['hdd_cnt'];
		$date_removable_data[] = $row['removable_cnt'];
		$date_etc_data[] = $row['etc_cnt'];

	}
	
	$data['device_date_unit'] = $date_unit;
	$data['device_date_notebook_data'] = $date_notebook_data;
	$data['device_date_hdd_data'] = $date_hdd_data;
	$data['device_date_removable_data'] = $date_removable_data;
	$data['device_date_etc_data'] = $date_etc_data;

	//if($result) sqlsrv_free_stmt($result);  

}


//**����� ������Ȳ
if($gubun=="ALL" || $gubun=="ORG"){

	$search_sql = " AND year(wvcs_dt) =".$year;

	if($month != "00"){

		$search_sql .= " AND month(wvcs_dt) =".$month;
	}

	if($asset_type !=""){
		$search_sql .= " AND vcs.v_asset_type = '$asset_type' ";
	}

	if($vcs_status !=""){

		$search_sql .= " AND vcs.vcs_status = '$vcs_status' ";
		
	}//if($status !=""){

	if($v_com_seq != ""){

		$search_sql .= " AND vcs.v_user_seq IN (SELECT v_user_seq FROM tb_v_user WHERE v_com_seq = '{$v_com_seq}' ) ";
	}

	$check_result2 = "";

	if($org_check_result=="WEAK"){

		$check_result2 = "weak";

		$search_sql .= " AND exists (SELECT TOP 1 * FROM tb_v_wvcs_weakness WHERE vcs.v_wvcs_seq = v_wvcs_seq) ";

	}else if($org_check_result=="VIRUS"){

		$check_result2 = "virus";

		$search_sql .= " AND exists (
								SELECT TOP 1 *
								FROM tb_v_wvcs_vaccine vcc 
									INNER JOIN tb_v_wvcs_vaccine_detail vccd ON vcc.vaccine_seq = vccd.vaccine_seq 
								WHERE vcs.v_wvcs_seq = v_wvcs_seq ) ";
	}
	
	//**chart data_link param
	if($month=="00"){
		
		$data_sdate = $year."-01-01";
		$data_edate = $year."-12-31";

	}else{
	
		$data_sdate = $year."-".$month."-01";
		$data_edate = $year."-".$month."-".date('t',strtotime($year."-".$month."-01"));
	}

	$param .= "&checkdate1=".$data_sdate."&checkdate2=".$data_edate;

	$qry_params = array("search_sql"=>$search_sql);
	$qry_label = QRY_STAT_PC_CHECK_ORG;
	$sql = query($qry_label,$qry_params);

	//printJson($sql);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

	

	if($result){

		while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
			
			$data['org_data']['id'][] = $row['org_name'];
			$data['org_data']['label'][] = $row['org_name'];
			$data['org_data']['value'][] = $row['cnt']; 
			$data['org_data']['link'][] = $url."?enc=".ParamEnCoding($param."&check_result2=".$check_result2."&searchopt=ORG_NAME&searchkey=".$row['org_name']);

		}

	}

}

//printJson($sql);

//**�μ��� ������Ȳ
if($gubun=="ALL" || $gubun=="DEPT"){

	$search_sql = " AND year(wvcs_dt) =".$year;

	if($month != "00"){

		$search_sql .= " AND month(wvcs_dt) =".$month;
	}

	if($asset_type !=""){
		$search_sql .= " AND vcs.v_asset_type = '$asset_type' ";
	}

	if($vcs_status !=""){

		$search_sql .= " AND vcs.vcs_status = '$vcs_status' ";
		
	}//if($status !=""){

	if($v_com_seq != ""){

		$search_sql .= " AND vcs.v_user_seq IN (SELECT v_user_seq FROM tb_v_user WHERE v_com_seq = '{$v_com_seq}' ) ";
	}

	$check_result2 = "";

	if($dept_check_result=="WEAK"){

		$check_result2 = "weak";

		$search_sql .= " AND exists (SELECT TOP 1 * FROM tb_v_wvcs_weakness WHERE vcs.v_wvcs_seq = v_wvcs_seq) ";

	}else if($dept_check_result=="VIRUS"){

		$check_result2 = "virus";

		$search_sql .= " AND exists (
								SELECT TOP 1 *
								FROM tb_v_wvcs_vaccine vcc 
									INNER JOIN tb_v_wvcs_vaccine_detail vccd ON vcc.vaccine_seq = vccd.vaccine_seq 
								WHERE vcs.v_wvcs_seq = v_wvcs_seq ) ";
	}

	//**chart data_link param
	if($month=="00"){
		
		$data_sdate = $year."-01-01";
		$data_edate = $year."-12-31";

	}else{
	
		$data_sdate = $year."-".$month."-01";
		$data_edate = $year."-".$month."-".date('t',strtotime($year."-".$month."-01"));
	}

	$param .= "&checkdate1=".$data_sdate."&checkdate2=".$data_edate;

	$qry_params = array("search_sql"=>$search_sql);
	$qry_label = QRY_STAT_PC_CHECK_DEPT;
	$sql = query($qry_label,$qry_params);

	//echo $sql;

	$result = sqlsrv_query($wvcs_dbcon, $sql);

	if($result){

		while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
			
			$data['dept_data']['id'][] = $row['mngr_department'];
			$data['dept_data']['label'][] = $row['mngr_department'];
			$data['dept_data']['value'][] = $row['cnt']; 
			$data['dept_data']['link'][] = $url."?enc=".ParamEnCoding($param."&check_result2=".$check_result2."&searchopt=MANAGER_DEPT&searchkey=".$row['mngr_department']);

		}

	}

}

//printJson($sql);

//**���� �����Ȳ
if($gubun=="ALL" || $gubun=="WEAK"){

	$search_sql = " AND year(wvcs_dt) =".$year;

	if($asset_type !=""){
		$search_sql .= " AND vcs.v_asset_type = '$asset_type' ";
	}

	if($vcs_status !=""){

		$search_sql .= " AND vcs.vcs_status = '$vcs_status' ";
		
	}//if($status !=""){

	if($v_com_seq != ""){

		$search_sql .= " AND vcs.v_user_seq IN (SELECT v_user_seq FROM tb_v_user WHERE v_com_seq = '{$v_com_seq}' ) ";
	}

	if($month != "00"){

		$search_sql .= " AND month(wvcs_dt) =".$month;
	}

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

}



//**�Ǽ��ڵ� �߰���Ȳ
if($gubun=="ALL" || $gubun=="VIRUS"){

	$search_sql = " AND year(wvcs_dt) =".$year;

	if($asset_type !=""){
		$search_sql .= " AND vcs.v_asset_type = '$asset_type' ";
	}

	if($vcs_status !=""){

		$search_sql .= " AND vcs.vcs_status = '$vcs_status' ";
		
	}//if($status !=""){

	if($v_com_seq != ""){

		$search_sql .= " AND vcs.v_user_seq IN (SELECT v_user_seq FROM tb_v_user WHERE v_com_seq = '{$v_com_seq}' ) ";
	}

	if($month != "00"){

		$search_sql .= " AND month(wvcs_dt) =".$month;
	}

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

}

//printJson($sql);

printJson($msg='',$data,$status=true,$result,$wvcs_dbcon);
?>