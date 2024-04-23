<?php
$page_name = "admin_list";
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
$dept = $_REQUEST['dept'];

$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;
if($dept!="") $param .= ($param==""? "":"&")."dept=".$dept;


$Model_manage = new Model_manage();

$args = array("use_yn"=>"Y");
$presets = $Model_manage->getAdminAuthPresetLists($args);

if($emp_seq <> "") {
	if($dept != ""){
		$search_sql .= " and A.dept_seq = '$dept' ";
	}

	if($searchkey != "" && $searchopt != ""){
		$search_sql .= " AND $searchopt like '%$searchkey%' ";
	}

	if($orderby != "") {
		$order_sql = " ORDER BY $orderby ";
	} else {
		$order_sql = " ORDER BY emp_seq DESC ";
	}
	

	if(COMPANY_CODE=="600"){	//카카오뱅크
		$search_sql .= " AND admin_level in ('SUPER','MAJOR')  ";
	}else{
		$search_sql .= " AND admin_level > ''  ";
	}
	
	$qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"emp_seq"=>$emp_seq);
	$qry_label = QRY_EMP_INFO;
	$sql = query($qry_label,$qry_params);

	//echo $sql;
	//echo nl2br($sql);
				
	$result = sqlsrv_query($wvcs_dbcon, $sql);
	$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

	$emp_seq = $row['emp_seq'];
	$emp_name = aes_256_dec($row['emp_name']);	
	$emp_no = $row['emp_no'];
	$emp_pwd = $row['emp_pwd'];
	$org_id = $row['org_id'];
	$work_yn = $row['work_yn'];
	$dept_seq = $row['dept_seq'];
	$dept_name = $row['dept_name'];
	$org_name = $row['org_name'];
	//james
	$login_lock_yn = $row['LOGIN_LOCK_YN'];
	$login_lock_type = $row['LOGIN_LOCK_TYPE'];
	if($login_lock_yn=="") $login_lock_yn = "N";
	
	$rnum = $row['rnum'];


	$email = $row['email'] ? aes_256_dec($row['email']) : "";
	$phone_no = $row['phone_no'] ? aes_256_dec($row['phone_no']) : "";
	$jpos_code = $row['jpos_code'];
	$jduty_code = $row['jduty_code'];
	$jgrade_code = $row['jgrade_code'];

	$use_lang = $row['use_lang'];
	$admin_level = $row['admin_level'];
	
	$title_word = $_LANG_TEXT["managetext"][$lang_code];
	$title_pw_word = $_LANG_TEXT["resettext"][$lang_code];

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
	
	//이전,다음
	$qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"rnum"=>$rnum);
	$qry_label = QRY_EMP_INFO_PREV;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);
	$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
	$prev_emp_seq = $row['emp_seq'];

	$qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"rnum"=>$rnum);
	$qry_label = QRY_EMP_INFO_NEXT;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);
	$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
	$next_emp_seq = $row['emp_seq'];
}

//**화면열람로그 기록
if($emp_seq != ""){
	$page_title = "[{$emp_name}] ".$_LANG_TEXT["m_manage_admin"][$lang_code];
	$work_log_seq = WriteAdminActLog($page_title,'VIEW');
}else{
	$page_title = $_LANG_TEXT["m_manage_admin"][$lang_code];
}

if($use_lang==""){
	$use_lang = $lang_code;
}

if($use_lang =="KR") {
	$checkLangKR = "checked";
}else if($use_lang =="EN") {
	$checkLangEN = "checked";
}else if($use_lang =="JP") {
	$checkLangJP = "checked";
}else if($use_lang =="CN") {
	$checkLangCN = "checked";
}
?>
<script type="text/javASCript">
	var _menuAuth = {
<?php
	foreach($_CODE['admin_menu_auth'] as $key => $value){
		echo "\t\t\"{$key}\": \"{$value}\",\n";
	}
?>	};

	$(document).ready(function(){
		SetAdminAuth();
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
		<form name="frmAdmin" id="frmAdmin" method="post">
			<input type='hidden' name='proc' id='proc'>
			<input type='hidden' name='proc_name' id='proc_name'>
			<input type="hidden" name="emp_seq" id="emp_seq" value="<?php echo $emp_seq; ?>">
			<div class="tit">
				<?=$_LANG_TEXT["admininfotext"][$lang_code];?>
			</div>
			<table class="view">
				<tr>
					<th style='min-width:150px'><span style='color:red'>*</span> <?=$_LANG_TEXT["empnametext"][$lang_code];?></th>
					<td style='width:300px'><input type="text" name="emp_name" id="emp_name" class="frm_input"
							value="<?php echo $emp_name; ?>" style="width:250px" maxlength="30"></td>
					<th style='min-width:150px' class="line"><?=$_LANG_TEXT["emailtext"][$lang_code];?></th>
					<td><input type="text" name="email" id="email" class="frm_input" value="<?php echo $email; ?>"
							style="width:250px" maxlength="120"></td>
				</tr>
				<tr class="bg">
					<th><span style='color:red'>*</span> <?=$_LANG_TEXT["empnotext"][$lang_code];?></th>
					<td><input type="text" name="emp_no" id="emp_no" class="frm_input" value="<?php echo $emp_no; ?>"
							<?=($emp_seq==""? "" : "disabled")?> style="width:250px" maxlength="30">
							<i class="fa fa-info-circle" onmouseover="viewlayer(true, 'moverlayerNameEmpNo');" onmouseout="viewlayer(false, 'moverlayerNameEmpNo');" ></i>
							<div id="moverlayerNameEmpNo" class="viewlayer" style='color:#fff;'>
								<? echo trsLang('아이디는 영문 대문자,소문자,숫자를 사용해 5~12자리로 입력해야합니다','empnoregtext');?>
							</div>
					</td>
					<th class="line"><?=$_LANG_TEXT["contactphonetext"][$lang_code];?></th>
					<td><input type="text" name="phone_no" id="phone_no" class="frm_input" value="<?php echo $phone_no; ?>"
							style="width:250px" maxlength="30" onkeyup="return onlyNumber(this);"></td>
				</tr>
				<tr>
					<th><span style='color:red'>*</span> <?=$_LANG_TEXT["passwordtext"][$lang_code];?> <?=$title_pw_word?></th>
					<td><input type="password" name="emp_pwd" id="emp_pwd" class="frm_input" style="width:250px;"
							value="<?if($emp_seq=="") echo $_initialpassword;?>" maxlength="20">
						<i class="fa fa-info-circle" onmouseover="viewlayer(true, 'moverlayerNameAdminPwd');" onmouseout="viewlayer(false, 'moverlayerNameAdminPwd');" ></i>
						<div id="moverlayerNameAdminPwd" class="viewlayer" style='color:#fff;'>
							<? echo trsLang('비밀번호는 영문 대/소문자(A~Z,a~z), 숫자(0~9), 특수문자(!@#$%^&*)를 3개 이상 포함하여 8~16자 이내로 입력해야 합니다.','adminpwdregxtext');?>
						</div>
					</td>
					<th class="line"><span style='color:red'>*</span> <?=$_LANG_TEXT["passwordconfirmtext"][$lang_code];?></th>
					<td><input type="password" name="emp_pwd_confirm" id="emp_pwd_confirm" class="frm_input" style="width:250px;"
							value="<?if($emp_seq=="") echo $_initialpassword;?>" maxlength="20"></td>
				</tr>
				<!-- james start -->
				<? if($emp_seq > 0){?>
				<tr>
					<th><?=$_LANG_TEXT["loginidstatus"][$lang_code];?></th>
					<td colspan="3">
						<span style='padding-right:20px;'>
							<?
					if($login_lock_yn=="Y"){
						$str_lock_yn = "<font color='blue'>".$_LANG_TEXT['loginlock'][$lang_code]."<font>";
					}else{
						$str_lock_yn = $_LANG_TEXT['normal'][$lang_code];
					}

					echo $str_lock_yn;
				?>
						</span>
						<? if($login_lock_yn=="Y" && ($login_lock_type=="" || $login_lock_type=="LOGIN_ATTEMPT_OVER")){ ?>
						<a href="javascript:void(0)" onclick="resetLoginAttempt('<? echo $emp_seq;?>')"
							class="btn20 gray"><?=$_LANG_TEXT["loginattemptresettext"][$lang_code];?></a>
						<?}?>


					</td>
				</tr>
				<?}?>


				<!-- james end -->
			</table>

			<?if($emp_seq==""){?>
			<div class="guide_message"> >> <?=str_replace("****",$_initialpassword,$_LANG_TEXT["initialpasswordguidetext"][$lang_code]);?></div>
			<?}?>

			<div class="tit" style="margin-top:30px">
				<?=$_LANG_TEXT["detailinfotext"][$lang_code];?>
			</div>
			<table class="view">
				<tr>
					<th ><span style='color:red'>*</span> <?=$_LANG_TEXT["adminleveltext"][$lang_code];?></th>
					<td >
						<select name='admin_level' id='admin_level' style="width:200px" onchange="SetAdminAuth();">
							<option value=''><?=$_LANG_TEXT["choosetext"][$lang_code];?></option>
							<?
							$option = $_CODE['admin_level'];
							foreach($option as $value => $name){

								//카카오뱅크 매니저 등급은 카카오뱅크 임직원 정보에서 등록한다.
								if(COMPANY_CODE=="600" && substr($value,0,7)=="MANAGER") continue;	

								echo "<option value='$value' ".($admin_level==$value? "selected=true" : "").">$name</option>";
							}
							?>
						</select>
					</td>
					<th class="line"><? echo trsLang('사용여부','useyesnonntext');?></th>
					<td>
						<select name="work_yn" id="work_yn" style="width:200px">
							<option value="Y" <?php if($work_yn=="Y") echo "selected"; ?>><? echo trsLang('사용함','useyestext');?>
							</option>
							<option value="N" <?php if($work_yn=="N") echo "selected"; ?>><? echo trsLang('사용안함','usenotext');?>
							</option>
						</select>
					</td>
				</tr>
				<tr>
					<th style='min-width:150px'><?=$_LANG_TEXT["workplacetext"][$lang_code];?></th>
					<td style='width:300px'>
						<select name="org_id" id="org_id" onchange="UserSubOrgSet()" style="width:200px">
							<option value=''><?=$_LANG_TEXT["choosetext"][$lang_code];?></option>
							<?php
							$qry_params = array();
							$qry_label = QRY_COMMON_ORG;
							$sql = query($qry_label,$qry_params);

							$result = sqlsrv_query($wvcs_dbcon, $sql);
				
							if($result){
								while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
									$tmp_id = $row['org_id'];
									$tmp_name = $row['org_name'];

									if($org_id == $tmp_id) {
											$select_str = "selected='selected' ";
									}else{
											$select_str = " ";
									}

									echo "<option value='$tmp_id' $select_str >$tmp_name</option>";
								}
							}
							?>
						</select>
					</td>
					<th style='min-width:150px' class="line"><?=$_LANG_TEXT["depttext"][$lang_code];?></th>
					<td>
						<select name="dept_seq" id="dept_seq" style="width:200px">
							<option value='' org=''><?=$_LANG_TEXT["choosetext"][$lang_code];?></option>
							<?php
							$qry_params = array();
							$qry_label = QRY_COMMON_DEPT;
							$sql = query($qry_label,$qry_params);

							$result = sqlsrv_query($wvcs_dbcon, $sql);
							
							if($result){
								while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
									$tmp_org_id = $row['org_id'];
									$tmp_dept_seq = $row['dept_seq'];
									$tmp_dept_name = ($row['lvl']=="" ? $row['org_name']."-": $row['lvl']).$row['dept_name'];

									if($tmp_dept_seq == $dept_seq) {
											$select_str = "selected='selected' ";
									}else{
											$select_str = " ";
									}
									echo "<option value='$tmp_dept_seq' org='$tmp_org_id' $select_str >$tmp_dept_name</option>";
								}
							}
							?>
						</select>
					</td>
				</tr>
				<tr class="bg display-none">
					<th style='min-width:150px'><?=$_LANG_TEXT["jobgradetext"][$lang_code];?></th>
					<td>
						<select name="jgrade_code" id="jgrade_code" style="width:200px">
							<option value=''><?=$_LANG_TEXT["choosetext"][$lang_code];?></option>
							<?php
							$qry_params = array();
							$qry_label = QRY_COMMON_JOBGRADE;
							$sql = query($qry_label,$qry_params);

							$result = sqlsrv_query($wvcs_dbcon, $sql);
							
							if($result){
								while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
									$tmp_code = $row['jgrade_code'];
									$tmp_name = $row['jgrade_name'];
									
									if($jgrade_code == $tmp_code) {
											$select_str = "selected='selected' ";
									}else{
											$select_str = " ";
									}

									echo "<option value='$tmp_code' $select_str >$tmp_name</option>";
								}
							}
							?>
						</select>
					</td>
					<th class="line"><?=$_LANG_TEXT["jobdutytext"][$lang_code];?></th>
					<td>
						<select name="jduty_code" id="jduty_code" style="width:200px">
							<option value=''><?=$_LANG_TEXT["choosetext"][$lang_code];?></option>
							<?php
							$qry_params = array();
							$qry_label = QRY_COMMON_JOBDUTY;
							$sql = query($qry_label,$qry_params);

							$result = sqlsrv_query($wvcs_dbcon, $sql);
				
							while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
								$tmp_code = $row['jduty_code'];
								$tmp_name = $row['jduty_name'];

								if($jduty_code == $tmp_code) {
										$select_str = "selected='selected' ";
								}else{
										$select_str = " ";
								}

								echo "<option value='$tmp_code' $select_str >$tmp_name</option>";
							}
							?>
						</select>
					</td>
				</tr>
				<tr class="bg display-none">
					<th ><?=$_LANG_TEXT["uselangtext"][$lang_code];?></th>
					<td>
						<div class="radio">
							<input type="radio" name="rdoLang" id="korLang" value="KR" <?=$checkLangKR;?>><label
								for="korLang"><?=$_LANG_TEXT["koreantext"][$lang_code];?></label>
							<input type="radio" name="rdoLang" id="engLang" value="EN" <?=$checkLangEN;?>><label
								for="engLang"><?=$_LANG_TEXT["englishtext"][$lang_code];?></label>
							<input type="radio" name="rdoLang" id="cnLang" value="CN" <?=$checkLangCN;?>><label for="cnLang">
								<?=$_LANG_TEXT["chinesetext"][$lang_code];?></label>
						</div>
					</td>
					<th class="line"><span class="display-none"><?=$_LANG_TEXT["jobpostext"][$lang_code];?></span></th>
					<td>
						<select  class="display-none" name="jpos_code" id="jpos_code" style="width:200px">
							<option value=''><?=$_LANG_TEXT["choosetext"][$lang_code];?></option>
							<?php
							$qry_params = array();
							$qry_label = QRY_COMMON_JOBPOS;
							$sql = query($qry_label,$qry_params);

							$result = sqlsrv_query($wvcs_dbcon, $sql);
							
							if($result){
								while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
									$tmp_code = $row['jpos_code'];
									$tmp_name = $row['jpos_name'];

									if($jpos_code == $tmp_code) {
											$select_str = "selected='selected' ";
									}else{
											$select_str = " ";
									}

									echo "<option value='$tmp_code' $select_str >$tmp_name</option>";
								}
							}
							?>
						</select>
					</td>
					
				</tr>
			</table>

			<div class="tit" style="margin-top:30px">
				<?=$_LANG_TEXT["adminauthtext"][$lang_code];?>
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

									if(is_array($emp_mng_org)){
										$checked = in_array($mng_org_id, $emp_mng_org)? "checked=true" : "";
									} else {
										$checked = ($org_count == 1) ? "checked=true" : "";
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
				<?php
					if ($emp_seq != "") {
				?>
				<div class="left display-none">
					<a href="<?if(empty($prev_emp_seq)){?>javascript:alert(nodatatext[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."
						?enc=".ParamEnCoding(" emp_seq=".$prev_emp_seq.($param ? " &" : "" ).$param); }?>" class="btn"
						id='btnPrev'><?=$_LANG_TEXT["btnprev"][$lang_code];?></a>
					<a href="<?if(empty($next_emp_seq)){?>javASCript:alert(nodatatext[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."
						?enc=".ParamEnCoding(" emp_seq=".$next_emp_seq.($param ? " &" : "" ).$param); }?>" class="btn"
						id='btnNext'><?=$_LANG_TEXT["btnnext"][$lang_code];?><a>
				</div>
				<?php	}?>
				<div class="right">
					<a href="./admin_list.php" class="btn" id="btnList"><?=$_LANG_TEXT["btnlist"][$lang_code];?></a>
					<?php
						if ($emp_seq == "") {
					?>
					<a href="javascript:" onclick="AdminInfoSubmit('CREATE')" class="btn required-create-auth hide"><?=$_LANG_TEXT["btnregist"][$lang_code];?></a>
					<?php
						}else{
					?>
					
					<a href="javascript:" onclick="AdminInfoSubmit('UPDATE')"
						class="btn required-update-auth hide"><?=$_LANG_TEXT["btnsave"][$lang_code];?></a>
					<a href="javascript:" onclick="AdminInfoSubmit('DELETE')"
						class="btn required-delete-auth hide"><?=$_LANG_TEXT["btndelete"][$lang_code];?></a>
					<?php
						}
					?>
					<a href="./admin_reg.php" class="btn" id='btnClear'><?=$_LANG_TEXT["btnclear"][$lang_code];?></a>
				</div>
			</div>

		</form>

	</div>

</div>
<div id="modal_admin_auth_detail" class="modal" >
  <div class="modal-content" style='width:1000px;'>
		<div class="" style="display:flex; align-items: center; justify-content:space-between; width:100%">
			<strong class="modal-title" id='pop_page_title'><!--모달 타이틀--></strong>
			<span class="close">&times;</span> 
		</div>
		<div id='menu_auth_detail' style='padding:15px;max-height:500px;overflow-y:auto'></div>
		<div class="btn_wrap ">
			<a href="javascript:void(0)"class="btn required-update-auth hide" id='btnSave' ><?= $_LANG_TEXT["save_file"][$lang_code]; ?></a>
		</div>
</div>

<?php
	include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";
?>