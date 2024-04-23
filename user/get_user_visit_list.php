<?php
$page_name = "access_control";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

/* $src referer
* USER_VISIT_STATUS :  /user/access_status_user.php	--방문자 출입현황
* USER_VISIT_MGR_STATUS :  /user/access_status_manager.php	--담당자 방문자 출입현황
*/

$tab = $_REQUEST[tab];
$src = $_REQUEST[src];
$v_user_seq = $_REQUEST[v_user_seq];
$manager_name = $_REQUEST[manager_name];
$manager_name_en = $_REQUEST[manager_name_en];
$orderby = $_REQUEST[orderby];		
$page = $_REQUEST[page];
$paging = "10";

$param = "";
if($src!="") $param .= ($param==""? "":"&")."src=".$src;
if($v_user_seq!="") $param .= ($param==""? "":"&")."v_user_seq=".$v_user_seq;
if($manager_name!="") $param .= ($param==""? "":"&")."manager_name=".$manager_name;
if($manager_name_en!="") $param .= ($param==""? "":"&")."manager_name_en=".$manager_name_en;

if($v_user_seq != ""){
	$search_sql = " AND v1.v_user_seq = '".$v_user_seq."' ";
}

if($manager_name != "" || $manager_name_en != ""){
	$search_sql = " AND (v2.manager_name = '".aes_256_enc($manager_name)."' and v2.manager_name_en ='{$manager_name_en}') ";
}

$Model_User = new Model_User();

$args = array("search_sql" => $search_sql);
$Model_User->SHOW_DEBUG_SQL = false;
$total = $Model_User->getUserVistListCount($args);
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
$result = $Model_User->getUserVistList($args);

//컬럼보이고 안보이고 설정..
$display= array('user_info'=>"", 'manager_info'=>"");
if($src=="USER_VISIT_STATUS"){
	$display['user_info'] = "display-none";
}else if($src=="USER_VISIT_MGR_STATUS"){
	$display['manager_info'] = "display-none";
}
?>
<script type='text/javascript'>
$(function(){
	$("#uc_wrapper1").scroll(function(){
		$("#uc_wrapper2").scrollLeft($("#uc_wrapper1").scrollLeft());
	});
	$("#uc_wrapper2").scroll(function(){
		$("#uc_wrapper1").scrollLeft($("#uc_wrapper2").scrollLeft());
	});

	window.onresize = function(event) {
		var w = $("#tblVisitList").width();
		$("#uc_div1").width(w);
	};
});
</script>
<!--검색결과리스트-->
<div id='uc_wrapper1' class="wrapper">
<div id='uc_div1' style='height:1px;width:1000px'></div>
</div>
<div id='uc_wrapper2' class="wrapper">
<table id='tblVisitList' class="list" style="margin-top:0px;min-width:1000px;" >
	<tr>
		<th class="num center"><?= $_LANG_TEXT['numtext'][$lang_code] ?></th>
		<th class="center <? echo $display['user_info'];?>" style="width:100px"><? echo trsLang('소속구분','belongdivtext'); ?></th>
		<th class="center <? echo $display['user_info'];?>" style='min-width:80px'><?= $_LANG_TEXT['visitor_name'][$lang_code] ?></th>
		<th class="center <? echo $display['user_info'];?>" style='min-width:100px'><?= $_LANG_TEXT['belongtext'][$lang_code] ?></th>
		<th class="cente <? echo $display['user_info'];?>" style='min-width:80px'><?= $_LANG_TEXT['contactphonetext'][$lang_code] ?></th>
		<th class="center" style='min-width:100px'><?= $_LANG_TEXT['entry_time'][$lang_code] ?></th>
		<th class="center" style='min-width:100px;max-width:200px;'><?= $_LANG_TEXT['purpose_visit'][$lang_code] ?></th>
		<th class="center" style='min-width:100px'>
			<? echo trsLang('검사장','scancentertext');?>
		</th>
		<th class="center <? echo $display['manager_info'];?>" style='min-width::100px'><?= $_LANG_TEXT['managertext'][$lang_code] ?></th>
		<th class="center <? echo $display['manager_info'];?>" style='min-width::100px'><?= $_LANG_TEXT['managedepartmenttext'][$lang_code] ?></th>
		<th class="center" style='min-width::100px'><?= trsLang('임시출입증번호','temppassnumber'); ?></th>
		<th class="center" style='min-width::100px'><?= trsLang('자산반입','assetimporttext'); ?></th>
		<th class="center" style='min-width::100px'><?= trsLang('파일반입','fileimport'); ?></th>
		<th class="center" style='min-width::100px'><?= trsLang('상세','detailstext'); ?></th>
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
					<a class='text_link' onclick="sendPostForm('<? echo $_www_server?>/user/access_info.php?enc=<?= $param_enc ?>')">
						<?= $v_user_name ?><? if($v_user_name_en != "") echo " ($v_user_name_en)"; ?>
						<?if($additional_cnt > 0) echo " (+{$additional_cnt})";?>
					</a>
				</td>
				<td class="center <? echo $display['user_info'];?>" ><?= $v_user_belong ?></td>
				<td class="center <? echo $display['user_info'];?>" ><?= $phone_no ?></td>
				<td class="center" ><?= $in_time_vl ?></td>
				<td class="center" ><?= $v_purpose ?></td>
				<td class="center" ><?= $in_center_name ?></td>
				<td class="center <? echo $display['manager_info'];?>" ><?= $manager_name ?><? if (!empty($manager_name_en)) { ?> (<?php echo $manager_name_en; ?>)<?php } ?></td>
				<td class="center <? echo $display['manager_info'];?>" ><?= $manager_dept ?></td>
				<td class="center" ><? echo ($pass_card_no=="" ? "-" : $pass_card_no)  ?></td>
				<td class="center" ><? echo ($in_goods_doc_no=="" ? "-" : $in_goods_doc_no) ?></td>
				<td class="center" ><? echo ($elec_doc_number=="" ? "-" : $elec_doc_number )?></td>
				<td class="center" >
					<a class='text_link' onclick="sendPostForm('<? echo $_www_server?>/user/access_info.php?enc=<? echo ParamEnCoding('tab='.$tab.'&v_user_list_seq='.$v_user_list_seq)?>')" ><? echo trsLang('보기','btnview');?></a>
				</td>
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
</div>

<!--페이징-->
<?php
if($total > 0) {
	$param_enc = ($param)? "enc=".ParamEnCoding($param) : "";
	print_pagelistNew3Func('user_visit_list',$_www_server."/user/get_user_visit_list.php",$page, $lists, $page_count, $param_enc, '', $total );
}
?>
<? 
	$excel_name = trsLang('출입내역','entryExitHistory');

	if($src=="USER_VISIT_STATUS"){
		$excel_name .= "($v_user_name)";
	}else if($src=="USER_VISIT_MGR_STATUS"){
		$excel_name .= "($manager_name ".trsLang('담당자','managertext').")";
	}

	$excel_down_url = $_www_server . "/user/access_control_excel.php?enc=" . ParamEnCoding($param); 
?>
<div class="right" style='margin-top:<?=$total > 0 ? "-70" : "10" ?>px;'>
	<a href="javascript:void(0)" class="btnexcel required-print-auth hide" title='<? echo trsLang('출입내역','entryExitHistory')." ".$_LANG_TEXT["btnexceldownload"][$lang_code];?>' onclick="getHTMLSplit('<?= $total ?>','<?= $excel_down_url ?>','<?= $excel_name ?>',this);"><?= $_LANG_TEXT["btnexceldownload"][$lang_code]; ?></a>
</div>