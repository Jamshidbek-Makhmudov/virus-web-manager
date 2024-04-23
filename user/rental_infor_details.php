<?php
$page_name = "rental_details";

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

$rent_list_seq = intVal($_REQUEST["rent_list_seq"]);

$searchopt = $_REQUEST[searchopt]; // 검색옵션
$searchkey = $_REQUEST[searchkey]; // 검색어
$orderby = $_REQUEST[orderby]; // 정렬순서
$page = $_REQUEST[page]; // 페이지
$start_date = $_REQUEST[start_date];
$end_date = $_REQUEST[end_date];

$memo = $_POST['memo'];

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
$emp_seq = $_ck_user_seq;

if ($rent_list_seq <> "") {

	if ($useyn == "Y" || $useyn == "N") {
		$search_sql .= " and user_agree_yn = '$useyn' ";
		// $search_sql .= " user_agree_yn = '$useyn' ";

	}

	if ($orderby != "") {
		$order_sql = " ORDER BY $orderby";
	} else {
		$order_sql = " ORDER BY rent_list_seq DESC ";

	}
	$args = array("order_sql" => $order_sql, "rent_list_seq" => $rent_list_seq);
	// $Model_User->SHOW_DEBUG_SQL = true;	

	$result = $Model_User->getItemRentalDetailsInfo($args);



	$row = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

	$phone_no_enc = $row['user_phone'];
	$phone_no = aes_256_dec($phone_no_enc);
	$user_company = $row['user_company'];
	$item_name = $row['item_name'];
	$item_mgt_number = $row['item_mgt_number'];
	$rent_date = $row['rent_date'];
	$return_sche_dt = $row['return_schedule_date'];
	$user_dept = $row['user_dept'];
	$user_name = aes_256_dec($row['user_name']);
	$rent_purpose = $row['rent_purpose'];
	$rent_center_code = $row['rent_center_code'];
	$emp_no = $row['emp_no'];
	$emp_name = aes_256_dec($row['emp_name']);


	//not used					
	$return_date = $row['return_date'];
	$rent_list_seq = $row['rent_list_seq'];
	$user_type = $row['user_type'];
	$user_name_en = $row['user_name_en'];
	$return_emp_seq = $row['return_emp_seq'];
	$create_date = $row['create_date'];
	$memo = $row['memo'];
	$user_agree_yn = $row['user_agree_yn'];
	$user_belong = $row['user_belong'];

	$rnum = $row['rnum'];

	$formatted_rent_date = date('Y-m-d H:i', strtotime($rent_date));


	if (!empty($return_sche_dt) && $return_sche_dt !== 'null') {
		$formatted_return_sche_dt = date('Y-m-d', strtotime($return_sche_dt));
	} else {
		$formatted_return_sche_dt = '';
	}
	if (!empty($return_date) && $return_date !== 'null') {
		$formatted_return_date = date('Y-m-d H:i', strtotime($return_date));
	} else {
		$formatted_return_date = '';
	}

	

}
if (!$user_dept) {
	$user_dept = $_LANG_TEXT['out_worker_text'][$lang_code];

}

//**화면열람로그 기록
$page_title = "[{$user_name}] ".$_LANG_TEXT["detailed_product_rental_information"][$lang_code];
$work_log_seq = WriteAdminActLog($page_title,'VIEW');
?>
<div id="result_view">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$page_title;?></span></h1>

			</div>

			<span class="line"></span>
		</div>
		<div class="page_right"><span style='cursor:pointer' onclick="location.href='rental_details.php'">
				<?= $_LANG_TEXT["btngobeforepage"][$lang_code]; ?>
			</span></div>
		<!--  -->
		<div style="margin-top:50px">
			<div>
				<form name='frmMemo' id='frmMemo' method='POST'>
					<input type='hidden' name='proc' id='proc'>
					<input type='hidden' name='proc_name' id='proc_name'>
					<input type="hidden" name="rent_list_seq" id="rent_list_seq" value="<?php echo $rent_list_seq; ?>">
					<table class="view">

						<tr>
							<th style='width:150px'>
								<?= $_LANG_TEXT['affiliation_classification'][$lang_code] ?>
							</th>

							<td style="width:450px;">

								<!-- <input type="text" name="user_dept" id="user_dept" class="frm_input" value="<?php echo $user_dept; ?>"
									style="width:50%;" maxlength="20"> -->
								<div style="display:flex; align-items:center; ">
							
										<div class="">
											<input style="margin-top:2px;" type="radio" name="user_type" id="user_type3" value='EMP' <? if($user_type=="EMP") echo "checked"; ?>
												/>
											<label for="user_type3"><span></span>
												<?= $_LANG_TEXT['kakao_employees'][$lang_code] ?>
											</label>
										</div>
										<div style="margin-left:20px;" class="">
											<input style="margin-top:2px;" type="radio" name="user_type" id="user_type4" value='OUT'
												<? if($user_type=="OUT") echo "checked"; ?> />
											<label for="user_type4"><span></span>
												<?= trsLang('방문객','m_visitor'); ?>
											</label>
										</div>

										</div>



							</td>
							<th class="line" style='width:150px'>
								<?= trsLang('소속','belongtext'); ?>
							</th>
							<td>

								<input type="text" name="user_belong" id="user_belong" class="frm_input"
									value="<?php echo $user_belong; ?>" style="width:50%;" maxlength="50">
							</td>
						</tr>
						<tr class="bg">
							<th>
								<?= $_LANG_TEXT['nametext'][$lang_code] ?>
							</th>
							<td>
								<input type="text" name="user_name" id="user_name" class="frm_input" value="<?php echo $user_name; ?>"
								style="width:100px;" maxlength="50"> 
								<input type="text" name="user_name_en" id="user_name_en" class="frm_input"
								value="<?php echo $user_name_en; ?>" style="width:200px;" maxlength="50"
								placeholder="<?= $_LANG_TEXT['engnameid'][$lang_code] ?>">
							</td>
							<th class="line">
								<?= $_LANG_TEXT['contactphonetext'][$lang_code] ?>
							</th>
							<td>
								<input type="text" name="user_phone" id="user_phone" class="frm_input" value="<?php echo replaceHiddenChar($phone_no,0,3); ?>"
									style="width:22%;" maxlength="400">
								<a href="javascript:void(0)" onclick="showHiddenInfo('user_phone','<? echo $phone_no_enc?>')"><i class="fa fa-eye-slash"></i></a>
							</td>
						</tr>

						<tr>
							<th>
								<?= $_LANG_TEXT['rentalitems'][$lang_code] ?>
							</th>

							<td>
<!-- 
								<input type="text" name="item_name" id="item_name" class="frm_input" value="<?php echo $item_name; ?>"
									style="width:22%;" maxlength="20"> -->
					<select name='item_name' id='item_name' style="width:350px;"  >
					<?	
						$result = $Model_User->getRentItemList();
						if ($result) {
							while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
								  $selected = ($row['value'] == $item_name) ? "selected" : "";
								echo "<option value='{$row['value']}' $selected>{$row['name']}</option>";
							}
						}
					?>
				</select>
							</td>
							<th class="line">
								<?= trsLang('물품번호','itemnumber'); ?>
							</th>

							<td>

								<input type="text" name="item_mgt_number" id="item_mgt_number" class="frm_input"
									value="<?php echo $item_mgt_number; ?>" style="width:22%;" maxlength="50">
							</td>
						</tr>
						<tr class="bg">
							<th>
								<?= $_LANG_TEXT['rental_location'][$lang_code] ?>
							</th>

							<td>

								<!-- <input type="text" name="rent_center_code" id="rent_center_code" class="frm_input"
									value="<?php echo $rent_center_code; ?>" style="width:50%;" maxlength="20"> -->

									<select name='rent_center_code' id='rent_center_code' class="frm-select" style="width:350px;">
									<?php
									$result = $Model_User->getCenerList();
									while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

										$scan_center_code = $row['scan_center_code'];
										$scan_center_name = $row['scan_center_name'];

										$selected = ($scan_center_code == $rent_center_code) ? "selected" : "";
										$display_name = (!empty($scan_center_name)) ? $scan_center_name : $scan_center_code;

										 echo "<option value='$scan_center_code' $selected>										 
										 $display_name
										 </option>";

									}
									?>
								</select>




							</td>
							<th class="line">
								<?= $_LANG_TEXT['rental_purpose'][$lang_code] ?>
							</th>
							<td>


								<input type="text" name="rent_purpose" id="rent_purpose" class="frm_input"
									value="<?php echo $rent_purpose; ?>" style="width:50%;" maxlength="100">
							</td>

						</tr>
						<tr>
							<th>
								<?= $_LANG_TEXT['rentaldate'][$lang_code] ?>
							</th>
							<td>
								<div class='flex-start'>

									
									<input type="text" name="rent_date" id="rent_date" class="frm_input"
									value="<?php echo $formatted_rent_date; ?>" style="width:120px;" maxlength="16">
									<p style='padding-left:5px; opacity:0.75'>ex: (<?= date("Y-m-d H-i") ?>)</p>
								</div>


							</td>
							<th class="line">
								<?= $_LANG_TEXT['return_schedule_date_text'][$lang_code] ?>
							</th>
							<td>

								<input type="text" name="return_schedule_date" id="return_schedule_date" class="frm_input datepicker"
									value="<?php echo $formatted_return_sche_dt; ?>" style="width:100px;" maxlength="20">
							</td>

						</tr>
						<tr class="bg">
							<th>
								<?= $_LANG_TEXT['retriver_text'][$lang_code] ?>
							</th>
							<td>
								<? if ($formatted_return_date != "") { ?>
									<?= $emp_name ?>
								<? } ?>
							</td>
							<th class="line">
								<?= $_LANG_TEXT['returndate'][$lang_code] ?>
							</th>

							<td>
								<?= $formatted_return_date ?>
							</td>


						</tr>

						<tr>
							<th>
								<?= $_LANG_TEXT['memotext'][$lang_code] ?>
							</th>
							<td colspan="3">
								<input type='text' id='memo' name='memo' class='frm_input' value='<?= $memo ?>' style='width:90%'
									maxlength="100">

							</td>
						</tr>
					</table>
				</form>
			</div>
			<div id="messageDiv"></div>


			<? if ($formatted_return_date != "") { ?>


				<!-- 저장 -->
				<div class="btn_wrap right" style='margin-bottom:10px;margin-left:5px;'>
					<a href="#" onclick="VisitorRegProcess()" class="btn required-update-auth hide">
						<?= $_LANG_TEXT['save_file'][$lang_code] ?>
					</a>
				</div>
	
				<!-- 회수취소 -->
				<div class="btn_wrap right" style='margin-bottom:10px;margin-right:5px;'>
					<a href="#" onclick="cancelRecovery('<?= $rent_list_seq ?>')" class="btn required-update-auth hide">
						<?= $_LANG_TEXT['unrecover_text'][$lang_code] ?>
					</a>
				</div>

			<? } else { ?>
				<div class="btn_wrap right" style='margin-bottom:10px;margin-left:5px;'>
					<a href="#" onclick="VisitorRegProcess()" class="btn required-update-auth hide">
						<?= $_LANG_TEXT['save_file'][$lang_code] ?>
					</a>
				</div>
				<div class="btn_wrap right" style='margin-bottom:10px; '>
					<a href="#" onclick="RentRecovery('<?= $rent_list_seq ?>')" class="btn required-update-auth hide">
						<?= $_LANG_TEXT['recoveryprocessing'][$lang_code] ?>
					</a>
				</div>

			<? } ?>

		</div>


	</div>
</div>
<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>