<?php
$page_name = "report";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
include_once $_server_path . "/" . $_site_path . "/inc/header.inc";

$title = $_REQUEST[title];
$reporter = $_REQUEST[reporter];
$reportdate = $_REQUEST[reportdate];
$printdate1 = $_REQUEST[printdate1];
$printdate2 = $_REQUEST[printdate2];
$vcs_status = $_REQUEST[vcs_status];
$stat_unit = $_REQUEST[stat_unit];
$options = $_REQUEST[options];
$preview_yn = $_REQUEST[preview_yn];

$now_year = date("Y");
$now_month = date("m");
$today = date("Y-m-d");

if($printdate1=="" && $printdate2==""){
	$reportyear = $now_year;
	$reportmonth = $now_month;
}else{
	$reportyear = substr($printdate1,0,4);
	$reportmonth = substr($printdate1,5,2);
}

$daily_vcs_status_year = $reportyear;
$daily_vcs_status_month = $reportmonth;

$monthly_vcs_status_year = $reportyear;

$daily_dvcs_status_year = $reportyear;
$daily_dvcs_status_month = $reportmonth;

$monthly_dvcs_status_year = $reportyear;

$weak_status_year = $reportyear;
$weak_status_month = $reportmonth;

$virus_status_year = $reportyear;
$virus_status_month = $reportmonth;

if($title==""){
	$report_title = $_LANG_TEXT["reporttitletext"][$lang_code];
	$report_title = str_replace("{yyyy}",$reportyear,$report_title);
}else{
	$report_title = $title;
}

if($reporter =="") $reporter = $_ck_user_name;
if($reportdate == "") $reportdate = $today;
if($printdate1 == "") $printdate1 = $today;
if($printdate2 == "") $printdate2 = $today;

//통계
if(is_array($stat_unit)){

	$str_stat_unit = implode(',',$stat_unit);

	if(in_array("M",$stat_unit)) $stat_unit_month_checked = "checked";
	if(in_array("D",$stat_unit)) $stat_unit_day_checked = "checked";

}else{
	
	$stat_unit = array();
}

if(empty($preview_yn)){
	$stat_unit_month_checked = "checked";
}

//출력옵션
if(is_array($options)){

	$str_options = implode(',',$options);

	if(in_array("VCS_STAT",$options)) $option_vcs_stat_checked = "checked";
	if(in_array("WV_STAT",$options)) $option_wv_stat_checked = "checked";
	if(in_array("DVCS_STAT",$options)) $option_dvcs_stat_checked = "checked";
	if(in_array("CVCS_STAT",$options)) $option_cvcs_stat_checked = "checked";
	if(in_array("VCS_LIST",$options)) $option_vcs_list_checked = "checked";
	if(in_array("DVCS_LIST",$options)) $option_dvcs_list_checked = "checked";
	if(in_array("WV_LIST",$options)) $option_wv_list_checked = "checked";

}else{

	$options = array();
}

?>
<script language="javascript">
$("document").ready(function(){

	$("#reportdate").datepicker(pickerOpts);
	$("#printdate1").datepicker(pickerOpts);
	$("#printdate2").datepicker(pickerOpts);
	
	var stat_unit_day_checked = document.all.stat_unit_day.checked;
	var stat_unit_month_checked = document.all.stat_unit_month.checked;
	var option_dvcs_stat_checked = document.all.option_dvcs_stat.checked;
	var option_cvcs_stat_checked = document.all.option_cvcs_stat.checked;
	var option_vcs_list_checked = document.all.option_vcs_list.checked;
	var option_dvcs_list_checked = document.all.option_dvcs_list.checked;
	var option_wv_list_checked = document.all.option_wv_list.checked;
	
	if(stat_unit_day_checked) ReportStatisticsPcCheckData('DAY');
	if(stat_unit_month_checked) ReportStatisticsPcCheckData('MONTH');
	if(stat_unit_day_checked && option_dvcs_stat_checked) ReportStatisticsPcCheckData('DAY_DEVICE');
	if(stat_unit_month_checked && option_dvcs_stat_checked) ReportStatisticsPcCheckData('MONTH_DEVICE');
	
	ReportStatisticsPcCheckData('WEAK');
	ReportStatisticsPcCheckData('VIRUS');

	var printdate1 = $("#printdate1").val();
	var printdate2 = $("#printdate2").val();
	var status = $("#vcs_status").val();
	
	if(option_cvcs_stat_checked){
		LoadPageDataList('report_com_vcs_list',SITE_NAME+'/stat/get_report_com_vcs_list.php',"enc="+ParamEnCoding('checkdate1='+printdate1+'&checkdate2='+printdate2+'&status='+status+"&$paging=99999&src=REPORT"));
	}
	
	if(option_vcs_list_checked){
		LoadPageDataList('report_vcs_list',SITE_NAME+'/stat/get_report_vcs_list.php',"enc="+ParamEnCoding('checkdate1='+printdate1+'&checkdate2='+printdate2+'&status='+status+"&src=REPORT"));
	}

	if(option_dvcs_list_checked){
		LoadPageDataList('report_device_vcs_list',SITE_NAME+'/stat/get_report_device_vcs_list.php',"enc="+ParamEnCoding('checkdate1='+printdate1+'&checkdate2='+printdate2+'&status='+status+"&src=REPORT"));
	}
	
	if(option_wv_list_checked){

		LoadPageDataList('report_weak_list',SITE_NAME+'/stat/get_report_weak_list.php',"enc="+ParamEnCoding('checkdate1='+printdate1+'&checkdate2='+printdate2+'&status='+status+"&src=REPORT"));

		LoadPageDataList('report_virus_list',SITE_NAME+'/stat/get_report_virus_list.php',"enc="+ParamEnCoding('checkdate1='+printdate1+'&checkdate2='+printdate2+'&status='+status+"&src=REPORT"));
	}

});
function ReportExcelDown(flag){

	if(flag != "") {

		$('#viewLoading').css('position', 'absolute');
		$('#viewLoading').css('margin', '2px');
		$('#viewLoading').css('left', $('#'+flag).offset().left);
		$('#viewLoading').css('top', $('#'+flag).offset().top);
		$('#viewLoading').css('width', $('#'+flag).css('width'));
		$('#viewLoading').css('height', $('#'+flag).css('height'));
		$('#viewLoading').fadeIn(500);
	}
	
	var frm = document.frmPrint;

	frm.method = "POST";
	frm.action = SITE_NAME+"/stat/report_excel.php";
	frm.submit();

	if(flag != "") {
		$('#viewLoading').fadeOut(500);
	}

	//ExcelDown('<?=$excel_down_url?>','btnexcelDown')
}
</script>
<?php
include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";
?>

<div id="statistics_report">
	<div class="outline">
		<div class="container">

			<div id="tit_area">
				<div class="tit_line">
					 <h1><span id='page_title'><?=$_LANG_TEXT["m_report"][$lang_code];?></span></h1>
				</div>
				<span class="line"></span>
			</div>
			

			<div class="tit">
				<?=$_LANG_TEXT["reportprintsettext"][$lang_code];?>
			</div>

			<FORM name='frmPrint' id='frmPrint' method='POST' action="<?=$_SERVER['PHP_SELF']?>">
			<input type='hidden' id='preview_yn' name='preview_yn' value='Y'>
			<table class="view">
				<tr>
					<th><?=$_LANG_TEXT["titletext"][$lang_code];?></th>
					<td colspan='3'><input type="text" name="title" id="title" class="frm_input" style="width:700px" value="<?=$report_title?>"></td>
				</tr>
				<tr class="bg">
					<th style='min-width:150px'><?=$_LANG_TEXT["reportertext"][$lang_code];?></th>
					<td style='min-width:300px'><input type="text" name="reporter" id="reporter" class="frm_input" style="width:280px" maxlength="30" value="<?=$reporter?>"></td>
					<th class="line" style='min-width:150px'><?=$_LANG_TEXT["reportdatetext"][$lang_code];?></th>
					<td style='min-width:300px'><input type="text" name="reportdate" id="reportdate" class="frm_input" value="<?=$reportdate?>" placeholder="" style="width:90px" maxlength="10"></td>
				</tr>
				<tr>
					<th><?=$_LANG_TEXT["printperiodtext"][$lang_code];?> <?=$title_pw_word?></th>
					<td>
						<input type="text" name="printdate1" id="printdate1" class="frm_input" value="<?=$printdate1?>" placeholder="" style="width:90px" maxlength="10"> ~ 
						<input type="text" name="printdate2" id="printdate2" class="frm_input" value="<?=$printdate2?>" placeholder="" style="width:90px" maxlength="10">
					</td>
					<th class="line"><?=$_LANG_TEXT["checktext"][$lang_code];?> <?=$_LANG_TEXT["progressstatustext"][$lang_code];?></th>
					<td>
						<select id='vcs_status' name='vcs_status'>
							<option value=''><?=$_LANG_TEXT["alltext"][$lang_code];?></option>
						<?
						foreach($_CODE['vcs_status'] as $key => $name){
							echo "<option value='".$key."' ".($vcs_status==$key ? "selected" : "").">".$name."</option>";
						}
						?>
						</select>
					</td>
				</tr>
				<tr class="bg">
					<th><?=$_LANG_TEXT["statisticstext"][$lang_code];?></th>
					<td colspan='3'>
						<input type='checkbox' name='stat_unit[]' id='stat_unit_month' value='M' <?=$stat_unit_month_checked?>> <label for='stat_unit_month'><?=$_LANG_TEXT["monthlytext"][$lang_code];?></label> 
						<input type='checkbox' name='stat_unit[]' id='stat_unit_day' value='D' <?=$stat_unit_day_checked?>> <label for='stat_unit_day'><?=$_LANG_TEXT["dailytext"][$lang_code];?></label> 
					</td>
				</tr>
				<tr>
					<th><?=$_LANG_TEXT["printoptiontext"][$lang_code];?></th>
					<td colspan='3'>
						<input type='checkbox' name='options[]' id='option_vcs_stat' value='VCS_STAT' <?=$option_vcs_stat_checked?>> <label for='option_vcs_stat'><?=$_LANG_TEXT["checkstatustext"][$lang_code];?></label> 
						<input type='checkbox' name='options[]' id='option_dvcs_stat' value='DVCS_STAT'  <?=$option_dvcs_stat_checked?>> <label for='option_dvcs_stat'><?=$_LANG_TEXT["checkdevicestatustext"][$lang_code];?></label> 
						<input type='checkbox' name='options[]' id='option_wv_stat' value='WV_STAT' <?=$option_wv_stat_checked?>> <label for='option_wv_stat'><?=$_LANG_TEXT["weaknessnvirusstatustext"][$lang_code];?></label>
						<input type='checkbox' name='options[]' id='option_cvcs_stat' value='CVCS_STAT'  <?=$option_cvcs_stat_checked?>> <label for='option_cvcs_stat'><?=$_LANG_TEXT["usercompanycheckstatustext"][$lang_code];?></label> 
						<input type='checkbox' name='options[]' id='option_vcs_list' value='VCS_LIST'  <?=$option_vcs_list_checked?>> <label for='option_vcs_list'><?=$_LANG_TEXT["checklisttext"][$lang_code];?></label> 
						<input type='checkbox' name='options[]' id='option_dvcs_list' value='DVCS_LIST'  <?=$option_dvcs_list_checked?>> <label for='option_dvcs_list'><?=$_LANG_TEXT["checkdevicelisttext"][$lang_code];?></label> 
						<input type='checkbox' name='options[]' id='option_wv_list' value='WV_LIST'  <?=$option_wv_list_checked?>> <label for='option_wv_list'><?=$_LANG_TEXT["weaknessnviruslisttext"][$lang_code];?></label> 
					</td>
				</tr>
			</table>
			
			<div class="btn_wrap">
				<? 
					$param_enc = ParamEnCoding($param.(($orderby)? "&orderby=".$orderby : ""));
					$excel_down_url = $_www_server."/stat/report_excel.php?enc=".$param_enc;
				?>
				<div class="right">
					<a href="javascript:" onclick="ReportPreview();" class='btn'><?=$_LANG_TEXT["btnpreview"][$lang_code];?></a>
					<a href="javascript:" class='btn' onclick="CallReportPrint();"><?=$_LANG_TEXT["btnprint"][$lang_code];?></a>

					<a href="javascript:" id='btnexcelDown' onclick="ReportExcelDown('btnexcelDown');" class="btnexcel required-print-auth hide" ><?=$_LANG_TEXT["btnexceldownload"][$lang_code];?></a>
				</div>
			</div>

			<?
			if($preview_yn=="Y"){

				include "./inc_report.php";
			}
			?>
			</FORM>
		</div>
	</div>
</div>

<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>