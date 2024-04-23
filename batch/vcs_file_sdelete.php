<?
ini_set('memory_limit', '1024M');

if (strpos($_SERVER['windir'], "Windows") || strpos($_SERVER['WINDIR'], "Windows")) {
	$_server_path = "D:/DPTWebManager/htdocs";
} else {
	$_server_path = "/DPT/DPTWebManager/htdocs";
}

$_site_path = "wvcs";

include $_server_path . "/" . $_site_path ."/lib/wvcs_config.inc"; 
include $_server_path . "/" . $_site_path ."/lib/lib.inc"; 
include $_server_path . "/" . $_site_path ."/inc/function.inc"; 

$rtnMsg = "";

$toDay = date('Ymd');
$toDay01 = date('Ymd His');
$file = "../../logs/batch/vcsfiledelete_" . $toDay . ".log";
echo $file . "<br>";

$f = fopen($file,'a+'); 

$str = "start = " .$toDay01 . chr(10);
@fwrite($f,$str);

	//데이터 보관 주기 정책 가져오기
	$sql = "select top 1 data_keep_day from tb_policy order by policy_seq desc";

	$result = sqlsrv_query($wvcs_dbcon, $sql);

	while( $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
		$data_keep_day = $row['data_keep_day'];
	}

	if($data_keep_day > 0 ) {

		$sql = " Select v_wvcs_seq,dbo.fn_make_ymdhis(create_dt) as create_dt 
				From tb_v_wvcs_info 
				Where DATEDIFF ( day , create_dt, getdate() ) > '{$data_keep_day}'
					and isnull(file_delete_flag,'0') in ('','0') ";

	}else{
		$str = "데이터 보관 기간 설정 : ".$data_keep_day;
		@fwrite($f,$str);
		exit;
	}

	$rtnMsg .= $sql . chr(10);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

	$str = $sql . chr(10);
	@fwrite($f,$str);

	while( $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {

		$v_wvcs_seq = $row['v_wvcs_seq'];
		$create_dt = $row['create_dt'];

		$folder_name = substr($create_dt,0,8)."SEQ".$v_wvcs_seq;

		$data_path = $_driver_path."\\DPTServer\\VCSDATA\\".$folder_name;

		echo $data_path."<BR>";

		$str = "폴터 체크시작 : " . $data_path.chr(10);
		@fwrite($f,$str);

		if(file_exists($data_path)) {

				$tempStr = "D:\\DPTWebManager\\htdocs\\wvcs\\batch\\SDelete\\sdelete64.exe -r -s  -q	".$data_path;
				@exec($tempStr, $output, $return_var);

				if($return_var == "0")	{	//삭제성공

					$sql = "update tb_v_wvcs_info 
							set file_delete_flag = '1' 
							where v_wvcs_seq = '{$v_wvcs_seq}'; ";

					$sql .= " update tb_v_wvcs_info_file_in
							set file_delete_flag = '1' 
							where v_wvcs_seq = '{$v_wvcs_seq}'; ";

					//echo nl2br($sql);

					@sqlsrv_query($wvcs_dbcon, $sql);

				}

				$rtnMsg .= $tempStr . chr(10);
				$rtnMsg .= "return_var : ".$return_var.chr(10);
				$str = $tempStr . chr(10);
				@fwrite($f,$str);
		}

	}

	sqlsrv_close($wvcs_dbcon);


$toDay01 = date('Ymd His');
$str = "End = " .$toDay01 . chr(10);
@fwrite($f,$str);
fclose($f);

$rtnMsg .= $str . chr(10);

echo $rtnMsg;
?>