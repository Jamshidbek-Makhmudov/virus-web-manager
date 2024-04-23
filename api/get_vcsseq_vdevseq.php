<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

/* Description
*  DPT 연동 방문자 VCS 점검결과 seq 값 가져오기
*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
//include  $_server_path . "/lib/dpt25_config.inc";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";
include "./common.php";

		$vul_dev_seq = $_REQUEST["vul_dev_seq"];

		$sql = "	SELECT v_wvcs_seq FROM tb_v_wvcs_info WHERE  dpt_vul_dev_seq=".$v_dev_seq;

		$result = sqlsrv_query($wvcs_dbcon, $sql_pol);

		while( $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
			$v_wvcs_seq = $row["v_wvcs_seq"];
		}

		echo $v_wvcs_seq;
	
		//$json_data = json_encode($data);
		//echo $json_data;
		//echo AES_Rijndael_Encript($json_data, $_AES_KEY, $_AES_IV);
?>