<?php
$page_name = "vcs_stat";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
include_once $_server_path . "/" . $_site_path . "/inc/header.inc";

$now_year = date("Y");
$now_month = date("m");

?>
<script language="javascript">

$("document").ready(function(){

	var onTab = $("#onTab").val();

	$(".section01 .tab li").removeClass("on");
	$(".section01 .tab li[name='"+onTab+"']").addClass("on");
	$("#onTab").val(onTab);

	CallStatisticsPcCheckData();
});

</script>
<?php
include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";
?>

<div id="statistics_check">
	<div class="outline">
	<div class="container">
		
	
		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_statistics_result"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		
		<div class='right'>
			<?=$_LANG_TEXT["vcsprogressstatuschoosetext"][$lang_code];?>
			<select id='vcs_status' name='vcs_status' onchange="CallStatisticsPcCheckData();">
				<option value=''><?=$_LANG_TEXT["alltext"][$lang_code];?></option>
			<?
			foreach($_CODE['vcs_status'] as $key => $name){
				echo "<option value='".$key."' ".($vcs_status==$key ? "selected" : "").">".$name."</option>";
			}
			?>
			</select>
		</div>

		<input type="hidden" name="chart_year" id="chart_year">
		<input type="hidden" name="chart_month" id="chart_month">
		<input type="hidden" name="deivce_gubun2" id="deivce_gubun2">

		<input type='hidden' name='onTab' id='onTab' value='DAYLIST'>
		
		<div class="section01">
		<h1><?=$_LANG_TEXT['m_statistics_result'][$lang_code]?></h1>
		<ul class="tab">
			<li name="DAYLIST" class="on">
				<a href="#" onclick="$('#onTab').val('DAYLIST');StatisticsPcCheckData('DAY')"><?=$_LANG_TEXT['daytext'][$lang_code]?></a>
				<div>
					<select name="year" onchange="StatisticsPcCheckData('DAY')">
						<option value=""><?=$_LANG_TEXT['chooseyeartext'][$lang_code]?></option>
<?php
						for($i = 2009 ; $i <= $now_year ; $i++){

							echo "<option value='$i' ".($i==$now_year? "selected=selected" : "").">".$i.$_LANG_TEXT['yeartext'][$lang_code]."</option>";
						}
?>
					</select>

					<select name="month" onchange="StatisticsPcCheckData('DAY')">
						<option value=""><?=$_LANG_TEXT['choosemonthtext'][$lang_code]?></option>
<?php
						for($i = 1 ; $i <= 12 ; $i++){
							
							$month = strlen($i)==1 ? "0".$i : $i;

							echo "<option value='$month' ".($month==$now_month? "selected=selected" : "").">".$_CODE['month'][$month]."</option>";
						}
?>
					</select>
					<select name='asset_type' onchange="StatisticsPcCheckData('DAY')">
						<option value=''><?=$_LANG_TEXT["devicealltext"][$lang_code];?></option>
						<?
						foreach($_CODE['asset_type'] as $key => $name){
							echo "<option value='".$key."' ".($asset_type==$key ? "selected" : "").">".$name."</option>";
						}
						?>
					</select>
					<div style="height:450px;"><canvas id="chartPcCheckDAY" name='chartPcCheck'  gubun='DAY' /></canvas></div>
				</div>
			</li>
			<li name="MONTHLIST">
				<a href="#" onclick="$('#onTab').val('MONTHLIST');StatisticsPcCheckData('MONTH')"><?=$_LANG_TEXT['monthtext'][$lang_code]?></a>
				<div>
					<select name="year" onchange="StatisticsPcCheckData('MONTH')">
						<option value=""><?=$_LANG_TEXT['chooseyeartext'][$lang_code]?></option>
<?php
						for($i = 2009 ; $i <= $now_year ; $i++){

							echo "<option value='$i' ".($i==$now_year? "selected=selected" : "").">".$i.$_LANG_TEXT['yeartext'][$lang_code]."</option>";
						}
?>
					</select>
					<select name='asset_type' onchange="StatisticsPcCheckData('MONTH')">
						<option value=''><?=$_LANG_TEXT["devicealltext"][$lang_code];?></option>
						<?
						foreach($_CODE['asset_type'] as $key => $name){
							echo "<option value='".$key."' ".($asset_type==$key ? "selected" : "").">".$name."</option>";
						}
						?>
					</select>
					<div style="height:450px;"><canvas id="chartPcCheckMONTH" name='chartPcCheck' gubun='MONTH'/></canvas></div>
				</div>
			</li>
		</ul>
		</div><!--<div class="section01">-->

		
		<div class="section02">
			<h1><?=$_LANG_TEXT['organcheckstatustext'][$lang_code]?></h1>
			<select name="year" onchange="StatisticsPcCheckData('ORG')">
				<option value=""><?=$_LANG_TEXT['chooseyeartext'][$lang_code]?></option>
<?php
				for($i = 2009 ; $i <= $now_year ; $i++){

					echo "<option value='$i' ".($i==$now_year? "selected=selected" : "").">".$i.$_LANG_TEXT['yeartext'][$lang_code]."</option>";
				}
?>
			</select>

			<select name="month" onchange="StatisticsPcCheckData('ORG')">
				<option value="00"><?=$_LANG_TEXT['alltext'][$lang_code]?></option>
<?php
				for($i = 1 ; $i <= 12 ; $i++){
					
					$month = strlen($i)==1 ? "0".$i : $i;

					echo "<option value='$month' ".($month==$now_month? "selected=selected" : "").">".$_CODE['month'][$month]."</option>";
				}
?>
			</select>
			<select name='asset_type' style='width:100px' onchange="StatisticsPcCheckData('ORG')">
				<option value=''><?=$_LANG_TEXT["devicealltext"][$lang_code];?></option>
				<?
				foreach($_CODE['asset_type'] as $key => $name){
					echo "<option value='".$key."' ".($asset_type==$key ? "selected" : "").">".$name."</option>";
				}
				?>
			</select>
			<select id="org_check_result" name="org_check_result" onchange="StatisticsPcCheckData('ORG')">
				<option value='CHECK'><?=$_LANG_TEXT['checktext'][$lang_code]?></option> 
				<option value='WEAK'><?=$_LANG_TEXT['weaknessdetectiontext'][$lang_code]?></option>
				<option value='VIRUS'><?=$_LANG_TEXT['virusdetectiontext'][$lang_code]?></option>
			</select>
			<div class="ch"><canvas id="chartPcCheckORG"  name="chartPcCheck" gubun="ORG"  width="260px" height="260px"/></canvas></div>
			<div class='ch_legend'><div id="chartPcCheckORG_legend" class="chart-legend"></div></div>
		</div><!--<div class="section02">-->

		<div class="section03">
			<h1><?=$_LANG_TEXT['deptcheckstatustext'][$lang_code]?></h1>
			<select name="year" onchange="StatisticsPcCheckData('DEPT')">
				<option value=""><?=$_LANG_TEXT['chooseyeartext'][$lang_code]?></option>
<?php
				for($i = 2009 ; $i <= $now_year ; $i++){

					echo "<option value='$i' ".($i==$now_year? "selected=selected" : "").">".$i.$_LANG_TEXT['yeartext'][$lang_code]."</option>";
				}
?>
			</select>

			<select name="month" onchange="StatisticsPcCheckData('DEPT')">
				<option value="00"><?=$_LANG_TEXT['alltext'][$lang_code]?></option>
<?php
				for($i = 1 ; $i <= 12 ; $i++){
					
					$month = strlen($i)==1 ? "0".$i : $i;

					echo "<option value='$month' ".($month==$now_month? "selected=selected" : "").">".$_CODE['month'][$month]."</option>";
				}
?>
			</select>
			<select name='asset_type' style='width:100px' onchange="StatisticsPcCheckData('DEPT')">
				<option value=''><?=$_LANG_TEXT["devicealltext"][$lang_code];?></option>
				<?
				foreach($_CODE['asset_type'] as $key => $name){
					echo "<option value='".$key."' ".($asset_type==$key ? "selected" : "").">".$name."</option>";
				}
				?>
			</select>
			<select id="dept_check_result" name="dept_check_result" onchange="StatisticsPcCheckData('DEPT')">
				<option value='CHECK'><?=$_LANG_TEXT['checktext'][$lang_code]?></option> 
				<option value='WEAK'><?=$_LANG_TEXT['weaknessdetectiontext'][$lang_code]?></option>
				<option value='VIRUS'><?=$_LANG_TEXT['virusdetectiontext'][$lang_code]?></option>
			</select>
			<div class="ch"><canvas id="chartPcCheckDEPT" name="chartPcCheck" gubun="DEPT" width="260px" height="260px"/></canvas></div>
			<div class='ch_legend'><div id="chartPcCheckDEPT_legend" class="chart-legend"></div></div>
		</div><!--<div class="section03">-->

		<div class="section04">
			<h1><?=$_LANG_TEXT['weaknessstatustext'][$lang_code]?></h1>
			<select name="year" onchange="StatisticsPcCheckData('WEAK')">
				<option value=""><?=$_LANG_TEXT['chooseyeartext'][$lang_code]?></option>
<?php
				for($i = 2009 ; $i <= $now_year ; $i++){

					echo "<option value='$i' ".($i==$now_year? "selected=selected" : "").">".$i.$_LANG_TEXT['yeartext'][$lang_code]."</option>";
				}
?>
			</select>

			<select name="month" onchange="StatisticsPcCheckData('WEAK')">
				<option value="00"><?=$_LANG_TEXT['alltext'][$lang_code]?></option>
<?php
				for($i = 1 ; $i <= 12 ; $i++){
					
					$month = strlen($i)==1 ? "0".$i : $i;

					echo "<option value='$month' ".($month==$now_month? "selected=selected" : "").">".$_CODE['month'][$month]."</option>";
				}
?>
			</select>
			<select name='asset_type' style='width:100px' onchange="StatisticsPcCheckData('WEAK')">
				<option value=''><?=$_LANG_TEXT["devicealltext"][$lang_code];?></option>
				<?
				foreach($_CODE['asset_type'] as $key => $name){
					echo "<option value='".$key."' ".($asset_type==$key ? "selected" : "").">".$name."</option>";
				}
				?>
			</select>
			<div class="ch"><canvas id="chartPcCheckWEAK"  name="chartPcCheck" gubun="WEAK"  width="260px" height="260px"/></canvas></div>
			<div class='ch_legend'><div id="chartPcCheckWEAK_legend" class="chart-legend"></div></div>
		</div><!--<div class="section04">-->

		<div class="section05">
			<h1><?=$_LANG_TEXT['virusdectionstatustext'][$lang_code]?></h1>
			<select name="year" onchange="StatisticsPcCheckData('VIRUS')">
				<option value=""><?=$_LANG_TEXT['chooseyeartext'][$lang_code]?></option>
<?php
				for($i = 2009 ; $i <= $now_year ; $i++){

					echo "<option value='$i' ".($i==$now_year? "selected=selected" : "").">".$i.$_LANG_TEXT['yeartext'][$lang_code]."</option>";
				}
?>
			</select>

			<select name="month" onchange="StatisticsPcCheckData('VIRUS')">
				<option value="00"><?=$_LANG_TEXT['alltext'][$lang_code]?></option>
<?php
				for($i = 1 ; $i <= 12 ; $i++){
					
					$month = strlen($i)==1 ? "0".$i : $i;

					echo "<option value='$month' ".($month==$now_month? "selected=selected" : "").">".$_CODE['month'][$month]."</option>";
				}
?>
			</select>
			<select name='asset_type' style='width:100px' onchange="StatisticsPcCheckData('VIRUS')">
				<option value=''><?=$_LANG_TEXT["devicealltext"][$lang_code];?></option>
				<?
				foreach($_CODE['asset_type'] as $key => $name){
					echo "<option value='".$key."' ".($asset_type==$key ? "selected" : "").">".$name."</option>";
				}
				?>
			</select>
			<div class="ch"><canvas id="chartPcCheckVIRUS" name="chartPcCheck" gubun="VIRUS" width="260px" height="260px"/></canvas></div>
			<div class='ch_legend'><div id="chartPcCheckVIRUS_legend" class="chart-legend"></div></div>
		</div><!--<div class="section05">-->
		
		<div class='clear'></div>

		</div><!--<div class="container">-->

	</div><!--<div class="outline" style='border:1px solid red;position:relative;'>-->
</div>

<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>