<?php
$page_name = "result_list";
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


//검색 로그 기록
$proc_name = $_POST['proc_name'];
if($proc_name != ""){
	$work_log_seq = WriteAdminActLog($proc_name,'SEARCH');
}
?>
<script language="javascript">
	$(function() {
		$("#start_date").datepicker(pickerOpts);
		$("#end_date").datepicker(pickerOpts);
	});
</script>
<div id="oper_list">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_result"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>

		<!--검색폼-->
		<form name="searchForm" action="<?php echo $_SERVER[PHP_SELF] ?>" method="POST">
			<input type='hidden' name='proc_name' id='proc_name'>
			<input type="hidden" name="page" value="">
			<table class="search">
				<tr>
					<th style='width:100px'><?= $_LANG_TEXT["inspection_date"][$lang_code]; ?></th>
					<td style='width:400px'>
						<input type="text" name="start_date" id="start_date" class="frm_input" placeholder="" style="width:100px" value="<?= $start_date ?>" maxlength="10"> ~
						<input type="text" name="end_date" id="end_date" class="frm_input" placeholder="" style="width:100px" value="<?= $end_date ?>" maxlength="10">
					</td>
					<th style='width:100px'><?= $_LANG_TEXT["inspection_center"][$lang_code]; ?></th>
					<td style='min-width:300px;'>
						<select name="searchopt" id="searchopt">
							<option value="" <?php if ($searchopt == "") {
																	echo ' selected="selected"';
																} ?>>
								<?= $_LANG_TEXT['scancenterchoosetext'][$lang_code] ?></option>
							<option value="EMP_NAME" <?php if ($searchopt == "EMP_NAME") {
																					echo ' selected="selected"';
																				} ?>>
								<?= $_LANG_TEXT['empnametext'][$lang_code] ?></option>
							<option value="EMP_NO" <?php if ($searchopt == "EMP_NO") {
																				echo ' selected="selected"';
																			} ?>>
								<?= $_LANG_TEXT['empnotext'][$lang_code] ?></option>
							<option value="IP" <?php if ($searchopt == "IP") {
																		echo ' selected="selected"';
																	} ?>>
								<?= $_LANG_TEXT['ipaddresstext'][$lang_code] ?></option>
						</select>
					</td>
				</tr>
<!-- 검사 장비 -->
				<tr>
					<th style='width:100px'><?= $_LANG_TEXT["inspection_equipment"][$lang_code]; ?></th>
					<td style='min-width:400px;'>
						<select name="searchopt" id="searchopt">
							<option value="" <?php if ($searchopt == "") {
																	echo ' selected="selected"';
																} ?>>
								<?= $_LANG_TEXT['devicegubunchoosetext'][$lang_code] ?></option>
							<option value="EMP_NAME" <?php if ($searchopt == "EMP_NAME") {
																					echo ' selected="selected"';
																				} ?>>
								<?= $_LANG_TEXT['empnametext'][$lang_code] ?></option>
							<option value="EMP_NO" <?php if ($searchopt == "EMP_NO") {
																				echo ' selected="selected"';
																			} ?>>
								<?= $_LANG_TEXT['empnotext'][$lang_code] ?></option>
							<option value="IP" <?php if ($searchopt == "IP") {
																		echo ' selected="selected"';
																	} ?>>
								<?= $_LANG_TEXT['ipaddresstext'][$lang_code] ?></option>
						</select>
	
						<span style='display:inline-block; margin:5px;'><?= $_LANG_TEXT["progresstext"][$lang_code]; ?></span>
						<select name="searchopt" id="searchopt">
							<option value="" <?php if ($searchopt == "") {
																	echo ' selected="selected"';
																} ?>>
								<?= $_LANG_TEXT['select_progress_status'][$lang_code] ?></option>
							<option value="EMP_NAME" <?php if ($searchopt == "EMP_NAME") {
																					echo ' selected="selected"';
																				} ?>>
								<?= $_LANG_TEXT['empnametext'][$lang_code] ?></option>
							<option value="EMP_NO" <?php if ($searchopt == "EMP_NO") {
																				echo ' selected="selected"';
																			} ?>>
								<?= $_LANG_TEXT['empnotext'][$lang_code] ?></option>
							<option value="IP" <?php if ($searchopt == "IP") {
																		echo ' selected="selected"';
																	} ?>>
								<?= $_LANG_TEXT['ipaddresstext'][$lang_code] ?></option>
						</select>

					</td>

					<th style='width:100px'><?= $_LANG_TEXT["check_results"][$lang_code]; ?></th>
					<td style='min-width:300px;'>
						<select name="searchopt" id="searchopt">
							<option value="" <?php if ($searchopt == "") {
																	echo ' selected="selected"';
																} ?>>
								<?= $_LANG_TEXT['m_result'][$lang_code] ?>1</option>
							<option value="EMP_NAME" <?php if ($searchopt == "EMP_NAME") {
																					echo ' selected="selected"';
																				} ?>>
								<?= $_LANG_TEXT['empnametext'][$lang_code] ?></option>
							<option value="EMP_NO" <?php if ($searchopt == "EMP_NO") {
																				echo ' selected="selected"';
																			} ?>>
								<?= $_LANG_TEXT['empnotext'][$lang_code] ?></option>
							<option value="IP" <?php if ($searchopt == "IP") {
																		echo ' selected="selected"';
																	} ?>>
								<?= $_LANG_TEXT['ipaddresstext'][$lang_code] ?></option>
						</select>


						<select name="searchopt" id="searchopt">
							<option value="" <?php if ($searchopt == "") {
																	echo ' selected="selected"';
																} ?>>
								<?= $_LANG_TEXT['m_result'][$lang_code] ?>2</option>
							<option value="EMP_NAME" <?php if ($searchopt == "EMP_NAME") {
																					echo ' selected="selected"';
																				} ?>>
								<?= $_LANG_TEXT['empnametext'][$lang_code] ?></option>
							<option value="EMP_NO" <?php if ($searchopt == "EMP_NO") {
																				echo ' selected="selected"';
																			} ?>>
								<?= $_LANG_TEXT['empnotext'][$lang_code] ?></option>
							<option value="IP" <?php if ($searchopt == "IP") {
																		echo ' selected="selected"';
																	} ?>>
								<?= $_LANG_TEXT['ipaddresstext'][$lang_code] ?></option>
						</select>

					</td>

				</tr>
				<!-- 검색 -->
				
				<tr>
					<th><?= $_LANG_TEXT['keywordsearchtext'][$lang_code] ?> </th>
					<td colspan="3">
						<select name="searchopt" id="searchopt">
							<option value="" <?php if ($searchopt == "") {
																	echo ' selected="selected"';
																} ?>>
								<?= $_LANG_TEXT['select_search_item'][$lang_code] ?></option>
							<option value="EMP_NAME" <?php if ($searchopt == "EMP_NAME") {
																					echo ' selected="selected"';
																				} ?>>
								<?= $_LANG_TEXT['empnametext'][$lang_code] ?></option>
							<option value="EMP_NO" <?php if ($searchopt == "EMP_NO") {
																				echo ' selected="selected"';
																			} ?>>
								<?= $_LANG_TEXT['empnotext'][$lang_code] ?></option>
							<option value="IP" <?php if ($searchopt == "IP") {
																		echo ' selected="selected"';
																	} ?>>
								<?= $_LANG_TEXT['ipaddresstext'][$lang_code] ?></option>
						</select>

						<input type="text" class="frm_input" style="width:50%" name="searchkey" id="searchkey" value="<?= $searchkey ?>" maxlength="50">
						<input type="submit" value="<?= $_LANG_TEXT['usersearchtext'][$lang_code] ?>" class="btn_submit" onclick="return SearchSubmit(document.searchForm);">
						<input type="button" value="<?= $_LANG_TEXT['userdetailsearchtext'][$lang_code] ?>" class="btn_submit_no_icon" onclick="$('#search_detail').toggle()">

						<!--상세검색-->
						<div id='search_detail' style='display:none;'>
							<? for ($i = 1; $i < 4; $i++) { ?>
								<div style='margin-top:5px;'>
									<select name="searchopt<? echo $i ?>" id="searchopt<? echo $i ?>">
										<option value="" <?php if ($searchopt == "") {
																				echo ' selected="selected"';
																			} ?>>
											<?= $_LANG_TEXT['select_search_item'][$lang_code] ?></option>
										<option value="EMP_NAME" <?php if ($searchopt == "EMP_NAME") {
																								echo ' selected="selected"';
																							} ?>>
											<?= $_LANG_TEXT['empnametext'][$lang_code] ?></option>
										<option value="EMP_NO" <?php if ($searchopt == "EMP_NO") {
																							echo ' selected="selected"';
																						} ?>>
											<?= $_LANG_TEXT['empnotext'][$lang_code] ?></option>
										<option value="IP" <?php if ($searchopt == "IP") {
																					echo ' selected="selected"';
																				} ?>>
											<?= $_LANG_TEXT['ipaddresstext'][$lang_code] ?></option>
									</select>
									<input style="width:50%" type="text" class="frm_input" name="searchkey<? echo $i ?>" id="searchkey<? echo $i ?>" maxlength="50">
									<select name="searchandor<? echo $i ?>" id="searchandor<? echo $i ?>">
										<option value='AND'>AND</option>
										<option value='OR'>OR</option>
									</select>
								</div>
							<? } ?>
						</div>
					</td>
				</tr>
			</table>


		</form>

		<div class="btn_wrap" style='margin-bottom:10px;'>
			<? $excel_down_url = $_www_server . "/stat/file_import_history_excel.php?enc=" . ParamEnCoding($param); ?>
			<div class="right">
				<a href="#" id="james2" class="btnexcel required-print-auth hide" onclick="getHTMLSplit('<?= $total ?>','<?= $excel_down_url ?>',this);"><?= $_LANG_TEXT["btnexceldownload"][$lang_code]; ?></a>
			</div>
		</div>
		<!--  검색결과리스트-->
			<div style='line-height:30px;'>
				<div style='float:left'>
					Results : <span style='color:blue'><?=number_format($total)?></span> / 
					Records : <select name='paging' onchange="searchForm.submit();">
						<option value='20' <?if($paging=='20') echo "selected";?>>20</option>
						<option value='40' <?if($paging=='40') echo "selected";?>>40</option>
						<option value='60' <?if($paging=='60') echo "selected";?>>60</option>
						<option value='80' <?if($paging=='80') echo "selected";?>>80</option>
						<option value='100' <?if($paging=='100') echo "selected";?>>100</option>
					</select>
				</div>
				<div style='float:right'>
					<?if(in_array("BAD_EXT",$_CODE_INSPECT_OPTION)){?>
					<img src="<? echo $_www_server?>/images/b_clean.png"> <? echo trsLang('위변조의심','suspectforgerytext');?>
					<?}?>
					<?if(in_array("VIRUS",$_CODE_INSPECT_OPTION)){?>
					<img src="<? echo $_www_server?>/images/v_clean.png"> <? echo $_LANG_TEXT["viruscleantext"][$lang_code];?>
					<?}?>
					<?if(in_array("WEAK",$_CODE_INSPECT_OPTION)){?>
					<img src="<? echo $_www_server?>/images/w_clean.png"> <? echo $_LANG_TEXT["weaknesscleantext"][$lang_code];?>
					<?}?>
				</div>
			</div>
		<!--  -->

			

		
	


		<!--검색결과리스트-->
		<table class="list" style="margin-top:10px; ">
			<tr>
				<th class="num"><?= $_LANG_TEXT['numtext'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['m_visitor'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['belongtext'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['inspection_center'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['inspection_equipment'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['modeltext'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['serialnumber'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['inspection_datetext'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['progresstext'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['m_result'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['number_inspection_files'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['number_import_files'][$lang_code] ?></th>

			</tr>
			<?php

			//**유지보수 관리자아이디(dptadmin) 숨김처리
			$search_sql .= " and emp_no != 'dptadmin' ";

			if ($start_date != "" && $end_date != "") {
				$search_sql .= " AND login_dt between '$start_date 00:00:00.000' and '$end_date 23:59:59.999' ";
			}

			if ($searchkey != "") {

				if ($searchopt == "EMP_NAME") {

					$search_sql .= " and emp_name  = '".aes_256_enc($searchkey)."' ";
				} else if ($searchopt == "EMP_NO") {

					$search_sql .= " and emp_no like '%$searchkey%' ";
				} else if ($searchopt == "IP") {

					$search_sql .= " and ip_addr like '%$searchkey%' ";
				}
			}


			// for test
			$no = 1;

			if ($no == 1) {
				$str_memo = "메모내용이 말풍";
			} else {
				$str_memo = "";
			}


			?>
			<!-- for test -->
			<tr>

				<td>1</td>
				<td>홍길동</td>
				<td>010 0000 0000</td>
				<td>dataprotec</td>
				<td>유지보수</td>
				<td>2023/09/09 10:00</td>
				<td>sn1231</td>
				<td>정보보안</td>
				<td><a class='text_link' href="./check_result_details.php?enc=<?= $param_enc ?>">반입완료</a></td>
				<td>외부기기</td>
				<td>외부기기</td>
				<td>외부기기</td>


			</tr>

			<?php

			// 		$no--;
			// 	}

			// }

			if ($result) sqlsrv_free_stmt($result);
			sqlsrv_close($wvcs_dbcon);
			//	if($total < 1) {

			?>
			<!-- <tr>
				<td colspan="12" align='center'><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
			</tr> -->
			<?php
			//}
			?>

		</table>



		<!--페이징-->
		<?php
		// if($total > 0) {
		// 	$param_enc = ($param)? "enc=".ParamEnCoding($param) : "";
		// 	print_pagelistNew3($page, $lists, $page_count, $param_enc, '', $total );
		// }
		?>
		<!-- </table> -->


	</div>
</div>
<!--메모전송폼-->
<form id='frmMemo' method='post' action=''></form>
<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>