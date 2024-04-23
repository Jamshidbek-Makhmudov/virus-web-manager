<?php
$page_name = "rental_info_change_history";

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

$searchandor = $_REQUEST[searchandor];
$searchoptm = $_REQUEST[searchoptm];	// 검색옵션
$searchkeym = $_REQUEST[searchkeym];	// 검색어

$uncovered = $_REQUEST[uncovered];	// 검색옵션

$searchopt = $_REQUEST[searchopt];	// 검색옵션
$searchkey = $_REQUEST[searchkey];	// 검색어
$orderby = $_REQUEST[orderby];		// 정렬순서
$page = $_REQUEST[page];			// 페이지
$paging = $_REQUEST[paging];
$start_date = $_REQUEST[start_date];
$end_date = $_REQUEST[end_date];

if ($paging == "") $paging = $_paging;
if ($useyn == "") $useyn = "Y";
$seq_val = "rent_list_seq";

$searchandor1 = $_REQUEST[searchandor1];
$searchandor2 = $_REQUEST[searchandor2];
$searchandor3 = $_REQUEST[searchandor3];
$searchoptm1 = $_REQUEST[searchoptm1];
$searchoptm2 = $_REQUEST[searchoptm2];
$searchoptm3 = $_REQUEST[searchoptm3];
$searchkeym1 = $_REQUEST[searchkeym1];
$searchkeym2 = $_REQUEST[searchkeym2];
$searchkeym3 = $_REQUEST[searchkeym3];


if ($start_date == "") $start_date = date("Y-m-d", strtotime(date("Y-m-d") . " -1 month"));
if ($end_date == "") $end_date = date("Y-m-d");

$param = "";
if ($searchopt != "") $param .= ($param == "" ? "" : "&") . "searchopt=" . $searchopt;
if ($searchkey != "") $param .= ($param == "" ? "" : "&") . "searchkey=" . $searchkey;
if ($orderby != "") $param .= ($param == "" ? "" : "&") . "orderby=" . $orderby;
if ($start_date != "") $param .= ($param == "" ? "" : "&") . "start_date=" . $start_date;
if ($end_date != "") $param .= ($param == "" ? "" : "&") . "end_date=" . $end_date;

if ($uncovered != "") $param .= ($param == "" ? "" : "&") . "uncovered=" . $uncovered;
if ($searchoptm != "") $param .= ($param == "" ? "" : "&") . "searchoptm=" . $searchoptm;
if ($searchkeym != "") $param .= ($param == "" ? "" : "&") . "searchkeym=" . $searchkeym;
if ($searchandor != "") $param .= ($param == "" ? "" : "&") . "searchandor=" . $searchandor;

if ($searchoptm1 != "") $param .= ($param == "" ? "" : "&") . "searchoptm1=" . $searchoptm1;
if ($searchoptm2 != "") $param .= ($param == "" ? "" : "&") . "searchoptm2=" . $searchoptm2;
if ($searchoptm3 != "") $param .= ($param == "" ? "" : "&") . "searchoptm3=" . $searchoptm3;
if ($searchkeym1 != "") $param .= ($param == "" ? "" : "&") . "searchkeym1=" . $searchkeym1;
if ($searchkeym2 != "") $param .= ($param == "" ? "" : "&") . "searchkeym2=" . $searchkeym2;
if ($searchkeym3 != "") $param .= ($param == "" ? "" : "&") . "searchkeym3=" . $searchkeym3;
if ($searchandor1 != "") $param .= ($param == "" ? "" : "&") . "searchandor1=" . $searchandor1;
if ($searchandor2 != "") $param .= ($param == "" ? "" : "&") . "searchandor2=" . $searchandor2;
if ($searchandor3 != "") $param .= ($param == "" ? "" : "&") . "searchandor3=" . $searchandor3;

//검색 로그 기록
$proc_name = $_POST['proc_name'];
if($proc_name != ""){
	$work_log_seq = WriteAdminActLog($proc_name,'SEARCH');
}


$emp_seq = $_ck_user_seq;

$Model_Stat = new Model_Stat();

?>
<script language="javascript">
	$(function() {
		$("#start_date").datepicker(pickerOpts);
		$("#end_date").datepicker(pickerOpts);
	});


</script>
<div id="user_list">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["rental_info_change_history"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>

		<!--검색폼-->
		<form name="searchForm" action="<?php echo $_SERVER[PHP_SELF] ?>" method="POST">
			<input type="hidden" name="page" value="">
			<input type='hidden' name='proc_name' id='proc_name'>
			<table class="search">
				<tr>
					<th style='widht:100px;'><?= $_LANG_TEXT['rentalDate'][$lang_code] ?> </th>
					<td>
						<input type="text" name="start_date" id="start_date" class="frm_input" placeholder="" style="width:100px" value="<?= $start_date ?>" maxlength="10"> ~
						<input type="text" name="end_date" id="end_date" class="frm_input" placeholder="" style="width:100px" value="<?= $end_date ?>" maxlength="10">
					</td>
				</tr>
				<?

				$searchopt_list = array(
					"user_name" => trsLang("이름", "nametext")
					, "user_belong" => trsLang("소속", "belongtext")
					, "item_name" => trsLang("대여물품", "rentalItems")
					, "item_mgt_number" => trsLang("물품번호", "itemnumber")
				);
				?>
				<tr>
					<th><? echo trsLang('키워드검색','keywordsearchtext'); ?> </th>
					<td style='padding:5px 13px'>
						<select name="searchopt" id="searchopt">
							<option value="" <?php if($searchopt == "") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['select_search_item'][$lang_code]?></option>
							<option value="user_name" <?php if($searchopt == "user_name") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['nametext'][$lang_code]?></option>
							<!--<option value="user_phone" <?php if($searchopt == "user_phone") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['contactphonetext'][$lang_code]?></option>-->
							<option value="user_belong" <?php if($searchopt == "user_belong") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['belongtext'][$lang_code]?></option>
							<option value="item_name" <?php if($searchopt == "item_name") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['rentalItems'][$lang_code]?></option>
							<option value="item_mgt_number" <?php if($searchopt == "item_mgt_number") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['itemnumber'][$lang_code]?></option>
						</select>
						<input type="text" class="frm_input" style="width:50%" name="searchkey" id="searchkey" value="<?= $searchkey ?>" maxlength="50">
						<input type="submit" value="<?= $_LANG_TEXT['usersearchtext'][$lang_code] ?>" style="cursor:pointer" class="btn_submit" onclick="return SearchSubmit(document.searchForm);">
						<input type="button" value="<?= $_LANG_TEXT['userdetailsearchtext'][$lang_code] ?>" style="cursor:pointer" class="btn_submit_no_icon" onclick="$('#search_detail').toggle()">

						<input type="button" value="<? echo trsLang('초기화','btnclear');?>" class="btn_submit_no_icon" onclick="location.href='<? echo $_www_server?>/stat/rental_info_change_history.php'">
						<!--상세검색-->
							<?
								$search_detail_visible = ($searchoptm1&&$searchkeym1 || $searchoptm2&&$searchkeym2 || $searchoptm3&&$searchkeym3);
							?>
						<!--  -->
							<div id='search_detail' style='<? if($search_detail_visible==false) echo "display:none";?>'>
							<? for ($i = 1; $i < 4; $i++) { ?>
								<div style='margin-top:5px;'>
									<select name="searchandor<? echo $i?>" id="searchandor<? echo $i?>" >
										<option value='AND' <? if(${"searchandor".$i}=="AND") echo "selected";?>>AND</option>
										<option value='OR' <? if(${"searchandor".$i}=="OR") echo "selected";?>>OR</option>
									</select>
									<select name="searchoptm<? echo $i ?>" id="searchoptm<? echo $i ?>">
										<option value="" <?php if ($searchoptm == "") {
																				echo ' selected="selected"';
																			} ?>>
											<?= $_LANG_TEXT['select_search_item'][$lang_code] ?></option>
										<?
										foreach($searchopt_list as $key=>$name){
											$selected = (${"searchoptm".$i}==$key) ? "selected" : "";
											echo "<option value='{$key}' {$selected} >{$name}</option>";
										}
										?>
									</select>
									<input style="width:50%" type="text" class="frm_input" name="searchkeym<? echo $i ?>" id="searchkeym<? echo $i ?>" maxlength="50" value='<? echo ${'searchkeym'.$i}?>'>
								</div>
							<? } ?>
						</div>
					</td>
				</tr>
			</table>
			<!--  -->
			<?php
			//검색항목
			 $search_sql .= " and user_agree_yn = 'Y' ";
			if ($start_date != "" && $end_date != "") {
				$search_sql .= " and rent_date between '" . str_replace('-', '', $start_date) . "000000' AND '" . str_replace('-', '', $end_date) . "235959' ";
			}

			// 키워드검색
			$searchandor0 = " and ( ";
			$searchoptm0 = $searchopt;
			$searchkeym0 = $searchkey;
			$keyword_search_sql = "";

			for ($i = 0; $i < 4; $i++) {
				
				$searchopt_i = ${"searchoptm".$i};	
				$searchkey_i = ${"searchkeym".$i};	
				$searchandor_i = ${"searchandor".$i};	

				if (!empty($searchopt_i) && !empty($searchkey_i)) {
					
					$keyword_search_sql .= " $searchandor_i ";

					if ($searchopt_i == "user_name") {
						if($_encryption_kind=="1"){

							 $keyword_search_sql .= " dbo.fn_DecryptString(user_name) like N'%$searchkey_i%' ";

						  }else if($_encryption_kind=="2"){

							 $keyword_search_sql .= " user_name= '".aes_256_enc($searchkey_i)."' ";
						  }
					} else if ($searchopt_i == "user_phone") {
							$searchkey_i = preg_replace("/[^0-9-]*/s", "", $searchkey_i); 
							if($_encryption_kind=="1"){

								 $keyword_search_sql .= " dbo.fn_DecryptString(user_phone) like N'%$searchkey_i%' ";

							  }else if($_encryption_kind=="2"){

								 $keyword_search_sql .= " user_phone= '".aes_256_enc($searchkey_i)."' ";
							  }

						// $keyword_search_sql .= " (user_phone like '%$searchkey_i%') ";
					} else if ($searchopt_i == "user_belong") {
						$keyword_search_sql .= " (user_belong like N'%$searchkey_i%') ";
					}else if ($searchopt_i == "item_name") {
						$keyword_search_sql .= " (item_name like N'%$searchkey_i%') ";
					}  else if ($searchopt_i == "item_mgt_number") {
						$keyword_search_sql .= " (item_mgt_number like '%$searchkey_i%') ";
					}
				}
			}

			if($keyword_search_sql != ""){
				$search_sql .= $keyword_search_sql.")";
			}


			//미회수 조회
			if($uncovered!=""){
				$search_sql .= " AND isnull(return_date,'') ='' ";
			}	
			$Model_Stat->SHOW_DEBUG_SQL = false;
			$args = array("search_sql" => $search_sql);
			$total = $Model_Stat->getRentalInfoChangeHistoryDetailsCount($args);
			$rows = $paging;			// 페이지당 출력갯수
			$lists = $_list;			// 목록수
			$page_count = ceil($total / $rows);
			if (!$page || $page > $page_count) $page = 1;
			$start = ($page - 1) * $rows;
			$no = $total - $start;
			$end = $start + $rows;

			if ($orderby != "") {
				$order_sql = " ORDER BY $orderby";
			} else {
				$order_sql = " ORDER BY h_seq DESC ";
			}
			$args = array("order_sql" => $order_sql, "search_sql" => $search_sql, "end" => $end, "start" => $start);
  //  $Model_Stat->SHOW_DEBUG_SQL = true;
			$result = $Model_Stat->getRentalInfoChangeHistoryDetails($args);

			$cnt = 20;
			$iK = 0;
			$classStr = "";
			//excel file name while downloading
			$excel_name = $_LANG_TEXT['rental_info_change_history'][$lang_code];

			?>
			<div class="btn_wrap right" style='margin-bottom:10px;'>
				<? $excel_down_url = $_www_server . "/stat/rental_info_change_history_excel.php?enc=" . ParamEnCoding($param); ?>
				<div class="right">
					<a href="#" id="rental_details_excel" class="btnexcel required-print-auth hide" onclick="getHTMLSplit('<?= $total ?>','<?= $excel_down_url ?>','<?= $excel_name ?>',this);"><?= $_LANG_TEXT["btnexceldownload"][$lang_code]; ?></a>
				</div>

				<div style='margin-right:10px; line-height:30px; ' class="right">
					Results : <span style='color:blue'><?= number_format($total) ?></span> /
					Records : <select name='paging' onchange="searchForm.submit();">
						<option value='20' <? if ($paging == '20') echo "selected"; ?>>20</option>
						<option value='40' <? if ($paging == '40') echo "selected"; ?>>40</option>
						<option value='60' <? if ($paging == '60') echo "selected"; ?>>60</option>
						<option value='80' <? if ($paging == '80') echo "selected"; ?>>80</option>
						<option value='100' <? if ($paging == '100') echo "selected"; ?>>100</option>
					</select>
				</div>

			</div>

		</form>

		<!--검색결과리스트-->
		<table class="list" style="margin-top:10px; ">
			<tr>
				<th class="num"><?= $_LANG_TEXT['numtext'][$lang_code] ?></th>
				<th class="center" style="width:100px"><? echo trsLang('소속구분','belongdivtext'); ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['nametext'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['belongtext'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['rentalItems'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['itemnumber'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['rentaldate'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['returndate'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['accessusertext'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['accessdatetext'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['process_type_text'][$lang_code] ?></th>
			</tr>
			<?php
			if ($result) {
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
					$cnt--;
					$iK++;

					$item_name = $row['item_name'];
					$item_mgt_number = $row['item_mgt_number'];
					$rent_date = $row['rent_date'];
					$return_date = $row['return_date'];
					$action = $row['action'];
					$history_date = $row['history_date'];
					$emp_no = $row['emp_no'];
					$emp_name = aes_256_dec($row['emp_name']);
					$user_name = aes_256_dec($row['user_name']);
					$user_name_en = $row['user_name_en'];
					$user_belong = $row['user_belong'];

					$rent_list_seq = $row['rent_list_seq'];
					$user_type = $row['user_type'];
					$str_user_type = $_CODE_V_USER_TYPE[$user_type];

					//not used
					$user_phone = $row['user_phone'];
					$user_company = $row['user_company'];
					$access_ip_addr = $row['access_ip_addr'];
					$access_date = $row['access_date'];
					$access_emp_seq = $row['access_emp_seq'];				
					$return_schedule_date = $row['return_schedule_date'];
					$user_dept = $row['user_dept'];
					$rent_purpose = $row['rent_purpose'];
					$rent_center_code = $row['rent_center_code'];
					$return_emp_seq = $row['return_emp_seq'];
					$create_date = $row['create_date'];
					$memo = $row['memo'];
					$user_agree_yn = $row['user_agree_yn'];
					$rnum = $row['rnum'];

					
					$formatted_rent_date = date('Y-m-d H:i', strtotime($rent_date));


					if (!empty($return_date) && $return_date !== 'null') {
						$formatted_return_date = date('Y-m-d H:i', strtotime($return_date));
					} else {
						$formatted_return_date = '';
					}
					if (!empty($history_date) && $history_date !== 'null') {
						$formatted_history_date = date('Y-m-d H:i', strtotime($history_date));
					} else {
						$formatted_history_date = '';
					}


															//phone
						if($_encryption_kind=="1"){

					
						$phone_no = $row['user_phone'];
						
					}else if($_encryption_kind=="2"){

						if($row['user_phone'] != ""){
							$phone_no = aes_256_dec($row['user_phone']);
						}
					}




			?>
					<tr>
						<td class="center" ><?= $no ?></td>
						<td class="center"  ><?= $str_user_type ?></td>
						<td class="center" ><?= $user_name ?></td>
						<td class="center" ><?php echo $user_belong ?></td>
						<td class="center" ><?= $item_name ?></td>
						<td class="center" ><?= $item_mgt_number ?></td>
						<td class="center" ><?= $formatted_rent_date ?></td>
						<td class="center" ><?= $formatted_return_date ?></td>
						<td class="center" ><?= $emp_name ?></td>
						<td class="center" ><?= $formatted_history_date ?></td>
						<td class="center" ><?= $action ?></td>




					</tr>

				<?php

					$no--;
				}
			}

			if ($result) sqlsrv_free_stmt($result);
			sqlsrv_close($wvcs_dbcon);
			if ($total < 1) {

				?>
				<tr>
					<td colspan="12" align='center'><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
				</tr>
			<?php
			}
			?>

		</table>

		<!--페이징-->
		<?php
		if ($total > 0) {
			$param_enc = ($param) ? "enc=" . ParamEnCoding($param) : "";
			print_pagelistNew3($page, $lists, $page_count, $param_enc, '', $total);
		}
		?>

	</div>
</div>

<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>
