<?php
	/*
	* 대시보드 Class
	*/
	Class Model_Dashboard extends Model {

		/*
		* 기간 주요통계 Count
		*/
		function getVisitPeriodSummary($args){
			$this->args = $args;

			$str_sdate = preg_replace("/[^0-9]*/s", "", $args['start_date'])."000000";
			$str_edate = preg_replace("/[^0-9]*/s", "", $args['end_date'])."235959";
			
			$sql = " select count(distinct case when v2.v_user_type='OUT' and v2.v_type like 'VISIT%'  then v2.v_user_list_seq end) as visit_count
							,count(distinct case when  v3.label_value > '' then v2.v_user_list_seq end) as visit_file_count
							,count(distinct case when  v3.pass_card_no > '' then v2.v_user_list_seq end) as visit_pass_count
							,count(distinct case when  v4.v_user_list_goods_seq > 0 then v4.v_user_list_goods_seq end) as visit_goods_count
						from tb_v_user v1
							inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
							left join tb_v_user_list_info v3 on v2.v_user_list_seq = v3.v_user_list_seq
							left join tb_v_user_list_goods v4 on v2.v_user_list_seq = v4.v_user_list_seq
							left join tb_scan_center c on v2.in_center_code = c.scan_center_code
						where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')."
								and v2.in_time between '".$str_sdate."' and '".$str_edate."' 
								".$args['search_sql'];

			return $this->fetchAll($sql);
		}

		/*
		* 기간 출입현황 - 방문자출입
		*/
		function getVisitPeriodStat($args){
			$this->args = $args;

			$str_sdate = preg_replace("/[^0-9]*/s", "", $args['start_date'])."000000";
			$str_edate = preg_replace("/[^0-9]*/s", "", $args['end_date'])."235959";
		
			$sql = " select str_date as label, isnull(cnt,0) as cnt
					from dbo.fn_getDateInterm('".$args['start_date']."','".$args['end_date']."') dt1
						left join (
							select left(v2.in_time,8) as in_time,count(v2.v_user_list_seq) as cnt
							from tb_v_user v1
								inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
								left join tb_scan_center c on v2.in_center_code = c.scan_center_code
							where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')."
								and v2.in_time between '".$str_sdate."' and '".$str_edate."' 
								and v2.v_user_type='OUT' and v2.v_type like 'VISIT%'
								".$args['search_sql']."
							group by left(in_time,8) 
						) dt2 on dt1.str_date = dt2.in_time ";
		

			return $this->fetchAll($sql);
		}

		/*
		* 기간출입현황 - 파일반입
		*/
		function getVisitPeriodStat_File($args){
			$this->args = $args;

			$str_sdate = preg_replace("/[^0-9]*/s", "", $args['start_date'])."000000";
			$str_edate = preg_replace("/[^0-9]*/s", "", $args['end_date'])."235959";
		
			$sql = " select str_date as label, isnull(cnt,0) as cnt
					from dbo.fn_getDateInterm('".$args['start_date']."','".$args['end_date']."') dt1
						left join (
							select left(v2.in_time,8) as in_time,count(v2.v_user_list_seq) as cnt
							from tb_v_user v1
								inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
								inner join tb_v_user_list_info v3 on v2.v_user_list_seq = v3.v_user_list_seq
								left join tb_scan_center c on v2.in_center_code = c.scan_center_code
							where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')."
								and v2.in_time between '".$str_sdate."' and '".$str_edate."' 
								and v3.label_value > '' 
								".$args['search_sql']."
							group by left(in_time,8) 
						) dt2 on dt1.str_date = dt2.in_time ";

			return $this->fetchAll($sql);
		}

		/*
		* 출입 - 임시출입증/USB 미반납, 외부자산 미반출 현황   
		*/
		function getNotReturnStat($args){
				
			$this->args = $args;

			$sql = " select count(distinct case when v3.pass_card_no > '' and isnull(v3.pass_card_return_date,'')='' then 
								v2.v_user_list_seq end) as not_return_pass_count
							,count(distinct case when v3.label_value > '' and isnull(v3.usb_return_date,'')='' then 
								v2.v_user_list_seq end) as not_return_usb_count
							,count(distinct case when v4.v_user_list_goods_seq > 0 and isnull(v4.out_date,'')='' then 
								v4.v_user_list_goods_seq end) as not_export_goods_count
						from tb_v_user v1
							inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
							left join tb_v_user_list_info v3 on v2.v_user_list_seq = v3.v_user_list_seq
							left join tb_v_user_list_goods v4 on v2.v_user_list_seq = v4.v_user_list_seq
							left join tb_scan_center c on v2.in_center_code = c.scan_center_code
						where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')."
							and ( (v3.pass_card_no > '' and isnull(v3.pass_card_return_date,'') ='' )
							or (v3.label_value > '' and isnull(v3.usb_return_date,'') ='')
							or (v4.v_user_list_goods_seq > 0 and isnull(v4.out_date,'') = '' ) ) 
							".$args['search_sql'];
			
			//echo nl2br($sql);
			return $this->fetchAll($sql);
		}

		/*
		* 대여물품 - 미반납 현황   
		*/
		function getNotReturnStats($args){

			$this->args = $args;

			$sql = " select count(rt.rent_list_seq) as not_return_rent_cnt
					from tb_rent_list rt
						left join tb_scan_center c on rt.rent_center_code = c.scan_center_code
					where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')
							.$args['search_sql']."
						and isnull(rt.return_date,'')='' ";

			return $this->fetchAll($sql);
		}

		/*
		* 기간 점검결과 - 바이러스 통계 
		*/
		function getVisitPeriodVirusStat($args){

			$this->args = $args;

			$str_sdate = preg_replace("/[^0-9]*/s", "", $args['start_date'])."000000";
			$str_edate = preg_replace("/[^0-9]*/s", "", $args['end_date'])."235959";
			
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
					and v2.in_time between '".$str_sdate."' and '".$str_edate."' 
					".$args['search_sql']."
				group by vcc.virus_name";

			return $this->fetchAll($sql);
		} 

		/*
		* 기간 점검결과 - 파일반입 통계 
		*/
		function getVcsFilePeriodStat($args){

			$this->args = $args;

			$str_sdate = preg_replace("/[^0-9]*/s", "", $args['start_date'])."000000";
			$str_edate = preg_replace("/[^0-9]*/s", "", $args['end_date'])."235959";
			
			$sql = "select count(f1.v_wvcs_file_seq) as file_count
							,count(f2.v_wvcs_file_in_seq) as file_in_count
							,count(case when CHARINDEX(f1.file_scan_result,'VIRUS') > 0 then 1 end) as virus_count
							,count(case when CHARINDEX(f1.file_scan_result,'BAD_EXT') > 0 then 1 end) as bad_ext_count
							,count(case when f2.v_wvcs_file_in_seq > 0 and CHARINDEX(f1.file_scan_result,'VIRUS')+CHARINDEX(f1.file_scan_result,'BAD_EXT') > 0 then 1 end) as exp_file_in_count
						from  tb_v_user v1
							inner join tb_v_user_list v2 on v1.v_user_seq = v2.v_user_seq
							inner join tb_v_wvcs_info vcs on  v2.v_user_seq = vcs.v_user_seq and v2.v_user_list_seq = vcs.v_user_list_seq
							inner join tb_v_wvcs_info_file f1 on vcs.v_wvcs_seq = f1.v_wvcs_seq 
							left join tb_v_wvcs_info_file_in f2 on f1.v_wvcs_file_seq = f2.v_wvcs_file_seq
							left join tb_scan_center c on v2.in_center_code = c.scan_center_code
				where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')."
					and v2.in_time between '".$str_sdate."' and '".$str_edate."' 
					".$args['search_sql'];

			return $this->fetchAll($sql);
		} 

		/*
		* 백신 업데이트 현황
		*/
		function getVaccineUpdateStat($args){

			$this->args = $args;

			$sql = "select c.scan_center_code,ap.kiosk_id, ap.app_name, ap.ver, ap.update_time
					from tb_scan_center c
						inner join tb_scan_center_kiosk ck on c.scan_center_code = ck.scan_center_code
						inner join tb_app_update_log ap on ap.kiosk_id = ck.kiosk_id
							and app_update_log_seq = (
								select top 1 app_update_log_seq
								from tb_app_update_log
								where kiosk_id = ap.kiosk_id
									and app_name = ap.app_name
									and result ='success'
									order by update_time desc )
					where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')."
						".$args['search_sql']."
					order by scan_center_name,kiosk_name";

			//echo nl2br($sql);
			return $this->fetchAll($sql);
			
		}

		/*
		* 키오스크 정보
		*/
		function getScanCenterKioskList($args){

			$this->args = $args;
			
			
			$sql = "select c.scan_center_code, c.scan_center_name, ck.kiosk_id, ck.kiosk_name
					from tb_scan_center c
						left join tb_scan_center_kiosk ck on c.scan_center_code = ck.scan_center_code
					where ".getCheckScanCenterAuthQuery('c.org_id','c.scan_center_code')."
						and ck.kiosk_menu like '%VCS%'
						".$args['search_sql']."
					order by c.sort, c.scan_center_name,ck.kiosk_name";

			//echo nl2br($sql);
			return $this->fetchAll($sql);
		}

		/*
		* 서버현황
		*/
		function getServerStatus(){

			global $_driver_path;
			
			exec("tasklist 2>NUL", $task_list);
		
			$services = array(
				 "WEB"=> "httpd.exe"
				,"DB" => "sqlservr.exe"                  
			);

			$WEB_isRunning =  sizeof(preg_grep('/'.$services["WEB"].'\s.*/', $task_list));	
			$DB_isRunning =  sizeof(preg_grep('/'.$services["DB"].'\s.*/', $task_list));

			$WEB = $WEB_isRunning > 0 ? "HEALTHY" : "CRITICAL";
			$DB = $DB_isRunning > 0 ? "HEALTHY" : "CRITICAL";


			$DISK_TOTAL = disk_total_space("/");
			$DISK_FREE = disk_free_space("/");
			$Disk_UsageRate = number_format($DISK_FREE / $DISK_TOTAL * 100,0);

			$HOST_NAME = php_uname('n');
			$HOST_SERVER= gethostbyname($HOST_NAME);

			$data['server_name'] = "Main ".$_driver_path;
			$data['server_ip'] = $HOST_SERVER;
			$data['disk_total'] = formatBytes($DISK_TOTAL);
			$data['disk_free'] = formatBytes($DISK_FREE);
			$data['disk_usage_rate'] = $Disk_UsageRate;
			$data['web_connection'] = $WEB;
			$data['db_connection'] = $DB;
			//
			$data['disk_total_chart'] = $DISK_TOTAL;
			$data['disk_free_chart'] = $DISK_FREE;

			//  print_r($data);
			

			return $data;


			
		}

		/*
		* 시스템 작업 로그
		*/
		function getSystemLog(){
			
			$sql = "select top 4 log_div,result,create_date,dbo.fn_dll_debase64(content) as content
					from tb_system_log
					order by system_log_seq desc";

			return $this->fetchAll($sql);
		}

    }
?>