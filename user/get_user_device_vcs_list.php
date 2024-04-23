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
src:ȣ��������
1.pop_user_vcs_summary 
2.pop_user_vcs_device
*/

$src = $_REQUEST[src];
$v_user_seq = $_REQUEST[v_user_seq];
$device_gubun = $_REQUEST[device_gubun];
$orderby = $_REQUEST[orderby];		
$page = $_REQUEST[page];

if($src=="pop_user_vcs_summary"){
	$paging = 10;
}else{
	$paging = $_paging;
}

$param = "";
if($src!="") $param .= ($param==""? "":"&")."src=".$src;
if($v_user_seq!="") $param .= ($param==""? "":"&")."v_user_seq=".$v_user_seq;
if($device_gubun!="") $param .= ($param==""? "":"&")."device_gubun=".$device_gubun;

$search_sql = " AND vcs1.v_user_seq = '".$v_user_seq."' ";

if($device_gubun !=""){

	if($device_gubun=='NOTEBOOK'){

		$search_sql .= " AND vcs1.v_asset_type = 'NOTEBOOK' ";

	}else if($device_gubun=='HDD'){
		
		$search_sql .= " AND vcs1.v_asset_type = 'RemovableDevice' AND dsk.media_type ='HDD' ";

	}else if($device_gubun=='Removable'){
		
		$search_sql .= " AND vcs1.v_asset_type = 'RemovableDevice' AND dsk.media_type ='Removable' ";

//	}else if($device_gubun=='CD/DVD'){
//		
//		$search_sql .= " AND vcs1.v_asset_type = 'RemovableDevice' AND lk.drive_type =''CD/DVD' ";

	}else if($device_gubun=='ETC'){

		$search_sql .= " AND vcs1.v_asset_type = 'RemovableDevice' ";
		$search_sql .= " AND CHARINDEX('HDD',dsk.media_type) = 0 ";
		$search_sql .= " AND CHARINDEX('Removable',dsk.media_type) = 0 ";
	}
}


$qry_params = array(
	"search_sql"=> $search_sql
);

$qry_label = QRY_USER_DEVICE_VCS_COUNT;
$sql = query($qry_label,$qry_params);
$result = sqlsrv_query($wvcs_dbcon, $sql); 

//echo nl2br($sql);

$total = 0;
if($result) {
	$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
	$total = $row['CNT'];
}

$rows = $paging;			// �������� ��°���
$lists = $_list;			// ��ϼ�
$page_count = ceil($total/$rows);
if(!$page || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;
$no = $total-$start;
$end = $start + $rows;

if($orderby != "") {
	$order_sql = " ORDER BY $orderby";
} else {
	$order_sql= " ORDER BY vcs1.v_wvcs_seq DESC ";
}

$qry_params = array(
	"end"=> $end
	,"start"=>$start
	,"order_sql"=>$order_sql
	,"search_sql"=> $search_sql
);
$qry_label = QRY_USER_DEVICE_VCS_LIST;
$sql = query($qry_label,$qry_params);
$result = sqlsrv_query($wvcs_dbcon, $sql); 

//echo nl2br($sql);

$cnt = 20;
$iK = 0;
$classStr = "";

if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;

?>
<script type='text/javascript'>
$(function(){
	$("#uc_wrapper1").scroll(function(){
		$("#uc_wrapper2").scrollLeft($("#uc_wrapper1").scrollLeft());
	});
	$("#uc_wrapper2").scroll(function(){
		$("#uc_wrapper1").scrollLeft($("#uc_wrapper2").scrollLeft());
	});

	window.onresize = function(event) {
		var w = $("#tblUsrDeviceVcsList").width();
		$("#uc_div1").width(w);
	};
	

});
</script>
<!--�˻��������Ʈ-->
<div id='uc_wrapper1' class="wrapper">
<div id='uc_div1' style='height:1px;width:1000px'></div>
</div>
<div id='uc_wrapper2' class="wrapper">
<table id='tblUsrDeviceVcsList' class="list" style="margin-top:0px;min-width:1000px;" >
<tr>
	<th style='min-width:50px;width:50px;'><?=$_LANG_TEXT["numtext"][$lang_code];?></th>
	<th style='min-width:100px;width:100px'><?=$_LANG_TEXT["devicegubuntext"][$lang_code];?></th>
	<th style='min-width:110px;width:110px;' class="<? echo $_CODE_CSS['display_inout_info'];?>"><?=$_LANG_TEXT["indatetext"][$lang_code];?></th>
	<th style='min-width:110px;width:110px;' class="<? echo $_CODE_CSS['display_inout_info'];?>"><?=$_LANG_TEXT["outdatetext"][$lang_code];?></th>
	<th style='min-width:200px;'><?=$_LANG_TEXT["manufacturertext"][$lang_code];?></th>
	<th style='min-width:140px;'><?=$_LANG_TEXT["serialnumbertext"][$lang_code];?></th>
	<th style='min-width:200px;'><?=$_LANG_TEXT["modeltext"][$lang_code];?></th>
	<th style='min-width:110px;width:110px;'><?=$_LANG_TEXT["checkdatetext"][$lang_code];?></th>
	<th style='min-width:100px;width:100px;'><?=$_LANG_TEXT["checkgubuntext"][$lang_code];?></th>
	<th style='min-width:80px;width:100px;'><?=$_LANG_TEXT["scancentertext"][$lang_code];?></th>
</tr>

<?

 if($result){
  while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

		$cnt--;
		$iK++;

		$v_wvcs_seq = $row['v_wvcs_seq'];

		$check_date = $row['check_date'];
		$in_date	= $row['in_date'];
		$out_date	= $row['out_date'];

		$v_scan_center_name = $row['org_name']." ".$row['scan_center_name'];
		$v_asset_type = $row['v_asset_type'];

		$check_type = $row['wvcs_type'];

		$media_type = $row['media_type'];
		$drive_type = $row['drive_type'];

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
		<td><?=$str_device_gubun?></td>
		<td class="<? echo $_CODE_CSS['display_inout_info'];?>"><?=$in_date?></td>
		<td class="<? echo $_CODE_CSS['display_inout_info'];?>"><?=$out_date?></td>
		<td><?=$maker?></td>
		<td><?=$sn?></td>
		<td><?=$model?></td>
		<td><?=$check_date?></td>
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
		<td colspan="9" align="center"><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
	</tr>
<?php
}
?>				
		
</table>
</div>

<!--����¡-->
<?php
if($total > 0) {
	$param_enc = ($param)? "enc=".ParamEnCoding($param) : "";
	print_pagelistNew3Func('user_device_vcs_list',$_www_server."/user/get_user_device_vcs_list.php",$page, $lists, $page_count, $param_enc, '', $total );
}
?>
<? 
	$excel_param_enc = ParamEnCoding($param.(($orderby)? "&orderby=".$orderby : ""));
	$excel_down_url = $_www_server."/user/user_device_vcs_list_excel.php?enc=".$excel_param_enc;
?>
<div class="right" style='margin-top:<?=$total > 0 ? "-70" : "10" ?>px;'>
	<a href="#" id='btnExcelDown' onclick="ExcelDown('<?=$excel_down_url?>','btnExcelDown')" class="btnexcel required-print-auth hide" ><?=$_LANG_TEXT["btnexceldownload"][$lang_code];?></a>
</div>