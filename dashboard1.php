<?php
if(!$wvcs_dbcon) return;
?>
<script language="javascript">
$("document").ready(function(){

	var gubun = "MONTH";
	var date = new Date();
	var year = date.dateformat('yyyy');
	var month = date.dateformat('mm');
	var today = date.dateformat('yyyy-mm-dd');
	
	ChartResizeMain();

	StatisticsDayVcsData(today);

	window.onresize = function(event) {
		ChartResizeMain();
	};
	
});
</script>
<div id="main">
	<!--오늘 점검현황-->
	<div class='outline' style='height:340px'>
	<div class="section">
	<div class="section01">
		<h1><?=$_LANG_TEXT['todaystoragecheckstatustext'][$lang_code]?></h1>
		<div class="ch">
			<div class='wrap1'><div class="wrap2"><div class="one"><div id='ChartStorageCheckData' class="txt2 cap1">0</div><div class="txt cap1"><?=$_LANG_TEXT['checkstatustext'][$lang_code]?></div><canvas id="ChartStorageCheck" /></canvas></div></div></div>
			<div class='wrap1'><div class="wrap2"><div class="two"><div id='ChartStorageVirusData' class="txt2 cap2">0</div><div class="txt cap2"><?=$_LANG_TEXT['virusdetectiontext'][$lang_code]?></div><canvas id="ChartStorageVirus" /></canvas></div></div></div>
		</div>
	</div>
	<div class="section02">
		<h1><?=$_LANG_TEXT['todaypccheckstatustext'][$lang_code]?></h1>
		<div class="ch">
			<div class='wrap1'><div class="wrap2"><div class="one"><div id='ChartPcCheckData' class="txt2 cap1">0</div><div class="txt cap1"><?=$_LANG_TEXT['checkstatustext'][$lang_code]?></div><canvas id="ChartPcCheck" /></canvas></div></div></div>
			<div class='wrap1' ><div class="wrap2"><div class="two"><div id='ChartPcWeakData' class="txt2 cap2">0</div><div class="txt cap2"><?=$_LANG_TEXT['weaknessdetectiontext'][$lang_code]?></div><canvas id="ChartPcWeak" /></canvas></div></div></div>
			<div class='wrap1'><div class="wrap2"><div class="three"><div id='ChartPcVirusData' class="txt2 cap3">0</div><div class="txt cap3"><?=$_LANG_TEXT['virusdetectiontext'][$lang_code]?></div><canvas id="ChartPcVirus" /></canvas></div></div></div>
		</div>
		
	</div>
	</div>
	</div>

	<!--이달 기관별 점검현황-->
	<div class='outline'>
	<div class="section03">
		<h1><?=$_LANG_TEXT['thismonthscancentercheckstatustext'][$lang_code]?></h1>
<?
		$sdate = date("Y-m-01");
		$edate = date("Y-m-t");

//		기관별 점검현황
//		$qry_params = array("sdate"=>$sdate." 00:00:00.000","edate"=>$edate." 23:59:59.999");
//		$qry_label = QRY_STAT_ORG_CHECK_STATUS;
//		$sql = query($qry_label,$qry_params);

		
//		센터별 점검현황
		$qry_params = array("sdate"=>$sdate." 00:00:00.000","edate"=>$edate." 23:59:59.999");
		$qry_label = QRY_STAT_SCAN_CENTER_CHECK_STATUS;
		$sql = query($qry_label,$qry_params);

		//echo nl2br($sql);

		$result = @sqlsrv_query($wvcs_dbcon, $sql);
?>
		<table class="list" >
			<thead>
				<th style='width:20%;'><?=$_LANG_TEXT['scancentertext'][$lang_code]?></th>
				<th style='width:20%;'><?=$_LANG_TEXT['devicegubuntext'][$lang_code]?></th>
				<th style='width:20%;'><?=$_LANG_TEXT['checkstatustext'][$lang_code]?></th>
				<th style='width:20%;'><?=$_LANG_TEXT['weaknessdetectiontext'][$lang_code]?></th>
				<th style='width:20%;'><?=$_LANG_TEXT['virusdetectiontext'][$lang_code]?></th>
			</thead>
<?
		$CNT = 0;
		if($result){

			while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){

				$org_name = $row['org_name'];
				$scan_center_name = $row['scan_center_name'];
				$scan_center_code = $row['scan_center_code'];
				$device_gubun = $row['gubun'];

				$device_gubun_name = $_CODE['asset_type'][$device_gubun];
				
				$check_cnt = number_format($row['check_cnt']);
				$weak_cnt =  number_format($row['weak_cnt']);
				$virus_cnt = number_format($row['virus_cnt']);

				$param ="src=main&scan_center_code=".$scan_center_code."&check_result1=all&checkdate1=".$sdate."&checkdate2=".$edate;

?>
			<tr>
				<td><a href='<?=$_www_server?>/result/result_list.php?enc=<?=ParamEnCoding($param);?>'><?=$org_name." ".$scan_center_name?></a></td>
				<td><a href='<?=$_www_server?>/result/result_list.php?enc=<?=ParamEnCoding($param."&asset_type=".$device_gubun);?>'><?=$device_gubun_name?></a></td>
				<td><a href='<?=$_www_server?>/result/result_list.php?enc=<?=ParamEnCoding($param."&asset_type=".$device_gubun."&check_result2=");?>'><?=$check_cnt?></a></td>
				<td>
					<?if($device_gubun=="NOTEBOOK"){ ?>
						<a href='<?=$_www_server?>/result/result_list.php?enc=<?=ParamEnCoding($param."&asset_type=".$device_gubun."&check_result2=weak");?>'><?=$weak_cnt?></a>
					<?}else{ echo "-";}?>
				</td>
				<td><a href='<?=$_www_server?>/result/result_list.php?enc=<?=ParamEnCoding($param."&asset_type=".$device_gubun."&check_result2=virus");?>'><?=$virus_cnt?></a></td>
			</tr>
<?			
			$CNT++;
			}
		}

		if($CNT==0){

			echo "<tr><td colspan='5'>".$_LANG_TEXT['nodata'][$lang_code]."</td></tr>";
		}
?>
		
		</table>
	</div>
	</div>
</div>
<?
include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";
?>