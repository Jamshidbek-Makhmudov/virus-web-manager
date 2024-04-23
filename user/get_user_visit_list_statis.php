<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$manager_name = $_REQUEST[manager_name];
$manager_name_en = $_REQUEST[manager_name_en];
$orderby = $_REQUEST[orderby];		
$page = $_REQUEST[page];
$paging = "10";

$param = "";
if($manager_name!="") $param .= ($param==""? "":"&")."manager_name=".$manager_name;
if($manager_name_en!="") $param .= ($param==""? "":"&")."manager_name_en=".$manager_name_en;

$Model_User = new Model_User();

$search_sql = " and (v2.manager_name = '".aes_256_enc($manager_name)."'  and v2.manager_name_en ='{$manager_name_en}' ) ";

$args = array( "search_sql" => $search_sql);
$Model_User->SHOW_DEBUG_SQL = false;
$total = $Model_User->getUserVisitStatisListCount($args);

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
	$order_sql = " ORDER BY v2.v_user_list_seq DESC ";
}

$args = array("order_sql" => $order_sql, "search_sql" => $search_sql, "end" => $end, "start" => $start);
$Model_User->SHOW_DEBUG_SQL = false;
$result = $Model_User->getUserVisitStatisList($args);

?>

<!--검색결과리스트-->

<table id='tblVisitList' class="list" style="margin-top:0px;min-width:1000px;" >
	<tr>
		<th class="num"><?= $_LANG_TEXT['numtext'][$lang_code] ?></th>
		<th style='min-width:100px'><? echo trsLang('소속구분','belongdivtext'); ?></th>
		<th style='min-width:100px'><?= $_LANG_TEXT['visitor_name'][$lang_code] ?></th>
		<th style='min-width:200px'><?= $_LANG_TEXT['belongtext'][$lang_code] ?></th>
		<th style='min-width:100px'><?= $_LANG_TEXT['contactphonetext'][$lang_code] ?></th>
		<th style='min-width:200px'><?= $_LANG_TEXT['totalNumberVisit'][$lang_code] ?></th>
		<th style='min-width:200px'><?= $_LANG_TEXT['fileimporttimes'][$lang_code] ?></th>
		<th style='min-width:200px'><?= $_LANG_TEXT['dateFirstVisit'][$lang_code] ?></th>
		<th style='min-width:200px'><?= $_LANG_TEXT['finalVisit'][$lang_code] ?></th>
	</tr>

		<?php
			if ($result) {
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
					
					$v_user_seq = $row['v_user_seq'];
					$v_user_name = aes_256_dec($row['v_user_name']);
					$v_user_name_en = $row['v_user_name_en'];
					$v_user_belong = $row['v_user_belong'];
					$in_cnt = $row['in_cnt'];
					$file_import_cnt = $row['file_import_cnt'];
					$first_in_time = setDateFormat($row['first_in_time'],'Y-m-d H:i');
					$last_in_time = setDateFormat($row['last_in_time'],'Y-m-d H:i');

					if($v_user_name_en==""){
						$str_v_user_name = $v_user_name;
					}else{
						$str_v_user_name = $v_user_name." ($v_user_name_en)";
					}

					$v_user_type = $row['v_user_type'];
					$str_v_user_type = $_CODE_V_USER_TYPE_DETAILS[$v_user_type];


					if($_encryption_kind=="1"){
						$phone_no = $row['v_phone_decript'];
						
					}else if($_encryption_kind=="2"){
					
						if($row['v_phone'] != ""){
							$phone_no = aes_256_dec($row['v_phone']);
						}
					}

			?>

			<tr>

				<td class="center" ><?= $no ?></td>
				<td class="center" ><?= $str_v_user_type ?></td>
				<td class="center">
					<a onclick="sendPostForm('<? echo $_www_server?>/user/access_status_user.php?enc=<? echo paramEncoding('v_user_seq='.$v_user_seq)?>')" class='text_link'>
					<? echo $str_v_user_name ;?></a></td>
				<td class="center"><? echo $v_user_belong ;?></td>
				<td class="center"><? echo $phone_no ;?></td>
				<td class="center"><? echo $in_cnt ;?></td>
				<td class="center"><? echo $file_import_cnt ;?></td>
				<td class="center"><? echo $first_in_time ;?></td>
				<td class="center"><? echo $last_in_time ;?></td>
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
		<td colspan="9" align="center"><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
	</tr>
<?php
}
?>				
		
</table>

<!--페이징-->
<?php
if($total > 0) {
	$param_enc = ($param)? "enc=".ParamEnCoding($param) : "";
	print_pagelistNew3Func('user_visit_list_statis',$_www_server."/user/get_user_visit_list_statis.php",$page, $lists, $page_count, $param_enc, '', $total );
}
?>
<? 
	$excel_name = trsLang('출입자별통계','statisticsVisitor');
	$excel_name .= "($manager_name ".trsLang('담당자','managertext').")";

	$excel_down_url = $_www_server . "/user/get_user_visit_list_statis_excel.php?enc=" . ParamEnCoding($param); 
?>
<div class="right" style='margin-top:<?=$total > 0 ? "-70" : "10" ?>px;'>
	<a href="javascript:void(0)" class="btnexcel required-print-auth hide" title='<? echo trsLang('출입자별통계','statisticsVisitor')." ".$_LANG_TEXT["btnexceldownload"][$lang_code];?>' onclick="getHTMLSplit('<?= $total ?>','<?= $excel_down_url ?>','<?= $excel_name ?>',this);"><?= $_LANG_TEXT["btnexceldownload"][$lang_code]; ?></a>
</div>