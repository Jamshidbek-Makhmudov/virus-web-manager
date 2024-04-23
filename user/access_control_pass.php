<?php
$page_name = "access_control";
$page_tab_name = "access_control_pass";

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

$searchandor = $_REQUEST[searchandor];

$scan_center_code = $_REQUEST[scan_center_code];	

$searchopt = $_REQUEST[searchopt];	// 검색옵션
$searchkey = $_REQUEST[searchkey];	// 검색어
$orderby = $_REQUEST[orderby];		// 정렬순서
$page = $_REQUEST[page];			// 페이지
$paging = $_REQUEST[paging];
$start_date = $_REQUEST[start_date];
$end_date = $_REQUEST[end_date];
$v_user_type =  $_REQUEST[v_user_type];
$uncovered = $_REQUEST[uncovered];

$searchopt1 = $_REQUEST[searchopt1];	// 검색옵션
$searchandor1 = $_REQUEST[searchandor1];
$searchkey1 = $_REQUEST[searchkey1];	// 검색어
$searchopt2 = $_REQUEST[searchopt2];	// 검색옵션
$searchandor2 = $_REQUEST[searchandor2];
$searchkey2 = $_REQUEST[searchkey2];	// 검색어
$searchopt3 = $_REQUEST[searchopt3];	// 검색옵션
$searchandor3 = $_REQUEST[searchandor3];
$searchkey3 = $_REQUEST[searchkey3];	// 검색어

if ($useyn == "") $useyn = "Y";
if ($paging == "") $paging = $_paging;

if ($start_date == "") $start_date = date("Y-m-d", strtotime(date("Y-m-d") . " -1 month"));
if ($end_date == "") $end_date = date("Y-m-d");

$param = "tab=".$page_tab_name;
if ($scan_center_code != "") $param .= ($param == "" ? "" : "&") . "scan_center_code=" . $scan_center_code;

if ($searchopt != "") $param .= ($param == "" ? "" : "&") . "searchopt=" . $searchopt;
if ($searchkey != "") $param .= ($param == "" ? "" : "&") . "searchkey=" . $searchkey;
if ($orderby != "") $param .= ($param == "" ? "" : "&") . "orderby=" . $orderby;
if ($start_date != "") $param .= ($param == "" ? "" : "&") . "start_date=" . $start_date;
if ($end_date != "") $param .= ($param == "" ? "" : "&") . "end_date=" . $end_date;
if ($v_user_type != "") $param .= ($param == "" ? "" : "&") . "v_user_type=" . $v_user_type;
if ($uncovered != "") $param .= ($param == "" ? "" : "&") . "uncovered=" . $uncovered;


if($paging!="") $param .= ($param==""? "":"&")."paging=".$paging;

if($searchopt1!="") $param .= ($param==""? "":"&")."searchopt1=".$searchopt1;
if($searchkey1!="") $param .= ($param==""? "":"&")."searchkey1=".$searchkey1;
if($searchandor1!="") $param .= ($param==""? "":"&")."searchandor1=".$searchandor1;

if($searchopt2!="") $param .= ($param==""? "":"&")."searchopt2=".$searchopt2;
if($searchkey2!="") $param .= ($param==""? "":"&")."searchkey2=".$searchkey2;
if($searchandor2!="") $param .= ($param==""? "":"&")."searchandor2=".$searchandor2;

if($searchopt3!="") $param .= ($param==""? "":"&")."searchopt3=".$searchopt3;
if($searchkey3!="") $param .= ($param==""? "":"&")."searchkey3=".$searchkey3;
if($searchandor3!="") $param .= ($param==""? "":"&")."searchandor3=".$searchandor3;

//검색 로그 기록
$proc_name = $_POST['proc_name'];
if($proc_name != ""){
	$work_log_seq = WriteAdminActLog($proc_name,'SEARCH');
}

$seq_val = "v_user_list_seq";
$Model_User = new Model_User();
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
				<h1><span id='page_title'><?= $_LANG_TEXT['access_control_theme'][$lang_code] ?>  <small><? echo trsLang('임시출입증발급','tempoprary_pass_text');?></small></span></h1>
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
						<input type="text" name="start_date" id="start_date" class="frm_input" placeholder="" style="width:100px"
							value="<?= $start_date ?>" maxlength="10"> ~
						<input type="text" name="end_date" id="end_date" class="frm_input" placeholder="" style="width:100px"
							value="<?= $end_date ?>" maxlength="10">
					</td>
					<th style='min-width:100px;'>
						<? echo trsLang('소속구분','belongdivtext');?>
					</th>
					<td style='width:200px'>
						<select name='v_user_type' id='v_user_type' style='max-width:150px;'>
							<option value=''><?=$_LANG_TEXT["alltext"][$lang_code];?></option>
							<?
							foreach($_CODE_V_USER_TYPE as $key=>$name){
								echo "<option value='$key' ".($key==$v_user_type ? "selected" : "").">".$name."</option>";
							}
							?>
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
							<option value='<?=$_scan_center_code?>' <?if($_scan_center_code==$scan_center_code) echo "selected" ;?>
								><?=$_scan_center_name?></option>
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
					//,"v_phone"=>trsLang("연락처","contactphonetext")
					,"mgr_name"=>trsLang("임직원","executives")
					,"mgr_dept"=>trsLang("임직원 소속","employee_affiliation")
					,"pass_number"=>trsLang("임시출입증번호","temppassnumber")
				);
				?>
				<tr>
					<th>
						<? echo trsLang('키워드검색','keywordsearchtext');?>
					</th>
					<td colspan="5" style='padding:5px 13px'>
						<select name="searchopt" id="searchopt" style='max-width:150px;'>
							<option value="" <?php if($searchopt == "") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['select_search_item'][$lang_code]?></option>
							<?
							foreach($searchopt_list as $key=>$name){
								$selected = $searchopt==$key ? "selected" : "";
								echo "<option value='{$key}' {$selected} >{$name}</option>";
							}
							?>
						</select>

						<input type="text" class="frm_input" style="width:50%" name="searchkey" id="searchkey"
							value="<?= $searchkey ?>" maxlength="50">
						<input type="submit" value="<?= $_LANG_TEXT['usersearchtext'][$lang_code] ?>" class="btn_submit"
							onclick="return SearchSubmit(document.searchForm);">
						<input type="button" value="<?= $_LANG_TEXT['userdetailsearchtext'][$lang_code] ?>"
							class="btn_submit_no_icon" onclick="$('#search_detail').toggle()">
						<input name="uncovered" id="uncovered" type="submit" value="<?= trsLang('미회수조회','unrecovered_views'); ?>" style="cursor:pointer" class="btn_submit_no_icon" onclick="return SearchSubmit(document.searchForm);">
						<input type="button" value="<? echo trsLang('초기화','btnclear');?>" class="btn_submit_no_icon" onclick="location.href='<? echo $_www_server?>/user/access_control_pass.php'">

							<!--상세검색-->
							<?
								$search_detail_visible = ($searchopt1&&$searchkey1 || $searchopt2&&$searchkey2 || $searchopt3&&$searchkey3);
							?>
							<div id='search_detail' style='<? if($search_detail_visible==false) echo "display:none";?>'>
								<? for($i = 1 ; $i < 4 ; $i++){?>
								<div  style='margin-top:5px;'>
									
									<select name="searchandor<? echo $i?>" id="searchandor<? echo $i?>" >
										<option value='AND' <? if(${"searchandor".$i}=="AND") echo "selected";?>>AND</option>
										<option value='OR' <? if(${"searchandor".$i}=="OR") echo "selected";?>>OR</option>
									</select>
									<select name="searchopt<? echo $i?>" id="searchopt<? echo $i?>"  style='max-width:150px;'>
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
			 $search_sql = "and v3.pass_card_no > '' ";
			 if ($start_date != "" && $end_date != "") {
				$search_sql .= " and v2.in_time between '" . str_replace('-', '', $start_date) . "000000' AND '" . str_replace('-', '', $end_date) . "235959' ";
			}
			if($scan_center_code !=""){ 

				$search_sql .= " and v2.in_center_code = '{$scan_center_code}'  ";
			}

			if($v_user_type != ""){
				$search_sql .= " and v2.v_user_type = '{$v_user_type}'  ";
			}

			if($uncovered != ""){
				$search_sql .= " and isnull(v3.pass_card_return_date,'') = ''  ";
			}
			
			//키워드검색
			$searchkey_sql= array(
				"v_user_belong" => " v2.v_user_belong like '%{?}%' "
				,"mgr_dept" => " v2.manager_dept like '%{?}%' "
				,"pass_number" => " v3.pass_card_no = '{?}' "
			);
			 
			$searchandor0 = " and ( ";
			$searchopt0 = $searchopt;
			$searchkey0 = $searchkey;
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

			if($keyword_search_sql != ""){
				$search_sql .= $keyword_search_sql.")";
			}

			$args = array("search_sql" => $search_sql);
			$total = $Model_User->getUserVistListCount_Pass($args);
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
			$result = $Model_User->getUserVistList_Pass($args);

			$cnt = 20;
			$iK = 0;
			$classStr = "";

			//excel file name while downloading
			$excel_name = $_LANG_TEXT['access_control_theme'][$lang_code]."(".trsLang('임시출입증발급','tempoprary_pass_text').")";
			?>
			<!-- for test -->

			<div class="btn_wrap right" style='margin-bottom:10px;'>
				<? $excel_down_url = $_www_server . "/user/access_control_pass_excel.php?enc=" . ParamEnCoding($param); ?>
				<div class="right">
					<a href="javascript:void(0)" id="rental_details_excel" class="btnexcel required-print-auth hide" onclick="getHTMLSplit('<?= $total ?>','<?= $excel_down_url ?>','<?= $excel_name ?>',this);"><?= $_LANG_TEXT["btnexceldownload"][$lang_code]; ?></a>
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
		<table class="list" style="margin-top:10px; ">
			<tr>
				<th class="num center"><?= $_LANG_TEXT['numtext'][$lang_code] ?></th>
				<th class="center" style="width:100px"><? echo trsLang('소속구분','belongdivtext'); ?></th>
				<th class="center" style='min-width:80px'><?= $_LANG_TEXT['nametext'][$lang_code] ?></th>
				<th class="center" style='min-width:100px'><?= $_LANG_TEXT['belongtext'][$lang_code] ?></th>
				<!--<th class="center" style='min-width:80px'><?= $_LANG_TEXT['contactphonetext'][$lang_code] ?></th>-->
				<th class="center" style='min-width:100px;max-width:200px;'><?= $_LANG_TEXT['issuepurposetext'][$lang_code] ?></th>
				<th class="center" style='min-width:100px'>
					<? echo trsLang('검사장','scancentertext');?>
				</th>
				<th class="center" style='min-width:100px'><?= $_LANG_TEXT['entry_time'][$lang_code] ?></th>
				<th class="center" style='min-width::100px'><?= $_LANG_TEXT['executives'][$lang_code] ?></th>
				<th class="center" style='min-width::100px'><?= $_LANG_TEXT['employee_affiliation'][$lang_code] ?></th>
				<th class="center" style='min-width::100px'><?= $_LANG_TEXT['temppassnumber'][$lang_code] ?></th>
				<th class="center" style='min-width::100px'><?= trsLang('회수','recovery_text'); ?></th>
				<th class="center" style='min-width::60px'><?= $_LANG_TEXT['memotext'][$lang_code] ?></th>

			</tr>

						<?php
			if ($result) {
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
					$cnt--;
					$iK++;

					$v_user_list_seq = $row['v_user_list_seq'];

					$v_user_name = aes_256_dec($row['v_user_name']);

					$v_user_name_en = $row['v_user_name_en'];

					$v_phone = $row['v_phone'];
					$v_email = $row['v_email'];

					//not used
					$v_company = $row['v_company'];
					$v_purpose = $row['v_purpose'];
					$manager_name =  aes_256_dec($row['manager_name']);
					$manager_name_en = $row['manager_name_en'];

					$manager_dept = $row['manager_dept'];
					$additional_cnt = $row['additional_cnt'];

					$memo = $row['memo'];

					$in_time = $row['in_time'];

					$in_center_code = $row['in_center_code'];

					$in_center_name = $row['in_center_name'];

					$pass_card_no = $row['pass_card_no'];
					$pass_card_return_date =  $row['pass_card_return_date'];	
					
						$str_pass_card_return_date = setDateFormat($row['pass_card_return_date'], 'Y-m-d');

					if($pass_card_return_date==""){
						$pass_return_proc = "<span class='text_link required-update-auth hide' onclick=\"visitorPassReturn('{$v_user_list_seq}')\"><li class='fa fa-undo' title='".trsLang('회수처리','recoveryprocessing')."'></li></span>";
					}else{
						$pass_return_proc = "<span class='text_link required-update-auth' onclick=\"visitorPassReturnCancel('{$v_user_list_seq}')\"  title='".trsLang('회수취소','unrecover_text')."'>".$str_pass_card_return_date."</span>";
					}			

					$elec_doc_number = $row['elec_doc_number'];
					$label_name = $row['label_name'];
					$label_value = $row['label_value'];		



					$v_user_type = $row['v_user_type'];
					$str_v_user_type = $_CODE_V_USER_TYPE[$v_user_type];

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
					<td class="center"  ><?= $str_v_user_type ?></td>
					<td class="center" >
						<a href="javascript:void(0)" class='text_link' onclick="sendPostForm('<? echo $_www_server?>/user/access_info.php?enc=<?= $param_enc ?>')">
							<?= $v_user_name ?><? if($v_user_name_en != "") echo " ($v_user_name_en)"; ?>
						</a>
					</td>
					<td class="center" ><?= $v_user_belong ?></td>
					<!--<td class="center" ><?= $phone_no ?></td>-->
					<td class="center" ><?= $v_purpose ?></td>
					<td class="center" ><?= $in_center_name ?></td>
					<td class="center" ><?= $in_time_vl ?></td>
					<td class="center" ><a href="javascript:void(0)" class='text_link' onclick="sendPostForm('<? echo $_www_server?>/user/access_status_manager.php?enc=<? echo ParamEnCoding('manager_name='.$manager_name.'&manager_name_en='.$manager_name_en) ?>')">
					<?= $manager_name ?> (<?= $manager_name_en ?>)</a></td>
					<td class="center" ><?= $manager_dept ?></td>
					<td class="center" ><?= $pass_card_no ?></td>
					<td class="center"><? echo $pass_return_proc;?></td>
	
						<td class="center viewlayer_parent" >
							<span class='text_link required-update-auth' onmouseover="viewlayer(true, 'moverlayerLock_<? echo $no ?>');" onmouseout="viewlayer(false, 'moverlayerLock_<? echo $no ?>');" onclick="appendRow_Memo('<? echo $v_user_list_seq; ?>','<? echo $seq_val; ?>')"><? echo $str_memo == "" ? trsLang('쓰기', 'btnwrite') : "<i class='fa fa-comments'></i>" ?></span>
							<? if ($str_memo > "") { ?>
								<div id="moverlayerLock_<? echo $no ?>" class="viewlayer left_view" style="display: none;"><? echo $str_memo; ?></div>
							<? } ?>
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
				<td colspan="15" align='center'><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
			</tr>
			<?php
			}
			?>

		</table>



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