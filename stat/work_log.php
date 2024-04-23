<?php
$page_name = "work_log";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
include_once $_server_path . "/" . $_site_path . "/inc/header.inc";
include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";

$searchopt = $_REQUEST[searchopt];	// 검색옵션
$searchkey = $_REQUEST[searchkey];	// 검색어
$orderby = $_REQUEST[orderby];		// 정렬순서
$page = intval($_REQUEST[page]);			// 페이지
$paging = $_REQUEST[paging];		// 페이지
$start_date = $_REQUEST[start_date];	
$end_date = $_REQUEST[end_date];
if($paging == "") $paging = $_paging;

if($start_date=="") $start_date = date( "Y-m-d", strtotime( date("Y-m-d")." -1 month" ) );
if($end_date=="") $end_date = date("Y-m-d");

$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;
if($start_date!="") $param .= ($param==""? "":"&")."start_date=".$start_date;
if($end_date!="") $param .= ($param==""? "":"&")."end_date=".$end_date;

//검색 로그 기록
/*
$proc_name = $_POST['proc_name'];
if($proc_name != ""){
	$work_log_seq = WriteAdminActLog($proc_name,'SEARCH');
}
*/

$Model_Stat = new Model_Stat();
?>
<script language="javascript">
	$(function() {
		$("#start_date").datepicker(pickerOpts);
		$("#end_date").datepicker(pickerOpts);
	});
</script>
<div id="oper_list">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				<h1><span id='page_title'><?=$_LANG_TEXT['work_log'][$lang_code]?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		
		<!--검색폼-->
		<form name="searchForm" action="<?php echo $_SERVER[PHP_SELF]?>" method="POST">
		<input type="hidden" name="page" value="<?=$page?>">
		<input type='hidden' name='proc_name' id='proc_name'>	
		<table class="search">
			<tr>
			<th><?=$_LANG_TEXT['work_period'][$lang_code]?> </th>
			<td>
				<input type="text" name="start_date" id="start_date" class="frm_input"  placeholder="" style="width:100px" value="<?=$start_date?>"  maxlength="10"> ~ <input type="text" name="end_date" id="end_date" class="frm_input" placeholder="" style="width:100px"  value="<?=$end_date?>"  maxlength="10">
			</td>
		</tr>

		<tr>
			<th><?=$_LANG_TEXT['usersearchtext'][$lang_code]?> </th>
			<td>
				<select name="searchopt" id="searchopt">
					<option value="" <?php if($searchopt == "") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['searchkeywordselecttext'][$lang_code]?></option>
					<option value="id" <?php if($searchopt == "id") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['idtext'][$lang_code]?></option>
					<option value="name" <?php if($searchopt == "name") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['nametext'][$lang_code]?></option>
					<option value="title" <?php if($searchopt == "title") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['work_detail'][$lang_code]?></option>
					<option value="ip" <?php if($searchopt == "ip") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['ipaddresstext'][$lang_code]?></option>
				</select>

				<input type="text" class="frm_input" style="width:50%" name="searchkey" id="searchkey"  value="<?=$searchkey?>"  maxlength="50">

				<input type="submit" value="<?=$_LANG_TEXT['btnsearch'][$lang_code]?>" class="btn_submit" onclick="return WorkLogSearchSubmit(document.searchForm);">
					<input type="button" value="<? echo trsLang('초기화','btnclear');?>" class="btn_submit_no_icon" onclick="location.href='<? echo $_www_server?>/stat/work_log.php'">

			</td>
		</tr>

		</table>

	
		<?php 	
		 $search_sql = "";
		if ($start_date != "" && $end_date != "") {
				$search_sql .= " a1.log_date between '" . str_replace('-', '', $start_date) . "000000' AND '" . str_replace('-', '', $end_date) . "235959' ";
			}
		  
		if($searchkey != ""){
			if($searchopt=="id"){
				$search_sql .= " AND a1.emp_no like '%$searchkey%' ";

			}else if($searchopt=="name"){
				$search_sql .= " AND a1.emp_name like '".aes_256_enc($searchkey)."' ";

			}else if($searchopt == "ip"){

				$search_sql .= " and a1.ip_addr like N'%$searchkey%' ";

			}else if($searchopt == "title"){

				$search_sql .= " and a1.log_title like N'%$searchkey%' ";

			}

		}


			$args = array("search_sql" => $search_sql);
			$total = $Model_Stat->getAdminActLogListCount($args);
			$rows = $paging;			// 페이지당 출력갯수
			$lists = $_list;			// 목록수
			$page_count = ceil($total / $rows);
			if (!$page || $page > $page_count) $page = 1;
			$start = ($page - 1) * $rows;
			$no = $total - $start;
			$end = $start + $rows;

		if($orderby != "") {
			$order_sql = " ORDER BY $orderby";
		} else {
			$order_sql = " ORDER BY a1.act_log_seq DESC ";
		}
									
      $args = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start);
			$Model_Stat->SHOW_DEBUG_SQL = false;

			$result = $Model_Stat->getAdminActLogList($args);
					
		$cnt = 20;
		$iK = 0;
		$classStr = "";

		//echo $param;
		
		  //excel file name while downloading
			$excel_name = $_LANG_TEXT['work_log'][$lang_code];?>
		
			<div class="btn_wrap right" style='margin-bottom:10px;'>
				<? $excel_down_url = $_www_server . "/stat/work_log_excel.php?enc=" . ParamEnCoding($param); ?>
				<div class="right">
					<a  href="javascript:void(0)" class="btnexcel required-print-auth hide" onclick="getHTMLSplit('<?= $total ?>','<?= $excel_down_url ?>','<?= $excel_name ?>',this);"><?= $_LANG_TEXT["btnexceldownload"][$lang_code]; ?></a>
				</div>

				<div style='margin-right:10px; line-height:30px; ' class="right">
					Results : <span style='color:blue'><?= number_format($total) ?></span> /
					Records : <select name='paging' onchange="searchForm.submit();">
						<option value='20' <? if ($paging == '20') echo "selected"; ?>>20</option>
						<option value='40' <? if ($paging == '40') echo "selected"; ?>>40</option>
						<option value='60' <? if ($paging == '60') echo "selected"; ?>>60</option>
						<option value='80' <? if ($paging == '80') echo "selected"; ?>>80</option>
						<option value='100' <? if ($paging == '100') echo "selected"; ?>>100</option>
					</select>
				</div>

			</div>
				</form>
		
		<!--검색결과리스트-->
		<table class="list" style="margin-top:10px">
		<tr>
			<th class="num"><?=$_LANG_TEXT['numtext'][$lang_code]?></th>
			<th><?=$_LANG_TEXT['work_date'][$lang_code]?></th>
			<th style='width:150px'><?=$_LANG_TEXT['nametext'][$lang_code]?></th>
			<th style='width:250px'><?=$_LANG_TEXT['idtext'][$lang_code]?></th>
			<th style='width:250px'><? echo trsLang('작업구분','work_classification');?></th>
			<th style='min-width:500px'><?=$_LANG_TEXT['work_detail'][$lang_code]?></th>
			<th style='width:120px'><?=$_LANG_TEXT['ipaddresstext'][$lang_code]?></th>
		</tr>
<?php

			if ($result) {
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

				$cnt--;
				$iK++;
				
				$act_log_seq = $row['act_log_seq'];
				$admin_id = $row['emp_no'];
				$log_title = $row['log_title'];
				$ip_addr = $row['ip_addr'];
				$act_type = $row['act_type'];
				$referer = $row['referer'];
				$rnum = $row['rnum'];
				$act_type = $row['act_type'];

				$admin_name = aes_256_dec($row['emp_name']);
				$log_dt=setDateFormat($row['log_date'],"Y-m-d H:i");


		  ?>	
			<tr>
				<td><?php echo $no; ?></td>
				<td><?=$log_dt?></td>
				<td><?=$admin_name?></td>
				<td><?=$admin_id?></td>
				<td><?=$act_type?></td>
				<td ><a href="javascript:void(0)" class='text_link' onclick="WorkLogDetail('<?=$act_log_seq?>');"><?=$log_title?></a></td>
				<td><?=$ip_addr?></td>
			</tr>
			<?php
			
				$no--;
			}
				
		}

			if ($result) sqlsrv_free_stmt($result);
			sqlsrv_close($wvcs_dbcon);
				if($total < 1) {

			?>
			<tr>
				<td colspan="15" align='center'><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
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
		</table>


	</div>

</div>
<div id='popContent' style='display:none;'></div>
<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>