<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
include_once $_server_path . "/" . $_site_path . "/inc/header.inc";

$v_wvcs_seq = $_REQUEST['v_wvcs_seq'];

if($v_wvcs_seq){

	$search_sql = " AND vcs1.v_wvcs_seq = '".$v_wvcs_seq."' ";
	$order_sql = " ORDER BY vcs1.v_wvcs_seq DESC ";

	$qry_params = array("search_sql"=>$search_sql,"order_sql"=>$order_sql);
	$qry_label = QRY_VCS_BARCODE_SCAN_INFO;
	$sql = query($qry_label,$qry_params);

	//echo nl2br($sql);

	$result = @sqlsrv_query($wvcs_dbcon, $sql,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	$vcs_count = @sqlsrv_num_rows( $result );  

	if($result){

		while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

			$barcode = $row['barcode'];

			$check_date = $row['check_date'];
			$in_available_date = $row['checkin_available_dt'];
			if($in_available_date){
				
				$hour = substr($in_available_date,8,2);
				$min = substr($in_available_date,10,2);

				$in_available_date = substr($in_available_date,0,4)."-".substr($in_available_date,4,2)."-".substr($in_available_date,6,2);
				
				$in_available_date = $in_available_date." ".($hour? $hour : "00").":".($min? $min : "00");
			}

			$scan_center_name = $row['scan_center_name'];
			$v_user_name = aes_256_dec($row['v_user_name']);
			$v_com_name = $row['v_com_name'];
			$os_ver_name = $row['os_ver_name'];

			$DISK[] = array(
				"type" => $row['media_type']
				,"model" => $row['disk_model']
				,"sn" => $row['serial_number']
				,"size" => $row['tot_size']
				,"partition_cnt" => $row['partition_cnt']
			);
		}
	}

}


?>
<style type="text/css">  
	html {overflow:hidden;}  
</style>
<script language="JavaScript" src="<?php echo $_js_server ?>/jquery-barcode.js"></script>
<script type='text/javascript'>
$("document").ready(function(){

	var oBarcode1 = {
			id : "barcode1",
			value:'<?=$barcode?>',
			btype:'code128',
			renderer:'css',
			settings : {
				bgcolor:"#FFFFFF",
				color:"#000000",
				barWidth:2,
				barHeight:20
			}
		};

	var oBarcode2 = {
			id : "barcode2",
			value:'<?=$barcode?>',
			btype:'code128',
			renderer:'css',
			settings : {
				bgcolor:"#FFFFFF",
				color:"#000000",
				barWidth:2,
				barHeight:40
			}
		};

	generateBarcode(oBarcode1,false,);
	generateBarcode(oBarcode2,true);
});
</script>
<div id="header">
	<div class="pop_gnb">
		<div class="logo">
			<img src="<?php echo $_www_server; ?>/images/logo.png">
		</div>
		<div class="title"><?=$_LANG_TEXT['vcsscanresulttext'][$lang_code]?> <a href='javascript:#' onclick="printArea('scan_zone')"><img src="<?=$_www_server?>/images/print.png"></a></div>
	</div>
</div>
<div id='popup'>
	<div class='container'>
		<div id='scan_zone' style='overflow-y:auto;overflow-x:hidden;height:440px;width:350px;'>
			<div style='border:1px solid #e7e7e7;padding:15px;width:300px;'>
			<div id="barcode1" style='margin:0px auto;margin-top:10px;margin-bottom:10px;'></div>
			<div style="text-align:center;width:100%;font-size:22px;font-weight:bold;margin:10px 0px 10px 0px;">[ <?=$_LANG_TEXT['vcsscanresulttext'][$lang_code]?> ]</div>
			
			<div>--------------------------------------------------------</div>
			<div><?=$_LANG_TEXT['scanprintmessagetext'][$lang_code]?></div>
			<div>--------------------------------------------------------</div>
			<ul>
				<li><?=$_LANG_TEXT['scancentertext'][$lang_code]?> : <?=$scan_center_name?></li>
				<li><?=$_LANG_TEXT['visitortext'][$lang_code]?> : <?=$v_user_name?></li>
				<li><?=$_LANG_TEXT['usercompanynametext'][$lang_code]?> : <?=$v_com_name?></li>
				<li><?=$_LANG_TEXT['checkdevicetext'][$lang_code]?> : <?=$os_ver_name?></li>
				<li><b><?=$_LANG_TEXT['checkdatetext'][$lang_code]?> : <?=$check_date?></b></li>
				<li><b><?=$_LANG_TEXT['inlimitdatetext'][$lang_code]?> : <?=$in_available_date?></b></li>
			</ul>
			<div>=================================<BR><b><<?=$_LANG_TEXT['checkdeviceinfotext'][$lang_code]?>></b><BR>=================================</div>
		<?
			for($i = 0 ; $i < sizeof($DISK) ; $i++){

				$_DISK = $DISK[$i];
		?>
			<ul>
				<?if($_DISK['partition_cnt'] ==0){?><li>### NO PARTITIONS ###</li><?}?>
				<li>Disk Type : <?=$_DISK['type']?></li>
				<li><b>Model : <?=$_DISK['model']?></b></li>
				<li><b>S/N : <?=$_DISK['sn']?></b></li>
				<li>Size : <?=formatBytes($_DISK['size'])?></li>
			</ul>
			<div>--------------------------------------------------------</div>
		<?}?>
			<div id="barcode2" style='margin:0px auto;margin-top:10px;margin-bottom:10px;'></div>
			<div><?=$_LANG_TEXT['scanprintmessagetext'][$lang_code]?></div>
			</div>
		</div>
		
	</div>
</div>
<?php
include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";
?>