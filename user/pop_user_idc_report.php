<?php
$_section_name = "pop_user_idc_report";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$v_user_list_seq = $_REQUEST["v_user_list_seq"];
$user_doc_seq    = $_REQUEST["user_doc_seq"];

$Model_User = new Model_User;
$Model_User->SHOW_DEBUG_SQL = false;

$args = compact("v_user_list_seq", "user_doc_seq");

$result = $Model_User->getUserVistListDetailsInfo_IDC($args);
$detail = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
@extract($detail);

$email = ($_encryption_kind == "2") ? aes_256_dec($v_email) : $v_email;
$phone = ($_encryption_kind == "2") ? aes_256_dec($v_phone) : $v_phone;

$document = $Model_User->getUserVisitListReport_IDC($args);
@extract($document); // doc_div, doc_title, doc_content, emp_no, emp_name, create_date

$v_user_name = aes_256_dec($v_user_name);
$emp_name = aes_256_dec($emp_name);

$content = json_decode($doc_content);
$tasks = $content->tasks;
$lists = $content->lists;

$create_date = date_create($create_date);

$doc_header = "";

if ($doc_div == "VSR_IDC_REPORT") {
	$doc_header = trsLang('유지보수결과서', 'idcvisitorreporttext');
	$job = array(
		"name"=>"",
		"title"=>"",
		"target"=>"",
		"start"=>"",
		"close"=>"",
		"content"=>"",
		"result"=>"",
		"writer"=>""
	);

	$start_date  = ";
	$close_date  = ";
	$submit_date = date_format($create_date, "Y년 m월 d일");

	foreach ($lists as $index => $list) {
		$item = "";
		$text = str_replace(' ', '', $list->text);
		$content = $list->text;
		$answers = htmlentities($list->answer);
	
		if ($text == "작업유형") {
			$item = "title";
		} else if ($text == "작업명") {
			$item = "name";
		} else if ($text == "작업대상") {
			$item = "target";
		} else if ($text == "작업시작일시") {
			$item = "start";
			$start_date = date_format(date_create($answers), "Y년 m월 d일");
			$answers = date_format(date_create($answers), "Y년 m월 d일 H시 i분");
		} else if ($text == "작업종료일시") {
			$item = "close";
			$close_date = date_format(date_create($answers), "Y년 m월 d일");

			if ($start_date == $close_date) {
				$answers = date_format(date_create($answers), "H시 i분");
			} else {
				$answers = date_format(date_create($answers), "Y년 m월 d일 H시 i분");
			}
		} else if ($text == "작업상세내역") {
			$item = "content";
		} else if ($text == "결과/특이사항") {
			$item = "result";
		} else if ($text == "담당자") {
			$item = "writer";
		}

		$job[$item] = compact("content", "answers");
	}

} else if ($doc_div == "MGR_IDC_REPORT") {
	$doc_header = trsLang('외부출입지원 체크리스트', 'idcmanagerreporttext');
}


?>
<div id="mark">
	<div class="content" style='width:1080px;max-height:900px;'>
		<div class='tit'>
			<div class='txt'><? echo $doc_header; ?></div>
			<div class='right'>
				<div class='close' onClick="ClosepopContent();"></div>
			</div>
		</div>		

		<div id='print_area' class='wrapper2' style="margin:0 auto;">
			<style type="text/css">
				#print_area .table { table-layout:fixed;border:1px solid #000;border-collapse:collapse; }
				#print_area .title { text-align:center;height:40px;padding:10px;font-size:1.4rem;color:#000;background-color:#dfdfdf }
				#print_area .subtitle { text-align:left;height:32px;padding:6px 5px 6px 20px;font-size:1.1rem;color:#000 }
				#print_area .header { text-align:center;height:24px;padding:5px;font-size:1rem;color:#000;background-color:#dfdfdf }
				#print_area .subheader { text-align:center;height:24px;padding:5px;font-size:1rem;color:#000;background-color:#eee }
				#print_area .answer { text-align:left;height:24px;padding:5px 5px 5px 20px;font-size:1rem;color:#000 }
				#print_area .text { vertical-align:top;padding:20px;height:200px;font-size:1rem;line-height:1.5rem;color:#000;background-color:#fff }
				#print_area .signature { text-align:left;font-size:1.1rem;color:#000;letter-spacing:1px; }
				#print_area .signature.date { text-align:center;font-size:1.1rem;color:#000;letter-spacing:3px;padding-top:40px }
				#print_area .signature.name { text-align:center;font-size:1.1rem;color:#000;letter-spacing:3px;padding-top:20px }
				#print_area .footer { padding: 30px; }
				@media print {
					body { background-color:#fff !important; -webkit-print-color-adjust:exact; padding: 0 15px; }
					.content .table { table-layout:fixed;border:1px solid #000;border-collapse:collapse; }
					.content .title { text-align:center;height:60px;padding:10px;font-size:2rem;color:#000;background-color:#dfdfdf }
					.content .subtitle { text-align:left;height:50px;padding:10px 5px 10px 20px;font-size:1.3rem;color:#000 }
					.content .header { text-align:center;height:36px;padding:5px;font-size:1.1rem;color:#000;background-color:#dfdfdf }
					.content .subheader { text-align:center;height:36px;padding:5px;font-size:1.1rem;color:#000;background-color:#eee }
					.content .answer { text-align:left;height:36px;padding:5px 5px 5px 20px;font-size:1.1rem;color:#000 }
					.content .text { vertical-align:top;padding:20px;height:400px;font-size:1.0rem;line-height:1.7rem;color:#000;background-color:#fff }
					.content .signature { text-align:left;font-size:1.3rem;color:#000;letter-spacing:1px; }
					.content .signature.date { text-align:center;font-size:1.4rem;color:#000;letter-spacing:3px;padding-top:50px }
					.content .signature.name { text-align:center;font-size:1.4rem;color:#000;letter-spacing:3px;padding-top:30px }
					.content .footer { padding: 50px; }
				}
			</style>
			<div id='pop_user_idc_report' style='padding:0px 30px;width:992px;margin:0;background-color:#fff'>
			<?php if ($doc_div == "VSR_IDC_REPORT") { ?>
				<div class='content' style='text-align:left;font-size:14px;line-height:21px;margin:60px 0;background-color:#fff'>
					<table border="1" class="table" style="min-width:992px;">
					<colgroup>
						<col width="150">
						<col width="140">
						<col width="">
						<col width="140">
						<col width="">
					</colgroup>
						<tr>
							<th colspan="5" class="title"><? echo $doc_title; ?></th>
						</tr>
						<tr>
							<th colspan="5" class="subtitle">1. 작업자 기본정보</th>
						</tr>
						<tr>
							<th rowspan="3" class="header">*작업자</th>
							<th class="subheader">소속</th>
							<td colspan="3" class="answer"><?php echo $v_user_belong; ?></td>
						</tr>
						<tr>
							<th class="subheader">이름</th>
							<td class="answer"><?php echo $v_user_name; ?></td>
							<th class="subheader">연락처</th>
							<td class="answer"><?php echo $phone; ?></td>
						</tr>
						<tr>
							<th class="subheader">이메일</th>
							<td colspan="3" class="answer"><?php echo $email; ?></td>
						</tr>
						<tr>
							<th colspan="5" class="subtitle">2. 작업정보</th>
						</tr>
						<tr>
							<th class="header">*<?php echo @$job["title"]["content"]; ?></th>
							<td colspan="4" class="answer"><?php echo @$job["title"]["answers"]; ?></td>
						</tr>
						<tr>
							<th class="header">*<?php echo @$job["start"]["content"]; ?></th>
							<td colspan="2" class="answer"><?php echo @$job["start"]["answers"]; ?></td>
							<th class="header">*<?php echo @$job["close"]["content"]; ?></th>
							<td class="answer"><?php echo @$job["close"]["answers"]; ?></td>
						</tr>
						<tr>
							<th class="header">*<?php echo @$job["target"]["content"]; ?></th>
							<td colspan="4" class="answer"><?php echo @$job["target"]["answers"]; ?></td>
						</tr>
						<tr>
							<th class="header">*<?php echo @$job["name"]["content"]; ?></th>
							<td colspan="4" class="answer"><?php echo @$job["name"]["answers"]; ?></td>
						</tr>
						<tr>
							<th class="header">작업내용</th>
							<td colspan="4" style="padding: 0 !important;">
								<table border="0" class="table" style="border:0 solid #000">
									<tr>
										<th class="header" style="width: 65%;border-bottom:1px solid #333;border-right:1px solid #333"><?php echo @$job["content"]["content"]; ?></th>
										<th class="header" style="border-bottom:1px solid #333;"><?php echo @$job["result"]["content"]; ?></th>
									</tr>
									<tr>
										<td class="answer text" style="border-right:1px solid #333"><?php echo nl2br(@$job["content"]["answers"]); ?></td>
										<td class="answer text"><?php echo nl2br(@$job["result"]["answers"]); ?></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="5" class="footer">
								<table class="table" style="border:0 solid #000">
									<tr>
										<td class="signature">위 유지보수 작업 내용 및 결과를 확인합니다.</td>
									</tr>
									<tr>
										<td class="signature date" style="letter-spacing:3px;text-align:center;"><?php echo $submit_date; ?></td>
									</tr>
									<tr>
										<td class="signature name" style="letter-spacing:3px;text-align:center;"><?php echo @$job["writer"]["content"]; ?> : <?php echo @$job["writer"]["answers"]; ?></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</div>
			<?php } else { ?>
				<div style='margin:30px;text-align:center;font-size:23px;font-weight:bold;'><? echo $doc_title; ?></div>
				<div class='content'  style='text-align:left;font-size:14px;line-height:21px;margin-bottom:30px;'>
					<table style="table-layout:fixed;min-width:980px;">
						<tr>
							<th class="line" style="width:90px;padding:5px;"><?= $_LANG_TEXT['worknumbertext'][$lang_code] ?> :</th>
							<td style="padding: 5px; color: #000;"><?php echo $elec_doc_number; ?></td>
						</tr>
						<tr>
							<th style="padding: 5px;"><?= $_LANG_TEXT['work_detail'][$lang_code] ?> :</th>
							<td style="padding: 5px; color: #000;"><?php echo $v_purpose; ?></td>
						</tr>
						<tr>
							<th style="padding: 5px;"><?= $_LANG_TEXT['center_location'][$lang_code] ?> :</th>
							<td style="padding: 5px; color: #000;"><?php echo $visit_center_desc; ?></td>
						</tr>
						<tr>
							<th style="padding: 5px;"><? echo trsLang('출입자정보', 'visitortext'); ?> :</th>
							<td style="padding: 5px; color: #000;"><?php echo $v_user_belong; ?> / <?php echo $v_user_name; ?></td>
						</tr>
						<?php if (!empty($emp_no)) { ?>
						<tr>
							<th style="padding: 5px;"><? echo trsLang('작업 지원자', 'supportertext'); ?> :</th>
							<td style="padding: 5px; color: #000;"><?php echo $emp_name; ?>(<? echo $emp_no;?>)</td>
						</tr>
						<?php } ?>
					</table>
					<table border="1" style="table-layout:fixed;margin-top:30px;min-width:980px;border:1px solid #333;border-collapse:collapse;">
						<tr>
							<th rowspan="2" style="text-align:center;width:40px;padding:5px 0;">No</th>
							<th rowspan="2" style="text-align:center;padding:5px 0;"><?=$_LANG_TEXT['checkitemtext'][$lang_code]?></th>
							<th style='text-align:center;padding:5px 0;width:320px;' colspan="<?php echo sizeof($tasks); ?>"><?=$_LANG_TEXT['taskdiv'][$lang_code]?></th>
							<th rowspan="2" style='text-align:center;padding:5px 0;width:60px'><?=$_LANG_TEXT['confirmtext'][$lang_code]?></th>
						</tr>
						<tr>
							<?php
							foreach ($tasks as $text) {
							?>
							<th style='width:80px;padding:5px 0;text-align:center; '><?=$text?></th>
							<?php
							}
							?>
						</tr>
						<?php
						foreach ($lists as $index => $item) {
							$no = $index + 1;
						?>
						<tr class="doc_item">
							<td style="padding:10px;text-align:center;color:#000;"><?=$no?></td>
							<td style="padding:10px;text-align:left;color:#000;"><?=$item->text?></td>
							<?php
							foreach ($tasks as $idx => $text) {
								$answers = $item->answers;
							?>
							<td style='text-align:center;color:#000;font-size:16px;font-weight:bold;'><?php if ($answers[$idx]) { echo '○'; }?></td>
							<?php
							}
							?>
							<td style='text-align:center;color:#000;font-size:16px;font-weight:bold;'><?php if ($item->confirm) { echo '○'; }?></td>
						</tr>
						<?php
						}
						?>
					</table>
				</div>
			<? } ?>
			</div>
		</div>
		<div class="btn_wrap" style='margin-right:20px'>
			<div class='right'>
				<!--<a href="javascript:void(0)" class="btn" id="><? echo trsLang('수정','btnupdate');?></a>&nbsp;-->
				<a href="javascript:void(0)" class="btn required-print-auth hide" onclick="printPage('print_area')"><? echo trsLang('인쇄','btnprint2');?></a>
			</div>
		</div>
	</div>
</div>