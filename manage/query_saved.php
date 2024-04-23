<?php
$page_name = "custom_query";
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

$searchopt = $_REQUEST[searchopt];	// 검색옵션

$searchkey = $_REQUEST[searchkey];	// 검색어
$orderby = $_REQUEST[orderby];		// 정렬순서
$page = $_REQUEST[page];	
$paging = $_REQUEST[paging];		// 페이지
$start_date = $_REQUEST[start_date];
$end_date = $_REQUEST[end_date];


 if ($paging == "") $paging = $_paging;

if ($start_date == "") $start_date = date("Y-m-d", strtotime(date("Y-m-d") . " -1 month"));
if ($end_date == "") $end_date = date("Y-m-d");

$param = "";
if ($searchopt != "") $param .= ($param == "" ? "" : "&") . "searchopt=" . $searchopt;

if ($searchkey != "") $param .= ($param == "" ? "" : "&") . "searchkey=" . $searchkey;
if ($orderby != "") $param .= ($param == "" ? "" : "&") . "orderby=" . $orderby;
if ($start_date != "") $param .= ($param == "" ? "" : "&") . "start_date=" . $start_date;
if ($end_date != "") $param .= ($param == "" ? "" : "&") . "end_date=" . $end_date;

//검색 로그 기록
$proc_name = $_POST['proc_name'];
if($proc_name != ""){
	$work_log_seq = WriteAdminActLog($proc_name,'SEARCH');
}

$Model_Utils=new Model_Utils();
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
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_query_editor"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
				<!--tab 메뉴-->
		<ul class="tab">
			<li  onclick="location.href='<? echo $_www_server?>/manage/custom_query.php'"><? echo trsLang('쿼리 검색 결과','query_search_result');?></li>
			<li class="on"  onclick="location.href='<? echo $_www_server?>/manage/query_saved.php'"><? echo trsLang('저장된 쿼리','saved_queries');?></li>

		</ul>

		<form name="searchForm" action="<?php echo $_SERVER[PHP_SELF] ?>" method="POST">
			<input type="hidden" name="page" value="">
			<input type='hidden' name='proc_name' id='proc_name'>
			<table  class="search">
							<tr>
				
					<th style='widht:100px;'><?= $_LANG_TEXT['createdatetext'][$lang_code] ?> </th>
					<td>
						<input type="text" name="start_date" id="start_date" class="frm_input" placeholder="" style="width:100px" value="<?= $start_date ?>" maxlength="10"> ~ 
						<input type="text" name="end_date" id="end_date" class="frm_input" placeholder="" style="width:100px" value="<?= $end_date ?>" maxlength="10">
					</td>
				</tr>
								<tr>
					<th><? echo trsLang('키워드검색','keywordsearchtext'); ?> </th>
					<td style='padding:5px 13px'>

								<select name="searchopt" id="searchopt">
							<option value="" <?php if($searchopt == "") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['select_search_item'][$lang_code]?></option>
							<option value="query_title" <?php if($searchopt == "query_title") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['query_title'][$lang_code]?></option>
						</select>

						<input type="text" class="frm_input" style="width:50%" name="searchkey" id="searchkey" value="<?= $searchkey ?>" maxlength="50">
						<input type="submit" value="<?= $_LANG_TEXT['usersearchtext'][$lang_code] ?>" class="btn_submit" onclick="return SearchSubmit(document.searchForm);">
							<input type="button" value="<? echo trsLang('초기화','btnclear');?>" class="btn_submit_no_icon" onclick="location.href='<? echo $_www_server?>/manage/query_saved.php'">
													
					</td>
				</tr>
			</table>
			<?php 
			//검색항목
				$search_sql = "";
				if ($start_date != "" && $end_date != "") {
					$search_sql .= " and create_date between '".str_replace('-', '', $start_date)."000000' AND '".str_replace('-', '', $end_date)."235959' ";
				}
			if ($searchkey != "" && $searchopt != "") {

           if ($searchopt == "query_title") {

					$search_sql .= " and query_title like N'%$searchkey%' ";
				} 
			
			}
			?>
		</form>
		<?php 
				$Model_Utils->SHOW_DEBUG_SQL = false;
				$args = array("search_sql"=>$search_sql);	
				$total=$Model_Utils->getQueryListCount($args); 
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
					$order_sql = " ORDER BY t1.custom_query_seq DESC ";
		
				}	

				$args = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start);			
				$result = $Model_Utils->getQueryListInfo($args); 
				
				$cnt = 20;
				$iK = 0;
				$classStr = "";
				//excel file name while downloading
		   $excel_name=$_LANG_TEXT['m_query_editor'][$lang_code];	

		?>

				<!--검색결과리스트-->
		<div class="btn_wrap right" style='margin-bottom:10px;'>
			
			<? $excel_down_url = $_www_server."/manage/query_editor_excel.php?enc=".ParamEnCoding($param);?>
			<div class="right">
				<a href="#" id="query_editor_excel" class="btnexcel required-print-auth hide"
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
		<BR>
		<BR>

		<table class="list" style="margin-top:10px; ">
			<tr>
				<th class="num"><?= $_LANG_TEXT['numtext'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['query_title'][$lang_code] ?></th>
				<th style='width:200px height:30px; '><?= $_LANG_TEXT['query_content'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['registertext'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['createdatetext'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['deletetext'][$lang_code] ?></th>

			</tr>
			<?php
			
						if ($result) {
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

				
						$cnt--;
						$iK++;

						$custom_query_seq = $row['custom_query_seq'];

						$query_title = $row['query_title'];
						$create_emp_seq = $row['create_emp_seq'];
						$create_date = $row['create_date'];		
						$query_content = $row['query_content'];
						$emp_name = aes_256_dec($row['emp_name']);

						$date_value = date('Y-m-d H:i', strtotime($create_date));			
						$utf8_query_content=utf8_strcut($query_content,130);
						$param_enc = ParamEnCoding("query_content=".$query_content.($param ? "&" : "").$param);

			?>
			<tr >

				<td class="center"><?=$no?></td>
				<td class="center" style='min-width:300px'><a href="javascript:void(0)" onclick="callQueryResult()" class='text_link' data-param-enc="<? echo ParamEnCoding("custom_query_seq=".$custom_query_seq);?>"><?=$query_title?></a></td>
				<td class="center"><?= $utf8_query_content;?></td>
				<td class="center"><?= $emp_name ?></td>
				<td class="center"><?=$date_value?></td>
					<td class="center" onclick="event.stopPropagation();"><span onclick="queryDeleteSubmit('<?=$custom_query_seq?>')"
						style='cursor:pointer;' class='required-delete-auth hide'><?=$_LANG_TEXT['btndelete'][$lang_code]?></span></td>

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
		</form>
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