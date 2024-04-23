<?
/*
* 파일예외반입승인 API 호출 테스트
*/
$api_url = "http://192.168.169.2/wvcs/api/kabang/file_import_apprv.php";
$access_token = "L1BJQ01TcTFpMTY0TllvOGo3TytrTjc3TnRGZ2wxdWxxcUJqd09kUm1GTT0=";
$body = '{"request_no":"M-REQ-231220-0015","apprv_status":"CANCEL","emp_id":"jorba.0"}';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'Authorization: Token ' . $access_token,
	'Content-Type: application/json',
	'Accept: application/json' ));
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
$response = curl_exec($ch);

var_dump($response);

$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//echo $http_code;
curl_close($ch);
exit;
?>