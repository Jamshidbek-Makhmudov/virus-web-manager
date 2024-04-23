<?php
$page_name = "access_control_idc";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI']) - 1);
$_apos = stripos($_REQUEST_URI, "/");
if ($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
include_once $_server_path . "/" . $_site_path . "/inc/header.inc";
include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";

$v_user_list_seq = $_REQUEST["v_user_list_seq"];
$user_doc_seq    = $_REQUEST["user_doc_seq"];
$tab = $_REQUEST["tab"];

$doc_div  = "MGR_IDC_REPORT";
$Model_User = new Model_User();
$Model_User->SHOW_DEBUG_SQL = false;

if (!empty($v_user_list_seq)) {
	$args = compact("v_user_list_seq");
	$result = $Model_User->getUserVistListDetailsInfo_IDC($args);
	$detail = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

	@extract($detail, EXTR_OVERWRITE);
	
	$v_user_name = aes_256_dec($v_user_name);
	$str_v_user_type = $_CODE_V_USER_TYPE_DETAILS[$v_user_type];

	if (empty($user_doc_seq)) {
		$form_div  = $doc_div;
		$form_lang = $lang_code;

		$args = compact("form_div", "form_lang");
		$document = $Model_User->getDefaultIDCSupportChecklistInfo($args);
		@extract($document, EXTR_OVERWRITE);
		
		$doc_title = $form_title;

		$content = json_decode($form_content);
	} else {
		$args = compact("v_user_list_seq", "user_doc_seq");
		$document = $Model_User->getUserVisitListReport_IDC($args);
		@extract($document, EXTR_OVERWRITE);
		
		$content = json_decode($doc_content);
	}

	$tasks = $content->tasks;
	$lists = $content->lists;
}

$page_title = "[{$v_user_name}] " . $doc_title;

?>
<div id="result_view">
	<div class="container">
		<div id="tit_area">
			<div class="tit_line">
				<h1><span id='page_title'><? echo $page_title; ?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		<div class="page_right">
			<span style='cursor:pointer' onclick="sendPostForm('./access_info_idc.php?enc=<?= ParamEnCoding("v_user_list_seq={$v_user_list_seq}&tab={$tab}"); ?>')"><?= $_LANG_TEXT["btngobeforepage"][$lang_code]; ?></span>
		</div>

		<div>
			<div class="tit" style="margin-top:30px"><? echo trsLang('작업정보', 'workinfotext'); ?></div>
			<div>
				<table class="view" style="table-layout:fixed;">
					<tr style="width: 100%">
						<th style='width:150px'><? echo trsLang('소속구분', 'belongdivtext'); ?></th>
						<td style="width: calc(50% - 152px);"><? echo $str_v_user_type; ?></td>
						<th class="line" style='width:150px'><?= $_LANG_TEXT['belongtext'][$lang_code] ?></th>
						<td style="width: calc(50% - 152px);"><?php echo $v_user_belong; ?></td>
					</tr>
					<tr>
						<th><?= $_LANG_TEXT['nametext'][$lang_code] ?></th>
						<td><?php echo $v_user_name; ?></td>
						<th class="line"><?= $_LANG_TEXT['engnameid'][$lang_code] ?></th>
						<td><?php echo $v_user_name_en; ?></td>
					</tr>
					<tr>
						<th><?= $_LANG_TEXT['center_location'][$lang_code] ?></th>
						<td><?php echo $visit_center_desc; ?></td>
						<th class="line"><?= $_LANG_TEXT['work_detail'][$lang_code] ?></th>
						<td><?php echo $v_purpose; ?></td>
					</tr>
					<tr>
						<th><?= $_LANG_TEXT['worknumbertext'][$lang_code] ?></th>
						<td><?php echo $elec_doc_number; ?></td>
						<th class="line"><?= $_LANG_TEXT['worknumbertext'][$lang_code] ?>(<?= $_LANG_TEXT['certify_text'][$lang_code] ?>)</th>
						<td><?php echo $work_number; ?></td>
					</tr>
				</table>
			</div>
		</div>

		<div>
			<div class="sub_tit" style='line-height:30px;'> ><? echo trsLang('체크리스트','checklist');?></div>
			<div id='wrapper2' class="wrapper">
				<form name='frmChecklist' id='frmChecklist' method='POST'>
					<input type='hidden' id='v_user_list_seq' name='v_user_list_seq' value='<?= $v_user_list_seq ?>'>
					<input type='hidden' id='user_doc_seq' name='user_doc_seq' value='<?= $user_doc_seq ?>'>
					<input type='hidden' id='doc_div' name='doc_div' value='<?= $doc_div ?>'>
					<input type='hidden' id='doc_title' name='doc_title' value='<?= $doc_title ?>'>
					<input type='hidden' id='doc_title_enc' name='doc_title_enc' value=''>
					<input type='hidden' id='doc_content_enc' name='doc_content_enc' value=''>
					<input type='hidden' id='proc_name' name='proc_name'>
					<input type='hidden' id='proc_exec' name='proc_exec'>
					<?php
					foreach ($tasks as $text) {
					?>
					<input type="hidden" class="no_submit doc_task" value="<?=$text?>" />
					<?php
					}
					?>
					<table class="list" style="margin-top:0px;min-width:1000px;">
						<tr>
							<th style='width:100px'><?=$_LANG_TEXT['numtext'][$lang_code]?></th>
							<th style="width:calc(calc(calc(100% - 50px) - <?php echo sizeof($tasks) * 100; ?>px) - 100px)"><?=$_LANG_TEXT['checkitemtext'][$lang_code]?></th>
							<th style='width:<?php echo sizeof($tasks) * 100; ?>px' colspan="<?php echo sizeof($tasks); ?>"><?=$_LANG_TEXT['taskdiv'][$lang_code]?></th>
							<th style='width:100px'><?=$_LANG_TEXT['checktext'][$lang_code]?></th>
						</tr>
						<?php
						foreach ($lists as $index => $item) {
							$no = $index + 1;
						?>
						<tr class="doc_item">
							<td>
								<?=$no?>
								<input type="hidden" class="no_submit item_text" name="item_text[]" value="<?=$item->text?>" />
								<input type="hidden" class="no_submit item_type" name="item_type[]" value="<?=$item->type?>" />
							</td>
							<td style="padding:3px 10px;text-align:left"><?=$item->text?></td>
							<?php
							foreach ($tasks as $idx => $text) {
								$answers = $item->answers;
							?>
							<td style='width:100px;' title="<?=$item->text?>" onclick="toggleIDCSupportCheckbox()">
								<input type="checkbox" class="no_submit chkbox doc_item_task doc_item_task_<?=$idx?>" id="check_task_<?=$no?>_<?=$idx?>" <?php if ($answers[$idx]) { echo 'checked="checked"'; }?> data-task="<?=$text?>"> <label for="check_task_<?=$no?>_<?=$idx?>"><?=$text?></label>
							</td>
							<?php
							}
							?>
							<td title="<?=$item->text?>" for="check_confirm_<?=$no?>" onclick="toggleIDCSupportCheckbox()">
								<input type="checkbox" class="no_submit chkbox doc_item_confirm" id="check_confirm_<?=$no?>" <?php if ($item->confirm) { echo 'checked="checked"'; }?>> <label for="check_confirm_<?=$no?>"><?=$_LANG_TEXT['confirmok'][$lang_code]?></label>
							</td>
						</tr>
						<?php
						}
						?>
					</table>
				</form>
			</div>
		</div>
		<div class="btn_wrap right" style='margin-bottom:10px;'>
			<!-- 저장하기 버튼 -->
			<a href="javascript:void(0)" onclick="saveIDCSupportChecklist()" class="btn required-update-auth hide">
				<?= $_LANG_TEXT['save_file'][$lang_code] ?>
			</a>
		</div>
	</div>
</div>
<style>
	#result_view .chkbox {width: 15px; height: 15px; margin-top: 3px !important;}
	#result_view label { user-select: none; }
</style>

<?php
include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";
?>