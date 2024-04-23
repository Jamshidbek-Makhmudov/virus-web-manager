<?php
/*
* 커스텀 쿼리 Class
*/
class Model_Utils extends Model
{
		/*
		* 쿼리 count
		*/
		function getQueryEditorsListCount($args){

			$this->args = $args;

			$sql = $args['query'];

			
			$result = $this->fetch($sql);
			
			return $result;

		}
		/*
		* 쿼리 결과 가져오기
		*/
		function getQueryEditorsList($args){
			
			$this->args = $args;

			$sql = "Select top 1000 * From (".$args['query'].") tbl ";

			//echo ($sql);

			$result = $this->fetchAll_Count($sql);

			 return $result;

		}

		/*
		* 쿼리 등록하기
		*/
		function queryRegist($args){
			$this->args = $args;

			//dbo.fn_Base64Encode( N'".$args['searchkey']."'),

			    $ymdhis = date("YmdHis");

				$sql = "Insert Into tb_custom_query (
						query_title, query_content, create_emp_seq, create_date
					)Values (
					N'".$args['query_title']."', N'".$args['query_content']."', '" . $_SESSION['user_seq'] . "' ,'{$ymdhis}'	 );";
								
				$result =$this->query($sql);
	
			return $result;

		}

		/*
		* 쿼리 수정하기
		*/
		function queryUpdate($args){
			$this->args = $args;

			$ymdhis = date("YmdHis");

			$sql = "Update tb_custom_query
				set query_title = N'".$args[query_title]."'
					,query_content = N'".$args[query_content]."'
				where custom_query_seq = '".$args[custom_query_seq]."' ";
				
			$result =$this->query($sql);
	
			return $result;

		}

		
		/*
		* 쿼리 삭제
		*/
		function queryDelete($args){
			$this->args = $args;
				
				$sql .= "Delete 
				From tb_custom_query 
				Where custom_query_seq = '".$args['custom_query_seq']."' ";

			return $this->query($sql);

		}

		/*
		* 쿼리 List count
		*/
		function getQueryListCount($args){
			$this->args = $args;

			$sql = "
			 select  count(custom_query_seq) as cnt
					from tb_custom_query
					WHERE 1= 1 ".$args['search_sql'];

			$result = $this->fetch($sql);
			
			
			return $result;
		}


		/*
		* 쿼리 List // dbo.fn_Base64Decode(query_content)
		*/
		function getQueryListInfo($args){

			$this->args = $args;

				$sql = " WITH QueryList AS
				(
				select  top ".$args['end']."
				 t1.custom_query_seq, t1.query_title,
					dbo.fn_dll_debase64(query_content) as  query_content,
					t1.create_emp_seq,t1.create_date,t2.emp_no,t2.emp_name,
				ROW_NUMBER() OVER(".$args['order_sql'].") as rnum
						from tb_custom_query t1
						left join tb_employee t2 on t2.emp_seq = t1.create_emp_seq
						WHERE 1=1 ".$args['search_sql']."
				
				)	
							SELECT a.*
					FROM QueryList  a
					WHERE rnum > ".$args['start'];

				if($args['excel_download_flag']=="1"){
					$sql .= " AND rnum <= " . ($args['start'] + RECORD_LIMIT_PER_FILE);	
				}	

				$result = $this->fetchAll($sql);
		
				return $result;

		}

		/*
		*	쿼리 info
		*/
		function getQueryInfo($args){
		
			$this->args = $args;

			$sql = "select  
					query_content as query_enc,dbo.fn_dll_debase64(query_content) as  query_content,query_title
				from tb_custom_query 
				where custom_query_seq = '".$args['custom_query_seq']."' ";

			$result = $this->fetchAll($sql);
		
			return $result;
		}






}
?>