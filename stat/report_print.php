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

$now_year = date("Y");
$now_month = date("m");
$today = date("Y-m-d");

$title = $_REQUEST[title];
$reporter = $_REQUEST[reporter];
$reportdate = $_REQUEST[reportdate];
$printdate1 = $_REQUEST[printdate1];
$printdate2 = $_REQUEST[printdate2];
$vcs_status = $_REQUEST[vcs_status];
$stat_unit = $_REQUEST[stat_unit];
$options = $_REQUEST[options];

if(empty($stat_unit)) $stat_unit = array();
if(empty($options)) $options = array();

$daily_vcs_status_year = $_REQUEST[daily_vcs_status_year];
$daily_vcs_status_month = $_REQUEST[daily_vcs_status_month];

$monthly_vcs_status_year = $_REQUEST[monthly_vcs_status_year];

$daily_dvcs_status_year = $_REQUEST[daily_dvcs_status_year];
$daily_dvcs_status_month = $_REQUEST[daily_dvcs_status_month];

$monthly_dvcs_status_year = $_REQUEST[monthly_dvcs_status_year];

$weak_status_year = $_REQUEST[weak_status_year];
$weak_status_month = $_REQUEST[weak_status_month];

$virus_status_year = $_REQUEST[virus_status_year];
$virus_status_month = $_REQUEST[virus_status_month];


if($daily_vcs_status_year=="") $daily_vcs_status_year = $now_year;
if($daily_vcs_status_month=="") $daily_vcs_status_month = $now_month;
if($monthly_vcs_status_year=="") $monthly_vcs_status_year = $now_year;
if($daily_dvcs_status_year=="") $daily_dvcs_status_year = $now_year;
if($daily_dvcs_status_month=="") $daily_dvcs_status_month = $now_month;
if($monthly_dvcs_status_year=="") $monthly_dvcs_status_year = $now_year;
if($weak_status_year=="") $weak_status_year = $now_year;
if($weak_status_month=="") $weak_status_month = $now_month;
if($virus_status_year=="") $virus_status_year = $now_year;
if($virus_status_month=="") $virus_status_month = $now_month;

?>
<style>
@media print{
	
	body{
	  -webkit-print-color-adjust: exact;
	  print-color-adjust: exact;
	  background-color:#000;
	  width: 100%;
	  margin: 0;
	  padding: 0;
	}

	#cover {
		page-break-after: always;
	}

	#report_area .page_devide_line:nth-child(4n){ 
		page-break-after: always; /*한페이지에 2개 chart 출력되도록 설정*/
	}
}

</style>
<script language="javascript">

$("document").ready(function(){

	var stat_unit_day_checked = <?=(in_array("D",$stat_unit))? "true" : "false";?>;
	var stat_unit_month_checked = <?=(in_array("M",$stat_unit))? "true" : "false";?>;
	var option_vcs_stat_checked = <?=(in_array("VCS_STAT",$options))? "true" : "false";?>;
	var option_dvcs_stat_checked = <?=(in_array("DVCS_STAT",$options))? "true" : "false";?>;
	var option_wv_stat_checked = <?=(in_array("WV_STAT",$options))? "true" : "false";?>;
	var option_cvcs_stat_checked = <?=(in_array("CVCS_STAT",$options))? "true" : "false";?>;
	var option_vcs_list_checked = <?=(in_array("VCS_LIST",$options))? "true" : "false";?>;
	var option_dvcs_list_checked = <?=(in_array("DVCS_LIST",$options))? "true" : "false";?>;
	var option_wv_list_checked = <?=(in_array("WV_LIST",$options))? "true" : "false";?>;

	
	if(stat_unit_day_checked && option_vcs_stat_checked) ReportStatisticsPcCheckData('DAY');
	if(stat_unit_month_checked && option_vcs_stat_checked) ReportStatisticsPcCheckData('MONTH');
	if(stat_unit_day_checked && option_dvcs_stat_checked) ReportStatisticsPcCheckData('DAY_DEVICE');
	if(stat_unit_month_checked && option_dvcs_stat_checked) ReportStatisticsPcCheckData('MONTH_DEVICE');
	
	if(option_wv_stat_checked) {
		ReportStatisticsPcCheckData('WEAK');
		ReportStatisticsPcCheckData('VIRUS');
	}

	var printdate1 = "<?=$printdate1?>";
	var printdate2 = "<?=$printdate2?>";
	var status = "<?=$vcs_status?>";
	
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

	$("#cover_print").click(function(){
		
		if(document.all.cover_print.checked){
			$("#cover").show();
		}else{
			$("#cover").hide();
		}
	});

	$("#btnPrint").click(function(){

		$("#print_options").hide();
		window.print();
		$("#print_options").show();
	
	});
	
});
</script>
<div id="statistics_report">
	<div class="container">
		<div id='print_options' class='print_options'>
			<input type='checkbox' id='cover_print' name='cover_print'> <label for='cover_print'><?=$_LANG_TEXT["reportcoverprinttext"][$lang_code];?></label> <a href="javascript:#" id='btnPrint'><img src="<?=$_www_server?>/images/print.png"></a>
		</div>
		<div id='cover' class='cover' style='display:none'>
			<div class='title'><?=$title?></div>
			<div class='mark'>
				<?if($_logo_img_report){?><img src="<?=$_www_server?>/images/<?=$_logo_img_report?>"><?}?>
			</div>
			<div class='descript'>
				<div><?=$_LANG_TEXT["reportertext"][$lang_code];?> : <?=$reporter?></div>
				<div><?=$_LANG_TEXT["reportdatetext"][$lang_code];?> : <?=$reportdate?></div>
			</div>
		</div>
		<FORM name='frmPrint' id='frmPrint' method='POST'>
			<input type='hidden' name='vcs_status' id='vcs_status' value='<?=$vcs_status?>'>
		</FORM>
		<?include "./inc_report.php";?>
	</div>
</div>