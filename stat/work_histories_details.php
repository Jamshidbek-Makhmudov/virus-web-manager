<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache,must-revalidate");

$page_name = "work_histories";

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

$param = "";

setPageParams($param, 'searchopt');
setPageParams($param, 'searchkey');
setPageParams($param, 'orderby');
setPageParams($param, 'start_date', $month);
setPageParams($param, 'end_date', $today);
setPageParams($param, 'scan_center_code');
setPageParams($param, 'menu_code');
setPageParams($param, 'work_type');
setPageParams($param, 'paging', $_paging);
setPageParams($param, 'lists', $_list);

$menu_list  = array();
$work_seq   = intVal($_POST["work_seq"]);
$Model_Stat = new Model_Stat();

foreach($_PAGE as $cate => $menu) {
	if ($cate == "MAIN") {
		continue;
	}
	$menu_code = $menu['MENU_CODE'];
	$page_list = $menu['PAGE'];

	if (!is_array($menu_list[$menu_code])) {
		$menu_list[$menu_code] = array();
	}

	foreach ($page_list as $key => $value) {
		$menu_list[$menu_code][$key] = implode(' > ', $value);
	}
}

if ($work_seq <> "") {
	$args = array("work_seq" => $work_seq);
	$data = $Model_Stat->getAdminWorkHistoryDetails($args);

	@extract($data['history']);
					
	$newer_data    = aes_256_dec($newer_data);
	$user_emp_name = aes_256_dec($user_emp_name);
	$create_dt     = setDateFormat($create_dt, "Y-m-d H:i");
	$menu_depth    = (!empty($page_code)) ?  $menu_list[$menu_code][$page_code] : $menu_name;
	
	$request_payload = json_decode($request_payload, true);
	$user_cookies    = json_decode($user_cookies, true);
	
	//enc,proc_name 파라메터값은 제외하고 보여준다.
	$remove_params = array("enc", "proc_name", "proc_exec", "proc", "record_count", "PHPSESSID");

	foreach($remove_params as $key) {
		unset($request_payload[$key]);
		unset($user_cookies[$key]);
	}

	$nodata= $_LANG_TEXT["nodata"][$lang_code];

	if($recv_data!=''&& $recv_data !='null' ){
		$log_desc .= "<li style='margin-top:10px; margin-left:5px; list-style-type: none;'>".$str_payload."</li>";
	}else{
		$log_desc .= "<li style='margin-top:10px;margin-left:5px;'>".$nodata."</li>";

	}
}
?>
<script language="javascript">
	$(function () {
		$("#start_date").datepicker(pickerOpts);
		$("#end_date").datepicker(pickerOpts);
	});

	function showWorkHistoryDataMOdified(table, type) {
		$(`div.${table}`).hide()
		$(`div.${table}.${type}`).show()

		return false;
	}

	function showWorkPage() {
		window.open('about:blank', 'view-window')
		$("#viewForm").submit();
	}
</script>
<div id="result_view">
	<div class="container">
		<div id="tit_area">
			<div class="tit_line">
				<h1><span id='page_title'><?= $_LANG_TEXT["admin"][$lang_code] ." ". $_LANG_TEXT["workinfotext"][$lang_code]; ?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		<div class="page_right">
			<span style='cursor:pointer' onclick="location.href='work_histories.php'"><?= $_LANG_TEXT["btngobeforepage"][$lang_code]; ?></span>
		</div>
		<!-- 내용 -->
		<div style="margin-top:50px">
			<div>
				<?php
				$page_url = (($work_type == 'VIEW') || ($work_type == 'SEARCH')) ? $request_url : $referer_url;

				echo printArrayToForm($page_url, $request_method, $request_payload); 
				?>
				<table class="view" style="table-layout: fixed;">
					<colgroup>
						<col style="width: 150px;">
						<col>
						<col style="width: 150px;">
						<col>
					</colgroup>
					<tr>
						<th><?=$_LANG_TEXT['work_detail'][$lang_code]?></th>
						<td colspan='3'><?=$work_title?></td>
					</tr>
					<tr>
						<th><?=$_LANG_TEXT["workertext"][$lang_code];?></th>
						<td><?=$user_emp_name?><?if ($user_emp_no) echo " ({$user_emp_no})";?></td>
						<th class='line'><?=$_LANG_TEXT["work_type"][$lang_code];?></th>
						<td><?=$work_type?></td>
					</tr>
					<tr class="bg">
						<th><?=$_LANG_TEXT['ipaddresstext'][$lang_code]?></th>
						<td><?=$create_ip?></td>
						<th class='line'><?=$_LANG_TEXT["work_date"][$lang_code];?></th>
						<td><?=$create_dt?></td>
					</tr>
					<tr>
						<th><?=$_LANG_TEXT['scancentertext'][$lang_code]?></th>
						<td><?=$scan_center_name?></td>
						<th class='line'><?=$_LANG_TEXT['menu_text'][$lang_code]?></th>
						<td><?=$menu_depth?></td>
					</tr>
					<?php /*
					<tr>
						<th><?=$_LANG_TEXT['workexecpagetext'][$lang_code]?></th>
						<td colspan='3'>
							<div style="display:inline-block;"><?=$request_url?></div>
							<?php if (($work_type == 'VIEW') || ($work_type == 'SEARCH')) { ?>
							<a class="btn" style="width: 100px; margin-left: 20px; height: 26px; line-height: 26px;" href="javascript:void(0)" onclick="showWorkPage()">페이지 보기</a>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<th><?=$_LANG_TEXT['workcallpagetext'][$lang_code]?></th>
						<td colspan='3'>
							<div style="display:inline-block;"><?=$referer_url?></div>
						</td>
					</tr>
					*/ ?>
				</table>
			</div>

			<?php
			if ($_ck_user_level == "SUPER") {
			?>
			<!--사용자 쿠키-->
			<div>
				<div class="sub_tit" style='margin-top: 40px;'> > <?=$_LANG_TEXT["usercookieinfo"][$lang_code];?></div>
				<div class='wrapper'>
					<?php 
						$cookie = array("user_seq", "user_id", "user_name", "user_org", "user_dept", "user_level", "user_mauth", "user_mng_org_auth", "user_mng_scan_center_auth", "user_pwd_change", "user_lsq", "user_lang");
						foreach ($user_cookies as $key => $value) {
							if (!in_array($key, $cookie)) {
								unset($user_cookies[$key]);
							}
						}

						echo printArrayToTable('user_cookies', $user_cookies, 3); 
					?>
				</div>
			</div>
			<?php 
			}
			?>

			<!-- 페이지 호출 데이터 -->
			<div>
				<div class="sub_tit" style='margin-top: 40px;'> > <?=$_LANG_TEXT["requestpayloadinfo"][$lang_code];?></div>
				<div class='wrapper'>
					<?php
						echo printArrayToTable('request_payload', $request_payload, 0); 
					?>
				</div>
			</div>
			
			<?php
			$table_name = "";

			$revisions = array();
			if ($data['revision']) {
				foreach ($data['revision'] as $idx => $item) {
					if ($table_name != $item['table_name']) {
						$table_name = $item['table_name'];
						$revisions[$table_name] = array("older"=>array(), "newer"=>array());
					}

					if (!empty($item['older_data'])) {
						$older_data = json_decode($item['older_data'], true);
						if (sizeof($older_data[0]) > 0) {
							$revisions[$table_name]['older'] = $older_data;
						} else {
							array_push($revisions[$table_name]['older'], $older_data);
						}
					}

					if (!empty($item['newer_data'])) {
						$newer_data = json_decode($item['newer_data'], true);
						array_push($revisions[$table_name]['newer'], $newer_data);
					}
				}
			?>
			<!-- 페이지 호출 데이터 -->
			<div>
				<?php

				foreach ($revisions as $key => $value) {
					echo printArrayToTableRows($key, $value, 0); 
				}
				?>
			</div>
			<?php 
			}
			?>
		</div>


	</div>
</div>
<?php
include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";
?>