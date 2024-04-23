<?php
if(!wvcs_dbcon) return;

$qry_params = array();
$qry_label = QRY_POLICY;
$sql = query($qry_label,$qry_params);

$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = @sqlsrv_query( $wvcs_dbcon, $sql , $params, $options );

if($result){

	$row_count = sqlsrv_num_rows( $result );

	$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
	
	if($row_count > 0){
		
		$_policy_seq = $row['policy_seq'];
		$_admin_pwd_change_term = $row['admin_pwd_change_term'];
		$_connection_yn = $row['connection_yn'];
		$_scrlock_yn = $row['scrlock_yn'];
		$_pc_checkin_term = $row['checkin_pc_term'];
		$_kiosk_checkin_term = $row['checkin_kiosk_term'];
		$_windows_update_term = $row['windows_update_term'];
		$_windows_update_yn = $row['windows_update_yn'];
		$_window_weakness_check_yn = $row['window_weakness_check_yn'];
		$_vaccine_check_yn = $row['vaccine_check_yn'];
		$_vacc_patch_term = $row['vacc_patch_term'];
		$_vacc_scan_type = $row['vacc_scan_type'];
		$_scrlock_warn_comment = $row['scrlock_warn_comment'];
		$_app_update_server = $row['app_update_server'];
		$_app_api_server = $row['app_api_server'];
		$_ftp_type = $row['ftp_type'];
		$_ftp_server = $row['ftp_server'];
		$_ftp_port = $row['ftp_port'];
		$_web_type = $row['web_type'];
		$_web_server = $row['web_server'];
		$_web_port = $row['web_port'];
		$_mail_server = $row['mail_server'];
		$_mail_port = $row['mail_port'];
		$_mail_id = $row['mail_id'];
		$_mail_pwd = $row['mail_pwd'];
		$_mail_type = $row['mail_type'];
		$_sms_type = $row['sms_type'];
		$_sms_server = $row['sms_server'];
		$_sms_port = $row['sms_port'];
		$_sms_id = $row['sms_id'];
		$_sms_pwd = $row['sms_pwd'];
		$_sms_db = $row['sms_db'];
		$_sms_url = $row['sms_url'];
		$_sms_table = $row['sms_table'];
		$_sms_send_telno = $row['sms_send_telno'];
		$_otp_yn = $row['otp_yn'];
		$_data_keep_day = $row['data_keep_day'];

		$_checkin_kiosk_in_type = $row['checkin_kiosk_in_type'];
		if($_checkin_kiosk_in_type=="") $_checkin_kiosk_in_type = "DEVICE";

		$_checkin_file_send_type = $row['checkin_file_send_type'];
		if($_checkin_file_send_type=="") $_checkin_file_send_type = "N";

		$_db_encription_kind = $row['db_encription_kind'];
		if($_db_encription_kind=="") $_db_encription_kind = "2";	//AES_Rijndael_Encript

		$_db_encription_flag = $row['db_encription_flag'];
		if($_db_encription_flag=="") $db_encription_flag = "1";		//전화번호,이메일 암호화

		// james
		$login_attempt_cnt = $row['login_attempt_cnt'];
		if($login_attempt_cnt=="") $login_attempt_cnt = "0";
		$login_ip_limit_yn = $row['login_ip_limit_yn'];
		if($login_ip_limit_yn=="") $login_ip_limit_yn = "N";

		$_file_scan_yn = $row['file_scan_yn'];
		if($_file_scan_yn=="") $_file_scan_yn = "N";

		$v3_use_yn = $row[v3_use_yn];
		if($v3_use_yn=="") $v3_use_yn = "Y";
		$eset_use_yn = $row[eset_use_yn];
		if($eset_use_yn=="") $eset_use_yn = "N";


		$_file_scan_device = $row[file_scan_device];
		if($_file_scan_device=="") $_file_scan_device = "ALL";
	
		$_checkin_file_send_device = $row[checkin_file_send_device];
		if($_checkin_file_send_device=="") $_checkin_file_send_device = "ALL";

		$_kiosk_data_delete_day = $row['kiosk_data_delete_day'];
		if($_kiosk_data_delete_day=="") $_kiosk_data_delete_day= 0;

		$_visit_checkout_batch_yn = $row['visit_checkout_batch_yn'];
		if($_visit_checkout_batch_yn=="") $_visit_checkout_batch_yn = "N";


	}else{
		$nopolicymsg = $_LANG_TEXT["nopolicymsg"][$lang_code];
	}

}
?>
<script>
	$(function(){
		bindPolicyEvent();
	});
</script>
<div id="policy">
	<div class="outline">
		<div class="container">

			<div id="tit_area">
				<div class="tit_line">
					 <h1><span id='page_title'><?=$_LANG_TEXT["m_manage_policy"][$lang_code];?></span></h1>
				</div>
				<span class="line"></span>
			</div>

			<!--tab-->
			<ul class="tab">
				<li class="on">
					<a href="<? echo $_www_server?>/manage/policy.php" ><? echo trsLang('전체설정','totalconfig');?></a>
				</li>
				<li>
					<a href="<? echo $_www_server?>/manage/policy_file_import.php"><? echo trsLang('파일반입예외설정','fileimportpolicy');?></a>
				</li>
			</ul>			
			<form name='frmPolicy' id='frmPolicy' method='post'>
				<input type='hidden' name='policy_seq' id='policy_seq' value='<?=$_policy_seq?>'>
				<input type='hidden' name='proc_name' id='proc_name'>
				<input type='hidden' name='proc' id='proc'>
				<table class="view display-none" style='margin-top:0px'>
					<tr>
						<th style='min-width:170px;width:170px'><?=$_LANG_TEXT['windowsupdateyntext'][$lang_code]?></th>
						<td style='min-width:200px;width:200px'>
							<select name='win_update_yn' id='win_update_yn'>
								<option value='Y' <?=$_windows_update_yn=="Y" ? "selected" : ""?>>
									<?=$_LANG_TEXT['yestext'][$lang_code]?></option>
								<option value='N' <?=$_windows_update_yn=="N" ? "selected" : ""?>><?=$_LANG_TEXT['notext'][$lang_code]?>
								</option>
							</select>
						</td>
						<th style='min-width:160px;width:160px' class="line">
							<?=$_LANG_TEXT['windowweaknesscheckyntext'][$lang_code]?></th>
						<td>
							<select name='win_weak_chk_yn' id='win_weak_chk_yn'>
								<option value='Y' <?=$_window_weakness_check_yn=="Y" ? "selected" : ""?>>
									<?=$_LANG_TEXT['yestext'][$lang_code]?></option>
								<option value='N' <?=$_window_weakness_check_yn=="N" ? "selected" : ""?>>
									<?=$_LANG_TEXT['notext'][$lang_code]?></option>
							</select>
							<button class='btn'
								onclick="location.href='<?=$_www_server?>/manage/policy_weakness.php';return false;"><?=$_LANG_TEXT['btnsetcheckitem'][$lang_code]?></button>
						</td>
					</tr>
					<tr class="bg">
						<th><?=$_LANG_TEXT['windowsupdatevaliditytext'][$lang_code]?></th>
						<td>
							<input type='text' name='win_update_term' id='win_update_term' class="frm_input" style="width:50px"
								value='<?=$_windows_update_term?>' maxlength="3" onkeyup='onlyNumber(this)'>
							<?=$_LANG_TEXT['daytext'][$lang_code]?>
						</td>
						<th class="line"><?=$_LANG_TEXT['vaccineupdatevaliditytext'][$lang_code]?></th>
						<td>
							<input type='text' name='vacc_patch_term' id='vacc_patch_term' class="frm_input" style="width:50px"
								value='<?=$_vacc_patch_term?>' maxlength="3" onkeyup='onlyNumber(this)'>
							<?=$_LANG_TEXT['daytext'][$lang_code]?>
						</td>
					</tr>
					<tr class="bg">
						<th class="line"><?=trsLang('저장매체반입형태','storagemediaintypetext')?></th>
						<td colspan='3'>
							<select id='checkin_kiosk_in_type' name='checkin_kiosk_in_type'>
								<option value='DEVICE' <?if($_checkin_kiosk_in_type=="DEVICE" ) echo "selected" ;?>
									><?=trsLang('매체반입','storagemediaintext')?></option>
								<option value='FILE' <?if($_checkin_kiosk_in_type=="FILE" ) echo "selected" ;?>
									><?=trsLang('파일만반입','onlyfileintext')?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="line"><?=$_LANG_TEXT['storagedevicetext'][$lang_code]?>
							<?=$_LANG_TEXT['checkinavailabledatetext'][$lang_code]?></th>
						<td>
							<input type='text' name='kiosk_chkin_availabled_term' id='kiosk_chkin_availabled_term' name=""
								class="frm_input" style="width:50px" value='<?=$_kiosk_checkin_term?>' maxlength="5"
								onkeyup='onlyNumber(this)'> <?=$_LANG_TEXT['hourtext'][$lang_code]?>
						</td>
						<th class="line"><?=$_LANG_TEXT['laptoptext'][$lang_code]?>
							<?=$_LANG_TEXT['checkinavailabledatetext'][$lang_code]?></th>
						<td>
							<input type='text' name='pc_chkin_availabled_term' id='pc_chkin_availabled_term' name="" class="frm_input"
								style="width:50px" value='<?=$_pc_checkin_term?>' maxlength="5" onkeyup='onlyNumber(this)'>
							<?=$_LANG_TEXT['hourtext'][$lang_code]?>
						</td>
					</tr>
					<tr class="bg">
						<th><?=$_LANG_TEXT['screenlockyntext'][$lang_code]?></th>
						<td>
							<select name='scrlock_yn' id='scrlock_yn'>
								<option value='Y' <?=$_scrlock_yn=="Y" ? "selected" : ""?>><?=$_LANG_TEXT['yestext'][$lang_code]?>
								</option>
								<option value='N' <?=$_scrlock_yn=="N" ? "selected" : ""?>><?=$_LANG_TEXT['notext'][$lang_code]?>
								</option>
							</select>
						</td>
						<th class='line'><?=$_LANG_TEXT['dataconnectionyntext'][$lang_code]?></th>
						<td>
							<select name='connection_yn' id='connection_yn'>
								<option value='Y' <?=$_connection_yn=="Y" ? "selected" : ""?>><?=$_LANG_TEXT['yestext'][$lang_code]?>
								</option>
								<option value='N' <?=$_connection_yn=="N" ? "selected" : ""?>><?=$_LANG_TEXT['notext'][$lang_code]?>
								</option>
							</select>
						</td>
					</tr>
					<tr>
						<th><?=$_LANG_TEXT['screenlockguidetext'][$lang_code]?></th>
						<td colspan="5">
							<input type='text' name='scrlock_warn_msg' id='scrlock_warn_msg' class="frm_input" maxlength="250"
								value="<?=$_scrlock_warn_comment?>"
								placeholder="<?=$_LANG_TEXT['screenlockguidesampletext'][$lang_code]?>" style="width:700px">
						</td>
					</tr>
					<tr class="bg">
						<th><?=$_LANG_TEXT['otpuseyntext'][$lang_code]?></th>
						<td>
							<select name='otp_yn' id='otp_yn'>
								<option value='Y' <?=$_otp_yn=="Y" ? "selected" : ""?>><?=$_LANG_TEXT['useyestext'][$lang_code]?>
								</option>
								<option value='N' <?=$_otp_yn=="N" ? "selected" : ""?>><?=$_LANG_TEXT['usenotext'][$lang_code]?>
								</option>
							</select>
						</td>
						<th class='line'><?=$_LANG_TEXT['webtypetext'][$lang_code]?>, Port</th>
						<td>
							<select name='web_type' id='web_type'>
								<?
						foreach($_CODE["web_type"] as $key => $value){
					?>
								<option value="<?=$key?>" <?=$_web_type==$key ? "selected" : ""?>><?=$value?></option>
								<?
						}
					?>

							</select>
							
							<input type='text' name='web_port' id='web_port' class="frm_input" style="width:30px" value="<?=$_web_port?>" maxlength="5">
						</td>
					</tr>
					</table>			
					
					<table class="view">
					<tr>
						<th style='min-width:170px;width:170px'>Web Port</th>
						<td style='min-width:200px;width:200px' >
							Web manager - 관리자용 (7443) / 매니저용(8443), Kiosk - 1443, API - 1443
						</td>
						<th class='line'><? echo trsLang('방문자퇴실처리배치실행','visitorcheckoutbatchyn');?></th>
						<td>
							<select name='visit_checkout_batch_yn' id='visit_checkout_batch_yn'>
									<option value='Y' <? if($_visit_checkout_batch_yn=="Y") echo "selected";?>><? echo trsLang('예','yestext')?></option>
									<option value='N' <? if($_visit_checkout_batch_yn=="N") echo "selected";?>><? echo trsLang('아니요','notext')?></option>
							</select>
						</td>
					</tr>
					<tr  class="bg">
						<th><?=$_LANG_TEXT['vaccinecheckyntext'][$lang_code]?></th>
						<td>
							<select name='vacc_chk_yn' id='vacc_chk_yn'>
								<option value='Y' <?=$_vaccine_check_yn=="Y" ? "selected" : ""?>><?=$_LANG_TEXT['yestext'][$lang_code]?>
								</option>
								<option value='N' <?=$_vaccine_check_yn=="N" ? "selected" : ""?>><?=$_LANG_TEXT['notext'][$lang_code]?>
								</option>
							</select>
							
							<select name='vacc_scan_type' id='vacc_scan_type'>
								<option value=""><?=$_LANG_TEXT['vaccinescantypetext'][$lang_code]?></option>
										<?
								foreach($_CODE["vaccine_scan_type"] as $key => $value){
									?>
										<option value="<?=$key?>" <?=$_vacc_scan_type==$key ? "selected" : ""?>><?=$value?></option>
										<?
								}
									?>

									</select>
							<div class='col'>
								<input type='checkbox' name='v3_use_yn' id='v3_use_yn' value='Y' <? if($v3_use_yn=="Y") echo "checked";?>> <label for='v3_use_yn'>V3</label>
								<input type='checkbox' name='eset_use_yn' id='eset_use_yn'  value='Y' <? if($eset_use_yn=="Y") echo "checked";?>> <label for='eset_use_yn'>Eset</label>
							</div>
						</td>
						<th class="line"><?=trsLang('파일검사적용','filescanadapttext');?></th>
						<td>
							<select name='file_scan_yn' id='file_scan_yn'>
								<option value='Y' <?=$_file_scan_yn=="Y" ? "selected" : ""?>><?=$_LANG_TEXT['yestext'][$lang_code]?>
								</option>
								<option value='N' <?=$_file_scan_yn=="N" ? "selected" : ""?>><?=$_LANG_TEXT['notext'][$lang_code]?>
								</option>
							</select>
							<div id='file_scan_device_wrap' class='col' <?if($_file_scan_yn=="N") echo "style='display:none'"?>>
								<!--<input type='checkbox' name='file_scan_device[]' id='file_scan_device_all' value='ALL' <?if(strpos($_file_scan_device,"ALL")!==false)  echo "checked";?>>
								<label for='file_scan_device_all'><? echo trsLang('전체','alltext');?></label>
								<input type='checkbox' name='file_scan_device[]' id='file_scan_device_hdd' value='HDD' <?if(strpos($_file_scan_device,"HDD")!==false)  echo "checked";?>>
								<label for='file_scan_device_hdd'>HDD</label>
								<input type='checkbox' name='file_scan_device[]' id='file_scan_device_usb' value='USB' <?if(strpos($_file_scan_device,"USB")!==false)  echo "checked";?>>
								<label for='file_scan_device_usb'>USB</label>
								<input type='checkbox' name='file_scan_device[]' id='file_scan_device_cd' value='CD' <?if(strpos($_file_scan_device,"CD")!==false)  echo "checked";?>>
								<label for='file_scan_device_cd'>CD</label>
								<input type='checkbox' name='file_scan_device[]' id='file_scan_device_etc' value='ETC' <?if(strpos($_file_scan_device,"ETC")!==false)  echo "checked";?>>
								<label for='file_scan_device_etc'><? echo trsLang('기타','etctext');?></label>-->
								<input type='checkbox' name='file_scan_device[]' id='file_scan_device_cd_n' value='CD_N' <?if(strpos($_file_scan_device,"CD_N")!==false) echo "checked";?>>
								<label for='file_scan_device_cd_n'>CD <? echo trsLang('제외','exclusionnntext');?></label>
							</div>
						</td>
					</tr>
					<tr>
						<th class="line"><?=trsLang('반입파일 서버 보관','storagemediafileserversendtype')?></th>
						<td>
							<select id='checkin_file_send_type' name='checkin_file_send_type'>
								<option value='N' <?if($_checkin_file_send_type=="N" ) echo "selected" ;?>
									><?=trsLang('전송안함','nosendtext')?></option>
								<option value='Y' <?if($_checkin_file_send_type=="Y" ) echo "selected" ;?>
									><?=trsLang('파일전송','filesendtext')?></option>
								<option value='L' <?if($_checkin_file_send_type=="L" ) echo "selected" ;?>
									><?=trsLang('파일목록만전송','onlyfilelistsendtext')?></option>
							</select>
							<div id='checkin_file_send_type_wrap' class='col' <?if($_checkin_file_send_type=="N") echo "style='display:none'"?>>
								<!--
								<input type='checkbox' name='checkin_file_send_device[]' id='checkin_file_send_device_all' value='ALL' <?if(strpos($_checkin_file_send_device,"ALL")!==false) echo "checked";?>>
								<label for='checkin_file_send_device_all'><? echo trsLang('전체','alltext');?></label>
								<input type='checkbox' name='checkin_file_send_device[]' id='checkin_file_send_device_hdd' value='HDD' <?if(strpos($_checkin_file_send_device,"HDD")!==false) echo "checked";?>>
								<label for='checkin_file_send_device_hdd'>HDD</label>
								<input type='checkbox' name='checkin_file_send_device[]' id='checkin_file_send_device_usb' value='USB' <?if(strpos($_checkin_file_send_device,"USB")!==false)  echo "checked";?>>
								<label for='checkin_file_send_device_usb'>USB</label>
								<input type='checkbox' name='checkin_file_send_device[]' id='checkin_file_send_device_cd' value='CD' <?if(strpos($_checkin_file_send_device,"CD")!==false)  echo "checked";?>>
								<label for='checkin_file_send_device_cd'>CD</label>
								<input type='checkbox' name='checkin_file_send_device[]' id='checkin_file_send_device_etc' value='ETC' <?if(strpos($_checkin_file_send_device,"ETC")!==false)  echo "checked";?>>
								<label for='checkin_file_send_device_etc'><? echo trsLang('기타','etctext');?></label>
								-->
								<input type='checkbox' name='checkin_file_send_device[]' id='checkin_file_send_device_cd_n' value='CD_N' <?if(strpos($_checkin_file_send_device,"CD_N")!==false) echo "checked";?>>
								<label for='checkin_file_send_device_cd_n'>CD <? echo trsLang('제외','exclusionnntext');?></label>
							</div>
						</td>
						
						<th class="line" style='min-width:170px;width:170px'><?=$_LANG_TEXT['adminpasswordchagetermtext'][$lang_code]?></th>
						<td>
							<input type='text' name='admin_pwd_change_term' id='admin_pwd_change_term' class="frm_input"
								style="width:50px" value='<?=$_admin_pwd_change_term?>' maxlength="5" onkeyup='onlyNumber(this)'>
							<?=$_LANG_TEXT['daytext'][$lang_code]?>
						</td>
						
					</tr>
					<tr   class="bg">
						<th style='min-width:170px;width:170px'><?=$_LANG_TEXT['datakeepdaytext'][$lang_code]?></th>
						<td style='min-width:200px;width:200px'>
							<input type='text' name='data_keep_day' id='data_keep_day' name="" class="frm_input" style="width:50px"
								value='<?=$_data_keep_day?>' maxlength="5" onkeyup='onlyNumber(this)'>
							<?=$_LANG_TEXT['daytext'][$lang_code]?>
						</td>
						<th class="line">KIOSK <? echo trsLang('데이타삭제주기','datadeletedayterms');?></th>
						<td>
							<input type='text' name='kiosk_data_delete_day' id='kiosk_data_delete_day' name="" class="frm_input" style="width:50px"
								value='<?=$_kiosk_data_delete_day?>' maxlength="5" onkeyup='onlyNumber(this)'>
							<?=$_LANG_TEXT['daytext'][$lang_code]?> (0 : not execution)
						</td>
						
					</tr>
					<tr>
						<th><b><?=$_LANG_TEXT['loginattemptlimittext'][$lang_code]?></b></th>
						<td class='line' style='width:30%;'>
							<select style="width:70px;" name='login_attempt_cnt' id='login_attempt_cnt'>
								<option value='0' <? if($login_attempt_cnt=="0" ) echo "selected" ; ?>>0</option>
								<option value='3' <? if($login_attempt_cnt=="3" ) echo "selected" ; ?>>3</option>
								<option value='5' <? if($login_attempt_cnt=="5" ) echo "selected" ; ?>>5</option>
							</select>
							<label style="margin:5px;" class='w120'>(0 : not execution)</label>
						</td>
						<th class='line'><b><?=$_LANG_TEXT['loginiplimittext'][$lang_code]?></b></th>
						<td class='line'>
							<select style="width:67px;" name='login_ip_limit_yn' id='login_ip_limit_yn'>
								<option value='N' <? if($login_ip_limit_yn=="N" ) echo "selected" ; ?>>No</option>
								<option value='Y' <? if($login_ip_limit_yn=="Y" ) echo "selected" ; ?>>Yes</option>
							</select>

						</td>
					</tr>

					
					<tr class="display-none">
						<th><b>DB encription method</b></th>
						<td class='line' style='width:30%;'>
							<select  name='db_encription_kind' id='db_encription_kind'>
								<? /*Symmetric Key 암호화는 현재 암호화 작업 안되어 있음.*/?>
								<!--<option value='1' <? if($_db_encription_kind=="1" ) echo "selected" ; ?>>Symmetric Key (1)</option>-->
								<option value='2' <? if($_db_encription_kind=="2" ) echo "selected" ; ?>>AES Rijndael (2)</option>
							</select>
						</td>
						<th class='line'><b>DB encription type</b></th>
						<td class='line'>
							<select  name='db_encription_flag' id='db_encription_flag'>
								<option value='1' <? if($_db_encription_flag=="1" ) echo "selected" ; ?>>Tel,Email Enc(1)</option>
								<option value='2' <? if($_db_encription_flag=="2" ) echo "selected" ; ?>>Name,Tel,Email Enc(2)</option>
							</select>

						</td>
					</tr>
				</table>





				<? # 현재 사용 안하는 기능 숨김처리# ?>
				<div class='display-none'>
					<table class="view" style='margin-top:30px;'>

						<tr>
							<th><?=$_LANG_TEXT['programupdateservertext'][$lang_code]?></th>
							<td>
								<input type='text' name='app_update_server' id='app_update_server' value="<?=$_app_update_server?>"
									class="frm_input" style="width:90%" maxlength="50">
							</td>
							<th class="line"><?=$_LANG_TEXT['programapiservertext'][$lang_code]?></th>
							<td>
								<input type='text' name='app_api_server' id='app_api_server' value="<?=$_app_api_server?>"
									class="frm_input" style="width:300px" maxlength="50">
							</td>
						</tr>
						<tr class="bg">
							<th style='width:170px;min-width:170px;'><?=$_LANG_TEXT['webtypetext'][$lang_code]?></th>
							<td style='min-width:200px;width:200px'>
								<!--
								<select name='web_type' id='web_type'>
									<?
										foreach($_CODE["web_type"] as $key => $value){
									?>
										<option value="<?=$key?>" <?=$_web_type==$key ? "selected" : ""?>><?=$value?></option>
									<?
										}
									?>
									
								</select>
								-->
							</td>
							<th style='width:170px;min-width:170px;' class="line"><?=$_LANG_TEXT['webserveraddrtext'][$lang_code]?>,
								<?=$_LANG_TEXT['porttext'][$lang_code]?></th>
							<td>
								<input type='text' name='web_server' id='web_server' class="frm_input" style="width:230px"
									value="<?=$_web_server?>" maxlength="50">
							</td>
						</tr>
						<tr>
							<th><?=$_LANG_TEXT['ftptypetext'][$lang_code]?></th>
							<td>
								<select name='ftp_type' id='ftp_type'>
									<?
						foreach($_CODE["ftp_type"] as $key => $value){
						?>
									<option value="<?=$key?>" <?=$_ftp_type==$key ? "selected" : ""?>><?=$value?></option>
									<?
						}
						?>

								</select>
							</td>
							<th class="line"><?=$_LANG_TEXT['ftpserveraddrtext'][$lang_code]?>,
								<?=$_LANG_TEXT['porttext'][$lang_code]?></th>
							<td>
								<input type='text' name='ftp_server' id='ftp_server' class="frm_input" style="width:230px"
									value="<?=$_ftp_server?>" maxlength="50"> ,
								<input type='text' name='ftp_port' id='ftp_port' class="frm_input" style="width:30px"
									value="<?=$_ftp_port?>" maxlength="5">
							</td>
						</tr>
					</table>

					<table class="view" style='margin-top:30px;'>
						<tr>
							<th style='width:170px;min-width:170px;'><?=$_LANG_TEXT['mailtypetext'][$lang_code]?></th>
							<td style='min-width:200px;width:200px'>
								<select name='mail_type' id='mail_type'>
									<?
						foreach($_CODE["mail_type"] as $key => $value){
					?>
									<option value="<?=$key?>" <?=$_mail_type==$key ? "selected" : ""?>><?=$value?></option>
									<?
						}
					?>

								</select>
							</td>
							<th style='width:170px;min-width:170px;' class="line"><?=$_LANG_TEXT['mailserveraddrtext'][$lang_code]?>,
								<?=$_LANG_TEXT['porttext'][$lang_code]?></th>
							<td>
								<input type='text' name='mail_server' id='mail_server' class="frm_input" style="width:230px"
									value="<?=$_mail_server?>" maxlength="50"> ,
								<input type='text' name='mail_port' id='mail_port' class="frm_input" style="width:30px"
									value="<?=$_mail_port?>" maxlength="5">
							</td>
						</tr>
						<tr class="bg">
							<th><?=$_LANG_TEXT['mailsendadminidtext'][$lang_code]?></th>
							<td>
								<input type='text' name='mail_id' id='mail_id' value="<?=$_mail_id?>" class="frm_input"
									style="width:90%" maxlength="50">
							</td>
							<th class="line"><?=$_LANG_TEXT['mailsendadminpwdtext'][$lang_code]?></th>
							<td>
								<input type='password' name='mail_pwd' id='mail_pwd' value="<?=$_mail_pwd?>" class="frm_input"
									style="width:300px" maxlength="50">
							</td>
						</tr>
					</table>

					<table class="view" style='margin-top:30px;'>
						<tr>
							<th style='width:170px;min-width:170px;'><?=$_LANG_TEXT['smstypetext'][$lang_code]?></th>
							<td style='min-width:200px;width:200px'>
								<select name='sms_type' id='sms_type'>
									<?
						foreach($_CODE["sms_type"] as $key => $value){
					?>
									<option value="<?=$key?>" <?=$_sms_type==$key ? "selected" : ""?>><?=$value?></option>
									<?
						}
					?>

								</select>
							</td>
							<th style='width:170px;min-width:170px;' class="line"><?=$_LANG_TEXT['smsserveraddrtext'][$lang_code]?>,
								<?=$_LANG_TEXT['porttext'][$lang_code]?></th>
							<td>
								<input type='text' name='sms_server' id='sms_server' class="frm_input" style="width:230px"
									value="<?=$_sms_server?>" maxlength="50"> ,
								<input type='text' name='sms_port' id='sms_port' class="frm_input" style="width:30px"
									value="<?=$_sms_port?>" maxlength="5">
							</td>
						</tr>
						<tr>
							<th><?=$_LANG_TEXT['smssendidtext'][$lang_code]?></th>
							<td>
								<input type='text' name='sms_id' id='sms_id' value="<?=$_sms_id?>" class="frm_input" style="width:90%"
									maxlength="50">
							</td>
							<th class="line"><?=$_LANG_TEXT['smssendpwdtext'][$lang_code]?></th>
							<td>
								<input type='password' name='sms_pwd' id='sms_pwd' value="<?=$_sms_pwd?>" class="frm_input"
									style="width:300px" maxlength="50">
							</td>
						</tr>
						<tr class="bg">
							<th><?=$_LANG_TEXT['smsdbtext'][$lang_code]?></th>
							<td>
								<input type='text' name='sms_db' id='sms_db' value="<?=$_sms_db?>" class="frm_input" style="width:90%"
									maxlength="50">
							</td>

							<th class="line"><?=$_LANG_TEXT['smsdbtabletext'][$lang_code]?></th>
							<td>
								<input type='text' name='sms_table' id='sms_table' value="<?=$_sms_table?>" class="frm_input"
									style="width:300px;" maxlength="50">
							</td>

						</tr>
						<tr>
							<th><?=$_LANG_TEXT['smssendweburltext'][$lang_code]?></th>
							<td colspan='3'><input type='text' name='sms_url' id='sms_url' value="<?=$_sms_url?>" class="frm_input"
									style="width:700px" maxlength="250"></td>
						</tr>
						<tr class="bg">
							<th><?=$_LANG_TEXT['smssendtelnotext'][$lang_code]?></th>
							<td><input type='text' name='sms_send_telno' id='sms_send_telno' value="<?=$_sms_send_telno?>"
									class="frm_input" style="width:90%" maxlength="50"></td>
							<th class="line" colspan='2'></th>
						</tr>

					</table>

				</div>

				<div class="btn_wrap">
					<div class="right">
						<a href="#" class="btn required-update-auth hide" onclick="return PolicySubmit()"><?=$_LANG_TEXT['btnsave'][$lang_code]?></a>
						<?  if($_SESSION['user_seq']=="0"){ ?>
							<a href="<? echo $_www_server?>/manage/policy_all.php" class="btn super">모두 보기</a>
						<?}?>
					</div>
				</div>

			</form>
			<div id="nopolicymsg" class="policymsg">&nbsp;<?=$nopolicymsg?></div>
		</div>


	</div>
</div>

<?php

if($result) sqlsrv_free_stmt($result);
sqlsrv_close($wvcs_dbcon);

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>