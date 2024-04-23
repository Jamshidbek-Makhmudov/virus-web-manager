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

?>
<style>
@media print{
	
	body{
	  -webkit-print-color-adjust: exact;
	  print-color-adjust: exact;
	  background-color:#000;
	  width: 210mm;
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
			<input type='hidden' name='printdate1' id='printdate1' value='<?=$printdate1?>'>
			<input type='hidden' name='printdate2' id='printdate2' value='<?=$printdate2?>'>
			<input type='hidden' name='scan_center_code' id='scan_center_code' value='<?=$scan_center_code?>'>
		</FORM>
		<div>
			<img src='<? echo $report_image_data;?>' style='width:100%'>
		</div>
	</div>
</div>