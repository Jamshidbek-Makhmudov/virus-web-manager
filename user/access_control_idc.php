<?php
$page_name = "access_control_idc";
$page_tab_name = "access_control_idc";

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
$idc_center =  $_REQUEST[idc_center];
$visit_status =  $_REQUEST[visit_status];


$searchopt1 = $_REQUEST[searchopt1];	// 검색옵션
$searchandor1 = $_REQUEST[searchandor1];
$searchkey1 = $_REQUEST[searchkey1];	// 검색어
$searchopt2 = $_REQUEST[searchopt2];	// 검색옵션
$searchandor2 = $_REQUEST[searchandor2];
$searchkey2 = $_REQUEST[searchkey2];	// 검색어
$searchopt3 = $_REQUEST[searchopt3];	// 검색옵션
$searchandor3 = $_REQUEST[searchandor3];
$searchkey3 = $_REQUEST[searchkey3];	// 검색어
$searchandor0 = $_REQUEST[searchandor0];
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
if ($idc_center != "") $param .= ($param == "" ? "" : "&") . "idc_center=" . $idc_center;
if ($visit_status != "") $param .= ($param == "" ? "" : "&") . "visit_status=" . $visit_status;
if ($paging!="") $param .= ($param==""? "":"&")."paging=".$paging;

if($searchandor0!="") $param .= ($param==""? "":"&")."searchandor0=".$searchandor0;

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
$Model_manage = new Model_manage;
?>
<script language="javascript">
	$("document").ready(function(){
		var w = $("#tblList").width();
		$("#div1").width(w);
	});

	$(function(){
		$("#wrapper1").scroll(function(){
			$("#wrapper2").scrollLeft($("#wrapper1").scrollLeft());
		});
		$("#wrapper2").scroll(function(){
			$("#wrapper1").scrollLeft($("#wrapper2").scrollLeft());
		});

		window.onresize = function(event) {
			var w = $("#tblList").width();
			$("#div1").width(w);
		};
	});
</script>
<div id="user_list">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				<h1><span id='page_title'>IDC <?= $_LANG_TEXT['access_control_theme'][$lang_code] ?> <small><? echo trsLang('출입내역','entryExitHistory');?></small></span></h1>
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
					<th><?= trsLang('방문일자','visitdatetext'); ?></th>
					<td>
						<input type="text" name="start_date" id="start_date" class="frm_input datepicker" placeholder="" style="width:100px"
							value="<?= $start_date ?>" maxlength="10"> ~
						<input type="text" name="end_date" id="end_date" class="frm_input datepicker" placeholder="" style="width:100px"
							value="<?= $end_date ?>" maxlength="10">
						
						<div class='col head'><? echo trsLang('소속구분','belongdivtext');?></div>
						<div class='col'>
							<select name='v_user_type' id='v_user_type' style='max-width:150px;'>
								<option value=''><?=$_LANG_TEXT["alltext"][$lang_code];?></option>
								<?
								foreach($_CODE_V_USER_TYPE as $key=>$name){
									echo "<option value='$key' ".($key==$v_user_type ? "selected" : "").">".$name."</option>";
								}
								?>
							<select>
						</div>

						<div class='col head'><? echo trsLang('검사장','scancentertext');?></div>
						<div class='col'>
							<select name='scan_center_code' id='scan_center_code'>
								<option value=''><?=$_LANG_TEXT["alltext"][$lang_code];?></option>
								<?php
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
						</div>

						<div class='col head'><? echo trsLang('센터위치','centerpositiontext');?></div>
						<div class='col'>
							<select name='idc_center' id='idc_center'>
								<option value=''><?=$_LANG_TEXT["alltext"][$lang_code];?></option>
								<?php
								$args = array("code_key"=>"IDC_CENTER", "use_yn"=>"Y", "search_sql"=>" and depth > 1 ");
								$result = $Model_manage->getCodeList($args);
								
								if($result){
									while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

										$_code_name = $row['code_name'];
							?>
								<option value='<?=$_code_name?>' <?if($_code_name==$idc_center) echo "selected" ;?>
									><?=$_code_name?></option>
								<?php
									}
								}
							?>
							</select>
						</div>

						<div class='col head'><? echo trsLang('상태','statustext');?></div>
						<div class='col'>
							<select name='visit_status' id='visit_status'>
								<option value=''><?=$_LANG_TEXT["alltext"][$lang_code];?></option>
								<?php
									foreach($_CODE_VISIT_STATUS as $key=>$name){
										$key = strval($key);
								?>
								<option value='<?=$key?>' <?if($key==$visit_status) echo "selected" ;?>><?=$name?></option>
							<?php
									}
							?>
							</select>
						</div>
					</td>
				</tr>
				<?
				//검색키워드목록
				$searchopt_list = array(
					"v_user_name"=>trsLang("이름","nametext")
					,"v_user_belong"=>trsLang("소속","belongtext")
					,"elec_doc_number"=>trsLang("작업번호","worknumbertext")
					,"work_number"=>trsLang("작업번호","worknumbertext")."(".trsLang('인증','certify_text').")"
					,"confirmer"=>trsLang("확인자","confirmertext")."(".trsLang('이름','nametext').")"
				);
				?>
				<tr>
					<th>
						<? echo trsLang('키워드검색','keywordsearchtext');?>
					</th>
					<td>
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
						<input type="submit"  value="<?= $_LANG_TEXT['usersearchtext'][$lang_code] ?>" class="btn_submit"
							onclick="return SearchSubmit(document.searchForm);">
						<input type="button" value="<?= $_LANG_TEXT['userdetailsearchtext'][$lang_code] ?>"
							class="btn_submit_no_icon" onclick="$('#search_detail').toggle()">
													<input type="button" value="<? echo trsLang('초기화','btnclear');?>" class="btn_submit_no_icon" onclick="location.href='<? echo $_www_server?>/user/access_control_idc.php'">

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
			 $search_sql = " and v2.v_type ='VISIT_IDC' ";

			if ($start_date != "" && $end_date != "") {
				$search_sql .= " and v2.visit_date between '" . str_replace('-', '', $start_date) . "' AND '" . str_replace('-', '', $end_date)."'";
			}
			if($scan_center_code !=""){ 

				$search_sql .= " and v2.in_center_code = '{$scan_center_code}'  ";
			}

			if($v_user_seq !=""){
				$search_sql .= " and v1.v_user_seq = '{$v_user_seq}'  ";
			}

			
			if($v_user_type != ""){
				$search_sql .= " and v2.v_user_type like '{$v_user_type}%'  ";
			}

			if($idc_center !=""){
				$search_sql .= " and CHARINDEX('{$idc_center}',v3.visit_center_desc) > 0  ";
			}

			if($visit_status != ""){
				$search_sql .= " and v2.visit_status = '{$visit_status}'  ";
			}

			//키워드검색
			$searchkey_sql= array(
				"v_user_belong" => " v2.v_user_belong like '%{?}%' "
				,"mgr_dept" => " v2.manager_dept like '%{?}%' "
				,"pass_number" => " v3.pass_card_no = '{?}' "
				,"elec_doc_number" => " v3.elec_doc_number = '{?}' "
				,"work_number" => " exists (select 1 from tb_v_user_list_work where v_user_list_seq = v2.v_user_list_seq and work_number = '{?}' ) "
				,"confirmer" => " ( vi.access_emp_name ='{?}' or vo.access_emp_name ='{?}' ) "
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

			//echo $search_sql ;

			if($keyword_search_sql != ""){
				$search_sql .= $keyword_search_sql.")";
			}
			$Model_User->SHOW_DEBUG_SQL = false;
			$args = array("search_sql" => $search_sql);
			$total = $Model_User->getUserVistListCount_IDC($args);
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

			$result = $Model_User->getUserVistList_IDC($args);

			$cnt = 20;
			$iK = 0;
			$classStr = "";

			//excel file name while downloading
			$excel_name = "IDC ".$_LANG_TEXT['access_control_theme'][$lang_code]."(".trsLang('출입내역','entryExitHistory').")";
			?>
			<!-- for test -->

			<div class="btn_wrap right" style='margin-bottom:10px;'>
				<? $excel_down_url = $_www_server . "/user/access_control_idc_excel.php?enc=" . ParamEnCoding($param); ?>
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
				<th class="num center"><?= $_LANG_TEXT['numtext'][$lang_code] ?></th>
				<th class="center" style='width:90px'><? echo trsLang('방문일자','visitdatetext')?></th>
				<th class="center" ><? echo trsLang('소속구분','belongdivtext');?></th>
				<th class="center" style='min-width:100px'><?= $_LANG_TEXT['belongtext'][$lang_code] ?></th>
				<th class="center" style='min-width:80px'><?= $_LANG_TEXT['nametext'][$lang_code] ?></th>
				<th class="center" style='min-width:100px;max-width:200px;'><?= $_LANG_TEXT['purpose_visit'][$lang_code] ?></th>
				<th class="center" style='min-width:100px'><? echo trsLang('검사장','inspection_center');?></th>
				<th class="center" style='min-width:100px'><? echo trsLang('센터위치','centerpositiontext');?></th>
				<th class="center"  style='min-width:100px'><? echo trsLang('작업번호','worknumbertext')?></th>
				<th class="center"  style='min-width:100px'><? echo trsLang('작업번호','worknumbertext')?>(<? echo trsLang('인증','certify_text')?>)</th>
				<th class="center" style='width:120px'><? echo trsLang('입실시간','inofficetimetext')?></th>
				<th class="center" style='min-width:120px'><? echo trsLang('입실확인자','inofficeconfirmertext')?></th>
				<th class="center" style='width:120px'><? echo trsLang('퇴실시간','outofficetimetext')?></th>
				<th class="center" style='min-width:120px'><? echo trsLang('퇴실확인자','outofficeconfirmertext')?></th>
				<th class="center" ><? echo trsLang('상태','statustext');?></th>
				<th class="center" ><? echo trsLang('정보보호서약서','informationprotectionpledge')?></th>
				<th class="center" ><? echo trsLang('유지보수결과서', 'idcvisitorreporttext')?></th>
				<th class="center" ><? echo trsLang('지원체크리스트', 'idcmanagerchecklist')?></th>
				<th class="center" style='width::60px'><?= $_LANG_TEXT['memotext'][$lang_code] ?></th>
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

					$in_center_code = $row['in_center_code'];

					$in_center_name = $row['in_center_name'];

					$pass_card_no = $row['pass_card_no'];
						
					$in_goods_cnt = $row['in_goods_cnt'];
					$elec_doc_number = $row['elec_doc_number'];
					$label_name = $row['label_name'];
					$label_value = $row['label_value'];		


					$carryin_info = array();
					if($in_goods_cnt > 0){
						$carryin_info[] =trsLang("자산","asset"). "($in_goods_cnt)";
					}
					if($elec_doc_number != ""){
						$carryin_info[] = trsLang("파일","file");
					}

					$str_carryin_info = sizeof($carryin_info) > 0 ? implode(",<BR>",$carryin_info) : "-";

					

					$v_user_type = $row['v_user_type'];
					$str_v_user_type = $_CODE_V_USER_TYPE_DETAILS[$v_user_type];
					if($str_v_user_type=="") $str_v_user_type = $v_user_type;

					$v_type = $row['v_type'];


					$v_user_belong = $row['v_user_belong'];

					
					$rnum = $row['rnum'];

					$visit_status = strVal($row['visit_status']);
					if($visit_status=="") $visit_status = "9";	//입실대기

					$str_visit_status = $_CODE_VISIT_STATUS[$visit_status];
					
					$str_in_time = $str_out_time = "";
					if($visit_status=="1") {
						$str_in_time = "<a href='javascript:void(0)' class='required-update-auth text_link' onclick='cancelVisitIn()' data-seq='".$v_user_list_seq."' title='".trsLang('입실처리취소','inofficeaccesscanceltext')."'>".setDateFormat($row['in_time'],'Y-m-d H:i')."</a>";
						$str_out_time =  "<a href='javascript:void(0)' class='required-update-auth text_link' onclick='procVisitOut()' data-seq='".$v_user_list_seq."'  title='".trsLang('퇴실처리','outofficeaccesstext')."'>[".trsLang('퇴실처리','outofficeaccesstext')."]</a>";
					}else if($visit_status=="0") {
						$str_in_time = setDateFormat($row['in_time'],'Y-m-d H:i');
						$str_out_time = "<a href='javascript:void(0)' class='required-update-auth text_link' onclick='cancelVisitOut()' data-seq='".$v_user_list_seq."' title='".trsLang('퇴실처리취소','outofficeaccesscanceltext')."'>".setDateFormat($row['out_time'],'Y-m-d H:i')."</a>";
					}else{
						$str_in_time = "<a href='javascript:void(0)' class='required-update-auth text_link' onclick='procVisitIn()' data-seq='".$v_user_list_seq."' title='".trsLang('입실처리','inofficeaccesstext')."'>[".trsLang('입실처리','inofficeaccesstext')."]</a>";
						$str_out_time =  "-";
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


					$visit_center_desc = $row['visit_center_desc'];
					$visit_date = setDateFormat($row['visit_date']);
					if($visit_date=="") $visit_date = setDateFormat($row['in_time']);
					
					$security_agree_yn = $row['security_agree_yn'];
					if($security_agree_yn=="Y"){
						$str_security_agree_yn = "<a href='javascript:void(0)' onclick='popSecurityAgree()' data-seq='".$v_user_list_seq."' class='text_link'>".trsLang('작성완료','writeoktext')."</a>";
					}else{
						$str_security_agree_yn ="-";
					}
					
					{
						
						$user_vsr_doc_seq = $row['user_vsr_doc_seq'];
						$user_mgr_doc_seq = $row['user_mgr_doc_seq'];

						$str_write_ok = trsLang('작성완료', 'writeoktext');
						
						$str_vsr_doc = empty($user_vsr_doc_seq) ? "-" : "<a href='javascript:void(0)' onclick='popUserIdcReport()' onclick='' data-seq='{$v_user_list_seq}' data-doc-seq='{$user_vsr_doc_seq}' class='text_link'>{$str_write_ok}</a>";
						$str_mgr_doc = empty($user_mgr_doc_seq) ? "-" : "<a href='javascript:void(0)' onclick='popUserIdcReport()' onclick='' data-seq='{$v_user_list_seq}' data-doc-seq='{$user_mgr_doc_seq}' class='text_link'>{$str_write_ok}</a>";
					}

					$work_number = $row['work_number'];
					
					$in_time_confirmer = $out_time_confirmer= "";
					if($visit_status=="1" || $visit_status=="0"){
						$in_time_confirmer = $row[in_access_emp_name]."(".$row[in_access_emp_id].")";
					}if($visit_status=="0"){
						$out_time_confirmer = $row[out_access_emp_name]."(".$row[out_access_emp_id].")";
					}

			?>

			<tr>

				<td class="center" ><?= $no ?></td>
				<td class="center" ><?= $visit_date ?></td>
				<td class="center"  ><?= $str_v_user_type ?></td>
				<td class="center" ><?= $v_user_belong ?></td>
				<td class="center" >
					<a href="javascript:void(0)" class='text_link' onclick="sendPostForm('<? echo $_www_server?>/user/access_info_idc.php?enc=<?= $param_enc ?>')">
						<?= $v_user_name ?><? if($v_user_name_en != "") echo " ($v_user_name_en)"; ?>
						<?if($additional_cnt > 0) echo " (+{$additional_cnt})";?>
					</a>
				</td>
				<td class="center" ><?= $v_purpose ?></td>
				<td class="center" ><?= $in_center_name ?></td>
				<td class="center" ><?= $visit_center_desc ?></td>
				<td class="center" ><? echo $elec_doc_number;?></td>
				<td class="center" ><? echo $work_number;?></td>
				<td class="center" ><?= $str_in_time ?></td>
				<td class="center" ><?= $in_time_confirmer ?></td>
				<td class="center" ><?= $str_out_time ?></td>
				<td class="center" ><?= $out_time_confirmer ?></td>
				<td class="center" ><? echo $str_visit_status;?></td>
				<td class="center" ><? echo $str_security_agree_yn;?></td>
				<td class="center" ><? echo $str_vsr_doc;?></td>
				<td class="center" ><? echo $str_mgr_doc;?></td>
				<td class="center viewlayer_parent">
					<?
						//메모 쓰기 권한
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
<!--정보보호서약서 팝업-->
<div id='popContent' style='display:none'></div>
<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>