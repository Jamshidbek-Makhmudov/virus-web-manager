<?php
$page_name = "com_info";
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

$v_com_seq = $_REQUEST['v_com_seq'];

if($v_com_seq <> "") {

		$qry_params = array("com_seq"=> $v_com_seq);
		$qry_label = QRY_USER_COM_INFO;
		$sql = query($qry_label,$qry_params);

		//echo nl2br($sql);

		$result = sqlsrv_query($wvcs_dbcon, $sql);

		if($result){

			$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
			$v_com_name = $row['v_com_name'];
			$use_yn = $row['use_yn'];
			$v_ceo_name = $row['v_ceo_name'];
			$v_com_code1 = $row['v_com_code_1'];
			$v_com_code2 = $row['v_com_code_2'];
			$v_com_code3 = $row['v_com_code_3'];
			$v_com_gubun_1 = $row['v_com_gubun_1'];
			$v_com_gubun_2 = $row['v_com_gubun_2'];

		}

}

?>
<div id="user_input">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_com_info.edit"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		<div class="page_right"><span style='cursor:pointer' onclick="history.back();"><?=$_LANG_TEXT["btngobeforepage"][$lang_code];?></span></div>
		<!--등록폼-->
		<form name="frmCom" id="frmCom" method="POST">
		<input type="hidden" name="com_seq" id="com_seq" value="<?php echo $v_com_seq; ?>">
		<table class="view">
			
			<tr>
				<th style='width:150px'><?=$_LANG_TEXT["usercompanynametext"][$lang_code];?></th>
				<td style='width:350px'>
					<input type="text" name="com_name" id="com_name" class="frm_input"  value="<?php echo $v_com_name; ?>" style="width:50%" maxlength="50">
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
				<td><input type="text" name="ceo_name" id="ceo_name" class="frm_input"  value="<?php echo $v_ceo_name; ?>" style="width:90%"  maxlength="50"></td>
				<th class="line"><?=$_LANG_TEXT["companyregistrationnumbertext"][$lang_code];?></th>
				<td>
					<input type="text" name="com_code1" id="com_code1" class="frm_input"  value="<?php echo $v_com_code1; ?>" style="width:80px" onkeyup="return onlyNumber(this);"  maxlength="10"> - 
					<input type="text" name="com_code2" id="com_code2" class="frm_input"  value="<?php echo $v_com_code2; ?>" style="width:80px" onkeyup="return onlyNumber(this);"  maxlength="10"> - 
					<input type="text" name="com_code3" id="com_code3" class="frm_input"  value="<?php echo $v_com_code3; ?>" style="width:80px" onkeyup="return onlyNumber(this);"  maxlength="10">
				</td>
			</tr>
			<tr>
				<th><?=$_LANG_TEXT["companyindustrytext"][$lang_code];?></th>
				<td><input type="text" name="com_gubun1" id="com_gubun1" class="frm_input"  value="<?php echo $v_com_gubun_1; ?>" style="width:90%"  maxlength="50"></td>
				<th class="line"><?=$_LANG_TEXT["companycategorytext"][$lang_code];?></th>
				<td>
					<input type="text" name="com_gubun2" id="com_gubun2" class="frm_input"  value="<?php echo $v_com_gubun_2; ?>" style="max-width:350px"  maxlength="50">
				</td>
			</tr>
		</table>
		<div class="btn_wrap">
			<div class="right">
					<a href="#"   onclick="ComInfoSubmit()" class="btn"><?=$_LANG_TEXT["btnsave"][$lang_code];?></a>
			</div>
		</div>
		
		</form>

	</div>

</div>

<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>