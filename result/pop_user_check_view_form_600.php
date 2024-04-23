<ul class="tab" style='margin-top:15px;'>
					<li class="on">
						<a href="#" onclick=""><?=$_LANG_TEXT['checkinfotext'][$lang_code]?></a>
						<div>
							<form name='frmVCS2' id='frmVCS2' method='POST' onsubmit="return false;">
								<input type='hidden' id='proc' name='proc'>
								<input type='hidden' name='proc_name'>
								<input type='hidden' id='v_wvcs_seq' name='v_wvcs_seq' value='<?=$v_wvcs_seq?>'>
								<input type='hidden' id='apprv_yn' name='apprv_yn' value='<?=$apprv_yn?>'>
								<input type='hidden' name='mngr_name' value='<?= $mngr_name ?>'>
								<input type='hidden' name='mngr_dept' value='<?= $mngr_dept ?>'>
								<input type='hidden' name='scan_center_code' value='<?= $scan_center_code ?>'>
								<table class="view">
									<tr>
										<th style='width:150px'><?= $_LANG_TEXT['checkdatetext'][$lang_code] ?></th>
										<td style='width:350px'><?= $check_date ?></td>
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
									<tr>
										<th><?= $_LANG_TEXT['executives'][$lang_code] ?></th>
										<td>
											<?= $mngr_name ?> 
										</td>
										<th class="line"><? echo trsLang('임직원 소속','employee_affiliation');?></th>
										<td>
											<?= $mngr_dept ?>
										</td>
									</tr>
									<tr  class="bg">
										<th><?= $_LANG_TEXT['progressstatustext'][$lang_code] ?></th>
										<td><span id='vcs_status'><?= $str_vcs_status; ?></span></td>
										<th class="line">
											<?= $_LANG_TEXT['scanfilecount'][$lang_code] ?>
										</th>
										<td>
											<?= $vacc_scan_count; ?>
											<? if ($_P_CHECK_FILE_SEND_TYPE != "N") { ?>
												<span>( <? echo trsLang('반입파일수', 'importfilecount'); ?> : <a href='javascript:void(0)' onClick="return popUserInFileList('<?= $v_wvcs_seq ?>','USER_FILE_LIST');"><? echo number_format($import_file_cnt); ?> )</span>
											<? } ?>
										</td>
									</tr>
									<tr >
										<th class="line"><?= $_LANG_TEXT['ipaddresstext'][$lang_code] ?></th>
										<td><?= $ip_addr; ?></td>
										
										<th class="line" style="width:150px"><?= $_LANG_TEXT['scancentertext'][$lang_code] ?></th>
										<td>
											<?= $scan_center_name ?>
										</td>
									</tr>
									<tr class="bg">
										<th><?= $_LANG_TEXT['memotext'][$lang_code] ?></th>
										<td colspan="3">
											<? if ($_ck_user_level == "SECURITOR_S") { ?>
												<?= $memo ?>
											<? } else { ?>
												<input type='text' id='memo' name='memo' class='frm_input' value='<?= $memo ?>' style='width:90%' maxlength="250">
											<? } ?>
										</td>
									</tr>
											<tr >
										<th class="line"><?= trsLang('PE 제작','madeby_pe') ?></th>
										<td  colspan="3"><?= $make_winpe; ?></td>
										
									
									</tr>
								</table>

							</form>
						</div>	
						<div class="btn_wrap">
							<div class="right">
							<?if($_ck_user_level=="SECURITOR_S"){?>	

							<?}else{?>
								<? if($file_send_status=="1" && $file_delete_flag=="0"){	// ���ϴٿ�ε�?>
									<a href="javascript:void(0)" onclick="fileDownLoad('<? echo $v_wvcs_seq?>')" class="btn bg-blue"><?= $_LANG_TEXT["fileDownloadText"][$lang_code]; ?></a>
								<?}?>	
								<a href="#" onClick="return ResultSubmit2('UPDATE')" class="btn"><?=$_LANG_TEXT['btnsave'][$lang_code]?></a>
							<?}?>
								
							</div>
						</div>
					</li>
					<li>
						<a href="javascript:void(0)" onclick="LoadDiskInfo(<?=$v_wvcs_seq?>);"><?=$_LANG_TEXT['diskinfotext'][$lang_code]?></a>
						<div id='disk_info' style='margin-bottom:50px;'></div>
					</li>
					<li >
						<a href="javascript:void(0)" onclick="LoadScanTimeLog(<?= $v_wvcs_seq ?>);"><?= $_LANG_TEXT['scantime_log'][$lang_code] ?></a>
						<div id='scan_time_log' style='margin-bottom:50px;'></div>
					</li>
				</ul>