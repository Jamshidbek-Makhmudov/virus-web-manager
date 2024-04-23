<?php
$page_name = "report";

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";


$filename = "Report_excel[".date('Ymd')."].xls";

header( "Content-type: application/vnd.ms-excel; charset=utf-8" ); 
header( "Content-Disposition: attachment; filename=$filename" ); 
header( "Content-Description: PHP5 Generated Data" );

$now_year = date("Y");
$now_month = date("m");
$today = date("Y-m-d");

$title = $_REQUEST[title];
$reporter = $_REQUEST[reporter];
$reportdate = $_REQUEST[reportdate];
$printdate1 = $_REQUEST[printdate1];
$printdate2 = $_REQUEST[printdate2];
$vcs_status = $_REQUEST[vcs_status];
$stat_unit = $_REQUEST[stat_unit];
$options = $_REQUEST[options];

if(empty($stat_unit)) $stat_unit = array();
if(empty($options)) $options = array();

$daily_vcs_status_year = $_REQUEST[daily_vcs_status_year];
$daily_vcs_status_month = $_REQUEST[daily_vcs_status_month];

$monthly_vcs_status_year = $_REQUEST[monthly_vcs_status_year];

$daily_dvcs_status_year = $_REQUEST[daily_dvcs_status_year];
$daily_dvcs_status_month = $_REQUEST[daily_dvcs_status_month];

$monthly_dvcs_status_year = $_REQUEST[monthly_dvcs_status_year];

$weak_status_year = $_REQUEST[weak_status_year];
$weak_status_month = $_REQUEST[weak_status_month];

$virus_status_year = $_REQUEST[virus_status_year];
$virus_status_month = $_REQUEST[virus_status_month];

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');

$checkdate1 = $printdate1;
$checkdate2 = $printdate2;

if($daily_vcs_status_year=="") $daily_vcs_status_year = $now_year;
if($daily_vcs_status_month=="") $daily_vcs_status_month = $now_month;
if($monthly_vcs_status_year=="") $monthly_vcs_status_year = $now_year;
if($daily_dvcs_status_year=="") $daily_dvcs_status_year = $now_year;
if($daily_dvcs_status_month=="") $daily_dvcs_status_month = $now_month;
if($monthly_dvcs_status_year=="") $monthly_dvcs_status_year = $now_year;
if($weak_status_year=="") $weak_status_year = $now_year;
if($weak_status_month=="") $weak_status_month = $now_month;
if($virus_status_year=="") $virus_status_year = $now_year;
if($virus_status_month=="") $virus_status_month = $now_month;

?>
<html>
<head>
	<title><?=$title?></title>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
</head>
<body topmargin='0' leftmargin='0'>

<?
	//**월별 점검현황
	if(in_array("VCS_STAT",$options) && in_array("M",$stat_unit)){

		$search_sql = "";

		$year = $monthly_vcs_status_year;

		$sdate = $year."-01-01 00:00:00.000";
		$edate = $year."-12-31 23:59:59.999";

		echo "<div><b>".$_LANG_TEXT["monthlyvcsstatustext"][$lang_code]."(".$year.")</b></div>";


		if($year==""){
			echo "No Data</p>";
		}

		$cur_year = date("Y");

		if($cur_year < $year){

			echo "No Data</p>";

		}else if($cur_year==$year){

			$last_month = "12";

		}else if($cur_year > $year){

			$last_month = "12";
		}
		
		$date_unit = "";
		$str_ym = "";
		for($i = 1 ; $i <= $last_month ; $i++){
				
			$m = strlen($i)==1 ? "0".$i : $i;

			$date_unit[] = $m;

			$ym = $year."-".$m;

			$str_ym .= ($str_ym=="" ? "" : ",").$ym;
		}

		$now_ym = date("Y-m");

		if($vcs_status !=""){

			$search_sql .= " AND vcs.vcs_status = '$vcs_status' ";
			
		}//if($vcs_status !=""){

		$qry_params = array("base_month"=>$now_ym,"str_ym"=>$str_ym,"sdate"=>$sdate,"edate"=>$edate,"search_sql"=>$search_sql);
		$qry_label = QRY_STAT_PC_CHECK_MONTH;
		$sql = query($qry_label,$qry_params);
		
		//echo $sql;

		$result = sqlsrv_query($wvcs_dbcon, $sql);
		
		if($result){

			while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
				
				$month = substr($row['label'],5,2);
				$mvcs["date"][] = $_CODE['month'][$month];
				$mvcs["check"][] = $row['check_cnt'];
				$mvcs["weak"][] = $row['weak_cnt'];
				$mvcs["virus"][] = $row['virus_cnt'];
			}
		}

		$_item = array("date" => $_LANG_TEXT["gubuntext"][$lang_code]
					,"check" => $_LANG_TEXT["checktext"][$lang_code]
					,"weak" => $_LANG_TEXT["weaknessdetectiontext"][$lang_code]
					,"virus" => $_LANG_TEXT["virusdetectiontext"][$lang_code]
				);

		echo "<table border='1'>";
				foreach($_item as $key => $value){
					
					$_name = $value;
					$_arr = $mvcs[$key];
					
					echo "<tr>";
					echo "<td  colspan='2'>".$_name."</td>";

					for($i = 0 ; $i < sizeof($_arr) ; $i++){
						
						$_val = $key=="date" ? $_arr[$i] : number_format($_arr[$i]);
						echo "<td>".$_val."</td>";
					}

					echo "</tr>";
				}
		echo "</table>";
		echo "</p>";

	}
?>

<?
	//**일별 점검현황
	if(in_array("VCS_STAT",$options) && in_array("D",$stat_unit)){	

		$search_sql = "";
		
		$cur_ym = date("Ym");

		$year = $daily_vcs_status_year;
		$month = $daily_vcs_status_month;

		echo "<div><b>".$_LANG_TEXT["dailyvcsstatustext"][$lang_code]."(".$year."-".$month.")</b></div>";

		$sdate = $year."-".$month."-01 00:00:00.000";
		$edate = $year."-".$month."-".date('t', strtotime($sdate) )." 23:59:59.999";
	
		if($cur_ym < $year.$month){

			echo "No Data</p>";

		}else if($cur_ym==$year.$month){

			$last_day = date('t',strtotime($year."-".$month."-01"));

		}else if($cur_ym > $year.$month){

			$last_day = date('t',strtotime($year."-".$month."-01"));
		}

		$date_unit = "";
		$str_date = "";
		for($i = 1 ; $i <= $last_day ; $i++){
				
			$day = strlen($i)==1 ? "0".$i : $i;
			
			$date_unit[] = $day;
			$date = $year."-".$month."-".$day;
			$str_date .= ($str_date=="" ? "" : ",").$date;

		}


		if($vcs_status !=""){

			$search_sql .= " AND vcs.vcs_status = '$vcs_status' ";
			
		}//if($vcs_status !=""){

		$qry_params = array("base_date"=>$today,"str_date"=>$str_date,"sdate"=>$sdate,"edate"=>$edate,"search_sql"=>$search_sql);
		$qry_label = QRY_STAT_PC_CHECK_DAY;
		$sql = query($qry_label,$qry_params);

		//echo $sql;
		
		$result = sqlsrv_query($wvcs_dbcon, $sql);
		
		if($result){

			while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
				
				$day = substr($row['label'],8,2);
				$dvcs["date"][] = $day;
				$dvcs["check"][] = $row['check_cnt'];
				$dvcs["weak"][] = $row['weak_cnt'];
				$dvcs["virus"][] = $row['virus_cnt'];
			}
		}

		$_item = array("date" => $_LANG_TEXT["gubuntext"][$lang_code]
					,"check" => $_LANG_TEXT["checktext"][$lang_code]
					,"weak" => $_LANG_TEXT["weaknessdetectiontext"][$lang_code]
					,"virus" => $_LANG_TEXT["virusdetectiontext"][$lang_code]
				);

		echo "<table border='1'>";
				foreach($_item as $key => $value){
					
					$_name = $value;
					$_arr = $dvcs[$key];
					
					echo "<tr>";
					echo "<td colspan='2'>".$_name."</td>";

					for($i = 0 ; $i < sizeof($_arr) ; $i++){

						$_val = $key=="date" ? $_arr[$i] : number_format($_arr[$i]);
						echo "<td>".$_val."</td>";
					}

					echo "</tr>";
				}
		echo "</table>";
		echo "</p>";
		
	}
?>

<?
	//**월별 장비 점검현황
	if(in_array("DVCS_STAT",$options) && in_array("M",$stat_unit)){	

		$search_sql = "";
		
		$year = $monthly_dvcs_status_year;

		$sdate = $year."-01-01 00:00:00.000";
		$edate = $year."-12-31 23:59:59.999";

		echo "<div><b>".$_LANG_TEXT["monthlydevicevcsstatustext"][$lang_code]."(".$year.")</b></div>";


		if($year==""){
			echo "No Data</p>";
		}

		$cur_year = date("Y");

		if($cur_year < $year){

			echo "No Data</p>";

		}else if($cur_year==$year){

			$last_month = "12";

		}else if($cur_year > $year){

			$last_month = "12";
		}
		
		$date_unit = "";
		$str_ym = "";
		for($i = 1 ; $i <= $last_month ; $i++){
				
			$m = strlen($i)==1 ? "0".$i : $i;

			$date_unit[] = $m;

			$ym = $year."-".$m;

			$str_ym .= ($str_ym=="" ? "" : ",").$ym;
		}

		$now_ym = date("Y-m");

		if($vcs_status !=""){

			$search_sql .= " AND vcs1.vcs_status = '$vcs_status' ";
			
		}//if($vcs_status !=""){

		$qry_params = array("base_month"=>$now_ym,"str_ym"=>$str_ym,"sdate"=>$sdate,"edate"=>$edate,"search_sql"=>$search_sql);
		$qry_label = QRY_STAT_DEVICE_CHECK_MONTH;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);

		//echo $sql;
		
		if($result){
			while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
				
				$month = substr($row['label'],5,2);
				$mdvcs["date"][] = $_CODE['month'][$month];
				$mdvcs["notebook"][] = $row['notebook_cnt'];
				$mdvcs["hdd"][] = $row['hdd_cnt'];
				$mdvcs["removable"][] = $row['removable_cnt'];
				$mdvcs["etc"][] = $row['etc_cnt'];
			}
		}

		$_item = array("date" => $_LANG_TEXT["gubuntext"][$lang_code]
					,"notebook" => $_LANG_TEXT["laptoptext"][$lang_code]
					,"hdd" => $_CODE['storage_device_type']['HDD']
					,"removable" => $_CODE['storage_device_type']['Removable']
					,"etc" => $_CODE['storage_device_type']['DEVICE_ETC']
				);

		echo "<table border='1'>";
				foreach($_item as $key => $value){
					
					$_name = $value;
					$_arr = $mdvcs[$key];
					
					echo "<tr>";
					echo "<td  colspan='2'>".$_name."</td>";

					for($i = 0 ; $i < sizeof($_arr) ; $i++){
						
						$_val = $key=="date" ? $_arr[$i] : number_format($_arr[$i]);
						echo "<td>".$_val."</td>";
					}

					echo "</tr>";
				}
		echo "</table>";
		echo "</p>";

	}
?>

<?
	//**일별 장비 점검현황
	if(in_array("DVCS_STAT",$options) && in_array("D",$stat_unit)){	

		$search_sql = "";

		$cur_ym = date("Ym");

		$year = $daily_dvcs_status_year;
		$month = $daily_dvcs_status_month;

		echo "<div><b>".$_LANG_TEXT["dailydevicevcsstatustext"][$lang_code]."(".$year."-".$month.")</b></div>";

		$sdate = $year."-".$month."-01 00:00:00.000";
		$edate = $year."-".$month."-".date('t', strtotime($sdate) )." 23:59:59.999";

		if($year=="" || $month==""){
			echo "No Data</p>";
		}

		if($cur_ym < $year.$month){

			echo "No Data</p>";

		}else if($cur_ym==$year.$month){

			$last_day = date('t',strtotime($year."-".$month."-01"));

		}else if($cur_ym > $year.$month){

			$last_day = date('t',strtotime($year."-".$month."-01"));
		}

		$date_unit = "";
		$str_date = "";
		for($i = 1 ; $i <= $last_day ; $i++){
				
			$day = strlen($i)==1 ? "0".$i : $i;
			
			$date_unit[] = $day;
			$date = $year."-".$month."-".$day;
			$str_date .= ($str_date=="" ? "" : ",").$date;

		}


		if($vcs_status !=""){

			$search_sql .= " AND vcs1.vcs_status = '$vcs_status' ";
			
		}//if($vcs_status !=""){

		$qry_params = array("base_date"=>$today,"str_date"=>$str_date,"sdate"=>$sdate,"edate"=>$edate,"search_sql"=>$search_sql);
		$qry_label = QRY_STAT_DEVICE_CHECK_DAY;
		$sql = query($qry_label,$qry_params);
		

		$result = sqlsrv_query($wvcs_dbcon, $sql);

		//echo $sql;
		
		if($result){
			while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
				
				$day = substr($row['label'],8,2);
				$ddvcs["date"][] = $day;
				$ddvcs["notebook"][] = $row['notebook_cnt'];
				$ddvcs["hdd"][] = $row['hdd_cnt'];
				$ddvcs["removable"][] = $row['removable_cnt'];
				$ddvcs["etc"][] = $row['etc_cnt'];
			}
		}

		$_item = array("date" => $_LANG_TEXT["gubuntext"][$lang_code]
					,"notebook" => $_LANG_TEXT["laptoptext"][$lang_code]
					,"hdd" => $_CODE['storage_device_type']['HDD']
					,"removable" => $_CODE['storage_device_type']['Removable']
					,"etc" => $_CODE['storage_device_type']['DEVICE_ETC']
				);

		echo "<table border='1'>";
				foreach($_item as $key => $value){
					
					$_name = $value;
					$_arr = $ddvcs[$key];
					
					echo "<tr>";
					echo "<td  colspan='2'>".$_name."</td>";

					for($i = 0 ; $i < sizeof($_arr) ; $i++){

						$_val = $key=="date" ? $_arr[$i] : number_format($_arr[$i]);
						echo "<td>".$_val."</td>";
					}

					echo "</tr>";
				}
		echo "</table>";
		echo "</p>";
		
	}
?>


<?
	//**취약점/악성코드 현황
	if(in_array("WV_STAT",$options)){

		$search_sql = "";
		
		//*보안 취약 현황
		$year = $weak_status_year;
		$month = $weak_status_month;

		echo "<div><b>".$_LANG_TEXT["weaknesstext"][$lang_code]."(".$year.($month=="00"? "" : "-".$month).")</b></div>";

		$search_sql = " AND year(wvcs_dt) =".$year;

		if($vcs_status !=""){

			$search_sql .= " AND vcs.vcs_status = '$vcs_status' ";
			
		}//if($vcs_status !=""){

		if($month != "00"){

			$search_sql .= " AND month(wvcs_dt) =".$month;
		}

		$qry_params = array("search_sql"=>$search_sql);
		$qry_label = QRY_STAT_PC_CHECK_WEAKNESS;
		$sql = query($qry_label,$qry_params);

		//printJson($sql);

		$result = sqlsrv_query($wvcs_dbcon, $sql);

		//echo $sql;

		echo "<table border='1'>
				<tr>
					<td colspan='4'>".$_LANG_TEXT["weaknesstext"][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT["detectcounttext"][$lang_code]."</td>
				</tr>";

		if($result){

			while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){

				echo "<tr>";
				echo	"<td colspan='4'>".$row['weakness_name']."</td>";
				echo	"<td colspan='2'>".number_format($row['cnt'])."</td>";
				echo "</tr>";
			}

		}

		echo "</table>";
		echo "</p>";


		//*악성코드 현황
		$year = $weak_status_year;
		$month = $weak_status_month;

		echo "<div><b>".$_LANG_TEXT["virustext"][$lang_code]."(".$year.($month=="00"? "" : "-".$month).")</b></div>";

		$search_sql = " AND year(wvcs_dt) =".$year;

		if($vcs_status !=""){

			$search_sql .= " AND vcs.vcs_status = '$vcs_status' ";
			
		}//if($vcs_status !=""){

		if($month != "00"){

			$search_sql .= " AND month(wvcs_dt) =".$month;
		}

		$qry_params = array("search_sql"=>$search_sql);
		$qry_label = QRY_STAT_PC_CHECK_VIRUS;
		$sql = query($qry_label,$qry_params);

		//echo $sql;

		$result = sqlsrv_query($wvcs_dbcon, $sql);

		echo "<table border='1'>
				<tr>
					<td colspan='4'>".$_LANG_TEXT["virustext"][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT["detectcounttext"][$lang_code]."</td>
				</tr>";

		if($result){

			while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
				
				echo "<tr>";
				echo	"<td colspan='4'>".$row['virus_name']."</td>";
				echo	"<td colspan='2'>".number_format($row['cnt'])."</td>";
				echo "</tr>";

			}

		}

		echo "</table>";
		echo "</p>";
		
	}
?>

<?
	//**업체별 점검현황
	if(in_array("CVCS_STAT",$options)){	

		$search_sql = "";
		
		echo "<div><b>".$_LANG_TEXT["companyvcsstatustext"][$lang_code]."(".$checkdate1."~".$checkdate2.")</b></div>";

		echo "<table border='1'>
				<tr>
					<td>".$_LANG_TEXT['numtext'][$lang_code]."</td>
					<td colspan='3'>".$_LANG_TEXT['usercompanynametext'][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT['checkstatustext'][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT['virusdetectiontext'][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT['weaknessdetectiontext'][$lang_code]."</td>
				</tr>";


		if($checkdate1 != "" && $checkdate2 !=""){

			$search_sql .= " AND vcs.wvcs_dt between '$checkdate1 00:00:00.000' and '$checkdate2 23:59:59.999' ";
		}

		if($vcs_status !=""){

			$search_sql .= " AND vcs.vcs_status = '$vcs_status' ";
			
		}//if($vcs_status !=""){


		if($orderby != "") {

			$orderby = str_replace("com_name","MAX(v_com_name)",$orderby);
			$orderby = str_replace("vcs"," COUNT(v_wvcs_seq)",$orderby);
			$orderby = str_replace("virus","COUNT(virus_check)",$orderby);
			$orderby = str_replace("weak","COUNT(weak_check)",$orderby);

			$order_sql = " ORDER BY $orderby";

		} else {
			$order_sql = " ORDER BY COUNT(v_wvcs_seq) DESC ";
		}

					
		$qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql);
		$qry_label = QRY_STAT_COMPANY_VCS_LIST_ALL;
		$sql = query($qry_label,$qry_params);
		$result = @sqlsrv_query($wvcs_dbcon, $sql,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET )); 
		 

		//echo $sql;

		
		if($result){
			$total = sqlsrv_num_rows( $result );
			$no = $total;
			while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){


			$v_com_seq = $row['v_com_seq'];
			$v_com_name = $row['v_com_name'];
			$vcs_cnt = $row['vcs_cnt'];
			$weak_check_cnt = $row['weak_check_cnt'];
			$virus_check_cnt = $row['virus_check_cnt'];


			?>	
			<tr>
				<td><?php echo $no; ?></td>
				<td colspan='3'><?=$v_com_name?></td>
				<td colspan='2'><?=number_format($vcs_cnt)?></td>
				<td colspan='2'><?=number_format($virus_check_cnt)?></td>
				<td colspan='2'><?=number_format($weak_check_cnt)?></td>
			</tr>
			<?php

				$no--;
			}

		}

		echo "</table>";
		echo "</p>";
	}
?>

<?
	//**점검내역
	if(in_array("VCS_LIST",$options)){	

		$search_sql = "";

		echo "<div><b>".$_LANG_TEXT["checklisttext"][$lang_code]."(".$checkdate1."~".$checkdate2.")</b></div>";
		
		$search_sql = "";

		if($checkdate1 != "" && $checkdate2 !=""){

			$search_sql .= " AND vcs.wvcs_dt between '$checkdate1 00:00:00.000' and '$checkdate2 23:59:59.999' ";
		}

		if($vcs_status !=""){

			$search_sql .= " AND vcs.vcs_status = '$vcs_status' ";
			
		}//if($vcs_status !=""){

		if($orderby != "") {
			$order_sql = " ORDER BY $orderby";
		} else {
			$order_sql= " ORDER BY vcs.v_wvcs_seq DESC ";
		}

		$qry_params = array(
			"order_sql"=>$order_sql
			,"search_sql"=> $search_sql
		);
		$qry_label = QRY_RESULT_CHECK_LIST_ALL;
		$sql = query($qry_label,$qry_params);
		$result = @sqlsrv_query($wvcs_dbcon, $sql,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET )); 

		//echo $sql;
		

		if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;

		echo "<table border='1'>
				<tr>
					<td>".$_LANG_TEXT['numtext'][$lang_code]."</td>
					<td colspan='3'>".$_LANG_TEXT['visitortext'][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT['checkdatetext'][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT['indatetext'][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT['outdatetext'][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT['scancentertext'][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT['devicegubuntext'][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT['checkdiskcounttext'][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT['checkgubuntext'][$lang_code]."</td>
					<td colspan='6'>".$_LANG_TEXT['osndevicetext'][$lang_code]."</td>
					<td colspan='6'>".$_LANG_TEXT['serialnumbertext'][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT['checkresulttext'][$lang_code]."</td>
				</tr>";
		
		 
		 if($result){

		   $total = sqlsrv_num_rows( $result );  
		   $no = $total;
		  while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

				$v_wvcs_seq = $row['v_wvcs_seq'];

				$check_date = $row['check_date'];
				$in_available_date  = $row['checkin_available_dt'];
				if($in_available_date){
					
					$hour = substr($in_available_date,8,2);
					$min = substr($in_available_date,10,2);

					$in_available_date = substr($in_available_date,0,4)."-".substr($in_available_date,4,2)."-".substr($in_available_date,6,2);
					
					$in_available_date = $in_available_date." ".($hour? $hour : "00").":".($min? $min : "00");
				}
				$in_date	= $row['in_date'];

				$v_scan_center_name = $row['org_name']." ".$row['scan_center_name'];
				$v_asset_type = $row['v_asset_type'];
				$sys_sn = $row['v_sys_sn'];
				$hdd_sn = $row['v_hdd_sn'];
				$board_sn = $row['v_board_sn'];
				$os = $row['os_ver_name'];
				$maker = $row['v_manufacturer'];
				$mngr_dept = $row['mngr_department'];
				$mngr_name = aes_256_dec($row['mngr_name']);
				$vv_user_name = aes_256_dec($row['v_user_name']);
				$v_com_name = $row['v_com_name'];
				$vv_user_sq = $row['v_user_seq'];
				$weak_cnt = $row['weak_cnt'];
				$virus_cnt = $row['virus_cnt'];
				$wvcs_authorize_yn = $row['wvcs_authorize_yn'];
				$vcs_status = $row['vcs_status'];
				$out_date = $row['checkout_dt'];


				$check_type = $row['wvcs_type'];

				$disk_cnt = $row['disk_cnt'];

				$check_result ="";
				
				if($weak_cnt > 0){
					$check_result = $_LANG_TEXT["weaknessshorttext"][$lang_code];
				}

				if($virus_cnt > 0){

					$check_result .= ($check_result? ",":"").$_LANG_TEXT["virusshorttext"][$lang_code];
				}

				if($weak_cnt+$virus_cnt ==0){

					$check_result .= $_LANG_TEXT["safetytext"][$lang_code];
				}

				$user_name_com = $vv_user_name.($v_com_name? "/" : "").$v_com_name;

				$mngr = aes_256_dec($row['mngr_name']).($row['mngr_department']? " / " :"").$row['mngr_department'];


				$str_vcs_status = $_CODE['vcs_status'][$vcs_status];
				
				
		  ?>	
			<tr>
				<td><?php echo $no; ?></td>
				<td colspan='3'><?=$user_name_com ?></td>
				<td colspan='2'><?=$check_date?></td>
				<td colspan='2'><?=$in_date?></td>
				<td colspan='2'><?=$out_date?></td>
				<td colspan='2'><?=$v_scan_center_name?></td>
				<td colspan='2'><?=$_CODE['asset_type'][$v_asset_type]?></td>
				<td colspan='2'><?=$disk_cnt?></td>
				<td colspan='2'><?=$check_type?></td>
				<td colspan='6'><?=$os?></td>
				<td colspan='6'><?=$sys_sn?></td>
				<td colspan='2'><?=$check_result?></td>
			</tr>
			<?php
			
				$no--;
			}
			
		 }

		echo "</table>";
		echo "</p>";

	}
?>

<?
	//**장비별 점검내역
	if(in_array("DVCS_LIST",$options)){	

		$search_sql = "";

		echo "<div><b>".$_LANG_TEXT["devicechecklisttext"][$lang_code]."(".$checkdate1."~".$checkdate2.")</b></div>";
		

		if($checkdate1 != "" && $checkdate2 !=""){

			$search_sql .= " AND vcs1.wvcs_dt between '$checkdate1 00:00:00.000' and '$checkdate2 23:59:59.999' ";
		}

		if($vcs_status !=""){

			$search_sql .= " AND vcs1.vcs_status = '$vcs_status' ";
			
		}//if($vcs_status !=""){


		if($orderby != "") {
			$order_sql = " ORDER BY $orderby";
		} else {
			$order_sql= " ORDER BY vcs1.v_wvcs_seq DESC ";
		}

		$qry_params = array(
			"order_sql"=>$order_sql
			,"search_sql"=> $search_sql
		);
		$qry_label = QRY_USER_DEVICE_VCS_LIST_ALL;
		$sql = query($qry_label,$qry_params);
		$result = @sqlsrv_query($wvcs_dbcon, $sql,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET )); 


		//echo nl2br($sql);
		

		if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;

		echo "<table border='1'>
				<tr>
					<td>".$_LANG_TEXT["numtext"][$lang_code]."</td>
					<td colspan='3'>".$_LANG_TEXT["visitortext"][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT["checkdatetext"][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT["indatetext"][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT["outdatetext"][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT["devicegubuntext"][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT["checkdiskcounttext"][$lang_code]."</td>
					<td colspan='4'>".$_LANG_TEXT["manufacturertext"][$lang_code]."</td>
					<td colspan='6'>".$_LANG_TEXT["serialnumbertext"][$lang_code]."</td>
					<td colspan='6'>".$_LANG_TEXT["modeltext"][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT["checkgubuntext"][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT["scancentertext"][$lang_code]."</td>
				</tr>";
		
		
		if($result){
			$total = sqlsrv_num_rows( $result ); 
			$no = $total;
		  while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

				$cnt--;
				$iK++;

				$v_wvcs_seq = $row['v_wvcs_seq'];
				$v_user_name = aes_256_dec($row['v_user_name']);
				$v_com_name = $row['v_com_name'];

				$check_date = $row['check_date'];
				$in_date	= $row['in_date'];

				$v_scan_center_name = $row['org_name']." ".$row['scan_center_name'];
				$v_asset_type = $row['v_asset_type'];

				$check_type = $row['wvcs_type'];

				$media_type = $row['media_type'];

				$out_date = $row['checkout_dt'];

				$user_name_com = $v_user_name.($v_com_name? "/" : "").$v_com_name;


				if($v_asset_type=='NOTEBOOK'){
					$sn = $row['v_sys_sn'];
					$maker = $row['v_manufacturer'];
					$model = $row['v_model_name'];
				}else{
					
					$maker = $row['manufacturer'];
					$model = $row['disk_model'];
					$sn = $row['serial_number'];
				}

				$disk_cnt = $row['disk_cnt'];

				
				if($v_asset_type=='NOTEBOOK'){
					$str_device_gubun = $_LANG_TEXT["laptoptext"][$lang_code]."(".$row['os_ver_name'].")";
				}else if($media_type=='HDD'){
					$str_device_gubun =  $_CODE['storage_device_type']['HDD'];
				}else if($media_type=='Removable'){
					$str_device_gubun =  $_CODE['storage_device_type']['Removable'];
				}else{ 
					$str_device_gubun = $media_type;
				}

		  ?>	
			<tr>
				<td><?php echo $no; ?></td>
				<td colspan='3'><?=$user_name_com?></td>
				<td colspan='2'><?=$check_date?></td>
				<td colspan='2'><?=$in_date?></td>
				<td colspan='2'><?=$out_date?></td>
				<td colspan='2'><?=$str_device_gubun?></td>
				<td colspan='2'><?=$disk_cnt?></td>
				<td colspan='4'><?=$maker?></td>
				<td colspan='6'><?=$sn?></td>
				<td colspan='6'><?=$model?></td>
				<td colspan='2'><?=$check_type?></td>
				<td colspan='2'><?=$v_scan_center_name?></td>
			</tr>
			<?php
			
				$no--;
			}
			
		}

		echo "</table>";
		echo "</p>";

	}
?>

<?
	//**취약점/악성코드 내역
	if(in_array("WV_LIST",$options)){

		$search_sql = "";
		
		//*취약점 내역
		echo "<div><b>".$_LANG_TEXT["weaknesslisttext"][$lang_code]."(".$checkdate1."~".$checkdate2.")</b></div>";
		
		
		$search_sql = "";
		if($checkdate1 != "" && $checkdate2 !=""){

			$search_sql .= " AND vcs1.wvcs_dt between '$checkdate1 00:00:00.000' and '$checkdate2 23:59:59.999' ";
		}

		if($vcs_status !=""){

			$search_sql .= " AND vcs1.vcs_status = '$vcs_status' ";
			
		}//if($vcs_status !=""){

		$qry_params = array("search_sql"=>$search_sql);
		$qry_label = QRY_USER_VCS_WEAK_LIST;
		$sql = query($qry_label,$qry_params);
		$result = @sqlsrv_query($wvcs_dbcon, $sql,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		
		//echo $sql;

		echo "<table border='1'>
				<tr>
					<td>".$_LANG_TEXT["numtext"][$lang_code]."</td>
					<td colspan='3'>".$_LANG_TEXT["visitortext"][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT["checkdatetext"][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT["indatetext"][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT["checkgubuntext"][$lang_code]."</td>
					<td colspan='4'>".$_LANG_TEXT["ostext"][$lang_code]."</td>
					<td colspan='3'>".$_LANG_TEXT["checkitemtext"][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT["checkresulttext"][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT["resolvedresulttext"][$lang_code]."</td>
				</tr>";

		
		if($result){
			$total = sqlsrv_num_rows($result);
			$no = $total;
			while($row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){

				$check_date = $row['check_date'];
				$in_date = $row['in_date'];
				$check_type = $row['wvcs_type'];
				$device = $row['os_ver_name'];
				$weakness_name = $row['weakness_name'];
				$v_user_name = aes_256_dec($row['v_user_name']);
				$v_com_name = $row['v_com_name'];

				$user_name_com = $v_user_name.($v_com_name? "/" : "").$v_com_name;

				$str_org_status = $row['org_status']=="SAFE" ? $_LANG_TEXT['safetytext'][$lang_code] : $_LANG_TEXT['weaknessshorttext'][$lang_code];
				$str_fix_status = $row['fix_status']=="SAFE" ? $_LANG_TEXT['safetytext'][$lang_code] : $_LANG_TEXT['weaknessshorttext'][$lang_code];
				
				
			?>
				<tr>
					<td><?=$no?></td>
					<td colspan='3'><?=$user_name_com?></td>
					<td colspan='2'><?=$check_date?></td>
					<td colspan='2'><?=$in_date?></td>
					<td colspan='2'><?=$check_type?></td>
					<td colspan='4'><?=$device?></td>
					<td colspan='3'><?=$weakness_name?></td>
					<td colspan='2'><?=$str_org_status?></td>
					<td colspan='2'><?=$str_fix_status?></td>
				</tr>
			<?
				$no--;
			}
		}

		echo "</table>";
		echo "</p>";


		//*악성코드 내역
		echo "<div><b>".$_LANG_TEXT["viruslisttext"][$lang_code]."(".$checkdate1."~".$checkdate2.")</b></div>";
		
		$search_sql = "";
		if($checkdate1 != "" && $checkdate2 !=""){

			$search_sql .= " AND vcs1.wvcs_dt between '$checkdate1 00:00:00.000' and '$checkdate2 23:59:59.999' ";
		}

		if($vcs_status !=""){

			$search_sql .= " AND vcs1.vcs_status = '$vcs_status' ";
			
		}//if($vcs_status !=""){

		$qry_params = array("search_sql"=>$search_sql);
		$qry_label = QRY_USER_VCS_VIRUS_LIST;
		$sql = query($qry_label,$qry_params);
		$result = @sqlsrv_query($wvcs_dbcon, $sql,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		
		//echo $sql;

		echo "<table border='1'>
				<tr>
					<td>".$_LANG_TEXT["numtext"][$lang_code]."</td>
					<td colspan='3'>".$_LANG_TEXT["visitortext"][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT["checkdatetext"][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT["indatetext"][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT["checkgubuntext"][$lang_code]."</td>
					<td colspan='4'>".$_LANG_TEXT["osndevicetext"][$lang_code]."</td>
					<td colspan='5'>".$_LANG_TEXT["virusnametext"][$lang_code]."</td>
					<td colspan='20'>".$_LANG_TEXT["filepathtext"][$lang_code]."</td>
					<td colspan='2'>".$_LANG_TEXT["transresulttext"][$lang_code]."</td>
				</tr>";

		
		if($result){
			$total = sqlsrv_num_rows($result);
			$no = $total;
			while($row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){

				$check_date = $row['check_date'];
				$in_date = $row['in_date'];
				$check_type = $row['wvcs_type'];
				$device = $row['os_ver_name'];
				$virus_name = $row['virus_name'];
				$virus_path = $row['virus_path'];
				$virus_status = $row['virus_status'];

				$v_user_name = aes_256_dec($row['v_user_name']);
				$v_com_name = $row['v_com_name'];

				$user_name_com = $v_user_name.($v_com_name? "/" : "").$v_com_name;

				
				
			?>
				<tr>
					<td><?=$no?></td>
					<td colspan='3'><?=$user_name_com?></td>
					<td colspan='2'><?=$check_date?></td>
					<td colspan='2'><?=$in_date?></td>
					<td colspan='2'><?=$check_type?></td>
					<td colspan='4'><?=$device?></td>
					<td colspan='5'><?=$virus_name?></td>
					<td colspan='20'><?=$virus_path?></td>
					<td colspan='2'><?=$virus_status?></td>
				</tr>
			<?
				$no--;
			}
		}

		echo "</table>";
		echo "</p>";

	}
?>

<?
	if($result) sqlsrv_free_stmt($result);  
	if($wvcs_dbcon) sqlsrv_close($wvcs_dbcon);

	exit;
?>
</body>
</html>