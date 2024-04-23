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

$_policy_file_in_seq = $_POST['policy_file_in_seq'];

$Model_manage = new Model_manage();
$Model_manage->SHOW_DEBUG_SQL = false;
$args = array("policy_file_in_seq"=>$_policy_file_in_seq);	
$result = $Model_manage->GetFileInPolicyInfo($args);

//지정파일목록 초기셋팅
$FileListData[] = array("file_hash"=>"", "file_comment"=>"");

if($result){
	while($row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		$policy_name = $row['policy_name'];
		$start_date = setDateFormat($row['start_date']);
		$end_date =  setDateFormat($row['end_date']);
		$target = $row['target'];
		$target_value = $row['target_value'];
		$target_name = aes_256_dec($row['target_name']);
		$file_div = $row['file_div'];
		$file_send_status = $row['file_send_status'];
		$emp_name = aes_256_dec($row['emp_name']);
		$create_date = $row['create_date'];
		$refer = $row['refer'];

		$policy_file_in_seq = $row['policy_file_in_seq'];
		
		$file_in_apply_seq = $row['file_in_apply_seq'];

		//방문자 반입 예외 신청에 대한 승인은 승인자를 정책 등록자로 본다.
		if($file_in_apply_seq ==""){
			$register_name = $emp_name;
		}else{
			$register_name = $target_name;
		}
	}
	
	//지정파일정보 가져오기
	if($file_div=="FILE"){
		
		$Model_manage->SHOW_DEBUG_SQL = false;
		$result_file = $Model_manage->GetFileInPolicyFileListInfo($args);
		$idx = 0;
		while($row = @sqlsrv_fetch_array($result_file,SQLSRV_FETCH_ASSOC)){
			$FileListData[$idx] = array("file_name"=>$row['file_name'],"file_hash"=>$row['file_hash'],"file_comment"=>$row['file_comment']);
			$idx++;
		}
	}
}

if($start_date==""){
	$start_date = date("Y-m-d");
}
if($end_date==""){
	$end_date = date("Y-m-d", strtotime("+7 days"));
}
?>
<div id="oper_input">
	<div class="outline">
		<div class="container">

			<div id="tit_area">
				<div class="tit_line">
					 <h1><span id='page_title'><?=$_LANG_TEXT["m_manage_policy"][$lang_code];?></span></h1>
				</div>
				<span class="line"></span>
			</div>

			<!--tab-->
			<ul class="tab" style='padding:0px;'>
				<li>
					<a href="<? echo $_www_server?>/manage/policy.php" ><? echo trsLang('전체설정','totalconfig');?></a>
				</li>
				<li  class="on">
					<a href="<? echo $_www_server?>/manage/policy_file_import.php"><? echo trsLang('파일반입예외설정','fileimportpolicy');?></a>
				</li>
			</ul>			
			<form name='frmPolicy' id='frmPolicy' method='post'>
				<input type='hidden' name='proc' id='proc'>
				<input type='hidden' name='proc_name' id='proc_name'>
				<input type='hidden' name='policy_file_in_seq' id='policy_file_in_seq' value='<?=$policy_file_in_seq?>'>
				
				<table class="view" >
					<tr>
						<th><label for='policy_name'><? echo trsLang('정책명','policyname');?></label></th>
						<td colspan="3">
							<input type='text' class="frm_input check_valid_data" name='policy_name' id='policy_name' maxlength="50" style='width:500px;' value='<? echo $policy_name?>'>
						</td>
					</tr>
					<tr>
						<th style='width:100px;'><? echo trsLang('적용기간','applicationperiod');?></th>
						<td style='min-width:200px' colspan="3">
							<input type="text" name="start_date" id="start_date" class="frm_input datepicker" placeholder="" value="<?= $start_date ?>" maxlength="10"> ~ 
							<input type="text" name="end_date" id="end_date" class="frm_input datepicker" placeholder="" value="<?= $end_date ?>" maxlength="10">
						</td>
					</tr>					
					<tr>
						<th><label for='target_name'><? echo trsLang('예외대상','exceptiontarget');?></label></th>
						<td colspan="3">
							<select name='target' id='target' class='check_valid_data' onchange="changePolicyTarget()">
								<option value=''><? echo trsLang('대상구분선택','selecttargetdiv'); ?></option>
								<option value='ALL'  <? if($target=="ALL") echo "selected";?>><? echo trsLang('전체','alltext'); ?></option>
								<option value='DEPT' <? if($target=="DEPT") echo "selected";?>><? echo trsLang('부서','depttext'); ?></option>
								<option value='EMP' <? if($target=="EMP") echo "selected";?>><? echo trsLang('사용자','usertext'); ?></option>
							</select>
							<input type='hidden' name='target_value' id='target_value' value='<? echo $target_value?>'>
							<span id='target_search_wrap' style='display:none'>
								<input type='text' class="frm_input" name='target_name' id='target_name' maxlength="50" style='width:300px;' value='<? echo $target_name?>' readonly>
								<input type="button" value="<? echo trsLang('검색','usersearchtext'); ?>" class="btn_submit_no_icon" onclick="popSyncEmployee()">
							</span>
						</td>
					</tr>
					<tr>
						<th><? echo trsLang('예외적용파일','exceptionfile');?></th>
						<td colspan="3">
							<select name='file_div' id='file_div' onchange="showFileListForm()">
								<option value='ALL' <? if($file_div=="ALL") echo "selected";?>><? echo trsLang('전체','alltext'); ?></option>
								<option value='FILE' <? if($file_div=="FILE") echo "selected";?>><? echo trsLang('지정파일','specifiedfile'); ?></option>
							</select>
						</td>
						<!--<th class='line'><? echo trsLang('서버전송여부','server_transfer_status');?></th>
						<td >
							<select name='file_send_status' id='file_send_status' >
								<option value='0' <? if($file_send_status=="0") echo "selected";?>><? echo trsLang('미전송','notsend_server'); ?></option>
								<option value='1' <? if($file_send_status=="1") echo "selected";?>><? echo trsLang('전송','send_server'); ?></option>
							</select>
						</td>-->
					</tr>
					<?if($_policy_file_in_seq != ""){?>
					<tr>
						<th><? echo trsLang('등록일자','registdatetext');?></th>
						<td style='width:300px;'><? echo setDateFormat($create_date,"Y-m-d H:i");?></td>
						<th class='line'><? echo trsLang('등록자','registertext');?></th>
						<td><? echo $register_name;?></td>
					</tr>
					<?}?>
				</table>

				<!--버튼-->
				<div class="btn_wrap">
					<div class="right">
						<?
							$save_event_title = trsLang('파일반입예외설정','fileimportpolicy')." ".trsLang('저장','btnsave');
							$delete_event_title = trsLang('파일반입예외설정','fileimportpolicy')." ".trsLang('삭제','btndelete');
						?>
						<a href="<? echo $_www_server?>/manage/policy_file_import.php" class="btn" ><? echo trsLang('목록','btnlist');?></a>
						<?if($_policy_file_in_seq != ""){?>
							<a href="javascript:void(0)" title="<? echo $save_event_title;?>" class="btn required-update-auth hide" onclick="saveFilePolicy()"><? echo trsLang('저장하기','btnsave');?></a>
							<a href="javascript:void(0)" title="<? echo $delete_event_title;?>" class="btn required-delete-auth hide"  onclick="deleteFilePolicy()"><? echo trsLang('삭제','btndelete');?></a>
						<?}else{?>
							<a href="javascript:void(0)" title="<? echo $save_event_title;?>" class="btn required-create-auth hide" onclick="saveFilePolicy()"><? echo trsLang('저장하기','btnsave');?></a>
						<?}?>
					</div>
				</div>
				
				<!--지정파일-->
				<div id='FileListWrap' style='display:<?if($file_div=="FILE"){ echo "block";}else{ echo "none";} ?>'>
					<div class="sub_tit" style='line-height:30px;margin-top:0px;'> > <? echo trsLang('지정파일','specifiedfile'); ?></div>
					<table class="list"  id='tblFileList'>
					<tr>
						<th style='width:300px;'><? echo trsLang('파일명','filenametext');?></th>
						<th style='width:450px;'><? echo trsLang('파일해시','filehash');?>(MD5)</th>
						<th style='width:450px;'><? echo trsLang('설명','descriptiontext');?></th>
						<th style='text-align:left;min-width:80px'><? echo trsLang('추가/삭제','addnremove');?></th>
					</tr>
					<?
						for($i = 0 ; $i <sizeof($FileListData) ; $i++){
							$file_name = $FileListData[$i]['file_name'];
							$file_hash = $FileListData[$i]['file_hash'];
							$file_comment =  $FileListData[$i]['file_comment'];
					?>	
					<tr>
						<td style='text-align:left;'>
							<input type='text' class="frm_input check_valid_data clsid_file_name"  name='file_name[]'  maxlength="300" style='width:95%;' placeholder="<? echo trsLang('파일명을 입력하세요','inputfilename');?>" value='<? echo $file_name;?>'>
						</td>
						<td style='text-align:left;'>
							<input type='text' class="frm_input check_valid_data clsid_file_hash"  name='file_hash[]'  maxlength="50" style='width:95%;' placeholder="<? echo trsLang('파일해시값(MD5)을 입력하세요','inputfilehash');?>" value='<? echo $file_hash;?>'>
						</td>
						<td style='text-align:left;'>
							<input type='text' class="frm_input check_valid_data" name='file_comment[]' maxlength="50" style='width:95%;' placeholder="<? echo trsLang('설명을 입력하세요.','inputdescription');?>" value='<? echo $file_comment;?>'>
						</td>
						<td style='text-align:left;'>
							<a href="javascript:void(0)" class='btn20 gray' style='width:10px'  onclick="appendRow_FilePolicy()">+</a>
							<a href="javascript:void(0)" class='btn20 gray' style='width:10px'  onclick="removeRow_FilePolicy()" >-</a>
						</td>
					</tr>
					<?}?>
					</table>
				</div>

			</form>
		</div>


	</div>
</div>
<!--예외대상검색-->
<div id='popContent' style='display:none'></div>
<?php

if($result) sqlsrv_free_stmt($result);
sqlsrv_close($wvcs_dbcon);

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>