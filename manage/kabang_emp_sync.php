<?
$page_name = "kabang_emp_list";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

exec("D:\DPTWebManager\htdocs\wvcs\batch\kabang_emp_sync_batch.cmd", $output, $return_var);

$jsonResult =  $output[0];

echo $jsonResult;
?>