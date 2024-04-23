<?php
$page_name = "kabang_emp_list";
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

$searchopt = $_REQUEST["searchopt"];	// 검색옵션
$searchkey = $_REQUEST["searchkey"];	// 검색어
$dept_name_path = $_REQUEST["dept_name_path"];	// 검색어
$fake_name_path = $_REQUEST["fake_name_path"];	// 검색어

if (empty($searchopt)) {
	$searchopt = 'EMP_NAME';
}

$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($dept_name_path!="") $param .= ($param==""? "":"&")."dept_name_path=".$dept_name_path;
if($fake_name_path!="") $param .= ($param==""? "":"&")."fake_name_path=".$fake_name_path;

//검색 로그 기록
$proc_name = $_POST['proc_name'];
if($proc_name != ""){
	$work_log_seq = WriteAdminActLog($proc_name,'SEARCH');
}


$Model_manage = new Model_manage;
		
$args = array("use_yn"=>"Y","admin_level"=>$admin_level);
$presets = $Model_manage->getAdminAuthPresetLists($args);
?>
<script type="text/javASCript">
	var _menuAuth = {
<?php

	foreach($_CODE['admin_menu_auth'] as $key => $value){
			echo $key.":'".$value."',";
	}
?> };

	$(document).ready(function(){
		changeAdminAuthPresetType();
	});

	function toggleCheckbox(id) {
		$(`#${id}`).prop('checked', !$(`#${id}`).prop('checked'))
	}
</script>
<div id="oper_list">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["staffinfo"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		
		<!--검색폼-->
		
		<div class="tit">
			<?=trsLang('vcs계정생성','accountregisttext');?>
		</div>
		<form name="searchForm"  method="POST">
			<input type='hidden' name='proc_name' id='proc_name'>
			<table class="search">
				<tr>
					<th><?= $_LANG_TEXT['finddepartment'][$lang_code] ?> </th>
					<td style="padding: 5px 13px;">
						<input type="hidden" name="dept_name_path" id="dept_name_path" value="<?=$dept_name_path?>">
						<input type="text" name="fake_name_path" id="fake_name_path" value="<?=$fake_name_path?>" class="frm_input" style="width:calc(50% + 155px)" readonly onclick="searchKabankDepartment()" >
						<button class="btn" onclick="resetDeptNapePath(); return false;" style="margin-top: 0; margin-left:0;"><?php echo $_LANG_TEXT["departmentstepreset"][$lang_code];?></button>
					</td>
				</tr>
				<tr>
					<th><?=$_LANG_TEXT["staff"][$lang_code];?> <?=$_LANG_TEXT["usersearchtext"][$lang_code];?></th>
					<td>
						<select name="searchopt" class="select_bg" id="searchopt" style="min-width: 150px; margin-top: 1px; height: 31px;">
							<option value="" <?php if($searchopt == "") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT["searchkeywordselecttext"][$lang_code];?></option>
							<option value="EMP_NAME" <?php if($searchopt == "EMP_NAME") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT["empnametext"][$lang_code];?></option>
							<option value="EMP_ID" <?php if($searchopt == "EMP_ID") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT["empnotext"][$lang_code];?></option>
							<option value="DEPT" <?php if($searchopt == "DEPT") { echo ' selected="selected"'; } ?>><? echo trsLang('부서명','deptnametext');?></option>
						</select>

						<input type="text" name="searchkey" id="searchkey"  value="<?=$searchkey?>" class="frm_input" style="width:50%"   maxlength="200">
						
						<input type="submit" value="<?=$_LANG_TEXT["btnsearch"][$lang_code];?>" class="btn_submit" onclick="return searchKabankEmp(document.searchForm);">
						<?php if (0) { ?>
						<input type="button" value="<?=$_LANG_TEXT["finddepartmentemp"][$lang_code];?>" class="btn_submit" style="width: 180px; padding-left: 30px;" onclick="return searchKabankDepartmentEmployee(document.searchForm);">
						<?php } ?>
					</td>
				</tr>
			</table>
		</form>
		<form name='frmEmpAccountList' id='frmEmpAccountList' method='POST'>
			<input type='hidden' name='proc' id='proc' value='CREATE'>
			<input type='hidden' name='proc_name' >
			<input type='hidden' name='emp_seq_list' id='emp_seq_list'>
			<table class="search">
				<tr>
					<th><?=$_LANG_TEXT["accessauthtext"][$lang_code];?></th>
					<td>
						<table class="view">
							<tr>
								<th><?=$_LANG_TEXT["userleveltext"][$lang_code];?></th>
								<td colspan="3">
									<select name='admin_level' id='admin_level' style="width:200px" onchange="SetAdminAuth();">
										<option value=''><?=$_LANG_TEXT["choosetext"][$lang_code];?></option>
										<?
										$option = $_CODE['admin_level'];
										foreach($option as $value => $name){

											echo "<option value='$value' >$name</option>";
										}
										?>
									</select>
								</td>
							</tr>
							
							<?
							$result = $Model_manage->getOrganList();
							$org_count = sqlsrv_num_rows($result);
							?>
							<tr class="<?if($org_count==1) echo "display-none";?>">
								<th style='width:150px;'><?=$_LANG_TEXT["manageorgantext"][$lang_code];?></th>
								<td colspan='3'>
									<div id='admin_mng_org'>
										<div class="radio" style="display:inline">
										<?php
										if($result){
											while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

												$mng_org_id = $row['org_id'];
												$mng_org_name = $row['org_name'];
												if($org_count==1) $checked = "checked";

												echo "<div class='checkbox'><input type='checkbox' name='mng_org[]' id='mng_org_{$mng_org_id}' value='{$mng_org_id}' {$checked}><label for='mng_org_{$mng_org_id}'>{$mng_org_name}</label></div>";
											}
										}
										?>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<th><?=$_LANG_TEXT["pageauthbypreset"][$lang_code];?></th>
								<td colspan="3">
									<div id='admin_mng_menu' style="margin: 0;" >
										<div style="margin: 0; display: inline-block;line-height: 30px;">
											<select name="admin_auth_type" id="admin_auth_type" data-selected-auth-type="<?php echo $admin_menu_auth_type; ?>" data-selected-preset-seq="<?php echo $admin_menu_auth_preset_seq; ?>" style="min-width:250px;" onchange="changeAdminAuthPresetType()">
												<option value="" data-target-level="NONE"><?php echo $_LANG_TEXT["choosetext"][$lang_code]; ?></option>
												<option disabled data-target-level="NONE">──────────────</option>
												<?php 
												foreach ($presets as $idx => $preset) {
													@extract($preset);
													$selected = ($admin_menu_auth_preset_seq == $preset_seq) ? "selected" : "";
													echo "<option value=\"PRESET\" data-preset-seq=\"{$preset_seq}\" data-target-level=\"{$target_level}\" {$selected}>{$preset_title}</option>";
												}
												?>
											</select>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<th style='width:150px;'><?=$_LANG_TEXT["managescancentertext"][$lang_code];?></th>
								<td colspan='3'>
									<div id='admin_mng_scan_center'>
										<div class="radio" style="display:inline">
										<?php
										$result = $Model_manage->getCenterList();
										
										if($result){
											while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

												$scan_center_code = $row['scan_center_code'];
												
												if($org_count > 1){
													$scan_center_name = $row['org_name']." ".$row['scan_center_name'];
												}else{
													$scan_center_name = $row['scan_center_name'];
												}

												echo "<div class='checkbox'><input type='checkbox' name='mng_scan_center[]' id='mng_scan_center_{$scan_center_code}' value='{$scan_center_code}'><label for='mng_scan_center_{$scan_center_code}'>{$scan_center_name}</label></div>";
											}
										}
										?>
										</div>
									</div>
								</td>
							</tr>

							<tr>
								<th><?=$_LANG_TEXT["pageaccessauth"][$lang_code];?></th>
								<td colspan="3" style="display: flex;">
									<div id='admin_mng_page_auth' style="display:flex;margin:5px 10px 4px 0;">
										<div class="radio">
											<?php
											$result = $Model_manage->getMenuList();
											if($result){
												while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
													$menu_code = $row['menu_code'];
													$menu_name = $_CODE['menu'][$menu_code];

													if ($admin_menu_auth_type == "CUSTOMIZE") {
														$checked = @in_array($menu_code, $emp_menu) ? "checked":"";
													}

													echo "<div class='checkbox'><input type='checkbox' disabled='disabled' class='{$checked}' name='menu[]' id='menu_{$menu_code}' value='{$menu_code}'><label for='menu_{$menu_code}'>{$menu_name}</label></div>";
												}
											}
											?>
											<input type="hidden" id="admin_auth_preset_seq" name="admin_auth_preset_seq" value="<?php echo $admin_menu_auth_preset_seq; ?>" />
										</div>
									</div>
									<div id='admin_mng_menu' style="display:flex;margin: 5px 0 0 0;height: 20px;white-space: nowrap;" >
										<div id="page_auth_preview" style="display:inline-flex;"><a href='javascript:void(0)' class='text_link' onclick='popAdminPageAuthDetail();' data-preset-seq='<?php echo $admin_menu_auth_preset_seq; ?>'>[<?php echo $_LANG_TEXT["pageauthpreview"][$lang_code]; ?>]</a></div>
										<div id='page_auth_detail' style="display:inline-flex;"><a href='javascript:void(0)' class="text_link required-update-auth hide" onclick='popAdminPageAuth();' data-emp-seq='<? echo $emp_seq?>'>[<? echo $_LANG_TEXT["setpageaccesstext"][$lang_code]; ?>]</a></div>
									</div>
								</td>
							</tr>
						</table>					
					</td>
				</tr>
			</table>
		
		<div class="btn_confirm right" >
			<a href="<? echo $_www_server?>/manage/kabang_emp_list.php" class="btn2"><?=trsLang('목록','btnlist');?></a>
			<a href="javascript:void(0)" onclick="frmEmpAccountListSubmit()" class="btn2  required-create-auth hide"><?=trsLang('등록','btnregist');?></a>
		</div>
		
		<!--검색결과리스트-->
		<div class="sub_tit" >
			> 검색 결과
		</div>
		<table class="list" style='margin-top:0px;'>
			<tr>	
				<th style='width:40px;min-width:40px'><input type='checkbox'  onclick="$('.clsid_cbx_emp:not(:disabled)').prop('checked',this.checked)" style="width:15px;height:15px;"></th>
				<th class="num" style='width:70px;min-width:70px'><?=$_LANG_TEXT["numtext"][$lang_code];?></th>
				<th style='min-width:90px'><?=$_LANG_TEXT["empnametext"][$lang_code];?></th>
				<th style='min-width:100px'><?=$_LANG_TEXT["empnotext"][$lang_code];?></th>
				<th style='min-width:100px'><?=$_LANG_TEXT["depttext"][$lang_code];?></th>
				<th style='width:120px;min-width:120px'>VCS 계정 유무</th>
			</tr>
			<?php
		
			$total = 0;
			$search_sql = "";

		  	if (($searchkey != "") && ($searchopt != "")) {
				if($searchopt=="EMP_NAME") {
					$searchkey = aes_256_enc($searchkey);
					$search_sql .= " AND k.emp_name = '{$searchkey}' ";
				} else if($searchopt=="EMP_ID") {
					$search_sql .= " AND k.emp_id like '%{$searchkey}%' ";
				} else if($searchopt=="DEPT") {
					$search_sql .= " AND k.dept_name like N'%{$searchkey}%' ";
				}
			}

			// 사용자 부서 검색
			if (!empty($dept_name_path)) {
				$search_sql .= " AND ( k.dept_name_path LIKE N'{$dept_name_path}%' OR  k.dept_name LIKE N'{$dept_name_path}%' )";
			}

			if (!empty($search_sql)) {
				$args   = array("search_sql"=>$search_sql);
				$result = $Model_manage->getKabangSyncEmpList($args);
			}

			 if ($result) {
				$total = sqlsrv_num_rows($result);
				$no = $total;

				while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
					$rnum = $row['rnum'];
					$emp_id = $row['emp_id'];
					$emp_name = aes_256_dec($row['emp_name']);
					$dept_name = $row['dept_name'];
					$vcs_emp_seq = $row['vcs_emp_seq'];
					$dept_name_path = str_replace(';', ' > ', $row['dept_name_path']);
					$str_vcs_account = ($vcs_emp_seq > 0) ? "있음" : "없음";
			?>	
			<tr onclick="toggleCheckbox('emp_id_<? echo $no?>')">
				<td onclick="event.stopPropagation();";><input type='checkbox' class='clsid_cbx_emp' name='emp_id[]' id='emp_id_<? echo $no?>' value='<? echo $emp_id?>' <?if($vcs_emp_seq > 0) echo "disabled";?> style="width:15px;height:15px;"></td>
				<td><?php echo $no; ?></td>
				<td>
					<input type='hidden' name='emp_name[]' value='<? echo $emp_name?>'>
					<?=$emp_name?>
				</td>
				<td><?=$emp_id?></td>
				<td><?=$dept_name?></td>
				<td><?=$str_vcs_account?></td>
			</tr>
			<?php
				$no--;
				}
			}
		 
			if ($total < 1) {
			?>
			<tr>
				<td colspan="8"><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
			</tr>
			<?php
			}
			?>		
		</table>

	</form>
	</div>

</div>

<!--조직도 검색 모달-->
<div id="modal_department_search" class="modal" >
  	<div class="modal-content" style='width:800px;'>
		<div class="" style="display:flex; align-items: center; justify-content:space-between; width:100%">
			<strong class="modal-title"><?= $_LANG_TEXT["finddepartmentemp"][$lang_code]; ?></strong>
			<span class="close">&times;</span> 
		</div>
		<div id='department_tree' style='padding:15px;max-height:600px;min-height:400px;overflow-y:auto'></div>
		<div class="btn_wrap ">
			<a href="javascript:void(0)" class="btn-white" style="padding:6px 20px;" onclick="openAllDetails()"><?= $_LANG_TEXT["opendepttree"][$lang_code]; ?></a>
			<a href="javascript:void(0)" class="btn-white" style="padding:6px 20px;" onclick="closeAllDetails()"><?= $_LANG_TEXT["closedepttree"][$lang_code]; ?></a>
			<a href="javascript:void(0)" class="btn-white" style="padding:6px 20px;background: #999;color:#fff;" onclick="closeModalWindow('modal_department_search')"><?= $_LANG_TEXT["canceltext"][$lang_code]; ?></a>
		</div>
	</div>
</div>

<!--페이지 접근권한 설정 모달-->
<div id="modal_admin_auth_detail" class="modal" >
  	<div class="modal-content" style='width:1000px;'>
		<div class="" style="display:flex; align-items: center; justify-content:space-between; width:100%">
			<strong class="modal-title" id='pop_page_title'><? echo trsLang('페이지접근권한설정','setpageaccesstext')?></strong>
			<span class="close">&times;</span> 
		</div>
		<div id='menu_auth_detail' style='padding:15px;max-height:500px;overflow-y:auto'></div>
		<div class="btn_wrap ">
			<a href="javascript:void(0)"class="btn  required-update-auth hide" id='btnSaveDetailAuth'  onclick="frmAdminAuthDetailsSubmit()"><?= $_LANG_TEXT["save_file"][$lang_code]; ?></a>
		</div>
	</div>
</div>
<?php


if($result) sqlsrv_free_stmt($result);  
sqlsrv_close($wvcs_dbcon);

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>