<?php
	/*
	* 방문객 Class
	*/
	Class Model_User extends Model {
		
		/*
		* 출입관리내역 가져오기
		*/
		function getUserVistList($args){

				$this->args = $args;

				$sql = " WITH VisitList AS
					(
						select  top ".$args['end']." 
							v2.v_user_list_seq,v2.v_user_name,v2.v_user_name_en, v_phone,v_email,v_company,v_purpose,manager_name,manager_name_en,manager_dept
							,v2.additional_cnt, v2.memo,v2.in_time,v2.in_center_code,c.scan_center_name as in_center_name,v2.v_user_belong,v2.v_user_type,v2.v_type
							,v3.pass_card_no,v3.elec_doc_number,v3.label_name,v3.label_value
							,v2.visit_date,v3.visit_center_desc, v2.visit_status, v2.out_time, vo.access_date as out_access_time, vo.access_emp_id as out_access_emp_id, vo.access_emp_name as out_access_emp_name
							,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
						from tb_v_user v1
							inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
							left join tb_v_user_list_info v3 on v2.v_user_list_seq = v3.v_user_list_seq
							left join tb_scan_center c on v2.in_center_code = c.scan_center_code
							left join tb_v_user_list_inout_log vo on v2.v_user_list_seq = vo.v_user_list_seq
								and vo.v_user_list_inout_log_seq = (
											select top 1 v_user_list_inout_log_seq 
											from tb_v_user_list_inout_log 
											where visit_status='0' and v_user_list_seq = v2.v_user_list_seq 
											order by v_user_list_inout_log_seq desc)
						where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')
							.$args['search_sql']." 
					) 
					SELECT a.*
						,(select top 1 elec_doc_number 
							from tb_v_user_list_goods 
							where v_user_list_seq = a.v_user_list_seq
								and elec_doc_number > '') as in_goods_doc_no
					FROM VisitList  a
					WHERE rnum > ".$args['start'];

				if($args['excel_download_flag']=="1"){
					$sql .= " AND rnum <= " . ($args['start'] + RECORD_LIMIT_PER_FILE);	
				}

				$result = $this->fetchAll($sql);
				// echo nl2br($sql);

		
				return $result;

		}

		/*
		* 출입관리내역 count
		*/
		function getUserVistListCount($args){

			$this->args = $args;

			$sql = " select  count(v2.v_user_list_seq) as cnt
					from tb_v_user v1
							inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
							left join tb_v_user_list_info v3 on v2.v_user_list_seq = v3.v_user_list_seq
							left join tb_scan_center c on v2.in_center_code = c.scan_center_code
					where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')
						.$args['search_sql'];
			
			$result = $this->fetch($sql);
		//  echo nl2br($sql);
			 
			
			return $result;
		}

		/*
		* IDC 출입관리내역 가져오기
		*/
		function getUserVistList_IDC($args){

				$this->args = $args;

				$sql = " WITH VisitList AS
					(
						select  top ".$args['end']." 
							v2.v_user_list_seq,v2.v_user_name,v2.v_user_name_en,		
							v1.v_phone,v1.v_email,v_company,v_purpose,manager_name,manager_name_en,manager_dept
							,v2.additional_cnt, v2.memo,v2.in_time,v2.out_time,v2.in_center_code
							,c.scan_center_name as in_center_name,v2.v_user_belong,v2.v_user_type,v2.v_type
							,v3.pass_card_no,v3.elec_doc_number,v3.label_name,v3.label_value,v3.work_number
							,v2.visit_date,v2.security_agree_yn,  v2.visit_status,v3.visit_center_desc
							,d1.user_doc_seq as user_vsr_doc_seq, d2.user_doc_seq as user_mgr_doc_seq
							,vi.access_date as in_access_time, vi.access_emp_id as in_access_emp_id, vi.access_emp_name as in_access_emp_name
							,vo.access_date as out_access_time, vo.access_emp_id as out_access_emp_id, vo.access_emp_name as out_access_emp_name
							,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
						from tb_v_user v1
							inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
							left join tb_v_user_list_info v3 on v2.v_user_list_seq = v3.v_user_list_seq
							left join tb_scan_center c on v2.in_center_code = c.scan_center_code
							left join tb_v_user_list_inout_log vi on v2.v_user_list_seq = vi.v_user_list_seq
								and vi.v_user_list_inout_log_seq = (
											select top 1 v_user_list_inout_log_seq 
											from tb_v_user_list_inout_log 
											where visit_status='1' and v_user_list_seq = v2.v_user_list_seq 
											order by v_user_list_inout_log_seq desc) 
							left join tb_v_user_list_inout_log vo on v2.v_user_list_seq = vo.v_user_list_seq
								and vo.v_user_list_inout_log_seq = (
											select top 1 v_user_list_inout_log_seq 
											from tb_v_user_list_inout_log 
											where visit_status='0' and v_user_list_seq = v2.v_user_list_seq 
											order by v_user_list_inout_log_seq desc)
							left join tb_v_user_doc d1 on v2.v_user_list_seq = d1.v_user_list_seq 
								and d1.doc_div = 'VSR_IDC_REPORT'
							left join tb_v_user_doc d2 on v2.v_user_list_seq = d2.v_user_list_seq 
								and d2.doc_div = 'MGR_IDC_REPORT'
						where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')
							.$args['search_sql']." 
					) 
					SELECT a.*
						,(select top 1 elec_doc_number 
							from tb_v_user_list_goods 
							where v_user_list_seq = a.v_user_list_seq
								and elec_doc_number > '') as in_goods_doc_no
					FROM VisitList  a
					WHERE rnum > ".$args['start'];

				if($args['excel_download_flag']=="1"){
					$sql .= " AND rnum <= " . ($args['start'] + RECORD_LIMIT_PER_FILE);	
				}

				$result = $this->fetchAll($sql);
				//echo nl2br($sql);

		
				return $result;

		}

		/*
		* IDC 출입관리내역 count
		*/
		function getUserVistListCount_IDC($args){

			$this->args = $args;

			$sql = " select  count(v2.v_user_list_seq) as cnt
					from tb_v_user v1
							inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
							left join tb_v_user_list_info v3 on v2.v_user_list_seq = v3.v_user_list_seq
							left join tb_scan_center c on v2.in_center_code = c.scan_center_code
							left join tb_v_user_list_inout_log vi on v2.v_user_list_seq = vi.v_user_list_seq
								and vi.v_user_list_inout_log_seq = (
											select top 1 v_user_list_inout_log_seq 
											from tb_v_user_list_inout_log 
											where visit_status='1' and v_user_list_seq = v2.v_user_list_seq 
											order by v_user_list_inout_log_seq desc) 
							left join tb_v_user_list_inout_log vo on v2.v_user_list_seq = vo.v_user_list_seq
								and vi.v_user_list_inout_log_seq = (
											select top 1 v_user_list_inout_log_seq 
											from tb_v_user_list_inout_log 
											where visit_status='0' and v_user_list_seq = v2.v_user_list_seq 
											order by v_user_list_inout_log_seq desc)
					where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')
						.$args['search_sql'];
			
			$result = $this->fetch($sql);
		//  echo nl2br($sql);
			 
			
			return $result;
		}


		/*
		* 출입관리내역 가져오기 - 파일반입
		*/
		function getUserVistList_File($args){

			$this->args = $args;

				$sql = " WITH VisitList AS
					(
						select  top ".$args['end']." 
							v2.v_user_list_seq,v2.v_user_name,v2.v_user_name_en, v_phone,v_email,v_company,v_purpose,manager_name,manager_name_en,manager_dept
							,v2.additional_cnt, v2.memo,v2.in_time,v2.in_center_code,c.scan_center_name as in_center_name,v2.v_user_belong,v2.v_user_type,v2.v_type
							,v3.pass_card_no,v3.elec_doc_number,v3.label_name,v3.label_value,v3.usb_return_date, e.emp_name as usb_return_emp_name
							,v3.visit_center_desc, v2.visit_date,v3.work_number
							,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
						from tb_v_user v1
							inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
							left join tb_v_user_list_info v3 on v2.v_user_list_seq = v3.v_user_list_seq
							left join tb_scan_center c on v2.in_center_code = c.scan_center_code
							left join tb_employee e on e.emp_seq = v3.usb_return_emp_seq
						where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')
							.$args['search_sql']." 
					) 
					SELECT a.*
						,isnull(vcs.vcs_cnt,0) as vcs_cnt, isnull(vcs.v_wvcs_seq,0) as v_wvcs_seq
					FROM VisitList  a
						left join (
							Select v_user_list_seq,count(v_wvcs_seq) as vcs_cnt ,max(v_wvcs_seq) as v_wvcs_seq
							From tb_v_wvcs_info
							Group by v_user_list_seq) vcs on a.v_user_list_seq = vcs.v_user_list_seq
					WHERE rnum > ".$args['start'];

				if($args['excel_download_flag']=="1"){
					$sql .= " AND rnum <= " . ($args['start'] + RECORD_LIMIT_PER_FILE);	
				}

				$result = $this->fetchAll($sql);
				
				//echo $sql;
		
				return $result;

		}

		/*
		* 출입관리내역 count- 파일반입
		*/
		function getUserVistListCount_File($args){

			$this->args = $args;

			$sql = " select  count(v2.v_user_list_seq) as cnt
					from tb_v_user v1
							inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
							left join tb_v_user_list_info v3 on v2.v_user_list_seq = v3.v_user_list_seq
							left join tb_scan_center c on v2.in_center_code = c.scan_center_code
					where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')
						.$args['search_sql'];
			
			$result = $this->fetch($sql);
			  //echo nl2br($sql);
			
			return $result;
		}

		/*
		* 출입관리내역 가져오기 - 임시출입증발급
		*/
		function getUserVistList_Pass($args){

			$this->args = $args;

				$sql = " WITH VisitList AS
					(
						select  top ".$args['end']." 
							v2.v_user_list_seq,v2.v_user_name,v2.v_user_name_en, v_phone,v_email,v_company,v_purpose,manager_name,manager_name_en,manager_dept
							,v2.additional_cnt, v2.memo,v2.in_time,v2.in_center_code,c.scan_center_name as in_center_name,v2.v_user_belong,v2.v_user_type,v2.v_type
							,v3.pass_card_no,v3.pass_card_return_date,v3.elec_doc_number,v3.label_name,v3.label_value
							,v4.emp_name
							,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
						from tb_v_user v1
							inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
							left join tb_v_user_list_info v3 on v2.v_user_list_seq = v3.v_user_list_seq
							left join tb_scan_center c on v2.in_center_code = c.scan_center_code
							left join tb_employee v4 on v4.emp_seq = v3.pass_card_return_emp_seq
						where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')
							.$args['search_sql']." 
					) 
					SELECT a.*
					FROM VisitList  a
					WHERE rnum > ".$args['start'];

				if($args['excel_download_flag']=="1"){
					$sql .= " AND rnum <= " . ($args['start'] + RECORD_LIMIT_PER_FILE);	
				}

				$result = $this->fetchAll($sql);
				// echo nl2br($sql);

		
				return $result;

		}

		/*
		* 출입관리내역 count- 임시출입증발급
		*/
		function getUserVistListCount_Pass($args){

			$this->args = $args;

			$sql = " select  count(v2.v_user_list_seq) as cnt
					from tb_v_user v1
							inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
							left join tb_v_user_list_info v3 on v2.v_user_list_seq = v3.v_user_list_seq
							left join tb_scan_center c on v2.in_center_code = c.scan_center_code
					where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')
						.$args['search_sql'];
			
			$result = $this->fetch($sql);
			 
			
			return $result;
		}

		/*
		* 출입관리내역 info
		*/
		function getUserVistListDetailsInfo($args){

			$this->args = $args;

				$sql = "
						select  
							v1.v_user_seq,v2.v_user_type,
							v2.v_user_list_seq,v2.v_user_name,v2.v_user_name_en, v_phone,v_email,v_company,v_purpose,manager_name,manager_name_en,manager_dept, v2.visit_status, v2.out_time
							,v2.additional_cnt, v2.memo,v2.in_time,v2.in_center_code,c.scan_center_name as in_center_name
							,v3.pass_card_no,v3.label_name,v3.label_value,v3.elec_doc_number,v2.create_date,v2.v_user_belong, vo.access_date as out_access_time, vo.access_emp_id as out_access_emp_id, vo.access_emp_name as out_access_emp_name
							,v3.pass_card_return_schedule_date,v3.pass_card_return_date,v4.emp_name,v2.security_agree_date,v2.security_agree_yn
							,isnull(v3.usb_return_schedule_date,convert(varchar(8),getdate(),112)) as usb_return_schedule_date, v3.usb_return_date,v5.emp_name as usb_return_emp_name
							,(select top 1 policy_file_in_seq from tb_policy_file_in where v_user_list_seq = v2.v_user_list_seq) as policy_file_in_seq
						  from tb_v_user v1
							inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
							left join tb_v_user_list_info v3 on v2.v_user_list_seq = v3.v_user_list_seq
							left join tb_scan_center c on v2.in_center_code = c.scan_center_code
							left join tb_employee v4 on v4.emp_seq = v3.pass_card_return_emp_seq
							left join tb_employee v5 on v5.emp_seq = v3.usb_return_emp_seq
							left join tb_v_user_list_inout_log vo on v2.v_user_list_seq = vo.v_user_list_seq
								and vo.v_user_list_inout_log_seq = (
											select top 1 v_user_list_inout_log_seq 
											from tb_v_user_list_inout_log 
											where visit_status='0' and v_user_list_seq = v2.v_user_list_seq 
											order by v_user_list_inout_log_seq desc)
						where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')." 
						and v2.v_user_list_seq= ".$args['v_user_list_seq']." ";

				$result = $this->fetchAll($sql);
				// echo nl2br($sql);

				return $result;

		}
		/*
		* IDC 출입관리내역 info
		*/
		function getUserVistListDetailsInfo_IDC($args){

			$this->args = $args;

				$sql = "
						select  
							v1.v_user_seq,v2.v_user_type,
							v2.v_user_list_seq,v2.v_user_name,v2.v_user_name_en, v_phone,v_email,v_company,v_purpose,v_purpose_desc,manager_name,manager_name_en,manager_dept
							,v2.additional_cnt, v2.memo,v2.in_time,v2.out_time, v2.visit_date,v2.in_center_code,c.scan_center_name as in_center_name
							,v3.pass_card_no,v3.label_name,v3.label_value,v3.elec_doc_number,v2.create_date,v2.v_user_belong
							,v3.pass_card_return_schedule_date,v3.pass_card_return_date,v4.emp_name,v2.security_agree_date,v2.security_agree_yn
							,isnull(v3.usb_return_schedule_date,convert(varchar(8),getdate(),112)) as usb_return_schedule_date, v3.usb_return_date,v5.emp_name as usb_return_emp_name
							,v3.visit_center_desc,v2.visit_status,v2.security_agree_yn, v2.security_agree_date
							,vi.access_date as in_access_time, vi.access_emp_id as in_access_emp_id, vi.access_emp_name as in_access_emp_name
							,vo.access_date as out_access_time, vo.access_emp_id as out_access_emp_id, vo.access_emp_name as out_access_emp_name
							,(select top 1 policy_file_in_seq from tb_policy_file_in where v_user_list_seq = v2.v_user_list_seq) as policy_file_in_seq
							,v3.work_number
						  from tb_v_user v1
							inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
							left join tb_v_user_list_info v3 on v2.v_user_list_seq = v3.v_user_list_seq
							left join tb_scan_center c on v2.in_center_code = c.scan_center_code
							left join tb_employee v4 on v4.emp_seq = v3.pass_card_return_emp_seq
							left join tb_employee v5 on v5.emp_seq = v3.usb_return_emp_seq
							left join tb_v_user_list_inout_log vi on v2.v_user_list_seq = vi.v_user_list_seq
								and vi.v_user_list_inout_log_seq = (
											select top 1 v_user_list_inout_log_seq 
											from tb_v_user_list_inout_log 
											where visit_status='1' and v_user_list_seq = v2.v_user_list_seq 
											order by v_user_list_inout_log_seq desc) 
							left join tb_v_user_list_inout_log vo on v2.v_user_list_seq = vo.v_user_list_seq
								and vo.v_user_list_inout_log_seq = (
											select top 1 v_user_list_inout_log_seq 
											from tb_v_user_list_inout_log 
											where visit_status='0' and v_user_list_seq = v2.v_user_list_seq 
											order by v_user_list_inout_log_seq desc)
						where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')." 
						and v2.v_user_list_seq= ".$args['v_user_list_seq']." ";

				$result = $this->fetchAll($sql);
				// echo nl2br($sql);

				return $result;

		}
		/*
		* 자산반입정보 가져오기
		*/
		function getImportInfo($args){

			$this->args = $args;

				$sql = " Select 
						v_user_list_goods_seq, v_user_list_seq, goods_kind, goods_name, model_name, serial_number, elec_doc_number
						, item_mgt_number, out_schedule_date, inout_status, in_date, out_date, out_emp_seq 
						,e.emp_name,g.memo
					From tb_v_user_list_goods g
						left join tb_employee e on g.out_emp_seq  = e.emp_seq
					Where v_user_list_seq = '".$args['v_user_list_seq']."'  ";

				$result = $this->fetchAll($sql);
	
				return $result;

		}
		
		/*
		* 임시출입증발급 info
		*/
		function TempopraryPassDeatils($args){

			$this->args = $args;

				$sql = "
						select  
							v2.v_user_list_seq,v2.v_user_name,v2.v_user_name_en, v_phone,v_email,v_company,v_purpose,manager_name,manager_name_en,manager_dept
							,v2.additional_cnt, v2.memo,v2.in_time,v2.in_center_code,c.scan_center_name as in_center_name,v2.v_user_belong
							,v3.pass_card_no,v3.pass_card_return_schedule_date,v3.pass_card_return_date,v3.pass_card_return_emp_seq,v4.emp_no,v4.emp_name,v3.label_name,v3.label_value,v3.elec_doc_number
						from tb_v_user v1
							inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
							left join tb_v_user_list_info v3 on v2.v_user_list_seq = v3.v_user_list_seq
							left join tb_scan_center c on v2.in_center_code = c.scan_center_code
							left join tb_employee v4 on v4.emp_seq = v3.pass_card_return_emp_seq
						where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')." 
						and v2.v_user_list_seq= "	.$args['v_user_list_seq']." ";

				$result = $this->fetchAll($sql);
				

			
				return $result;

		}

		/*
		* 물품 대여내역 가져오기
		*/
		function getItemRentalDetailsList($args){

			$this->args = $args;

				$sql = " WITH VisitList AS
					(
						select  top ".$args['end']." 
						t1.emp_no,t1.emp_name,r1.rent_list_seq, user_type,user_name,user_name_en,user_company,user_dept,user_phone,item_name,item_mgt_number,rent_purpose,rent_center_code,return_schedule_date,rent_date,return_date,return_emp_seq,create_date,memo,user_agree_yn	,user_belong
							,c.scan_center_name
							,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
						from tb_rent_list r1
							left join tb_employee t1 on t1.emp_seq = r1.return_emp_seq
							left join tb_scan_center c on r1.rent_center_code = c.scan_center_code
						WHERE ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')."
							" .$args['search_sql']." 

					) 
					SELECT a.*
					FROM VisitList  a
					WHERE rnum > ".$args['start'];

				if($args['excel_download_flag']=="1"){
					$sql .= " AND rnum <= " . ($args['start'] + RECORD_LIMIT_PER_FILE);	
				}
				$result = $this->fetchAll($sql);

				//echo $sql;

				return $result;

		}
		/*
		* 물품 대여내역 count
		*/
		function getItemRentalDetailsCount($args){

			$this->args = $args;

			$sql = " select  count(rent_list_seq) as cnt
					from tb_rent_list r1
						left join tb_employee t1 on t1.emp_seq = r1.return_emp_seq
						left join tb_scan_center c on r1.rent_center_code = c.scan_center_code
					WHERE ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')."
							" .$args['search_sql'];			
			$result = $this->fetch($sql);
			

	
			return $result;
		}

		/*
		* 물품 대여내역 info
		*/
		function getItemRentalDetailsInfo($args){

			$this->args = $args;

				$sql = "
						select t1.emp_no,t1.emp_name,r1.rent_list_seq, user_type,user_name,user_name_en,user_company,user_dept,user_phone,item_name,item_mgt_number,rent_purpose,rent_center_code,return_schedule_date,rent_date,return_date,return_emp_seq,create_date,memo,user_agree_yn,user_belong,c.scan_center_name
						from tb_rent_list r1
							left join tb_employee t1 on t1.emp_seq = r1.return_emp_seq
							left join tb_scan_center c on r1.rent_center_code = c.scan_center_code
						WHERE rent_list_seq=" .$args['rent_list_seq']."";
				$result = $this->fetchAll($sql);


				
				return $result;

		}
				/*
		* 주차권 지급내역 가져오기
		*/
		function getItemParkingDetailsList($args){

			$this->args = $args;

				$sql = " WITH VisitList AS
					(
						select  top ".$args['end']." 
						ticket_list_seq,user_name,user_name_en,serve_time,car_number,user_type,user_dept,ticket_desc,memo,create_date,user_agree_yn,user_belong
						,user_company,out_time,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
						from tb_parking_ticket t1
							left join tb_scan_center c on t1.reg_center_code = c.scan_center_code
						WHERE   ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code') .$args['search_sql']." 

					) 
					SELECT a.*
					FROM VisitList  a
					WHERE rnum > ".$args['start'];

				if($args['excel_download_flag']=="1"){
					$sql .= " AND rnum <= " . ($args['start'] + RECORD_LIMIT_PER_FILE);	
				}
				$result = $this->fetchAll($sql);

			

				
				
				
				return $result;

		}
		/*
		* 주차권 지급내역 count
		*/
		function getItemParkingDetailsCount($args){

			$this->args = $args;

			$sql = " select  count(ticket_list_seq) as cnt
					from tb_parking_ticket t1
							left join tb_scan_center c on t1.reg_center_code = c.scan_center_code
					WHERE  ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code') .$args['search_sql'];				
			$result = $this->fetch($sql);
			

	
			return $result;
		}
		/*
		* 주차권 지급내역 info
		*/
		function getItemParkingDetailsInfo($args){

			$this->args = $args;

				$sql = "
						select ticket_list_seq,user_name,user_name_en,serve_time,car_number,ticket_desc,memo,create_date,user_agree_yn,
						user_company,user_type,user_dept, user_belong,out_time
						from tb_parking_ticket
						WHERE ticket_list_seq=" .$args['ticket_list_seq']."";
				$result = $this->fetchAll($sql);
		
				return $result;

		}

		/*
		* 외부인력 정보교육 가져오기
		*/
		function getItemTrainDetailsList($args){

			$this->args = $args;

				$sql = " WITH VisitList AS
					(
						select  top ".$args['end']." 
						train_seq,train_name,project_name,user_name,user_name_en,user_company,train_date,manager_name,
						manager_name_en,manager_company,manager_dept,memo,create_date,manager_type,manager_belong,
						user_agree_yn
							,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
						from tb_train_list t1
							left join tb_scan_center c on t1.reg_center_code = c.scan_center_code
						WHERE  ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code') .$args['search_sql']." 

					) 
					SELECT a.*
					FROM VisitList  a
					WHERE rnum > ".$args['start'];

				if($args['excel_download_flag']=="1"){
					$sql .= " AND rnum <= " . ($args['start'] + RECORD_LIMIT_PER_FILE);	
				}
				$result = $this->fetchAll($sql);
				
				//echo $sql;
				
				return $result;

		}
		/*
		* 외부인력 정보교육 count
		*/
		function getItemTrainDetailsCount($args){

			$this->args = $args;

			$sql = " select  count(train_seq) as cnt
					from tb_train_list t1
							left join tb_scan_center c on t1.reg_center_code = c.scan_center_code
					WHERE  ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code') .$args['search_sql'];				
			$result = $this->fetch($sql);
			
			return $result;
		}
		/*
		* 외부인력 정보교육 info
		*/
		function getItemTrainDetailsInfo($args){

			$this->args = $args;

				$sql = "
						select train_seq,train_name,project_name,user_name,user_name_en,user_company,train_date,manager_name,
						manager_name_en,manager_company,manager_dept,manager_type,memo,create_date,user_agree_yn,manager_belong
						,c.scan_center_name
						from tb_train_list t1
							left join tb_scan_center c on t1.reg_center_code = c.scan_center_code
						WHERE train_seq=" .$args['train_seq']."";
				$result = $this->fetchAll($sql);


				
				return $result;

		}

		//get time with string
		function getParkingTicket($timeInHours) {
				$list = array(
				"0.5"=>"30분이내",
				"1"=>"1시간이내",
				"1.5"=>"1시간30분이내",
				"2"=>"2시간이내",
				"2.5"=>"2시간30분이내",
				"3"=>"3시간이내",
				"3.5"=>"3시간30분이내",
				"4"=>"4시간이내",
				"4.5"=>"4시간30분이내",
				"5"=>"5시간이내",
				"6"=>"5시간30분이내",
				"6.5"=>"6시간이내",
				"8"=> "종일"
				);

				if (isset($list[$timeInHours])) {
						return $list[$timeInHours];
				} else {
						return $timeInHours;
				}
    }


		/*
		* 주차권 요청시간 
		*/
		function getParkingTicketList(){
			
			$list = array(
				"0.5"=>"30분이내",
				"1"=>"1시간이내",
				"1.5"=>"1시간30분이내",
				"2"=>"2시간이내",
				"2.5"=>"2시간30분이내",
				"3"=>"3시간이내",
				"3.5"=>"3시간30분이내",
				"4"=>"4시간이내",
				"4.5"=>"4시간30분이내",
				"5"=>"5시간이내",
				"5.5"=>"5시간30분이내",
				"6"=>"6시간이내",
				"6.5"=>"6시간30분이내",
				"7"=>"7시간이내",
				"8"=> "종일"
			);

			return $list;
			
		}


		/*
		* 물품대여 메모 업데이트하기
		*/

		function updateRentListMemo($args) {

			$this->args = $args;

			$sql = "UPDATE tb_rent_list 
					SET  memo = '" . $args['memo'] . "'
						,access_emp_seq = '" . $_SESSION['user_seq'] . "' 
						,access_date = dbo.fn_ymdhis()
					WHERE rent_list_seq = '" . $args['rent_list_seq'] . "'";
				
			return $this->query($sql);
		}

		/*
		* 물품대여  업데이트하기
		*/
		function updateRentListInfo($args) {

			$this->args = $args;
			
			$return_schedule_date = preg_replace("/[^0-9]*/s", "", $args['return_schedule_date']);
			$rent_date = preg_replace("/[^0-9]*/s", "", $args['rent_date']);

			$user_phone_enc = aes_256_enc($args['user_phone']);
			$user_name_enc =  aes_256_enc($args['user_name']); 

			$user_phone = $args['user_phone'];
			$user_phone_raw = preg_replace("/[^0-9]*/s", "", $user_phone);
			
			//전화번호 숨김 상태 인지 체크
			if($user_phone==$user_phone_raw){
				$query_phone = ", user_phone = '" . $user_phone_enc. "' ";
			}else $query_phone = "";

			$sql = "UPDATE tb_rent_list 
					SET user_belong ='" . $args['user_belong'] . "'
						, memo = N'" . $args['memo'] . "'
						, user_dept = N'" . $args['user_dept'] . "'
						, user_name = '" . $user_name_enc . "'
						, user_name_en ='" . $args['user_name_en'] . "'
						, item_name = N'" . $args['item_name'] . "'
						, item_mgt_number ='" . $args['item_mgt_number'] . "'
						, rent_center_code ='" . $args['rent_center_code'] . "'
						, rent_purpose =N'" . $args['rent_purpose'] . "'
						, return_schedule_date ='".$return_schedule_date."'
						, rent_date ='".$rent_date."'
						, user_company ='".$args['user_company'] ."'
						, user_type ='".$args['user_type'] ."'
						,access_emp_seq = '" . $_SESSION['user_seq'] . "' 
						,access_date = dbo.fn_ymdhis()
						{$query_phone}
					WHERE rent_list_seq = '" . $args['rent_list_seq'] . "'";
	
			return $this->query($sql);
		}
		/*
		* 주차권 지급 업데이트하기
		*/

		function updateParingListMemo($args) {
			$this->args = $args;

			$sql = "UPDATE tb_parking_ticket 
				SET memo = '" . $args['memo'] . "'
					,access_emp_seq = '" . $_SESSION['user_seq'] . "' 
					,access_date = dbo.fn_ymdhis()
				WHERE ticket_list_seq = '" . $args['ticket_list_seq'] . "'";
				
			
			return $this->query($sql);
		}
		/*
		* 주차권 지급 업데이트하기
		*/

		function updateParkingListInfo($args) {
			$this->args = $args;
			
			$serve_time_value = preg_replace("/[^0-9.]*/s", "", $args['serve_time']);

			$car_number_enc = aes_256_enc($args['car_number']);
			$user_name_enc = aes_256_enc($args['user_name']);

			$sql = "UPDATE tb_parking_ticket 
				SET user_belong ='" . $args['user_belong'] . "
					',memo = N'" . $args['memo'] . "'
					,user_name = '" . $user_name_enc . "'
					,user_name_en ='" . $args['user_name_en'] . "'
					,ticket_desc = N'" . $args['ticket_desc'] . "'
					,car_number ='" . $car_number_enc . "'
					,serve_time ='" . $args['serve_time']. "'
					,user_company ='".$args['user_company'] ."'
					,user_type ='".$args['user_type'] ."'
					,user_dept = N'" . $args['user_dept'] . "'
					,out_time =  '" . $args['out_time'] . "' 
					,access_emp_seq = '" . $_SESSION['user_seq'] . "' 
					,access_date = dbo.fn_ymdhis()
				WHERE ticket_list_seq = '" . $args['ticket_list_seq'] . "'";
			
			return $this->query($sql);
		}
		/*
		* 주차권 지급 업데이트하기
		*/

		function updateTrainListInfo($args) {
			$this->args = $args;

			$train_date_value = preg_replace("/[^0-9]*/s", "", $args['train_date']);

			$user_name_enc = aes_256_enc($args['user_name']);
			$manager_name_enc = aes_256_enc($args['manager_name']);

			$sql = "UPDATE tb_train_list 
				SET user_company = '" . $args['user_company'] . "'
					,project_name = N'" . $args['project_name'] . "'
					,memo = N'" . $args['memo'] . "'
					,user_name = '" . $user_name_enc. "'
					,user_name_en ='" . $args['user_name_en'] . "' 
					,manager_type ='" . $args['manager_type'] . "'
					,manager_name ='" . $manager_name_enc. "'
					,manager_name_en ='" . $args['manager_name_en'] . "'
					,manager_belong = N'" . $args['manager_belong'] . "'
					,train_date ='" .$train_date_value . "'
					,manager_dept = N'" . $args['manager_dept'] . "'
					,manager_company = N'" . $args['manager_company'] . "'
					,access_emp_seq = '" . $_SESSION['user_seq'] . "' 
					,access_date = dbo.fn_ymdhis()
				WHERE train_seq = '" . $args['train_seq'] . "'";
				//  echo nl2br($sql);
				// 	 exit;
			
			return $this->query($sql);
		}
		/*
		* 주차권 지급 업데이트하기
		*/

		function updateTrainListMemo($args) {
			$this->args = $args;

			$sql = "UPDATE tb_train_list 
				SET memo = '" . $args['memo'] . "' 
					,access_emp_seq = '" . $_SESSION['user_seq'] . "' 
					,access_date = dbo.fn_ymdhis()
				WHERE train_seq = '" . $args['train_seq'] . "'";
			
			return $this->query($sql);
		}

		/*
		* 출입관리 비고 업데이트하기
		*/
		function updateUserVistListMemo($args) {
			$this->args = $args;

			$sql = "UPDATE tb_v_user_list
			 SET memo = '" . $args['memo'] . "'
				,access_emp_seq = '" . $_SESSION['user_seq'] . "' 
				,access_date = dbo.fn_ymdhis()
			 WHERE v_user_list_seq = '" . $args['v_user_list_seq'] . "'";
			//  echo nl2br($sql);

			 
			return $this->query($sql);
		}
		/*
		* 자산빈입정보 메모 업데이트하기
		*/
		function updateUserImportGoodsMemo($args) {
			$this->args = $args;

			$sql = "
			 UPDATE tb_v_user_list_goods
			 SET memo = N'" . $args['memo'] . "'
				,access_emp_seq = '" . $_SESSION['user_seq'] . "' 
				,access_date = dbo.fn_ymdhis()
			 WHERE v_user_list_goods_seq = '" . $args['v_user_list_goods_seq'] . "'";

			return $this->query($sql);
		}
		/*
		* 자산빈입정보  업데이트하기
		*/
		function updateUserImportGoods($args) {
			$this->args = $args;


			$sql = "
			 UPDATE tb_v_user_list_goods
			 SET goods_name = N'".$args['g_name']."'
					,model_name = N'".$args['g_model']."'
					,serial_number = N'".$args['g_sn']."'
					,elec_doc_number = '".$args['g_doc_no']."'
					,item_mgt_number = '".$args['g_mgt_no']."'
					,out_schedule_date = '".$args['g_out_schedule_date']."'
					,memo = N'" . $args['g_memo'] . "'
					,access_emp_seq = '" . $_SESSION['user_seq'] . "' 
					,access_date = dbo.fn_ymdhis()
			 WHERE v_user_list_goods_seq = '" . $args['v_user_list_goods_seq'] . "'";
		 
			return $this->query($sql);
		}
		/*
		* 출입관리 업데이트하기
		*/
		function updateVisitUser($args) {
			$this->args = $args;

			$user_phone_enc =aes_256_enc($args['v_phone']);
			$user_name_enc =aes_256_enc($args['v_user_name']);
			
			$v_phone = $args['v_phone'];
			$v_phone_raw = preg_replace("/[^0-9]*/s", "", $v_phone);
			
			//전화번호 숨김 상태 인지 체크
			if($v_phone==$v_phone_raw){
				$query_phone = ", v_phone = '" . $user_phone_enc. "' ";
			}else $query_phone = "";

			$v_user_type = $args['v_user_type'];

			if($v_user_type=="OUT"){
				$v_com_name = $args['v_user_belong'];
			}else{
				$v_com_name = COMPANY_NAME;
			}

			$sql = "UPDATE tb_v_user
						SET v_user_name = '" . $user_name_enc . "'	
						,v_user_name_en = '" . $args['v_user_name_en'] . "'
						,v_com_name = N'".$v_com_name."'
						".$query_phone."	
						WHERE v_user_seq in( SELECT v_user_seq
				  FROM tb_v_user_list
				WHERE v_user_list_seq = '" . $args['v_user_list_seq'] . "' )";

			//echo $sql;
			//exit;
			
			return $this->query($sql);
		}

		/*
		* 출입관리 업데이트하기
		*/
		function updateVisitUserList($args) {
			$this->args = $args;

			$in_time_value = preg_replace("/[^0-9]*/s", "", $args['in_time']);

			if($args['additional_cnt'==""]){
				$args['additional_cnt'==""]="0";
			}

			$user_name_enc =aes_256_enc($args['v_user_name']);
			$manager_name_enc =aes_256_enc($args['manager_name']);

			$v_user_type = $args['v_user_type'];

			if($v_user_type=="OUT"){
				$v_company = $args['v_user_belong'];
			}else{
				$v_company = COMPANY_NAME;
			}

			$sql = "UPDATE tb_v_user_list
			SET v_user_name_en = '" . $args['v_user_name_en'] . "'
				,v_user_name =  '" . $user_name_enc . "'
				,memo = N'" . $args['memo'] . "'					
				,v_user_belong = N'" . $args['v_user_belong'] . "'	
				,v_company = N'" . $v_company . "'	
				,v_purpose = N'" . $args['v_purpose'] . "'					
				,manager_dept = N'" . $args['manager_dept'] . "'					
				,manager_name = '" . $manager_name_enc . "'					
				,manager_name_en = '" . $args['manager_name_en'] . "'	
				,in_time = '" . $in_time_value. "'
				,additional_cnt = '" . $args['additional_cnt'] . "'
				,access_emp_seq = '" . $_SESSION['user_seq'] . "' 
				,access_date = dbo.fn_ymdhis()
				,in_center_code =  '" . $args['in_center_code'] . "'
				,out_center_code =  '" . $args['in_center_code'] . "'
			WHERE v_user_list_seq = '" . $args['v_user_list_seq'] . "'";
			

			return $this->query($sql);
		}
		/*
		* IDC 출입관리 업데이트하기
		*/
		function updateVisitUserList_IDC($args) {
			$this->args = $args;

			$user_name_enc =aes_256_enc($args['v_user_name']);
			$v_user_name_en = $args['v_user_name_en'];
			$v_user_belong = $args['v_user_belong'];
			$v_user_type = $args['v_user_type'];
			$access_emp_seq = $_SESSION['user_seq'];
			$memo = $args['memo'];

			if($v_user_type=="OUT"){
				$v_company = $v_user_belong;
			}else{
				$v_company = COMPANY_NAME;
			}

			$sql = "
				UPDATE tb_v_user_list
				SET    v_user_name_en =  '{$v_user_name_en}'
					 , v_user_name    =  '{$user_name_enc}'
					 , v_user_type    = N'{$v_user_type}'
					 , v_user_belong  = N'{$v_user_belong}'	
					 , v_company      = N'{$v_company}'	
					 , access_emp_seq =  '{$access_emp_seq}' 
					 , memo           = N'{$memo}'
					 , access_date    = dbo.fn_ymdhis()
				WHERE  v_user_list_seq = '" . $args['v_user_list_seq'] . "'
			";
	
			return $this->query($sql);
		}
		/*
		* 출입관리 정보 업데이트(전자문서번호,작업번호)
		*/
		function updateVisitUserListInfo($args) {
			$this->args = $args;

			$sql = "UPDATE tb_v_user_list_info
			SET elec_doc_number = '" . $args['elec_doc_number'] . "'
				,work_number = N'" . $args['work_number'] . "'
				,access_emp_seq = '" . $_SESSION['user_seq'] . "' 
				,access_date = dbo.fn_ymdhis()
			WHERE v_user_list_seq = '" . $args['v_user_list_seq'] . "'";
			
			return $this->query($sql);
		}

		/*
		* 출입관리 정보 업데이트(작업번호)
		*/
		function updateVisitUserListWorkInfo($args) {

			$this->args = $args;

			$ymdhis = date("YmdHis");

			$work_number = preg_replace('/\s+/', '', $args['work_number']);

			$sql = "Delete From tb_v_user_list_work where v_user_list_seq = '".$args[v_user_list_seq]."' ";

			$result = $this->query($sql);
			
			if($result){
				$sql = "Insert into tb_v_user_list_work (v_user_list_seq,work_number,create_date)
				Select '".$args[v_user_list_seq]."',value,'".$ymdhis."' from dbo.fn_split('".$work_number."',',') ";
				$result =  $this->query($sql);
			}
			
			return $result;

		}


		/*
		* 사내 USB 정보 업데이트하기
		*/

		function updateUsbInfo($args) {
			$this->args = $args;

			$usb_return_schedule_date_value = preg_replace("/[^0-9]*/s", "", $args['usb_return_schedule_date']);

			$sql = "UPDATE tb_v_user_list_info
			SET usb_return_schedule_date = '" . $usb_return_schedule_date_value . "'
				,label_value = N'" . $args['label_value'] . "'
				,access_emp_seq = '" . $_SESSION['user_seq'] . "' 
				,access_date = dbo.fn_ymdhis()
			WHERE v_user_list_seq = '" . $args['v_user_list_seq'] . "'";
			
			return $this->query($sql);
		}

		/*
		* 임시출입증 정보 업데이트하기
		*/

		function updatepassCardInfo($args) {
			$this->args = $args;

			$pass_card_return_schedule_date = preg_replace("/[^0-9]*/s", "", $args['pass_card_return_schedule_date']);

			$sql = "UPDATE tb_v_user_list_info
			SET pass_card_return_schedule_date = '" . $pass_card_return_schedule_date . "'
				,pass_card_no = N'" . $args['pass_card_no'] . "'
				,access_emp_seq = '" . $_SESSION['user_seq'] . "' 
				,access_date = dbo.fn_ymdhis()
			WHERE v_user_list_seq = '" . $args['v_user_list_seq'] . "'";
			
			return $this->query($sql);
		}
		/*
		* 대여물품 회수처리 업데이트하기
		*/
		function updateRentRecoveryList($args) {
			$this->args = $args;
			
			$ymdhis = date("YmdHis");
			$sql = "UPDATE tb_rent_list 
						SET return_date = '" . $ymdhis . "'
							,return_emp_seq = '" . $_SESSION['user_seq'] . "' 
							,access_emp_seq = '" . $_SESSION['user_seq'] . "' 
							,access_date = dbo.fn_ymdhis()
						WHERE rent_list_seq = '" . $args['rent_list_seq'] . "'";

					return $this->query($sql);
			}

		/*
		* 대여물품 회수처리 취소하기 
		*/
		function cancelRentCollection($args) {
			$this->args = $args;

			$sql = "UPDATE tb_rent_list 
					SET return_date = null
						, return_emp_seq = '" . $_SESSION['user_seq'] . "' 
						,access_emp_seq = '" . $_SESSION['user_seq'] . "' 
						,access_date = dbo.fn_ymdhis() 
					WHERE rent_list_seq = '" . $args['rent_list_seq'] . "'";
					return $this->query($sql);
			}

		/*
		*  임시출입증 반납처리하기
		*/
		function updateReturnTempopraryProc($args) {
			$this->args = $args;
			
			$ymdhis = date("YmdHis");
			$sql = "UPDATE tb_v_user_list_info 
					SET pass_card_return_date = '" . $ymdhis . "'
						, pass_card_return_emp_seq = '" . $_SESSION['user_seq'] . "' 
						,access_emp_seq = '" . $_SESSION['user_seq'] . "' 
						,access_date = dbo.fn_ymdhis()
					WHERE v_user_list_seq = '" . $args['v_user_list_seq'] . "'";

					return $this->query($sql);
			}

		/*
		* 임시출입증 반납처리 취소 하기
		*/
		function cancelReturnTempopraryProc($args) {
			$this->args = $args;

			$sql = "UPDATE tb_v_user_list_info 
					SET pass_card_return_date = null
					, pass_card_return_emp_seq = '" . $_SESSION['user_seq'] . "' 
					,access_emp_seq = '" . $_SESSION['user_seq'] . "' 
					,access_date = dbo.fn_ymdhis()
					WHERE v_user_list_seq = '" . $args['v_user_list_seq'] . "'";
					return $this->query($sql);
		}


		/*
		*  사내usb 반납처리하기
		*/
		function updateReturnUsbProc($args) {
			$this->args = $args;
			
			$ymdhis = date("YmdHis");
			$sql = "UPDATE tb_v_user_list_info 
					SET usb_return_date = '" . $ymdhis . "'
						, usb_return_emp_seq = '" . $_SESSION['user_seq'] . "' 
						,access_emp_seq = '" . $_SESSION['user_seq'] . "' 
						,access_date = dbo.fn_ymdhis()
					WHERE v_user_list_seq = '" . $args['v_user_list_seq'] . "'";

					return $this->query($sql);
			}

		/*
		* 사내usb 반납처리 취소 하기
		*/
		function cancelReturnUsbProc($args) {
			$this->args = $args;

			$sql = "UPDATE tb_v_user_list_info 
					SET usb_return_date = null
					, usb_return_emp_seq = '" . $_SESSION['user_seq'] . "' 
					,access_emp_seq = '" . $_SESSION['user_seq'] . "' 
					,access_date = dbo.fn_ymdhis()
					WHERE v_user_list_seq = '" . $args['v_user_list_seq'] . "'";
					return $this->query($sql);
		}
		/*
		*  사내usb 반납처리하기
		*/
		function updateOutGoodsProc($args) {
			$this->args = $args;
			
			$ymdhis = date("YmdHis");
			$sql = "UPDATE tb_v_user_list_goods 
					SET out_date = '" . $ymdhis . "'
						, out_emp_seq = '" . $_SESSION['user_seq'] . "' 
						,access_emp_seq = '" . $_SESSION['user_seq'] . "' 
						,access_date = dbo.fn_ymdhis()
					WHERE v_user_list_seq = '" . $args['v_user_list_seq'] . "'";

					// echo nl2br($sql);
					// exit;
					
					return $this->query($sql);
				}
				
				/*
				* 사내usb 반납처리 취소 하기
				*/
		function cancelOutGoodsProc($args) {
			$this->args = $args;
					$sql = "UPDATE tb_v_user_list_goods 
					SET out_date = null
						, out_emp_seq = '" . $_SESSION['user_seq'] . "' 
						,access_emp_seq = '" . $_SESSION['user_seq'] . "' 
						,access_date = dbo.fn_ymdhis()
					WHERE v_user_list_seq = '" . $args['v_user_list_seq'] . "'";
					// echo nl2br($sql);
					// exit;
					return $this->query($sql);
		}

		/*
		* 자산 반출처리하기
		*/
		function updateTakeOutProc($args) {
			$this->args = $args;

			
			$ymdhis = date("YmdHis");
			$sql = "UPDATE tb_v_user_list_goods 
				SET out_date = '" . $ymdhis . "'
					, out_emp_seq = '" . $_SESSION['user_seq'] . "' 
					,access_emp_seq = '" . $_SESSION['user_seq'] . "' 
					,access_date = dbo.fn_ymdhis()
				WHERE v_user_list_goods_seq = '" . $args['v_user_list_goods_seq'] . "'";

					return $this->query($sql);
			}

		/*
		* 자산 반출처리 취소 하기
		*/
		function cancelTakeOutProc($args) {
			$this->args = $args;
			
			$sql = "UPDATE tb_v_user_list_goods 
				SET out_date = null
					, out_emp_seq = '".$_SESSION['user_seq']."' 
					,access_emp_seq = '" . $_SESSION['user_seq'] . "' 
					,access_date = dbo.fn_ymdhis()
				WHERE v_user_list_goods_seq = '" . $args['v_user_list_goods_seq'] . "'";
			
			return $this->query($sql);
		}

		
		/*
		*	방문자 출입현황
		*/
		function getUserVisitStatus($args){
			$this->args = $args;

			$sql = "select v1.v_user_name,v1.v_user_name_en,v2.v_user_belong,v2.v_user_list_seq as last_v_user_list_seq
						, v1.v_phone,v1.v_email
						, dbo.fn_DecryptString(v1.v_phone) as v_phone_decript,	dbo.fn_DecryptString(v1.v_email) as v_email_decript
						, first_in_time, last_in_time,in_cnt,file_import_cnt
					from tb_v_user v1
						inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
						inner join (
							select v_user_seq, min(t1.in_time) as first_in_time, max(t1.in_time) as last_in_time,count(t1.v_user_list_seq) as in_cnt
								,count(case when t2.elec_doc_number > '' then 1 end) as file_import_cnt 
							from tb_v_user_list t1
								inner join tb_v_user_list_info t2 on t1.v_user_list_seq = t2.v_user_list_seq
							group by v_user_seq
						) v3 on v1.v_user_seq = v3.v_user_seq
					where v1.v_user_seq='".$args['v_user_seq']."'
						and v2.v_user_list_seq = (
							select top 1 v_user_list_seq 
							from tb_v_user_list
							where v_user_seq = v1.v_user_seq
							order by v_user_list_seq desc)";
		
			return $this->fetchAll($sql);
		}

		/*
		*	방문 담당자 출입현황
		*/
		function getUserVisitStatus_Manager($args){
			$this->args = $args;

				$sql = "select v2.manager_name,v2.manager_name_en,v2.manager_dept,v3.in_cnt,v3.file_import_cnt
						from tb_v_user_list v2
							inner join (
								select t1.manager_name,t1.manager_name_en
									,count(t1.v_user_list_seq) as in_cnt
									,count(case when t2.elec_doc_number > '' then 1 end) as file_import_cnt 
								from tb_v_user_list t1
									inner join tb_v_user_list_info t2 on t1.v_user_list_seq = t2.v_user_list_seq
								group by t1.manager_name,t1.manager_name_en
							) v3 on v2.manager_name = v3.manager_name and v2.manager_name_en = v3.manager_name_en
						where v2.manager_name='".aes_256_enc($args['manager_name'])."'
							and v2.manager_name_en = '".$args['manager_name_en']."'
							and v2.v_user_list_seq = (
								select top 1 v_user_list_seq 
								from tb_v_user_list
								where manager_name = v2.manager_name
									and manager_name_en = v2.manager_name_en
								order by v_user_list_seq desc)";

			return $this->fetchAll($sql);
		}

		
		/*
		*	방문 담당자 방문자별 출입통계 리스트
		*/
		function getUserVisitStatisList($args){
			$this->args = $args;
		
			$sql = " WITH VisitList AS
					(
						select  top ".$args['end']." 
							v1.v_user_seq,v2.v_user_name,v2.v_user_name_en,v2.v_user_belong,v2.v_user_list_seq as last_v_user_list_seq,v2.v_user_type
							, v1.v_phone,v1.v_email
							, dbo.fn_DecryptString(v1.v_phone) as v_phone_decript,	dbo.fn_DecryptString(v1.v_email) as v_email_decript
							, first_in_time, last_in_time,in_cnt,file_import_cnt
							,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
						from tb_v_user v1
							inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
							inner join (
								select v_user_seq, min(t1.in_time) as first_in_time, max(t1.in_time) as last_in_time,count(t1.v_user_list_seq) as in_cnt
									,count(case when t2.elec_doc_number > '' then 1 end) as file_import_cnt 
								from tb_v_user_list t1
									inner join tb_v_user_list_info t2 on t1.v_user_list_seq = t2.v_user_list_seq
								group by v_user_seq
							) v3 on v1.v_user_seq = v3.v_user_seq
						where 1 =  1 ".$args['search_sql']."
							and v2.v_user_list_seq = (
								select top 1 v_user_list_seq 
								from tb_v_user_list
								where v_user_seq = v2.v_user_seq
								order by v_user_list_seq desc) 
					)
					SELECT a.*
					FROM VisitList  a
					WHERE rnum > ".$args['start'];

			if($args['excel_download_flag']=="1"){
				$sql .= " AND rnum <= " . ($args['start'] + RECORD_LIMIT_PER_FILE);	
			}

			return $this->fetchAll($sql);

		}

		/*
		*	방문 담당자 방문자별 출입통계 리스트 카운트
		*/
		function getUserVisitStatisListCount($args){
			$this->args = $args;
			
			$sql = "select count(v1.v_user_seq) as cnt
			from tb_v_user v1
				inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
				inner join (
						select v_user_seq
						from tb_v_user_list t1
							inner join tb_v_user_list_info t2 on t1.v_user_list_seq = t2.v_user_list_seq
						group by v_user_seq
					) v3 on v1.v_user_seq = v3.v_user_seq
			where 1 =  1 ".$args['search_sql']."
				and v2.v_user_list_seq = (
					select top 1 v_user_list_seq 
					from tb_v_user_list
					where v_user_seq = v2.v_user_seq
					order by v_user_list_seq desc) ";

			return $this->fetch($sql);

		}
		
		/*
		* 외부자산반입내역 가져오기
		*/
		function getUserImportGoodsList($args){
			$this->args = $args;

				$sql = " WITH ImportList AS
					(
						select  top ".$args['end']." 
							v2.v_user_list_seq,v2.v_user_name,v2.v_user_name_en,v2.v_user_belong,manager_name,manager_name_en
							,manager_dept,v1.v_phone,v2.v_user_type,g.v_user_list_goods_seq
							,g.goods_name,g.model_name,g.serial_number,g.elec_doc_number,g.item_mgt_number,g.out_schedule_date
							,g.inout_status,v2.in_time,c.scan_center_name as in_center_name,g.memo
							,g.out_date,e.emp_name as out_emp_name,v2.visit_date
							,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
						from  tb_v_user v1
							inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
							inner join tb_v_user_list_goods g on v2.v_user_list_seq = g.v_user_list_seq 
							left join tb_scan_center c on v2.in_center_code = c.scan_center_code
							left join tb_employee e on g.out_emp_seq = e.emp_seq
						where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')
							.$args['search_sql']." 
					) 
					SELECT a.*
					FROM ImportList  a
					WHERE rnum > ".$args['start'];

				if($args['excel_download_flag']=="1"){
					$sql .= " AND rnum <= " . ($args['start'] + RECORD_LIMIT_PER_FILE);	
				}

				$result = $this->fetchAll($sql);
				// echo nl2br($sql);

				return $result;

		}

		/*
		* 외부자산반입내역 count
		*/
		function getUserImportGoodsListCount($args){
			$this->args = $args;

			$sql = " select  count(g.v_user_list_goods_seq) as cnt
					from  tb_v_user v1
							inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
							inner join tb_v_user_list_goods g on v2.v_user_list_seq = g.v_user_list_seq 
							left join tb_scan_center c on v2.in_center_code = c.scan_center_code
							left join tb_employee e on g.out_emp_seq = e.emp_seq
					where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')
						.$args['search_sql'];
			
			$result = $this->fetch($sql);
		
			return $result;
		}
				/*
		* 대여기기정보 가져오기
		*/
		function getRentItemList(){
			$this->args = $args;

			$sql = "select code_name as name, CASE WHEN depth=1 THEN '' ELSE code_name END as value  
					from tb_code
					where code_key='RENT_ITEM'
						and use_yn='Y'
					order by depth,sort ";
			
			return $this->fetchAll($sql);	

		}
		/*
		* 검사장정보(대여장소) 가져오기
		*/
		function getCenerList(){
			$this->args = $args;

			$sql = "select scan_center_code,scan_center_name from tb_scan_center where use_yn='Y' ORDER BY sort,scan_center_name";
			
			return $this->fetchAll($sql);	

		}


		/*
		* 방문자 입퇴실처리
		*/
		function UpdateVisitInoutProc($args){

			$this->args = $args;

			$args[visit_status] = strVal($args[visit_status]);

			$ymdhis = date("YmdHis");
			
			if($args[visit_status]=="1"){	//입실

				if($args[in_time]==""){
					 $in_time = $ymdhis;
				}else $in_time = $args[in_time];

				$sql = "Update tb_v_user_list
				Set in_time = '".$in_time."'
					,out_time=''
					,visit_status = '1'
				Where v_user_list_seq = '".$args[v_user_list_seq]."' ";

			}else if($args[visit_status]=="0"){	//퇴실
				
				if($args[out_time]==""){
					 $out_time = $ymdhis;
				}else $out_time = $args[out_time];

				$sql = "Update tb_v_user_list
				Set out_time='".$out_time."'
					,visit_status = '0'
				Where v_user_list_seq = '".$args[v_user_list_seq]."' ";

			}else {

				$sql = "Update tb_v_user_list
				Set in_time = visit_date+'000000'
					,out_time=''
					,visit_status = '9'
				Where v_user_list_seq = '".$args[v_user_list_seq]."' ";
			}

			$result = $this->query($sql);

			$access_emp_name = $this->_ck_user_name;
			$access_emp_id = $this->_ck_user_id;
			$access_ip_addr = $_SERVER['REMOTE_ADDR'];
			
			//입/퇴실 로그 등록
			if($result){
				$sql = "Insert Into tb_v_user_list_inout_log (
					v_user_list_seq,visit_status,access_date,access_emp_name,access_emp_id,access_ip_addr,memo,create_date	
				)Values (
					'".$args[v_user_list_seq]."','".$args[visit_status]."','{$ymdhis}','{$access_emp_name}','{$access_emp_id}','{$access_ip_addr}','".$args['memo']."','{$ymdhis}'	
				);";

				$result = $this->query($sql);
			}

			return $result;
		
		}

		/*
		* 정보보안서약서 작성정보 가져오기
		*/
		function getUserSecurityAgreeInfo($args){

			$this->args = $args;
			
			$sql = "select v_user_name, v_purpose,security_agree_yn, security_agree_date,v_user_belong
				from tb_v_user_list
				where v_user_list_seq ='".$args['v_user_list_seq']."'";

			return $this->fetchAll($sql);	
			
		}

		/*
		* 방문정보 삭제
		*/
		function deleteUserVisitInfo($args){

			$v_user_list_seq = $args['v_user_list_seq'];

			$sql = "delete from tb_v_wvcs_ldisk	
			where v_wvcs_seq in (select v_wvcs_seq from tb_v_wvcs_info	where v_user_list_seq ='{$v_user_list_seq}' ) ";

			if($this->query($sql)==false) return false;

			$sql = "delete from tb_v_wvcs_macaddr	
			where v_wvcs_seq in (select v_wvcs_seq from tb_v_wvcs_info	where v_user_list_seq ='{$v_user_list_seq}' ) ";

			if($this->query($sql)==false) return false;

			$sql = "delete from tb_v_wvcs_pdisk	
			where v_wvcs_seq in (select v_wvcs_seq from tb_v_wvcs_info	where v_user_list_seq ='{$v_user_list_seq}' )";

			if($this->query($sql)==false) return false;

			$sql = "delete from tb_v_wvcs_programs	
			where v_wvcs_seq in (select v_wvcs_seq from tb_v_wvcs_info	where v_user_list_seq ='{$v_user_list_seq}' )";

			if($this->query($sql)==false) return false;

			$sql = "delete from tb_v_wvcs_vaccine_detail
			where vaccine_seq in (
				select vaccine_seq from tb_v_wvcs_vaccine	
				where v_wvcs_seq in (select v_wvcs_seq from tb_v_wvcs_info	where v_user_list_seq ='{$v_user_list_seq}' )
			);";

			if($this->query($sql)==false) return false;

			$sql = "delete from tb_v_wvcs_vaccine	
			where v_wvcs_seq in (select v_wvcs_seq from tb_v_wvcs_info	where v_user_list_seq ='{$v_user_list_seq}' )";

			if($this->query($sql)==false) return false;

			$sql = "delete from tb_v_wvcs_weakness	
			where v_wvcs_seq in (select v_wvcs_seq from tb_v_wvcs_info	where v_user_list_seq ='{$v_user_list_seq}' )";

			if($this->query($sql)==false) return false;

			$sql = "delete from tb_v_wvcs_scan_log	
			where v_wvcs_seq in (select v_wvcs_seq from tb_v_wvcs_info	where v_user_list_seq ='{$v_user_list_seq}' )";

			if($this->query($sql)==false) return false;

			$sql = "delete from tb_v_wvcs_windowsupdate	
			where v_wvcs_seq in (select v_wvcs_seq from tb_v_wvcs_info	where v_user_list_seq ='{$v_user_list_seq}' )";

			if($this->query($sql)==false) return false;

			$sql = "delete from tb_v_wvcs_info_file
			where v_wvcs_seq in (select v_wvcs_seq from tb_v_wvcs_info	where v_user_list_seq ='{$v_user_list_seq}' );";

			if($this->query($sql)==false) return false;

			$sql = "delete from tb_v_wvcs_info_file_in 
			where v_wvcs_seq in (select v_wvcs_seq from tb_v_wvcs_info	where v_user_list_seq ='{$v_user_list_seq}' );";

			if($this->query($sql)==false) return false;

			$sql = "delete from tb_v_wvcs_info_detail	
			where v_wvcs_seq in (select v_wvcs_seq from tb_v_wvcs_info	where v_user_list_seq ='{$v_user_list_seq}' );";

			if($this->query($sql)==false) return false;

			$sql = "delete from tb_v_wvcs_info_file_in_apply_detail
			where file_in_apply_seq in (
				select file_in_apply_seq 
				from tb_v_wvcs_info_file_in_apply t1
					inner join tb_v_wvcs_info t2 on t1.v_wvcs_seq = t2.v_wvcs_seq
				where t2.v_user_list_seq ='{$v_user_list_seq}' );";

			if($this->query($sql)==false) return false;

			$sql = "delete from tb_v_wvcs_info_file_in_apply
			where v_wvcs_seq in (select v_wvcs_seq from tb_v_wvcs_info where v_user_list_seq ='{$v_user_list_seq}' );";

			if($this->query($sql)==false) return false;

			$sql = "delete from tb_v_wvcs_info	where v_user_list_seq ='{$v_user_list_seq}';";

			if($this->query($sql)==false) return false;

			$sql = "delete from tb_v_user_list_goods
			where v_user_list_seq = '{$v_user_list_seq}';";

			if($this->query($sql)==false) return false;

			$sql = "delete from tb_v_user_list_info
			where v_user_list_seq = '{$v_user_list_seq}';";

			if($this->query($sql)==false) return false;

			$sql = "delete from tb_v_user_list_work
			where v_user_list_seq = '{$v_user_list_seq}';";

			if($this->query($sql)==false) return false;

			$sql = "delete from tb_v_user_list_inout_log
			where v_user_list_seq = '{$v_user_list_seq}';";
			
			if($this->query($sql)==false) return false;

			$sql = "delete from tb_v_user_list where v_user_list_seq = '{$v_user_list_seq}';";

			if($this->query($sql)==false) return false;

			return true;
		}

		/*방문자 보안USB정보가져오기*/
		function getUserUSBInfo($args){

			$sql = "select t2.usb_id,t2.user_id
						from tb_v_user_list_info t1
							inner join tb_usb t2 on t1.label_value = t2.usb_id
						where v_user_list_seq= '".$args['v_user_list_seq']."' ";

			return $this->fetchAll($sql);	
		}
		
		/*
		 * IDC 출입지원 체크리스트 내용
		 */
		function getDefaultIDCSupportChecklistInfo($args){
			$this->args = $args;
			$sql = "
				SELECT form_title
					, form_content
				FROM tb_form 
				WHERE form_div = ?
					AND form_lang = ?
					AND company_code = ?
			";
			$params = array();

			array_push($params, $args['form_div']);
			array_push($params, $args['form_lang']);
			array_push($params, COMPANY_CODE);

			$result = $this->fetchAll($sql, $params);
			$data = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

			return $data;
		}

		/*
		* IDC 문서 SEQ 가져오기
		*/
		function getUserVisitListReportSeq_IDC($args) {
			$this->args = $args;

			$sql = "
				SELECT max(IIF(v3.doc_div = 'VSR_IDC_REPORT', v3.user_doc_seq, null)) as vsr_doc_seq
					 , max(IIF(v3.doc_div = 'MGR_IDC_REPORT', v3.user_doc_seq, null)) as mgr_doc_seq
				FROM tb_v_user v1
					INNER JOIN tb_v_user_list v2 ON v1.v_user_seq = v2.v_user_seq
					LEFT JOIN tb_v_user_doc v3 ON v2.v_user_list_seq = v3.v_user_list_seq
				WHERE v2.v_user_list_seq = ?
			";
			$params = array();

			array_push($params, $args['v_user_list_seq']);

			$result = $this->fetchAll($sql, $params);
			$data = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

			return $data;
		}
		
		/*
		* 문서 내용 저장하기
		*/
		function createUserVisitListReport_IDC($args){
			$this->args = $args;
			$YmdHis = date("YmdHis");

			$sql = "
				INSERT INTO tb_v_user_doc (
					v_user_list_seq, doc_div, doc_title, doc_content, create_emp_seq, create_date	
				) VALUES (
					?, ?, ?, ?, ?, ?
				);
			";
			
			$params = array();

			array_push($params, $args['v_user_list_seq']);
			array_push($params, $args['doc_div']);
			array_push($params, $args['doc_title']);
			array_push($params, $args['doc_content']);
			array_push($params, $args['create_emp_seq']);
			array_push($params, $YmdHis);
			
			$seq = $this->fetchIdentity($sql, $params);
			return $seq;
		}

		/*
		* 문서 내용 수정하기
		*/
		function updateUserVisitListReport_IDC($args){
			$this->args = $args;

			$sql = "
				UPDATE tb_v_user_doc 
				SET doc_title = ?
					, doc_content = ?
				WHERE user_doc_seq = ?
					AND v_user_list_seq = ?
			";
			$params = array();

			array_push($params, $args['doc_title']);
			array_push($params, $args['doc_content']);
			array_push($params, $args['user_doc_seq']);
			array_push($params, $args['v_user_list_seq']);

			return $this->query($sql, $params);
		}

		/*
		* 문서 내용 가져오기
		*/
		function getUserVisitListReport_IDC($args){
			$this->args = $args;

			$sql = "
				SELECT v1.doc_div
					 , v1.doc_title
					 , v1.doc_content
					 , v2.emp_no
					 , v2.emp_name
					 , v1.create_date
				FROM tb_v_user_doc v1
					LEFT JOIN tb_employee v2 ON v1.create_emp_seq = v2.emp_seq
				WHERE v1.user_doc_seq = ?
					AND v1.v_user_list_seq = ?
			";
			$params = array();

			array_push($params, $args['user_doc_seq']);
			array_push($params, $args['v_user_list_seq']);

			$result = $this->fetchAll($sql, $params);
			$data = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

			return $data;
		}
	}



?>


