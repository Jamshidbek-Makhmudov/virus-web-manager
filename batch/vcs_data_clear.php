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


$sql = "exec up_UserDataCleanJob;";
$result = @sqlsrv_query($wvcs_dbcon, $sql);

$str_result = $result ? "success" : "fail";

$log_div = "user_data_delete_batch";
writeSystemLog($log_div,$str_result,$msg=$str_result);
echo $str_result;
?>