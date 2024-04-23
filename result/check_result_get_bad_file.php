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
require $_server_path . "/" . $_site_path . '/inc/class/model.php';
require $_server_path . "/" . $_site_path . '/inc/class/model_result.php';
// 


$Model_result = new Model_result();
$v_wvcs_seq = $_REQUEST['v_wvcs_seq'];
$page = $_REQUEST['page'];

$paging = 15;
if($paging == "") $paging = $_paging;
$args = array("v_wvcs_seq" => $v_wvcs_seq);
// var_dump($args);

$total = $Model_result->getUserVCSBadFileCount($args);
$paging = 20;
// $total=127;

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
	$order_sql = " ORDER BY v1.v_wvcs_seq DESC "; //check it
}
//$Model_result->SHOW_DEBUG_SQL = true;
$result = $Model_result->getUserVCSBadFileList($args);

$param = "v_wvcs_seq=".$v_wvcs_seq;

if ($paging != "") $param .= ($param == "" ? "" : "&") . "paging=" . $paging;
$cnt = 20;
$iK = 0;
$classStr = "";
?>
<!-- <div style="min-width:980px; max-width:2910px; width:100% ; margin:0 auto; " id="oper_list">
	<div style="min-height:200px;" class="container"> -->


		<!-- <div id='wrapper1' class="wrapper">
			<div id='div1' style='height:1px;'></div>
		</div>
		<div id='wrapper2' class="wrapper"> -->
			<!-- <table class="view" id='tblList' style="margin-top:10px"> -->
			<table class="list" id='' style="margin-top:1px;">
				<tr>
					<!-- <td style='text-align:left'><ul class='info'><li><div style='text-align:center'><? //echo $_LANG_TEXT['nodata'][$lang_code]; 
																																																?></div></li></ul></td> -->
					<th style='min-width:60px'><?= $_LANG_TEXT['numtext'][$lang_code] ?></th>
					<th style='min-width:80px'><?= $_LANG_TEXT['filepathtext'][$lang_code] ?></th>
					<th style='min-width:80px'><?= $_LANG_TEXT['filenametext'][$lang_code] ?></th>
					<th style='min-width:100px'><?= $_LANG_TEXT['filesizetext'][$lang_code] ?></th>
					<th style='min-width:80px'><?= $_LANG_TEXT["filesignature"][$lang_code]; ?></th>
					<th style='min-width:80px'><?= $_LANG_TEXT["fileidnntext"][$lang_code]; ?></th>
					<th style='min-width:80px'><?= $_LANG_TEXT["Md5"][$lang_code]; ?></th>


				</tr>
				<?php


				if ($result) {
					while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

						$cnt--;
						$iK++;

						$v_wvcs_file_seq = $row['v_wvcs_file_seq'];
						$file_path = $row['file_path'];
						$file_name_org = $row['file_name_org'];
						$file_size = $row['file_size'];
						$file_signature = $row['file_signature'];
						$file_id  = $row['file_id'];
						$md5  = $row['md5'];
						// $sha256  = $row['sha256'];

				?>
						<tr>
							<td><?php echo $no; ?></td>

							<td style="text-align:left;"><?= $file_path ?></td>
							<td><?= $file_name_org ?></td>
							<td><?= $file_size ?>KB</td>
							<td><?= $file_signature ?></td>
							<td><?= $file_id ?></td>
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
		<!-- </div> -->
		<div class="btn_wrap right">
			<!-- <? //$excel_down_url = $_www_server."/stat/file_import_history_excel.php?enc=".ParamEnCoding($param);
						?> -->
			<div class="right">
				<a href="#" id="#" class="btnexcel required-print-auth hide" onclick="getHTMLSplit('<?= $total ?>','<?= $excel_down_url ?>',this);"><?= $_LANG_TEXT["btnexceldownload"][$lang_code]; ?></a>
			</div>
		</div>

		<!--페이징-->
		<?php
if($total > 0) {
$param_enc = ($param)? "enc=".ParamEnCoding($param) : "";
print_pagelistNew3Func('file_scan_list',$_www_server."/result/get_scan_file.php",$page, $lists, $page_count, $param_enc, '', $total );
}



		?>
	<!-- </div>
</div> -->