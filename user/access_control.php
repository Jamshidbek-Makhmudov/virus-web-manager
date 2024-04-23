<?php
$page_name = "access_control";
$page_tab_name = "access_control";

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI']) - 1);
$_apos = stripos($_REQUEST_URI,  "/");
if ($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
include_once $_server_path . "/" . $_site_path . "/inc/header.inc";
include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";

$page = $_REQUEST['page'];	// 페이지
$param = "tab=".$page_tab_name;
$today = date("Y-m-d");
$last_month = date("Y-m-d", strtotime($today . " -1 month"));

setPageParams($param, 'scan_center_code');
setPageParams($param, 'searchopt');
setPageParams($param, 'searchkey');
setPageParams($param, 'searchandor');
setPageParams($param, 'searchandor0');
setPageParams($param, 'orderby');
setPageParams($param, 'start_date', $last_month);
setPageParams($param, 'end_date', $today);
setPageParams($param, 'paging', $_paging);
setPageParams($param, 'visit_div');

for ($i = 0; $i <= 3; $i++) {
	setPageParams($param, "searchandor{$i}");
	setPageParams($param, "searchopt{$i}");
	setPageParams($param, "searchkey{$i}");
}


//검색 로그 기록
$proc_name = $_POST['proc_name'];

if($proc_name != ""){
	$work_log_seq = WriteAdminActLog($proc_name, 'SEARCH');
}

$seq_val = "v_user_list_seq";
$Model_User = new Model_User();
$Model_User->SHOW_DEBUG_SQL = false;

$show_visitor_out = false;
?>
<script language="javascript">
$(function() {
	$("#start_date").datepicker(pickerOpts);
	$("#end_date").datepicker(pickerOpts);
});
</script>
<div id="user_list">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				<h1><span id='page_title'><?= $_LANG_TEXT['access_control_theme'][$lang_code] ?> <small><? echo trsLang('전체','alltext');?></small></span></h1>
			</div>
			<span class="line"></span>
		</div>

		<!--tab 메뉴-->
		<? require_once(__DIR__."/access_control_tab.php");?>

		<!--검색폼-->
		<form name="searchForm" action="<?php echo $_SERVER[PHP_SELF] ?>" method="POST">
			<input type="hidden" name="page" value="">
			<input type='hidden' name='proc_name' id='proc_name'>
			<table class="search">
				<tr>
					<th style='width:100px;'><?= $_LANG_TEXT['entry_date'][$lang_code] ?> </th>
					<td style='width:350px;'>
						<input type="text" name="start_date" id="start_date" class="frm_input" placeholder="" style="width:100px" value="<?= $start_date ?>" maxlength="10"> ~
						<input type="text" name="end_date" id="end_date" class="frm_input" placeholder="" style="width:100px" value="<?= $end_date ?>" maxlength="10">
					</td>
					<th style='min-width:100px;'>
						<? echo trsLang('구분','gubuntext');?>
					</th>
					<td style='width:230px'>
						<select name='visit_div' id='visit_div' style='max-width:200px;'>
							<option value=''><?=$_LANG_TEXT["alltext"][$lang_code];?></option>
							<option value='OUT_VISIT' <? if($visit_div=="OUT_VISIT") echo "selected";?>><? echo trsLang('방문객','m_visitor');?></option>
							<option value='EMP_PASS' <? if($visit_div=="EMP_PASS") echo "selected";?>><? echo trsLang('임직원','executives');?> <? echo trsLang('임시출입증발급','tempoprary_pass_text');?></option>
							<option value='OUT_VCS' <? if($visit_div=="OUT_VCS") echo "selected";?>><? echo trsLang('임직원','executives');?> <? echo trsLang('파일반입','fileimport');?></option>
						<select>
					</td>
					<th style='width:100px;'>
						<? echo trsLang('검사장','scancentertext');?>
					</th>
					<td style='min-width:200px'>
						<select name='scan_center_code' id='scan_center_code'>
							<option value=''><?=$_LANG_TEXT["alltext"][$lang_code];?></option>
							<?php
							$Model_manage = new Model_manage;
							$result = $Model_manage->getCenterList();
							
							if($result){
								while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
									$_scan_center_code = $row['scan_center_code'];
									$_scan_center_name = $row['scan_center_name'];
							?>
							<option value='<?=$_scan_center_code?>' <?if($_scan_center_code==$scan_center_code) echo "selected" ;?>><?=$_scan_center_name?></option>
							<?php
								}
							}
						?>
						</select>
					</td>
				</tr>
				<?
				//검색키워드목록
				$searchopt_list = array(
					"v_user_name"=>trsLang("이름","nametext")
					,"v_user_belong"=>trsLang("소속","belongtext")
					,"mgr_name"=>trsLang("임직원","executives")
					,"mgr_dept"=>trsLang("임직원 소속","employee_affiliation")
					//,"v_phone"=>trsLang("연락처","contactphonetext")
				);
				?>
				<tr>
					<th>
						<? echo trsLang('키워드검색','keywordsearchtext');?>
					</th>
					<td colspan="5" style='padding:5px 13px'>
						<select name="searchopt" id="searchopt" style='max-width:150px;height:31px;margin-top:1px;'>
							<option value="" <?php if($searchopt == "") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['select_search_item'][$lang_code]?></option>
							<?
							foreach($searchopt_list as $key=>$name){
								$selected = $searchopt==$key ? "selected" : "";
								echo "<option value='{$key}' {$selected} >{$name}</option>";
							}
							?>
						</select>


						<input type="text" class="frm_input" style="width:50%" name="searchkey" id="searchkey" value="<?= $searchkey ?>" maxlength="50">
						<input type="submit"  value="<?= $_LANG_TEXT['usersearchtext'][$lang_code] ?>" class="btn_submit" onclick="return SearchSubmit(document.searchForm);">
						<input type="button" value="<?= $_LANG_TEXT['userdetailsearchtext'][$lang_code] ?>" class="btn_submit_no_icon" onclick="$('#search_detail').toggle()">
						<input type="button" value="<? echo trsLang('초기화','btnclear');?>" class="btn_submit_no_icon" onclick="location.href='<? echo $_www_server?>/user/access_control.php'">

						<!--상세검색-->
						<?
							$search_detail_visible = ($searchopt1&&$searchkey1 || $searchopt2&&$searchkey2 || $searchopt3&&$searchkey3);
						?>
						<div id='search_detail' style='<? if($search_detail_visible==false) echo "display:none";?>'>
							<? for($i = 1 ; $i < 4 ; $i++){?>
							<div  style='margin-top:5px;'>
								
								<select name="searchandor<? echo $i?>" id="searchandor<? echo $i?>" style='height:31px;margin-top:1px;'>
									<option value='AND' <? if(${"searchandor".$i}=="AND") echo "selected";?>>AND</option>
									<option value='OR' <? if(${"searchandor".$i}=="OR") echo "selected";?>>OR</option>
								</select>
								<select name="searchopt<? echo $i?>" id="searchopt<? echo $i?>"  style='max-width:150px;height:31px;margin-top:1px;'>
									<option value="" <?php if(${"searchopt".$i} == "") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['select_search_item'][$lang_code]?></option>
									<?
									foreach($searchopt_list as $key=>$name){
										$selected = (${"searchopt".$i}==$key) ? "selected" : "";
										echo "<option value='{$key}' {$selected} >{$name}</option>";
									}
									?>
								</select>
								<input style="width:50%" type="text" class="frm_input" name="searchkey<? echo $i?>" id="searchkey<? echo $i?>" maxlength="50" value='<? echo ${'searchkey'.$i}?>'>
							</div>
							<?}?>
						</div>
					</td>
				</tr>
			</table>
			<!--  -->

			<?php
			//검색항목
			 $search_sql = "";
      		if ($start_date != "" && $end_date != "") {
				$search_sql .= " and v2.in_time between '" . str_replace('-', '', $start_date) . "000000' AND '" . str_replace('-', '', $end_date) . "235959' ";
			}

			if($scan_center_code !=""){ 
				$search_sql .= " and v2.in_center_code = '{$scan_center_code}'  ";
			}

			if($v_user_seq !=""){
				$search_sql .= " and v1.v_user_seq = '{$v_user_seq}'  ";
			}

			if($visit_div == "OUT_VISIT"){
				$search_sql .= " and v2.v_user_type = 'OUT' and v2.v_type like 'VISIT%'  ";
			}else if($visit_div == "OUT_VCS"){
				$search_sql .= " and v2.v_user_type = 'OUT' and v2.v_type like 'VCS%'  ";
			}else  if($visit_div == "EMP_PASS"){
				$search_sql .= " and v2.v_user_type = 'EMP' and v3.pass_card_no > ''  ";
			}
			
			//키워드검색
			$searchkey_sql= array(
				"v_user_belong" => " v2.v_user_belong like '%{?}%' "
				,"mgr_dept" => " v2.manager_dept like '%{?}%' "
				,"pass_number" => " v3.pass_card_no = '{?}' "
			);
			 
			$searchandor0 = " and ( ";
			$searchopt0   = $searchopt;
			$searchkey0   = $searchkey;
			$keyword_search_sql = "";

			for($i = 0 ; $i < 4 ;$i++){
				$_searchopt = ${"searchopt".$i};	
				$_searchkey = ${"searchkey".$i};	
				$_searchandor = ${"searchandor".$i};	
				
				if($_searchopt != "" && $_searchkey != ""){
					if($_searchopt=="v_phone"){
						if($_encryption_kind=="1"){
							$keyword_search_sql .= $_searchandor." dbo.fn_DecryptString(v1.v_phone) like '%{?}%' ";
						}else if($_encryption_kind=="2"){
							$keyword_search_sql .= $_searchandor." v1.v_phone = '".aes_256_enc($_searchkey)."' ";
						}
					}else if($_searchopt=="v_user_name"){
						if($_encryption_kind=="1"){
							$keyword_search_sql .= $_searchandor." (dbo.fn_DecryptString(v2.v_user_name) like '%{?}%' or  v2.v_user_name_en like '%{$_searchkey}%') ";
						}else if($_encryption_kind=="2"){
							$keyword_search_sql .= $_searchandor." (v2.v_user_name = '".aes_256_enc($_searchkey)."' or  v2.v_user_name_en like '%{$_searchkey}%') ";
						}
					}else if($_searchopt=="mgr_name"){
						if($_encryption_kind=="1"){
							$keyword_search_sql .= $_searchandor." (dbo.fn_DecryptString(v2.manager_name) like '%{?}%' or  v2.manager_name_en like '%{$_searchkey}%') ";
						}else if($_encryption_kind=="2"){
							$keyword_search_sql .= $_searchandor." (v2.manager_name = '".aes_256_enc($_searchkey)."' or  v2.manager_name_en like '%{$_searchkey}%') ";
						}
					}else{
						$keyword_search_sql .= " {$_searchandor} ".str_replace('{?}', $_searchkey, $searchkey_sql[$_searchopt]);
					}
				}
			}

			//echo $search_sql ;

			if($keyword_search_sql != ""){
				$search_sql .= $keyword_search_sql.")";
			}
			
			$args = array("search_sql" => $search_sql);
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
			$result = $Model_User->getUserVistList($args);

			$cnt = 20;
			$iK = 0;
			$classStr = "";

			//excel file name while downloading
			$excel_name = $_LANG_TEXT['access_control_theme'][$lang_code]."(".trsLang('전체','alltext').")";
			?>
			<!-- for test -->

			<div class="btn_wrap right" style='margin-bottom:10px;'>
				<? $excel_down_url = $_www_server . "/user/access_control_excel.php?enc=" . ParamEnCoding($param); ?>
				<div class="right">
					<a  href="javascript:void(0)" class="btnexcel required-print-auth hide" onclick="getHTMLSplit('<?= $total ?>','<?= $excel_down_url ?>','<?= $excel_name ?>',this);"><?= $_LANG_TEXT["btnexceldownload"][$lang_code]; ?></a>
				</div>

				<div style='margin-right:10px; line-height:30px; ' class="right">
					Results : <span style='color:blue'><?= number_format($total) ?></span> /
					Records : <select name='paging' onchange="searchForm.submit();">
						<option value='20' <? if ($paging == '20') echo "selected"; ?>>20</option>
						<option value='40' <? if ($paging == '40') echo "selected"; ?>>40</option>
						<option value='60' <? if ($paging == '60') echo "selected"; ?>>60</option>
						<option value='80' <? if ($paging == '80') echo "selected"; ?>>80</option>
						<option value='100' <? if ($paging == '100') echo "selected"; ?>>100</option>
					</select>
				</div>

			</div>
		</form>

		<!--검색결과리스트-->
		<div id='wrapper1' class="wrapper">
			<div id='div1' style='height:1px;'></div>
		</div>
		<div id='wrapper2' class="wrapper">
		<table id='tblList' class="list" style="margin-top:10px;margin:0px auto; white-space: nowrap;">
			<tr>
				<th class="num center" style="width:70px;min-width:70px"><?= $_LANG_TEXT['numtext'][$lang_code] ?></th>
				<th class="center" style="width:80px;min-width:80px"><?= $_LANG_TEXT['gubuntext'][$lang_code] ?></th>
				<th class="center" style='min-width:80px'><?= $_LANG_TEXT['nametext'][$lang_code] ?></th>
				<th class="center" style='min-width:100px'><?= $_LANG_TEXT['belongtext'][$lang_code] ?></th>
				<!--<th class="center" style='min-width:80px'><?= $_LANG_TEXT['contactphonetext'][$lang_code] ?></th>-->
				<th class="center" style='min-width:100px;max-width:200px;'><?= $_LANG_TEXT['purpose_visit'][$lang_code] ?></th>
				<th class="center" style='min-width:100px'><?= $_LANG_TEXT['scancentertext'][$lang_code] ?></th>
				<th class="center" style='width:130px;min-width:130px'><?= $_LANG_TEXT['entry_time'][$lang_code] ?></th>
				<th class="center" style='min-width:100px'><?= $_LANG_TEXT['executives'][$lang_code] ?></th>
				<th class="center" style='min-width:100px'><?= $_LANG_TEXT['employee_affiliation'][$lang_code] ?></th>
				<?php if ($show_visitor_out) { ?>
				<th class="center" style='width:130px;min-width:130px'><?= $_LANG_TEXT['outofficetimetext'][$lang_code] ?></th>
				<th class="center" style='width:130px;min-width:130px'><?= $_LANG_TEXT['outofficeconfirmertext'][$lang_code] ?></th>
				<th class="center" style='width:80px;min-width:80px'><?= $_LANG_TEXT['statustext'][$lang_code] ?></th>
				<?php } ?>
				<th class="center" style='width:60px;min-width:60px'><?= $_LANG_TEXT['memotext'][$lang_code] ?></th>
			</tr>
			<?php
			if ($result) {
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
					$cnt--; $iK++;

					@extract($row);

					/* $row => (v_user_list_seq, v_user_name, v_user_name_en, v_phone, v_email, v_company, v_purpose, v_user_belong, v_user_type, v_type
						, manager_name, manager_name_en, manager_dept, additional_cnt, memo, in_time, in_center_code, in_center_name, pass_card_no
						, elec_doc_number, label_name, label_value, visit_date, visit_center_desc, visit_status
						, out_time, out_access_time, out_access_emp_id, out_access_emp_name, rnum, in_goods_doc_no)
	 				*/

					$v_user_name  = ($_encryption_kind == "1") ? $v_user_name : aes_256_dec($v_user_name);
					$manager_name = ($_encryption_kind == "1") ? $manager_name : aes_256_dec($manager_name);	
					$phone_no     = ($_encryption_kind == "1") ? $v_phone : aes_256_dec($v_phone);

					$carryin_info = array();

					if ($in_goods_cnt > 0) {
						$carryin_info[] = trsLang("자산","asset"). "($in_goods_cnt)";
					}

					if ($elec_doc_number != "") {
						$carryin_info[] = trsLang("파일","file");
					}

					$str_memo         = $memo;
					$str_out_time     = "";
					$str_visit_div    = "";
					$str_visit_status = $_CODE_VISIT_STATUS[$visit_status];
					$str_v_user_type  = $_CODE_V_USER_TYPE[$v_user_type];
					$str_carryin_info = (sizeof($carryin_info) > 0) ? implode(",<BR>", $carryin_info) : "-";
					$str_out_confirm  = ($visit_status == "0") ? "{$out_access_emp_name} ({$out_access_emp_id})" : "";

					$str_user_name    = $v_user_name . (!empty($v_user_name_en) ? " ($v_user_name_en)":"") . (($additional_cnt > 0) ? " (+{$additional_cnt})" : "");

					if ($v_user_type == "OUT") {
						if (($v_type == "VCS") && ($label_value !="")) {
							$str_visit_div .= trsLang('임직원','executives')." ".trsLang('파일반입', 'fileimport');
						} else {
							$str_visit_div = trsLang('방문객','m_visitor');
						}
					} else {
						$str_visit_div = trsLang('임직원','executives');
						
						if ($pass_card_no !="") {
							$str_visit_div .= " ".trsLang('임시출입증발급', 'tempoprary_pass_text');
						}
					}

					if ($visit_status == "1") {
						$str_out_time = "<a href='javascript:void(0)' class='required-update-auth text_link' onclick='procVisitOut()' data-seq='".$v_user_list_seq."'  title='".trsLang('퇴실처리','outofficeaccesstext')."'>[".trsLang('퇴실처리','outofficeaccesstext')."]</a>";
					} else if ($visit_status=="0") {
						$str_out_time = "<a href='javascript:void(0)' class='required-update-auth text_link' onclick='cancelVisitOut()' data-seq='".$v_user_list_seq."' title='".trsLang('퇴실처리취소','outofficeaccesscanceltext')."'>".setDateFormat($row['out_time'],'Y-m-d H:i')."</a>";
					} else {
						$str_out_time =  "-";
					}

					if (!empty($in_time) && $in_time !== 'null') {
						$in_time_vl = date('Y-m-d H:i', strtotime($in_time));
					} else {
						$in_time_vl = '';
					}

					$param_user    = ParamEnCoding("v_user_list_seq={$v_user_list_seq}" . ($param ? "&" : "") . $param);
					$param_manager = ParamEnCoding("manager_name={$manager_name}&manager_name_en={$manager_name_en}&tab={$page_tab_name}");

					$link_user    = "{$_www_server}/user/access_info.php?enc={$param_user}";
					$link_manager = "{$_www_server}/user/access_status_manager.php?enc={$param_manager}";
					$str_manager  = $manager_name . (empty($manager_name_en) ? "" : " ({$manager_name_en})");
			?>
			<tr>
				<td class="center" ><?= $no--; ?></td>
				<td class="center" ><?= $str_visit_div ?></td>
				<td class="center" ><a class='text_link' onclick="sendPostForm('<? echo $link_user; ?>')"><?php echo $str_user_name; ?></a></td>
				<td class="center" ><?= $v_user_belong ?></td>
				<!-- <td class="center" ><?= $phone_no ?></td> -->
				<td class="center" ><?= $v_purpose ?></td>
				<td class="center" ><?= $in_center_name ?></td>
				<td class="center" ><?= $in_time_vl ?></td>
				<td class="center" ><a class='text_link' onclick="sendPostForm('<? echo $link_manager; ?>')"><? echo $str_manager; ?></a></td>
				<td class="center" ><?= $manager_dept ?></td>
				<?php if ($show_visitor_out) { ?>
				<td class="center" ><?= $str_out_time ?></td>
				<td class="center" ><?= $str_out_confirm ?></td>
				<td class="center" ><? echo $str_visit_status;?></td>
				<?php } ?>
				<td class="center viewlayer_parent" >
					<?	//메모 쓰기 권한
						$memo_click_event = "appendRow_Memo('{$v_user_list_seq}','{$seq_val}')";
						$memo_class = "text_link";
						$memo_button = ($str_memo == "" ? trsLang('쓰기', 'btnwrite') : "<i class='fa fa-comments'></i>");
					?>
					<span class='<? echo $memo_class;?> required-update-auth' onmouseover="viewlayer(true, 'moverlayerLock_<? echo $no ?>');" onmouseout="viewlayer(false, 'moverlayerLock_<? echo $no ?>');" onclick="<? echo $memo_click_event;?>"><? echo $memo_button?></span>
					<? if ($str_memo > "") { ?>
						<div id="moverlayerLock_<? echo $no ?>" class="viewlayer left_view" style="display: none;"><? echo $str_memo; ?></div>
					<? } ?>
				</td>
			</tr>

			<?php
				}
			}

			if ($result) {
				sqlsrv_free_stmt($result);
			}

			sqlsrv_close($wvcs_dbcon);
			
			if($total < 1) {
				$colspan = 10;

				if ($show_visitor_out) {
					$colspan += 3;
				}
			?>
			<tr>
				<td colspan="<?php echo $colspan; ?>" align='center'><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
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
			print_pagelistNew3($page, $lists, $page_count, $param_enc, '', $total );
		}
		?>
		<!-- </table> -->


	</div>
</div>
<!--메모전송폼-->
<form id='frmMemo' method='post' action=''></form>
<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>