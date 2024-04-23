<div id='report_area'>

<?
//**월별 점검현황
if(in_array("VCS_STAT",$options) && in_array("M",$stat_unit)){	
?>

	<div id='monthly_section'  class='section'>
		<h1> > <?=$_LANG_TEXT['monthlyvcsstatustext'][$lang_code]?></h1>
		<div  class='section01'>
			<select id='monthly_vcs_status_year' name="monthly_vcs_status_year" onchange="ReportStatisticsPcCheckData('MONTH')">
				<option value=""><?=$_LANG_TEXT['chooseyeartext'][$lang_code]?></option>
<?php
				for($i = 2009 ; $i <= $now_year ; $i++){

					echo "<option value='$i' ".($i==$monthly_vcs_status_year? "selected=selected" : "").">".$i.$_LANG_TEXT['yeartext'][$lang_code]."</option>";
				}
?>
			</select>
			<div style="height:450px;"><canvas id="chartPcCheckMONTH" name='chartPcCheck' gubun='MONTH'/></canvas></div>
			<div class='charttable' id='chartPcCheckMONTH_ChartDataTable'></div>
		</div>
	</div>
	<div class='page_devide_line'></div>
<?}?>

<?
//**일별 점검현황
if(in_array("VCS_STAT",$options) && in_array("D",$stat_unit)){	
?>
	<div id='daily_section' class='section'>
		<h1> > <?=$_LANG_TEXT['dailyvcsstatustext'][$lang_code]?></h1>
		<div class='section01'>
			<select id='daily_vcs_status_year' name="daily_vcs_status_year" onchange="ReportStatisticsPcCheckData('DAY')">
				<option value=""><?=$_LANG_TEXT['chooseyeartext'][$lang_code]?></option>
<?php
				for($i = 2009 ; $i <= $now_year ; $i++){

					echo "<option value='$i' ".($i==$daily_vcs_status_year? "selected=selected" : "").">".$i.$_LANG_TEXT['yeartext'][$lang_code]."</option>";
				}
?>
			</select>

			<select id='daily_vcs_status_month' name="daily_vcs_status_month" onchange="ReportStatisticsPcCheckData('DAY')">
				<option value=""><?=$_LANG_TEXT['choosemonthtext'][$lang_code]?></option>
<?php
				for($i = 1 ; $i <= 12 ; $i++){
					
					$month = strlen($i)==1 ? "0".$i : $i;

					echo "<option value='$month' ".($month==$daily_vcs_status_month? "selected=selected" : "").">".$_CODE['month'][$month]."</option>";
				}
?>
			</select>
			<div style="height:450px;"><canvas id="chartPcCheckDAY" name='chartPcCheck'  gubun='DAY' /></canvas></div>
			<div class='charttable' id='chartPcCheckDAY_ChartDataTable'></div>
		</div>
	</div>
	<div class='page_devide_line'></div>
<?}?>

<?
//**월별 장비 점검현황
if(in_array("DVCS_STAT",$options) && in_array("M",$stat_unit)){	
?>
	<div id='device_monthly_section'  class='section'>
		<h1> > <?=$_LANG_TEXT['monthlydevicevcsstatustext'][$lang_code]?></h1>
		<div  class='section01'>
			<select id='monthly_dvcs_status_year' name="monthly_dvcs_status_year" onchange="ReportStatisticsPcCheckData('MONTH_DEVICE')">
				<option value=""><?=$_LANG_TEXT['chooseyeartext'][$lang_code]?></option>
<?php
				for($i = 2009 ; $i <= $now_year ; $i++){

					echo "<option value='$i' ".($i==$monthly_dvcs_status_year? "selected=selected" : "").">".$i.$_LANG_TEXT['yeartext'][$lang_code]."</option>";
				}
?>
			</select>
			<div style="height:450px;"><canvas id="chartPcCheckMONTH_DEVICE" name='chartPcCheck' gubun='MONTH_DEVICE'/></canvas></div>
			<div class='charttable' id='chartPcCheckMONTH_DEVICE_ChartDataTable'></div>
		</div>
	</div>
	<div class='page_devide_line'></div>
<?}?>
	
<?
//**일별 장비 점검현황
if(in_array("DVCS_STAT",$options) && in_array("D",$stat_unit)){	
?>

	<div id='device_daily_section'  class='section'>
		<h1> > <?=$_LANG_TEXT['dailydevicevcsstatustext'][$lang_code]?></h1>
		<div class='section01'>
			<select id='daily_dvcs_status_year' name="daily_dvcs_status_year" onchange="ReportStatisticsPcCheckData('DAY_DEVICE')">
				<option value=""><?=$_LANG_TEXT['chooseyeartext'][$lang_code]?></option>
<?php
				for($i = 2009 ; $i <= $now_year ; $i++){

					echo "<option value='$i' ".($i==$daily_dvcs_status_year? "selected=selected" : "").">".$i.$_LANG_TEXT['yeartext'][$lang_code]."</option>";
				}
?>
			</select>

			<select id='daily_dvcs_status_month' name="daily_dvcs_status_month" onchange="ReportStatisticsPcCheckData('DAY_DEVICE')">
				<option value=""><?=$_LANG_TEXT['choosemonthtext'][$lang_code]?></option>
<?php
				for($i = 1 ; $i <= 12 ; $i++){
					
					$month = strlen($i)==1 ? "0".$i : $i;

					echo "<option value='$month' ".($month==$daily_dvcs_status_month? "selected=selected" : "").">".$_CODE['month'][$month]."</option>";
				}
?>
			</select>
			<div style="height:450px;"><canvas id="chartPcCheckDAY_DEVICE" name='chartPcCheck'  gubun='DAY_DEVICE' /></canvas></div>
			<div class='charttable' id='chartPcCheckDAY_DEVICE_ChartDataTable'></div>
		</div>
	</div>
	<div class='page_devide_line'></div>
<?}?>
	
<?
//**취약점/악성코드 현황
if(in_array("WV_STAT",$options)){
?>

	<div id='weakvirus_section'  class='section'>
		<h1> > <?=$_LANG_TEXT['weaknessnvirusstatustext'][$lang_code]?></h1>
		<div class='section02'>
		<div class='table'>
			<div id='weak_section' class="cell">
				<h1><?=$_LANG_TEXT['weaknessstatustext'][$lang_code]?></h1>
				<select id='weak_status_year' name="weak_status_year" onchange="ReportStatisticsPcCheckData('WEAK')">
					<option value=""><?=$_LANG_TEXT['chooseyeartext'][$lang_code]?></option>
	<?php
					for($i = 2009 ; $i <= $now_year ; $i++){

						echo "<option value='$i' ".($i==$weak_status_year? "selected=selected" : "").">".$i.$_LANG_TEXT['yeartext'][$lang_code]."</option>";
					}
	?>
				</select>

				<select id='weak_status_month' name="weak_status_month" onchange="ReportStatisticsPcCheckData('WEAK')">
					<option value="00"><?=$_LANG_TEXT['alltext'][$lang_code]?></option>
	<?php
					for($i = 1 ; $i <= 12 ; $i++){
						
						$month = strlen($i)==1 ? "0".$i : $i;

						echo "<option value='$month' ".($month==$weak_status_month? "selected=selected" : "").">".$_CODE['month'][$month]."</option>";
					}
	?>
				</select>
				<div class="ch"><canvas id="chartPcCheckWEAK"  name="chartPcCheck" gubun="WEAK"  width="260px" height="260px"/></canvas></div>
				<div class='ch_legend'><div id="chartPcCheckWEAK_legend" class="chart-legend"></div></div>
			</div>
			<div id='virus_section' class="cell">
				<h1><?=$_LANG_TEXT['virusdectionstatustext'][$lang_code]?></h1>
				<select id='virus_status_year' name="virus_status_year" onchange="ReportStatisticsPcCheckData('VIRUS')">
					<option value=""><?=$_LANG_TEXT['chooseyeartext'][$lang_code]?></option>
	<?php
					for($i = 2009 ; $i <= $now_year ; $i++){

						echo "<option value='$i' ".($i==$virus_status_year? "selected=selected" : "").">".$i.$_LANG_TEXT['yeartext'][$lang_code]."</option>";
					}
	?>
				</select>

				<select id='virus_status_month' name="virus_status_month" onchange="ReportStatisticsPcCheckData('VIRUS')">
					<option value="00"><?=$_LANG_TEXT['alltext'][$lang_code]?></option>
	<?php
					for($i = 1 ; $i <= 12 ; $i++){
						
						$month = strlen($i)==1 ? "0".$i : $i;

						echo "<option value='$month' ".($month==$virus_status_month? "selected=selected" : "").">".$_CODE['month'][$month]."</option>";
					}
	?>
				</select>
				<div class="ch"><canvas id="chartPcCheckVIRUS" name="chartPcCheck" gubun="VIRUS" width="260px" height="260px"/></canvas></div>
				<div class='ch_legend'><div id="chartPcCheckVIRUS_legend" class="chart-legend"></div></div>
			</div>
		</div>
		</div>
	</div>
	<div class='page_devide_line'></div>

<?}?>

<?
//**업체별 점검현황
if(in_array("CVCS_STAT",$options)){	
?>
	<div id='com_vcs_stat'  class='section'>
		<h1> > <?=$_LANG_TEXT['companyvcsstatustext'][$lang_code]?> (<?=$printdate1?> ~ <?=$printdate2?>)</h1>
		<div id='report_com_vcs_list' class='section03'></div>
	</div>
<?}?>

<?
//**점검내역
if(in_array("VCS_LIST",$options)){	
?>
	<div id='vcs_list'  class='section'>
		<h1> > <?=$_LANG_TEXT['checklisttext'][$lang_code]?> (<?=$printdate1?> ~ <?=$printdate2?>)</h1>
		<div id='report_vcs_list' class='section03'></div>
	</div>
<?}?>

<?
//**장비별 점검내역
if(in_array("DVCS_LIST",$options)){	
?>
	<div id='device_vcs_list'  class='section'>
		<h1> > <?=$_LANG_TEXT['checkdevicelisttext'][$lang_code]?> (<?=$printdate1?> ~ <?=$printdate2?>)</h1>
		<div id='report_device_vcs_list' class='section03'></div>
	</div>
<?}?>

<?
//**취약점/악성코드 점검내역
if(in_array("WV_LIST",$options)){	
?>
	<div id='weak_list'  class='section'>
		<h1> > <?=$_LANG_TEXT['weaknesslisttext'][$lang_code]?> (<?=$printdate1?> ~ <?=$printdate2?>)</h1>
		<div id='report_weak_list' class='section03'></div>
	</div>

	<div id='virus_list'  class='section'>
		<h1> > <?=$_LANG_TEXT['viruslisttext'][$lang_code]?> (<?=$printdate1?> ~ <?=$printdate2?>)</h1>
		<div id='report_virus_list' class='section03'></div>
	</div>
<?}?>

</div><!--<div id='report_area'>-->
