<?php
$page_name = "my_info";
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

$emp_seq = $_ck_user_seq;

if($emp_seq <> "") {

		$qry_params = array("emp_seq"=> $emp_seq);
		$qry_label = QRY_MY_INFO;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);

		if($result){

			$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
			$emp_seq = $row['emp_seq'];
			$emp_name = aes_256_dec($row['emp_name']);
			$emp_no = $row['emp_no'];
			$emp_pwd = $row['emp_pwd'];
			$org_id = $row['org_id'];
			$work_yn = $row['work_yn'];
			$dept_seq = $row['dept_seq'];
			$organ_name = $row['org_name'];
			$dept_name = $row['dept_name'];

			if($_encryption_kind=="1"){

				$email = $row['email_decript'];
				$phone_no = $row['phone_no_decript'];
				
			}else if($_encryption_kind=="2"){

				$email = $email ? aes_256_dec($row['email']) : "";
				$phone_no = $phone_no ? aes_256_dec($row['phone_no']) : "";
			}

			$jpos_code = $row['jpos_code'];
			$jduty_code = $row['jduty_code'];
			$jgrade_code = $row['jgrade_code'];
			$jgrade_name = $row['jgrade_name'];
			$jduty_name = $row['jduty_name'];
			$jpos_name = $row['jpos_name'];
			$sgrade_name = $row['sgrade_name'];
			$sgrade_grd = $row['sgrade_grd'];

			$use_lang = $row['use_lang'];
			$sgrade_code = $row['sgrade_code'];
			$admin_level = $row['admin_level'];

		}
		
		$title_pw_word = $_LANG_TEXT["resettext"][$lang_code];

		

}

if($use_lang=="") $use_lang = $lang_code;


?>
<div id="user_input">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_my_info"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>

		<!--등록폼-->
		<form name="UserFrm" id="UserFrm" method="POST">
		<input type="hidden" name="emp_seq" id="emp_seq" value="<?php echo $emp_seq; ?>">
		<div class="tit">
			<?=$_LANG_TEXT["basicinfotext"][$lang_code];?>
		</div>
		<table class="view">
		<tr>
			<th style='min-width:150px;'><?=$_LANG_TEXT["empnametext"][$lang_code];?></th>
			<td style='width:300px;'><input type="text" name="emp_name" id="emp_name" class="frm_input"  value="<?php echo $emp_name; ?>" style="width:250px;" maxlength="30" disabled  maxlength="50"></td>
			<th  style='min-width:150px;' class="line"><?=$_LANG_TEXT["emailtext"][$lang_code];?></th>
			<td><input type="text" name="email" id="email" class="frm_input" value="<?php echo $email; ?>"  style="width:250px;" maxlength="125"></td>
		</tr>
		<tr class="bg">
			<th><?=$_LANG_TEXT["empnotext"][$lang_code];?></th>
			<td><input type="text" name="emp_no" id="emp_no" class="frm_input" value="<?php echo $emp_no; ?>" disabled style="width:250px;" maxlength="30"></td>
			<th class="line"><?=$_LANG_TEXT["contactphonetext"][$lang_code];?></th>
			<td><input type="text" name="phone_no" id="phone_no" class="frm_input" value="<?php echo $phone_no; ?>"  style="width:250px;" maxlength="30" onkeyup="return onlyNumber(this);"></td>
		</tr>
		<tr>
			<th><?=$_LANG_TEXT["passwordtext"][$lang_code];?> <?=$title_pw_word?></th>
			<td>
				<input type="password" name="emp_pwd" id="emp_pwd" class="frm_input" style="width:250px;"  maxlength="30">
				<i class="fa fa-info-circle" onmouseover="viewlayer(true, 'moverlayerNameAdminPwd');" onmouseout="viewlayer(false, 'moverlayerNameAdminPwd');" ></i>
				<div id="moverlayerNameAdminPwd" class="viewlayer" style='color:#fff;'>
					<? echo trsLang('비밀번호는 대문자, 소문자, 숫자, 특수문자를 3개 이상 포함해서 9자 이상이어야 합니다.','adminpwdregxtext');?>
				</div>
			</td>
			<th class="line"><?=$_LANG_TEXT["passwordconfirmtext"][$lang_code];?></th>
			<td><input type="password" name="emp_pwd_confirm" id="emp_pwd_confirm" class="frm_input" style="width:250px;"  maxlength="30"></td>
		</tr>
		</table>
		<div class="btn_wrap">
			<div class="right">
					<a href="#"   onclick="MyinfoSubmit()" class="btn"><?=$_LANG_TEXT["btnsave"][$lang_code];?></a>
			</div>
		</div>
		
		<div class="tit" style="margin-top:30px">
			<?=$_LANG_TEXT["detailinfotext"][$lang_code];?>
		</div>

		<table class="view">
		<tr>
			<th style='min-width:150px'><?=$_LANG_TEXT["workplacetext"][$lang_code];?></th>
			<td style='width:300px'>
				<?=$organ_name?>
			</td>
			<th  style='min-width:150px' class="line"><?=$_LANG_TEXT["depttext"][$lang_code];?></th>
			<td>
				<?=$dept_name? $dept_name : "-";?>
			</td>
		</tr>
		<tr class="bg display-none">
			<th><?=$_LANG_TEXT["jobgradetext"][$lang_code];?></th>
			<td>
				<?=$jgrade_name? $jgrade_name : "-";?>
			</td>
			<th class="line"><?=$_LANG_TEXT["jobdutytext"][$lang_code];?></th>
			<td>
				<?=$jduty_name? $jduty_name : "-";?>		
			</td>
		</tr>
		<tr class="bg">
			
			<th><?=$_LANG_TEXT["userleveltext"][$lang_code];?></th>
			<td>
				<?=$admin_level? $_CODE['admin_level'][$admin_level] : $_LANG_TEXT["generalvisitortext"][$lang_code];?>	
			</td>
			
			<th  class="line"><?=$_LANG_TEXT["workyntext"][$lang_code];?></th>
			<td>
				<?
					 if($work_yn=="Y") echo $_LANG_TEXT["workyestext"][$lang_code];
					 else $_LANG_TEXT["worknotext"][$lang_code];
				?>
			</td>
			
		</tr>
		<tr class='display-none'>
			<th><?=$_LANG_TEXT["uselangtext"][$lang_code];?></th>
			<td>
				<?
					if($use_lang=="KR"){
						echo $_LANG_TEXT["koreantext"][$lang_code];
					}else if($use_lang=="EN"){
						echo $_LANG_TEXT["englishtext"][$lang_code];
					}else if($use_lang=="JP"){
						echo $_LANG_TEXT["japanesetext"][$lang_code];
					}
				?>
			</td>
			<th class="line"><?=$_LANG_TEXT["securityleveltext"][$lang_code];?></th>
			<td><?=$sgrade_grd."-".$sgrade_name;?>		
			</td>
		</tr>
		</table>
		
		</form>

		

	</div>

</div>

<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>