			<ul class="tab">

				<li class="on">
					<a href="#" onclick=""><?= $_LANG_TEXT['checkinfotext'][$lang_code] ?></a>
					<div>
						<form name='frmVCS' id='frmVCS' method='POST' onsubmit="return false">
							<input type='hidden' id='v_wvcs_seq' name='v_wvcs_seq' value='<?= $v_wvcs_seq ?>'>
							<input type='hidden' name='mngr_name' value='<?= $mngr_name ?>'>
							<input type='hidden' name='mngr_dept' value='<?= $mngr_dept ?>'>
							<input type='hidden' name='scan_center_code' value='<?= $scan_center_code ?>'>
							<input type='hidden' name='proc' id='proc'>
							<input type='hidden' name='proc_name' id='proc_name'>
							<table class="view">
								<tr>
									<th style='width:150px'><?= $_LANG_TEXT['checkdatetext'][$lang_code] ?></th>
									<td style='width:350px'><?= $check_date ?> (<? echo $v_wvcs_seq?>)</td>
									<th class="line" style="width:150px"><?= $_LANG_TEXT['devicegubuntext'][$lang_code] ?></th>
									<td><?= $os_ver_name ?></td>
								</tr>
								<tr class="bg">
									<th><?= $_LANG_TEXT['visitortext'][$lang_code] ?></th>
									<td><?= $raw_v_user_name; ?></td>
									<th class="line" style="width:150px"><?= $_LANG_TEXT['belongtext'][$lang_code] ?></th>
									<td>
										<?= $v_user_belong ?>
									</td>
								</tr>
								<tr class="bg">
									<th><?= $_LANG_TEXT['executives'][$lang_code] ?></th>
									<td>
										<?= $mngr_name ?> 
									</td>
									<th class="line"><? echo trsLang('임직원 소속','employee_affiliation');?></th>
									<td>
										<?= $mngr_dept ?>
									</td>
								</tr>
								<tr>
									<th><?= $_LANG_TEXT['progressstatustext'][$lang_code] ?></th>
									<td><span id='vcs_status'><?= $str_vcs_status; ?></span></td>
									<th class="line">
										<? echo trsLang('방문목적','purpose_visit'); ?>
									</th>
									<td>
										<? echo $v_purpose;?>
									</td>
								</tr>
								<tr class="bg">
									<th class="line"><?= $_LANG_TEXT['ipaddresstext'][$lang_code] ?></th>
									<td><?= $ip_addr; ?></td>
									
									<th class="line" style="width:150px"><?= $_LANG_TEXT['scancentertext'][$lang_code] ?></th>
									<td>
										<?= $scan_center_name ?>
									</td>
								</tr>
									<tr>
									<th>USB <? echo trsLang('관리번호','managenumber');?></th>
									<td>
										<?= $label_value ?>
									</td>
									<th class="line" >USB instance path</th>
									<td>
										<?= $copy_device_instance_path ?>
									</td>
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
								<!-- PE 제작 -->
								<tr>
									<th><?= $_LANG_TEXT['madeby_pe'][$lang_code] ?></th>
									<td><? echo $make_winpe ?></td>
									<th class="line" ><?= $_LANG_TEXT['carryinmediatext'][$lang_code] ?></th>
									<td><? echo $str_device_in_flag ?></td>
								</tr>
							</table>
						</form>
					</div>
					<div class="btn_wrap">
						<div class="left display-none" >
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
							<? } else {?>
								<? if($file_send_status=="1" && $file_delete_flag=="0"){	// 파일다운로드?>
									<a  href="javascript:void(0)" onclick="fileDownLoad('<? echo $v_wvcs_seq?>')" class="btn bg-blue required-print-auth hide"><?= $_LANG_TEXT["fileDownloadText"][$lang_code]; ?></a>
								<?}?>	
								<a href="./result_list.php?enc=<?= ParamEnCoding("asset_type=" . $asset_type) ?>" class="btn"><?= $_LANG_TEXT['btnlist'][$lang_code] ?></a>
								<a href="javascript:void(0)" onClick="return ResultSubmit('UPDATE')" class="btn required-update-auth hide"><?= $_LANG_TEXT['btnsave'][$lang_code] ?></a>
								<a href="javascript:void(0)" onClick="return ResultSubmit('DELETE')" class="btn required-delete-auth hide"><?= $_LANG_TEXT['btndelete'][$lang_code] ?></a>
							<? } ?>
						</div>
					</div>
				</li>
				<li>
					<a href="javascript:" onclick="LoadDiskInfo(<?= $v_wvcs_seq ?>);"><?= $_LANG_TEXT['diskinfotext'][$lang_code] ?></a>
					<div id='disk_info'></div>
				</li>
				<li>
					<a href="javascript:" onclick="LoadScanTimeLog(<?= $v_wvcs_seq ?>);"><?= $_LANG_TEXT['scantime_log'][$lang_code] ?></a>
					<div id='scan_time_log'></div>
				
				</li>
			</ul>




				<!-- popup -->
				<?php $downloading_text=$_LANG_TEXT['downloading_text'][$lang_code] ; ?>
				<div class=" file_download_modal" id="progressPopup" style="display: none;">
				<div class="inner_modal">
					<div class="inner_modal_theme">
						
						<h3><?=$downloading_text?>...</h3>
					</div>
					<div class="progress_box">
						
						<div class="progress">
							  <progress  id="downloadProgress" max="100" value="0"></progress>

						</div>
					</div>

			   </div>

        </div>
