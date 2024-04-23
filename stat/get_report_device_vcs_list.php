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
src:호출페이지
1.REPORT: /stat/report.php
*/

$src = $_REQUEST[src];
$checkdate1 = $_REQUEST[checkdate1];
$checkdate2 = $_REQUEST[checkdate2];
$status = $_REQUEST[status];
$orderby = $_REQUEST[orderby];	

$param = "";
if($src!="") $param .= ($param==""? "":"&")."src=".$src;
if($checkdate1!="") $param .= ($param==""? "":"&")."checkdate1=".$checkdate1;
if($checkdate2!="") $param .= ($param==""? "":"&")."checkdate2=".$checkdate2;
if($status!="") $param .= ($param==""? "":"&")."status=".$status;


$search_sql = "";

if($checkdate1 != "" && $checkdate2 !=""){

	$search_sql .= " AND vcs1.wvcs_dt between '$checkdate1 00:00:00.000' and '$checkdate2 23:59:59.999' ";
}

if($status !=""){

	$search_sql .= " AND vcs1.vcs_status = '$status' ";
	
}//if($status !=""){


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

?>
<table class="list" >
<tr>
	<th style='min-width:50px;'><?=$_LANG_TEXT["numtext"][$lang_code];?></th>
	<th style='min-width:150px;'><?=$_LANG_TEXT["visitortext"][$lang_code];?></th>
	<th style='min-width:80px;'><?=$_LANG_TEXT["checkdatetext"][$lang_code];?></th>
	<th style='min-width:80px;'><?=$_LANG_TEXT["indatetext"][$lang_code];?></th>
	<th style='min-width:90px;'><?=$_LANG_TEXT["devicegubuntext"][$lang_code];?></th>
	<th style='min-width:150px;'><?=$_LANG_TEXT["manufacturertext"][$lang_code];?></th>
	<th style='min-width:100px;'><?=$_LANG_TEXT["serialnumbertext"][$lang_code];?></th>
	<th style='min-width:130px;'><?=$_LANG_TEXT["modeltext"][$lang_code];?></th>
	<th style='min-width:85px;'><?=$_LANG_TEXT["checkgubuntext"][$lang_code];?></th>
	<th style='min-width:80px;'><?=$_LANG_TEXT["scancentertext"][$lang_code];?></th>
</tr>

<?

if($result){

  $total = @sqlsrv_num_rows( $result ); 
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
			$str_device_gubun = $_LANG_TEXT["laptoptext"][$lang_code]."<span  class='blue'>(".$disk_cnt.")</span><BR>(".$row['os_ver_name'].")";
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
		<td><?=$user_name_com?></td>
		<td><?=$check_date?></td>
		<td><?=$in_date?></td>
		<td><?=$str_device_gubun?></td>
		<td><?=$maker?></td>
		<td><?=$sn?></td>
		<td><?=$model?></td>
		<td><?=$check_type?></td>
		<td><?=$v_scan_center_name?></td>
	</tr>
	<?php
	
		$no--;
	}
	
}

if($total < 1) {
	
?>
	<tr>
		<td colspan="10" align="center"><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
	</tr>
<?php
}
?>				
		
</table>