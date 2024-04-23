			<ul class="tab">

				<li class="on">
					<a href="#" onclick=""><?= $_LANG_TEXT['checkinfotext'][$lang_code] ?></a>
					<div>
						<form name='frmVCS' id='frmVCS' method='POST' onsubmit="return false">
							<input type='hidden' id='v_wvcs_seq' name='v_wvcs_seq' value='<?= $v_wvcs_seq ?>'>
							<input type='hidden' id='proc' name='proc'>
							<input type='hidden' name='proc_name' id='proc_name'>
							<table class="view">
								<tr>
									<th style='width:150px'><?= $_LANG_TEXT['checkdatetext'][$lang_code] ?></th>
									<td style='width:350px'><?= $check_date ?></td>
									<th class="line" style='width:150px'><?= $_LANG_TEXT['lastcheckdatetext'][$lang_code] ?></th>
									<td><?= $last_check_date ?></td>
								</tr>
								<tr class="bg">
									<th><?= $_LANG_TEXT['devicegubuntext'][$lang_code] ?></th>
									<td><?= $_CODE['asset_type'][$device_gubun] ?><span class='blue'>(<?= $disk_cnt ?>)</span></td>
									<th class="line">Device</th>
									<td><?= $os_ver_name ?></td>
								</tr>
								<tr>
									<th><?= $_LANG_TEXT['visitortext'][$lang_code] ?></th>
									<td><?= $v_user_name_com ?></td>
									<th class="line"><?= $_LANG_TEXT['checkgubuntext'][$lang_code] ?></th>
									<td><?= $check_type ?></td>
								</tr>
								<tr class="bg">
									<th><?= $_LANG_TEXT['executives'][$lang_code] ?> / <?= $_LANG_TEXT['depttext'][$lang_code] ?></th>
									<td>
										<? if ($_ck_user_level == "SECURITOR_S") { ?>
											<?= $mngr_name ?> / <?= $mngr_dept ?>
										<? } else { ?>
											<input type='text' id='mngr_name' name='mngr_name' class='frm_input' style='width:80px' value='<?= $mngr_name ?>' maxlength="50"> /
											<input type='text' id='mngr_dept' name='mngr_dept' class='frm_input' style='width:150px' value='<?= $mngr_dept ?>' maxlength="100">
										<? } ?>
									</td>
									<th class="line"><?= $_LANG_TEXT['scancentertext'][$lang_code] ?></th>
									<td>
										<? if ($_ck_user_level == "SECURITOR_S") { ?>
											<?= $org_name ?> <?= $scan_center_name ?>
										<? } else { ?>

											<?
											$qry_params = array();
											$qry_label = QRY_COMMON_SCAN_CENTER_USE_ALL;
											$sql = query($qry_label, $qry_params);

											$result = sqlsrv_query($wvcs_dbcon, $sql);
											?>
											<select id='scan_center_code' name='scan_center_code'>
												<option value=''><?= $_LANG_TEXT['scancenterchoosetext'][$lang_code] ?></option>
												<?
												while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

													$_org_name = $row['org_name'];
													$_scan_center_code = $row['scan_center_code'];
													$_scan_center_name = $row['scan_center_name'];
												?>
													<option value='<?= $_scan_center_code ?>' <? if ($scan_center_code == $_scan_center_code) echo "selected"; ?>><?= $_org_name . " " . $_scan_center_name ?></option>
												<?
												}
												?>
											</select>

										<? } ?>
									</td>
								</tr>
								<tr>
									<th><?= $_LANG_TEXT['progressstatustext'][$lang_code] ?></th>
									<td><span id='vcs_status'><?= $str_vcs_status; ?></span></td>
									<th class="line"><?= $_LANG_TEXT['inlimitdatetext'][$lang_code] ?></th>
									<td><span id='in_available_date'><?= $in_available_date ?></span></td>
								</tr>
								<tr class="bg">
									<th><?= $_LANG_TEXT['indatetext'][$lang_code] ?></th>
									<td><span id='in_date'><?= $in_date ?></span></td>
									<th class="line"><?= $_LANG_TEXT['outdatetext'][$lang_code] ?> </th>
									<td>
										<span id='out_date'><?= $out_date ?></span>
									</td>
								</tr>
								<tr>
									<th><?= $_LANG_TEXT['checkapprovertext'][$lang_code] ?> </th>
									<td>
										<span id='apprv_info'><?= $apprv_name ?> <? if ($apprv_name) {
																																echo "(" . $apprv_dt . ")";
																															} ?></span>
									</td>
									<th class="line">
										<?= $_LANG_TEXT['scanfilecount'][$lang_code] ?>
									</th>
									<td>
										<?= $vacc_scan_count; ?>
										<? if ($_P_CHECK_FILE_SEND_TYPE != "N") { ?>
											<span>( <? echo trsLang('�������ϼ�', 'importfilecount'); ?> : <a href='javascript:void(0)' onClick="return popUserInFileList('<?= $v_wvcs_seq ?>','USER_FILE_LIST');"><? echo number_format($import_file_cnt); ?> )</span>
										<? } ?>
									</td>
								</tr>
								<tr class="bg">
									<th class="line"><?= $_LANG_TEXT['ipaddresstext'][$lang_code] ?></th>
									<td><?= $ip_addr; ?></td>
									<th class="line"></th>
									<td></td>
								</tr>
								<tr>
									<th><?= $_LANG_TEXT['memotext'][$lang_code] ?></th>
									<td colspan="3">
										<? if ($_ck_user_level == "SECURITOR_S") { ?>
											<?= $memo ?>
										<? } else { ?>
											<input type='text' id='memo' name='memo' class='frm_input' value='<?= $memo ?>' style='width:90%' maxlength="250">
										<? } ?>
									</td>
								</tr>
							</table>
						</form>
					</div>
				</li>
				<li>
					<a href="javascript:" onclick="LoadDiskInfo(<?= $v_wvcs_seq ?>);"><?= $_LANG_TEXT['diskinfotext'][$lang_code] ?></a>
					<div id='disk_info'></div>
				</li>
				<li>
					<span style='left:280px;'>Barcode : <?= $barcode ?></span>
				</li>
			</ul>

		<div class="btn_wrap">
			<div class="left">
				<a href="<? if (empty($prev_v_wvcs_seq)) { ?>javASCript:alert(nodatatext[lang_code])<? } else {
																																														echo $prev_url . "?enc=" . ParamEnCoding("v_wvcs_seq=" . $prev_v_wvcs_seq . ($param ? "&" : "") . $param);
																																													} ?>" class="btn" id='btnPrev'><?= $_LANG_TEXT["btnprev"][$lang_code]; ?></a>
				<a href="<? if (empty($next_v_wvcs_seq)) { ?>javASCript:alert(nodatatext[lang_code])<? } else {
																																														echo $next_url . "?enc=" . ParamEnCoding("v_wvcs_seq=" . $next_v_wvcs_seq . ($param ? "&" : "") . $param);
																																													} ?>" class="btn" id='btnNext'><?= $_LANG_TEXT["btnnext"][$lang_code]; ?><a>
			</div>
			<div class="right">
				<? if ($_ck_user_level == "SECURITOR_S") { ?>
					<a href="./result_list.php?enc=<?= ParamEnCoding("asset_type=" . $asset_type) ?>" class="btn"><?= $_LANG_TEXT['btnlist'][$lang_code] ?></a>
				<? } else {

					if ($in_date == "") {
						$btnintext =  $_LANG_TEXT['btnin'][$lang_code];
					} else {
						$btnintext =  $_LANG_TEXT['btnincancel'][$lang_code];
					}

					if ($out_date == "") {
						$btnouttext =  $_LANG_TEXT['btnout'][$lang_code];
					} else {
						$btnouttext =  $_LANG_TEXT['btnoutcancel'][$lang_code];
					}
				?>
					<a href="#" id='btnApprvIn' onClick="return ResultCheckInSubmit()" class="btn2 <? echo $_CODE_CSS['display_inout_info']; ?>"><?= $btnintext ?></a>
					<a href="#" id='btnApprvOut' onClick="return ResultCheckOutSubmit()" class="btn2 <? echo $_CODE_CSS['display_inout_info']; ?>"><?= $btnouttext ?></a>
					<a href="#" onClick="popVcsScanResultPrint(<?= $v_wvcs_seq ?>)" class="btn gray"><?= $_LANG_TEXT['btnscanresultprint'][$lang_code] ?></a>
					<a href="./result_list.php?enc=<?= ParamEnCoding("asset_type=" . $asset_type) ?>" class="btn"><?= $_LANG_TEXT['btnlist'][$lang_code] ?></a>
					<a href="#" onClick="return ResultSubmit('UPDATE')" class="btn"><?= $_LANG_TEXT['btnsave'][$lang_code] ?></a>
					<a href="#" onClick="return ResultSubmit('DELETE')" class="btn"><?= $_LANG_TEXT['btndelete'][$lang_code] ?></a>
				<? } ?>
			</div>
		</div>
