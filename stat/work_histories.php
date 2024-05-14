<?php
// $page_name = "work_log";
header("Pragma: no-cache");
header("Cache-Control: no-cache,must-revalidate");

$page_name    = "work_histories";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI']) - 1);
$_apos = stripos($_REQUEST_URI, "/");
if ($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
include_once $_server_path . "/" . $_site_path . "/inc/header.inc";
include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";


$page  = intval($_REQUEST['page']);	// 페이지
$today = date("Y-m-d");
$month = date("Y-m-d", strtotime($today . " -1 month"));
$param = "";

setPageParams($param, 'searchopt');
setPageParams($param, 'searchkey');
setPageParams($param, 'orderby');
setPageParams($param, 'start_date', $month);
setPageParams($param, 'end_date', $today);
setPageParams($param, 'scan_center_code');
setPageParams($param, 'search_menu_code');
setPageParams($param, 'work_type');
setPageParams($param, 'paging', $_paging);
setPageParams($param, 'lists', $_list);

//검색 로그 기록
/*
$proc_name = $_POST['proc_name'];

if (!empty($proc_name)) {
	$work_log_seq = WriteAdminActLog($proc_name, 'SEARCH');
}
*/

function optionSelected($value, $select) {
	if ($value == $select) { 
		echo 'selected="selected"';
	}
}

$Model_Stat = new Model_Stat();
$Model_manage = new Model_manage();

// 페이지 권한이 있는 작업내역만 보여준다.
if ($_ck_user_level != 'SUPER') {
	$page_args = array("user_seq"=>$_ck_user_seq);
	$auth_page = $Model_Stat->getAccessiblePageList($page_args);
	$page_list = array();

	if ($_ck_user_level != 'SUPER') {
		foreach ($auth_page as $key => $page) {
			@extract($page);

			if ($page_code != 'all') {
				if (!is_array($page_list[$menu_code])) {
					$page_list[$menu_code] = array();
				}

				array_push($page_list[$menu_code], $page_code);
			}
		}
	}
}
?>
<script language="javascript">
	$(function () {
		$("#start_date").datepicker(pickerOpts);
		$("#end_date").datepicker(pickerOpts);
		
		scrollWrapperTrigger('listTable', 'wrapper2', 'wrapper1');
	});
</script>
<div id="oper_list">
	<div class="container">
		<div id="tit_area">
			<div class="tit_line">
				<h1><span id='page_title'><?= $_LANG_TEXT['work_log'][$lang_code] ?></span></h1>
			</div>
			<span class="line"></span>
		</div>

		<!--검색폼-->
		<form name="searchForm" action="<?php echo $_SERVER[PHP_SELF] ?>" method="POST">
			<input type="hidden" name="page" value="<?= $page ?>">
			<input type='hidden' name='proc_name' id='proc_name'>
			<table class="search">
				<tr>
					<th><?= $_LANG_TEXT['work_period'][$lang_code] ?> </th>
					<td>
						<input type="text" name="start_date" id="start_date" class="frm_input" placeholder="" style="width:100px" value="<?= $start_date ?>" maxlength="10"> ~ <input type="text" name="end_date" id="end_date" class="frm_input" placeholder="" style="width:100px" value="<?= $end_date ?>" maxlength="10">

						<!-- Scan Center -->
						<div style="display: inline-block;">
							<div class='col head'><? echo trsLang('검사장','scancentertext');?></div>
							<div class='col'>
								<select name='scan_center_code' id='scan_center_code'>
									<option value=''><?=$_LANG_TEXT["alltext"][$lang_code];?></option>
									<?php
									$Model_manage = new Model_manage;
									$result = $Model_manage->getCenterList();
									
									if ($result) {
										while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
											$_scan_center_code = $row['scan_center_code'];
											$_scan_center_name = $row['scan_center_name'];

											$selected = ($_scan_center_code == $scan_center_code) ? "selected" : "";

											echo "<option value='{$_scan_center_code}' {$selected}>{$_scan_center_name}</option>";
										}
									}
								?>
								</select>
							</div>
						</div>

						<!-- menu -->
						<div style="display: inline-block;">
							<div class='col head'><? echo trsLang('메뉴','menu_text');?></div>
							<div class='col'>
								<select name='search_menu_code' id='menu_code'>
									<option value=''><?=$_LANG_TEXT["alltext"][$lang_code];?></option>
									<?php
									$result = $Model_manage->getMenuList();
									
									if ($result) {
										while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
											$_menu_code = $row['menu_code'];
											$_menu_name = $row['menu_name'];
											
											if (!in_array($_menu_code, $_ck_user_mauth)) {
												continue;
											}
											
											$selected = ($_menu_code == $search_menu_code) ? "selected" : "";

											echo "<option value='{$_menu_code}' {$selected}>{$_menu_name}</option>";
										}
									}
								?>
								</select>
							</div>
						</div>

						<!-- work type -->
						<div style="display: inline-block;">
							<div class='col head'><? echo trsLang('작업구분', 'work_classification'); ?></div>
							<div class='col'>
								<select name='work_type' id='work_type'>
									<option value=''><?=$_LANG_TEXT["alltext"][$lang_code];?></option>
									<?php
									$types = $Model_Stat->getWorkTypeList();
									
									foreach ($types as $index => $row) {
										$_work_type = $row['work_type'];
										$selected  = ($_work_type == $work_type) ? "selected" : "";

										echo "<option value='{$_work_type}' {$selected}>{$_work_type}</option>";
									}
								?>
								</select>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<th><?= $_LANG_TEXT['usersearchtext'][$lang_code] ?> </th>
					<td>
						<select name="searchopt" id="searchopt" style="height: 31px; margin-top: 1px;">
							<option value="name" <?php optionSelected($searchopt, "name"); ?>><?= $_LANG_TEXT['nametext'][$lang_code] ?></option>
							<option value="id" <?php optionSelected($searchopt, "id"); ?>><?= $_LANG_TEXT['idtext'][$lang_code] ?></option>
							<option value="title" <?php optionSelected($searchopt, "title"); ?>><?= $_LANG_TEXT['work_detail'][$lang_code] ?></option>
							<option value="ip" <?php optionSelected($searchopt, "ip"); ?>><?= $_LANG_TEXT['ipaddresstext'][$lang_code] ?></option>
						</select>

						<input type="text" class="frm_input" style="width:50%" name="searchkey" id="searchkey" value="<?= $searchkey ?>" maxlength="50">
						<input type="submit" value="<?= $_LANG_TEXT['btnsearch'][$lang_code]; ?>" class="btn_submit" onclick="return WorkLogSearchSubmit(document.searchForm);">
						<input type="button" value="<?= $_LANG_TEXT['btnclear'][$lang_code]; ?>" class="btn_submit_no_icon" onclick="location.href='<? echo $_www_server ?>/stat/work_histories.php'">
					</td>
				</tr>
			</table>

			<?php
			$search_sql = "";

			if ($start_date != "" && $end_date != "") {
				$start_datetime = str_replace('-', '', $start_date) . '000000';
				$end_datetime   = str_replace('-', '', $end_date) . '235959';

				$search_sql .= "AND a1.create_dt BETWEEN '{$start_datetime}' AND '{$end_datetime}' ";
			}
			
			if ($scan_center_code !="") { 
				$search_sql .= "AND a1.scan_center_code = '{$scan_center_code}' ";
			}

			if ($search_menu_code !="") { 
				$search_sql .= "AND a1.menu_code = '{$search_menu_code}' ";
			}

			if ($work_type !="") { 
				$search_sql .= "AND a1.work_type = '{$work_type}' ";
			}

			if ((!empty($searchopt)) && (!empty($searchkey))) {
				if ($searchopt == "id") {
					$search_sql .= "AND a1.user_emp_no LIKE '%{$searchkey}%' ";

				} else if ($searchopt == "name") {
					$searchkey = aes_256_enc($searchkey);
					$search_sql .= "AND a1.user_emp_name LIKE '{$searchkey}' ";

				} else if ($searchopt == "ip") {
					$search_sql .= "AND a1.create_ip LIKE N'%{$searchkey}%' ";

				} else if ($searchopt == "title") {
					$search_sql .= "AND a1.work_title LIKE N'%{$searchkey}%' ";
				}
			}

			if (sizeof($page_list) > 0) {
				$page_sql = "";

				foreach ($page_list as $menu => $pages) {
					$page_in_str = implode("','", $pages);
					if(!empty($page_sql)) {
						$page_sql .= " OR ";
					}

					$page_sql .= "( a1.menu_code = '{$menu}' AND a1.page_code IN ('{$page_in_str}') )";
				}

				$search_sql .= "AND ( {$page_sql} ) ";
			}

			
			$menu_group  = array_unique($_ck_user_mauth);
			$menu_in_str = implode("','", $menu_group);
			
			$search_sql .= "AND a1.menu_code IN ('{$menu_in_str}') ";


			$args  = array("search_sql" => $search_sql);
			$total = $Model_Stat->getAdminWorkHistoriesCount($args);

			$page_count = ceil($total / $paging);

			if (!$page || ($page > $page_count)) {
				$page = 1;
			}

			$start = ($page - 1) * $paging;
			$no    = $total - $start;
			$end   = $start + $paging;

			if ($orderby != "") {
				$order_sql = " ORDER BY $orderby";
			} else {
				$order_sql = " ORDER BY a1.work_seq DESC ";
			}

			$args       = array("order_sql"=>$order_sql, "search_sql"=>$search_sql, "end"=>$end, "start"=>$start);
			 $Model_Stat->SHOW_DEBUG_SQL = false;
			$histories  = $Model_Stat->getAdminWorkHistories($args);

			$excel_name = $_LANG_TEXT['work_log'][$lang_code]; 
			$excel_url  = $_www_server . "/stat/work_histories_excel.php?enc=" . ParamEnCoding($param);
			?>
			<div class="btn_wrap right" style='margin-bottom:10px;'>
				<div class="right">
					<a href="javascript:void(0)" style="height: 27px; line-height:27px; border-size: 1px;" class="btnexcel required-print-auth hide" onclick="getHTMLSplit('<?= $total ?>','<?= $excel_url ?>','<?= $excel_name ?>',this);"><?= $_LANG_TEXT["btnexceldownload"][$lang_code]; ?></a>
				</div>

				<div style='margin-right:10px; line-height:30px; ' class="right">
					Results : <span style='color:blue'><?= number_format($total) ?></span> /
					Records : <select name='paging' onchange="searchForm.submit();" style="height: 28px;">
						<option value='20'  <? optionSelected($paging, '20'); ?>>20</option>
						<option value='40'  <? optionSelected($paging, '40'); ?>>40</option>
						<option value='60'  <? optionSelected($paging, '60'); ?>>60</option>
						<option value='80'  <? optionSelected($paging, '80'); ?>>80</option>
						<option value='100' <? optionSelected($paging, '100'); ?>>100</option>
					</select>
				</div>
			</div>
		</form>

		<!--검색결과리스트-->
		<div id='wrapper1' class="wrapper">
			<div id='div1' style='height:1px;'></div>
		</div>
		<div id='wrapper2' class="wrapper">
			<table class="list" id="listTable" style="margin-top:10px;margin:0px auto; white-space: nowrap;">
				<tr>
					<th class="num center" style="min-width: 70px;width:70px;"><?= $_LANG_TEXT['numtext'][$lang_code] ?></th>
					<th class="center" style="min-width: 120px;width:120px;"><?= $_LANG_TEXT['work_date'][$lang_code] ?></th>
					<th class="center" style='min-width:150px'><?= $_LANG_TEXT['nametext'][$lang_code] ?></th>
					<th class="center" style='min-width:150px'><?= $_LANG_TEXT['idtext'][$lang_code] ?></th>
					<th class="center" style='min-width:100px;width:100px'><?= $_LANG_TEXT['work_classification'][$lang_code] ?></th>
					<th class="center" style='min-width:100px'><?= $_LANG_TEXT['scancentertext'][$lang_code] ?></th>
					<th class="center" style='min-width:100px'><?= $_LANG_TEXT['menu_text'][$lang_code] ?></th>
					<th class="center" style='min-width:500px'><?= $_LANG_TEXT['work_detail'][$lang_code] ?></th>
					<th style='min-width:120px;width:120px'><?= $_LANG_TEXT['ipaddresstext'][$lang_code] ?></th>
				</tr>
				<?php

				if (is_array($histories)) {
					foreach ($histories as $idx => $row) {
						$menu_code        = $row['menu_code'];
						$menu_name        = $row['menu_name'];
						$scan_center_name = $row['scan_center_name'];
						$work_seq         = $row['work_seq'];
						$work_title       = $row['work_title'];
						$work_type        = $row['work_type'];
						$user_emp_no      = $row['user_emp_no'];
						$create_ip        = $row['create_ip'];
						$user_emp_name    = aes_256_dec($row['user_emp_name']);
						$create_dt        = setDateFormat($row['create_dt'], "Y-m-d H:i");

						$param_enc = ParamEnCoding("work_seq={$work_seq}&{$param}");
						$link      = "{$_www_server}/stat/work_histories_details.php?enc={$param_enc}";
				?>
				<tr>
					<td><?php echo $no--; ?></td>
					<td><?= $create_dt ?></td>
					<td><?= $user_emp_name ?></td>
					<td><?= $user_emp_no ?></td>
					<td><?= $work_type ?></td>
					<td><?= $scan_center_name ?></td>
					<td><?= $menu_name ?></td>
					<td><a href="javascript:void(0)" class='text_link' onclick="sendPostForm('<? echo $link; ?>')"><?= $work_title ?></a></td>
					<td><?= $create_ip ?></td>
				</tr>
				<?php
					}
				}

				if ($total < 1) {
				?>
				<tr>
					<td colspan="15" align='center'><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
				</tr>
				<?php
				}
				?>
			</table>
		</div>

		<!--페이징-->
		<?php
		if ($total > 0) {
			$param_enc = ($param) ? "enc=" . ParamEnCoding($param) : "";
			print_pagelistNew3($page, $lists, $page_count, $param_enc, '', $total);
		}
		?>
		</table>
	</div>
</div>

<?php
@sqlsrv_close($wvcs_dbcon);
include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";
?>