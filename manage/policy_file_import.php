<?php
$page_name = "policy";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
include_once $_server_path . "/" . $_site_path . "/inc/header.inc";
include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";

$target = $_REQUEST[target];
$start_date = $_REQUEST[start_date];	
$end_date = $_REQUEST[end_date];
$adapt_yn= $_REQUEST[adapt_yn];
if($adapt_yn=="") $adapt_yn = "Y";
$searchopt = $_REQUEST[searchopt];		// 검색옵션
$searchkey = $_REQUEST[searchkey];		// 검색어
$searchopt1 = $_REQUEST[searchopt1];	// 검색옵션
$searchandor1 = $_REQUEST[searchandor1];
$searchkey1 = $_REQUEST[searchkey1];	// 검색어
$searchopt2 = $_REQUEST[searchopt2];	// 검색옵션
$searchandor2 = $_REQUEST[searchandor2];
$searchkey2 = $_REQUEST[searchkey2];	// 검색어
$searchopt3 = $_REQUEST[searchopt3];	// 검색옵션
$searchandor3 = $_REQUEST[searchandor3];
$searchkey3 = $_REQUEST[searchkey3];	// 검색어
$orderby = $_REQUEST[orderby];				// 정렬순서

$paging = $_REQUEST[paging];

$page = $_REQUEST[page];			// 페이지

if($paging == "") $paging = $_paging;

$param = "";
if($target!="") $param .= ($param==""? "":"&")."target=".$target;
if($start_date!="") $param .= ($param==""? "":"&")."start_date=".$start_date;
if($end_date!="") $param .= ($param==""? "":"&")."end_date=".$end_date;
if($adapt_yn !="") $param .= ($param==""? "":"&")."adapt_yn=".$adapt_yn;
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;

if($searchopt1!="") $param .= ($param==""? "":"&")."searchopt1=".$searchopt1;
if($searchkey1!="") $param .= ($param==""? "":"&")."searchkey1=".$searchkey1;
if($searchandor1!="") $param .= ($param==""? "":"&")."searchandor1=".$searchandor1;

if($searchopt2!="") $param .= ($param==""? "":"&")."searchopt2=".$searchopt2;
if($searchkey2!="") $param .= ($param==""? "":"&")."searchkey2=".$searchkey2;
if($searchandor2!="") $param .= ($param==""? "":"&")."searchandor2=".$searchandor2;

if($searchopt3!="") $param .= ($param==""? "":"&")."searchopt3=".$searchopt3;
if($searchkey3!="") $param .= ($param==""? "":"&")."searchkey3=".$searchkey3;
if($searchandor3!="") $param .= ($param==""? "":"&")."searchandor3=".$searchandor3;

if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;
if($paging!="") $param .= ($param==""? "":"&")."paging=".$paging;

if($start_date==""){
	$start_date = date("Y-m-d");
}
if($end_date==""){
	$end_date =date("Y-m-d", strtotime(date("Y-m-d") . " +1 month"));
}

//검색 로그 기록
// $proc_name = $_POST['proc_name'];
// if($proc_name != ""){
// 	$work_log_seq = WriteAdminActLog($proc_name,'SEARCH');
// }

$Model_manage = new Model_manage();
?>
<script>
	function toggleAdpat(obj){
		var adapt_yn = $(obj).is(":checked") ? "Y" : "N";
		$("#adapt_yn").val(adapt_yn);

	}
</script>
<div id="oper_list">
	<div class="outline">
		<div class="container">

			<div id="tit_area">
				<div class="tit_line">
					 <h1><span id='page_title'><?=$_LANG_TEXT["m_manage_policy"][$lang_code];?></span></h1>
				</div>
				<span class="line"></span>
			</div>

			<!--tab 메뉴-->
			<ul class="tab">
				<li >
					<a href="<? echo $_www_server?>/manage/policy.php" ><? echo trsLang('전체설정','totalconfig');?></a>
				</li>
				<li class="on">
					<a href="<? echo $_www_server?>/manage/policy_file_import.php"><? echo trsLang('파일반입예외설정','fileimportpolicy');?></a>
				</li>
			</ul>
				
			<!--검색폼-->
			<form name="searchForm" id='searchForm'  method="POST">
				<!-- <input type='hidden' name='proc_name' id='proc_name'> -->
				<table class="search">
					<tr>
						<th style='width:100px;'><? echo trsLang('대상구분','targetdiv'); ?> </th>
						<td  style='width:350px;'>
							<select name='target' id='target'>
								<option value=''><? echo trsLang('대상구분선택','selecttargetdiv'); ?></option>
								<option value='ALL'><? echo trsLang('전체','alltext'); ?></option>
								<option value='DEPT'><? echo trsLang('부서','depttext'); ?></option>
								<option value='EMP'><? echo trsLang('사용자','usertext'); ?></option>
							</select>
						</td>
						<th style='width:100px;'><? echo trsLang('적용기간','applicationperiod');?></th>
						<td style='min-width:200px'>
							<input type="text" name="start_date" id="start_date" class="frm_input datepicker"  value="<?= $start_date ?>" maxlength="10"> ~ 
							<input type="text" name="end_date" id="end_date" class="frm_input datepicker"  value="<?= $end_date ?>" maxlength="10">
							<div class='col'>
								<input type='hidden' name='adapt_yn' id='adapt_yn' value='<? echo $adapt_yn?>'>
								<input type='checkbox' id='cbx_adapt' <? if($adapt_yn=="Y") echo "checked";?> onclick="toggleAdpat(this)"> <label for='cbx_adapt'><? echo trsLang('적용중','adapting');?></label>
							</div>
						</td>
					</tr>
					<?
					//검색키워드목록
					$searchopt_list = array(
						""=>trsLang("검색항목선택","select_search_item")
						,"policy_name"=>trsLang("정책명","policyname")
						,"target"=>trsLang("예외대상","exceptiontarget")
						,"apply_number"=>trsLang("신청번호","applynumbertext")
						,"file_hash"=>trsLang("파일해시","filehash")."(MD5)"
					);
					?>
					<tr>
						<th><? echo trsLang('키워드검색','keywordsearchtext');?> </th>
						<td colspan="3" style='padding:5px 13px'>
							<select name="searchopt" id="searchopt"  style='max-width:150px;'>
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
							<input type="button" value="<? echo trsLang('초기화','btnclear');?>" class="btn_submit_no_icon" onclick="location.href='<? echo $_www_server?>/manage/policy_file_import.php'">
							
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
			</form>
			<!--검색폼 END-->

			<!--Excel Download-->
			<div class="btn_wrap right" style='margin-bottom:10px;'>
				<? 
					$excel_down_url = $_www_server."/manage/policy_file_import_excel.php?enc=".ParamEnCoding($param);
					$excel_down_name = "file_policy_list_".date("ymdhis")."xls";
					$excel_down_title = trsLang('파일반입예외설정','fileimportpolicy')." ".$_LANG_TEXT["btnexceldownload"][$lang_code];
				?>
				<div class="right">
					<a href="javascript:void(0)"  class="btnexcel required-print-auth hide" title="<? echo $excel_down_title;?>"
						onclick="getHTMLSplit('<?=$total?>','<?=$excel_down_url?>','<? echo $excel_down_name?>',this);"><?=$_LANG_TEXT["btnexceldownload"][$lang_code];?></a>
					<a href="<? echo $_www_server?>/manage/policy_file_import_reg.php" class="btn2 required-create-auth hide"><? echo trsLang('정책추가','additionpolicy');?></a>
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
		
			<!--리스트-->
			<?
				$search_sql = "";

				if($target != ""){
					$search_sql .= " and t1.target='{$target}'  ";
				}



				if($adapt_yn =="Y"){
					$search_sql .= " and t1.end_date > '".date('YmdHis')."' ";
				}

				$start_date = preg_replace("/[^A-Za-z0-9]/", "",$start_date)."000000";
				$end_date = preg_replace("/[^A-Za-z0-9]/", "",$end_date)."235959";
				
				if($start_date != "" && $end_date != ""){
					$search_sql .= " and t1.start_date <= '{$end_date}' and t1.end_date >= '{$start_date}' ";
				}
				
				$searchkey_sql= array(
					"policy_name" => " t1.policy_name like N'{?}%' "
					,"target" => " t1.target_name like N'{?}%' "
					,"apply_number"=> "t3.refer_apply_seq = '{?}' "
					,"file_hash" => " exists (Select 1 
								From tb_policy_file_in_list 
								Where t1.policy_file_in_seq = policy_file_in_seq
									and file_hash = '{?}' ) "
				);		

				if($searchopt != "" && $searchkey != ""){
					$search_sql .= " and ".str_replace('{?}', $searchkey, $searchkey_sql[$searchopt]);
				}

				if($searchopt1 != "" && $searchkey1 != ""){
					$search_sql .= " {$searchandor1} ".str_replace('{?}', $searchkey1, $searchkey_sql[$searchopt1]);
				}

				if($searchopt2 != "" && $searchkey2 != ""){
					$search_sql .= " {$searchandor2} ".str_replace('{?}', $searchkey2, $searchkey_sql[$searchopt2]);
				}

				if($searchopt3 != "" && $searchkey3 != ""){
					$search_sql .= " {$searchandor3} ".str_replace('{?}', $searchkey3, $searchkey_sql[$searchopt3]);
				}

				//echo $search_sql;

				$args = array("search_sql"=>$search_sql);
				$Model_manage->SHOW_DEBUG_SQL = false;
				$total=$Model_manage->getFileInPolicyListCount($args);
		
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
					$order_sql = " ORDER BY t1.policy_file_in_seq DESC "; 
				}	
				
				$args = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start);	
				
				$result = $Model_manage->getFileInPolicyList($args);

				if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;
			?>
			
			<table class="list" style="margin-top:10px; ">
				<tr>
					<th style='width:50px'><? echo trsLang('번호','numtext');?></th>
					<th style='width:300px'><? echo trsLang('정책명','policyname');?></th>
					<th style='width:200px;min-width:200px'><? echo trsLang('적용기간','applicationperiod');?></th>
					<th style='width:100px;min-width:100px'><? echo trsLang('대상구분','targetdiv');?></th>
					<th style='width:150px;min-width:150px'><? echo trsLang('예외대상','exceptiontarget');?></th>
					<th style='width:100px;min-width:100px'><? echo trsLang('신청번호','applynumbertext');?></th>
					<th style='width:100px;min-width:100px'><? echo trsLang('예외적용파일','exceptionfile');?></th>
					<!--<th style='width:100px;min-width:100px'><? echo trsLang('파일서버전송','fileserversend');?></th>-->
					<th style='width:100px;min-width:100px'><? echo trsLang('등록자','registertext');?></th>
					<th style='width:60px'><? echo trsLang('삭제','deletedeletetext');?></th>
				</tr>
				<?
					if($result){
						while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
						
							$policy_name = $row['policy_name'];
							$start_date = setDateFormat($row['start_date'],'Y-m-d H:i:s');
							$end_date = setDateFormat($row['end_date'],'Y-m-d H:i:s');
							$target = $row['target'];
							$str_target = $_CODE_FILE_EXCEPTION_TARGET[$target];
							$target_name = aes_256_dec($row['target_name']);
							$refer_apply_seq = $row[refer_apply_seq];
							
							$file_div = $row['file_div'];
							$file_cnt = $row['file_cnt'];
							if($file_div=="ALL"){
								$str_file_cnt = trsLang("전체","alltext");
							}else{
								$str_file_cnt = number_format($file_cnt);
							}
							$emp_name =  aes_256_dec($row['emp_name']);
							$file_send_status = $row['file_send_status'];
							
							if($file_send_status=="1"){	
								$str_file_send_status = trsLang("전송","send_server");
							}else{
								$str_file_send_status = trsLang("미전송","notsend_server");
							}							

							$refer = $row['refer'];

							$file_in_apply_seq = $row['file_in_apply_seq'];

							//방문자 반입 예외 신청에 대한 승인은 승인자를 정책 등록자로 본다.
							if($file_in_apply_seq ==""){
								$register_name = $emp_name;
							}else{
								$register_name = $target_name;
							}
					
							$policy_file_in_seq = $row['policy_file_in_seq'];

							$param_enc = ParamEnCoding("policy_file_in_seq=".$policy_file_in_seq.($param==""? "":"&").$param);
				?>
					<tr>
						<td><? echo $no?></td>
						<td>
							<a href="javascript:void(0)" class='text_link' onclick="sendPostForm('<? echo $_www_server?>/manage/policy_file_import_reg.php?enc=<?= $param_enc ?>')">
							<? echo $policy_name?></a></td>
						<td><? echo $start_date?> ~ <? echo $end_date?></td>
						<td><? echo $str_target?></td>
						<td><? echo $target_name?></td>
						<td><? echo $refer_apply_seq?></td>
						<td><? echo $str_file_cnt?></td>
						<!--<td><? echo $str_file_send_status?></td>-->
						<td><? echo $register_name?></td>
						<td>
							<? $delete_event_title = trsLang('파일반입예외설정','fileimportpolicy')." ".trsLang('삭제','btndelete');?>
							<a href='javascript:void(0)' title="<? echo $delete_event_title;?>" onclick="deleteFilePolicy_Row()" class='text_link  required-delete-auth hide'><? echo trsLang('삭제','btndelete');?></a>
							<input type='hidden' name='policy_file_in_seq' class='clsid_policy_file_in_seq'  value='<? echo $policy_file_in_seq;?>'>
						</td>
					</tr>
				<?
							$no--;
						}
					}

				if($total < 1) {
					
				?>
					<tr>
						<td colspan="10" align="center"><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
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
</div>
<form name='frmPolicy' id='frmPolicy' class='display-none'>
	<input type='hidden' name='proc' id='proc' >
	<input type='hidden' name='proc_name' id='proc_name' >
	<input type='hidden' name='policy_file_in_seq' id='policy_file_in_seq'>
</form>
<?php

if($result) sqlsrv_free_stmt($result);
sqlsrv_close($wvcs_dbcon);

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>