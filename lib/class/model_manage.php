<?php
	/*
	* 운영/관리 Class
	*/
	Class Model_manage extends Model {
		
		/*
		* 검사장 정보 가져오기
		*/
		function getScanCenterList($args){
		
			$this->args = $args;
			
			$sql = "select org.org_id, org.org_name,cn.scan_center_code,cn.scan_center_name 
					from tb_organ org
						inner join tb_scan_center cn on org.org_id = cn.org_id
					where ".getCheckScanCenterAuthQuery('cn.org_id','cn.scan_center_code')."
						and cn.use_yn = 'Y'
						".$args['search_sql'];

			return $this->fetchAll($sql);
		
		}
        
		/*
		* 검사장 키오스크 정보 가져오기
		*/
		function getScanCenterKiosk($args){
			$this->args = $args;
			
			$sql = "Select k.kiosk_seq, k.kiosk_id,k.scan_center_code, k.kiosk_name, k.kiosk_ip_addr, k.kiosk_menu,memo
				From tb_scan_center_kiosk k
				Where 1 =1  ".$args['search_sql'];
		
			return $this->fetchAll($sql);
		}

		/*
		* 검사장 키오스크 링크 정보 가져오기 1
		*/
		function getScanCenterKioskLink($args){
			$this->args = $args;
			
			$sql = "Select lk.kiosk_link_seq, lk.kiosk_seq,lk.link_name,lk.link_url
				From tb_scan_center_kiosk_link lk
					inner join tb_scan_center_kiosk k on lk.kiosk_seq = k.kiosk_seq
				Where 1 =1  ".$args['search_sql'];
		
			return $this->fetchAll($sql);
		}

		/*
		* 검사장 키오스크 링크 정보 가져오기 2
		*/
		function getKioskLink($args){
			$this->args = $args;
			
			$sql = "Select lk.kiosk_link_seq, lk.link_name,lk.link_url
				From tb_scan_center_kiosk_link lk
				Where 1 =1  ".$args['search_sql'];
		
			return $this->fetchAll($sql);
		}

		/*
		* 검사장 키오스크 링크 정보 가져오기 3
		*/
		function getDistinctKioskLink($args){
			$this->args = $args;
			
			$sql = "Select distinct lk.link_name,lk.link_url
				From tb_scan_center_kiosk_link lk
				Where 1 =1  ".$args['search_sql'];
		
			return $this->fetchAll_Count($sql);
		}

        
		/*
		* 검사장 키오스크 정보 저장
		*/
		function saveScanCenterKiosk($args){
			$this->args = $args;

				$sql = "
				Insert into tb_scan_center_kiosk(
					scan_center_code,kiosk_id,kiosk_name,kiosk_ip_addr,kiosk_menu,create_date,memo)
				Values (
					'".$args['scan_center_code']."','".$args['kiosk_id']."', N'".$args['kiosk_name']."','".$args['kiosk_ip_addr']."','".$args['kiosk_menu']."','".date('YmdHis')."','".$args['memo']."') ";
		
				$seq = $this->fetchIdentity($sql);
				return $seq;

		}

		/*
		* 검사장 키오스크 링크정보 저장
		*/
		function saveScanCenterKioskLink($args){
			$this->args = $args;
			
			$sql = " Insert into tb_scan_center_kiosk_link(
						kiosk_seq,link_name,link_url,create_date)
					Values (
						'".$args['kiosk_seq']."','".$args['link_name']."','".$args['link_url']."','".date('YmdHis')."') ";
			
			//echo $sql;
			return $this->query($sql);
				
		}

		/*
		* 검사장 키오스크정보 삭제1
		*/
		function deleteScanCenterKiosk($args){
			$this->args = $args;

			$sql = "Delete 
				From tb_scan_center_kiosk_link 
				Where kiosk_seq in (
						Select kiosk_seq from tb_scan_center_kiosk Where scan_center_code = '".$args['scan_center_code']."' ) ";

			$sql .= "Delete 
				From tb_scan_center_kiosk 
				Where scan_center_code = '".$args['scan_center_code']."' ";

		
			return $this->query($sql);
				
		}

		/*
		* 검사장 키오스크정보 삭제2
		*/
		function deleteKiosk($args){
			$this->args = $args;

			$sql = "Delete 
				From tb_scan_center_kiosk_link 
				Where kiosk_seq in ( ".$args['kiosk_seq']." ) ";

			$sql .= "Delete 
				From tb_scan_center_kiosk 
				Where kiosk_seq in ( ".$args['kiosk_seq']." ) ";

		
			return $this->query($sql);
				
		}

		/*
		* 검사장 키오스크정보 삭제3
		*/
		function deleteScanCenterKioskBySeq($args){
			
			$this->args = $args;

			$sql = "Delete 
				From tb_scan_center_kiosk_link 
				Where kiosk_seq in (
						Select kiosk_seq 
						from tb_scan_center_kiosk t1
							inner join tb_scan_center t2 on t1.scan_center_code = t2.scan_center_code
						where t2.scan_center_seq = '".$args['scan_center_seq']."'  ) ";

			$sql .= "Delete 
				From tb_scan_center_kiosk 
				Where scan_center_code = (select scan_center_code from tb_scan_center where scan_center_seq='".$args['scan_center_seq']."') ";

		
			return $this->query($sql);
				
		}

		/*
		*	키오스크 ID 중복체크
		*/
		function checkExistsKioskID($args){

			$sql = "Select kiosk_id  From tb_scan_center_kiosk where scan_center_code <> '".$args[scan_center_code]."' and kiosk_id in (".$args[kiosk_id].") ";		
			return $this->fetchAll($sql);
		}

		/*
		* 검사장 키오스크링크정보 삭제
		*/
		function deleteKioskLink($args){
			$this->args = $args;

			if($args['kiosk_seq'] != ""){
				$search_sql .= " and kiosk_seq in ( ".$args['kiosk_seq']." ) ";
			}

			if($args['kiosk_link_seq'] != ""){
				$search_sql .= " and kiosk_link_seq in ( ".$args['kiosk_link_seq']." ) ";
			}

			if($args['link_name'] != ""){
				$search_sql .= " and link_name = '".$args['link_name']."' ";
			}

			if($args['link_url'] != ""){
				$search_sql .= " and link_url  = '".$args['link_url']."' ";
			}

			$sql = "Delete 
				From tb_scan_center_kiosk_link 
				Where 1= 1 ".$search_sql;

			return $this->query($sql);
				
		}
		
		/*
		* 예외 파일 반입 정책 등록
		*/
		function SaveFileInPolicy($args){
			$this->args = $args;
			
			$ymdhis = date("YmdHis");

			$target_name = $args['target_name'];
			$target_value = $args['target_value'];
			$file_send_status = $args['file_send_status'];
			$refer ='WEB';
			$ip_addr = $_SERVER['REMOTE_ADDR'];

			if($args['target']=="ALL"){
				$target_value = "ALL";
				$target_name = aes_256_enc(trsLang('전체','alltext'));
			}

			$_ck_user_seq = $this->_ck_user_seq;

			$sql = "Insert Into tb_policy_file_in (
					policy_name,start_date,end_date,target,target_value,target_name,file_div,create_emp_seq,create_date,refer,file_send_status,ip_addr
				)Values (
					N'".$args['policy_name']."','".$args[start_date]."','".$args[end_date]."','".$args['target']."','".$target_value."'
					,N'".$target_name."','".$args['file_div']."','".$_ck_user_seq."','{$ymdhis}','{$refer}','".$args['file_send_status']."','{$ip_addr}');";
	
			//echo $sql;			

			return $this->fetchIdentity($sql);
		
		}

		/*
		* 예외 파일 반입 정책 파일 해시값 등록
		*/
		function SaveFileInPolicyFileList($args){
			$this->args = $args;
			
			$ymdhis = date("YmdHis");

			$sql = "Insert Into tb_policy_file_in_list (
						policy_file_in_seq,file_hash,file_comment,create_date,file_name	
					)Values (
						'".$args['policy_file_in_seq']."','".$args['file_hash']."','".$args['file_comment']."','{$ymdhis}',N'".$args['file_name']."'	 );";

			$result =$this->query($sql);
		
			return $result;
		}

		/*
		* 예외 파일 반입 정책 수정
		*/
		function UpdateFileInPolicy($args){
			$this->args = $args;

			$ymdhis = date("YmdHis");

			$target_name = $args['target_name'];
			$target_value = $args['target_value'];
			$file_send_status = $args['file_send_status'];
			$refer ='WEB';

			if($args['target']=="ALL"){
				$target_value = "ALL";
				$target_name = trsLang('전체','alltext');
			}

			$_ck_user_seq = $this->_ck_user_seq;

			$sql = "Update tb_policy_file_in 
						Set policy_name= N'".$args['policy_name']."'
						 ,start_date='".$args[start_date]."'
						 ,end_date='".$args[end_date]."'
						 ,target='".$args[target]."'
						 ,target_value='{$target_value}'
						 ,target_name='{$target_name}'
						 ,file_div='".$args[file_div]."'
						 ,create_emp_seq='{$_ck_user_seq}'
						 ,create_date='{$ymdhis}'
						 ,refer='{$refer}'
						 ,file_send_status='{$file_send_status}'
					Where policy_file_in_seq = '".$args[policy_file_in_seq]."'";

			//echo $sql;

			return $this->query($sql);
		}

		/*
		* 예외 파일 반입 정책 적용기간 종료 처리
		*/
		function UpdateFileInPolicyEndDate($args){
			$this->args = $args;

			$ymdhis = date("YmdHis");

			$_ck_user_seq = $this->_ck_user_seq;

			$sql = "Update tb_policy_file_in 
						Set end_date='".$args[end_date]."'
						 ,create_emp_seq='{$_ck_user_seq}'
						 ,create_date='{$ymdhis}'
					Where policy_file_in_seq = '".$args[policy_file_in_seq]."'";

			//echo $sql;

			return $this->query($sql);
		}

		/*
		* 예외파일 반입 정책 삭제
		*/
		function DeleteFileInPolicy($args){
			$this->args = $args;
			
			$sql = "";
			$sql .= "Delete from tb_policy_file_in_list Where policy_file_in_seq ='".$args['policy_file_in_seq']."';";
			$sql .= "Delete from tb_policy_file_in Where policy_file_in_seq ='".$args['policy_file_in_seq']."'";
			$result =$this->query($sql);

			return $result;
		
		}
		
		/*
		* 예외파일 반입 정책 지정파일 정보 삭제
		*/
		function DeleteFileInPolicyFileList($args){
			$this->args = $args;
			
			$sql = "Delete from tb_policy_file_in_list Where policy_file_in_seq ='".$args['policy_file_in_seq']."'";

			$result =$this->query($sql);		
			
			return $result;
		
		}

		/*
		* 예외파일 반입 정책 정보 가져오기
		*/
		function GetFileInPolicyInfo($args){
			$this->args = $args;
			
			$sql = "Select t1.policy_file_in_seq,t1.policy_name, t1.start_date, t1.end_date, t1.target, t1.target_value, t1.target_name, t1.file_div
					, t1.create_emp_seq, t1.create_date, t1.refer,t2.emp_name,t1.file_send_status,t1.file_in_apply_seq
					From tb_policy_file_in t1
						left join tb_employee t2 on t1.create_emp_seq = t2.emp_seq
					Where policy_file_in_seq = '".$args['policy_file_in_seq']."' ";

			$result =$this->fetchAll($sql);		
			
			return $result;
		
		}

		/*
		* 예외파일 반입 정책 지정파일 정보 가져오기
		*/
		function GetFileInPolicyFileListInfo($args){
			$this->args = $args;
			
			$sql = "Select file_hash, file_comment,file_name
					From tb_policy_file_in_list 
					Where policy_file_in_seq = '".$args['policy_file_in_seq']."' ";

			$result =$this->fetchAll($sql);		
			
			return $result;
		
		}

		/*
		* 파일예외정책 리스트 가져오기
		*/
		function getFileInPolicyList($args){
			$this->args = $args;

				if($args['excel_download_flag']=="1"){

					$file_query = " STUFF((SELECT ',' + file_hash
													FROM tb_policy_file_in_list
												   WHERE policy_file_in_seq = t1.policy_file_in_seq
													 FOR XML PATH('')
										   ), 1, 1, '') AS file_hash ";
				}else{
					$file_query = "(select count(*) from tb_policy_file_in_list where policy_file_in_seq = t1.policy_file_in_seq) as file_cnt";
				}

				$sql = " WITH PolicyList AS (
						select  top ".$args['end']." 
							t1.policy_file_in_seq,t1.policy_name, t1.start_date, t1.end_date, t1.target, t1.target_value, t1.target_name, t1.file_div,t1.file_send_status
							, t1.create_emp_seq, t1.create_date, t1.refer,t1.file_in_apply_seq
							,t2.emp_name,t3.refer_apply_seq
							, {$file_query}
							,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
						From tb_policy_file_in t1
							left join tb_employee t2 on t1.create_emp_seq = t2.emp_seq
							left join tb_v_wvcs_info_file_in_apply t3 on t1.file_in_apply_seq = t3.file_in_apply_seq
						where  1=1 "
							.$args['search_sql']." 
					) 
					SELECT a.*
					FROM PolicyList  a
					WHERE rnum > ".$args['start'];

				if($args['excel_download_flag']=="1"){
					$sql .= " AND rnum <= " . ($args['start'] + RECORD_LIMIT_PER_FILE);	
				}

				//echo $sql;

				$result = $this->fetchAll($sql);
				
				return $result;

		}

		/*
		* 파일예외정책 count 가져오기
		*/
		function getFileInPolicyListCount($args){
			$this->args = $args;

			$sql = " select  count(t1.policy_file_in_seq) as cnt
					From tb_policy_file_in t1
							left join tb_employee t2 on t1.create_emp_seq = t2.emp_seq
							left join tb_v_wvcs_info_file_in_apply t3 on t1.file_in_apply_seq = t3.file_in_apply_seq
					where  1= 1"
						.$args['search_sql'];
			
			$result = $this->fetch($sql);
			
			return $result;
		}


		/*
		* 파일예외정책 파일 리스트 가져오기
		*/
		function getFileInPolicyFileList($args){
			$this->args = $args;

				$sql = " WITH PolicyList AS (
						select  top ".$args['end']." 
							t1.policy_file_in_seq,t1.policy_name, t1.start_date, t1.end_date, t1.target, t1.target_value, t1.target_name, t1.file_div,t1.file_send_status
							, t1.create_emp_seq, t1.create_date, t1.refer,t2.file_name,t2.file_hash,t2.file_comment
							,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
						From tb_policy_file_in t1
							inner join tb_policy_file_in_list t2 on t1.policy_file_in_seq = t2.policy_file_in_seq
						where  1=1 "
							.$args['search_sql']." 
					) 
					SELECT a.*
					FROM PolicyList  a
					WHERE rnum > ".$args['start'];

				if($args['excel_download_flag']=="1"){
					$sql .= " AND rnum <= " . ($args['start'] + RECORD_LIMIT_PER_FILE);	
				}

				//echo $sql;

				$result = $this->fetchAll($sql);
				
				return $result;

		}

		/*
		* 파일예외정책 파일리스트 count 가져오기
		*/
		function getFileInPolicyFileListCount($args){
			$this->args = $args;

			$sql = " select  count(t1.policy_file_in_seq) as cnt
					From tb_policy_file_in t1
							inner join tb_policy_file_in_list t2 on t1.policy_file_in_seq = t2.policy_file_in_seq
					where  1= 1"
						.$args['search_sql'];
			
			$result = $this->fetch($sql);
			
			return $result;
		}

		
		/*
		* 카카오뱅크 연동 인사정보 조회
		*/
		function getKaBangEmp($args){
			$this->args = $args;

			if($args['target']=="EMP"){
				$search_sql = " and (emp_name = '".aes_256_enc($args['searchkey'])."%' or emp_id like '".$args['searchkey']."%')";
			}else if($args['target']=="DEPT"){
				$search_sql = " and dept_name like '".$args['searchkey']."%' ";
			}else{
				$search_sql = " and 1=2";
			}

			$sql = " select  emp_name,emp_id,dept_name,dept_code
					From tb_emp_kakaobank 
					where  1= 1"
						.$search_sql;
			
			$result = $this->fetchAll_Count($sql);
			
			return $result;
		}

		/*
		* 카카오뱅크 연동 부서정보 조회
		*/
		function getKaBangDept($args){
			$this->args = $args;

			$search_sql = " and dept_name like '".$args['searchkey']."%' ";

			$sql = " select  distinct dept_name,dept_code
					From tb_emp_kakaobank 
					where  1= 1"
						.$search_sql;
			
			$result = $this->fetchAll_Count($sql);
			
			return $result;
		}

		/*
		* Code 항목가져오기
		*/
		function getCodeList($args){
			$this->args = $args;

			$search_sql = $args['search_sql'];

			if($args['code_key'] != ""){
				$search_sql .= " and code_key ='".$args['code_key']."' ";
			}else if($args['use_yn'] != ""){
				$search_sql .= " and use_yn ='".$args['use_yn']."' ";
			}

			$sql = " select  code_key,code_name,depth,sort,use_yn
					From tb_code 
					where  1= 1"
						.$search_sql."
					Order by depth,sort";
			
			$result = $this->fetchAll($sql);
			
			return $result;
		}

		/*
		* 동의서 내용 가져오기
		*/
		function getAgreeContent($args){
			$this->args = $args;
			
			$sql = "Select agree_config_seq, agree_div, agree_title, agree_content, agree_bottom, agree_lang
						, request_consent_yn, company_code, use_yn
					From tb_agree_config 
					Where agree_div = '".$args['agree_div']."'
						and agree_lang='".$args['agree_lang']."'
						and company_code = '".COMPANY_CODE."' ";

			$result = $this->fetchAll($sql);

			return $result;
		}
		
		/*
		* 동의서 내용 저장하기
		*/
		function registAgreeContent($args){
			$this->args = $args;

			$ymdhis = date("YmdHis");
			$use_yn = "Y";
			$company_code = COMPANY_CODE;

			$sql = "Insert Into tb_agree_config (
				agree_div,agree_title,agree_content,agree_bottom,agree_lang,request_consent_yn,company_code,use_yn,create_date	
			)Values (
			'".$args['agree_div']."','".$args['agree_title']."','".$args['agree_content']."','".$args['agree_bottom']."','".$args['agree_lang']."'
			,'".$args['request_consent_yn']."','{$company_code}','{$use_yn}','{$ymdhis}'	);";

			 $seq = $this->fetchIdentity($sql);
			return $seq;

		}

		/*
		* 동의서 내용 수정하기
		*/
		function updateAgreeContent($args){
			$this->args = $args;

			$sql = "Update tb_agree_config 
						Set agree_title= N'".$args['agree_title']."'
						 ,agree_content= N'".$args['agree_content']."'
						 ,agree_bottom= N'".$args['agree_bottom']."'
						 ,agree_lang= '".$args['agree_lang']."'
						 ,request_consent_yn='".$args['request_consent_yn']."'
						 ,use_yn='".$args['use_yn']."'
						Where agree_config_seq = '".$args['agree_config_seq']."' ";

			 return $this->query($sql);

		}
		
		/*
		* 문서 내용 가져오기
		*/
		function getDocumentContent($args){
			$this->args = $args;
			
			$sql = "
				SELECT form_seq
					, form_div
					, form_title
					, form_content
					, form_lang
					, company_code
					, use_yn
				FROM tb_form 
				WHERE form_div = ?
					AND form_lang = ?
					AND company_code = ?
			";
			$params = array();

			array_push($params, $args['form_div']);
			array_push($params, $args['form_lang']);
			array_push($params, COMPANY_CODE);

			return $this->fetchAll($sql, $params);
		}
		
		/*
		* 문서 내용 저장하기
		*/
		function registDocumentContent($args){
			$this->args = $args;

			$ymdhis = date("YmdHis");

			$sql = "
				INSERT INTO tb_form (
					form_div, form_title, form_content, form_lang, company_code, use_yn, create_date	
				) VALUES (
					?, ?, ?, ?, ?, ?, ?
				);
			";
			
			$params = array();

			array_push($params, $args['form_div']);
			array_push($params, $args['form_title']);
			array_push($params, $args['form_content']);
			array_push($params, $args['form_lang']);
			array_push($params, COMPANY_CODE);
			array_push($params, $args['use_yn']);
			array_push($params, $ymdhis);

			return $this->fetchIdentity($sql, $params);
		}

		/*
		* 문서 내용 수정하기
		*/
		function updateDocumentContent($args){
			$this->args = $args;

			$sql = "
				UPDATE tb_form 
				SET form_title = ?
					, form_content = ?
					, form_lang = ?
					, use_yn = ?
				WHERE form_seq = ?
			";
			$params = array();

			array_push($params, $args['form_title']);
			array_push($params, $args['form_content']);
			array_push($params, $args['form_lang']);
			array_push($params, $args['use_yn']);
			array_push($params, $args['form_seq']);

			return $this->query($sql, $params);
		}

		/*
		* 카카오뱅크 vcs 임직원정보 리스트 가져오기
		*/
		function getKabangEmpList($args){
			$this->args = $args;
			
			$sql = " WITH EmpList AS
					(
						select  top ".$args['end']." 
							m.emp_seq, k.emp_name, k.emp_id, k.dept_name, k.dept_name_path, k.status,m.work_yn,m.admin_level
							,a.auth_type, a.preset_seq, a.preset_title
							,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
						from tb_emp_kakaobank k
							inner join tb_employee m on k.emp_id = m.emp_no
							inner join (
								SELECT a1.emp_seq, a2.auth_type, a2.auth_preset_seq as preset_seq, a3.preset_title
								FROM (
									SELECT  emp_seq, MAX(admin_menu_auth_seq) AS auth_seq
									FROM tb_admin_menu_auth
									GROUP BY emp_seq
								) a1
									INNER JOIN tb_admin_menu_auth a2 ON a1.auth_seq = a2.admin_menu_auth_seq
									LEFT JOIN tb_admin_menu_auth_preset a3 on a2.auth_preset_seq = a3.preset_seq
							) a on m.emp_seq = a.emp_seq
						WHERE k.status = '1'  " .$args['search_sql']." 

					) 
					SELECT a.*
					FROM EmpList  a
					WHERE rnum > ".$args['start'];

				if($args['excel_download_flag']=="1"){
					$sql .= " AND rnum <= " . ($args['start'] + RECORD_LIMIT_PER_FILE);	
				}
				$result = $this->fetchAll($sql);


				//echo $sql ;

				return $result;			

		}

		/*
		* 카카오뱅크 vcs 임직원정보 리스트 카운트 가져오기
		*/
		function getKabangEmpListCount($args){
			$this->args = $args;
			
			$sql = " select  count(k.emp_seq) as cnt
					from tb_emp_kakaobank k
						inner join tb_employee m on k.emp_id = m.emp_no
						inner join (
							SELECT a1.emp_seq, a2.auth_type, a2.auth_preset_seq as preset_seq, a3.preset_title
							FROM (
								SELECT  emp_seq, MAX(admin_menu_auth_seq) AS auth_seq
								FROM tb_admin_menu_auth
								GROUP BY emp_seq
							) a1
								INNER JOIN tb_admin_menu_auth a2 ON a1.auth_seq = a2.admin_menu_auth_seq
								LEFT JOIN tb_admin_menu_auth_preset a3 on a2.auth_preset_seq = a3.preset_seq
						) a on m.emp_seq = a.emp_seq
					WHERE k.status = '1'" .$args['search_sql'];	

			$result = $this->fetch($sql);
			
			return $result;			

		}

		/*
		*  카뱅 vcs 임직원정보 가져오기 
		*/
		function getKabangEmpListAll($args){
			$this->args = $args;
			
			$sql = "
				select  k.emp_name, k.emp_id, k.dept_name, k.dept_name_path, k.status
						,m.emp_seq,m.work_yn,m.admin_level,m.org_id
				from tb_emp_kakaobank k
					inner join tb_employee m on k.emp_id = m.emp_no 
				where k.status = '1' ".$args['search_sql'];

			return $this->fetchAll($sql);
		}

		/*
		* 카카오뱅크 vcs 임직원정보 정보 가져오기
		*/
		function getKabangEmpInfo($args){
			$this->args = $args;
			
			$sql = " select  k.emp_name, k.emp_id, k.dept_name, k.dept_name_path, k.status
							,m.emp_seq,m.work_yn,m.admin_level,m.org_id
					from tb_emp_kakaobank k
						inner join tb_employee m on k.emp_id = m.emp_no
					WHERE m.emp_seq = '".$args[emp_seq]."'";
			
			$result = $this->fetchAll($sql);
			
			return $result;
		}

		/*
		* 카카오뱅크 임직원정보 조회
		*/
		function findKabangEmpInfoByID($args){
			$this->args = $args;
			
			$sql = " select  k.emp_name, k.emp_id, k.dept_name, k.dept_name_path, k.status
					from tb_emp_kakaobank k
					WHERE emp_id = '".$args[emp_id]."'
							and status = '1'	--재직 ";

			$result = $this->fetchAll($sql);
			
			return $result;
		}

		/*
		* 카카오뱅크 동기화 임직원정보 리스트 가져오기
		*/
		function getKabangSyncEmpList($args){
			$this->args = $args;
			
			$sql = " select 
							k.emp_seq, k.emp_name, k.emp_id, k.dept_name, k.dept_name_path, k.status, e.emp_seq as vcs_emp_seq
						from tb_emp_kakaobank k 
							left join tb_employee e on k.emp_id = e.emp_no
						WHERE k.status='1'  " .$args['search_sql'];

				$result = $this->fetchAll_Count($sql);

				return $result;			

		}
		
		/*
		* 임직원정보 업데이트
		*/
		function updateEmpInfo($args){
			$this->args = $args;

			$_ck_user_seq = $this->_ck_user_seq;
			
			$sql = " update tb_employee
					set admin_level = '".$args[admin_level]."'
						,work_yn = '".$args[work_yn]."'
						,modify_dt = getdate()
						,modify_emp_seq = '".$_ck_user_seq."'
					WHERE emp_seq = '".$args[emp_seq]."'";
			
			$result = $this->query($sql);
			
			return $result;
		}

		/*
		* 임직원정보 사용자등급 업데이트
		*/
		function updateEmpLevel($args){
			$this->args = $args;

			$_ck_user_seq = $this->_ck_user_seq;
			
			$sql = " update tb_employee
					set admin_level = '".$args[admin_level]."'
						,modify_dt = getdate()
						,modify_emp_seq = '".$_ck_user_seq."'
					WHERE emp_seq = '".$args[emp_seq]."'";
			
			$result = $this->query($sql);
			
			return $result;
		}

		/*
		* 메뉴목록가져오기
		*/
		function getMenuList(){
			$this->args = $args;

			$sql = "SELECT menu_code,menu_name FROM tb_menu WHERE use_yn='Y' ORDER BY sort";
			return $this->fetchAll($sql);
		}

		/*
		* 기관 목록 가져오기
		*/
		function getOrganList(){
			$this->args = $args;

			$sql = "SELECT * FROM tb_organ WHERE use_yn = 'Y' order by org_name ";
			return $this->fetchAll_Count($sql);
		}
		
		/*
		* 검사장 목록 가져오기
		*/
		function getCenterList($args=array()){
			$this->args = $args;
		
			$sql = "
				SELECT * 
				FROM tb_scan_center cn
					INNER JOIN tb_organ org ON cn.org_id = org.org_id
				WHERE ".getCheckScanCenterAuthQuery('cn.org_id','cn.scan_center_code')."  
					and cn.use_yn = 'Y' 
					".$args['search_sql']."
				ORDER BY cn.sort,scan_center_name ";
			return $this->fetchAll($sql);
		}

		/*
		* 임직원 아이디 정보 얻기
		*/
		function getEmpIDBySeq($args){
			$this->args = $args;

			$sql = "Select emp_no from tb_employee where emp_seq='".$args['emp_seq']."'";
			return $this->fetch($sql);
		}

		/*
		* 임직원 정보 얻기
		*/
		function getEmpInfoBySeq($args){
			$this->args = $args;

			if($args['emp_seq'] != ""){
				$search_sql = " and emp_seq ='".$args['emp_seq']."' ";
			}

			if($args['search_sql'] !=""){
				$search_sql .= $args['search_sql'];
			}

			$sql = "Select emp_seq,emp_no,admin_level,emp_name from tb_employee where 1= 1 ".$search_sql;

			//echo $sql;
			return $this->fetchAll($sql);
		}

		/*
		* 관리자/임직원 메뉴 권한 가져오기
		*/
		function getEmpMenuDetailAuth($args){
			$this->args = $args;

			$auth = $this->getAdminMenuAuth($args);

			$auth_type = $auth["auth_type"];
			$preset_seq = $auth["auth_preset_seq"];

			if ($auth_type == "PRESET") {
				$sql = "Select menu_code,page_code,exec_auth 
					FROM tb_admin_menu_auth_preset_detail
					WHERE  preset_seq = '".$preset_seq."' 
					ORDER BY detail_seq ";
			} else {
				$sql = "Select menu_code,page_code,exec_auth 
					from tb_admin_menu 
					where  emp_seq='".$args['emp_seq']."' 
						and page_code is not null
						and ( isnull(admin_menu_auth_seq,0) = 0 or  
							admin_menu_auth_seq = (
							Select top 1 admin_menu_auth_seq
							From tb_admin_menu_auth
							Where emp_seq='".$args['emp_seq']."'
							Order by admin_menu_auth_seq desc) ) ";
			}

			return $this->fetchAll($sql);
		}

		/*
		* 관리자/임직원 메뉴 세부 권한 설정하기
		*/
		function saveEmpMenuDetailAuth($args){

			$this->args = $args;
			$_ck_user_seq = $this->_ck_user_seq;
			$ymdhis = date("YmdHis");

			$sql = "Insert into tb_admin_menu_auth(emp_seq,create_date,create_emp_seq) 
					Values('".$args[emp_seq]."','".$ymdhis."','".$_ck_user_seq."') ";

			$admin_menu_auth_seq = $this->fetchIdentity($sql);

			if($admin_menu_auth_seq > 0){

				foreach($args['menu_auth'] as $menu_code=>$pagelist){

					foreach($pagelist as $page_code=>$exec_auth){

						$sql = "Insert into tb_admin_menu(
							create_emp_seq,create_dt,modify_emp_seq,modify_dt,emp_seq,menu_code,page_code,exec_auth,admin_menu_auth_seq
						)Values (
							'".$_ck_user_seq."',getdate(),'".$_ck_user_seq."',getdate(),'".$args[emp_seq]."','{$menu_code}','{$page_code}','{$exec_auth}'
							,'{$admin_menu_auth_seq}'
						); ";

						$result = $this->query($sql);

						if(!$result) return false;
					}
				}
			}

			return $result;

		}


						/*
		*   보안USB관리 리스트 가져오기
		*/
		function getUsbList($args){
			$this->args = $args;
			
			$sql = " WITH UsbList AS
					(
						select  top ".$args['end']." usb_seq,usb_id,user_id,create_date,access_date,access_emp_seq, b.emp_no, b.emp_name
							,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
						from tb_usb a
						LEFT JOIN tb_employee b on a.access_emp_seq=b.emp_seq
						where  1=1  " .$args['search_sql']." 

					) 
					SELECT a.*
					FROM UsbList  a
					WHERE rnum > ".$args['start'];

				if($args['excel_download_flag']=="1"){
					$sql .= " AND rnum <= " . ($args['start'] + RECORD_LIMIT_PER_FILE);	
				}
				$result = $this->fetchAll($sql);


				//echo $sql ;

				return $result;			

		}

		/*
		* 보안USB관리 리스트 카운트 가져오기
		*/
		function getUsbListCount($args){
			$this->args = $args;
			
			$sql = " select  count(usb_seq) as cnt
					from tb_usb
					WHERE 1=1 " .$args['search_sql'];	
			
			$result = $this->fetch($sql);
			
			return $result;			

		}

				/*
		* 보안USB관리 info
		*/
		function getUsbListInfo($args){

			$this->args = $args;

				$sql = "WITH UsbList AS
				(
						select usb_seq,usb_id,user_id,create_date,access_date,access_emp_seq, b.emp_no, b.emp_name
						,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
						from tb_usb a
						LEFT JOIN tb_employee b on a.access_emp_seq=b.emp_seq
						WHERE 1 = 1 ".$args['search_sql']."
				)
				SELECT *
						FROM	UsbList
						WHERE usb_seq=" .$args['usb_seq']."";
				$result = $this->fetchAll($sql);
		
				return $result;

		}

		/*
		* 보안USB관리 삭제
		*/
		function deleteUsbList($args){
			$this->args = $args;

		$sql = "Delete 
				From tb_usb 
				Where usb_seq = " . $args['usb_seq'];

			return $this->query($sql);
				
		}


			/*
		* 보안USB관리 PREV_NEXT
		*/
	function usbListPrevNext($args){
		$this->args = $args;
		$sql = " WITH UsbList AS 
		(
			 select usb_seq,usb_id,user_id,create_date,access_date,access_emp_seq
			 ,ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
			 from tb_usb
			 WHERE 1 = 1 ".$args['search_sql']."
		) 
		SELECT TOP 1 *
		FROM UsbList 
		WHERE 1 = 1 " . $params['prev_next_sql'];
		$result = $this->fetchAll($sql);
		
		return $result;
	}

			/*
		* 보안USB관리 등록하기
		*/
		function createUsbList($args){
			$this->args = $args;
			$ymdhis = date("YmdHis");

				$sql = "Insert Into tb_usb (
						usb_id,user_id,create_date,access_date,access_emp_seq
					)Values (
					'".$args['usb_id']."', '".$args['user_id']."','{$ymdhis}',dbo.fn_ymdhis(), '" . $_SESSION['user_seq'] . "' );";
								
				$result =$this->query($sql);
	
			return $result;

		}
		/*
		* 보안USB관리 업데이트하기
		*/

		function updateUsbList($args) {

			$this->args = $args;
			
			$sql = "UPDATE tb_usb 
					SET  usb_id = '" . $args['usb_id'] . "'
					  ,user_id = '" . $args['user_id'] . "'
						,access_date = dbo.fn_ymdhis()
						,access_emp_seq = '" . $_SESSION['user_seq'] . "' 
						WHERE usb_seq = '" . $args['usb_seq'] . "'";
						
						return $this->query($sql);
					}
							
		/*
		*	보안USB관리 중복체크
		*/
	
		function checkExistsUsbList($args){
		 $this->args = $args;

			$sql = "select count(usb_seq) as cnt
			from tb_usb
			WHERE ".$args[search_sql];	

			$result = $this->fetch($sql);
			
			return $result;
		}


		/*
		* 메뉴 관리 그룹 Count 가져오기
		*/
		function getAdminMenuAuthPresetCount($args) {
			$this->args = $args;

			extract($args); // preset_title

			$where  = "WHERE p1.preset_seq > 0";
			$params = array();

			if (!empty($preset_title)) {
				$where .= "	AND p1.preset_title LIKE CONCAT('%', ?, '%')";
				array_push($params, $preset_title);
			}


			$sql = "
				SELECT COUNT(p1.preset_seq) AS cnt
				FROM tb_admin_menu_auth_preset p1
				{$where}
			";
			
			$result = $this->fetch($sql, $params);

			return $result;
		}

		/*
		* 메뉴 관리 그룹 목록 가져오기
		*/
		public function getAdminMenuAuthPresetLIsts($args) {
			$this->args = $args;

			extract($args); // preset_title

			$where  = "WHERE p1.preset_seq > 0";
			$params = array();

			if (!empty($preset_title)) {
				$where .= "	AND p1.preset_title LIKE CONCAT('%', ?, '%')";
				array_push($params, $preset_title);
			}

			if (!empty($search_use_yn)) {
				$where .= "	AND p1.use_yn = ?";
				array_push($params, $search_use_yn);
			}

			if (!empty($search_admin_level)) {
				$where .= "	AND p1.admin_level IN (?)";

				if ($search_admin_level == "NONE") {
					$search_admin_level = "";
				}

				array_push($params, $search_admin_level);
			}

			$sql = "
				WITH PresetLIst AS (
					SELECT TOP {$end} 
						p1.preset_seq
						, p1.preset_title
						, p1.admin_level
						, p1.use_yn
						, e.emp_no
						, e.emp_name
						, ISNULL(a.preset_used, 0) AS preset_used
						, p1.create_emp_seq
						, p1.create_date
						, ROW_NUMBER() OVER(ORDER BY e.emp_seq DESC) AS rnum
					FROM tb_admin_menu_auth_preset p1
						LEFT JOIN (
							SELECT a3.preset_seq, count(*) AS preset_used
							FROM ( SELECT emp_seq, MAX(admin_menu_auth_seq) AS auth_seq FROM tb_admin_menu_auth GROUP BY emp_seq ) a1
								INNER JOIN tb_admin_menu_auth a2 ON a1.auth_seq = a2.admin_menu_auth_seq
								INNER JOIN tb_admin_menu_auth_preset a3 ON a2.auth_preset_seq = a3.preset_seq
								INNER JOIN tb_employee e ON a1.emp_seq = e.emp_seq
							GROUP BY a3.preset_seq
						) a ON p1.preset_seq = a.preset_seq
						LEFT JOIN tb_employee e ON p1.create_emp_seq = e.emp_seq
					{$where}
					ORDER BY p1.preset_seq DESC
				) 
				SELECT t.*
				FROM PresetLIst t
				WHERE rnum > {$start}
			";
			
			$rows = $this->fetchArray($sql, $params);

			return $rows;
		}

		/*
		* 메뉴 관리 그룹 가져오기
		*/
		public function getAdminAuthPresetLists($args) {
			$this->args = $args;

			extract($args); // preset_title

			$where  = "WHERE p1.preset_seq > 0";
			$params = array();

			if (!empty($preset_title)) {
				$where .= "	AND p1.preset_title LIKE CONCAT('%', ?, '%')";
				array_push($params, $preset_title);
			}

			if (!empty($use_yn)) {
				$where .= "	AND p1.use_yn = ?";
				array_push($params, $use_yn);
			}

			if (!empty($admin_level)) {
				$where .= "	AND p1.admin_level IN (?, ?)";

				if ($admin_level == "SUPER") {
					array_push($params, $admin_level);
					array_push($params, $admin_level);
				} else {
					array_push($params, "");
					array_push($params, $admin_level);
				}
			}

			$sql = "
				SELECT p1.preset_seq
					, p1.preset_title
					, p1.admin_level AS target_level
					, p1.use_yn
					, e.emp_no
					, e.emp_name
					, p1.create_emp_seq
					, p1.create_date
				FROM tb_admin_menu_auth_preset p1
					LEFT JOIN tb_employee e ON p1.create_emp_seq = e.emp_seq
				{$where}
				ORDER BY p1.preset_seq DESC
			";

			$rows = $this->fetchArray($sql, $params);

			return $rows;
		}

		/*
		* 메뉴 관리 그룹 내용 가져오기
		*/
		public function getAdminMenuAuthPreset($args) {
			$this->args = $args;

			extract($args); // preset_seq

			$params = array($preset_seq);

			{	// 권한그룹 
				$sql = "
					SELECT p1.preset_seq
						, p1.preset_title
						, p1.admin_level
						, p1.use_yn
						, e.emp_no
						, e.emp_name
						, p1.create_emp_seq
						, p1.create_date
					FROM tb_admin_menu_auth_preset p1
						LEFT JOIN tb_employee e ON p1.create_emp_seq = e.emp_seq
					WHERE p1.preset_seq = ?;
				";
				
				$preset = $this->fetchOne($sql, $params);
				$preset["scan_center"] = array();
				$preset["menu_auth"]   = array();
			}

			{	// 관리 검사장
				$sql = "
					SELECT p1.scan_center_code
					FROM tb_admin_menu_auth_preset_scan_center p1
					WHERE p1.preset_seq = ?
					ORDER BY p1.scan_center_seq DESC
				";
				
				$result = $this->fetchAll($sql, $params);
				
				if ($result) {
					$scan_center = array();
					while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
						@extract($row);
						array_push($scan_center, $scan_center_code);
					}
					
					$preset["scan_center"] = $scan_center;
				}
			}

			{	// 페이지 접근 권한
				$sql = "
					SELECT p1.detail_seq
						, p1.menu_code
						, p1.page_code
						, p1.exec_auth
					FROM tb_admin_menu_auth_preset_detail p1
					WHERE p1.preset_seq = ?
					ORDER BY p1.detail_seq DESC
				";
				
				$result = $this->fetchAll($sql, $params);
				
				if ($result) {
					$menu_auth = array();
					while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
						@extract($row);

						if (!isset($menu_auth[$menu_code])) {
							$menu_auth[$menu_code] = array();
						}

						$menu_auth[$menu_code][$page_code] = $exec_auth;
					}
					
					$preset["menu_auth"] = $menu_auth;
				}
			}

			return $preset;
		}


		/*
		* 메뉴 관리 그룹 관리검사장 삭제
		*/
		function deleteAdminMenuAuthPresetScanCenters($preset_seq) {
			if ($preset_seq > 0) {
				$sql = "
					DELETE
					FROM tb_admin_menu_auth_preset_scan_center
					WHERE preset_seq = ?
				";

				$this->query($sql, array($preset_seq));
			}
		}


		/*
		* 메뉴 관리 그룹 관리검사장 등록
		*/
		function registAdminMenuAuthPresetScanCenters($preset_seq, $scan_center) {
			if ($preset_seq > 0) {
				$sql = "
					INSERT INTO tb_admin_menu_auth_preset_scan_center (
						preset_seq, scan_center_code
					) VALUES (
						?, ?
					)
				";

				$scan_center_code = null;

				//$stmt = $this->prepare($sql, array(&$preset_seq, &$scan_center_code));

				foreach ($scan_center as $index => $scan_center_code) {
					$result =  $this->query($sql, array($preset_seq, $scan_center_code));

					if (!$result) {
						return false;
					}
				}

				return $result;
			} else {
				return false;
			}
		}


		/*
		* 메뉴 관리 그룹 세부 권한 삭제
		*/
		function deleteAdminMenuAuthPresetDetails($preset_seq) {
			if ($preset_seq > 0) {
				$sql = "
					DELETE
					FROM tb_admin_menu_auth_preset_detail
					WHERE preset_seq = ?
				";

				$this->query($sql, array($preset_seq));
			}
		}


		/*
		* 메뉴 관리 그룹 세부 권한 등록
		*/
		function registAdminMenuAuthPresetDetails($preset_seq, $menu_auth) {
			if ($preset_seq > 0) {
				$sql = "
					INSERT INTO tb_admin_menu_auth_preset_detail (
						preset_seq, menu_code, page_code, exec_auth
					) VALUES (
						?, ?, ?, ?
					)
				";

				$menu_code = null;
				$page_code = null;
				$exec_auth = null;

				foreach ($menu_auth as $menu_code => $page_list) {
					foreach ($page_list as $page_code => $exec_auth) {
						$result =  $this->query($sql, array($preset_seq, $menu_code, $page_code, $exec_auth));
						if (!$result) {
							return false;
						}
					}
				}

				return $result;
			} else {
				return false;
			}
		}
		

		/*
		* 메뉴 관리 그룹 등록
		*/
		function registAdminMenuAuthPreset($args) {
			$this->args = $args;
			@extract($args);
			$create_date = date("YmdHis");

			if ($use_yn == "N") {
				$admin_level = "";
			}

			{
				$sql = "
					INSERT INTO tb_admin_menu_auth_preset (
						preset_title, admin_level, use_yn, create_emp_seq, create_date
					) Values (
						?, ?, ?, ?, ?
					) 
				";

				$params = array();

				array_push($params, $preset_title);
				array_push($params, $admin_level);
				array_push($params, $use_yn);
				array_push($params, $create_emp_seq);
				array_push($params, $create_date);

				$preset_seq = $this->fetchIdentity($sql, $params);
			}

			if ($preset_seq > 0) {
				if (sizeof($scan_center) > 0) {
					$result = $this->registAdminMenuAuthPresetScanCenters($preset_seq, $scan_center);
				}
			}

			if ($preset_seq > 0) {
				if (sizeof($menu_auth) > 0) {
					$result = $this->registAdminMenuAuthPresetDetails($preset_seq, $menu_auth);
				}
			}

			return $preset_seq;
		}
		
		
		/*
		* 메뉴 관리 그룹 수정
		*/
		function updateAdminMenuAuthPreset($args) {
			$this->args = $args;
			@extract($args);

			if (empty($preset_seq)) {
				return false;
			}

			$modify_date = date("YmdHis");

			if ($use_yn == "N") {
				$admin_level = "";
			}

			{
				$sql = "
					UPDATE tb_admin_menu_auth_preset
					SET preset_title = ?
						, admin_level = ?
						, use_yn = ?
						, modify_emp_seq = ?
						, modify_date = ?
					WHERE preset_seq = ?
				";

				$params = array();

				array_push($params, $preset_title);
				array_push($params, $admin_level);
				array_push($params, $use_yn);
				array_push($params, $modify_emp_seq);
				array_push($params, $modify_date);
				array_push($params, $preset_seq);

				$result = $this->query($sql, $params);
			}

			if ($result) {
				$this->deleteAdminMenuAuthPresetScanCenters($preset_seq);

				if (sizeof($scan_center) > 0) {
					$result = $this->registAdminMenuAuthPresetScanCenters($preset_seq, $scan_center);
				}
			}

			if ($result) {
				$this->deleteAdminMenuAuthPresetDetails($preset_seq);

				if (sizeof($menu_auth) > 0) {
					$result = $this->registAdminMenuAuthPresetDetails($preset_seq, $menu_auth);
				}
			}

			return $result;
		}

		
		/*
		* 메뉴 관리 그룹 사용중인 회원 수
		*/
		function getAdminMenuAuthPresetUsedCount($args) {
			$this->args = $args;
			@extract($args);

			if (empty($preset_seq)) {
				return false;
			}

			{
				$sql = "
					SELECT COUNT(a2.admin_menu_auth_seq) AS cnt
					FROM ( SELECT emp_seq, MAX(admin_menu_auth_seq) AS auth_seq FROM tb_admin_menu_auth GROUP BY emp_seq ) a1
						INNER JOIN tb_admin_menu_auth a2 ON a1.auth_seq = a2.admin_menu_auth_seq
                        INNER JOIN tb_admin_menu_auth_preset a3 ON a2.auth_preset_seq = a3.preset_seq
                        INNER JOIN tb_employee e ON a1.emp_seq = e.emp_seq
						LEFT  JOIN tb_emp_kakaobank k on e.emp_no = k.emp_id
					WHERE a2.auth_preset_seq = ?
				";
				
				return $this->fetch($sql, array($preset_seq));
			}
		}

		
		/*
		* 메뉴 관리 그룹 사용중인 회원 목록
		*/
		function getAdminMenuAuthPresetUsedLists($args) {
			$this->args = $args;
			@extract($args);

			if (empty($preset_seq)) {
				return false;
			}
			{
				$sql = "
					WITH PresetUsedLIst AS (
						SELECT TOP {$end} 
							e.emp_seq
							, e.emp_no AS emp_id
							, CASE WHEN k.emp_seq IS NULL THEN e.emp_name ELSE k.emp_name END AS emp_name
							, CASE WHEN k.emp_seq IS NULL THEN 'ADMIN' ELSE 'STAFF' END AS emp_type
							, k.dept_name
							, k.dept_name_path
							, k.status
							, e.work_yn
							, e.admin_level
							, ROW_NUMBER() OVER(ORDER BY e.emp_seq DESC) AS rnum
						FROM ( SELECT emp_seq, MAX(admin_menu_auth_seq) AS auth_seq FROM tb_admin_menu_auth GROUP BY emp_seq ) a1
							INNER JOIN tb_admin_menu_auth a2 ON a1.auth_seq = a2.admin_menu_auth_seq
							INNER JOIN tb_admin_menu_auth_preset a3 ON a2.auth_preset_seq = a3.preset_seq
							INNER JOIN tb_employee e ON a1.emp_seq = e.emp_seq
                            LEFT  JOIN tb_emp_kakaobank k on e.emp_no = k.emp_id
						WHERE a2.auth_preset_seq = ?
					) 
					SELECT t.*
					FROM PresetUsedLIst t
					WHERE rnum > {$start}
				";

				return $this->fetchArray($sql, array($preset_seq));
			}
		}

		
		/*
		* 메뉴 관리 그룹 삭제
		*/
		function deleteAdminMenuAuthPreset($args) {
			$this->args = $args;
			@extract($args);

			if (empty($preset_seq)) {
				return false;
			}

			{
				$sql = "
					DELETE
					FROM tb_admin_menu_auth_preset
					WHERE preset_seq = ?
				";
				$result = $this->query($sql, array($preset_seq));
			}

			if ($result) {
				$this->deleteAdminMenuAuthPresetDetails($preset_seq);
			}

			return $result;
		}

		
		/*
		* 현재 사용중인 메뉴 관리 그룹 가져오기
		*/
		function getAdminMenuAuth($args) {
			$this->args = $args;
			@extract($args);

			if (empty($emp_seq)) {
				return false;
			}

			{
				$sql = "
					SELECT TOP 1 admin_menu_auth_seq
						, auth_type
						, auth_preset_seq
					FROM tb_admin_menu_auth
					WHERE  emp_seq = ?
					ORDER BY admin_menu_auth_seq DESC
				";
				$result = $this->fetchOne($sql, array($emp_seq));
			}

			return $result;
		}

		
		/*
		* 현재 사용중인 메뉴 관리 그룹 가져오기
		*/
		function getAdminMenuCustomized($args) {
			$this->args = $args;
			@extract($args);

			if (empty($emp_seq)) {
				return false;
			}

			{
				$sql = "
					SELECT menu_code, CASE WHEN page_code = 1 THEN 'all' ELSE 'some' END AS page_code
					FROM (
						SELECT menu_code, MAX(CASE WHEN page_code = 'all' AND exec_auth = 'R,C,U,D,P' THEN 1 ELSE 0 END) AS page_code
						FROM tb_admin_menu 
						WHERE emp_seq = ?
							AND ( ISNULL(admin_menu_auth_seq, 0) = 0 
								OR admin_menu_auth_seq = (
									SELECT TOP 1 admin_menu_auth_seq
									FROM tb_admin_menu_auth
									WHERE emp_seq = ?
									ORDER BY admin_menu_auth_seq DESC )
								)
							AND page_code IS NOT NULL
						GROUP BY menu_code
					) t
				";
				$result = $this->fetchArray($sql, array($emp_seq, $emp_seq));
			}

			return $result;
		}

		
		/*
		* 메뉴 권한 저장
		*/
		function insertAdminMenuAuth($args) {
			$this->args = $args;
			@extract($args);

			if (empty($emp_seq)) {
				return false;
			}
			
			$create_date = date("YmdHis");
			
			$sql = "	
				INSERT INTO  tb_admin_menu_auth (
					emp_seq, auth_type, auth_preset_seq, create_emp_seq, create_date
				) VALUES (
					?, ?, ?, ?, ?
				)
			";
			$params = array();

			array_push($params, $emp_seq);
			array_push($params, $auth_type);
			array_push($params, $auth_preset_seq);
			array_push($params, $create_emp_seq);
			array_push($params, $create_date);

			$new_seq = $this->fetchIdentity($sql, $params);

			if (($new_seq > 0) && ($admin_level == "SUPER") && ($auth_type == "CUSTOMIZE")) {
				$args = array("create_emp_seq"=>$create_emp_seq, "emp_seq"=>$emp_seq, "emp_seq"=>$emp_seq, "admin_menu_auth_seq"=>$new_seq);
				$this->insertSuperAdminMenu($args);
			}

			return ($new_seq > 0) ? true : false;
		}

		
		/*
		* 최고관리자 직접지정 메뉴 등록
		*/
		function insertSuperAdminMenu($args) {
			global $_CODE;

			$this->args = $args;
			@extract($args);

			if (empty($emp_seq)) {
				return false;
			}
			
			$page_code = $_CODE["admin_menu_auth"]["SUPER"];
			
			$sql = "	
				INSERT INTO tb_admin_menu (
					menu_code, create_emp_seq, create_dt, emp_seq, page_code, exec_auth, admin_menu_auth_seq
				)
				SELECT value, {$create_emp_seq}, getdate(), {$emp_seq}, 'all', 'C,R,U,D,P', {$admin_menu_auth_seq}
				FROM dbo.fn_split('{$page_code}', ',')
			";

			return $this->query($sql);
		}


		/*
		* 카뱅 조직도 가져오기
		*/
		public function getKabankDepartment($args) {
			$this->args = $args;

			extract($args); // preset_title

			$params = array();

			if (!empty($use_yn)) {
				$where .= "AND use_yn = '$use_yn'";
			}

			$sql = "
			WITH KakaoDept AS (
				SELECT d1.dept_seq
					 , d1.dept_name
					 , d1.dept_name_path
					 , d1.p_dept_seq
					 , d1.depth
					 , d1.sort
				FROM tb_department_kakaobank d1
				WHERE d1.p_dept_seq IS NULL
				{$where}
			
				UNION ALL
			
				SELECT d2.dept_seq
					 , d2.dept_name
					 , d2.dept_name_path
					 , d2.p_dept_seq
					 , d2.depth
					 , d2.sort
				FROM tb_department_kakaobank d2
				INNER JOIN KakaoDept d3 ON d3.dept_seq = d2.p_dept_seq
				WHERE d2.p_dept_seq > 0
				{$where}
			)
			SELECT * 
			FROM KakaoDept WITH(NOLOCK)
			ORDER BY depth, sort, dept_name_path
			";

			$rows = $this->fetchArray($sql, $params);

			return $rows;
		}		

				/*
		* 접속IP관리 등록
		*/
		function loginIPLimitInsert($args){
			$this->args = $args;
			
			$ymdhis = date("YmdHis");

			$sql = "INSERT INTO tb_login_ip_mgt (
						ip_addr,allow_id,memo,admin_seq,create_date	
					)Values ('".$args['ip_addr']."',N'".$args['allow_id']."',N'".$args['memo']."','".$args['admin_seq']."',getdate());";

			 $result =$this->query($sql);
		
			 return $result;

		}
	}
?>