<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$start_date = $_REQUEST[start_date];
$end_date = $_REQUEST[end_date];
$scan_center_code = $_REQUEST[scan_center_code];
$paging = "99999";

$param = "";
if($start_date!="") $param .= ($param==""? "":"&")."start_date=".$start_date;
if($end_date!="") $param .= ($param==""? "":"&")."end_date=".$end_date;
if($scan_center_code!="") $param .= ($param==""? "":"&")."scan_center_code=".$scan_center_code;

$search_sql = "";
if($start_date != "" && $end_date != ""){

	$str_start_date = preg_replace("/[^0-9]*/s", "", $start_date)."000000";
	$str_end_date = preg_replace("/[^0-9]*/s", "", $end_date)."235959";

	$search_sql .= " AND v2.in_time between '{$str_start_date}' and '{$str_end_date}' ";
}

if($scan_center_code != "" ){
	$search_sql .= " AND v2.in_center_code = '{$scan_center_code}'  ";
}

$Model_User = new Model_User();

$args = array("search_sql" => $search_sql);
$Model_User->SHOW_DEBUG_SQL = false;
$total = $Model_User->getUserVistListCount($args);
$rows = $paging;			// 페이지당 출력갯수
$lists = $_list;				// 목록수
$page_count = ceil($total / $rows);
if (!$page || $page > $page_count) $page = 1;
$start = ($page - 1) * $rows;
$no = $total - $start;
$end = $start + $rows;

$order_sql = " ORDER BY v2.v_user_list_seq DESC ";

$args = array("order_sql" => $order_sql, "search_sql" => $search_sql, "end" => $end, "start" => $start);
$Model_User->SHOW_DEBUG_SQL = false;
$result = $Model_User->getUserVistList($args);

?>
<!--검색결과리스트-->
<table id='tblVisitList' class="list" style="margin-top:0px;min-width:1000px;" >
	<tr>
		<th class="num center" style='width:50px;'><?= $_LANG_TEXT['numtext'][$lang_code] ?></th>
		<th class="center"  style="min-width:60px"><? echo trsLang('소속구분','belongdivtext'); ?></th>
		<th class="center"  style='min-width:80px'><?= $_LANG_TEXT['visitor_name'][$lang_code] ?></th>
		<th class="center"  style='min-width:100px'><?= $_LANG_TEXT['belongtext'][$lang_code] ?></th>
		<th class="center" style='min-width:80px'><?= $_LANG_TEXT['entry_time'][$lang_code] ?></th>
		<th class="center" style='min-width:80px;'><?= $_LANG_TEXT['purpose_visit'][$lang_code] ?></th>
		<th class="center" style='min-width:80px'><? echo trsLang('검사장','scancentertext');?></th>
		<th class="center" style='width::100px'><?= $_LANG_TEXT['managertext'][$lang_code] ?></th>
		<th class="center" style='min-width::80px'><?= $_LANG_TEXT['managedepartmenttext'][$lang_code] ?></th>
		<th class="center" style='min-width::80px'><?= trsLang('임시출입증번호','temppassnumber'); ?></th>
		<th class="center" style='min-width::80px'><?= trsLang('자산반입','assetimporttext'); ?></th>
		<th class="center" style='min-width::80px'><?= trsLang('파일반입','fileimport'); ?></th>
	</tr>

		<?php
			if ($result) {
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

					$v_user_list_seq = $row['v_user_list_seq'];

					$v_user_name = aes_256_dec($row['v_user_name']);

					$v_user_name_en = $row['v_user_name_en'];

					$v_phone = $row['v_phone'];
					$v_email = $row['v_email'];

					//not used
					$v_company = $row['v_company'];
					$v_purpose = $row['v_purpose'];
					$manager_name = aes_256_dec($row['manager_name']);
					$manager_name_en = $row['manager_name_en'];

					$manager_dept = $row['manager_dept'];
					$additional_cnt = $row['additional_cnt'];

					$memo = $row['memo'];

					$in_time = $row['in_time'];

					$in_center_code = $row['in_center_code'];

					$in_center_name = $row['in_center_name'];

					$pass_card_no = $row['pass_card_no'];
						
					$in_goods_doc_no = $row['in_goods_doc_no'];
					$elec_doc_number = $row['elec_doc_number'];
					$label_name = $row['label_name'];
					$label_value = $row['label_value'];

					$in_file_cnt = "0";		

					$v_user_type = $row['v_user_type'];
					$str_v_user_type = $_CODE_V_USER_TYPE_DETAILS[$v_user_type];

					$v_user_belong = $row['v_user_belong'];

					
					$rnum = $row['rnum'];


					if (!empty($in_time) && $in_time !== 'null') {
						$in_time_vl = date('Y-m-d H:i', strtotime($in_time));
					} else {
						$in_time_vl = '';
					}

					$param_enc = ParamEnCoding("v_user_list_seq=" . $v_user_list_seq . ($param ? "&" : "") . $param);
					$str_memo = $memo;

					//phone
					if($_encryption_kind=="1"){
						$phone_no = $row['v_phone'];
						
					}else if($_encryption_kind=="2"){
					
						if($row['v_phone'] != ""){
							$phone_no = aes_256_dec($row['v_phone']);
						}
					}

			?>

			<tr>
				<td class="center" ><?= $no ?></td>
				<td class="center <? echo $display['user_info'];?>"  ><?= $str_v_user_type ?></td>
				<td class="center <? echo $display['user_info'];?>" >
						<?= $v_user_name ?><? if($v_user_name_en != "") echo " ($v_user_name_en)"; ?>
						<?if($additional_cnt > 0) echo " (+{$additional_cnt})";?>
				</td>
				<td class="center" ><?= $v_user_belong ?></td>
				<td class="center" ><?= $in_time_vl ?></td>
				<td class="center" ><?= $v_purpose ?></td>
				<td class="center" ><?= $in_center_name ?></td>
				<td class="center" ><?= $manager_name ?> (<?= $manager_name_en ?>)</td>
				<td class="center" ><?= $manager_dept ?></td>
				<td class="center" ><? echo ($pass_card_no=="" ? "-" : $pass_card_no)  ?></td>
				<td class="center" ><? echo ($in_goods_doc_no=="" ? "-" : $in_goods_doc_no) ?></td>
				<td class="center" ><? echo ($elec_doc_number=="" ? "-" : $elec_doc_number )?></td>

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
		<td colspan="15" align="center"><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
	</tr>
<?php
}
?>				
		
</table>