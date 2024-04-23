<?php
		
		$raw_value = $_POST['json'];
		$str_value = unQuotChars($raw_value);
		$json_value = json_decode($str_value, true);
		

		$vcs_mode = $json_value['vcs_mode'];
		$wvcs_success_yn = $json_value['wvcs_success_yn'];
		
		$phone_num_enc = base64_decode($json_value['phone_num']);
		$phone_num =  AES_Rijndael_Decript($phone_num_enc, $_AES_KEY, $_AES_IV); 

		if($json_value['visitor_id']==""){
			$visitor_id =   "";
		}else{
			$visitor_id =  AES_Rijndael_Decript($json_value['visitor_id'], $_AES_KEY, $_AES_IV); 
		}
		
		if($json_value['visit_num']==""){
			
			$visit_num =  "";
		
		}else{
			
			//$visit_num =  AES_Rijndael_Decript($json_value['visit_num'], $_AES_KEY, $_AES_IV); 
			$visit_num_enc = base64_decode($json_value['visit_num']);
			$visit_num =  AES_Rijndael_Decript($visit_num_enc, $_AES_KEY, $_AES_IV); 
		}
		
		if($json_value['visit_dev_num']==""){
			$visit_dev_num = "";
		}else{
			$visit_dev_num =  AES_Rijndael_Decript($json_value['visit_dev_num'], $_AES_KEY, $_AES_IV); 
		}
		
		if($json_value['user_num']==""){
			$user_num =  "";
		}else{
			$user_num =  AES_Rijndael_Decript($json_value['user_num'], $_AES_KEY, $_AES_IV); 
		}

		if($json_value['user_seq']==""){
			$user_seq =  "";
		}else{
			$user_seq =  AES_Rijndael_Decript($json_value['user_seq'], $_AES_KEY, $_AES_IV); 
		}


		$scan_date = getDefineDateFormatDot($json_value['scan_date']);
		$vacc_name =  $json_value['vacc_name'];
		$vacc_ver = $json_value['vacc_ver'];
		$vacc_update_date = getDefineDateFormatDot($json_value['vacc_update_date']);
		$vacc_scan_date = getDefineDateFormatDot($json_value['vacc_scan_date']);
		
		//2023-11-22 바이러스 검사 시작시간,종료시간 추가
		$vacc_scan_start_date = $json_value['vacc_scan_start_date'];
		$vacc_scan_end_date = $json_value['vacc_scan_end_date'];

		$vacc_scan_count = $json_value['vacc_scan_count'];
		if($vacc_scan_count=="") $vacc_scan_count = 0;
		$infect_yn = $json_value['infect_yn'];
		$winup_check_date = getDefineDateFormatDot($json_value['winup_check_date']);
		$winup_install_date = getDefineDateFormatDot($json_value['winup_install_date']);
		$winup_pass_yn = $json_value['winup_pass_yn'];
		$weakpoint_cnt = $json_value['weakpoint_cnt'];
		$weakpoint_pass_yn = $json_value['weakpoint_pass_yn'];
		$virus_list = $json_value['virus_list'];
		$winup_list = $json_value['winup_list'];
		$weakpoint_list = $json_value['weakpoint_list'];
		$ldisk_list = $json_value['ldisk_list'];
		$pdisk_list = $json_value['pdisk_list'];
		$program_list = $json_value['program_list'];
		$system_info = $json_value['system_info'];
		$checkin_available_date = $json_value['checkin_available_date'];
		$scan_center = $json_value['scan_center'];
		$wvcs_type = $json_value['wvcs_type'];
		if($wvcs_type=="") $wvcs_type = "DOWNLOAD";
		$asset_type = $json_value['asset_type'];
		$v_user_list_seq = $json_value['v_user_list_seq'];
		

		$mngr_name = AES_Rijndael_Decript($json_value['mngr_name'], $_AES_KEY, $_AES_IV);
		$mngr_department = AES_Rijndael_Decript($json_value['mngr_department'], $_AES_KEY, $_AES_IV);
		$sys_sn = AES_Rijndael_Decript($json_value['sys_sn'], $_AES_KEY, $_AES_IV);
		$hdd_sn = AES_Rijndael_Decript($json_value['hdd_sn'], $_AES_KEY, $_AES_IV);
		$board_sn = AES_Rijndael_Decript($json_value['board_sn'], $_AES_KEY, $_AES_IV);
		$model_name = AES_Rijndael_Decript($json_value['model_name'], $_AES_KEY, $_AES_IV);
		$manufacturer = AES_Rijndael_Decript($json_value['manufacturer'], $_AES_KEY, $_AES_IV);
		$host_name = AES_Rijndael_Decript($json_value['host_name'], $_AES_KEY, $_AES_IV);
		$ram_size = $json_value['ram_size'];
		$mac_addr = AES_Rijndael_Decript($json_value['mac_addr'], $_AES_KEY, $_AES_IV);
		$cpu_info = AES_Rijndael_Decript($json_value['cpu_info'], $_AES_KEY, $_AES_IV);
		$os_info = AES_Rijndael_Decript($json_value['os_info'], $_AES_KEY, $_AES_IV);
		$os_ver_name = AES_Rijndael_Decript($json_value['os_ver_name'], $_AES_KEY, $_AES_IV);
		$os_architecture = AES_Rijndael_Decript($json_value['os_architecture'], $_AES_KEY, $_AES_IV);
		$os_ver_major = $json_value['os_ver_major'];
		$os_ver_minor = $json_value['os_ver_minor'];
		$os_ver_build = $json_value['os_ver_build'];
		$os_ver_sp = AES_Rijndael_Decript($json_value['os_ver_sp'], $_AES_KEY, $_AES_IV);
		$os_key = AES_Rijndael_Decript($json_value['os_key'], $_AES_KEY, $_AES_IV);
		$boot_device = AES_Rijndael_Decript($json_value['boot_device'], $_AES_KEY, $_AES_IV);
		$pc_gmt = AES_Rijndael_Decript($json_value['pc_gmt'], $_AES_KEY, $_AES_IV);
		$pc_time = AES_Rijndael_Decript($json_value['pc_time'], $_AES_KEY, $_AES_IV);
		$work_group = AES_Rijndael_Decript($json_value['work_group'], $_AES_KEY, $_AES_IV);
		$user_account = AES_Rijndael_Decript($json_value['user_account'], $_AES_KEY, $_AES_IV);
		$user_grade = AES_Rijndael_Decript($json_value['user_grade'], $_AES_KEY, $_AES_IV);
		$mui_lang = AES_Rijndael_Decript($json_value['mui_lang'], $_AES_KEY, $_AES_IV);
		$bios_ver = AES_Rijndael_Decript($json_value['bios_ver'], $_AES_KEY, $_AES_IV);
		$windows_dir = AES_Rijndael_Decript($json_value['windows_dir'], $_AES_KEY, $_AES_IV);
		$ip_addr = AES_Rijndael_Decript($json_value['ip_addr'], $_AES_KEY, $_AES_IV);
		$notebook_key = AES_Rijndael_Decript($json_value['notebook_key'], $_AES_KEY, $_AES_IV);
		$barcode = AES_Rijndael_Decript($json_value['barcode'], $_AES_KEY, $_AES_IV);
		


		if($ram_size == "") $ram_size = 0;
		if($os_ver_major == "") $os_ver_major = 0;
		if($os_ver_minor == "") $os_ver_minor = 0;
		if($os_ver_build == "") $os_ver_build = 0;
		if($os_ver_sp == "") $os_ver_sp = 0;
		
		if($user_seq ==""){
			/*
			$sql = " SELECT  top 1 v_user_seq
						FROM   tb_v_user
						WHERE   v_phone = '".aes_256_enc($phone_num)."' 
						ORDER BY v_user_seq ";
			*/

			$sql = "
				SELECT TOP 1 v_user_seq
				FROM   tb_v_user_list
				WHERE  v_user_list_seq = '{$v_user_list_seq}'
			";

			$result = sqlsrv_query($wvcs_dbcon, $sql);

			while( $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
				$v_user_seq = $row["v_user_seq"];
			}

		}else{
			$v_user_seq =  $user_seq;
		}

		if($v_user_seq > 0) {
			
			//1. 결과 저장
			/* v_wvcs_seq, v_user_seq, v_notebook_key, checkin_available_dt, mngr_name, mngr_department, wvcs_dt, wvcs_success_yn, wvcs_authorize_yn, wvcs_authorize_name, wvcs_authorize_dt
			v_asset_type, memo_text, 	ip_addr, 	create_dt, org_name, wvcs_type			*/
			if($vcs_mode=="DPTPRO") {
					//dpt_v_user_list_seq 값을 넣어준다.
					$sql_info = " INSERT INTO tb_v_wvcs_info ( v_user_seq, v_notebook_key, checkin_available_dt, mngr_name, mngr_department, wvcs_dt, wvcs_success_yn
										, v_asset_type, ip_addr, create_dt, wvcs_type, scan_center_code, barcode, visit_num, visit_dev_num, vcs_status, visit_dev_num_list, vacc_scan_count, dpt_vul_dev_seq ,v_user_list_seq)   
								VALUES ( $v_user_seq, '$notebook_key', '$checkin_available_date', '".aes_256_enc($mngr_name)."', '$mngr_department', '$scan_date', '$wvcs_success_yn', '$asset_type',  '$ip_addr', getdate() , '$wvcs_type', '$scan_center' , '$barcode' , '$visit_num', '$visit_dev_num', 'CHECK', '$visit_dev_num', '$vacc_scan_count', '$visit_dev_num','$v_user_list_seq'); 
								select SCOPE_IDENTITY() as id; ";
			}else {
					$sql_info = " INSERT INTO tb_v_wvcs_info ( v_user_seq, v_notebook_key, checkin_available_dt, mngr_name, mngr_department, wvcs_dt, wvcs_success_yn
										, v_asset_type, ip_addr, create_dt, wvcs_type, scan_center_code, barcode, visit_num, visit_dev_num, vcs_status, visit_dev_num_list, vacc_scan_count,v_user_list_seq )   
								VALUES ( $v_user_seq, '$notebook_key', '$checkin_available_date', '".aes_256_enc($mngr_name)."', '$mngr_department', '$scan_date', '$wvcs_success_yn', '$asset_type',  '$ip_addr', getdate() , '$wvcs_type', '$scan_center' , '$barcode' , '$visit_num', '$visit_dev_num', 'CHECK', '$visit_dev_num', '$vacc_scan_count','$v_user_list_seq'); 
								select SCOPE_IDENTITY() as id; ";
			}
		
			$result = @sqlsrv_query($wvcs_dbcon, $sql_info );
			@sqlsrv_next_result($result);
			@sqlsrv_fetch($result);
			$wvcs_seq = @sqlsrv_get_field($result, 0);

			/* v_wvcs_detail_seq, v_wvcs_seq, v_sys_sn, v_hdd_sn, v_board_sn, v_model_name, v_manufacturer, host_name, ram_size,
			mac_addr, cpu_info, os_info, os_ver_name, os_architecture, os_ver_major, os_ver_minor, os_ver_build, os_ver_sp, os_key,
			boot_device, pc_gmt, pc_time, work_group, user_account, user_grade, mui_lang, bios_ver, windows_dir, create_dt
			*/


			if($wvcs_seq > 0) {
					
					$sql_info_detail = " INSERT INTO tb_v_wvcs_info_detail (v_wvcs_seq, v_sys_sn, v_hdd_sn, v_board_sn, v_model_name, v_manufacturer, host_name
					, ram_size, mac_addr
					, cpu_info, os_info, os_ver_name, os_architecture, os_ver_major, os_ver_minor, os_ver_build, os_ver_sp, os_key, boot_device
					, pc_gmt, pc_time, work_group, user_account, user_grade, mui_lang, bios_ver, windows_dir,  create_dt )   
					VALUES ( $wvcs_seq, '$sys_sn', '$hdd_sn', '$board_sn', '$model_name', '$manufacturer', '$host_name', $ram_size, '$mac_addr'
					, '$cpu_info', '$os_info', '$os_ver_name', '$os_architecture', $os_ver_major, $os_ver_minor, $os_ver_build, '$os_ver_sp', '$os_key', '$boot_device'
					, '$pc_gmt', '$pc_time', '$work_group', '$user_account', '$user_grade', '$mui_lang', '$bios_ver', '$windows_dir',  getdate()); 
					select SCOPE_IDENTITY() as id; ";

					$result = sqlsrv_query($wvcs_dbcon, $sql_info_detail );

					

					//2. 윈도우 업데이트 검사 결과 & 내역
					$sql_winup = " INSERT INTO tb_v_wvcs_windowsupdate ( v_wvcs_seq, wu_check_date, wu_install_date, wu_success_yn, create_dt )
										VALUES ( $wvcs_seq, '$winup_check_date', '$winup_install_date', '$winup_pass_yn', getdate() ); 
										select SCOPE_IDENTITY() as id; ";

					$result = sqlsrv_query($wvcs_dbcon, $sql_winup );
					sqlsrv_next_result($result);
					sqlsrv_fetch($result);
					$winup_seq = sqlsrv_get_field($result, 0);

					foreach ($winup_list as $key => $value){

								//$secu_type = $value['secu_type'];
								$install_name = AES_Rijndael_Decript($value['install_name'], $_AES_KEY, $_AES_IV);
								$install_date = getDefineDateFormatDot(AES_Rijndael_Decript($value['install_date'], $_AES_KEY, $_AES_IV) );

								$sql_winuplist = " INSERT INTO tb_v_wvcs_windowsupdate_detail ( windowsupdate_seq, wu_type, wu_name, install_date, create_dt) 
														VALUES ( $winup_seq , '$secu_type', '$install_name', '$install_date', getdate()) 	";

								//echo $sql_winuplist;

								sqlsrv_query($wvcs_dbcon, $sql_winuplist );
					}

					//3. 바이러스 검출내역
					$sql_vacc = " INSERT INTO tb_v_wvcs_vaccine ( v_wvcs_seq, vaccine_name, vaccine_update_date, scan_date, scan_start_date,scan_end_date,success_yn, create_dt )
										VALUES ( $wvcs_seq, '$vacc_name', '$vacc_update_date', '$vacc_scan_date', '$vacc_scan_start_date','$vacc_scan_end_date', 'Y', getdate() ); 
										select SCOPE_IDENTITY() as id; ";
					
					$result = sqlsrv_query($wvcs_dbcon, $sql_vacc );
					sqlsrv_next_result($result);
					sqlsrv_fetch($result);
					$vacc_seq = sqlsrv_get_field($result, 0);

					foreach ($virus_list as $key => $value){

								$virus_name = AES_Rijndael_Decript($value['virus_name'], $_AES_KEY, $_AES_IV);
								$virus_path = AES_Rijndael_Decript($value['virus_path'], $_AES_KEY, $_AES_IV);
								$virus_status = AES_Rijndael_Decript($value['virus_status'], $_AES_KEY, $_AES_IV);
								$vol_letter = substr( $virus_path, 0, 2 );

								$sql_viruslist = " INSERT INTO tb_v_wvcs_vaccine_detail ( vaccine_seq, virus_name, virus_path,  virus_status, create_dt, vol_letter) 
														VALUES ( $vacc_seq , '$virus_name', '$virus_path', '$virus_status', getdate(), '$vol_letter') 	";
								
								//echo $sql_viruslist;
								sqlsrv_query($wvcs_dbcon, $sql_viruslist );
					}


					//4. 취약점 내역
					foreach ($weakpoint_list as $key => $value){

								$weakness_name = AES_Rijndael_Decript($value['weakpoint'], $_AES_KEY, $_AES_IV);
								$org_status = AES_Rijndael_Decript($value['org_status'], $_AES_KEY, $_AES_IV);
								$fix_status = AES_Rijndael_Decript($value['fix_status'], $_AES_KEY, $_AES_IV);

								$sql_weaklist = "INSERT INTO tb_v_wvcs_weakness ( v_wvcs_seq, weakness_name, org_status, fix_status, create_dt ) 
													   VALUES ( $wvcs_seq, '$weakness_name', '$org_status', '$fix_status', getdate() ) ";
								
								sqlsrv_query($wvcs_dbcon, $sql_weaklist );
					}
					
					// 5. 논리디스크(LDisk) 내역
					// -- vol_name, vol_letter,drive_type, file_system, tot_size, free_size, vol_sn, vol_desc
					foreach ($ldisk_list as $key => $value){

								$vol_name =  AES_Rijndael_Decript($value['vol_name'], $_AES_KEY, $_AES_IV);
								$vol_letter = AES_Rijndael_Decript($value['vol_letter'], $_AES_KEY, $_AES_IV);
								$drive_type = AES_Rijndael_Decript($value['drive_type'], $_AES_KEY, $_AES_IV);
								$file_system = AES_Rijndael_Decript($value['file_system'], $_AES_KEY, $_AES_IV);
								$tot_size = AES_Rijndael_Decript($value['tot_size'], $_AES_KEY, $_AES_IV);
								$free_size = AES_Rijndael_Decript($value['free_size'], $_AES_KEY, $_AES_IV);
								$vol_sn = AES_Rijndael_Decript($value['vol_sn'], $_AES_KEY, $_AES_IV);
								$vol_desc = AES_Rijndael_Decript($value['vol_desc'], $_AES_KEY, $_AES_IV);

								$sql_ldisklist = "INSERT INTO tb_v_wvcs_ldisk ( v_wvcs_seq, vol_name, vol_letter, drive_type, file_system, tot_size, free_size, create_dt, vol_sn, vol_desc ) 
													   VALUES ( $wvcs_seq, '$vol_name', '$vol_letter', '$drive_type', '$file_system', $tot_size, $free_size, getdate(), '$vol_sn', '$vol_desc' ) ";
								
								sqlsrv_query($wvcs_dbcon, $sql_ldisklist );
					}

					// 6. 물리디스크 내역
					// --disk_name,disk_model,media_type, partition_cnt, tot_size, tot_sector, manufacturer, serial_number, disk_index  
					foreach ($pdisk_list as $key => $value){

								$disk_name = AES_Rijndael_Decript($value['disk_name'], $_AES_KEY, $_AES_IV);
								$disk_model = AES_Rijndael_Decript($value['disk_model'], $_AES_KEY, $_AES_IV);
								$media_type = AES_Rijndael_Decript($value['media_type'], $_AES_KEY, $_AES_IV);
								$partition_cnt = AES_Rijndael_Decript($value['partition_cnt'], $_AES_KEY, $_AES_IV);
								$tot_size = AES_Rijndael_Decript($value['tot_size'], $_AES_KEY, $_AES_IV);
								$tot_sector = AES_Rijndael_Decript($value['tot_sector'], $_AES_KEY, $_AES_IV);
								$manufacturer = AES_Rijndael_Decript($value['manufacturer'], $_AES_KEY, $_AES_IV);
								$serial_number = AES_Rijndael_Decript($value['serial_number'], $_AES_KEY, $_AES_IV);
								$disk_index = AES_Rijndael_Decript($value['disk_index'], $_AES_KEY, $_AES_IV);

								if($value['device_instance_path'] != ""){
									$device_instance_path = AES_Rijndael_Decript($value['device_instance_path'], $_AES_KEY, $_AES_IV);
								}

								$sql_pdisklist = "INSERT INTO tb_v_wvcs_pdisk ( v_wvcs_seq, disk_name,disk_model,media_type, partition_cnt, tot_size, tot_sector , create_dt, manufacturer, serial_number, disk_index,device_instance_path ) 
													   VALUES ( $wvcs_seq, '$disk_name', '$disk_model', '$media_type', $partition_cnt , $tot_size , $tot_sector , getdate(),  '$manufacturer', '$serial_number', '$disk_index','$device_instance_path') ";
								
								sqlsrv_query($wvcs_dbcon, $sql_pdisklist );
					}


					// 7. 프로그램 설치 내역
					// --prog_name, prog_ver, prod_company, install_ymd
					foreach ($program_list as $key => $value){

								$prog_name = AES_Rijndael_Decript($value['prog_name'], $_AES_KEY, $_AES_IV);
								$prog_ver = AES_Rijndael_Decript($value['prog_ver'], $_AES_KEY, $_AES_IV);
								$prod_company = AES_Rijndael_Decript($value['prod_company'], $_AES_KEY, $_AES_IV);
								$install_ymd = AES_Rijndael_Decript($value['install_ymd'], $_AES_KEY, $_AES_IV);

								$sql_programlist = "INSERT INTO tb_v_wvcs_programs ( v_wvcs_seq, prog_name, prog_ver, prod_company, install_ymd, create_dt ) 
													   VALUES ( $wvcs_seq, '$prog_name', '$prog_ver', '$prod_company', '$install_ymd', getdate() ) ";
								
								sqlsrv_query($wvcs_dbcon, $sql_programlist );
					}

					
					$arrDevNum = explode( ',', $visit_dev_num );

					echo "TRUE:".$wvcs_seq;

			}else{
					echo "FALSE:INFO_NOTEXIST";
					writeLog($sql_info);
			exit;
			}

		}else{
			echo "FALSE:USER_NOTEXIST";
			exit;
		}
?>
