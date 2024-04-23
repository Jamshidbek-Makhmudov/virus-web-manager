<?php
$page_name = "system_log";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI']) - 1);
$_apos = stripos($_REQUEST_URI,  "/");
if ($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;


include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
include_once $_server_path . "/" . $_site_path . "/inc/header.inc";
include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";

$log_div = $_REQUEST[log_div];		
$orderby = $_REQUEST[orderby];		// 정렬순서
$page = $_REQUEST[page];	
$paging = $_REQUEST[paging];		// 페이지
$start_date = $_REQUEST[start_date];
$end_date = $_REQUEST[end_date];

if ($paging == "") $paging = $_paging;

if ($start_date == "") $start_date = date("Y-m-d", strtotime(date("Y-m-d") . " -1 month"));
if ($end_date == "") $end_date = date("Y-m-d");

$param = "";
if ($log_div != "") $param .= ($param == "" ? "" : "&") . "log_div=" . $log_div;
if ($orderby != "") $param .= ($param == "" ? "" : "&") . "orderby=" . $orderby;
if ($start_date != "") $param .= ($param == "" ? "" : "&") . "start_date=" . $start_date;
if ($end_date != "") $param .= ($param == "" ? "" : "&") . "end_date=" . $end_date;

//검색 로그 기록
$proc_name = $_POST['proc_name'];
if($proc_name != ""){
	$work_log_seq = WriteAdminActLog($proc_name,'SEARCH');
}

$Model_Stat=new Model_Stat();

?>
<script language="javascript">
	$(function() {
		$("#start_date").datepicker(pickerOpts);
		$("#end_date").datepicker(pickerOpts);
	});

</script>
<div id="user_list">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["worklogtext"][$lang_code];?></span></h1>

			</div>
			<span class="line"></span>
		</div>

		<!--검색폼-->
		<form name="searchForm" action="<?php echo $_SERVER[PHP_SELF] ?>" method="POST">
			<input type="hidden" name="page" value="">
				<input type='hidden' name='proc_name' id='proc_name'>
			<table class="search">
				<tr>
					<th style='widht:100px;'><?= $_LANG_TEXT['work_date'][$lang_code] ?> </th>
					<td>
						<input type="text" name="start_date" id="start_date" class="frm_input" placeholder="" style="width:100px" value="<?= $start_date ?>" maxlength="10"> ~ 
						<input type="text" name="end_date" id="end_date" class="frm_input" placeholder="" style="width:100px" value="<?= $end_date ?>" maxlength="10">
					</td>
				</tr>

				<tr>
					<th><? echo trsLang('작업구분','work_classification'); ?> </th>
					<td style='padding:5px 13px'>

							<select name="log_div" id="log_div">
									<option value=''><? echo trsLang('선택','choosetexts');?></option>
								<?foreach($_CODE_SYSTEM_LOG_LIST as $key=>$name){
										$selected = $key==$log_div ? "selected" : "";
									echo "<option value='{$key}' {$selected}>{$name}</option>";
								}?>
							</select>
						<input type="submit" value="<?= $_LANG_TEXT['usersearchtext'][$lang_code] ?>" class="btn_submit" onclick="return SearchSubmit(document.searchForm);">
						<!--<input type="button" value="<? echo trsLang('초기화','btnclear');?>" class="btn_submit_no_icon" onclick="location.href='<? echo $_www_server?>/stat/system_log.php'">-->
						
					</td>
				</tr>
			</table>
			<?php 
			//검색항목
			$search_sql = "";
			if ($start_date != "" && $end_date != "") {
				$search_sql .= " and s.create_date between '".str_replace('-', '', $start_date)."000000' AND '".str_replace('-', '', $end_date)."235959' ";
			}


			 if($log_div != ""){

				$search_sql .= " and s.log_div = '{$log_div}' ";

			}

			 $Model_Stat->SHOW_DEBUG_SQL = false;
				$args = array("search_sql"=>$search_sql);
	
				$total=$Model_Stat->getSystemLogListCount($args); 
	
				$rows = $paging;			// 페이지당 출력갯수
				$lists = $_list;			// 목록수
				$page_count = ceil($total/$rows);
				if(!$page || $page > $page_count) $page = 1;
				$start = ($page-1)*$rows;
				$no = $total-$start;
				$end = $start + $rows;

				if($orderby != "") {
					$order_sql = " ORDER BY $orderby";
				} else {
					$order_sql = " ORDER BY s.system_log_seq DESC ";
		
				}	

				$args = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start);			
				$Model_Stat->SHOW_DEBUG_SQL = false;	
				$result = $Model_Stat->getSystemLogList($args); 
				
	
				$cnt = 20;
				$iK = 0;
				$classStr = "";
				//excel file name while downloading
		   $excel_name=$_LANG_TEXT['worklogtext'][$lang_code];		
			?>

		<div class="btn_wrap right" style='margin-bottom:10px;'>
			<? $excel_down_url = $_www_server."/stat/system_log_excel.php?enc=".ParamEnCoding($param);?>
			<div class="right">
				<a href="#" id="system_log_excel" class="btnexcel required-print-auth hide"
						onclick="getHTMLSplit('<?=$total?>','<?=$excel_down_url?>','<?=$excel_name?>',this);"><?=$_LANG_TEXT["btnexceldownload"][$lang_code];?></a>
			</div>
			
			<div style='margin-right:10px; line-height:30px; ' class="right">
				Results : <span style='color:blue'><?=number_format($total)?></span> /
				Records : <select name='paging' onchange="searchForm.submit();">
					<option value='20' <?if($paging=='20' ) echo "selected" ;?>>20</option>
					<option value='40' <?if($paging=='40' ) echo "selected" ;?>>40</option>
					<option value='60' <?if($paging=='60' ) echo "selected" ;?>>60</option>
					<option value='80' <?if($paging=='80' ) echo "selected" ;?>>80</option>
					<option value='100' <?if($paging=='100' ) echo "selected" ;?>>100</option>
				</select>
			</div>

		</div>


		</form>
		
		

		<!--검색결과리스트-->
		<table class="list" style="margin-top:10px; ">
			<tr>

				<th class="num"><?= $_LANG_TEXT['numtext'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['worktimetext'][$lang_code] ?></th>
				<th style='width:300px'><?= $_LANG_TEXT['work_classification'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['workresulttext'][$lang_code] ?></th>
				<th style='text-align:left;padding-left:5px'><? echo trsLang('작업결과상세','workresultdetails')?></th>

			</tr>
			<?php

						if ($result) {
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
						$cnt--;
						$iK++;

						$system_log_seq = $row['system_log_seq'];
						$log_div = $row['log_div'];
						$str_log_div = $_CODE_SYSTEM_LOG_LIST[$log_div];
						if ($str_log_div=="") $str_log_div = $log_div;
						$workresult = $row['result'];

						$workresult = "completed";

						$content = utf8_strcut($row['content'],400);

				    $str_create_date=setDateFormat($row['create_date'],"Y-m-d H:i");

			?>
			<tr>

				<td  class='center'><?=$no?></td>
				<td  class='center'><?=$str_create_date?></td>
				<td  class='center'><?=$str_log_div?></td>
				
				<td  class='center'><?=$workresult?></td>
				
				<td><?= $content ?></td>


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
				<td colspan="12" align='center'><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
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
</div>


<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>