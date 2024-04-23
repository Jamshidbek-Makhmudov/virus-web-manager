<?php
$page_name = "app_update_log";
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
$searchopt1 = $_REQUEST[searchopt1];	// 검색옵션
$searchkey1 = $_REQUEST[searchkey1];	// 검색어
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
if ($searchopt1 != "") $param .= ($param == "" ? "" : "&") . "searchopt1=" . $searchopt1;
if ($searchkey1 != "") $param .= ($param == "" ? "" : "&") . "searchkey1=" . $searchkey1;
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
				 <h1><span id='page_title'><?=$_LANG_TEXT["app_update_log_text"][$lang_code];?></span></h1>

			</div>
			<span class="line"></span>
		</div>

		<!--검색폼-->
		<form name="searchForm" action="<?php echo $_SERVER[PHP_SELF] ?>" method="POST">
			<input type="hidden" name="page" value="">
				<input type='hidden' name='proc_name' id='proc_name'>
			<table class="search">
				<tr>
					<th style='widht:100px;'><?= $_LANG_TEXT['update_date'][$lang_code] ?> </th>
					<td style="width:320px">
						<input type="text" name="start_date" id="start_date" class="frm_input" placeholder="" style="width:100px" value="<?= $start_date ?>" maxlength="10"> ~ 
						<input type="text" name="end_date" id="end_date" class="frm_input" placeholder="" style="width:100px" value="<?= $end_date ?>" maxlength="10">
					</td>
					<th style='widht:100px;'><?=$_LANG_TEXT['appnametext'][$lang_code]?></th>
					<td>
						<input type="hidden" class="frm_input" style="width:50%" name="searchopt1" id="searchopt1" value="app_name">
						<select id='searchkey1' name='searchkey1' style="height: 31px;margin-top:1px;">
							<option value="" <?php if($searchkey1 == "") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['choosetext'][$lang_code]?></option>
							<?
							$option = $_CODE_UPDATE_APP_NAME;
							foreach($option as $value => $name){
								echo "<option value='$value' ".($searchkey1==$value? "selected=true" : "").">$name</option>";
							}
							?>
						</select>	
					</td>
				</tr>
				<tr>
					<th><? echo trsLang('키워드검색','keywordsearchtext'); ?> </th>
					<td colspan="3" style='padding:5px 13px'>
						<select name="searchopt" id="searchopt" style="height: 31px;margin-top:1px;">
							<option value="" <?php if($searchopt == "") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['select_search_item'][$lang_code]?></option>
							<!-- <option value="app_name" <?php if($searchopt == "app_name") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['appnametext'][$lang_code]?></option> -->
							<option value="ver" <?php if($searchopt == "ver") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['versiontext'][$lang_code]?></option>
						</select>
						<input type="text" class="frm_input" style="width:50%" name="searchkey" id="searchkey" value="<?= $searchkey ?>" maxlength="50">
						<input type="submit" value="<?= $_LANG_TEXT['usersearchtext'][$lang_code] ?>" class="btn_submit" onclick="return SearchSubmit(document.searchForm);">
						<input type="button" value="<? echo trsLang('초기화','btnclear');?>" class="btn_submit_no_icon" onclick="location.href='<? echo $_www_server?>/stat/app_update_log.php'">
						
					</td>
				</tr>
			</table>
			<?php 
			//검색항목
			$search_sql = "";

			if ($start_date != "" && $end_date != "") {
				$search_sql .= " and a1.update_time between '".str_replace('-', '', $start_date)."000000' AND '".str_replace('-', '', $end_date)."235959' ";
			}

			// 키워드검색
			if($searchkey != ""){
				if($searchopt == "app_name"){
					$search_sql .= " and a1.app_name like '%$searchkey%' ";
				} else if($searchopt=="ver"){
					$search_sql .= " and a1.ver = '$searchkey' ";
				}
			}

			if (!empty($searchopt1) && !empty($searchkey1)){
				if($searchopt1 == "app_name"){
					$search_sql .= " and a1.app_name = '$searchkey1' ";
				}
			}

			$Model_Stat->SHOW_DEBUG_SQL = false;
			$args = array("search_sql"=>$search_sql);

			$total=$Model_Stat->getAppUpdateLogListCount($args); 

			$rows = $paging;			// 페이지당 출력갯수
			$lists = $_list;			// 목록수
			$page_count = ceil($total/$rows);
			if(!$page || $page > $page_count) $page = 1;
			$start = ($page-1)*$rows;
			$no  = $total-$start;
			$end = $start + $rows;

			if ($orderby != "") {
				$order_sql = " ORDER BY $orderby";
			} else {
				$order_sql = " ORDER BY a1.app_update_log_seq DESC ";
			}	

			$args = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start);			
			$Model_Stat->SHOW_DEBUG_SQL = false;	
			$result = $Model_Stat->getAppUpdateLogList($args); 


			$cnt = 20;
			$iK = 0;
			$classStr = "";
			//excel file name while downloading
		   	$excel_name=$_LANG_TEXT['app_update_log_text'][$lang_code];		
			?>

		<div class="btn_wrap right" style='margin-bottom:10px;'>
			<? $excel_down_url = $_www_server."/stat/app_update_log_excel.php?enc=".ParamEnCoding($param);?>
			<div class="right">
				<a href="#" id="app_update_log_excel" class="btnexcel required-print-auth hide" onclick="getHTMLSplit('<?=$total?>','<?=$excel_down_url?>','<?=$excel_name?>',this);"><?=$_LANG_TEXT["btnexceldownload"][$lang_code];?></a>
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
				<th style='min-width:200px'><? echo trsLang('검사장','scancentertext'); ?></th>
				<th style='min-width:200px'><?= $_LANG_TEXT['update_target'][$lang_code] ?></th>
				<th style='min-width:220px'><?= $_LANG_TEXT['appnametext'][$lang_code] ?></th>
				<th style='width:160px'>APP <?= $_LANG_TEXT['versiontext'][$lang_code] ?></th>
				<th style='width:150px;'>APP <?=$_LANG_TEXT['registerdatetext'][$lang_code]?></th>
				<th style='width:110px'><? echo trsLang('업데이트일자','update_date'); ?></th>
				<th style='width:140px'><? echo trsLang('시작시간','starttime'); ?></th>
				<th style='width:140px'><? echo trsLang('완료시간','end_date_text'); ?></th>
				<th style='width:100px'><? echo trsLang('결과','result_text'); ?></th>

			</tr>
			<?php

			if ($result) {
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
					$cnt--;
					$iK++;

					$app_update_log_seq = $row['app_update_log_seq'];
					$kiosk_name = $row['kiosk_name'];
					$scan_center_name = $row['scan_center_name'];
					$result_text = $row['result'];
					$result_msg  = ucwords(str_replace('_', ' ', $row['result_msg']));

					$app_seq = $row['app_seq'];
					$app_name = $row['app_name'];
					$str_app_name = $_CODE_UPDATE_APP_NAME[$app_name];
					$ver = $row['ver'];
				    $str_update_time=setDateFormat($row['update_time'],"Y-m-d");
				    $str_start_time=setDateFormat($row['update_time'],"Y-m-d H:i");

					$end_time=$row['end_time'];
					
					$patch_date = $row['patch_dt'];
					$create_dt = $row['create_dt'];
					$str_create_dt = setDateFormat($create_dt, "Y-m-d H:i");
					
					if (!empty($end_time) && $end_time !== 'null') {
						$str_end_time = date('Y-m-d H:i', strtotime($end_time));
					} else {
						$str_end_time = '';
					}

					if (substr($patch_date, 0, 10) == "1900-01-01"){	//매일 특정시간업데이트
						$patch_time = substr($patch_date,11,2);
						$str_patch_date = trsLang("매일","everyday")." ".$patch_time.trsLang('시','hourtimetext');
					} else {
						$str_patch_date=setDateFormat($patch_date, "Y-m-d H:i");
					}

					$app_param = ParamEnCoding("app_seq={$app_seq}");
			?>
			<tr>
				<td class='center'><?=$no?></td>
				<td class='center'><? echo $scan_center_name?></td>
				<td class='center'><? echo $kiosk_name ?></td>
				<td class='center'><a href="javascript:void(0)" class="text_link" onclick="sendPostForm('<?php echo $_www_server; ?>/manage/app_update_reg.php?enc=<?=$app_param?>')" style='cursor:pointer'><?=$str_app_name?></a></td>
				<td class='center'><?=$ver?></td>
				<td class='center'><?=$str_create_dt?></td>
				<td class='center'><?=$str_update_time?></td>
				<td class='center'><?=$str_start_time?></td>
				<td class='center'><?=$str_end_time?></td>
				<td class='center' title="<?php echo $result_msg; ?>"><?=$result_text?></td>
			</tr>
			<?php
					$no--;
				}
			}

			if ($result) {
				sqlsrv_free_stmt($result);
			}

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