<?
/*
* Description : 외부 파일 반입 예외 파일 정보 API
* VCS Client에서 호출한다.
*/
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
include  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include  $_server_path . "/".$_site_path."/lib/class/load.php";

$raw_value = $_POST['json'];
$data = unQuotChars($raw_value);

/*
if(gethostname()=="dataprotecs"){
	$data = '{
		"json": {
			"v_wvcs_seq": "4129",
			"file": [
				{
					"file_name": "apicfg.ini",
					"md5": "6f9e32c3fc0b6950bf071c4f33119d70"
				},
				{
					"file_name": "scrcfg2.ini",
					"md5": "5917c80b5b75d023c84c49baca20e511"
				}
			]
		}
	}';
}
*/

if($data==""){
	echo "FALSE:INVALID_DATA";
	exit;
}

$Model_api = new Model_Api;
$args = array("data"=>$data);
$bridge_seq = $Model_api->saveBridgeData($args);

if($bridge_seq > 0){
	echo "TRUE:".$bridge_seq;
}else{
	echo "FALSE:ERROR";
}
exit;
?>