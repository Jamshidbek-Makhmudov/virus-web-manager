<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$src = $_REQUEST[src];
$checkdate1 = $_REQUEST[checkdate1];
$checkdate2 = $_REQUEST[checkdate2];
$status = $_REQUEST[status];


$orderby = $_REQUEST[orderby];		// 정렬순서


$param = "";
if($checkdate1!="") $param .= ($param==""? "":"&")."checkdate1=".$checkdate1;
if($checkdate2!="") $param .= ($param==""? "":"&")."checkdate2=".$checkdate2;
if($status!="") $param .= ($param==""? "":"&")."status=".$status;

?>
<table class="list" >
	<tr>
		<th><?=$_LANG_TEXT['numtext'][$lang_code]?></th>
		<th><a href="javascript:" onclick="LoadPageDataList('report_com_vcs_list',SITE_NAME+'/stat/get_report_com_vcs_list.php','enc=<?=ParamEnCoding($param.($param? "&":"")."orderby=".($orderby=="com_name"? "com_name desc" : "com_name"))?>')" class='sort'><?=$_LANG_TEXT["usercompanynametext"][$lang_code];?></a></th>
		<th><a href="javascript:" onclick="LoadPageDataList('report_com_vcs_list',SITE_NAME+'/stat/get_report_com_vcs_list.php','enc=<?=ParamEnCoding($param.($param? "&":"")."orderby=".($orderby=="vcs"? "vcs desc" : "vcs"))?>')" class='sort'><?=$_LANG_TEXT["checkstatustext"][$lang_code];?></a></th>
		<th><a href="javascript:" onclick="LoadPageDataList('report_com_vcs_list',SITE_NAME+'/stat/get_report_com_vcs_list.php','enc=<?=ParamEnCoding($param.($param? "&":"")."orderby=".($orderby=="virus"? "virus desc" : "virus"))?>')"   class='sort'><?=$_LANG_TEXT["virusdetectiontext"][$lang_code];?></a></th>
		<th><a href="javascript:" onclick="LoadPageDataList('report_com_vcs_list',SITE_NAME+'/stat/get_report_com_vcs_list.php','enc=<?=ParamEnCoding($param.($param? "&":"")."orderby=".($orderby=="weak"? "weak desc" : "weak"))?>')"  class='sort'><?=$_LANG_TEXT["weaknessdetectiontext"][$lang_code];?></a></th>
	</tr>
<?php


if($checkdate1 != "" && $checkdate2 !=""){

	$search_sql .= " AND vcs.wvcs_dt between '$checkdate1 00:00:00.000' and '$checkdate2 23:59:59.999' ";
}

if($status !=""){

	$search_sql .= " AND vcs.vcs_status = '$status' ";
	
}//if($status !=""){


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

//echo nl2br($sql);

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
		<td class='num'><?php echo $no; ?></td>
		<td><?=$v_com_name?></td>
		<td><?=number_format($vcs_cnt)?></td>
		<td><?=number_format($virus_check_cnt)?></td>
		<td><?=number_format($weak_check_cnt)?></td>
	</tr>
	<?php

	$no--;
	}

}

if($result) sqlsrv_free_stmt($result);  
sqlsrv_close($wvcs_dbcon);
if($total < 1) {

?>
<tr>
	<td colspan="6" align='center'><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
</tr>
<?php
}
?>
</table>