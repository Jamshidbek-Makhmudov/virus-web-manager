<?php
{
	$page_name = "admin_auth_list";

	$_server_path = $_SERVER['DOCUMENT_ROOT'];
    $_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, (strLen($_SERVER['REQUEST_URI']) - 1));
	$_apos = stripos($_REQUEST_URI,  "/");

	if($_apos > 0) {
		$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
	}

	$_site_path = $_REQUEST_URI;

	include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

	$Model_manage = new Model_manage();
}

{
	$preset_seq = $_REQUEST["preset_seq"];

	$page   = $_REQUEST['page'];
	$lists  = $_REQUEST['list'];
	$paging = $_REQUEST['paging'];
	
	$page   = (!empty($page))   ? $page : 1;			// 페이지
	$lists  = (!empty($lists))  ? $lists : $_list;		// 목록수
	$paging = (!empty($paging)) ? $paging : $_paging;	// 페이지당 출력갯수
	$param  = "preset_seq={$preset_seq}&paging={$paging}";

	$args  = @compact("preset_seq");
	$total = $Model_manage->getAdminMenuAuthPresetUsedCount($args);

	$page_count = ceil($total / $paging);

	$page   = ($page > $page_count) ? 1 : $page;
	$start  = ($page - 1) * $paging;
	$end    = $start + $paging;
	$number = $total - $start;
}
?>
<table class="list" id='' style="margin-top:1px;">
	<tr>
		<th class="num" style='width:90px;min-width:90px'><?=$_LANG_TEXT["numtext"][$lang_code];?></th>
		<th style='width:100px'><?=$_LANG_TEXT["gubuntext"][$lang_code];?></th>
		<th style='min-width:200px'><?=$_LANG_TEXT["depttext"][$lang_code];?></th>
		<th style='min-width:100px'><?=$_LANG_TEXT["empnametext"][$lang_code];?></th>
		<th style='min-width:150px'><?=$_LANG_TEXT["empnotext"][$lang_code];?></th>
		<th style='width:120px;min-width:120px'><?=$_LANG_TEXT["adminleveltext"][$lang_code];?></th>
		<th style='width:120px;min-width:120px'><?=$_LANG_TEXT["useyntext"][$lang_code];?></th>
	</tr>
	<?php
	$args = @compact("preset_seq","start","end");
	$empLists = $Model_manage->getAdminMenuAuthPresetUsedLists($args);

	if ($empLists) {
		foreach ($empLists as $idx => $row) {
			@extract($row);

			$emp_name = aes_256_dec($row['emp_name']);
			
			$str_admin_level = $_CODE["admin_level"][$admin_level];
			$str_emp_type    = ($emp_type == "ADMIN") ? $_LANG_TEXT["admin"][$lang_code] : $_LANG_TEXT["staff"][$lang_code];
			$str_work_yn     = ($work_yn == "Y") ? $_LANG_TEXT["useyestext"][$lang_code] : $_LANG_TEXT["usenotext"][$lang_code];
	?>
	<tr>
		<td><?php echo $number; ?></td>
		<td><?=$str_emp_type?></td>
		<td><?=$dept_name?></td>
		<td><?=$emp_name?></td>
		<td><?=$emp_id?></td>
		<td><?=$str_admin_level?></td>
		<td><?=$str_work_yn?></td>
	</tr>
	<?php
			$number--;
		}
	} else {
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
if ($total > 0) {
	$param = (empty($param)) ? "" : ParamEnCoding($param);
	$param_enc  = "enc={$param}";
	$html_el_id = "preset_used_list";
	$html_page  = $_www_server."/manage/get_admin_auth_reg_emp.php";
	print_pagelistNew3Func($html_el_id, $html_page, $page, $lists, $page_count, $param_enc, '', $total);
}
?>