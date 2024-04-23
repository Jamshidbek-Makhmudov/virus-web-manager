<?php
/*
* 조회/통계 Class
*/
class Model_Stat extends Model
{
	/*
	*	일별 출입 통계 - 출입건수 기준
	*/
	function getVisitStatDaily($args){

			$this->args = $args;

			$args[start_date] = preg_replace("/[^0-9]*/s", "", $args[start_date]);
			$args[end_date] = preg_replace("/[^0-9]*/s", "", $args[end_date]);
		
			$sql = " select str_date as label, isnull(cnt,0) as cnt
					from dbo.fn_getDateInterm('".$args[start_date]."','".$args[end_date]."') dt1
						left join (
							select left(v2.in_time,8) as in_time,count(v2.v_user_list_seq) as cnt
							from tb_v_user v1
								inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
								left join tb_v_user_list_info v3 on v2.v_user_list_seq = v3.v_user_list_seq
								left join tb_scan_center c on v2.in_center_code = c.scan_center_code
							where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')."
								and v2.in_time between '".$args[start_date]."000000' and '".$args[end_date]."235959' 
								".$args['search_sql']."
							group by left(in_time,8) 
						) dt2 on dt1.str_date = dt2.in_time ";

		//echo $sql;

		return $this->fetchAll($sql);
	}

	/*
	* 월별 출입 통계 - 출입건수 기준
	*/
	function getVisitStatMonthly($args){

			$this->args = $args;

			$sql = " select value as label, isnull(cnt,0) as cnt
				from dbo.fn_split('01,02,03,04,05,06,07,08,09,10,11,12',',') dt1
				left join (
					select left(v2.in_time,6) as in_time, count(v2.v_user_list_seq) as cnt 
					from tb_v_user v1
								inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
								left join tb_v_user_list_info v3 on v2.v_user_list_seq = v3.v_user_list_seq
								left join tb_scan_center c on v2.in_center_code = c.scan_center_code
							where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')."
								and v2.in_time like '".$args['year']."%' 
								".$args['search_sql']."
								group by left(in_time,6)
				) dt2 on '".$args['year']."'+dt1.value = dt2.in_time ";


			return $this->fetchAll($sql);
	}

	/*
	*	일별 점검 통계 - 점검건수 기준
	*/
	function getVisitVcsStatDaily($args){
			
			$this->args = $args;

			$args[start_date] = preg_replace("/[^0-9]*/s", "", $args[start_date]);
			$args[end_date] = preg_replace("/[^0-9]*/s", "", $args[end_date]);

			$sql = " select str_date as label, isnull(cnt,0) as cnt
					from dbo.fn_getDateInterm('".$args[start_date]."','".$args[end_date]."') dt1
						left join (
							select left(v2.in_time,8) as in_time,count(v2.v_user_list_seq) as cnt
							from tb_v_user v1
								inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
								inner join tb_v_wvcs_info vcs on v2.v_user_list_seq = vcs.v_user_list_seq
								left join tb_v_user_list_info v3 on v2.v_user_list_seq = v3.v_user_list_seq
								left join tb_scan_center c on v2.in_center_code = c.scan_center_code
							where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')."
								and v2.in_time between '".$args[start_date]."000000' and '".$args[end_date]."235959' 
								".$args['search_sql']."
							group by left(in_time,8) 
						) dt2 on dt1.str_date = dt2.in_time ";

			//echo $sql;

		return $this->fetchAll($sql);
	}

	/*
	* 월별 점검 통계 - 점검건수 기준
	*/
	function getVisitVcsStatMonthly($args){
			$this->args = $args;

		
			$sql = " select value as label, isnull(cnt,0) as cnt
				from dbo.fn_split('01,02,03,04,05,06,07,08,09,10,11,12',',') dt1
				left join (
					select left(v2.in_time,6) as in_time, count(v2.v_user_list_seq) as cnt 
					from tb_v_user v1
								inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
								inner join tb_v_wvcs_info vcs on v2.v_user_list_seq = vcs.v_user_list_seq
								left join tb_v_user_list_info v3 on v2.v_user_list_seq = v3.v_user_list_seq
								left join tb_scan_center c on v2.in_center_code = c.scan_center_code
							where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')."
								and v2.in_time like '".$args['year']."%' 
								".$args['search_sql']."
								group by left(in_time,6)
				) dt2 on '".$args['year']."'+dt1.value = dt2.in_time ";


			return $this->fetchAll($sql);
	}

	/*
	* 기간 바이러스 통계 
	*/
	function getVisitVirusStat($args){

		$this->args = $args;

		$args[start_date] = preg_replace("/[^0-9]*/s", "", $args[start_date]);
		$args[end_date] = preg_replace("/[^0-9]*/s", "", $args[end_date]);
		
		$sql = "select vcc.virus_name,count(*) as cnt
			from  tb_v_user v1
				inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
				inner join tb_v_wvcs_info vcs on v2.v_user_list_seq = vcs.v_user_list_seq
				inner join (
					select vc1.v_wvcs_seq
						, ltrim(min(case when vaccine_name like 'AhnLab V3%' then ' '+vc2.virus_name else vc2.virus_name end)) as virus_name
					from tb_v_wvcs_vaccine vc1
						inner join tb_v_wvcs_vaccine_detail vc2 on vc1.vaccine_seq = vc2.vaccine_seq
					group by vc1.v_wvcs_seq,vc2.virus_path) vcc on vcs.v_wvcs_seq = vcc.v_wvcs_seq
				left join tb_scan_center c on v2.in_center_code = c.scan_center_code
			where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')."
				and v2.in_time between '".$args[start_date]."000000' and '".$args[end_date]."235959' 
				".$args['search_sql']."
			group by vcc.virus_name";

		//echo $sql;

		// echo	nl2br($sql);


		return $this->fetchAll($sql);
	} 

	/*
	* 기간 위변조파일 통계 
	*/
	function getVisitBadExtionStat($args){

		$this->args = $args;

		$args[start_date] = preg_replace("/[^0-9]*/s", "", $args[start_date]);
		$args[end_date] = preg_replace("/[^0-9]*/s", "", $args[end_date]);		

		$sql = "select case when f1.file_signature ='' then f1.file_type else f1.file_signature end file_signature,count(*) as cnt
					from  tb_v_user v1
						inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
						inner join tb_v_wvcs_info vcs on v2.v_user_list_seq = vcs.v_user_list_seq
						inner join tb_v_wvcs_info_detail vcs2 on vcs.v_wvcs_seq = vcs2.v_wvcs_seq
						inner join tb_v_wvcs_info_file f1 on vcs2.v_wvcs_seq = f1.v_wvcs_seq  and vcs2.v_wvcs_detail_seq = f1.v_wvcs_detail_seq
						left join tb_scan_center c on v2.in_center_code = c.scan_center_code
					where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')."
						and v2.in_time between '".$args[start_date]."000000' and '".$args[end_date]."235959' 
						and f1.file_scan_result = 'BAD_EXT' 
						".$args['search_sql']."
					group by case when f1.file_signature ='' then f1.file_type else f1.file_signature end ";

		//echo $sql;

		return $this->fetchAll($sql);
	} 

	/*
	*	일별 물품대여 통계
	*/
	function getRentalStatDaily($args){
			$this->args = $args;

			$start_date = $args['ym']."01";
			$end_date = $args['ym'].DATE('t', strtotime($start_date));
		
			$sql = " select str_date as label, isnull(cnt,0) as cnt
					from dbo.fn_getDateInterm('".$start_date."','".$end_date."') dt1
						left join (
							select  left(rt.rent_date,8) as rent_date,count(rt.rent_list_seq) as cnt
							from tb_rent_list rt
								left join tb_scan_center c on rt.rent_center_code = c.scan_center_code
							where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')."
								and rt.rent_date like  '".$args['ym']."%' 
								".$args['search_sql']."
							group by left(rent_date,8)
						) dt2 on dt1.str_date = dt2.rent_date ";

		//echo ($sql);

		return $this->fetchAll($sql);
	}

	/*
	* 월별 물품대여 통계 
	*/
	function getRentalStatMonthly($args){
			$this->args = $args;

		
			$sql = " select value as label, isnull(cnt,0) as cnt
				from dbo.fn_split('01,02,03,04,05,06,07,08,09,10,11,12',',') dt1
				left join (
					select left(rt.rent_date,6) as rent_date,count(rt.rent_list_seq) as cnt
					from tb_rent_list rt
						left join tb_scan_center c on rt.rent_center_code = c.scan_center_code
					where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')."
						and rt.rent_date like  '".$args['year']."%' 
						".$args['search_sql']."
					group by left(rent_date,6)
				) dt2 on '".$args['year']."'+dt1.value = dt2.rent_date ";


			return $this->fetchAll($sql);
	}

	/*
	* 대여물품 항목 가져오기
	*/
	function getRentalItem($args){
			$this->args = $args;

		$sql = " select distinct item_name
			from tb_rent_list rt
				left join tb_scan_center c on rt.rent_center_code = c.scan_center_code
			where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')."
				".$args['search_sql'];

		//echo $sql;

		return $this->fetchAll($sql);
	}

	/*
	* 물품 대여 변동내역 가져오기
	*/
	function getRentalInfoChangeHistoryDetails($args){
			$this->args = $args;

			$sql = " WITH ChangeHistorylist AS
				(
					select  top ".$args['end']." 
					t1.emp_no,t1.emp_name,r1.rent_list_seq, user_type,user_name,user_name_en,user_company,user_dept,user_phone,
					item_name,item_mgt_number,rent_purpose,rent_center_code,return_schedule_date,rent_date,return_date,
					return_emp_seq,create_date,memo,user_agree_yn,user_belong,access_emp_seq,access_date,history_date,access_ip_addr,action
						,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
					from tb_rent_list_history r1
						left join tb_employee t1 on t1.emp_seq = r1.return_emp_seq
						left join tb_scan_center c on r1.rent_center_code = c.scan_center_code
					WHERE ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code').$args['search_sql']." 

				) 
				SELECT a.*
				FROM ChangeHistorylist  a
				WHERE rnum > ".$args['start'];

			if($args['excel_download_flag']=="1"){
				$sql .= " AND rnum <= " . ($args['start'] + RECORD_LIMIT_PER_FILE);	
			}
			$result = $this->fetchAll($sql);

			// echo nl2br($sql);
		

			
			return $result;

	}
	/*
	* 물품 대여변동내역 count
	*/
	function getRentalInfoChangeHistoryDetailsCount($args){
		$this->args = $args;

		$sql = "select count (h_seq) as cnt 
			from tb_rent_list_history r1
				left join tb_employee t1 on t1.emp_seq = r1.return_emp_seq
				left join tb_scan_center c on r1.rent_center_code = c.scan_center_code
			WHERE ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code').$args['search_sql'];		
		$result = $this->fetch($sql);
		
		return $result;
	}


	/*
	* 관리자 작업내역 가져오기
	*/
	function getAdminActLogList($args){
			
			$this->args = $args;

			$sql = " WITH AdminActlist AS
				(
					select  top ".$args['end']." 
						 a1.act_log_seq,a1.emp_name,a1.emp_no,a1.log_title,a1.ip_addr,a1.log_date,a1.act_type,a1.referer
						,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
					from tb_admin_act_log  a1
					WHERE " .$args['search_sql']." 

				) 
				SELECT a.*
				FROM AdminActlist  a
				WHERE rnum > ".$args['start'];

			if($args['excel_download_flag']=="1"){
				$sql .= " AND rnum <= " . ($args['start'] + RECORD_LIMIT_PER_FILE);	
			}
			$result = $this->fetchAll($sql);

			// echo nl2br($sql);
		
			return $result;

	}

	/*
	* 관리자 작업내역 count
	*/
	function getAdminActLogListCount($args){
		
		$this->args = $args;

		$sql = "select count (a1.act_log_seq) as cnt 
				from tb_admin_act_log a1
				WHERE " .$args['search_sql'];				
		$result = $this->fetch($sql);
		
		return $result;
	}

	/*
	* 관리자 작업내역 상세정보 가져오기
	*/
	function getAdminActLogDetails($args){

		$this->args = $args;

		$sql = "select  a1.act_log_seq,a1.emp_name,a1.emp_no,a1.log_title,a1.ip_addr,a1.log_date,a1.act_type,a1.referer
					,dbo.fn_dll_debase64(recv_data) as recv_data
				from tb_admin_act_log a1
				WHERE a1.act_log_seq = '".$args[act_log_seq]."' ";
			
		$result = $this->fetchAll($sql);
		
		return $result;
		
	}

	/*
	* 백신업데이트 내역 가져오기
	*/
	function getAppUpdateLogList($args){
			
			$this->args = $args;

			$sql = " WITH AppUpdateList AS
				(
					select  top ".$args['end']." 
						 a1.app_update_log_seq,k1.kiosk_name,c1.scan_center_name,a1.app_name,a1.ver,a1.update_time,a1.end_time,a1.result,a1.result_msg
						 ,a2.app_seq,a2.file_size,convert(varchar,a2.patch_dt,120) as patch_dt, convert(varchar,a2.create_dt,120) as create_dt
						,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
					from tb_app_update_log a1
						inner join tb_app_update a2 on a1.app_seq = a2.app_seq
						inner join tb_scan_center_kiosk k1 on a1.kiosk_id = k1.kiosk_id
						inner join tb_scan_center c1 on k1.scan_center_code = c1.scan_center_code
					WHERE  1 = 1 " .$args['search_sql']." 

				) 
				SELECT a.*
				FROM AppUpdateList  a
				WHERE rnum > ".$args['start'];

			if($args['excel_download_flag']=="1"){
				$sql .= " AND rnum <= " . ($args['start'] + RECORD_LIMIT_PER_FILE);	
			}
			$result = $this->fetchAll($sql);

			// echo nl2br($sql);
		
			return $result;

	}

	/*
	* 백신업데이트 내역 count
	*/
	function getAppUpdateLogListCount($args){
		
		$this->args = $args;

		$sql = "select count (a1.app_update_log_seq) as cnt 
				from tb_app_update_log a1
					inner join tb_app_update a2 on a1.app_seq = a2.app_seq
					inner join tb_scan_center_kiosk k1 on a1.kiosk_id = k1.kiosk_id
					inner join tb_scan_center c1 on k1.scan_center_code = c1.scan_center_code
				WHERE 1 = 1 " .$args['search_sql'];				
		$result = $this->fetch($sql);
		
		return $result;
	}

	/*
	* 작업 로그 가져오기
	*/
	function getSystemLogList($args){
			
			$this->args = $args;

			$sql = " WITH SysLogList AS
				(
					select  top ".$args['end']." 
						 s.system_log_seq,s.log_div,s.result,dbo.fn_dll_debase64(s.content) as content,s.create_date
						,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
					from tb_system_log s
					WHERE  1 = 1 " .$args['search_sql']." 

				) 
				SELECT a.*
				FROM SysLogList  a
				WHERE rnum > ".$args['start'];

			if($args['excel_download_flag']=="1"){
				$sql .= " AND rnum <= " . ($args['start'] + RECORD_LIMIT_PER_FILE);	
			}
			$result = $this->fetchAll($sql);

			 //echo nl2br($sql);
		
			return $result;

	}

	/*
	* 작업 로그 count
	*/
	function getSystemLogListCount($args){
		
		$this->args = $args;

		$sql = "select count (s.system_log_seq) as cnt 
				from tb_system_log s
				WHERE 1 = 1 " .$args['search_sql'];				
		$result = $this->fetch($sql);
		
		return $result;
	}


	/*
	*	동의 및 서약서 내역 조회
	*/
	function getUserAgreeList($args){
	
		$this->args = $args;

			$sql = " WITH AgreeList AS
				(
					select  top ".$args['end']." 
						v2.v_user_list_seq,v2.v_user_name,v2.v_user_name_en, v1.v_phone,v2.v_user_belong
						,v2.visit_date,v2.security_agree_yn, v2.security_agree_date,v2.v_agree_date, v2.v_agree_yn, c.scan_center_name as in_center_name
						,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
					from tb_v_user v1
						inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
						left join tb_scan_center c on v2.in_center_code = c.scan_center_code
					where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')."
						and v2.v_user_type='OUT' and v2.v_type like 'VISIT%' "
						.$args['search_sql']." 

				) 
				SELECT a.*
				FROM AgreeList  a
				WHERE rnum > ".$args['start'];

			if($args['excel_download_flag']=="1"){
				$sql .= " AND rnum <= " . ($args['start'] + RECORD_LIMIT_PER_FILE);	
			}
			$result = $this->fetchAll($sql);

			// echo nl2br($sql);
		
			return $result;
			
	}

	/*
	*	동의 및 서약서 내역 조회 카운트
	*/
	function getUserAgreeListCount($args){

		$this->args = $args;

		$sql = "select  count(v2.v_user_list_seq) as cnt
				from tb_v_user v1
					inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
					left join tb_scan_center c on v2.in_center_code = c.scan_center_code
				where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')."
					and v2.v_user_type='OUT' and v2.v_type like 'VISIT%'  "
					.$args['search_sql'];
			
		$result = $this->fetch($sql);
		
		return $result;

	}

	/*
	* IDC 입퇴실내역 가져오기
	*/
	function getUserVistInoutList_IDC($args){

			$this->args = $args;

			$sql = " WITH VisitList AS
				(
					select  top ".$args['end']." 
						v2.v_user_list_seq,v2.v_user_name,v2.v_user_name_en,v2.in_center_code,v2.v_user_belong,v2.v_user_type,v2.v_type	
						,v1.v_phone,v1.v_email,v2.v_purpose,v2.visit_date,v2.security_agree_yn, v3.visit_center_desc
						,v3.elec_doc_number,v3.work_number
						,vi.visit_status,vi.access_date, vi.access_emp_id, vi.access_emp_name
						,c.scan_center_name as in_center_name
						,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
					from tb_v_user v1
						inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
						inner join tb_v_user_list_info v3 on v2.v_user_list_seq = v3.v_user_list_seq
						inner join tb_v_user_list_inout_log vi on v2.v_user_list_seq = vi.v_user_list_seq
						left join tb_scan_center c on v2.in_center_code = c.scan_center_code
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
			//echo nl2br($sql);

	
			return $result;

	}

	/*
	* IDC 입퇴실내역 count
	*/
	function getUserVistInoutListCount_IDC($args){

		$this->args = $args;

		$sql = " select  count(v2.v_user_list_seq) as cnt
				from tb_v_user v1
					inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
					inner join tb_v_user_list_info v3 on v2.v_user_list_seq = v3.v_user_list_seq
					inner join tb_v_user_list_inout_log vi on v2.v_user_list_seq = vi.v_user_list_seq
					left join tb_scan_center c on v2.in_center_code = c.scan_center_code
				where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')
					.$args['search_sql'];
		
		$result = $this->fetch($sql);
	//  echo nl2br($sql);
		 
		
		return $result;
	}
	
}