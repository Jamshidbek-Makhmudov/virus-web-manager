<?
	//**로그파일경로
	if($_log_local_path==""){
		if($_OS_KIND == "LINUX") {
			$_log_local_path = "/DPT/DPTWebManager/htdocs/logs/vcs";
		} else {
			$_log_local_path = "D:\DPTWebManager\htdocs\logs/vcs";
		}
	}

	//로그파일생성
	//if(gethostname()=="dataprotecs"){

		$savedir = $_log_local_path;
		if(is_dir($savedir)==false) @mkdir($savedir, 0777, true);
		$file = $savedir."/vcs_api_" . date('Ymd'). ".log";

		$logFile = @fopen($file, "a+") or die("Unable to open file!");
		fwrite($logFile, $_SERVER['PHP_SELF']."[".date('YmdHis')."]-".$title."\r\n\r\n");
		
		$strParam  = "";
		foreach($_POST as $key=>$val){
			$strParam .=  "POST[".$key."]".$val."\r\n";
		}
		foreach($_GET as $key=>$val){
			$strParam .=  "GET[".$key."]".$val."\r\n";
		}
		fwrite($logFile, str_replace("&quot;","\"",$strParam)."\r\n\r\n");
		fclose($logFile);

	//}

	function writeLog($str,$title=''){

			global $_log_local_path;

			$savedir = $_log_local_path;	

			if(is_dir($savedir)==false) @mkdir($savedir, 0777, true);
			$file = $savedir."/vcs_api_error_" . date('Ymd'). ".log";

			$logFile = @fopen($file, "a+") or die("Unable to open file!");
			fwrite($logFile, $_SERVER['PHP_SELF']."[".date('YmdHis')."]-".$title."\r\n\r\n");
			
			//파라메타 기록
			$strParam  = "";
			foreach($_POST as $key=>$val){
				$strParam .=  "POST[".$key."]".$val."\r\n";
			}
			foreach($_GET as $key=>$val){
				$strParam .=  "GET[".$key."]".$val."\r\n";
			}
			fwrite($logFile, str_replace("&quot;","\"",$strParam)."\r\n");

			
			fwrite($logFile, $str."\r\n\r\n");
			fclose($logFile);
	}
?>