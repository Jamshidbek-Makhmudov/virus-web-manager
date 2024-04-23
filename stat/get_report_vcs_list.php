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

	$search_sql .= " AND vcs.wvcs_dt between '$checkdate1 00:00:00.000' and '$checkdate2 23:59:59.999' ";
}

if($status !=""){

	$search_sql .= " AND vcs.vcs_status = '$status' ";
	
}//if($status !=""){


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
  

//echo nl2br($sql);


if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;

?>
<!--검색결과리스트-->
<table class="list" style="margin-top:0px;" >
<tr>
	<th style='min-width:50px' ><?=$_LANG_TEXT["numtext"][$lang_code];?></th>
	<th style='min-width:150px;' ><?=$_LANG_TEXT["visitortext"][$lang_code];?></th>
	<th style='min-width:115px;' ><?=$_LANG_TEXT["checkdatetext"][$lang_code];?></th>
	<!--<th style='min-width:120px' ><?=$_LANG_TEXT["inlimitdatetext"][$lang_code];?></th>-->
	<th style='min-width:115px;' ><?=$_LANG_TEXT["indatetext"][$lang_code];?></th>
	<th style='min-width:80px'><?=$_LANG_TEXT["scancentertext"][$lang_code];?></th>
	<th style='min-width:60px' ><?=$_LANG_TEXT["devicegubuntext"][$lang_code];?></th>
	<th style='min-width:85px' ><?=$_LANG_TEXT["checkgubuntext"][$lang_code];?></th>
	<th style='min-width:120px'><?=$_LANG_TEXT["osndevicetext"][$lang_code];?></th>
	<th style='min-width:100px'><?=$_LANG_TEXT["serialnumbertext"][$lang_code];?></th>
	<!--<th style='min-width:80px' ><?=$_LANG_TEXT["progressstatustext"][$lang_code];?></th>-->
	<th style='min-width:80px' ><?=$_LANG_TEXT["checkresulttext"][$lang_code];?></th>
</tr>

<?

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


		$check_type = $row['wvcs_type'];

		$disk_cnt = $row['disk_cnt'];

		

		$check_result ="";
		
		if($weak_cnt > 0){
			$check_result = "<span class='icontxt weakness'>".$_LANG_TEXT["weaknessshorttext"][$lang_code]."</span> ";
		}

		//echo $virus_cnt;

		if($virus_cnt > 0){

			$check_result .= "<span class='icontxt virus'>".$_LANG_TEXT["virusshorttext"][$lang_code]."</span> ";
		}

		if($weak_cnt+$virus_cnt ==0){

			$check_result .= "<span class='icontxt clean'>".$_LANG_TEXT["safetytext"][$lang_code]."</span> ";
		}

		$user_name_com = $vv_user_name.($v_com_name? "/" : "").$v_com_name;

		$mngr = aes_256_dec($row['mngr_name']).($row['mngr_department']? " / " :"").$row['mngr_department'];


		if($wvcs_authorize_yn=="Y"){
			$vcs_status = $_CODE['vcs_status']['SUCCESS'];
		}else{
			$vcs_status = $_CODE['vcs_status']['CHECK'];
		}
		
		$param_enc = ParamEnCoding("view_src=".$src."&v_wvcs_seq=".$v_wvcs_seq.($param==""? "":"&").$param);

		$param_view_enc = ParamEnCoding("view_src=".$src."&v_wvcs_seq=".$v_wvcs_seq."&user_check_list_page=".$page.($param==""? "":"&").$param);

		if($v_asset_type=='NOTEBOOK'){
			$view_url = $_www_server.'/result/result_view_pc.php?enc='.$param_view_enc;
		}else{
			$view_url = $_www_server.'/result/result_view_storage.php?enc='.$param_view_enc;
		}
  ?>	
	<tr id='r_<?=$v_wvcs_seq?>' <?if($v_wvcs_seq==$view_v_wvcs_seq){ echo "style='background-color:#d6e5ff;'";}?> >
		<td><?php echo $no; ?></td>
		<td><?=$user_name_com ?></td>
		<td><?=$check_date?></td>
		<!--<td><?=$in_available_date?></td>-->
		<td><?=$in_date?></td>
		<td><?=$v_scan_center_name?></td>
		<td><?=$_CODE['asset_type'][$v_asset_type]?><span class='blue'>(<?=$disk_cnt?>)</span></td>
		<td><?=$check_type?></td>
		<td><?=$os?></td>
		<td><?=$sys_sn?></td>
		<!--<td><?=$vcs_status?></td>-->
		<td><?=$check_result?></td>
	</tr>
	<?php
	
		$no--;
	}
	
}

if($total < 1) {
	
?>
	<tr>
		<td colspan="13" align="center"><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
	</tr>
<?php
}
?>				
		
</table>