<?php
$page_name = "vcs_company_stat";
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

$searchopt = $_REQUEST[searchopt];	// 검색옵션
$searchkey = $_REQUEST[searchkey];	// 검색어
$asset_type = $_REQUEST[asset_type];
$vcs_type = $_REQUEST[vcs_type];
$checkdate1 = $_REQUEST[checkdate1];
$checkdate2 = $_REQUEST[checkdate2];
$indate1 = $_REQUEST[indate1];
$indate2 = $_REQUEST[indate2];
$status = $_REQUEST[status];

$orderby = $_REQUEST[orderby];		// 정렬순서
$page = $_REQUEST[page];			// 페이지
if($paging == "") $paging = $_paging;

if($checkdate1=="") $checkdate1 = date("Y")."-01-01";
if($checkdate2=="") $checkdate2 = date("Y-m-d");

$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($asset_type!="") $param .= ($param==""? "":"&")."asset_type=".$asset_type;
if($vcs_type!="") $param .= ($param==""? "":"&")."vcs_type=".$vcs_type;
if($checkdate1!="") $param .= ($param==""? "":"&")."checkdate1=".$checkdate1;
if($checkdate2!="") $param .= ($param==""? "":"&")."checkdate2=".$checkdate2;
if($indate1!="") $param .= ($param==""? "":"&")."indate1=".$indate1;
if($indate2!="") $param .= ($param==""? "":"&")."indate2=".$indate2;
if($status!="") $param .= ($param==""? "":"&")."status=".$status;


?>
<script language="javascript">

$("document").ready(function(){

	$("#checkdate1").datepicker(pickerOpts);
	$("#checkdate2").datepicker(pickerOpts);
	$("#indate1").datepicker(pickerOpts);
	$("#indate2").datepicker(pickerOpts);

	var onTab = $("#onTab").val();

	$(".section01 .tab li").removeClass("on");
	$(".section01 .tab li[name='"+onTab+"']").addClass("on");
	$("#onTab").val(onTab);
	
	var v_com_seq = $("#v_com_seq").val();
	CallStatisticsComCheckData(v_com_seq);
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
					 <h1><span id='page_title'><?=$_LANG_TEXT["m_statistics_result_company"][$lang_code];?></span></h1>
				</div>
				<span class="line"></span>
			</div>
			
			<div class='static_wrapper'>
				<!--chart table-->
				<div class='datatable'>
					
					<form id='searchForm' name="searchForm" action="<?php echo $_SERVER[PHP_SELF]?>" method="GET">
					<input type="hidden" name="page" value="">
					<table class="search" style='margin-top:10px;'>
						<tr>
							<th style='width:100px'><?=$_LANG_TEXT["checkperiodtext"][$lang_code];?></th>
							<td style='width:300px;'>
								<input type="text" name="checkdate1" id="checkdate1" class="frm_input" value="<?=$checkdate1?>" placeholder="" style="width:90px" maxlength="10"> ~ <input type="text" name="checkdate2" id="checkdate2" class="frm_input" value="<?=$checkdate2?>" placeholder="" style="width:90px"  maxlength="10">
							</td>
							<th style='width:100px'><?=$_LANG_TEXT["inperiodtext"][$lang_code];?></th>
							<td style='min-width:300px'>
								<input type="text" name="indate1" id="indate1" class="frm_input" value="<?=$indate1?>" placeholder="" style="width:90px" maxlength="10"> ~ <input type="text" name="indate2" id="indate2" class="frm_input" value="<?=$indate2?>" placeholder="" style="width:90px" maxlength="10">
							</td>
						</tr>
						<tr>
							<th><?=$_LANG_TEXT["checkndevicegubuntext"][$lang_code];?></th>
							<td>
								<select id='vcs_type' name='vcs_type' style='width:120px' onchange="setAssetGubun(this)">
									<option value=''><?=$_LANG_TEXT["checkgubunchoosetext"][$lang_code];?></option>
								<?
								foreach($_CODE['vcs_type'] as $key => $name){
									echo "<option value='".$key."' ".($vcs_type==$key ? "selected" : "").">".$name."</option>";
								}
								?>
								</select>
								<select id='asset_type' name='asset_type' style='width:120px' onchange="setStoargeDeviceType(this)">
									<option value=''><?=$_LANG_TEXT["devicegubunchoosetext"][$lang_code];?></option>
								<?
								foreach($_CODE['asset_type'] as $key => $name){
									echo "<option value='".$key."' ".($asset_type==$key ? "selected" : "").">".$name."</option>";
								}
								?>
								</select>
							</td>
							<th><?=$_LANG_TEXT["progressstatustext"][$lang_code];?></th>
							<td>
								<select id='status' name='status'>
									<option value=''><?=$_LANG_TEXT["alltext"][$lang_code];?></option>
								<?
								foreach($_CODE['vcs_status'] as $key => $name){
									echo "<option value='".$key."' ".($status==$key ? "selected" : "").">".$name."</option>";
								}
								?>
							</td>
						</tr>
						<tr>
							<th><?=$_LANG_TEXT["usercompanynametext"][$lang_code];?></th>
							<td colspan='3'>
								<input type='hidden' name='searchopt' id='searchopt' value='COM_NAME'>
								<input type="text" name="searchkey" id="searchkey" class="frm_input" value="<?=$searchkey?>" maxlength="50" >
								<input type="submit" value="<?=$_LANG_TEXT["btnsearch"][$lang_code];?>" class="btn_submit" >
							</td>
						</tr>
					</table>
					</form>

					<table class="list" style='margin-top:30px;'>
						<tr>
							<th><?=$_LANG_TEXT['numtext'][$lang_code]?></th>
							<th><a href="<?=$PHP_SELF?>?enc=<?=ParamEnCoding($param.($param? "&":"")."orderby=".($orderby=="com_name"? "com_name desc" : "com_name"))?>"   class='sort'><?=$_LANG_TEXT["usercompanynametext"][$lang_code];?></a></th>
							<th><a href="<?=$PHP_SELF?>?enc=<?=ParamEnCoding($param.($param? "&":"")."orderby=".($orderby=="vcs"? "vcs desc" : "vcs"))?>"   class='sort'><?=$_LANG_TEXT["checkstatustext"][$lang_code];?></a></th>
							<th><a href="<?=$PHP_SELF?>?enc=<?=ParamEnCoding($param.($param? "&":"")."orderby=".($orderby=="virus"? "virus desc" : "virus"))?>"   class='sort'><?=$_LANG_TEXT["virusdetectiontext"][$lang_code];?></a></th>
							<th><a href="<?=$PHP_SELF?>?enc=<?=ParamEnCoding($param.($param? "&":"")."orderby=".($orderby=="weak"? "weak desc" : "weak"))?>"   class='sort'><?=$_LANG_TEXT["weaknessdetectiontext"][$lang_code];?></a></th>
						</tr>
	<?php

		if($asset_type != ""){

			$search_sql .=  " AND vcs.v_asset_type = '".$asset_type."' ";
		}

		if($vcs_type != ""){

			$search_sql .=  " AND vcs.wvcs_type = '".$vcs_type."' ";
		}

		if($checkdate1 != "" && $checkdate2 !=""){

			$search_sql .= " AND vcs.wvcs_dt between '$checkdate1 00:00:00.000' and '$checkdate2 23:59:59.999' ";
		}

		if($indate1 !="" && $indate2 != ""){	

			$search_sql .= " AND vcs.wvcs_authorize_dt between '$indate1 00:00:00.000' and '$indate2 23:59:59.999' ";
		}

		if($status !=""){

			$search_sql .= " AND vcs.vcs_status = '$status' ";

		}//if($status !=""){


		if($searchkey != ""){

			if($searchopt=="COM_NAME"){

				$search_sql .= " and com.v_com_name like '%$searchkey%' ";

			}
		}

		$qry_params = array("search_sql"=>$search_sql);
		$qry_label = QRY_STAT_COMPANY_VCS_COUNT;
		$sql = query($qry_label,$qry_params);

		//echo nl2br($sql);

		$result = sqlsrv_query($wvcs_dbcon, $sql);
		$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		$total = $row['CNT'];

		$rows = $paging;			// 페이지당 출력갯수
		$lists = $_list;			// 목록수
		$page_count = ceil($total/$rows);
		if(!$page || $page > $page_count) $page = 1;
		$start = ($page-1)*$rows;
		$no = $total-$start;
		$end = $start + $rows;

		if($orderby != "") {
			
			$orderby = str_replace("com_name","MAX(v_com_name)",$orderby);
			$orderby = str_replace("vcs"," COUNT(v_wvcs_seq)",$orderby);
			$orderby = str_replace("virus","COUNT(virus_check)",$orderby);
			$orderby = str_replace("weak","COUNT(weak_check)",$orderby);

			$order_sql = " ORDER BY $orderby";

		} else {
			$order_sql = " ORDER BY COUNT(v_wvcs_seq) DESC ";
		}

								
		$qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start);
		$qry_label = QRY_STAT_COMPANY_VCS_LIST;
		$sql = query($qry_label,$qry_params);
		$result =@sqlsrv_query($wvcs_dbcon, $sql);

		//echo nl2br($sql);
		
		$cnt = 20;
		$iK = 0;
		$classStr = "";

		if($result){
		  while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

				$cnt--;
				$iK++;
				
				$v_com_seq = $row['v_com_seq'];
				$v_com_name = $row['v_com_name'];
				$vcs_cnt = $row['vcs_cnt'];
				$weak_check_cnt = $row['weak_check_cnt'];
				$virus_check_cnt = $row['virus_check_cnt'];
				

		  ?>	
					<tr>
						<td class='num'><?php echo $no; ?></td>
						<td><a href="#ChartWrapper" onclick="CallStatisticsComCheckData('<?=$v_com_seq?>')"><span id='str_com_<?=$v_com_seq?>'><?=$v_com_name?></span></a></td>
						<td><?=number_format($vcs_cnt)?></td>
						<td><?=number_format($virus_check_cnt)?></td>
						<td><?=number_format($weak_check_cnt)?></td>
					</tr>
			<?php
			
				$no--;
			}
				
		}

		if($result) sqlsrv_free_stmt($result);  
		sqlsrv_close($wvcs_dbcon);
		if($total < 1) {
			
		?>
					<tr>
						<td colspan="6" align='center'><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
					</tr>
		<?php
		}
		?>				
							
					</table>
					<!--페이징-->
					<?php
					if($total > 0) {
						$param_enc = ($param)? "enc=".ParamEnCoding($param) : "";
						print_pagelistNew3($page, $lists, $page_count, $param_enc, '', $total );
					}
					?>

				</div>
				<!--chart table-->

				<!--chart-->
				<div id='ChartWrapper' class='chart' >
					
					<div class='right'>
						<?=$_LANG_TEXT["vcsprogressstatuschoosetext"][$lang_code];?>
						<select id='vcs_status' name='vcs_status' onchange="CallStatisticsComCheckData($('#v_com_seq').val());">
							<option value=''><?=$_LANG_TEXT["alltext"][$lang_code];?></option>
						<?
						foreach($_CODE['vcs_status'] as $key => $name){
							echo "<option value='".$key."' ".($status==$key ? "selected" : "").">".$name."</option>";
						}
						?>
						</select>
					</div>

					<input type='hidden' name='v_com_seq' id='v_com_seq'>
					<input type="hidden" name="chart_year" id="chart_year">
					<input type="hidden" name="chart_month" id="chart_month">
					<input type='hidden' name='onTab' id='onTab' value='MONTHLIST'>

					<div class="section01">
						<h1><span name='tit_com_name' class='red'><?=$_LANG_TEXT['alltext'][$lang_code]?></span> <?=$_LANG_TEXT['m_statistics_result'][$lang_code]?></h1>
						<ul class="tab">
							<li name="DAYLIST">
								<a href="#" onclick="$('#onTab').val('DAYLIST');StatisticsComCheckData('DAY')"><?=$_LANG_TEXT['daytext'][$lang_code]?></a>
								<div>
									<select name="year" onchange="StatisticsComCheckData('DAY')">
										<option value=""><?=$_LANG_TEXT['chooseyeartext'][$lang_code]?></option>
				<?php
										for($i = 2009 ; $i <= $now_year ; $i++){

											echo "<option value='$i' ".($i==$now_year? "selected=selected" : "").">".$i.$_LANG_TEXT['yeartext'][$lang_code]."</option>";
										}
				?>
									</select>

									<select name="month" onchange="StatisticsComCheckData('DAY')">
										<option value=""><?=$_LANG_TEXT['choosemonthtext'][$lang_code]?></option>
				<?php
										for($i = 1 ; $i <= 12 ; $i++){
											
											$month = strlen($i)==1 ? "0".$i : $i;

											echo "<option value='$month' ".($month==$now_month? "selected=selected" : "").">".$_CODE['month'][$month]."</option>";
										}
				?>
									</select>
									<select name='asset_type' onchange="StatisticsComCheckData('DAY')">
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
							<li name="MONTHLIST" class="on">
								<a href="#" onclick="$('#onTab').val('MONTHLIST');StatisticsComCheckData('MONTH')"><?=$_LANG_TEXT['monthtext'][$lang_code]?></a>
								<div>
									<select name="year" onchange="StatisticsComCheckData('MONTH')">
										<option value=""><?=$_LANG_TEXT['chooseyeartext'][$lang_code]?></option>
				<?php
										for($i = 2009 ; $i <= $now_year ; $i++){

											echo "<option value='$i' ".($i==$now_year? "selected=selected" : "").">".$i.$_LANG_TEXT['yeartext'][$lang_code]."</option>";
										}
				?>
									</select>
									<select name='asset_type' onchange="StatisticsComCheckData('MONTH')">
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
					<h1><span name='tit_com_name' class='red'><?=$_LANG_TEXT['alltext'][$lang_code]?></span> <?=$_LANG_TEXT['weaknessstatustext'][$lang_code]?></h1>
					<select name="year" onchange="StatisticsComCheckData('WEAK')">
						<option value=""><?=$_LANG_TEXT['chooseyeartext'][$lang_code]?></option>
		<?php
						for($i = 2009 ; $i <= $now_year ; $i++){

							echo "<option value='$i' ".($i==$now_year? "selected=selected" : "").">".$i.$_LANG_TEXT['yeartext'][$lang_code]."</option>";
						}
		?>
					</select>

					<select name="month" onchange="StatisticsComCheckData('WEAK')">
						<option value="00"><?=$_LANG_TEXT['alltext'][$lang_code]?></option>
		<?php
						for($i = 1 ; $i <= 12 ; $i++){
							
							$month = strlen($i)==1 ? "0".$i : $i;

							echo "<option value='$month' >".$_CODE['month'][$month]."</option>";
						}
		?>
					</select>
					<select name='asset_type' style='width:100px' onchange="StatisticsComCheckData('WEAK')">
						<option value=''><?=$_LANG_TEXT["devicealltext"][$lang_code];?></option>
						<?
						foreach($_CODE['asset_type'] as $key => $name){
							echo "<option value='".$key."' ".($asset_type==$key ? "selected" : "").">".$name."</option>";
						}
						?>
					</select>
					<div class="ch"><canvas id="chartPcCheckWEAK"  name="chartPcCheck" gubun="WEAK"  width="260px" height="260px"/></canvas></div>
					<div class='ch_legend'><div id="chartPcCheckWEAK_legend" class="chart-legend"></div></div>
				</div><!--<div class="section02">-->

				<div class="section03">
					<h1><span name='tit_com_name' class='red'><?=$_LANG_TEXT['alltext'][$lang_code]?></span> <?=$_LANG_TEXT['virusdectionstatustext'][$lang_code]?></h1>
					<select name="year" onchange="StatisticsComCheckData('VIRUS')">
						<option value=""><?=$_LANG_TEXT['chooseyeartext'][$lang_code]?></option>
		<?php
						for($i = 2009 ; $i <= $now_year ; $i++){

							echo "<option value='$i' ".($i==$now_year? "selected=selected" : "").">".$i.$_LANG_TEXT['yeartext'][$lang_code]."</option>";
						}
		?>
					</select>

					<select name="month" onchange="StatisticsComCheckData('VIRUS')">
						<option value="00"><?=$_LANG_TEXT['alltext'][$lang_code]?></option>
		<?php
						for($i = 1 ; $i <= 12 ; $i++){
							
							$month = strlen($i)==1 ? "0".$i : $i;

							echo "<option value='$month' >".$_CODE['month'][$month]."</option>";
						}
		?>
					</select>
					<select name='asset_type' style='width:100px' onchange="StatisticsComCheckData('VIRUS')">
						<option value=''><?=$_LANG_TEXT["devicealltext"][$lang_code];?></option>
						<?
						foreach($_CODE['asset_type'] as $key => $name){
							echo "<option value='".$key."' ".($asset_type==$key ? "selected" : "").">".$name."</option>";
						}
						?>
					</select>
					<div class="ch"><canvas id="chartPcCheckVIRUS" name="chartPcCheck" gubun="VIRUS" width="260px" height="260px"/></canvas></div>
					<div class='ch_legend'><div id="chartPcCheckVIRUS_legend" class="chart-legend"></div></div>
				</div><!--<div class="section03">-->

				<div class='clear'></div>
				
				</div>
				
				<!--chart-->
			</div>
			
		</div><!--<div class="container">-->

	</div><!--<div class="outline">-->
</div>

<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>