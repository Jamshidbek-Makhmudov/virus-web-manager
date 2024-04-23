<?php
$page_name = "m_user_list";
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
$dept = $_REQUEST['dept'];				
$orderby = $_REQUEST["orderby"];		//정렬

$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;
if($dept!="") $param .= ($param==""? "":"&")."dept=".$dept;

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
	
		$qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"emp_seq"=>$emp_seq);
		$qry_label = QRY_EMP_INFO;
		$sql = query($qry_label,$qry_params);

		//echo $sql;

		$result = sqlsrv_query($wvcs_dbcon, $sql);
		$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		$emp_seq = $row['emp_seq'];
		$emp_name = aes_256_dec($row['emp_name']);
		$emp_no = $row['emp_no'];
		$emp_pwd = $row['emp_pwd'];
		$org_id = $row['org_id'];
		$work_yn = $row['work_yn'];
		$dept_seq = $row['dept_seq'];
		$rnum = $row['rnum'];
		
		if($_encryption_kind=="1"){

			$email = $row['email_decript'];
			$phone_no = $row['phone_no_decript'];
			
		}else if($_encryption_kind=="2"){

			$email = $row['email'] ? aes_256_dec($row['email']) : "";
			$phone_no = $row['phone_no'] ? aes_256_dec($row['phone_no']) : "";
		}

		$jpos_code = $row['jpos_code'];
		$jduty_code = $row['jduty_code'];
		$jgrade_code = $row['jgrade_code'];

		$use_lang = $row['use_lang'];
		$admin_level = $row['admin_level'];
		
		$title_word = $_LANG_TEXT["managetext"][$lang_code];
		$title_pw_word = $_LANG_TEXT["resettext"][$lang_code];

		$qry_params = array("emp_seq"=>$emp_seq);
		$qry_label = QRY_COMMON_ADMIN_MENU;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);
		
		while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
			$emp_menu[] = $row['menu_code'];
		}
		
		/*관리기관*/
		$qry_params = array("emp_seq"=>$emp_seq);
		$qry_label = QRY_ADMIN_MNG_ORG;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);
		
		while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
			$emp_mng_org[] = $row['org_id'];
		}

		/*관리스캔센터*/
		$qry_params = array("emp_seq"=>$emp_seq);
		$qry_label = QRY_ADMIN_MNG_SCAN_CENTER;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);
		
		while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
			$emp_mng_scan_center[] = $row['scan_center_code'];
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

if($use_lang=="") $use_lang = $lang_code;

if($use_lang =="KR") {
	$checkLangKR = "checked";
}else if($use_lang =="EN") {
	$checkLangEN = "checked";
}else if($use_lang =="JP") {
	$checkLangJP = "checked";
}else if($use_lang =="CN"){
	$checkLangCN = "checked";
}

//**화면열람로그 기록
if($emp_seq != ""){
	$page_title = "[{$emp_name}] ".$_LANG_TEXT["m_manage_employee"][$lang_code];
	$work_log_seq = WriteAdminActLog($page_title,'VIEW');
}else{
	$page_title = $_LANG_TEXT["m_manage_employee"][$lang_code];
}
?>
<script type="text/javascript">
	
var _menuAuth = {
<?php

	foreach($_CODE['admin_menu_auth'] as $key => $value){
			echo $key.":'".$value."',";
	}
?> };
	
$(document).ready(function(){

	return UserSubOrgSet();
});
</script>
<?
include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";
?>
<!--
<script>
	$("div[id!='user_input']").html("");
	document.body.style.zoom="80%"
</script>
-->
<div id="oper_input">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$page_title;?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		<div class="page_right"><span style='cursor:pointer' onclick="history.back();"><?=$_LANG_TEXT["btngobeforepage"][$lang_code];?></span></div>

		<!--등록폼-->
		<form name="UserFrm" id="UserFrm" method="POST">
		<input type="hidden" name="proc" id="proc" >
		<input type="hidden" name="emp_seq" id="emp_seq" value="<?php echo $emp_seq; ?>">
		<input type="hidden" name="src" id="src" value="user_list">
		<div class="tit">
			<?=$_LANG_TEXT["basicinfotext"][$lang_code];?>
		</div>
		<table class="view">
		<tr>
			<th style='width:150px;'><?=$_LANG_TEXT["empnametext"][$lang_code];?></th>
			<td style='width:300px;'><input type="text" name="emp_name" id="emp_name" class="frm_input"  value="<?php echo $emp_name; ?>" style="width:280px" maxlength="30"></td>
			<th style='width:150px;' class="line"><?=$_LANG_TEXT["emailtext"][$lang_code];?></th>
			<td style='min-width:300px;'><input type="text" name="email" id="email" class="frm_input" value="<?php echo $email; ?>"  style="width:280px" maxlength="125"></td>
		</tr>
		<tr class="bg">
			<th><?=$_LANG_TEXT["empnotext"][$lang_code];?></th>
			<td><input type="text" name="emp_no" id="emp_no" class="frm_input" value="<?php echo $emp_no; ?>"  <?=($emp_seq==""? "" : "disabled")?> style="width:280px" maxlength="30"></td>
			<th class="line"><?=$_LANG_TEXT["contactphonetext"][$lang_code];?></th>
			<td><input type="text" name="phone_no" id="phone_no" class="frm_input" value="<?php echo $phone_no; ?>"  style="width:280px" maxlength="30" onkeyup="return onlyNumber(this);"></td>
		</tr>
		<tr>
			<th><?=$_LANG_TEXT["passwordtext"][$lang_code];?> <?=$title_pw_word?></th>
			<td><input type="password" name="emp_pwd" id="emp_pwd" class="frm_input" style="width:280px" value="<?if($emp_seq=="") echo $_initialpassword;?>"   maxlength="30"></td>
			<th class="line"><?=$_LANG_TEXT["passwordconfirmtext"][$lang_code];?></th>
			<td><input type="password" name="emp_pwd_confirm" id="emp_pwd_confirm" class="frm_input" style="width:280px" value="<?if($emp_seq=="") echo $_initialpassword;?>"   maxlength="30"></td>
		</tr>
		</table>
		
		<?if($emp_seq==""){?>
			<div class="guide_message">>> <?=str_replace("****",$_initialpassword,$_LANG_TEXT["initialpasswordguidetext"][$lang_code]);?></div>
		<?}?>
		
		<div class="tit" style="margin-top:30px">
			<?=$_LANG_TEXT["detailinfotext"][$lang_code];?>
		</div>
		<table class="view">
		<tr>
			<th  style='width:150px;'><?=$_LANG_TEXT["workplacetext"][$lang_code];?></th>
			<td  style='width:300px;'>
				<select name="org_id" id="org_id" onchange="UserSubOrgSet()" style="width:200px">
					<option value=''><?=$_LANG_TEXT['choosetext'][$lang_code]?></option>
<?php
					$qry_params = array();
					$qry_label = QRY_COMMON_ORG;
					$sql = query($qry_label,$qry_params);

					$result = sqlsrv_query($wvcs_dbcon, $sql);
		
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
?>
				</select>
			</td>
			<th  style='width:150px;' class="line"><?=$_LANG_TEXT["depttext"][$lang_code];?></th>
			<td  style='min-width:300px;'>
				<select name="dept_seq" id="dept_seq" style="width:200px">
					<option value='' org='' ><?=$_LANG_TEXT['choosetext'][$lang_code]?></option>
<?php
					$qry_params = array();
					$qry_label = QRY_COMMON_DEPT;
					$sql = query($qry_label,$qry_params);

					$result = sqlsrv_query($wvcs_dbcon, $sql);
		
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
?>
				</select>
			</td>
		</tr>
		<tr class="bg">
			<th><?=$_LANG_TEXT["jobgradetext"][$lang_code];?></th>
			<td>
				<select name="jgrade_code" id="jgrade_code" style="width:200px">
					<option value=''><?=$_LANG_TEXT['choosetext'][$lang_code]?></option>
<?php
				$qry_params = array();
				$qry_label = QRY_COMMON_JOBGRADE;
				$sql = query($qry_label,$qry_params);

				$result = sqlsrv_query($wvcs_dbcon, $sql);
	
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
?>
				</select>			
			</td>
			<th class="line"><?=$_LANG_TEXT["jobdutytext"][$lang_code];?></th>
			<td>
				<select name="jduty_code" id="jduty_code" style="width:200px">
					<option value=''><?=$_LANG_TEXT['choosetext'][$lang_code]?></option>
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
		<tr>
			<th><?=$_LANG_TEXT["jobpostext"][$lang_code];?></th>
			<td>
				<select name="jpos_code" id="jpos_code" style="width:200px">
					<option value=''><?=$_LANG_TEXT['choosetext'][$lang_code]?></option>
<?php
					$qry_params = array();
					$qry_label = QRY_COMMON_JOBPOS;
					$sql = query($qry_label,$qry_params);

					$result = sqlsrv_query($wvcs_dbcon, $sql);
		
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
?>
					</select>			
			</td>
			<th class="line"><?=$_LANG_TEXT["uselangtext"][$lang_code];?></th>
			<td>
				<div class="radio">
					<input type="radio" name="rdoLang" id="korLang" value="KR" <?=$checkLangKR;?>><label for="korLang"><?=$_LANG_TEXT["koreantext"][$lang_code];?></label>
					<input type="radio" name="rdoLang" id="engLang" value="EN" <?=$checkLangEN;?>><label for="engLang"><?=$_LANG_TEXT["englishtext"][$lang_code];?></label>
					<input type="radio" name="rdoLang" id="cnLang" value="CN" <?=$checkLangCN;?>><label for="cnLang"> <?=$_LANG_TEXT["chinesetext"][$lang_code];?></label>
				</div>
			</td>
		</tr>
		<tr class="bg">
			<th><?=$_LANG_TEXT["workyntext"][$lang_code];?></th>
			<td>
				<select name="work_yn" id="work_yn" style="width:200px">
					<option value="Y" <?php if($work_yn=="Y") echo "selected"; ?>><?=$_LANG_TEXT["workyestext"][$lang_code];?></option>
					<option value="N" <?php if($work_yn=="N") echo "selected"; ?>><?=$_LANG_TEXT["worknotext"][$lang_code];?></option>
				</select>
			</td>
			<th class="line"><?=$_LANG_TEXT["userleveltext"][$lang_code];?></th>
			<td colspan='3'>
				<select name='admin_level' id='admin_level' style="width:200px" onchange="SetAdminAuth();";>
					<option value=''><?=$_LANG_TEXT['generalusertext'][$lang_code]?></option>
<?
					$option = $_CODE['admin_level'];
					foreach($option as $value => $name){

					//카카오뱅크 매니저 등급은 카카오뱅크 임직원 정보 동기화 배치로 등록한다. 
					if(COMPANY_CODE=="600" && substr($value,0,7)=="MANAGER") continue;	
						
						echo "<option value='$value' ".($admin_level==$value? "selected=true" : "").">$name</option>";
					}
?>	
			</td>
		</tr>
		</table>
		
		<div id="admin_auth_tab" style='display:<?=($admin_level=="" ? "none" : "inline") ?>'>
			<div class="tit" style="margin-top:30px">
				<?=$_LANG_TEXT["adminauthtext"][$lang_code];?>
			</div>
			<table class="view">
			<tr>
				<th  style='width:150px;'><?=$_LANG_TEXT["menuauthtext"][$lang_code];?></th>
				<td colspan="3">
					<div class="radio">
	<?php
					$qry_params = array();
					$qry_label = QRY_COMMON_MENU;
					$sql = query($qry_label,$qry_params);

					$result = sqlsrv_query($wvcs_dbcon, $sql);
					
					while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

						$menu_code = $row['menu_code'];
						$menu_name = $_CODE['menu'][$menu_code]; //$row['menu_name'];

						if(is_array($emp_menu)){
							$checked = in_array($menu_code,$emp_menu)? "checked=true" : "";
						}else $checked = "";

						if(stripos($_CODE['admin_menu_auth'][$admin_level],$menu_code)===false){
							$disabled = "disabled";
						}else $disabled = "";
	?>
						<input type='checkbox' name='menu[]' id='menu_<?=$menu_code?>' value='<?=$menu_code?>' <?=$checked?> <?=$disabled?>><label for='menu_<?=$menu_code?>'><?=$menu_name?></label>
	<?php
					}
	?>
					</div>
				</td>
			</tr>
			<tr>
				<th  style='width:150px;'><?=$_LANG_TEXT["manageorgantext"][$lang_code];?></th>
				<td colspan='3'>
					<div id='admin_mng_org'>
						<?
						if($admin_level=='SUPER'){ 
							echo "<span name='all'>".$_LANG_TEXT["alltext"][$lang_code]."</span>";
						 }
						;?>
						<div class="radio" style="display:<?=$admin_level=='SUPER'? 'none' : 'inline';?>">
			<?php
							$qry_params = array();
							$qry_label = QRY_COMMON_ORG_USE_ALL;
							$sql = query($qry_label,$qry_params);

							$result = sqlsrv_query($wvcs_dbcon, $sql);
							
							while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

								$mng_org_id = $row['org_id'];
								$mng_org_name = $row['org_name'];

								if(is_array($emp_mng_org)){
									$checked = in_array($mng_org_id,$emp_mng_org)? "checked=true" : "";
								}else $checked = "";
			?>
								<input type='checkbox' name='mng_org[]' id='mng_org_<?=$mng_org_id?>' value='<?=$mng_org_id?>' <?=$checked?>><label for='mng_org_<?=$mng_org_id?>'><?=$mng_org_name?></label>
			<?php
							}
			?>
							</div>
						</div>
				</td>
			</tr>

			<tr>
				<th  style='width:150px;'><?=$_LANG_TEXT["managescancentertext"][$lang_code];?></th>
				<td colspan='3'>
					<div id='admin_mng_scan_center'>
						<?
						if($admin_level=='SUPER'){ 
							echo "<span name='all'>".$_LANG_TEXT["alltext"][$lang_code]."</span>";
						 }
						;?>
						<div class="radio" style="display:<?=$admin_level=='SUPER'? 'none' : 'inline';?>">
			<?php
							$qry_params = array();
							$qry_label = QRY_COMMON_SCAN_CENTER_USE_ALL;
							$sql = query($qry_label,$qry_params);

							$result = sqlsrv_query($wvcs_dbcon, $sql);
							
							while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

								$scan_center_code = $row['scan_center_code'];
								$scan_center_name = $row['org_name']." ".$row['scan_center_name'];

								if(is_array($emp_mng_scan_center)){
									$checked = in_array($scan_center_code,$emp_mng_scan_center)? "checked=true" : "";
								}else $checked = "";
			?>
								<input type='checkbox' name='mng_scan_center[]' id='mng_scan_center_<?=$scan_center_code?>' value='<?=$scan_center_code?>' <?=$checked?>><label for='mng_scan_center_<?=$scan_center_code?>'><?=$scan_center_name?></label>
			<?php
							}
			?>
							</div>
						</div>
				</td>
			</tr>
			
			</table>
		</div>

		<div class="btn_wrap">
<?php
		if ($emp_seq != "") {
?>
			<div class="left">
				<a href="<?if(empty($prev_emp_seq)){?>javASCript:alert(nodatatext[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."?enc=".ParamEnCoding("emp_seq=".$prev_emp_seq.($param ? "&" : "").$param); }?>"  class="btn" id='btnPrev'><?=$_LANG_TEXT["btnprev"][$lang_code];?></a>
				<a href="<?if(empty($next_emp_seq)){?>javASCript:alert(nodatatext[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."?enc=".ParamEnCoding("emp_seq=".$next_emp_seq.($param ? "&" : "").$param); }?>"  class="btn" id='btnNext'><?=$_LANG_TEXT["btnnext"][$lang_code];?><a>
			</div>
<?php	}?>
			<div class="right">
					<a href="./m_user_list.php" class="btn" id="btnList"><?=$_LANG_TEXT["btnlist"][$lang_code];?></a>
<?php
					if ($emp_seq == "") {
?>
							<a href="javascript:"   onclick="EmpinfoSubmit('CREATE')" class="btn required-create-auth hide"><?=$_LANG_TEXT["btnsave"][$lang_code];?></a>
<?php
					}else{
?>	
							<a href="javascript:"   onclick="EmpinfoSubmit('UPDATE')" class="btn required-update-auth hide"><?=$_LANG_TEXT["btnsave"][$lang_code];?></a>
							<a href="javascript:"   onclick="EmpinfoSubmit('DELETE')" class="btn required-delete-auth hide"><?=$_LANG_TEXT["btndelete"][$lang_code];?></a>
							

<?php
					}
?>
					<a href="./m_user_reg.php" class="btn"  id='btnClear'><?=$_LANG_TEXT["btnclear"][$lang_code];?></a>

			</div>
		</div>

		</form>

		

	</div>

</div>

<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>