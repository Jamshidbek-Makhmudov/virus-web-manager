<?php
    Class Model {
        //변수
        private $db;
		private $AES_KEY;
		private $AES_IV;
		private $_encryption_kind;

		//작업로그기록
		public $work_log_seq;
		public $args;

		//debuging
		public $SHOW_DEBUG_SQL = false;
		public $SHOW_DEBUG_BACKTRACE = false;
		public $RETURN_SQL_ERRORS = false;
		public $SQL_ERRORS;

        //생성자
        function __construct(){

			global $wvcs_dbcon;
            $this->db = $wvcs_dbcon;
			
			global $_encryption_kind;
			$this->_encryption_kind = $_encryption_kind;

			global $_AES_KEY;
			global $_AES_IV;
			
			$this->AES_KEY = $_AES_KEY;
			$this->AES_IV = $_AES_IV;

			//관리자작업로그
			global $work_log_seq;
			$this->work_log_seq = $work_log_seq;

			//쿠키
			global $_ck_user_seq;
			global $_ck_user_id;
			global $_ck_user_name;

			$this->_ck_user_seq = $_ck_user_seq;
			$this->_ck_user_id = $_ck_user_id;
			$this->_ck_user_name = $_ck_user_name;
		
        }
		

        // 쿼리문 실행
		function query ($sql, $params=array()) {
			$this->debug_print($sql);

			$result = @sqlsrv_query($this->db, $sql, $params);

			if( $result === false ) {
				if( ($errors = @sqlsrv_errors() ) != null) {
					if($this->RETURN_SQL_ERRORS==true){
						$this->SQL_ERRORS = $errors;
					}else{
						if(gethostname()=="dataprotecs"){
							foreach( $errors as $error ) {
								echo "<div style='border:1px solid #000'>";
								echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
								echo "code: ".$error[ 'code']."<br />";
								echo "message: ".$error[ 'message']."<br />";
								echo "<div>";
							}
						}
					}
				}
			}

			// 작업로그상세 기록
			if($this->work_log_seq > 0){
				WriteAdminActDetailLog($this->work_log_seq,$sql,$this->args);
			}

			return $result;
		}

		
		//쿼리문 실행 : sqlsrv_num_rows 구할때 실행
		function fetchAll_Count($sql, $params=array()){
			$this->debug_print($sql);

			$options = array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			$result  = @sqlsrv_query($this->db, $sql, $params, $options );

			if( $result === false ) {
				if( ($errors = @sqlsrv_errors() ) != null) {
					if($this->RETURN_SQL_ERRORS==true){
						$this->SQL_ERRORS = $errors;
					}else{
						if(gethostname()=="dataprotecs"){
							foreach( $errors as $error ) {
								echo "<div style='border:1px solid #000'>";
								echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
								echo "code: ".$error[ 'code']."<br />";
								echo "message: ".$error[ 'message']."<br />";
								echo "<div>";
							}
						}
					}
				}
			}

			return $result;
		}
		

		// 단일 데이터 가져오기
		function fetch($sql, $params=array()) {
			$result = $this->query($sql, $params);
			
			if( @sqlsrv_fetch( $result) === false) {
				return false;
			}else{
				return @sqlsrv_get_field( $result, 0);
			}
		}


		// 한 건 데이터 가져오기
		function fetchOne ($sql, $params=array()) {
			$result = $this->query($sql, $params);

			if(@sqlsrv_has_rows($result)){
				$data = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
				return $data;
			}else{
				return false;
			}
		}


		// 다건 데이터 가져오기
		function fetchAll ($sql, $params=array()) {
			$result = $this->query($sql, $params);

			if(@sqlsrv_has_rows($result)){
				return $result;
			}else{
				return false;
			}
		}


		// 다건 데이터 배열로 가져오기
		function fetchArray ($sql, $params=array()) {
			$result = $this->query($sql, $params);
			$items  = array();

			if(@sqlsrv_has_rows($result)){
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
					array_push($items, $row);
				}

				return $items;
			}else{
				return false;
			}
		}

		
		//실행후 identity 값 가져오기
		function fetchIdentity($sql, $params=array()){
			$sql .= "Select scope_identity(); ";
			$result = $this->query($sql, $params);
			
			@sqlsrv_next_result($result);
			
			if( @sqlsrv_fetch( $result) === false) {
				return 0;
			}else{
				return @sqlsrv_get_field( $result, 0);
			}
		}
		

		//실행
		function execute($sql, $params=array()){
			$result = $this->query($sql, $params);
			$rows_affected = @sqlsrv_rows_affected($result);

			if( $rows_affected === false) {
				return false;			//die( print_r( sqlsrv_errors(), true));
			} elseif( $rows_affected == -1) {
				return false;			//echo "No information available.<br />";
			} else {
				return true;			//echo $rows_affected." rows were updated.<br />";
			}
		}


        // 쿼리문 실행 리소스 생성
		function prepare ($sql, $params=array()) {
			$stmt = @sqlsrv_prepare($this->db, $sql, $params);

			return $stmt;
		}

		function debug_print($log){
			if($this->SHOW_DEBUG_SQL==true) {
				echo "<div style='border:1px solid #000'>";
				echo nl2br($log);
				echo "</div>";
			}

			if($this->SHOW_DEBUG_BACKTRACE==true){
				echo "<div style='border:1px solid #000'>";
				print_r(debug_backtrace());
				echo "</div>";
			}
		}
	}

?>