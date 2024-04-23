<?php
if(!$wvcs_dbcon) return;

$scan_center_code = $_REQUEST[scan_center_code];
$param = "";
if ($start_date != "") $param .= ($param == "" ? "" : "&") . "start_date=" . $end_date;
if ($end_date != "") $param .= ($param == "" ? "" : "&") . "end_date=" . $end_date;

if ($scan_center_code != "") $param .= ($param == "" ? "" : "&") . "scan_center_code=" . $scan_center_code;

$start_date = date("Y-m-d", strtotime(date("Y-m-d") . " -1 month"));
$end_date = date("Y-m-d");
//this is for titles
$start_date_dot = date("Y.m.d", strtotime(date("Y-m-d") . " -1 month"));
$end_date_dot = date("Y.m.d");

$now_year = date("Y");
$now_month = date("m");

if ($scan_center_code !="") {
	$initial_active_class = false;
}else{
	$initial_active_class=true;

}
$Model_Dashboard=new Model_Dashboard();
?>
<script language="javascript">
	$("document").ready(function() {

		// 출입 현황
		loadStatisticsVisitPeriodChart()
		//미회수/ 미반출 현황
		loadgetNotReturnChart();
		//악성코드 현황
		loadMainStatisticsVcsResultChart()
		// 점검현황
		LoadVcsStatus()
		// 서버현황
		loadMainServerStatus()
		
	});

	function updateCurrentTime() {
			const currentTimeElement = document.getElementById('current-time');
			const now = new Date();

			const options = {
				year: 'numeric',
				month: 'long',
				day: 'numeric',
				weekday: 'short',
				hour: '2-digit',
				minute: '2-digit',
				hour12: false,
			};

			const formattedTime = new Intl.DateTimeFormat('ko-KR', options).format(now);
			currentTimeElement.textContent = formattedTime;

			requestAnimationFrame(updateCurrentTime);
		}

	requestAnimationFrame(updateCurrentTime);



	 function handleClick(element) {
			var input = element.previousElementSibling;
			if (input) {
				input.click();
			}
		}
</script>


<?php 
$search_sql = "";
if($scan_center_code !=""){ 

  $search_sql .= " and v2.in_center_code = '{$scan_center_code}'  ";
}


?>

<div id="main2">
	<!-- main header -->
	<div class='header'>
		<div class='section_top'>
	
			<form  name="searchForm " action="<?php echo $_SERVER[PHP_SELF] ?>" method="POST">
			<!-- <input type='hidden' name='proc_name' id='proc_name'> -->
			<div class='main_btns flex-start scroll'>
						
				<input class="btn  <?= $initial_active_class ? "active": "" ?> " value="<?= trsLang('통합 대시보드','integrated_dashboard'); ?>" type="button" onclick="location.href='<? echo $_www_server?>/main.php'">
					<?php
							$Model_manage = new Model_manage;
	
							$result = $Model_manage->getCenterList();
							
							if($result){
								while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

									$_scan_center_code = $row['scan_center_code'];
									$_scan_center_name = $row['scan_center_name'];
									$_scan_center_name_str=utf8_strcut($_scan_center_name,15);
						?>
				 <label class='scan-center-label' style='padding-left:5px;'>
            <input class='btn <?php if ($_scan_center_code == $scan_center_code) echo "active"; ?>' type="submit" name="scan_center_code" value="<?= $_scan_center_code ?>" style="display:none;" onclick="return SearchSubmitDash(document.searchForm);">
         
            <span title="<?= $_scan_center_name ?>" class='scan-center-name btn  <?php if ($_scan_center_code == $scan_center_code) echo "active"; ?>' onclick="handleClick(this)"><?=  $_scan_center_name_str ?></span>
						
        </label>
							<?php
								}
							}
						?>
			
				</div>
								</form>

			<div class="main_time">
			<span class="logo"></span>
			<div  class="time" id="current-time"> <!--현재시간--></div>
			</div>
					
		</div>
	</div>
	
	<form name="chartsearchForm" id='chartsearchForm'>
		<input type="hidden" name="start_date"  value="<?= $start_date ?>">
		<input type="hidden" name="end_date"  value="<?= $end_date ?>">
		<input type="hidden" id="scan_center_code" name="scan_center_code"  value="<?= $scan_center_code ?>">
	</form>

	<!-- 주요 통계 section01-->
	<?php include_once $_server_path . "/" . $_site_path . "/dashboard/main_summary_inc.php";?>
<!-- 출입현황  차트 section02 -->
		<div class='section002'>
			<div class='outline left'>
			 <div class='chart_title flex-between'>
				
						<h2><?= trsLang('출입 현황','ent_exit_status_theme'); ?> ( <?= $start_date_dot?> ~ <?= $end_date_dot?>) </h2>
							<div class="chart_title_name flex-evenly">

						<div  class="vst_box">
						</div>
						<span ><?= trsLang('방문자 출입','visitor_visit'); ?></span>
						<div class="file_box">
						</div>
						<span ><?= trsLang('파일반입','fileimport'); ?></span>

					</div>
			 </div>
				<div style="height:450px; padding: 28px;"><canvas id="chartVisitPeriod" name='chartVisitPeriod'  /></canvas></div>

				
			</div>

			 <!-- section03 -->
				<div class='outline right'>					
					 <div class=' doughnut_title flex-between'>
								<div>					
									<h3><?= trsLang('미회수/ 미반출 현황','none_recovery_text'); ?> (<?= $end_date_dot?>) </h3>
								</div>
								<? if(gethostname()=="dataprotecs"){?>
								<div class=" flex-center ">
										<span class='logo_refresh' style='cursor:pointer;' onclick="loadgetNotReturnChart()"></span>
								</div>
								<?}?>
					</div>
          <div class='doughnut_sec flex-center  '>
										<div class="ch" style='height:250px; width:50%;'><canvas id="chartPcCheckWEAK"  name="chartPcCheck" gubun="WEAK"  width="260px" height="260px"/>
									
									</canvas></div>	
										<div  class='ch_legend' style='display:none'><div id="chartPcCheckWEAK_legend" class="chart-legend"></div></div>
					 <div class='doughnut_list'>
										<?php 
										$Model_Dashboard->SHOW_DEBUG_SQL=false;
										// $args=array("start_date"=>$start_date,"end_date"=>$end_date,"search_sql"=>$search_sql);
									
										$args=array("search_sql"=>$search_sql);
									
	                  $result = $Model_Dashboard->getNotReturnStat($args); 
										if($result){
											$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

											$not_return_pass_count=$row['not_return_pass_count']; 
											$not_return_usb_count=$row['not_return_usb_count'];
											$not_export_goods_count=$row['not_export_goods_count'];
 										}

									$uncovered = true;		
									$chart_param = "";
									if ($uncovered) $chart_param .= ($chart_param == "" ? "" : "&") . "uncovered=" . $uncovered."&start_date=2020-01-01";
									if ($scan_center_code != "") $chart_param .= ($chart_param == "" ? "" : "&") . "scan_center_code=" . $scan_center_code;

										?>
			
			<a style='cursor:pointer;' onclick="javascript:location.href='<? echo $_www_server?>/user/access_control_file.php?enc=<?= ParamEnCoding($chart_param)?>'">
						<div class='flex-between'>	
						
							<div class='flex-start'>
								
							<div class='flex-center'>			
								<span class='logo_plus'></span>
							</div>
							<span class='doughnut_list_title'><?= trsLang('보안 USB','usb_count');?></span>
						</div>	
						<div>
							
							<span class='num2' style='color:#ff9e40;'><?=$not_return_usb_count?></span>
						</div>
					</div>
				</a>	
				<a style='cursor:pointer;' onclick="javascript:location.href='<? echo $_www_server?>/user/access_control_pass.php?enc=<?= ParamEnCoding($chart_param)?>'">
			
						<div class='flex-between'>	
							<div class='flex-start'>					
							<div class='flex-center'>			
								<span class='logo_plus'></span>
								
							</div>
							<span class='doughnut_list_title'><?=trsLang('임시 출입증','pass_count');?></span>
						</div>
						<div>

							
								<span class='num2' style='color:#fe6483;'><?=$not_return_pass_count?></span>
						</div>
						</div>
						</a>
							<a style='cursor:pointer;' onclick="javascript:location.href='<? echo $_www_server?>/user/rental_details.php?enc=<?=ParamEnCoding($chart_param)?>'">
						
						<div class='flex-between'>		
							<div class='flex-start'>				
							<div class='flex-center'>			
								<span class='logo_plus'></span>
							</div>

							<span class='doughnut_list_title'><?=trsLang('물품 미회수','rent_count');?></span>
						</div>
						<div>
							<?php if($scan_center_code !=""){ 
          	$search_sql_return .= " and rt.rent_center_code = '{$scan_center_code}'  ";}
										$Model_Dashboard->SHOW_DEBUG_SQL=false;
										$args=array("start_date"=>$start_date,"end_date"=>$end_date,"search_sql"=>$search_sql_return);
	                  $result = $Model_Dashboard->getNotReturnStats($args); 
										if($result){
											$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

											$not_return_rent_cnt=$row['not_return_rent_cnt']; 

 										}
										?>
							<span class='num2' style='color:#4cc0c0;'><?=$not_return_rent_cnt?></span>
						</div>
						</div>
						</a>
							<a style='cursor:pointer;' onclick="javascript:location.href='<? echo $_www_server?>/user/access_control_goods.php?enc=<?=ParamEnCoding($chart_param)?>'">
						
						<div class='flex-between'>	
							<div class='flex-start'>					
							<div class='flex-center'>			
								<span class='logo_plus'></span>
							</div>
							<span class='doughnut_list_title'><?=trsLang('자산 미반출','goods_count');?></span>
						</div>
						<div>
							
							<span class='num2' style='color:#ffcd57;'><?=$not_export_goods_count?></span>
						</div>
						</div>
						</a>
			
						
					</div>

					</div>
			</div>
		</div>
	 <div class='section003 flex-center'>	
 <!-- section04 -->
			<?php include_once $_server_path . "/" . $_site_path . "/dashboard/main_vcs_status_inc.php";?>
<!-- section05 -->	
		<div class='outline_right incenter'>	
		<h2><?= trsLang('악성코드 현황','mal_code'); ?> ( <?= $start_date_dot?> ~ <?= $end_date_dot?>) </h2>
     <div class='doughnut_sec2 flex-start'>
					<div class="ch" style='height:250px; width:50%;'><canvas id="chartPcCheckVIRUS" name="chartPcCheckVIRUS" width="260px" height="260px"/></canvas></div>
				<div class='ch_legend'><div id="chartPcCheckVIRUS_legend" class="chart-legend flex-center"></div></div>

		 </div>

		</div>
<!-- 백신 업데이트 현황 section06 -->
			<?php include_once $_server_path . "/" . $_site_path . "/dashboard/main_app_update_inc.php";?>	
		</div>

		<div class='section004 flex-start'>
<!-- 시스템 작업 로그 section07 -->
			<?php include_once $_server_path . "/" . $_site_path . "/dashboard/main_system_log_inc.php";?>
<!--서버현황 section08 -->
			<?php include_once $_server_path . "/" . $_site_path . "/dashboard/main_server_status_inc.php";?>


</div>
	
	
</div>
<?
include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";
?>