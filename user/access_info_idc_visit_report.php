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

$doc_div  = "VSR_IDC_REPORT";
$Model_User = new Model_User();
$Model_User->SHOW_DEBUG_SQL = false;

if (!empty($v_user_list_seq)) {
	$args = compact("v_user_list_seq");
	$result = $Model_User->getUserVistListDetailsInfo_IDC($args);
	$detail = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

	@extract($detail, EXTR_OVERWRITE);
	
	$v_user_name = aes_256_dec($v_user_name);
	$str_v_user_type = $_CODE_V_USER_TYPE_DETAILS[$v_user_type];

	$args = compact("v_user_list_seq", "user_doc_seq");
	$document = $Model_User->getUserVisitListReport_IDC($args);
	@extract($document, EXTR_OVERWRITE);
	
	$doc_content = json_decode($doc_content);
}

$page_title = "[{$v_user_name}] " . $doc_title;

?>
<script>
	$(document).ready(function(){
		$(".timepicker").wickedpicker(timepickerOpts)
		$(".timepicker").prop("readonly",true)

		$(".datetime").change(function(e) { 
			e.preventDefault();
			let index = $(e.target).data('index');
			let date = $(".date_"+index).val();
			let time = $(".time_"+index).val().replace(/ /g, '');
			
			$(".report_item_"+index).val(date+" "+time);
		});
	});
</script>
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
			<div class="sub_tit" style='line-height:30px;'> ><? echo trsLang('유지보수결과서','idcvisitorreporttext');?></div>
			
				<form name='frmVisitReport' id='frmVisitReport' method='POST'>
					<input type='hidden' id='v_user_list_seq' name='v_user_list_seq' value='<?= $v_user_list_seq ?>'>
					<input type='hidden' id='user_doc_seq' name='user_doc_seq' value='<?= $user_doc_seq ?>'>
					<input type='hidden' id='doc_div' name='doc_div' value='<?= $doc_div ?>'>
					<input type='hidden' id='doc_title' name='doc_title' value='<?= $doc_title ?>'>
					<input type='hidden' id='doc_title_enc' name='doc_title_enc' value=''>
					<input type='hidden' id='doc_content_enc' name='doc_content_enc' value=''>
					<input type='hidden' id='proc_name' name='proc_name'>
					<input type='hidden' id='proc_exec' name='proc_exec'>
					<div>
						<table class="view">
						<?php 
						$writer = null;
						foreach ($doc_content->lists as $idx => $item) {
							$text = str_replace(' ', '', $item->text);
							$itemId = "report-item-{$idx}";
							$itemClass = "no_submit report_item report_item_{$idx}";
							$placeholder = "{$item->text}을 입력하세요.";

							$value = $item->answer;

							if (empty($value) && ($text == "작업유형")) {
								$value = $v_purpose;
							}
						?>
							<?php
							
							if ($text == "담당자") {
								$writer = $item;

								if (empty($writer->answer)) {
									$writer->answer = $v_user_name;
								}

								continue;
							} else if ($item->type == "STARTTIME") {
								if (empty($value)) {
									$created = date_create($in_time);
									$value   = date_format($created, "Y-m-d H:i");
								}

								$datetime   = explode(' ', $value);
								$start_date = $datetime[0];
								$start_time = $datetime[1];
							?>
							<tr>
								<th>* <?php echo $item->text; ?></th>
								<td style="width:40%">
									<input type="hidden" class="<?php echo $itemClass; ?>" data-text="<?php echo $item->text; ?>" data-type="<?php echo $item->type; ?>" value='<? echo "{$start_date} {$start_time}"; ?>'>
									<input type="text" class="frm_input no_submit datepicker datetime date_<?php echo $idx; ?>" style="width: 220px;text-align:center" data-index="<?php echo $idx; ?>" value='<? echo $start_date;?>'>
									<input type="text" class="frm_input no_submit timepicker datetime time_<?php echo $idx; ?>" style="width: 50px; text-align:center" data-index="<?php echo $idx; ?>" value='<? echo $start_time;?>'>
								</td>
							<?php 
							} else if ($item->type == "ENDTIME") {
								if (empty($value)) {
									$value = date('Y-m-d H:i');
								}

								$datetime   = explode(' ', $value);
								$close_date = $datetime[0];
								$close_time = $datetime[1];
							?>
								<th class="th-pad">* <?php echo $item->text; ?></th>
								<td style="width:40%">
									<input type="hidden" class="<?php echo $itemClass; ?>" data-text="<?php echo $item->text; ?>" data-type="<?php echo $item->type; ?>" value='<? echo "{$close_date} {$close_time}"; ?>'>
									<input type="text" class="frm_input no_submit datepicker datetime date_<?php echo $idx; ?>" style="width: 220px;text-align:center" data-index="<?php echo $idx; ?>" value='<? echo $close_date;?>'>
									<input type="text" class="frm_input no_submit timepicker datetime time_<?php echo $idx; ?>" style="width: 50px; margin-left: 10px; text-align:center" data-index="<?php echo $idx; ?>" value='<? echo $close_time;?>'>
								</td>
							</tr>
							<?php 
							} else {
							?>
							<tr>
								<th>* <?php echo $item->text; ?></th>
								<td style="width:90%" colspan="3">
									<?php
									if ($item->type == "TEXTLINE") {
									?>
									<input type="text" class="frm_input w-full <?php echo $itemClass; ?>" id="<?php echo $itemId; ?>" data-text="<?php echo $item->text; ?>" data-type="<?php echo $item->type; ?>" style="width:90%;" value='<? echo $value?>' placeholder="<?php echo $placeholder; ?>">
									<?php
									} else if ($item->type == "TEXTAREA") { 
										$height = "150px";
									?>
									<textarea class="frm_text w-full <?php echo $itemClass; ?>" id="<?php echo $itemId; ?>" data-text="<?php echo $item->text; ?>" data-type="<?php echo $item->type; ?>" style="width:90%;height:<?php echo $height;?>" placeholder="<?php echo $placeholder; ?>"><? echo $value?></textarea>
									<?php
									}
									?>
								</td>
							</tr>
						<?php
							}
						}
						?>
						<tr>
							<th>* <?php echo $writer->text; ?></th>
							<td style="width:90%" colspan="3">
								<input type="text" class="frm_input w-full <?php echo $itemClass; ?>" id="<?php echo $itemId; ?>" style="width: 220px;" data-text="<?php echo $writer->text; ?>" data-type="<?php echo $writer->type; ?>" value='<? echo $writer->answer?>' placeholder="<?php echo $placeholder; ?>">
							</td>
						</tr>
						</table>
					</div>
				</form>
			</div>
			<div class="btn_wrap right" style='margin-top: 30px;margin-bottom:10px;'>
				<!-- 저장하기 버튼 -->
				<a href="javascript:void(0)" onclick="saveIDCVisitReport()" class="btn required-update-auth hide">
					<?= $_LANG_TEXT['save_file'][$lang_code] ?>
				</a>
			</div>
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