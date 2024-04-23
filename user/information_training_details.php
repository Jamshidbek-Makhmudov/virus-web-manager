<?php
$page_name = "external_training";

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI']) - 1);
$_apos = stripos($_REQUEST_URI,  "/");
if ($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;


include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
include_once $_server_path . "/" . $_site_path . "/inc/header.inc";
include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";

$train_seq = intVal($_REQUEST["train_seq"]);

$searchopt = $_REQUEST[searchopt];	// 검색옵션
$searchkey = $_REQUEST[searchkey];	// 검색어
$orderby = $_REQUEST[orderby];		// 정렬순서
$page = $_REQUEST[page];			// 페이지
$start_date = $_REQUEST[start_date];
$end_date = $_REQUEST[end_date];
if ($paging == "") $paging = $_paging;

if ($start_date == "") $start_date = date("Y-m-d", strtotime(date("Y-m-d") . " -1 month"));
if ($end_date == "") $end_date = date("Y-m-d");

$param = "";
if ($searchopt != "") $param .= ($param == "" ? "" : "&") . "searchopt=" . $searchopt;
if ($searchkey != "") $param .= ($param == "" ? "" : "&") . "searchkey=" . $searchkey;
if ($orderby != "") $param .= ($param == "" ? "" : "&") . "orderby=" . $orderby;
if ($start_date != "") $param .= ($param == "" ? "" : "&") . "start_date=" . $start_date;
if ($end_date != "") $param .= ($param == "" ? "" : "&") . "end_date=" . $end_date;

$Model_User=new Model_User();


if ($train_seq <> "") {

	if ($orderby != "") {
		$order_sql = " ORDER BY $orderby";
	} else {
		$order_sql = " ORDER BY train_seq DESC ";

	}
	$args = array("order_sql" => $order_sql, "train_seq" => $train_seq);

	$result = $Model_User->getItemTrainDetailsInfo($args);

	$row = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

	$train_seq = $row['train_seq'];
	$train_name = $row['train_name'];//교육명
	$project_name = $row['project_name'];
	$user_name = aes_256_dec($row['user_name']);
	$user_name_en = $row['user_name_en'];
	$user_company = $row['user_company'];

	$manager_name = aes_256_dec($row['manager_name']);
	$manager_name_en = $row['manager_name_en'];
	$manager_company = $row['manager_company'];//담당자회사명
	$manager_dept = $row['manager_dept'];//담당자부서
	$memo = $row['memo'];
	$manager_type = $row['manager_type'];//담당자부서
		$manager_belong = $row['manager_belong'];//담당자부서
								
	$train_date = $row['train_date'];
  $formatted_train_date = date('Y-m-d', strtotime($train_date));
	// $create_date = $row['create_date'];
  // $formatted_create_date = date('Y-m-d H:i', strtotime($create_date));


	if($manager_type=="EMP"){
		$manager_type_value=$_LANG_TEXT['out_manager_text'][$lang_code];
	} else if($manager_type=="OUT"){
		$manager_type_value=$_LANG_TEXT['onsite_agent_text'][$lang_code];
	} else {
		$manager_type_value=$manager_type;
	}
}

//**화면열람로그 기록
$page_title = "[{$user_name}] ".$_LANG_TEXT["External_training_details"][$lang_code];
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
		<div class="page_right"><span style='cursor:pointer' onclick="location.href='external_training.php'"><?=$_LANG_TEXT["btngobeforepage"][$lang_code];?></span></div>
		<!--  -->
		<div style="margin-top:50px">
			<div>
				<form name='frmMemo' id='frmMemo' method='POST'>
					<input type='hidden' id='train_seq' name='train_seq' value='<?= $train_seq ?>'>
					<input type='hidden' id='proc' name='proc'>
					<input type='hidden' id='proc_name' name='proc_name'>
					<table class="view">
						<tr>
							<th style='width:150px'><?= $_LANG_TEXT['projectname'][$lang_code] ?></th>
							<td style='width:350px'>
							
							<input type="text" name="project_name" id="project_name" class="frm_input"
									value="<?php echo $project_name; ?>" style="width:50%" maxlength="50">
						
						</td>
							<th class="line" style='width:150px'><?= $_LANG_TEXT['companyname_text'][$lang_code] ?></th>
							<td>
								
							<input type="text" name="user_company" id="user_company" class="frm_input"
									value="<?php echo $user_company; ?>" style="width:50%" maxlength="50">
							</td>
						</tr>
						<tr class="bg">
							<th><?= $_LANG_TEXT['user_name_kr'][$lang_code] ?></th>
							<td>
								
								<input type="text" name="user_name" id="user_name" class="frm_input"
									value="<?php echo $user_name; ?>" style="width:50%" maxlength="50">
							
							</td>
							<th class="line"><?= $_LANG_TEXT['engnameid'][$lang_code] ?></th>
							<td>

							
								
							<input type="text" name="user_name_en" id="user_name_en" class="frm_input"
									value="<?php echo $user_name_en; ?>" style="width:50%" maxlength="50">
							</td>
						</tr>

						<tr>
							<th><?= $_LANG_TEXT['classify_edu'][$lang_code] ?></th>
							<td>
								
								<!-- <input type="text" name="manager_type" id="manager_type" class="frm_input"
									value="<?php echo $manager_type_value; ?>" style="width:50%" maxlength="10"> -->

															
									<div style="display:flex; align-items:center; ">
										<?php $isChecked = ($manager_type == "EMP") ? 'checked' : '';?>
										<div class="">
											<input style="margin-top:2px;" type="radio" name="manager_type" id="manager_type1" value='EMP' <?= $isChecked ?>/>
											<label for="manager_type1"><span></span><?= $_LANG_TEXT['outsourcing_maganager'][$lang_code] ?></label>
										</div>
										<div style="margin-left:20px;" class="">
											<input style="margin-top:2px;" type="radio" name="manager_type" id="manager_type2" value='OUT'  <?= $isChecked == '' ? 'checked' : '' ?>/>
											<label for="manager_type2"><span></span><?= $_LANG_TEXT['on_site_agent'][$lang_code] ?></label>
										</div>
									</div>
								
							
							</td>
							<th class="line"><?= $_LANG_TEXT['education_manager_eng_id'][$lang_code] ?></th>
							<td>
							
							<input type="text" name="manager_name_en" id="manager_name_en" class="frm_input"
									value="<?php echo $manager_name_en; ?>" style="width:50%" maxlength="50">
							</td>
						</tr>
						<tr class="bg">
							<th><?= $_LANG_TEXT['department_of_education'][$lang_code] ?></th>
							<!-- <td> <?//php echo $manager_type == "EMP" ? $manager_dept: $manager_company ?> </td> -->
							<!-- <td> <?//php echo $manager_type == "EMP" ? $manager_belong: $manager_company ?> </td> -->
							<td>
								 
								<input type="text" name="manager_belong" id="manager_belong" class="frm_input"
									value="<?php echo $manager_belong != "" ? $manager_belong: $manager_company ?>" style="width:50%" maxlength="50">
								</td>
							<th class="line"><?= $_LANG_TEXT['training_date'][$lang_code] ?></th>
							<td>
								
							<input type="text" name="train_date" id="train_date" class="frm_input datepicker"
									value="<?php echo $formatted_train_date; ?>" style="width:100px" maxlength="12">
							</td>
						</tr>



						<tr>
							<th><?= $_LANG_TEXT['memotext'][$lang_code] ?></th>
							<td colspan="3">
									<input type='text' id='memo' name='memo' class='frm_input' value='<?= $memo ?>' style='width:90%' maxlength="100">

							</td>
						</tr>
					</table>
				</form>
			</div>
	
		
			<div class="btn_wrap right" style='margin-bottom:10px;'>
				<a href="#" onclick="VisitorRegProcess()" class="btn required-update-auth hide"><?= $_LANG_TEXT['save_file'][$lang_code] ?></a>
			</div>


		</div>


	</div>
</div>
<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>

