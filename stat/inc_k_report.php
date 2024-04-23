<div id='report_area'>

<?

if(is_array($options) ==false) return;

//**일별 출입통계
if(in_array("DAILY_VISIT_STAT",$options)){	
?>
	<div class='section'>
		<h1> > <? echo $print_option['DAILY_VISIT_STAT']?> (<?=$printdate1?> ~ <?=$printdate2?>)</h1>
		<div  class='section01'>
			<div id='DAILY_VISIT_STAT_wrap' >
				<div style="height:450px;"><canvas id="chartVisitDay" name='chartVisitDay' /></canvas></div>
				<div class='charttable' id='chartVisitDay_DataTable'></div>
			</div>
		</div>
	</div>
<?}?>

<?
//**출입 내역
if(in_array("VISIT_LIST",$options)){	
?>
	<div class='section'>
		<h1> > <? echo $print_option['VISIT_LIST']?> (<?=$printdate1?> ~ <?=$printdate2?>)</h1>
		<div id='visit_list' class='section03'>Loading..</div>
	</div>
<?}?>

<?
//**일별 점검통계
if(in_array("DAILY_VCS_STAT",$options)){	
?>
	<div class='section'>
		<h1> > <? echo $print_option['DAILY_VCS_STAT']?> (<?=$printdate1?> ~ <?=$printdate2?>)</h1>
		<div  class='section01'>
			<div  id='DAILY_VCS_STAT_wrap' >
				<div style="height:450px;"><canvas id="chartVcsDay" name='chartVcsDay' /></canvas></div>
				<div class='charttable' id='chartVcsDay_DataTable'></div>
			</div>
		</div>
	</div>
<?}?>

<?
//**점검내역
if(in_array("VCS_LIST",$options)){	
?>
	<div class='section'>
		<h1> > <? echo $print_option['VCS_LIST']?> (<?=$printdate1?> ~ <?=$printdate2?>)</h1>
		<div id='vcs_list' class='section03'>Loading..</div>
	</div>
<?}?>


<?
//**점검결과 통계
if(in_array("VCS_RESULT_STAT",$options)){	
?>
	<div class='section'>
		<h1> > <? echo $print_option['VCS_RESULT_STAT']?> (<?=$printdate1?> ~ <?=$printdate2?>)</h1>
		<div >
			<div class="section022"  id='VCS_RESULT_STAT_wrap'>
				<div class=" table" style='width:100%'>
					<div class="cell" >
						<h1> > <? echo trsLang('위변조의심 현황','badextentionstatustext');?></h1>
						<div class="ch"><canvas id="chartVcsBadExt"  name="chartVcsBadExt"  width="260px" height="260px"/></canvas></div>
						<div class='ch_legend' style='width:40%;'><div id="chartVcsBadExt_legend" class="chart-legend" style='width:100%; ;'></div></div>
					</div>
					<div class="cell" >
						<h1> > <? echo trsLang('악성코드발견현황','virusdectionstatustext')?></h1>
						<div class="ch"><canvas id="chartVcsVirus" name="chartVcsVirus" width="260px" height="260px"/></canvas></div>
						<div class='ch_legend' style='width:40%;'><div id="chartVcsVirus_legend" class="chart-legend" style='width:100%; '></div></div>
					</div>
				</div>
			<div>
		</div>
	</div>
<?}?>

<?
//**위변조의심내역
if(in_array("BAD_FILE_LIST",$options)){	
?>
	<div class='section'>
		<h1> > <? echo trsLang('위변조의심내역','badextentionlisttext');?> (<?=$printdate1?> ~ <?=$printdate2?>)</h1>
		<div id='bad_file_list' class='section03'>Loading..</div>
	</div>
<?}?>

<?
//**악성코드내역
if(in_array("VIRUS_FILE_LIST",$options)){	
?>
	<div class='section'>
		<h1> > <? echo trsLang('악성코드내역','viruslisttext');?> (<?=$printdate1?> ~ <?=$printdate2?>)</h1>
		<div id='virus_file_list' class='section03'>Loading..</div>
	</div>
<?}?>


</div><!--<div id='report_area'>-->
