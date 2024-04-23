<?php
$page_name = "user_pc_stat";
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
	StatisticsUserPcData();	
});

</script>
<?php
include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";
?>

<div id="statistics_user_pc">
	<div class="outline">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">

				 <h1><span id='page_title'><?=$_LANG_TEXT["m_statistics_pc"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		
		<div class="container_chart">
		<div style="height:400px">
			<div class="section01">
				<h1><?=$_LANG_TEXT['pcosstatustext'][$lang_code]?></h1>
				
				<div class="ch"><canvas id="StatisticsUserPcOS"  name="StatisticsUserPc" gubun="OS"  width="260px" height="260px"/></canvas></div>
				<div class='ch_legend'><div id="StatisticsUserPcOS_legend" class="chart-legend"></div></div>
			</div>

			<div class="section02">
				<h1><?=$_LANG_TEXT['pcmanufacturertext'][$lang_code]?></h1>
				
				<div class="ch"><canvas id="StatisticsUserPcMAKER" name="StatisticsUserPc" gubun="MAKER" width="260px" height="260px"/></canvas></div>
				<div class='ch_legend'><div id="StatisticsUserPcMAKER_legend" class="chart-legend"></div></div>
			</div>
		</div>
		</div>

	</div>
	</div>
</div>

<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>