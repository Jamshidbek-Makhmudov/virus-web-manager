<?
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI']) - 1);
$_apos = stripos($_REQUEST_URI,  "/");
if ($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$start_date = $_REQUEST[start_date];
$end_date = $_REQUEST[end_date];
$scan_center_code = $_REQUEST[scan_center_code];
$paging = "99999999";

$Model_result = new Model_result();

$order_sql = " ORDER BY v1.v_wvcs_seq DESC "; 

$search_sql = "";
if($start_date != "" && $end_date != ""){

	$str_start_date = preg_replace("/[^0-9]*/s", "", $start_date)."000000";
	$str_end_date = preg_replace("/[^0-9]*/s", "", $end_date)."235959";

	$search_sql .= " AND v20.in_time between '{$str_start_date}' and '{$str_end_date}' ";
}

if($scan_center_code != "" ){
	$search_sql .= " AND v20.in_center_code = '{$scan_center_code}'  ";
}

$args = array("search_sql"=>$search_sql);
$total = $Model_result->getUserVCSBadFileListCount($args);

$rows = $paging;			// 페이지당 출력갯수

$lists = $_list;				// 목록수
$page_count = ceil($total / $rows);
if (!$page || $page > $page_count) $page = 1;

$start = ($page - 1) * $rows;
$no = $total - $start;
$end = $start + $rows;

$args = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start);
$result = $Model_result->getUserVCSBadFileList($args);

?>

<table class="list" id='' style="margin-top:1px;">
<tr>
	<th style='min-width:60px'><?= $_LANG_TEXT['numtext'][$lang_code] ?></th>
	<th class="center"  style='min-width:80px'><?= $_LANG_TEXT['visitor_name'][$lang_code] ?></th>
	<th class="center"  style='min-width:100px'><?= $_LANG_TEXT['belongtext'][$lang_code] ?></th>
	<th class="center"  style='min-width:80px'><?= trsLang('방문일시','date_visit') ?></th>
	<th class="center" style='min-width:100px;max-width:200px;'><?= $_LANG_TEXT['purpose_visit'][$lang_code] ?></th>
	<th class="center" style='min-width:100px'><?= $_LANG_TEXT['inspection_center'][$lang_code] ?></th>
	<th style='min-width:80px'><?= $_LANG_TEXT['filepathtext'][$lang_code] ?></th>
	<th style='min-width:100px'><?= $_LANG_TEXT['filesizetext'][$lang_code] ?></th>
	<th style='min-width:80px'><?= $_LANG_TEXT["filesignature"][$lang_code]; ?></th>
	<th style='width:250px'><?= trsLang('파일해시','filehash'); ?>(md5)</th>
</tr>
<?php
	if ($result) {
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			
			$v_user_name = aes_256_dec($row[v_user_name]);
			$v_user_name_en = $row[v_user_name_en];
			$v_user_belong = $row[v_user_belong];
			$v_purpose = $row[v_purpose];
			$scan_center_name = $row[scan_center_name];
			$in_time = setDateFormat($row[in_time]);

			$v_wvcs_file_seq = $row['v_wvcs_file_seq'];
			$file_path = $row['file_path'].$row['file_name_org'];
			$file_size = getSizeCheck($row['file_size']);
			$file_signature = $row['file_signature'];
			$file_id  = $row['file_id'];
			$md5  = $row['md5'];

			$v_phone = $row['v_phone'];

	?>
			<tr>
				<td><?php echo $no; ?></td>
				<td class="center <? echo $display['user_info'];?>" >
						<?= $v_user_name ?><? if($v_user_name_en != "") echo " ($v_user_name_en)"; ?>
				</td>
				<td class="center" ><?= $v_user_belong ?></td>
				<td class="center" ><?= $in_time ?></td>
				<td class="center" ><?= $v_purpose ?></td>
				<td class="center" ><?= $scan_center_name ?></td>
				<td style="text-align:left;"><?= $file_path ?></td>
				<td><?= $file_size ?></td>
				<td><?= $file_signature ?></td>
				<td><?= $md5 ?></td>

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