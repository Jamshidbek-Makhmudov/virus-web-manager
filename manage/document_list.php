<?php
$page_name = "document_list";
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

?>
<script>
	$("document").ready(function () {
		getDocumentContent();
	});
</script>
<div id="oper_input">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				<h1><span id='page_title'><?=$_LANG_TEXT["managedocument"][$lang_code];?> <small><? echo trsLang('외부출입지원 체크리스트','idcmanagerreporttext');?></small></span></h1>
			</div>
			<span class="line"></span>
		</div>

		<ul class="tab">
			<li class="clsid_mgr_idc_report on" onclick="setDocumentConfig('MGR_IDC_REPORT')"><? echo trsLang('외부출입지원 체크리스트','idcmanagerreporttext');?></li>
		</ul>

		<form id='frmDocument' name='frmDocument' method='post' action=''>
			<input type='hidden' name='proc' id='proc'>
			<input type='hidden' name='proc_name' id='proc_name'>
			<input type='hidden' name='form_seq' id='form_seq'>
			<input type='hidden' name='form_div' id='form_div' value='MGR_IDC_REPORT'>
			<input type='hidden' name='form_lang' id='form_lang' value='KR'>
			<input type='hidden' name='form_title_enc' id='form_title_enc' value=''>
			<input type='hidden' name='form_content_enc' id='form_content_enc' value=''>
			<table class="view">
				<tr>
					<th><? echo trsLang('문서제목','idcreporttitle');?></th>
					<td>
						<input type="text" class="frm_input" style="width:50%" id='form_title' name='form_title'maxlength="50">
					</td>
				</tr>
				<tr class="bg">
					<th><? echo trsLang('사용여부','useyesnonntext');?></th>
					<td>
						<input type='radio' id='use_yn1' name='use_yn' value='Y' checked> <label style='vertical-align:baseline' for='use_yn1'><? echo trsLang('사용함','useyesnntext');?></label>
						<input type='radio' id='use_yn2' name='use_yn' value='N'> <label style='vertical-align:baseline' for='use_yn2'><? echo trsLang('사용안함','usenonntext');?></label>
					</td>
				</tr>
			</table>
		</form>

		<div class='report_checklist'>
			<div class="sub_tit" style='line-height:30px;'> ><? echo trsLang('업무구분','taskdiv');?> <? echo trsLang('설정','settingtext'); ?></div>
			<div id='wrapper2' class="wrapper">
				<table class="list" id='tblDocumentTasks' style="min-width:1400px;">
					<tr style='padding:5px;font-weight:bold'>
						<th style='width:720px;'><?=$_LANG_TEXT['taskdiv'][$lang_code]?></th>
						<th style='min-width:80px;text-align:left'><? echo trsLang('추가/삭제','addnremove');?></th>
					</tr>
					<tr class='report_item' style='padding:5px;'>
						<td>
							<input type='text' class='frm_input check_valid_data report_input_item' name='report_input_item[]' placeholder="<? echo trsLang('점검 항목을 입력하세요','inputcheckitem'); ?>" style='width:700px;' value='' />
						</td>
						<td style='text-align:left'>
							<a href="javascript:void(0)" class='btn20 gray' style='width:10px' onclick="addendDocumentRow()">+</a>
							<a href="javascript:void(0)" class='btn20 gray' style='width:10px' onclick="removeDocumentRow()">-</a>
						</td>
					</tr>
				</table>
			</div>
		</div>

		<div class='report_section'>
			<div class="sub_tit" style='line-height:30px;'> ><? echo trsLang('작성항목','reportitemtext');?> <? echo trsLang('설정','settingtext'); ?></div>
			<div id='wrapper2' class="wrapper">
				<table class="list" id='tblDocumentItems' style="min-width:1400px;">
					<tr style='padding:5px;font-weight:bold'>
						<th style='width:720px;'><? echo trsLang('작성항목','reportitemtext');?></th>
						<th style='min-width:80px;text-align:left'><? echo trsLang('추가/삭제','addnremove');?></th>
					</tr>
					<tr class='report_item' style='padding:5px;'>
						<td>
							<input type='text' class='frm_input check_valid_data report_input_item' name='report_input_item[]' placeholder="<? echo trsLang('작성 항목을 입력하세요','inputreportitem'); ?>"  style='width:700px;' value='' />
							<input type="hidden" name='report_input_type[]' class='frm_input check_valid_data report_input_type type_default_checklist' value="CHECKLIST"/>
						</td>
						<td style='text-align:left'>
							<a href="javascript:void(0)" class='btn20 gray' style='width:10px' onclick="addendDocumentRow()">+</a>
							<a href="javascript:void(0)" class='btn20 gray' style='width:10px' onclick="removeDocumentRow()">-</a>
						</td>
					</tr>
				</table>
			</div>
		</div>

		<div class="btn_confirm">
			<a href="javascript:void(0)" onclick="saveDocumentContent()" class="btn required-update-auth hide"><?=$_LANG_TEXT['btnsave'][$lang_code]?></a>
		</div>
	</div>
</div>

<?php
	include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";
?>