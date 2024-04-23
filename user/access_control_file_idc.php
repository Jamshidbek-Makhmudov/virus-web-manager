<?php
$page_name = "access_control_idc";
$page_tab_name = "access_control_file_idc";

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI']) - 1);
$_apos = stripos($_REQUEST_URI,  "/");
if ($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

//파일반입 탭은 점검결과 메뉴 권한이 있어야 볼수 있다.
if(in_array("R1000",$_ck_user_mauth)==false){
	header("Location:access_control_idc.php");
	exit;
}

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
$in_user_div =  $_REQUEST[in_user_div];

$searchopt1 = $_REQUEST[searchopt1];	// 검색옵션
$searchandor1 = $_REQUEST[searchandor1];
$searchkey1 = $_REQUEST[searchkey1];	// 검색어
$searchopt2 = $_REQUEST[searchopt2];	// 검색옵션
$searchandor2 = $_REQUEST[searchandor2];
$searchkey2 = $_REQUEST[searchkey2];	// 검색어
$searchopt3 = $_REQUEST[searchopt3];	// 검색옵션
$searchandor3 = $_REQUEST[searchandor3];
$searchkey3 = $_REQUEST[searchkey3];	// 검색어
$uncovered = $_REQUEST[uncovered];

if ($useyn == "") $useyn = "Y";
if ($paging == "") $paging = $_paging;

if ($start_date == "") $start_date = date("Y-m-d", strtotime(date("Y-m-d") . " -1 month"));
if ($end_date == "") $end_date = date("Y-m-d");

$param = "tab=".$page_tab_name;
if ($scan_center_code != "") $param .= ($param == "" ? "" : "&") . "scan_center_code=" . $scan_center_code;

if ($searchopt != "") $param .= ($param == "" ? "" : "&") . "searchopt=" . $searchopt;
if ($searchkey != "") $param .= ($param == "" ? "" : "&") . "searchkey=" . $searchkey;
if($searchandor!="") $param .= ($param==""? "":"&")."searchandor=".$searchandor;
if ($orderby != "") $param .= ($param == "" ? "" : "&") . "orderby=" . $orderby;
if ($start_date != "") $param .= ($param == "" ? "" : "&") . "start_date=" . $start_date;
if ($end_date != "") $param .= ($param == "" ? "" : "&") . "end_date=" . $end_date;
if ($in_user_div != "") $param .= ($param == "" ? "" : "&") . "in_user_div=" . $in_user_div;
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
				<h1><span id='page_title'>IDC <?= $_LANG_TEXT['access_control_theme'][$lang_code] ?>  <small><? echo trsLang('파일반입','fileimport');?></small></span></h1>
			</div>
			<span class="line"></span>
		</div>

		<!--tab 메뉴-->
		<? require_once(__DIR__."/access_control_tab_idc.php");?>

		<!--검색폼-->
		<form name="searchForm" action="<?php echo $_SERVER[PHP_SELF] ?>" method="POST">
			<input type="hidden" name="page" value="">
			<input type='hidden' name='proc_name' id='proc_name'>
			<table class="search">
				<tr>
					<th style='width:100px;'><?= trsLang('반입일자','importdate'); ?> </th>
					<td style='width:350px;'>
						<input type="text" name="start_date" id="start_date" class="frm_input" placeholder="" style="width:100px"
							value="<?= $start_date ?>" maxlength="10"> ~
						<input type="text" name="end_date" id="end_date" class="frm_input" placeholder="" style="width:100px"
							value="<?= $end_date ?>" maxlength="10">
					</td>
					<th style='min-width:100px;'>
						<? echo trsLang('반입구분','importdiv'); ?>
					</th>
					<td style='width:200px'>
						<select name='in_user_div' id='in_user_div' style='max-width:150px;'>
							<option value=''><?=$_LANG_TEXT["alltext"][$lang_code];?></option>
							<option value='OUT' <? if($in_user_div=="OUT") echo "selected";?>><? echo trsLang('방문객','m_visitor');?></option>
							<option value='EMP' <? if($in_user_div=="EMP") echo "selected";?>><? echo trsLang('임직원','staff');?></option>
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
					,"mgr_name"=>trsLang("임직원","executives")
					,"mgr_dept"=>trsLang("임직원 소속","employee_affiliation")
					,"elec_doc_number"=>trsLang("출입번호","visitnumbertext")
					,"work_number"=>trsLang("작업번호","worknumbertext")
					,"mgt_number"=>"USB ".trsLang("관리번호","managenumber")
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
													<input type="button" value="<? echo trsLang('초기화','btnclear');?>" class="btn_submit_no_icon" onclick="location.href='<? echo $_www_server?>/user/access_control_file_idc.php'">

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
		
			<?php
			//검색항목
			 $search_sql = " and v2.v_type in ('VISIT_IDC','VCS_IDC') and  v3.label_value > '' ";	//반입이 있는 정보만 가져온다.(usb 물품관리번호)

			
			if ($start_date != "" && $end_date != "") {
				$search_sql .= " and v2.visit_date between '" . str_replace('-', '', $start_date) . "' AND '" . str_replace('-', '', $end_date) . "' ";
			}
			if($scan_center_code !=""){ 

				$search_sql .= " and v2.in_center_code = '{$scan_center_code}'  ";
			}

			if($in_user_div == "OUT"){	//방문객이 파일 반입 한경우
				$search_sql .= " and v2.v_user_type='OUT'  and v2.v_type = 'VISIT_IDC'  ";
			}else if($in_user_div=="EMP"){	//임직원이 파일 반입한 경우
				$search_sql .= " and ( v2.v_user_type like 'EMP%' or v2.v_type = 'VCS_IDC' ) ";
			}

			if($uncovered != ""){
				$search_sql .= " and isnull(v3.usb_return_date,'') = ''  ";
			}
			
			//키워드검색
			$searchkey_sql= array(
				"v_user_belong" => " v2.v_user_belong like '%{?}%' "
				,"mgr_dept" => " v2.manager_dept like '%{?}%' "
				,"pass_number" => " v3.pass_card_no = '{?}' "
				,"elec_doc_number" => " v3.elec_doc_number = '{?}' "
				,"work_number" => " exists (select 1 from tb_v_user_list_work where v_user_list_seq = v2.v_user_list_seq and work_number = '{?}' ) "
				,"mgt_number" => " v3.label_value = '{?}' "
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

			$Model_User->SHOW_DEBUG_SQL = false;
			$args = array("search_sql" => $search_sql);
			$total = $Model_User->getUserVistListCount_File($args);
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
			$result = $Model_User->getUserVistList_File($args);

			$cnt = 20;
			$iK = 0;
			$classStr = "";

			//excel file name while downloading
			$excel_name = "IDC ".$_LANG_TEXT['access_control_theme'][$lang_code]."(".trsLang('파일반입','fileimport').")";
			?>
			<!-- for test -->

			<div class="btn_wrap right" style='margin-bottom:10px;'>
				<? $excel_down_url = $_www_server . "/user/access_control_file_idc_excel.php?enc=" . ParamEnCoding($param); ?>
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
				<th class="center" style="width:100px"><? echo trsLang('반입구분','importdiv'); ?></th>
				<th class="center" style='min-width:80px'><?= $_LANG_TEXT['nametext'][$lang_code] ?></th>
				<th class="center" style='min-width:100px'><?= $_LANG_TEXT['belongtext'][$lang_code] ?></th>
				<th class="center" style='min-width:100px;max-width:200px;'><?= $_LANG_TEXT['purpose_visit'][$lang_code] ?></th>
				<th class="center" style='min-width:100px'><? echo trsLang('검사장','scancentertext');?></th>
				<th class="center" style='min-width:80px'><? echo trsLang('센터위치','center_location');?></th>
				<th class="center" style='min-width:100px'><?= trsLang('반입일자','importdate') ?></th>
				<th class="center" style='min-width::100px'><?= $_LANG_TEXT['executives'][$lang_code] ?></th>
				<th class="center" style='min-width::100px'><?= $_LANG_TEXT['employee_affiliation'][$lang_code] ?></th>
				<th class="center" style='min-width::100px'><?= trsLang('출입번호','visitnumbertext'); ?></th>
				
				<th class="center" style='min-width::100px'><?= trsLang('작업번호','worknumbertext'); ?></th>
				<th class="center" style='min-width::100px'>USB <?= trsLang('관리번호','managenumber'); ?></th>
				<th class="center" style='min-width::100px'>USB <?= trsLang('회수','recovery_text'); ?></th>
				<th class="center" style='min-width::100px'><?= trsLang('점검결과','m_result'); ?></th>
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
					$manager_name = aes_256_dec($row['manager_name']);
					$manager_name_en = $row['manager_name_en'];

					$manager_dept = $row['manager_dept'];
					$additional_cnt = $row['additional_cnt'];

					$memo = $row['memo'];

					$in_time = $row['in_time'];

					$in_center_code = $row['in_center_code'];

					$in_center_name = $row['in_center_name'];

					$pass_card_no = $row['pass_card_no'];
						
					$in_goods_cnt = $row['in_goods_cnt'];
					$elec_doc_number = $row['elec_doc_number'];
					$label_name = $row['label_name'];
					$label_value = $row['label_value'];		


					$vcs_cnt = $row['vcs_cnt'];
					$v_wvcs_seq = $row['v_wvcs_seq'];

					$v_type = $row['v_type'];
					$v_user_type = $row['v_user_type'];

					//파일반입을 누가했는지 구분
					if($v_user_type=="OUT"){
							$str_user_in_div = substr($v_type,0,3)=="VCS" ? trsLang('임직원','staff') : trsLang('방문객','m_visitor'); 						
					}else{
						$str_user_in_div = trsLang('임직원','staff');
					}

					$v_user_belong = $row['v_user_belong'];


					$usb_return_date = $row['usb_return_date'];

					$str_usb_return_date=setDateFormat($row['usb_return_date'],"Y-m-d");

					
					$rnum = $row['rnum'];

					if(!empty($manager_name)){
						$manager_info = $manager_name." ({$manager_name_en})";
					}else {
						$manager_info = "-";
					};

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

					if($usb_return_date==""){
						$usb_return_proc = "<span class='text_link required-update-auth hide' onclick=\"visitorUsbReturn('{$v_user_list_seq}')\"><li class='fa fa-undo'  title='USB ".trsLang('회수처리','recoveryprocessing')."' ></li></span>";
					}else{
						$usb_return_proc = "<span class='text_link required-update-auth'  title='USB ".trsLang('회수취소','unrecover_text')."' onclick=\"visitorUsbReturnCancel('{$v_user_list_seq}')\">".$str_usb_return_date."</span>";
					}		


					$visit_center_desc = $row[visit_center_desc];
					$visit_date = setDateFormat($row[visit_date]);
					$work_number = $row[work_number];
			?>

			<tr>

				<td class="center" ><?= $no ?></td>
					<td class="center"  ><?= $str_user_in_div ?></td>
					<td class="center" >

							<a href="javascript:void(0)" class='text_link' onclick="sendPostForm('<? echo $_www_server?>/user/access_info_idc.php?enc=<?= $param_enc ?>')">
							<?= $v_user_name ?><? if($v_user_name_en != "") echo " ($v_user_name_en)"; ?>
						</a>
					</td>
					<td class="center" ><?= $v_user_belong ?></td>
					<td class="center" ><?= $v_purpose ?></td>
					<td class="center" ><?= $in_center_name ?></td>
					<td class="center" ><?= $visit_center_desc ?></td>
					<td class="center" ><?= $visit_date ?></td>
					<td class="center" ><?= $manager_info ?></td>
					<td class="center" ><?= $manager_dept ?></td>
					<td class="center" ><?= $elec_doc_number ?></td>
					<td class="center" ><?= $work_number ?></td>
					<td class="center" ><?= $label_value ?></td>
					<td class="center"><? echo $usb_return_proc;?></td>
					<td class="center" >
						<?
						//점검결과건이 1개이면 점검결과보기 화면을 띄우고, 1개이상이면 점검결과로그보기 화면을 띄운다.
						if($vcs_cnt > 1){?>
							<a href="javascript:void(0)" onclick="popUserVcsLog_Visit('<? echo $v_user_list_seq;?>')" class="text_link"><? echo trsLang('보기','btnview');?></a>
						<?}else{?>
							<a href="javascript:void(0)" onclick="popUserVcsView('<? echo $v_wvcs_seq;?>')" class="text_link"><? echo trsLang('보기','btnview');?></a>
						<?}?>
					</td>
	
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


	</div>
</div>
<!--검사결과 팝업-->
<div id='popContent' style='display:none'></div>
<!--메모전송폼-->
<form id='frmMemo' method='post' action=''></form>
<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>