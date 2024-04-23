<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";


/*
$src:호출페이지
1.REPORT : /stat/report.php
*/

$src = $_REQUEST[src];
$checkdate1 = $_REQUEST[checkdate1];
$checkdate2 = $_REQUEST[checkdate2];
$status = $_REQUEST[status];
$orderby = $_REQUEST[orderby];	

//echo $src;

$param = "";
if($src!="") $param .= ($param==""? "":"&")."src=".$src;
if($checkdate1!="") $param .= ($param==""? "":"&")."checkdate1=".$checkdate1;
if($checkdate2!="") $param .= ($param==""? "":"&")."checkdate2=".$checkdate2;
if($status!="") $param .= ($param==""? "":"&")."status=".$status;

if($checkdate1 != "" && $checkdate2 !=""){

	$search_sql .= " AND vcs1.wvcs_dt between '$checkdate1 00:00:00.000' and '$checkdate2 23:59:59.999' ";
}

if($status !=""){

	$search_sql .= " AND vcs1.vcs_status = '$status' ";
	
}//if($status !=""){

$qry_params = array("search_sql"=>$search_sql);
$qry_label = QRY_USER_VCS_VIRUS_LIST;
$sql = query($qry_label,$qry_params);
$result = @sqlsrv_query($wvcs_dbcon, $sql,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));


//echo nl2br($sql);

?>
<!--검색결과리스트-->
<table class="list" style='margin-top:0px'>
	<tr>
		<th style='min-width:50px' ><?=$_LANG_TEXT["numtext"][$lang_code];?></th>
		<th style='min-width:150px' ><?=$_LANG_TEXT["visitortext"][$lang_code];?></th>
		<th style='min-width:80px;width:80px;' ><?=$_LANG_TEXT["checkdatetext"][$lang_code];?></th>
		<th style='min-width:80px;width:80px;' ><?=$_LANG_TEXT["indatetext"][$lang_code];?></th>
		<th style='min-width:85px' ><?=$_LANG_TEXT["checkgubuntext"][$lang_code];?></th>
		<th style='min-width:120px' ><?=$_LANG_TEXT["osndevicetext"][$lang_code];?></th>
		<th style='min-width:100px' ><?=$_LANG_TEXT["virusnametext"][$lang_code];?></th>
		<th style='min-width:150px' ><?=$_LANG_TEXT["filepathtext"][$lang_code];?></th>
		<th style='min-width:60px' ><?=$_LANG_TEXT["transresulttext"][$lang_code];?></th>
	</tr>
<?

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
			<td><?=$user_name_com?></td>
			<td><?=$check_date?></td>
			<td><?=$in_date?></td>
			<td><?=$check_type?></td>
			<td><?=$device?></td>
			<td><?=$virus_name?></td>
			<td><?=$virus_path?></td>
			<td><?=$virus_status?></td>
		</tr>
	<?
		$no--;
	}
}

if($total == 0){

	echo "<tr><td colspan='9'>".$_LANG_TEXT['nodata'][$lang_code]."</td></tr>";
}
?>				
		
</table>