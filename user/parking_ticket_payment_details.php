<?php
$page_name = "parking_ticket_payment";

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

$ticket_list_seq = intVal($_REQUEST["ticket_list_seq"]);

$searchopt = $_REQUEST[searchopt]; // 검색옵션
$searchkey = $_REQUEST[searchkey]; // 검색어
$orderby = $_REQUEST[orderby]; // 정렬순서
$page = $_REQUEST[page]; // 페이지
$start_date = $_REQUEST[start_date];
$end_date = $_REQUEST[end_date];

if ($paging == "")
	$paging = $_paging;

if ($start_date == "")
	$start_date = date("Y-m-d", strtotime(date("Y-m-d") . " -1 month"));
if ($end_date == "")
	$end_date = date("Y-m-d");

$param = "";
if ($searchopt != "")
	$param .= ($param == "" ? "" : "&") . "searchopt=" . $searchopt;
if ($searchkey != "")
	$param .= ($param == "" ? "" : "&") . "searchkey=" . $searchkey;
if ($orderby != "")
	$param .= ($param == "" ? "" : "&") . "orderby=" . $orderby;
if ($start_date != "")
	$param .= ($param == "" ? "" : "&") . "start_date=" . $start_date;
if ($end_date != "")
	$param .= ($param == "" ? "" : "&") . "end_date=" . $end_date;

$Model_User = new Model_User();


if ($ticket_list_seq <> "") {

	if ($orderby != "") {
		$order_sql = " ORDER BY $orderby";
	} else {
		$order_sql = " ORDER BY ticket_list_seq DESC ";

	}
	$args = array("order_sql" => $order_sql, "ticket_list_seq" => $ticket_list_seq);

	$result = $Model_User->getItemParkingDetailsInfo($args);

	$row = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

	$ticket_list_seq = $row['ticket_list_seq'];

	$user_name_en = $row['user_name_en'];
	$user_name = aes_256_dec($row['user_name']);
	$car_number = aes_256_dec($row['car_number']);
	$ticket_desc = $row['ticket_desc'];
	$user_company = $row['user_company'];
	$memo = $row['memo'];
	$user_belong = $row['user_belong'];
	$user_type = $row['user_type'];

	$create_date = $row['create_date'];
	$formatted_create_date = date('Y-m-d H:i', strtotime($create_date));

	$serve_time = $row['serve_time'];
	$getTime = $Model_User->getParkingTicket($serve_time);

	$out_time = $row['out_time'];

	//car number
	if ($_encryption_kind == "1") {
		$car_num = $row['car_number'];

	} else if ($_encryption_kind == "2") {

		if ($row['car_number'] != "") {
			$car_num = aes_256_dec($row['car_number']);
		}
	}
}

//**화면열람로그 기록
$page_title = "[{$user_name}] ".$_LANG_TEXT["parking_ticket_payment_details_theme"][$lang_code];
$work_log_seq = WriteAdminActLog($page_title,'VIEW');
?>
<script language="javascript">
	$(function () {
		$("#start_date").datepicker(pickerOpts);
		$("#end_date").datepicker(pickerOpts);
	});
</script>
<div id="result_view">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">

					 <h1><span id='page_title'><?=$page_title ;?></span></h1>

			</div>

			<span class="line"></span>
		</div>
		<div class="page_right"><span style='cursor:pointer' onclick="location.href='parking_ticket_payment.php'">
				<?= $_LANG_TEXT["btngobeforepage"][$lang_code]; ?>
			</span></div>
		<!--  -->
		<div style="margin-top:50px">
			<div>
				<form name='frmMemo' id='frmMemo' method='POST'>
					<input type='hidden' id='ticket_list_seq' name='ticket_list_seq' value='<?= $ticket_list_seq ?>'>
					<input type='hidden' id='proc' name='proc'>
					<input type='hidden' name='proc_name'>
					<table class="view">
						<tr class='bg'>
						<th style='width:150px'>
								<?= $_LANG_TEXT['affiliation_classification'][$lang_code] ?>
							</th>

							<td colspan="3" style="width:450px;">

								<div style="display:flex; align-items:center; ">
						

										<?php $isChecked = ($user_type == "EMP") ? 'checked' : '';?>


										<div class="">
											<input style="margin-top:2px;" type="radio" name="user_type" id="user_type3" value='EMP' <?= $isChecked ?>
												 />
											<label for="user_type3"><span></span>
												<?= $_LANG_TEXT['kakao_employees'][$lang_code] ?>
											</label>
										</div>
										<div style="margin-left:20px;" class="display-none">
											<input style="margin-top:2px;" type="radio" name="user_type" id="user_type4" value='OUT'
												  <?= $isChecked == '' ? 'checked' : '' ?>/>
											<label for="user_type4"><span></span>
												<?= $_LANG_TEXT['m_visitor'][$lang_code] ?>
											</label>
										</div>

											</div>



							</td>
							
						</tr>
						<tr>
							<th style='width:150px'>
								<?= $_LANG_TEXT['nametext'][$lang_code] ?></th>
							<td style=' display:inline- block; vertical-align: middle;max-width:150px  '>

								<input type="text" name="user_name" id="user_name" class="frm_input" value="<?php echo $user_name; ?>"
									style="width:100px;" maxlength="50">
								<input type="text" name="user_name_en" id="user_name_en" class="frm_input"
									value="<?php echo $user_name_en; ?>" style="max-width:200px;margin:0 7px;" maxlength="50" placeholder="<?= $_LANG_TEXT['engnameid'][$lang_code] ?>">

							</td>
							
							<th class="line" style='width:150px'>
								<?= $_LANG_TEXT['belongtext'][$lang_code] ?>
							</th>
							<td>

								<input type="text" name="user_belong" id="user_belong" class="frm_input"
									value="<?php echo $user_belong; ?>" style="width:50%" maxlength="50">
							</td>
							<!-- <td><? //php  echo $user_type == "EMP" ? $user_dept: $user_company ?></td> -->
						</tr>

						<tr class="bg">
							<th>
								<?= $_LANG_TEXT['purpose_visit'][$lang_code] ?>
							</th>
							<td>

								<input type="text" name="ticket_desc" id="ticket_desc" class="frm_input"
									value="<?php echo $ticket_desc; ?>" style="width:52%" maxlength="100">
							</td>
							<th class="line">
								<?= $_LANG_TEXT['carnumber'][$lang_code] ?>
							</th>
							<td>

								<input type="text" name="car_number" id="car_number" class="frm_input" value="<?php echo $car_num; ?>"
									style="width:50%" maxlength="50">
							</td>
						</tr>

						<tr>
							<th>
								<?= $_LANG_TEXT['requesttime'][$lang_code] ?>
							</th>
							<td>

								<!-- <input type="text" name="serve_time" id="serve_time" class="frm_input" value="<?php echo $getTime; ?>"
									style="width:50%" maxlength="16"> -->
									<?php 	$result = $Model_User->getParkingTicketList();?>

																		<select name='serve_time' id='serve_time' class="frm-select w-530">
										<option value='0'><?= $_LANG_TEXT['selectRequestTime'][$lang_code] ?></option>
										<? foreach ($result as $duration => $description) {
											 $selected = ($duration == $serve_time) ? "selected" : "";
											
											echo "<option value='$duration' $selected>$description</option>";
										}
										?>
									</select>





							</td>
							<th class="line">
								<?= $_LANG_TEXT['carouttime'][$lang_code] ?>
							</th>
							<td>
								<input type="text" name="out_time" id="out_time" class="frm_input" value="<?php echo $out_time; ?>"
									style="width:60px" maxlength="5" placeholder="<? echo date('H:i')?>"><button type="button" class="sch" onclick="$('#out_time').val(new Date().dateformat('hh:mi'))"><? echo trsLang('현재시간','btncurrenttime');?></button> 
							</td>
						</tr>



						<tr class="bg">
							<th>
								<?= $_LANG_TEXT['memotext'][$lang_code] ?>
							</th>
							<td>
								<input type='text' id='memo' name='memo' class='frm_input' value='<?= $memo ?>' style='width:90%'
									maxlength="100">
							</td>
							<th class="line">
								<?= $_LANG_TEXT['paymentdate'][$lang_code] ?>
							</th>
							<td>
								<?= $formatted_create_date ?>
							</td>
						</tr>
					</table>
				</form>
			</div>


			<div class="btn_wrap right" style='margin-bottom:10px;'>
				<a href="#" onclick="VisitorRegProcess()" class="btn required-update-auth hide">
					<?= $_LANG_TEXT['save_file'][$lang_code] ?>
				</a>
			</div>


		</div>


	</div>
</div>
<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>