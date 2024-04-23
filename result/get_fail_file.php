<?
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI']) - 1);
$_apos = stripos($_REQUEST_URI,  "/");
if ($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

// include_once $_server_path . "/" . $_site_path . "/inc/common2.inc";
include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$v_wvcs_seq = $_REQUEST['v_wvcs_seq'];
$page = $_REQUEST['page'];
$paging = 15;
if($paging == "") $paging = $_paging;

$Model_result = new Model_result();

if ($orderby != "") {
	$order_sql = " ORDER BY $orderby";
} else {
	$order_sql = " ORDER BY v1.v_wvcs_seq DESC "; 
}

$search_sql = " and v1.v_wvcs_seq = '{$v_wvcs_seq}' ";

$args = array("search_sql"=>$search_sql);
$total = $Model_result->getUserVCSImportFailListCount($args);

$rows = $paging;			// 페이지당 출력갯수

$lists = $_list;				// 목록수
$page_count = ceil($total / $rows);
if (!$page || $page > $page_count) $page = 1;

$start = ($page - 1) * $rows;
$no = $total - $start;
$end = $start + $rows;

$args = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start);
$failList = $Model_result->getUserVCSImportFailList($args);

$param = "v_wvcs_seq=".$v_wvcs_seq;
if ($paging != "") $param .= ($param == "" ? "" : "&") . "paging=" . $paging;

$classStr = "";
?>
<table class="list" id='' style="margin-top:1px;">
	<tr>
		<th style='width:80px'><?= $_LANG_TEXT['numtext'][$lang_code] ?></th>
		<th style='min-width:80px'><?= $_LANG_TEXT['filepathtext'][$lang_code] ?></th>
		<th style='min-width:80px'><?= $_LANG_TEXT['filenametext'][$lang_code] ?></th>
		<th style='width:120px'><?= $_LANG_TEXT['filesizetext'][$lang_code] ?></th>
		<th style='width:120px'><?= $_LANG_TEXT["carry_in_status"][$lang_code]; ?></th>
		<th style='width:120px'><?= $_LANG_TEXT["server_transfer_status"][$lang_code]; ?></th>
	</tr>
	<?php
	if ($failList) {
		foreach ($failList as $idx => $row) {
			@extract($row);
			
			if ($v_wvcs_file_in_seq > 0) {
				$bring_in = "<font >" . $_LANG_TEXT['intext'][$lang_code] . "<font>";
			} else {
				$bring_in = $_LANG_TEXT['nointext'][$lang_code];
			}
			
			//서버전송여부
			if ($file_send_status == "1" && $file_send_date != "") {
				$send_server = "<font >" . $_LANG_TEXT['send_server'][$lang_code] . "<font>";
			} else {
				$send_server = $_LANG_TEXT['notsend_server'][$lang_code];
			}
	?>
	<tr>
		<td><?php echo $no; ?></td>
		<td style="text-align:left;"><?= $file_path ?></td>
		<td style="text-align:left;"><?= $file_name_org ?></td>
		<td><?= getSizeCheck($file_size) ?></td>
		<td><?= $bring_in ?></td>
		<td><?= $send_server ?></td>
	</tr>
	<?php
			$no--;
		}
	}

	if ($result) sqlsrv_free_stmt($result);
	if ($wvcs_dbcon) sqlsrv_close($wvcs_dbcon);
	
	if ($total < 1) {
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
	$param_enc = ($param)? "enc=".ParamEnCoding($param) : "";
	print_pagelistNew3Func('file_fail_list',$_www_server."/result/get_fail_file.php",$page, $lists, $page_count, $param_enc, '', $total );
}
?>