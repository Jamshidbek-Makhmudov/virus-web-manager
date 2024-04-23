<?php
$page_name = "user_info";
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

$v_user_seq = $_REQUEST['v_user_seq'];


if($v_user_seq <> "") {

		$qry_params = array("v_user_seq"=> $v_user_seq);
		$qry_label = QRY_USER_INFO;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);

		if($result){

			$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
			$user_name = aes_256_dec($row['v_user_name']);
		
			$use_yn = $row['v_use_yn'];
			$user_com_name = $row['v_com_name'];

			if($_encryption_kind=="1"){

				$email = $row['v_email_decript'];
				$phone_no = $row['v_phone_decript'];
				
			}else if($_encryption_kind=="2"){

				$email = aes_256_dec($row['v_email']);
				$phone_no = aes_256_dec($row['v_phone']);
			}
			
			$com_seq = $row['v_com_seq'];
			$ceo_name = $row['v_ceo_name'];
			$com_code1 = $row['v_com_code_1'];
			$com_code2 = $row['v_com_code_2'];
			$com_code3 = $row['v_com_code_3'];
			$com_gubun1 = $row['v_com_gubun_1'];
			$com_gubun2 = $row['v_com_gubun_2'];
			$com_use_yn = $row['com_use_yn'];

		}
		
		$title_pw_word = $_LANG_TEXT["resettext"][$lang_code];

		

}

?>
<div id="user_input">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_visitor_info.edit"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		<div class="page_right"><span style='cursor:pointer' onclick="history.back();"><?=$_LANG_TEXT["btngobeforepage"][$lang_code];?></span></div>
		<!--등록폼-->
		<form name="frmUser" id="frmUser" method="POST">
		<input type="hidden" name="v_user_seq" id="v_user_seq" value="<?php echo $v_user_seq; ?>">
		<div class="tit" style="margin-top:30px">
			<div><?=$_LANG_TEXT["visitorinfotext"][$lang_code];?></div>
		</div>
		<table class="view">
			<tr>
				<th style='width:150px'><?=$_LANG_TEXT["visitortext"][$lang_code];?></th>
				<td style='width:350px'><input type="text" name="user_name" id="user_name" class="frm_input"  value="<?php echo $user_name; ?>" style="width:90%" maxlength="30"></td>
				<th class="line" style='width:150px'><?=$_LANG_TEXT["approvedyesnotext"][$lang_code];?></th>
				<td >
					<select name="user_use_yn" id="user_use_yn">
						<option value="Y" <?if($use_yn=="Y"){echo "selected";}?>><?=$_LANG_TEXT["approvedtext"][$lang_code];?></option>
						<option value="N" <?if($use_yn=="N"){echo "selected";}?>><?=$_LANG_TEXT["unapprovedtext"][$lang_code];?></option>
					</select>
				</td>
			</tr>
			<tr class="bg">
				<th><?=$_LANG_TEXT["emailtext"][$lang_code];?></th>
				<td><input type="text" name="user_email" id="user_email" class="frm_input"  value="<?php echo $email; ?>" style="width:90%" maxlength="120"></td>
				<th class="line"><?=$_LANG_TEXT["contactphonetext"][$lang_code];?></th>
				<td><input type="text" name="user_phone" id="user_phone" class="frm_input"  value="<?php echo $phone_no; ?>" style="max-width:350px" maxlength="30" onkeyup="return onlyNumber(this);"></td>
			</tr>
			<tr>
				<th><?=$_LANG_TEXT["usercompanytext"][$lang_code];?></th>
				<td colspan="3">
					<select name="sel_user_com" id="sel_user_com" onchange="changeUserCompany()">
						<option value=""><?=$_LANG_TEXT["usercompanychoosetext"][$lang_code];?></option>
						<?
						//업체정보
						$qry_params = array();
						$qry_label = QRY_USER_COM_LIST;
						$sql = query($qry_label,$qry_params);

						$result = sqlsrv_query($wvcs_dbcon, $sql);

						if($result){
							while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
								
								echo "<option value='".$row['v_com_seq']."' ".($com_seq==$row['v_com_seq'] ? "selected" : "").">".$row['v_com_name']."</option>";
							}
						}
						?>
					</select> 
					<button type="button" class='btn'  onclick="ResetUserCompany()"><?=$_LANG_TEXT["btnadd"][$lang_code];?></button>
				</td>
			</tr>
		</table>
		
		
		<div class="tit" style="margin-top:30px">
			<div><?=$_LANG_TEXT["usercompanyinfotext"][$lang_code];?></div>
		</div>
		<table class="view">
			<input type="hidden" name="com_seq" id="com_seq" value="<?php echo $com_seq; ?>">
			<tr>
				<th style='width:150px'><?=$_LANG_TEXT["usercompanynametext"][$lang_code];?></th>
				<td style='width:350px'>
					<input type="text" name="com_name" id="com_name" class="frm_input"  value="<?php echo $user_com_name; ?>" style="width:50%" maxlength="50">
				</td>
				<th class="line" style='width:150px'><?=$_LANG_TEXT["approvedyesnotext"][$lang_code];?></th>
				<td >
					<select name="com_use_yn" id="com_use_yn">
						<option value="Y" <?if($use_yn=="Y"){echo "selected";}?>><?=$_LANG_TEXT["approvedtext"][$lang_code];?></option>
						<option value="N" <?if($use_yn=="N"){echo "selected";}?>><?=$_LANG_TEXT["unapprovedtext"][$lang_code];?></option>
					</select>
				</td>
			</tr>
			<tr class="bg">
				<th><?=$_LANG_TEXT["ceotext"][$lang_code];?></th>
				<td><input type="text" name="ceo_name" id="ceo_name" class="frm_input"  value="<?php echo $ceo_name; ?>" style="width:90%" maxlength="50"  ></td>
				<th class="line"><?=$_LANG_TEXT["companyregistrationnumbertext"][$lang_code];?></th>
				<td>
					<input type="text" name="com_code1" id="com_code1" class="frm_input"  value="<?php echo $com_code1; ?>" style="width:80px" onkeyup="return onlyNumber(this);"  maxlength="10"> - 
					<input type="text" name="com_code2" id="com_code2" class="frm_input"  value="<?php echo $com_code2; ?>" style="width:80px" onkeyup="return onlyNumber(this);"  maxlength="10"> - 
					<input type="text" name="com_code3" id="com_code3" class="frm_input"  value="<?php echo $com_code3; ?>" style="width:80px" onkeyup="return onlyNumber(this);"  maxlength="10">
				</td>
			</tr>
			<tr>
				<th><?=$_LANG_TEXT["companyindustrytext"][$lang_code];?></th>
				<td><input type="text" name="com_gubun1" id="com_gubun1" class="frm_input"  value="<?php echo $com_gubun1; ?>" style="width:90%"  maxlength="50"></td>
				<th class="line"><?=$_LANG_TEXT["companycategorytext"][$lang_code];?></th>
				<td>
					<input type="text" name="com_gubun2" id="com_gubun2" class="frm_input"  value="<?php echo $com_gubun2; ?>" style="max-width:350px"  maxlength="50">
				</td>
			</tr>
		</table>
		<div class="btn_wrap">
			<div class="right">
					<a href="#"   onclick="UserInfoSubmit()" class="btn"><?=$_LANG_TEXT["btnsave"][$lang_code];?></a>
			</div>
		</div>
		
		</form>

	</div>

</div>

<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>