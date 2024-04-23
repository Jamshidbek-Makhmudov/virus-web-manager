<?php
$page_name = "agree_list";
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

$Model_manage = new Model_manage();
?>
<script>
	$("document").ready(function(){
		getAgreeContent();
	});
</script>
<div id="oper_input">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["information_consent_manag"][$lang_code];?> <small><? echo trsLang('출입신청','application_entry_text');?></small></span> </h1>
			</div>
			<span class="line"></span>
		</div>

		<ul class="tab">
			<li class="clsid_visit on" onclick="setAgreeConfig('VISIT')"><? echo trsLang('출입신청','application_entry_text');?></li>
			<li class="clsid_visit_idc" onclick="setAgreeConfig('VISIT_IDC')">IDC <? echo trsLang('출입신청','application_entry_text');?></li>
			<li class="clsid_security content_replace" onclick="setAgreeConfig('SECURITY')"><? echo trsLang('정보보호서약서','informationprotectionpledge');?></li>
			<li class="clsid_rent" onclick="setAgreeConfig('RENT')"><? echo trsLang('물품대여','rental_goods');?></li>
			<li class="clsid_parking" onclick="setAgreeConfig('PARKING')"><? echo trsLang('주차권지급','parking_ticket_payment');?></li>
			<li class="clsid_train" onclick="setAgreeConfig('TRAIN')"><? echo trsLang('외부인력 정보교육','External_training');?></li>
		</ul>		
		
		<form id='frmAgree' name='frmAgree' method='post' action=''>
			<input type='hidden' name='proc_name' id='proc_name'>
			<input type='hidden' name='proc' id='proc'>
			<input type='hidden' name='agree_config_seq' id='agree_config_seq' >
			<input type='hidden' name='agree_div' id='agree_div' value='VISIT'>
			<input type='hidden' name='agree_lang' id='agree_lang' value='KR'>
			<input type='hidden' name='agree_title_enc' id='agree_title_enc' value=''>
			<input type='hidden' name='agree_content_enc' id='agree_content_enc' value=''>
			<input type='hidden' name='agree_bottom_enc' id='agree_bottom_enc' value=''>
			<table class="view">
				<tr id='row_rent_item' style='display:none'>
					<th><? echo trsLang('물품대여항목','rentingitems');?></th>
					<td>
						<?
						$args = array("code_key"=>"RENT_ITEM","use_yn"=>"Y","search_sql"=>" and depth > 1");
						$result = $Model_manage->getCodeList($args);
						?>
						<select name='rent_item' id='rent_item' onchange="setAgreeConfig(this.value)">
							<option value='RENT'><? echo trsLang('전체','alltext');?></option>
							<?
								 while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
									$code_name = $row['code_name'];
							?>
								<option value='RENT_<? echo $code_name?>'><? echo $code_name?></option>
							<?	
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<th><? echo trsLang('동의및안내제목','agreeTitle');?></th>
					<td>
						<input type="text" class="frm_input" style="width:50%" id='agree_title' name='agree_title' maxlength="50">
						<input type='checkbox' id='request_consent_yn' name='request_consent_yn' value='Y'> <label style='vertical-align:baseline' for='request_consent_yn'><? echo trsLang('동의필수','agreeNeed');?></label>
					</td>
				</tr>
				<tr class='row_content_replace' style='display:none'>
					<th><? echo trsLang('내용치환단어','contentreplaceword');?></th>
					<td>{#VISIT_PURPOSE} : <? echo trsLang('출입신청시입력한방문목적으로치환','visitpurposereplacetext');?> </td>
				</tr>
				<tr>
					<th><? echo trsLang('동의및안내내용','agreeContent');?></th>
					<td>
						<textarea style='height:300px;' id='agree_content' name='agree_content'></textarea>
						<input type='hidden' name='agree_bottom' id='agree_bottom' value=''>
					</td>
				</tr>
				<tr>
					<th><? echo trsLang('사용여부','useyesnonntext');?></th>
					<td>
						<input type='radio' id='use_yn1' name='use_yn' value='Y' checked> <label style='vertical-align:baseline' for='use_yn1'><? echo trsLang('사용함','useyesnntext');?></label>
						<input type='radio' id='use_yn2' name='use_yn' value='N'> <label style='vertical-align:baseline' for='use_yn2'><? echo trsLang('사용안함','usenonntext');?></label>
					</td>
				</tr>
			</table>
		</form>
		
		<div class="btn_confirm">
			<a href="javascript:void(0)" onclick="saveAgreeContent()" class="btn required-update-auth hide"><?=$_LANG_TEXT['btnsave'][$lang_code]?></a>
		</div>

	</div>

</div>
<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>