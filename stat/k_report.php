<?php
$page_name = "k_report";
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
$scan_center_code = $_REQUEST[scan_center_code];
$options = $_REQUEST[options];
$preview_yn = $_REQUEST[preview_yn];
$mode = $_REQUEST[mode];
if($mode=="") $mode = "preview";

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

if($title==""){
	$report_title = $_LANG_TEXT["reporttitletext"][$lang_code];
	$report_title = str_replace("{yyyy}",$reportyear,$report_title);
}else{
	$report_title = $title;
}

if($reporter =="") $reporter = $_ck_user_name;
if($reportdate == "") $reportdate = $today;
if($printdate1 == "") $printdate1 =  date("Y-m-d",strtotime("-30 days"));
if($printdate2 == "") $printdate2 =$today;

//검색 로그 기록
$proc_name = $_POST['proc_name'];
if($proc_name != ""){
	$work_log_seq = WriteAdminActLog($proc_name,'VIEW');
}
?>
<script type="text/javascript" src="<?php echo $_js_server ?>/html2canvas.min.js"></script>
<script language="javascript">
$("document").ready(function(){
	
	loadReport();

});
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
			<input type='hidden' id='proc_name' name='proc_name' >
			<input type='hidden' id='mode' name='mode' value='<? echo $mode;?>'>
			<input type='hidden' id='preview_yn' name='preview_yn' value='Y'>
			<input type='hidden' id='DAILY_VISIT_STAT_IMG_DATA' name='DAILY_VISIT_STAT_IMG_DATA'>
			<input type='hidden' id='DAILY_VCS_STAT_IMG_DATA' name='DAILY_VCS_STAT_IMG_DATA'>
			<input type='hidden' id='VCS_RESULT_STAT_IMG_DATA' name='VCS_RESULT_STAT_IMG_DATA'>
			<table class="view">
				<tr>
					<th><?=$_LANG_TEXT["titletext"][$lang_code];?></th>
					<td colspan='3'><input type="text" name="title" id="title" class="frm_input" style="width:700px" value="<?=$report_title?>"></td>
				</tr>
				<tr class="bg">
					<th style='min-width:150px'><?=$_LANG_TEXT["reportertext"][$lang_code];?></th>
					<td style='min-width:300px'><input type="text" name="reporter" id="reporter" class="frm_input" style="width:280px" maxlength="30" value="<?=$reporter?>"></td>
					<th class="line" style='min-width:150px'><?=$_LANG_TEXT["reportdatetext"][$lang_code];?></th>
					<td style='min-width:300px'><input type="text" name="reportdate" id="reportdate" class="frm_input datepicker" value="<?=$reportdate?>" placeholder="" style="width:90px" maxlength="10"></td>
				</tr>
				<tr>
					<th><?=$_LANG_TEXT["printperiodtext"][$lang_code];?> <?=$title_pw_word?></th>
					<td>
						<input type="text" name="printdate1" id="printdate1" class="frm_input datepicker" value="<?=$printdate1?>" placeholder="" style="width:90px" maxlength="10"> ~ 
						<input type="text" name="printdate2" id="printdate2" class="frm_input datepicker" value="<?=$printdate2?>" placeholder="" style="width:90px" maxlength="10">
						<span style='padding:10px'>(<? echo trsLang('최대31일','max31days');?>)</span>
					</td>
					<th class="line" style='min-width:150px'><? echo trsLang('검사장','scancentertext');?></th>
					<td style='min-width:300px'>
						<select name='scan_center_code' id='scan_center_code'>
							<option value=''><?=$_LANG_TEXT["alltext"][$lang_code];?></option>
							<?php
							$Model_manage = new Model_manage;
							$result = $Model_manage->getCenterList();
							
							if($result){
								while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

									$_scan_center_code = $row['scan_center_code'];
									$_scan_center_name = $row['scan_center_name'];

									if($_scan_center_code==$scan_center_code){
										$selected = "selected";
										$search_scan_center_name = $_scan_center_name;
									}else{
										$selected = "";
									}
							?>
							<option value='<?=$_scan_center_code?>' <? echo $selected ;?>
								><?=$_scan_center_name?></option>
							<?php
								}
							}
							?>
						</select>
					</td>
				</tr>
				<tr class="bg">
					<th><?=$_LANG_TEXT["printoptiontext"][$lang_code];?></th>
					<td colspan='3'>
						<?
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
						
						foreach($print_option as $option_key=>$option_name){
								
								if(is_array($options)){
									$checked = in_array($option_key,$options) ? "checked" : "";
								}
						?>
							<input type='checkbox' name='options[]'  id='OPT_<? echo $option_key;?>' value='<? echo $option_key;?>' <? echo $checked ;?>> <label for='OPT_<? echo $option_key;?>'><?=$option_name?></label>
						<?}?>
					</td>
				</tr>
			</table>
			
			<div class="btn_wrap">
				<? 
					$param_enc = ParamEnCoding($param.(($orderby)? "&orderby=".$orderby : ""));
					$excel_down_url = $_www_server."/stat/k_report_excel.php?enc=".$param_enc;
				?>
				<div class="right">
					<a href="javascript:" onclick="submitReport()" class='btn'><?=$_LANG_TEXT["btnpreview"][$lang_code];?></a>
					<a href="javascript:" class='btn required-print-auth hide' onclick="printReport();"><?=$_LANG_TEXT["btnprint"][$lang_code];?></a>

					<a href="javascript:" id='btnexcelDown' onclick="Report2ExcelDown('btnexcelDown');" class="btnexcel required-print-auth hide" ><?=$_LANG_TEXT["btnexceldownload"][$lang_code];?></a>
				</div>
			</div>

			<?
			if($preview_yn=="Y"){

				include "./inc_k_report.php";
			}
			?>
			</FORM>
		</div>
	</div>
</div>

<?php
include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";
?>