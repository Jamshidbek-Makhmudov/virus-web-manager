<?php
$page_name = "file_in_apply_list";

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
$v_type =  $_REQUEST[v_type];

$searchopt1 = $_REQUEST[searchopt1];	// 검색옵션
$searchandor1 = $_REQUEST[searchandor1];
$searchkey1 = $_REQUEST[searchkey1];	// 검색어
$searchopt2 = $_REQUEST[searchopt2];	// 검색옵션
$searchandor2 = $_REQUEST[searchandor2];
$searchkey2 = $_REQUEST[searchkey2];	// 검색어
$searchopt3 = $_REQUEST[searchopt3];	// 검색옵션
$searchandor3 = $_REQUEST[searchandor3];
$searchkey3 = $_REQUEST[searchkey3];	// 검색어
$uncovered = $_REQUEST['uncovered'];

if ($useyn == "") $useyn = "Y";
if ($paging == "") $paging = $_paging;

if ($start_date == "") $start_date = date("Y-m-d", strtotime(date("Y-m-d") . " -1 month"));
if ($end_date == "") $end_date = date("Y-m-d");

$param = "";
if ($scan_center_code != "") $param .= ($param == "" ? "" : "&") . "scan_center_code=" . $scan_center_code;

if ($searchopt != "") $param .= ($param == "" ? "" : "&") . "searchopt=" . $searchopt;
if ($searchkey != "") $param .= ($param == "" ? "" : "&") . "searchkey=" . $searchkey;
if ($orderby != "") $param .= ($param == "" ? "" : "&") . "orderby=" . $orderby;
if ($start_date != "") $param .= ($param == "" ? "" : "&") . "start_date=" . $start_date;
if ($end_date != "") $param .= ($param == "" ? "" : "&") . "end_date=" . $end_date;
if ($v_type != "") $param .= ($param == "" ? "" : "&") . "v_type=" . $v_type;
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
$Model_result=new Model_result();

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
				 <h1><span id='page_title'><?=$_LANG_TEXT["file_in_apply_list"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>

		<!--검색폼-->
		<form name="searchForm" action="<?php echo $_SERVER[PHP_SELF] ?>" method="POST">
		<input type='hidden' name='proc_name' id='proc_name'>
			<input type="hidden" name="page" value="">
			<table class="search">
				<tr>
					<th style='widht:100px;'><?= $_LANG_TEXT['checkperiodtext'][$lang_code] ?> </th>
					<td>
						<input type="text" name="start_date" id="start_date" class="frm_input datepicker" placeholder="" style="width:100px" value="<?= $start_date ?>" maxlength="10"> ~ 
						<input type="text" name="end_date" id="end_date" class="frm_input datepicker" placeholder="" style="width:100px" value="<?= $end_date ?>" maxlength="10">
					</td>
				</tr>
								<?
				//검색키워드목록
					$searchopt_list = array(
						"APPLY_SEQ"=>trsLang("신청번호","applynumbertext")
						,"USER_NAME"=>trsLang("이름","nametext")
						,"USER_BELONG"=>trsLang("소속","belongtext")
						,"MANAGER"=>trsLang("임직원","executives")
						,"MANAGER_DEPT"=>trsLang("임직원 소속","employee_affiliation")
						,"DOC_NO"=>trsLang("전자문서번호","electronic_payment_document_number")
						,"FILE_NAME"=>trsLang('파일명','filenametext')
						,"FILE_HASH"=>trsLang('파일해시','filehash')."(MD5)"
					);
				?>
				<tr>
					<th><? echo trsLang('키워드검색','keywordsearchtext'); ?> </th>
					<td style='padding:5px 13px'>

							<select name="searchopt" id="searchopt" style='max-width:150px;'>
								<option value="" <?php if($searchopt == "") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['select_search_item'][$lang_code]?></option>
								<?
								foreach($searchopt_list as $key=>$name){
									$selected = $searchopt==$key ? "selected" : "";
									echo "<option value='{$key}' {$selected} >{$name}</option>";
								}
								?>
							</select>

						<input type="text" class="frm_input" style="width:50%" name="searchkey" id="searchkey" value="<?= $searchkey ?>" maxlength="50">
						<input type="submit" value="<?= $_LANG_TEXT['usersearchtext'][$lang_code] ?>" class="btn_submit" onclick="return SearchSubmit(document.searchForm);">
						<input type="button" value="<?= $_LANG_TEXT['userdetailsearchtext'][$lang_code] ?>" class="btn_submit_no_icon" onclick="$('#search_detail').toggle()">
												<input type="button" value="<? echo trsLang('초기화','btnclear');?>" class="btn_submit_no_icon" onclick="location.href='<? echo $_www_server?>/result/file_in_apply_list.php'">
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
			$search_sql = "";

			if ($start_date != "" && $end_date != "") {
				$search_sql .= " and v1.in_time between '".str_replace('-', '', $start_date)."000000' AND '".str_replace('-', '', $end_date)."235959' ";
			}

			//키워드검색
			$searchkey_sql= array(
				"APPLY_SEQ" => " t1.refer_apply_seq = '{?}' "
				,"USER_BELONG" => " v1.v_user_belong like N'%{?}%' "
				,"MANAGER_DEPT" => " v1.manager_dept like '%{?}%' "
				,"DOC_NO" => " v2.elec_doc_number like '%{?}%' "
				,"FILE_NAME" => " t2.file_name like N'%{?}%' "
				,"FILE_HASH" => " t2.file_hash like '%{?}%' "
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

					if($_searchopt=="USER_NAME"){
						$keyword_search_sql .= " {$_searchandor} v1.v_user_name  = '".aes_256_enc($_searchkey)."'";
					}else if($_searchopt=="MANAGER"){
						$keyword_search_sql .= " {$_searchandor} ( v1.manager_name  = '".aes_256_enc($_searchkey)."' or  v1.manager_name_en like '%{$_searchkey}%' ) ";
					}else {
						$keyword_search_sql .= " {$_searchandor} ".str_replace('{?}', $_searchkey, $searchkey_sql[$_searchopt]);
					}

				}

			}

			if($keyword_search_sql != ""){
				$search_sql .= $keyword_search_sql.")";
			}


			$Model_result = new Model_result();
			$Model_result->SHOW_DEBUG_SQL = false;
			$args = array("search_sql"=>$search_sql);
	
			$total=$Model_result->getFileInApplyFileListCount($args); 
	
			$rows = $paging;			// 페이지당 출력갯수
			$lists = $_list;				// 목록수
			$page_count = ceil($total/$rows);
			if(!$page || $page > $page_count) $page = 1;
			$start = ($page-1)*$rows;
			$no = $total-$start;
			$end = $start + $rows;

			if($orderby != "") {
				$order_sql = " ORDER BY $orderby";
			} else {
				$order_sql = " ORDER BY t1.file_in_apply_seq DESC "; 
			}	

			$args = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start);	
			
			$Model_result->SHOW_DEBUG_SQL = false;
			$result = $Model_result->getFileInApplyFileList($args);

			if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;
				
				$cnt = 20;
				$iK = 0;
				$classStr = "";
				//excel file name while downloading
		   $excel_name=$_LANG_TEXT['file_in_apply_list'][$lang_code];		
			?>

		<div class="btn_wrap right" style='margin-bottom:10px;'>
			<? $excel_down_url = $_www_server."/result/file_in_apply_list_excel.php?enc=".ParamEnCoding($param);?>
			<div class="right">
				<a href="#" id="parking_ticket_payment_excel" class="btnexcel required-print-auth hide"
						onclick="getHTMLSplit('<?=$total?>','<?=$excel_down_url?>','<?=$excel_name?>',this);"><?=$_LANG_TEXT["btnexceldownload"][$lang_code];?></a>
			</div>
			
			<div style='margin-right:10px; line-height:30px; ' class="right">
				Results : <span style='color:blue'><?=number_format($total)?></span> /
				Records : <select name='paging' onchange="searchForm.submit();">
					<option value='20' <?if($paging=='20' ) echo "selected" ;?>>20</option>
					<option value='40' <?if($paging=='40' ) echo "selected" ;?>>40</option>
					<option value='60' <?if($paging=='60' ) echo "selected" ;?>>60</option>
					<option value='80' <?if($paging=='80' ) echo "selected" ;?>>80</option>
					<option value='100' <?if($paging=='100' ) echo "selected" ;?>>100</option>
				</select>
			</div>

		</div>


		</form>
		
		

		<!--검색결과리스트-->
		<div id='wrapper1' class="wrapper">
		  <div id='div1' style='height:1px;'></div>
		</div>
		<div id='wrapper2' class="wrapper">
		<table id='tblList' class="list" style="margin:0px;auto; white-space: nowrap; ">
			<tr>
				<th class="num" style="min-width:80px"><?= $_LANG_TEXT['numtext'][$lang_code] ?></th>
				<th class="center" style="min-width:100px"><? echo trsLang('신청번호','applynumbertext'); ?></th>
				<th class="center" style="min-width:100px"><? echo trsLang('방문자명','visitor_name'); ?></th>
				<th class="center" style="min-width:150px"><? echo trsLang('소속','belongtext'); ?></th>
				<th class="center" style="min-width:100px"><? echo trsLang('검사일','checkdatetext'); ?></th>
				<th class="center" style="min-width:100px"><? echo trsLang('검사장','scancentertext'); ?></th>
				<th class="center" style="min-width:150px"><? echo trsLang('파일명','filenametext'); ?></th>
				<th class="center" style="min-width:250px"><? echo trsLang('파일해시','filehash'); ?></th>
				<th class="center" style="min-width:150px" ><? echo trsLang('임직원','executives');?></th>
				<th class="center" style="min-width:150px" ><? echo trsLang('임직원 소속','employee_affiliation');?></th>
				<th class="center" style="min-width:200px" ><? echo trsLang('전자문서번호','electronic_payment_document_number');?></th>
				<th class="center" style="min-width:150px"><? echo trsLang('신청사유','applyreason'); ?></th>
				<th class="center" style="min-width:200px"><? echo trsLang('적용기간','application_period'); ?></th>
				<th class="center" style="min-width:100px"><? echo trsLang('승인여부','approvedyesnotext'); ?></th>
				<!--<th class="center" style="min-width:100px"><? echo trsLang('승인자','approver'); ?></th>-->
				<th class="center" style="min-width:100px"><? echo trsLang('승인일자','approvedate'); ?></th>


			</tr>
			<?php

			if($result){
				while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

					$v_user_name = aes_256_dec($row['v_user_name']);
					$v_user_belong = $row['v_user_belong'];
					$file_name = $row['file_name'];
					$file_hash = $row['file_hash'];
					$manager_dept = $row['manager_dept'];
					$manager_name = aes_256_dec($row['manager_name']);
					$manager_name_en = $row['manager_name_en'];
					$elec_doc_number = $row['elec_doc_number'];
					$file_comment = $row['file_comment'];
					$apprv_emp_name = aes_256_dec($row['apprv_emp_name']);
					$scan_center_name = $row['scan_center_name'];
					if($scan_center_name=="") $scan_center_name = $row['scan_center_code'];
					$approve_date = setDateFormat($row['approve_date']);
					$in_time = setDateFormat($row['in_time']);
					$str_approve_status = $_CODE_FILE_EXCEPTION_APPRV_STATUS[$row['approve_status']];
					$refer_apply_seq = $row[refer_apply_seq];
					
					if($str_approve_status=="") $str_approve_status = $row['approve_status'];
					
					$start_date = setDateFormat($row['start_date']);
					$end_date = setDateFormat($row['end_date']);
					
					if($manager_name_en==""){
						$str_manager_name = $manager_name;
					}else{
						$str_manager_name = $manager_name." (".$manager_name_en.")";
					}

			?>
			<tr>
				<td class='center'><? echo $no?></td>
				<td class='center'><? echo $refer_apply_seq?></td>
				<td class='center'><? echo $v_user_name?></td>
				<td class='center'><? echo $v_user_belong?></td>
				<td class='center'><? echo $in_time?></td>
				<td class='center'><? echo $scan_center_name?></td>
				<td style='text-align:left'><? echo $file_name?></td>
				<td class='center'><? echo $file_hash?></td>
				<td class='center'><?=$str_manager_name?></td>
				<td class='center'><?=$manager_dept?></td>
				<td class='center'><?=$elec_doc_number?></td>
				<td class='center'><? echo $file_comment?></td>
				<td class='center'><? echo $start_date?> ~ <? echo $end_date?></td>
				<td class='center'><? echo $str_approve_status?></td>
				<!--<td class='center'><? echo $apprv_emp_name?></td>-->
				<td class='center'><? echo $approve_date?></td>
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
	
	</div>
</div>

<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>
<!-- file_in_apply_list.php -->