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
$report_image_data = $_REQUEST[report_image_data];

$printdate1 = $_REQUEST[printdate1];
$printdate2 = $_REQUEST[printdate2];
$scan_center_code = $_REQUEST[scan_center_code];
$options = $_REQUEST[options];
$mode = $_POST[mode];

//chart image data
$DAILY_VISIT_STAT_IMG_DATA = $_POST[DAILY_VISIT_STAT_IMG_DATA];
$DAILY_VCS_STAT_IMG_DATA = $_POST[DAILY_VCS_STAT_IMG_DATA];
$VCS_RESULT_STAT_IMG_DATA = $_POST[VCS_RESULT_STAT_IMG_DATA];


//출력옵션
$print_option = array(
	"DAILY_VISIT_STAT"=>trsLang("일별출입통계",'dailyvisitstatisticstext')
	,"VISIT_LIST"=>trsLang("출입내역",'entryExitHistory')
	,"DAILY_VCS_STAT"=>trsLang("일별점검통계",'dailyscanstatisticstext')
	,"VCS_LIST"=>trsLang("점검내역",'checklisttext')
	,"VCS_RESULT_STAT"=>trsLang("점검결과통계",'scanresultstatisticstext')
	,"BAD_FILE_LIST"=>trsLang("위변조의심내역",'badextentionlisttext')
	,"VIRUS_FILE_LIST"=>trsLang("악성코드내역",'viruslisttext')
);

if(empty($options)) $options = array();

//인쇄 로그 기록
$proc_name = $_POST['proc_name'];
if($proc_name != ""){
	$work_log_seq = WriteAdminActLog($proc_name,'PRINT');
}
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
<script type="text/javascript" src="<?php echo $_js_server ?>/html2canvas.min.js"></script>
<script language="javascript">

	$("document").ready(function(){
		
		
		var OPT_DAILY_VISIT_STAT_CHECKED = <?=(in_array("DAILY_VISIT_STAT",$options))? "true" : "false";?>;
		var OPT_VISIT_LIST_CHECKED = <?=(in_array("VISIT_LIST",$options))? "true" : "false";?>;
		var OPT_DAILY_VCS_STAT_CHECKED = <?=(in_array("DAILY_VCS_STAT",$options))? "true" : "false";?>;
		var OPT_VCS_LIST_CHECKED = <?=(in_array("VCS_LIST",$options))? "true" : "false";?>;
		var OPT_VCS_RESULT_STAT_CHECKED = <?=(in_array("VCS_RESULT_STAT",$options))? "true" : "false";?>;
		var OPT_BAD_FILE_LIST_CHECKED = <?=(in_array("BAD_FILE_LIST",$options))? "true" : "false";?>;
		var OPT_VIRUS_FILE_LIST_CHECKED = <?=(in_array("VIRUS_FILE_LIST",$options))? "true" : "false";?>;

		var printdate1 = $("#printdate1").val();
		var printdate2 = $("#printdate2").val();
		var scan_center_code = $("#scan_center_code").val();
		var param_enc = ParamEnCoding('start_date='+printdate1+'&end_date='+printdate2+'&scan_center_code='+scan_center_code);

		$(".section01").width("97%");

		//일별 출입통계
		if(OPT_DAILY_VISIT_STAT_CHECKED) {
			LoadReportVisitStat();
			//$("#DAILY_VISIT_STAT_wrap").html("<img src='<? echo $DAILY_VISIT_STAT_IMG_DATA;?>' width='100%'>");
		}

		//출입내역
		if(OPT_VISIT_LIST_CHECKED) {
			LoadPageDataList('visit_list',SITE_NAME+'/stat/get_report_visit_list.php',"enc="+param_enc);
		}

		//일별 점검통계
		if(OPT_DAILY_VCS_STAT_CHECKED) {
			LoadReportVcsStat();
			//$("#DAILY_VCS_STAT_wrap").html("<img src='<? echo $DAILY_VCS_STAT_IMG_DATA;?>' width='100%'>");
		}

		//점검내역
		if(OPT_VCS_LIST_CHECKED) {
			LoadPageDataList('vcs_list',SITE_NAME+'/stat/get_report_vist_vcs_list.php',"enc="+param_enc);
		}

		//점검결과통계
		if(OPT_VCS_RESULT_STAT_CHECKED) {
			LoadReporVcsResultStat();
			//$("#VCS_RESULT_STAT_wrap").html("<img src='<? echo $VCS_RESULT_STAT_IMG_DATA;?>' width='100%'>");
		}

		//위변조의심내역
		if(OPT_BAD_FILE_LIST_CHECKED) {
			LoadPageDataList('bad_file_list',SITE_NAME+'/stat/get_report_visit_bad_file_list.php',"enc="+param_enc);
		}

		//악성코드내역
		if(OPT_VIRUS_FILE_LIST_CHECKED) {
			LoadPageDataList('virus_file_list',SITE_NAME+'/stat/get_report_visit_virus_file_list.php',"enc="+param_enc);
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
		<div id='print_options' class='print_options' >
			<input type='checkbox' id='cover_print' name='cover_print'> <label for='cover_print'><?=$_LANG_TEXT["reportcoverprinttext"][$lang_code];?></label>
			<a href="javascript:#" id='btnPrint'><img src="<?=$_www_server?>/images/print.png"></a>
		</div>
		<div id='cover' class='cover' style='display:none'>
			<div class='title'><?=$title?></div>
			<div class='mark'>
				<?if($_logo_img_report){?><img src="<?=$_www_server?>/images/<?=$_logo_img_report?>"><?}?>
			</div>
			<div class='descript' >
					<div><?=$_LANG_TEXT["reportertext"][$lang_code];?> : <?=$reporter?></div>
					<div><?=$_LANG_TEXT["reportdatetext"][$lang_code];?> : <?=$reportdate?></div>
			</div>
		</div>
		<FORM name='frmPrint' id='frmPrint' method='POST'>
			<input type='hidden' id='proc_name' name='proc_name' >
			<input type='hidden' id='mode' name='mode' value='<? echo $mode;?>'>
			<input type='hidden' name='printdate1' id='printdate1' value='<?=$printdate1?>'>
			<input type='hidden' name='printdate2' id='printdate2' value='<?=$printdate2?>'>
			<input type='hidden' name='scan_center_code' id='scan_center_code' value='<?=$scan_center_code?>'>
		</FORM>
		<?
			include "./inc_k_report.php";
		?>
	</div>
</div>