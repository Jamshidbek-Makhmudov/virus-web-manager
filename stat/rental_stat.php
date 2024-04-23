<?php
$page_name = "rental_stat";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI']) - 1);
$_apos = stripos($_REQUEST_URI,  "/");
if ($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
include_once $_server_path . "/" . $_site_path . "/inc/header.inc";

$searchopt = $_REQUEST[searchopt];	// 검색옵션
$searchkey = $_REQUEST[searchkey];	// 검색어
$year = $_REQUEST[year];	
$month = $_REQUEST[month];	
$scan_center_code  = $_REQUEST[scan_center_code];	

if($year=="") $year = date("Y");
if($month=="") $month = date("m");

$param = "";
if ($searchopt != "") $param .= ($param == "" ? "" : "&") . "searchopt=" . $searchopt;
if ($searchkey != "") $param .= ($param == "" ? "" : "&") . "searchkey=" . $searchkey;
if ($year != "") $param .= ($param == "" ? "" : "&") . "year=" . $year;
if ($month != "") $param .= ($param == "" ? "" : "&") . "month=" . $month;
if ($scan_center_code != "") $param .= ($param == "" ? "" : "&") . "scan_center_code=" . $scan_center_code;

?>
<script language="javascript">
	$("document").ready(function() {
		loadStatisticsRentalStatusDailyChart();
	});
		
</script>
<?php
include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";

//검색 로그 기록
$proc_name = $_POST['proc_name'];
if($proc_name != ""){
	$work_log_seq = WriteAdminActLog($proc_name,'SEARCH');
}

$Model_Stat=new Model_Stat();

?>

<div id="statistics_check">
	<div class="outline">
		<div class="container">

			<div id="tit_area">
				<div class="tit_line">
					 <h1><span id='page_title'><? echo trsLang('물품대여현황','item_rental_status'); ?> <small><? echo trsLang('일별','dailytext');?></small></span></h1>
				</div>
				<span class="line"></span>
			</div>

				<ul class="tab">
					<li class="on">
						<a href='<? echo $_www_server?>/stat/rental_stat.php' ><?= $_LANG_TEXT['daytext'][$lang_code] ?></a>
					</li>
					<li>
						<a href='<? echo $_www_server?>/stat/rental_stat_month.php' ><?= $_LANG_TEXT['monthtext'][$lang_code] ?></a>
					</li>
				</ul>

				<!--검색-->
				<form name="searchForm" id="searchForm" action="<? echo basename(__FILE__);?>" method="POST">
				<input type='hidden' name='proc_name' id='proc_name'>
					<table class="search">
						<!-- 날짜 -->
						<tr>
							<th style="color:#737296;"><?= $_LANG_TEXT['periodtext'][$lang_code] ?> </th>
							<td style='padding:5px 13px'>

								<select style="min-width:120px;" name="year" id='year' >
									<option value=""><?= $_LANG_TEXT['chooseyeartext'][$lang_code] ?></option>
									<?php
									$base_year = date("Y");
									for ($i = $base_year-3; $i <= $base_year; $i++) {

										echo "<option value='$i' " . ($i == $year ? "selected=selected" : "") . ">" . $i . $_LANG_TEXT['yeartext'][$lang_code] . "</option>";
									}
									?>
								</select>

								<select name="month" id="month" >
									<option value=""><?= $_LANG_TEXT['choosemonthtext'][$lang_code] ?></option>
									<?php
									for ($i = 1; $i <= 12; $i++) {

										$str_month = sprintf('%02d',$i);

										echo "<option value='$str_month' " . ($str_month == $month ? "selected=selected" : "") . ">" . $_CODE['month'][$str_month] . "</option>";
									}
									?>
								</select>
								<div class="col head">
									<? echo trsLang('검사장','scancentertext');?>
								</div>
								<div class="col">
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
								</div>
							</td>
						</tr>

						<tr>
							<!-- 검색 -->
							<?php 
							//검색키워드목록
							$searchopt_list = array(
								"v_user_name"=>trsLang("이름","nametext")
								,"v_user_belong"=>trsLang("소속","belongtext")
							);
							?>
							<th style="color:#737296;"><?= $_LANG_TEXT['keywordsearchtext'][$lang_code] ?> </th>

							<td style=''>
							<select name="searchopt" id="searchopt" style='width:120px;margin-top:1px;'>
							<option value="" <?php if($searchopt == "") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['select_search_item'][$lang_code]?></option>
							<?
							foreach($searchopt_list as $key=>$name){
								$selected = $searchopt==$key ? "selected" : "";
								echo "<option value='{$key}' {$selected} >{$name}</option>";
							}
							?>
						</select>


								<input type="text" class="frm_input" style="width:50%" name="searchkey" id="searchkey" value="<?= $searchkey ?>" maxlength="50">
								<input type="submit" value="<?= $_LANG_TEXT['usersearchtext'][$lang_code] ?>" class="btn_submit" onclick=" SearchSubmit(document.searchForm); ">

							</td>
						</tr>
					</table>
				</form>
				
				<!--title-->
				<?
					$title = array();
					$title[] = $year.trsLang("년","yeartext")." ".$_CODE['month'][$month];
					$title[] = $search_scan_center_name;
					$title[] = $searchkey;
					$title[]=  trsLang('일별물품대여현황','dailyrentalstatus');
					$str_title = implode(" ",$title);
				?>
				<div class="sub_tit">
					> <? echo $str_title;?>
				</div> 

				<!--chart-->
				<div class="section01" >
					<div style="height:450px;"><canvas id="chartRentalDay" name='chartRentalDay' /></canvas></div>
					
					<div id='chartRentalDay_DataTable' >
						<!--chart data table-->
					</div>
					
					<!-- excel download -->
					<div class="btn_wrap right" style='margin-top:50px;'>
						<? 
							$excel_down_url = $_www_server . "/stat/rental_stat_excel.php?enc=" . ParamEnCoding($param); 
							$excel_name = $str_title;
						?>
						<div class="right">
							<a  href="javascript:void(0)" class="btnexcel required-print-auth hide" onclick="getHTMLSplit('<? echo $total = 100 ?>','<?= $excel_down_url ?>','<?= $excel_name ?>',this);"><?= $_LANG_TEXT["btnexceldownload"][$lang_code]; ?></a>
						</div>
					</div>

				</div>

			</div>
							
				</div> 
			<div class='clear'></div>
		</div>

</div>

<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>