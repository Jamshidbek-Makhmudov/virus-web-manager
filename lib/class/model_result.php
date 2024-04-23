<?php
	/*
	* 점검결과 Class
	*/
	Class Model_result extends Model {

		/*
		* 방문자 점검결과 리스트 Count
		*/
		function getVCSListCount($args){
			$this->args = $args;
			
			$sql = "SELECT COUNT(*) AS CNT 
					FROM	tb_v_wvcs_info vcs
						LEFT JOIN tb_v_wvcs_info_detail vcd ON vcs.v_wvcs_seq = vcd.v_wvcs_seq
						INNER JOIN tb_v_user_list vl ON vcs.v_user_list_seq = vl.v_user_list_seq
						INNER JOIN tb_v_user us ON vl.v_user_seq = us.v_user_seq
						LEFT JOIN tb_v_user_list_info vi ON vl.v_user_list_seq = vi.v_user_list_seq
						LEFT JOIN tb_scan_center cn ON vcs.scan_center_code = cn.scan_center_code
					WHERE ".getCheckScanCenterAuthQuery('cn.org_id','cn.scan_center_code')."
							".$args['search_sql'];


			return $this->fetch($sql);
		}

		/*
		* 방문자 점검결과 리스트
		*/
		function getVCSList($args){
			$this->args = $args;
			
			$sql = " WITH Result AS
					(
					SELECT	TOP ".$args['end']." 
							vcs.v_wvcs_seq, vcd.v_wvcs_detail_seq,convert(varchar(16),wvcs_dt ,21) as	check_date,checkin_available_dt,v_manufacturer,
							vcs.wvcs_type,vcs.v_asset_type,vcs.v_notebook_key,vcd.v_sys_sn,vcs.wvcs_success_yn,vcs.wvcs_authorize_yn
							,CONVERT(varchar(16),vcs.wvcs_authorize_dt,21) as in_date, cn.scan_center_name,vcs.vcs_status,vcs.vacc_scan_count
							,CONVERT(varchar(16),vcs.checkout_dt,21) as out_date,vcd.os_ver_name,vcd.make_winpe
							,vl.v_user_belong,vl.manager_name,vl.manager_name_en, vl.manager_dept,vl.v_user_name
							,vl.v_user_list_seq,vl.v_user_seq,us.v_email,us.v_phone,vi.elec_doc_number,vi.label_name,vi.label_value,vl.v_type
							,vcd.device_in_flag
							,cn.scan_center_div
							,dbo.fn_DecryptString(vl.v_user_name) as v_user_name_decript
							,dbo.fn_DecryptString(vl.manager_name) as manager_name_decript
							,dbo.fn_DecryptString(us.v_email) as v_email_decript
							,dbo.fn_DecryptString(us.v_phone) as v_phone_decript
							,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
					FROM	tb_v_wvcs_info vcs
						LEFT JOIN tb_v_wvcs_info_detail vcd ON vcs.v_wvcs_seq = vcd.v_wvcs_seq
						INNER JOIN tb_v_user_list vl ON vcs.v_user_list_seq = vl.v_user_list_seq
						INNER JOIN tb_v_user us ON vl.v_user_seq = us.v_user_seq
						LEFT JOIN tb_v_user_list_info vi ON vl.v_user_list_seq = vi.v_user_list_seq
						LEFT JOIN tb_scan_center cn ON vcs.scan_center_code = cn.scan_center_code
					WHERE ".getCheckScanCenterAuthQuery('cn.org_id','cn.scan_center_code')."
						".$args['search_sql']."
				) 
				SELECT a.*,
							(	SELECT count(*) 
								FROM tb_v_wvcs_info_file
								WHERE a.v_wvcs_seq = v_wvcs_seq and a.v_wvcs_detail_seq = v_wvcs_detail_seq
									AND file_scan_result='BAD_EXT') as file_bad_cnt,
							(	SELECT count(*) 
								FROM tb_v_wvcs_weakness 
								WHERE a.v_wvcs_seq = v_wvcs_seq
									AND org_status='WEAK') as weak_cnt,
							(	SELECT count(distinct vccd.virus_path) 
								FROM tb_v_wvcs_vaccine vcc
									INNER JOIN tb_v_wvcs_vaccine_detail  vccd
									ON  vcc.vaccine_seq = vccd.vaccine_seq
								WHERE a.v_wvcs_seq = v_wvcs_seq) as virus_cnt,
							(	(SELECT count(pd_seq) 
								FROM tb_v_wvcs_pdisk
								WHERE a.v_wvcs_seq = v_wvcs_seq)	
								--+(SELECT count(ld_seq)
								--FROM tb_v_wvcs_ldisk
								--WHERE a.v_wvcs_seq = v_wvcs_seq
								--	AND drive_type='CD/DVD') 
							) as disk_cnt,
							(	SELECT count(v_wvcs_file_seq) 
								FROM tb_v_wvcs_info_file f1
								WHERE a.v_wvcs_detail_seq = v_wvcs_detail_seq
										and a.v_wvcs_seq = v_wvcs_seq) as scan_file_cnt,
							(	SELECT count(v_wvcs_file_in_seq) 
								FROM tb_v_wvcs_info_file_in f1
								WHERE a.v_wvcs_detail_seq = v_wvcs_detail_seq
										and a.v_wvcs_seq = v_wvcs_seq) as import_file_cnt
				FROM Result a
				WHERE rnum > ".$args['start']." ";

			if($args['excel_download_flag']=="1"){
				$sql .= " AND rnum <= " . ($args['start'] + RECORD_LIMIT_PER_FILE);	
			}

			// echo nl2br($sql);

			return $this->fetchAll($sql);
		}
        
		/*
		* 전체 파일검사내역가져오기
		*/
		function getVCSScanList($args){
			$this->args = $args;

				$sql = " WITH ScanList AS
					(
						select  top ".$args['end']." 
							v1.v_wvcs_seq, v4.v_user_name,v1.wvcs_dt,f1.v_wvcs_file_seq
							, f1.file_path,f1.file_name_org,f1.file_size,f1.file_ext,f1.file_signature,f1.file_scan_result,f1.md5,f1.sha256
							,f2.v_wvcs_file_in_seq,f2.file_send_status,f1.[file_id],f2.file_delete_flag,f2.file_send_date
							,v4.v_user_belong,v4.v_purpose,cn.scan_center_name
							,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
						from tb_v_wvcs_info v1
							inner join tb_v_user v2 on v1.v_user_seq = v2.v_user_seq
							inner join tb_v_user_list v4 on v2.v_user_seq = v4.v_user_seq and v4.v_user_list_seq = v1.v_user_list_seq 
							inner join tb_v_wvcs_info_detail v3 on v1.v_wvcs_seq = v3.v_wvcs_seq
							inner join tb_v_wvcs_info_file f1 on v1.v_wvcs_seq = f1.v_wvcs_seq and v3.v_wvcs_detail_seq = f1.v_wvcs_detail_seq
							left join tb_v_wvcs_info_file_in f2 on f1.v_wvcs_file_seq = f2.v_wvcs_file_seq
							left join tb_scan_center cn ON v1.scan_center_code = cn.scan_center_code
						where ".getCheckScanCenterAuthQuery('cn.org_id','cn.scan_center_code')
							.$args['search_sql']." 
					) 
					SELECT a.*
					FROM ScanList  a
					WHERE rnum > ".$args['start'];

				if($args['excel_download_flag']=="1"){
					$sql .= " AND rnum <= " . ($args['start'] + RECORD_LIMIT_PER_FILE);	
				}

				$result = $this->fetchAll($sql);
				return $result;

		}
	
		/*
		* 전체 파일검사내역 count
		*/
		function getVCSScanListCount($args){
			$this->args = $args;

			$sql = " select  count(v1.v_wvcs_seq) as cnt
					from tb_v_wvcs_info v1
						inner join tb_v_user v2 on v1.v_user_seq = v2.v_user_seq
						inner join tb_v_user_list v4 on v2.v_user_seq = v4.v_user_seq and v4.v_user_list_seq = v1.v_user_list_seq
						inner join tb_v_wvcs_info_detail v3 on v1.v_wvcs_seq = v3.v_wvcs_seq
						inner join tb_v_wvcs_info_file f1 on v1.v_wvcs_seq = f1.v_wvcs_seq and v3.v_wvcs_detail_seq = f1.v_wvcs_detail_seq
						left join tb_v_wvcs_info_file_in f2 on f1.v_wvcs_file_seq = f2.v_wvcs_file_seq
						left join tb_scan_center cn ON v1.scan_center_code = cn.scan_center_code
					where ".getCheckScanCenterAuthQuery('cn.org_id','cn.scan_center_code')
							.$args['search_sql'];
			

			$result = $this->fetch($sql);
			return $result;
		}

		/*
		* 전체 반입파일내역가져오기
		*/
		function getVCSFileImportList($args){
			$this->args = $args;

				$sql = " WITH ScanList AS
					(
						select  top ".$args['end']." 
							v2.v_user_name,v1.wvcs_dt,f1.v_wvcs_file_seq, f1.file_path,f1.file_name_org
							,f1.file_size,f1.file_ext,f1.file_signature,f1.file_scan_result,f1.md5,f1.sha256
							,f2.v_wvcs_file_in_seq,f2.file_send_status,f1.[file_id],f2.file_delete_flag
							,f2.file_send_date
							,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
						from tb_v_wvcs_info v1
							inner join tb_v_user v2 on v1.v_user_seq = v2.v_user_seq
							inner join tb_v_wvcs_info_detail v3 on v1.v_wvcs_seq = v3.v_wvcs_seq
							inner join tb_v_wvcs_info_file f1 on v1.v_wvcs_seq = f1.v_wvcs_seq and v3.v_wvcs_detail_seq = f1.v_wvcs_detail_seq
							inner join tb_v_wvcs_info_file_in f2 on f1.v_wvcs_file_seq = f2.v_wvcs_file_seq
							left join tb_scan_center cn ON v1.scan_center_code = cn.scan_center_code
						where ".getCheckScanCenterAuthQuery('cn.org_id','cn.scan_center_code')
							.$args['search_sql']."  
					) 
					SELECT a.*
					FROM ScanList  a
					WHERE rnum > ".$args['start'];


				if($args['excel_download_flag']=="1"){
					$sql .= " AND rnum <= " . ($args['start'] + RECORD_LIMIT_PER_FILE);	
				}

				$result = $this->fetchAll($sql);
				return $result;

		}
		
		/*
		* 전체 반입파일내역 count
		*/
		function getVCSFileImportListCount($args){
			$this->args = $args;

			$sql = " select  count(v1.v_wvcs_seq) as cnt
					from tb_v_wvcs_info v1
						inner join tb_v_user v2 on v1.v_user_seq = v2.v_user_seq
						inner join tb_v_wvcs_info_detail v3 on v1.v_wvcs_seq = v3.v_wvcs_seq
						inner join tb_v_wvcs_info_file f1 on v1.v_wvcs_seq = f1.v_wvcs_seq and v3.v_wvcs_detail_seq = f1.v_wvcs_detail_seq
						inner join tb_v_wvcs_info_file_in f2 on f1.v_wvcs_file_seq = f2.v_wvcs_file_seq
						left join tb_scan_center cn ON v1.scan_center_code = cn.scan_center_code
					where ".getCheckScanCenterAuthQuery('cn.org_id','cn.scan_center_code')
							.$args['search_sql'];

			$result = $this->fetch($sql);
			return $result;
		}

		/*
		* 사용자 검사결과 - 검사 불가 파일내역 가져오기
		*/
		function getUserVCSImportFailList($args){
			$this->args = $args;
			$search_sql = $args['search_sql'];
			$order_sql  = $args['order_sql'];
			$down_sql   = "";
			$page_start = $args['start'];
			$page_end   = $args['end'];
			$excel_flag = $args['excel_download_flag'];

			if ($excel_flag == "1") {
				$down_sql = " AND rnum <= " . ($page_start + RECORD_LIMIT_PER_FILE);	
			}

			$sql = " 
				WITH ScanList AS
				(
					SELECT  TOP {$page_end} v20.in_time
						, v20.v_user_name
						, v20.v_user_name_en
						, v20.v_user_belong
						, v20.v_purpose
						, cn.scan_center_name
						, f1.v_wvcs_file_seq
						, f1.file_path
						, f1.file_name_org
						, f1.file_size
						, f2.v_wvcs_file_in_seq
						, f2.file_send_status
						, ROW_NUMBER() OVER({$order_sql}) AS rnum
					FROM tb_v_wvcs_info v1
						INNER JOIN tb_v_user v2 ON v1.v_user_seq = v2.v_user_seq
						INNER JOIN tb_v_user_list v20 ON v2.v_user_seq = v20.v_user_seq AND v1.v_user_list_seq = v20.v_user_list_seq
						INNER JOIN tb_v_wvcs_info_detail v3 ON v1.v_wvcs_seq = v3.v_wvcs_seq
						INNER JOIN tb_v_wvcs_info_file f1 ON v1.v_wvcs_seq = f1.v_wvcs_seq AND v3.v_wvcs_detail_seq = f1.v_wvcs_detail_seq
						LEFT JOIN tb_v_wvcs_info_file_in f2 ON f1.v_wvcs_file_seq = f2.v_wvcs_file_seq
						LEFT JOIN tb_scan_center cn ON v1.scan_center_code = cn.scan_center_code
					WHERE ".getCheckScanCenterAuthQuery('cn.org_id', 'cn.scan_center_code')."
						AND v1.vcs_status = 'IN'
						AND f1.file_scan_result = ''
						AND f2.v_wvcs_file_in_seq IS NULL 
						{$search_sql}
				) 
				SELECT a.*
				FROM  ScanList  a
				WHERE rnum > {$page_start}
				{$down_sql}
			";
				
			$result = $this->fetchArray($sql);

			return $result;
		}

		
		/*
		* 사용자 검사결과 - 검사 불가 파일 갯수 가져오기
		*/
		function getUserVCSImportFailListCount($args){
			$this->args = $args;
			$search_sql = $args['search_sql'];
			
			$sql = " 
				SELECT  COUNT(v1.v_wvcs_seq) AS cnt
				FROM tb_v_wvcs_info v1
					INNER JOIN tb_v_user v2 ON v1.v_user_seq = v2.v_user_seq
					INNER JOIN tb_v_user_list v20 ON v2.v_user_seq = v20.v_user_seq AND v1.v_user_list_seq = v20.v_user_list_seq
					INNER JOIN tb_v_wvcs_info_detail v3 ON v1.v_wvcs_seq = v3.v_wvcs_seq
					INNER JOIN tb_v_wvcs_info_file f1 ON v1.v_wvcs_seq = f1.v_wvcs_seq AND v3.v_wvcs_detail_seq = f1.v_wvcs_detail_seq
					LEFT JOIN tb_v_wvcs_info_file_in f2 ON f1.v_wvcs_file_seq = f2.v_wvcs_file_seq
					LEFT JOIN tb_scan_center cn ON v1.scan_center_code = cn.scan_center_code
				WHERE ".getCheckScanCenterAuthQuery('cn.org_id', 'cn.scan_center_code')."
					AND v1.vcs_status = 'IN'
					AND f1.file_scan_result = ''
					AND f2.v_wvcs_file_in_seq IS NULL 
					{$search_sql}
			";

			$result = $this->fetch($sql);
			return $result;
		}

		/*
		* 사용자 검사결과 - 위변조의심 파일내역 가져오기
		*/
		function getUserVCSBadFileList($args){
			$this->args = $args;

			$sql = " WITH ScanList AS
					(
						select  top ".$args['end']." 
							v20.in_time,v20.v_user_name,v20.v_user_name_en,v20.v_user_belong,v20.v_purpose,cn.scan_center_name
							,f1.v_wvcs_file_seq, f1.file_path,f1.file_name_org,f1.file_size,f1.file_signature,f1.[file_id], f1.md5, f1.sha256
							,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
						from tb_v_wvcs_info v1
							inner join tb_v_user v2 on v1.v_user_seq = v2.v_user_seq
							inner join tb_v_user_list v20 on v2.v_user_seq = v20.v_user_seq and v1.v_user_list_seq = v20.v_user_list_seq
							inner join tb_v_wvcs_info_detail v3 on v1.v_wvcs_seq = v3.v_wvcs_seq
							inner join tb_v_wvcs_info_file f1 on v1.v_wvcs_seq = f1.v_wvcs_seq and v3.v_wvcs_detail_seq = f1.v_wvcs_detail_seq
							left join tb_scan_center cn ON v1.scan_center_code = cn.scan_center_code
						where ".getCheckScanCenterAuthQuery('cn.org_id','cn.scan_center_code')."
							and f1.file_scan_result='BAD_EXT' "
							.$args['search_sql']."
					) 
					SELECT a.*
					FROM ScanList  a
					WHERE rnum > ".$args['start'];
				

				if($args['excel_download_flag']=="1"){
					$sql .= " AND rnum <= " . ($args['start'] + RECORD_LIMIT_PER_FILE);	
				}

				$result = $this->fetchAll($sql);
				return $result;
		}

		
		/*
		* 사용자 검사결과 - 위변조의심 파일 리스트 갯수 가져오기
		*/
		function getUserVCSBadFileListCount($args){
			$this->args = $args;
			
			$sql = " select  count(v1.v_wvcs_seq) as cnt
					from tb_v_wvcs_info v1
						inner join tb_v_user v2 on v1.v_user_seq = v2.v_user_seq
						inner join tb_v_user_list v20 on v2.v_user_seq = v20.v_user_seq and v1.v_user_list_seq = v20.v_user_list_seq
						inner join tb_v_wvcs_info_detail v3 on v1.v_wvcs_seq = v3.v_wvcs_seq
						inner join tb_v_wvcs_info_file f1 on v1.v_wvcs_seq = f1.v_wvcs_seq and v3.v_wvcs_detail_seq = f1.v_wvcs_detail_seq
						left join tb_scan_center cn ON v1.scan_center_code = cn.scan_center_code
					where ".getCheckScanCenterAuthQuery('cn.org_id','cn.scan_center_code')."
							and f1.file_scan_result='BAD_EXT' "
							.$args['search_sql'];


			$result = $this->fetch($sql);
			return $result;
		}

		/*
		* 반입 파일 정보 가져오기
		*/
		function getImportFileInfo($args){
			$this->args = $args;
			
			$sql  = "select v1.file_send_status,v1.file_send_date,v1.file_delete_flag
					,v2.v_user_name,v2.in_time, v2.manager_name_en,v3.elec_doc_number
				from tb_v_wvcs_info v1
					inner join tb_v_user_list v2 on v1.v_user_list_seq = v2.v_user_list_seq
					inner join tb_v_user_list_info v3 on v2.v_user_list_seq = v3.v_user_list_seq
				where v_wvcs_seq = '".$args['v_wvcs_seq']."' ";
			
			$result = $this->fetchAll($sql);
			return $result;
		}


		/*
		* 점검 로그(소요시간) 가져오기
		*/
		function getVcsScanTimeLog($args){
			$this->args = $args;
		
			$sql = "
					select event_div,min(event_time) as start_time, max(event_time) as end_time
					from tb_vcs_scan_log
					where v_wvcs_seq='".$args['v_wvcs_seq']."'
					group by event_div
					order by min(event_time) ";

			$result = $this->fetchAll($sql);
			return $result;


		}

		/*
		* 파일예외반입신청 파일 리스트 가져오기
		*/
		function getFileInApplyFileList($args){
			$this->args = $args;

				$sql = " WITH FileApplyList AS (
						select  top ".$args['end']." 
							t1.start_date, t1.end_date, t1.file_send_status, t1.create_date
							,t2.file_name,t2.file_hash,t1.reason as file_comment
							,t1.approver_name as apprv_emp_name,t1.approve_status,t1.approve_date
							,t1.user_name as v_user_name,t1.user_company as v_user_belong
							,t1.manager_name,t1.manager_id as manager_name_en,t1.manager_dept,t1.refer_apply_seq
							,v2.elec_doc_number
							,v1.in_time ,cn.scan_center_name,vcs.scan_center_code
							,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
						From tb_v_wvcs_info_file_in_apply t1
							inner join tb_v_wvcs_info_file_in_apply_detail t2 on t1.file_in_apply_seq = t2.file_in_apply_seq
							inner join tb_v_wvcs_info vcs on vcs.v_wvcs_seq = t1.v_wvcs_seq
							inner join tb_v_user_list v1 on vcs.v_user_list_seq = v1.v_user_list_seq
							inner join tb_v_user_list_info v2 on v1.v_user_list_seq = v2.v_user_list_seq
							LEFT JOIN tb_scan_center cn ON vcs.scan_center_code = cn.scan_center_code
						WHERE ".getCheckScanCenterAuthQuery('cn.org_id','cn.scan_center_code')
							.$args['search_sql']." 
					) 
					SELECT a.*
					FROM FileApplyList  a
					WHERE rnum > ".$args['start'];

				if($args['excel_download_flag']=="1"){
					$sql .= " AND rnum <= " . ($args['start'] + RECORD_LIMIT_PER_FILE);	
				}

				//echo nl2br($sql);

				$result = $this->fetchAll($sql);
				
				return $result;

		}

		/*
		* 파일예외반입신청 파일리스트 count 가져오기
		*/
		function getFileInApplyFileListCount($args){
			$this->args = $args;

			$sql = " select  count(t1.file_in_apply_seq) as cnt
					From tb_v_wvcs_info_file_in_apply t1
							inner join tb_v_wvcs_info_file_in_apply_detail t2 on t1.file_in_apply_seq = t2.file_in_apply_seq
							inner join tb_v_wvcs_info vcs on vcs.v_wvcs_seq = t1.v_wvcs_seq
							inner join tb_v_user_list v1 on vcs.v_user_list_seq = v1.v_user_list_seq
							inner join tb_v_user_list_info v2 on v1.v_user_list_seq = v2.v_user_list_seq
							LEFT JOIN tb_scan_center cn ON vcs.scan_center_code = cn.scan_center_code
						WHERE ".getCheckScanCenterAuthQuery('cn.org_id','cn.scan_center_code')
							.$args['search_sql'];
			
			$result = $this->fetch($sql);
			
			return $result;
		}


		/*
		* 사용자 검사결과 - 악성코드 발견 내역 Count
		*/
		function getUserVCSVirusFileListCount($args){
					
			$this->args = $args;
			
			$sql = " select  count(v1.v_wvcs_seq) as cnt
					from tb_v_wvcs_info v1
						inner join tb_v_user v2 on v1.v_user_seq = v2.v_user_seq
						inner join tb_v_user_list v20 on v2.v_user_seq = v20.v_user_seq and v1.v_user_list_seq = v20.v_user_list_seq
						inner join tb_v_wvcs_info_detail v3 on v1.v_wvcs_seq = v3.v_wvcs_seq
						inner join tb_v_wvcs_info_file f1 on v1.v_wvcs_seq = f1.v_wvcs_seq and v3.v_wvcs_detail_seq = f1.v_wvcs_detail_seq
						left join tb_scan_center cn ON v1.scan_center_code = cn.scan_center_code
					where ".getCheckScanCenterAuthQuery('cn.org_id','cn.scan_center_code')."
							and f1.file_scan_result='VIRUS' "
							.$args['search_sql'];

			//echo nl2br($sql);

			$result = $this->fetch($sql);
			return $result;
		}

		/*
		* 사용자 검사결과 - 악성코드 발견 내역 
		*/
		function getUserVCSVirusFileList($args){
					
			$this->args = $args;

			$sql = " WITH ScanList AS
					(
						select  top ".$args['end']." 
							v20.in_time,v20.v_user_name,v20.v_user_name_en,v20.v_user_belong,v20.v_purpose,cn.scan_center_name
							,f1.v_wvcs_file_seq, f1.md5, f1.file_path, f1.file_name_org
							,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
						from tb_v_wvcs_info v1
							inner join tb_v_user v2 on v1.v_user_seq = v2.v_user_seq
							inner join tb_v_user_list v20 on v2.v_user_seq = v20.v_user_seq and v1.v_user_list_seq = v20.v_user_list_seq
							inner join tb_v_wvcs_info_detail v3 on v1.v_wvcs_seq = v3.v_wvcs_seq
							inner join tb_v_wvcs_info_file f1 on v1.v_wvcs_seq = f1.v_wvcs_seq and v3.v_wvcs_detail_seq = f1.v_wvcs_detail_seq
							left join tb_scan_center cn ON v1.scan_center_code = cn.scan_center_code
						where ".getCheckScanCenterAuthQuery('cn.org_id','cn.scan_center_code')."
							and f1.file_scan_result='VIRUS' "
							.$args['search_sql']."
					) 
					SELECT a.*
					FROM ScanList  a
					WHERE rnum > ".$args['start'];
				

				if($args['excel_download_flag']=="1"){
					$sql .= " AND rnum <= " . ($args['start'] + RECORD_LIMIT_PER_FILE);	
				}

				$result = $this->fetchAll($sql);
				return $result;
		}


		/*
		* 점검결과 악성코드 내역 가져오기
		*/
		function getVCSVirusFileDetailList($args){
			
			$this->args = $args;

			$sql = "select distinct vc1.vaccine_name,vc1.scan_date,vc2.virus_path,vc2.virus_name,vc2.v_wvcs_file_seq
					from tb_v_wvcs_vaccine vc1
						inner join  tb_v_wvcs_vaccine_detail vc2 on vc1.vaccine_seq = vc2.vaccine_seq
						inner join tb_v_wvcs_info v1 on vc1.v_wvcs_seq = v1.v_wvcs_seq
						inner join tb_v_user v2 on v1.v_user_seq = v2.v_user_seq
						inner join tb_v_user_list v20 on v2.v_user_seq = v20.v_user_seq and v1.v_user_list_seq = v20.v_user_list_seq
						inner join tb_v_wvcs_info_detail v3 on v1.v_wvcs_seq = v3.v_wvcs_seq
						left join tb_scan_center cn ON v1.scan_center_code = cn.scan_center_code
					where ".getCheckScanCenterAuthQuery('cn.org_id','cn.scan_center_code')
						.$args['search_sql'];

			//echo nl2br($sql);
			$result = $this->fetchAll($sql);
			return $result;
		}


    }
?>