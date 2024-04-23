<ul class="tab" style='margin-top:15px;'>
					<li class="on">
						<a href="#" onclick=""><?=$_LANG_TEXT['checkinfotext'][$lang_code]?></a>
						<div>
							<form name='frmVCS2' id='frmVCS2' method='POST' onsubmit="return false">
							<input type='hidden' id='proc' name='proc'>
							<input type='hidden' name='proc_name'>
							<input type='hidden' id='v_wvcs_seq' name='v_wvcs_seq' value='<?=$v_wvcs_seq?>'>
							<input type='hidden' id='apprv_yn' name='apprv_yn' value='<?=$apprv_yn?>'>
							<?if($device_gubun=='NOTEBOOK'){?>
								<table class="view">
								<tr>
									<th style='width:150px'><?=$_LANG_TEXT['checkdatetext'][$lang_code]?></th>
									<td style='width:300px'><?=$check_date?></td>
									<th class="line" style='width:150px'><?=$_LANG_TEXT['lastcheckdatetext'][$lang_code]?></th>
									<td ><?=$last_check_date?></td>
								</tr>
								<tr class="bg">
									<th><?=$_LANG_TEXT['devicegubuntext'][$lang_code]?></th>
									<td><?=$_CODE['asset_type'][$device_gubun]?><span class='blue'>(<?=$disk_cnt?>)</span></td>
									<th class="line"><?=$_LANG_TEXT['ostext'][$lang_code]?></th>
									<td><?=$os_ver_name?></td>
								</tr>
								<tr>
									<th><?=$_LANG_TEXT['modeltext'][$lang_code]?></th>
									<td><?=$model_name?></td>
									<th class="line"><?=$_LANG_TEXT['manufacturertext'][$lang_code]?></th>
									<td><?=$manufacturer?></td>
								</tr>
								<tr class="bg">
									<th><?=$_LANG_TEXT['ipaddresstext'][$lang_code]?></th>
									<td><?=$ip_addr?></td>
									<th class="line"><?=$_LANG_TEXT['macaddresstext'][$lang_code]?></th>
									<td><?=$mac_addr?></td>
								</tr>
								<tr>
									<th><?=$_LANG_TEXT['visitortext'][$lang_code]?></th>
									<td><?=$v_user_name_com?></td>
									<th class="line"><?=$_LANG_TEXT['checkgubuntext'][$lang_code]?></th>
									<td><?=$check_type?></td>
								</tr>
								<tr class="bg" >
									<th><?=$_LANG_TEXT['executives'][$lang_code]?> / <?=$_LANG_TEXT['depttext'][$lang_code]?></th>
									<td>
									<?if($_ck_user_level=="SECURITOR_S"){?>	
											<?=$mngr_name?> / <?=$mngr_dept?>
									<?}else{?>
										<input type='text' id='mngr_name' name='mngr_name' class='frm_input' style='width:80px' value='<?=$mngr_name?>'  maxlength="50"> / 
										<input type='text' id='mngr_dept' name='mngr_dept' class='frm_input' style='width:150px' value='<?=$mngr_dept?>'  maxlength="100">
									<?}?>
									</td>
									<th class="line"><?=$_LANG_TEXT['scancentertext'][$lang_code]?></th>
									<td>
									<?if($_ck_user_level=="SECURITOR_S"){?>	
											<?=$org_name?> <?=$scan_center_name?> 
									<?}else{?>

										<?
											$qry_params = array();
											$qry_label = QRY_COMMON_SCAN_CENTER_USE_ALL;
											$sql = query($qry_label,$qry_params);

											$result = sqlsrv_query($wvcs_dbcon, $sql);
										?>
											<select id='scan_center_code' name='scan_center_code' >
												<option value=''><?=$_LANG_TEXT['scancenterchoosetext'][$lang_code]?></option>
										<?
											while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
												
												$_org_name = $row['org_name'];
												$_scan_center_code = $row['scan_center_code'];
												$_scan_center_name = $row['scan_center_name'];
										?>		
												<option value='<?=$_scan_center_code?>' <?if($scan_center_code==$_scan_center_code) echo "selected";?>><?=$_org_name." ".$_scan_center_name?></option>
										<?	
											}
										?>
											</select>

									<?}?>
									</td>
								</tr>
								<tr >
									<th><?=$_LANG_TEXT['inlimitdatetext'][$lang_code]?></th>
									<td><?=$in_available_date?></td>
									<th class="line"><?=$_LANG_TEXT['indatetext'][$lang_code]?></th>
									<td><span id='in_date'><?=$in_date?></span></td>
								</tr>
								<tr class="bg">
									<th><?=$_LANG_TEXT['outdatetext'][$lang_code]?> </th>
									<td>
										<span id='out_date'><?=$out_date?></span>
									</td>
									<th class="line"><?=$_LANG_TEXT['progressstatustext'][$lang_code]?></th>
									<td><span id='vcs_status'><?=$str_vcs_status;?></span></td>
								</tr>
								<tr >
									<th><?=$_LANG_TEXT['checkapprovertext'][$lang_code]?> </th>
									<td>
										<span id='apprv_info'><?=$apprv_name?> <?if($apprv_name){ echo "(".$apprv_dt.")"; }?></span>
									</td>
									<th class="line"></th>
									<td></td>
								</tr>
								<tr class="bg">
									<th><?=$_LANG_TEXT['memotext'][$lang_code]?></th>
									<td colspan="3">
									<?if($_ck_user_level=="SECURITOR_S"){?>
										<?=$memo?>
									<?}else{?>
										<input type='text' id='memo' name='memo' class='frm_input' value='<?=$memo?>' style='width:90%'  maxlength="250">
									<?}?>
									</td>
								</tr>
								</table>
							<?}else{?>
								<table class="view">
								<tr>
									<th style='width:150px'><?=$_LANG_TEXT['checkdatetext'][$lang_code]?></th>
									<td style='width:350px'><?=$check_date?></td>
									<th class="line" style='width:150px'><?=$_LANG_TEXT['lastcheckdatetext'][$lang_code]?></th>
									<td ><?=$last_check_date?></td>
								</tr>
								<tr class="bg">
									<th><?=$_LANG_TEXT['devicegubuntext'][$lang_code]?></th>
									<td><?=$_CODE['asset_type'][$device_gubun]?><span class='blue'>(<?=$disk_cnt?>)</span></td>
									<th class="line">Device</th>
									<td><?=$os_ver_name?></td>
								</tr>
								<!-- Machine model/manufacturer
								<tr>
									<th><?=$_LANG_TEXT['modeltext'][$lang_code]?></th>
									<td><?=$model_name?></td>
									<th class="line"><?=$_LANG_TEXT['manufacturertext'][$lang_code]?></th>
									<td><?=$manufacturer?></td>
								</tr>
								-->
								<tr >
									<th><?=$_LANG_TEXT['visitortext'][$lang_code]?></th>
									<td><?=$v_user_name_com?></td>
									<th class="line"><?=$_LANG_TEXT['checkgubuntext'][$lang_code]?></th>
									<td><?=$check_type?></td>
								</tr>
								<tr  class="bg">
									<th><?=$_LANG_TEXT['executives'][$lang_code]?> / <?=$_LANG_TEXT['depttext'][$lang_code]?></th>
									<td>
									<?if($_ck_user_level=="SECURITOR_S"){?>	
											<?=$mngr_name?> / <?=$mngr_dept?>
									<?}else{?>
										<input type='text' id='mngr_name' name='mngr_name' class='frm_input' style='width:80px' value='<?=$mngr_name?>' maxlength="50"> / 
										<input type='text' id='mngr_dept' name='mngr_dept' class='frm_input' style='width:150px' value='<?=$mngr_dept?>' maxlength="100">
									<?}?>
									</td>
									<th class="line"><?=$_LANG_TEXT['scancentertext'][$lang_code]?></th>
									<td>
									<?if($_ck_user_level=="SECURITOR_S"){?>	
											<?=$org_name?> <?=$scan_center_name?> 
									<?}else{?>

										<?
											$qry_params = array();
											$qry_label = QRY_COMMON_SCAN_CENTER_USE_ALL;
											$sql = query($qry_label,$qry_params);

											$result = sqlsrv_query($wvcs_dbcon, $sql);
										?>
											<select id='scan_center_code' name='scan_center_code' >
												<option value=''><?=$_LANG_TEXT['scancenterchoosetext'][$lang_code]?></option>
										<?
											while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
												
												$_org_name = $row['org_name'];
												$_scan_center_code = $row['scan_center_code'];
												$_scan_center_name = $row['scan_center_name'];
										?>		
												<option value='<?=$_scan_center_code?>' <?if($scan_center_code==$_scan_center_code) echo "selected";?>><?=$_org_name." ".$_scan_center_name?></option>
										<?	
											}
										?>
											</select>

									<?}?>
									</td>
								</tr>
								<tr  >
									<th><?=$_LANG_TEXT['inlimitdatetext'][$lang_code]?></th>
									<td><span id='in_available_date'><?=$in_available_date?></span></td>
									<th class="line"><?=$_LANG_TEXT['indatetext'][$lang_code]?></th>
									<td><span id='in_date'><?=$in_date?></span></td>
								</tr>
								<tr class="bg">
									<th><?=$_LANG_TEXT['outdatetext'][$lang_code]?> </th>
									<td>
										<span id='out_date'><?=$out_date?></span>
									</td>
									<th class="line"><?=$_LANG_TEXT['progressstatustext'][$lang_code]?></th>
									<td><span id='vcs_status'><?=$str_vcs_status;?></span></td>
								</tr>
								<tr >
									<th><?=$_LANG_TEXT['checkapprovertext'][$lang_code]?> </th>
									<td>
										<span id='apprv_info'><?=$apprv_name?> <?if($apprv_name){ echo "(".$apprv_dt.")"; }?></span>
									</td>
									<th class="line"><?=$_LANG_TEXT['ipaddresstext'][$lang_code]?></th>
									<td><?=$ip_addr;?></td>
								</tr>
								<tr class="bg">
									<th><?=$_LANG_TEXT['memotext'][$lang_code]?></th>
									<td colspan="3">
									<?if($_ck_user_level=="SECURITOR_S"){?>
										<?=$memo?>
									<?}else{?>
										<input type='text' id='memo' name='memo' class='frm_input' value='<?=$memo?>' style='width:90%'  maxlength="250">
									<?}?>
									</td>
								</tr>
								</table>
							<?}	//if($device_gubun=='NOTEBOOK'){?>
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
						<a href="javascript:" onclick="LoadDiskInfo(<?=$v_wvcs_seq?>);"><?=$_LANG_TEXT['diskinfotext'][$lang_code]?></a>
						<div id='disk_info'></div>
					</li>
				</ul>