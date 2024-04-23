<?
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$Model_result = new Model_result();
$v_wvcs_seq = $_REQUEST['v_wvcs_seq'];
$page = $_REQUEST['page'];

$paging = 15;
if($paging == "") $paging = $_paging;
$search_sql = " and v1.v_wvcs_seq = '{$v_wvcs_seq}' ";
$args = array("search_sql" => $search_sql);
$total =  $Model_result->getVCSFileImportListCount($args);
// $paging = 20;
$rows = $paging;			// 페이지당 출력갯수
$lists = $_list;			// 목록수
$page_count = ceil($total / $rows);
if (!$page || $page > $page_count) $page = 1;
$start = ($page - 1) * $rows;
$no = $total - $start;
$end = $start + $rows;
if ($orderby != "") {
	$order_sql = " ORDER BY $orderby";
} else {
	$order_sql = " ORDER BY f1.v_wvcs_file_seq"; 
}


$args = array("search_sql" => $search_sql, "order_sql" => $order_sql, "start" => $start, "end" => $end);
$Model_result->SHOW_DEBUG_SQL = false;
$file_scan_list = $Model_result->getVCSFileImportList($args);

$param = "v_wvcs_seq=".$v_wvcs_seq;
if ($paging != "") $param .= ($param == "" ? "" : "&") . "paging=" . $paging;
$cnt = 20;
$iK = 0;
$classStr = "";
?>

			<table class="list" id='' style="margin-top:1px;">
				<tr>

					<!--<th style='min-width:60px'><?= $_LANG_TEXT['choosetexts'][$lang_code] ?></th>-->
					<th style='min-width:60px'><?= $_LANG_TEXT['numtext'][$lang_code] ?></th>
					<th style='min-width:80px'><?= $_LANG_TEXT['filepathtext'][$lang_code] ?></th>
					<th style='min-width:80px'><?= $_LANG_TEXT['filenametext'][$lang_code] ?></th>
					<th style='min-width:100px'><?= $_LANG_TEXT['filesizetext'][$lang_code] ?></th>
					<th style='min-width:100px'><?= $_LANG_TEXT['confirmed_person'][$lang_code] ?></th>
					<th style='min-width:80px'><?= $_LANG_TEXT["filesignature"][$lang_code]; ?></th>
					<th style='min-width:80px'><?= $_LANG_TEXT["m_result"][$lang_code]; ?></th>
					<th style='min-width:80px'><?= $_LANG_TEXT["server_transfer_status"][$lang_code]; ?></th>
					<th style='min-width:80px'><?= $_LANG_TEXT["deleteyntext"][$lang_code]; ?></th>
				</tr>
				<?php

				if ($file_scan_list) {
					while ($row = sqlsrv_fetch_array($file_scan_list, SQLSRV_FETCH_ASSOC)) {

						$cnt--;
						$iK++;
						$v_user_name = aes_256_dec($row['v_user_name']);
						$wvcs_dt = $row['wvcs_dt'];
						$v_wvcs_file_seq = $row['v_wvcs_file_seq'];
						$file_path = $row['file_path'];
						$file_name_org = $row['file_name_org'];
						$file_size  = $row['file_size'];
						$file_ext  = $row['file_ext'];
						$file_signature  = $row['file_signature'];
						$file_scan_result  = $row['file_scan_result'];
						$v_wvcs_file_in_seq  = $row['v_wvcs_file_in_seq'];
						$file_send_status  = $row['file_send_status'];
						$file_id  = $row['file_id'];
						$file_delete_flag  = $row['file_delete_flag'];
						//반입여부
						if ($file_scan_result == "BAD_EXT") {
							$m_result = "<font >" . $_LANG_TEXT['suspectforgerytext'][$lang_code] . "<font>";
						} else if ($file_scan_result == "VIRUS") {
							$m_result = "<font >" . $_LANG_TEXT['virustext'][$lang_code] . "<font>";
						} else {
							$m_result = "<font >" . $_LANG_TEXT['cleantext'][$lang_code] . "<font>";
						}
						$v_wvcs_file_in_seq  = $row['v_wvcs_file_in_seq'];

						//서버전송여부
						$file_send_status  = $row['file_send_status'];
						$file_send_date = $row['file_send_date'];
						if ($file_send_status == "1" && $file_send_date != "") {
							$send_server = "<font >" . $_LANG_TEXT['send_server'][$lang_code] . "<font>";
						} else {
							$send_server = $_LANG_TEXT['notsend_server'][$lang_code];
						}

						//삭제여부
						$file_delete_flag  = $row['file_delete_flag'];
						if ($file_delete_flag == 1) {
							$delete_flag = "<font >o<font>";
						} else {
							$delete_flag = "X";
						}
				?>
						<tr>
							<!--<td><input type="checkbox" name="" value=""></td>-->
							<td><?php echo $no; ?></td>
							<td style="text-align:left;"><?= $file_path ?></td>
							<td  style="text-align:left;"><?= $file_name_org ?></td>
							<td><?= formatBytes($file_size) ?></td>
							<td><?= $file_ext ?></td>
							<td><?= $file_signature ?></td>
							<td><?= $m_result ?></td>
							<td><?= $send_server ?></td>
							<td><?= $delete_flag ?></td>
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
				<table>
							<?php 
		$excel_name=$_LANG_TEXT['importfiletext'][$lang_code];
		?>
		<div class="btn_wrap right">
			<? $excel_down_url = $_www_server."/result/get_import_file_excel.php?enc=".ParamEnCoding($param);
						?>
			<div class="right">
				<a href="#" id="file_import_list_excel" title="<?echo trsLang('반입파일','importfiletext')." ".$_LANG_TEXT["btnexceldownload"][$lang_code]; ?>"  class="btnexcel required-print-auth hide" onclick="getHTMLSplit('<?= $total ?>','<?= $excel_down_url ?>','<?= $excel_name ?>',this);"><?= $_LANG_TEXT["btnexceldownload"][$lang_code]; ?></a>
			</div>
		</div>

		<!--페이징-->
		<?php
if($total > 0) {
$param_enc = ($param)? "enc=".ParamEnCoding($param) : "";
print_pagelistNew3Func('file_import_list',$_www_server."/result/get_import_file.php",$page, $lists, $page_count, $param_enc, '', $total );
}

		?>
