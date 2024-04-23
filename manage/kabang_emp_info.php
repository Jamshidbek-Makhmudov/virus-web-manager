<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache,must-revalidate");

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

$emp_seq = $_REQUEST["emp_seq"];
$searchopt = $_REQUEST['searchopt'];	// 검색옵션
$searchkey = $_REQUEST['searchkey'];	// 검색어
$orderby = $_REQUEST["orderby"];		//정렬

$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;


if($emp_seq <> "") {

		
		$Model_manage = new Model_manage;
			
		$args = array("emp_seq"=>$emp_seq);
		$Model_manage->SHOW_DEBUG_SQL = false;
		$result = $Model_manage->getKabangEmpInfo($args);
		$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

		$org_id = $row['org_id'];
		$emp_seq = $row['emp_seq'];
		$emp_name = aes_256_dec($row['emp_name']);
		$emp_id = $row['emp_id'];
		$dept_name = $row['dept_name'];
		$dept_name_path = str_replace(';', ' > ', $row['dept_name_path']);
		$admin_level = $row['admin_level'];
		$work_yn = $row['work_yn'];
		
		/*권한그룹*/
		$args = array("use_yn"=>"Y","admin_level"=>$admin_level);
		$presets = $Model_manage->getAdminAuthPresetLists($args);
		
		/*메뉴정보*/
		$args = array("emp_seq"=>$emp_seq);
		$emp_menu = array();
		$emp_menu_info = $Model_manage->getAdminMenuCustomized($args);
		if ($emp_menu_info) {
			foreach($emp_menu_info as $menu) {
				$emp_menu[$menu['menu_code']] = $menu['page_code'];
			}
		}

		/*권한정보*/
		$emp_menu_auth = $Model_manage->getAdminMenuAuth($args);
		$admin_menu_auth_seq  = $emp_menu_auth["admin_menu_auth_seq"];
		$admin_menu_auth_type = $emp_menu_auth["auth_type"];
		$admin_menu_auth_preset_seq = $emp_menu_auth["auth_preset_seq"];
		
		/*관리기관*/
		$qry_params = array("emp_seq"=>$emp_seq);
		$qry_label = QRY_ADMIN_MNG_ORG;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);
		
		if($result){
			while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
				$emp_mng_org[] = $row['org_id'];
			}
		}
		
		/*관리스캔센터*/
		$qry_params = array("emp_seq"=>$emp_seq);
		$qry_label = QRY_ADMIN_MNG_SCAN_CENTER;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);
		
		if($result){
			while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
				$emp_mng_scan_center[] = $row['scan_center_code'];
			}
		}
		
}

//**화면열람로그 기록
if($emp_seq != ""){
	$page_title = "[{$emp_name}] ".$_LANG_TEXT["staffinfo"][$lang_code];
	$work_log_seq = WriteAdminActLog($page_title,'VIEW');
}else{
	$page_title = $_LANG_TEXT["staffinfo"][$lang_code];
}

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
		return UserSubOrgSet();
	});
</script>
<?
include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";
?>
<div id="oper_input">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$page_title;?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		<div class="page_right"><span style='cursor:pointer'
				onclick="history.back();"><?=$_LANG_TEXT["btngobeforepage"][$lang_code];?></span></div>

		<!--등록폼-->
		<form name="frmMgr" id="frmMgr" method="post">
			<input type='hidden' name='proc' id='proc' >
			<input type='hidden' name='proc_name' id='proc_name' >
			<input type="hidden" name="emp_seq" id="emp_seq" value="<?php echo $emp_seq; ?>">
			<input type="hidden" name="emp_id" id="emp_id" value="<?php echo $emp_id; ?>">
			<div class="tit">
				<?=$_LANG_TEXT["staffinfo"][$lang_code];?>
			</div>
			<table class="view">
				<tr>
					<th style='min-width:150px'><?=$_LANG_TEXT["empnametext"][$lang_code];?></th>
					<td style='width:300px'><?php echo $emp_name; ?></td>
					<th style='min-width:150px' class="line"><?=$_LANG_TEXT["empnotext"][$lang_code];?></th>
					<td><?php echo $emp_id; ?></td>
				</tr>
				<tr class="bg">
					<th><?=$_LANG_TEXT["deptnametext"][$lang_code];?></th>
					<td>
						<input type='hidden' name='org_id' id='org_id' value='<? echo $org_id?>'>
						<?php echo $dept_name; ?>
					</td>
					<th class="line"><?=$_LANG_TEXT["userleveltext"][$lang_code];?></th>
					<td>
						<input type='hidden' name='admin_level' id='admin_level' value='<? echo $admin_level;?>'>
						<? echo $_CODE['admin_level'][$admin_level];?>
					</td>
			</tr>
			<tr>
				<th><? echo trsLang('사용여부','useyesnonntext');?></th>
				<td colspan="3">
					<select name='work_yn' id='work_yn'>
						<option value='Y' <?if($work_yn=="Y") echo "selected";?>><? echo trsLang('사용','useyestext');?></option>
						<option value='N' <?if($work_yn=="N") echo "selected";?>><? echo trsLang('사용안함','usenotext');?></option>
					</select>
				</td>
			</tr>	
			</table>
			<div class="tit" style="margin-top:30px">
				<?=$_LANG_TEXT["accessauthtext"][$lang_code];?>
			</div>
			<table class="view">
				<tr>
					<th style='width:150px;'><?=$_LANG_TEXT["manageorgantext"][$lang_code];?></th>
					<td colspan='3'>
						<div id='admin_mng_org'>
							<?
							if($admin_level=='SUPER'){ 
								echo "<span name='all'>".$_LANG_TEXT["alltext"][$lang_code]."</span>";
							 }
							?>
							<div class="radio" style="display:<?=$admin_level=='SUPER'? 'none' : 'inline';?>">
							<?php
							$result = $Model_manage->getOrganList();
							
							if($result){
								$org_count = sqlsrv_num_rows($result);

								while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

									$mng_org_id = $row['org_id'];
									$mng_org_name = $row['org_name'];

									if (is_array($emp_mng_org)) {
										$checked = in_array($mng_org_id,$emp_mng_org)? "checked=true" : "";
									} else {
										$checked = ""; 
									}

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
									<option value="CUSTOMIZE" data-preset-seq="" data-target-level="NONE" <?php echo ($admin_menu_auth_type == "CUSTOMIZE") ? "selected":"";?>><?php echo $_LANG_TEXT["pageauthbycustomize"][$lang_code]; ?></option>
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
								$qry_params = array();
								$qry_label = QRY_COMMON_SCAN_CENTER_USE_ALL;
								$sql = query($qry_label,$qry_params);

								$result = sqlsrv_query($wvcs_dbcon, $sql);
								
								if ($result) {
									while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
										$scan_center_code = $row['scan_center_code'];

										if ($org_count > 1) {
											$scan_center_name = $row['org_name']." ".$row['scan_center_name'];
										} else {
											$scan_center_name = $row['scan_center_name'];
										}

										if (is_array($emp_mng_scan_center)) {
											if ($admin_menu_auth_type == "CUSTOMIZE") {
												$checked = @in_array($scan_center_code, $emp_mng_scan_center)? "checked" : "";
											}
										} else {
											$checked = "";
										}
									
										echo "<div class='checkbox'><input type='checkbox' class='{$checked}' name='mng_scan_center[]' id='mng_scan_center_{$scan_center_code}' value='{$scan_center_code}'><label for='mng_scan_center_{$scan_center_code}'>{$scan_center_name}</label></div>";
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
										$checked   = "";

										if ($admin_menu_auth_type == "CUSTOMIZE") {
											if (isset($emp_menu[$menu_code])) {
												$checked = ($emp_menu[$menu_code] == 'all') ? "checked":"indeterminate";
											}
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

			<div class="btn_wrap">
				<div class="right">
					<a href="<? echo $_www_server?>/manage/kabang_emp_list.php" class="btn" id="btnList"><?=$_LANG_TEXT["btnlist"][$lang_code];?></a>
					<a href="javascript:void(0)" onclick="ManagerInfoSubmit('UPDATE')" class="btn required-update-auth hide"><?=$_LANG_TEXT["btnsave"][$lang_code];?></a>
					<a href="javascript:void(0)" onclick="ManagerInfoSubmit('DELETE')" class="btn required-delete-auth hide"><?=$_LANG_TEXT["btndelete"][$lang_code];?></a>
				</div>
			</div>

		</form>

	</div>

</div>
<div id="modal_admin_auth_detail" class="modal" >
  	<div class="modal-content" style='width:1000px;'>
		<div class="" style="display:flex; align-items: center; justify-content:space-between; width:100%">
			<strong class="modal-title" id='pop_page_title'><? echo trsLang('세부권한지정','setauthdetails')?></strong>
			<span class="close">&times;</span> 
		</div>
		<div id='menu_auth_detail' style='padding:15px;max-height:500px;overflow-y:auto'></div>
		<div class="btn_wrap "><a href="javascript:void(0)"class="btn required_auth" id='btnSave' ><?= $_LANG_TEXT["save_file"][$lang_code]; ?></a></div>
	</div>
</div>
<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>