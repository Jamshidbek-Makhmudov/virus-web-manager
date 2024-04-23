<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

/* Description
*  VCS 정책가져오기
*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
//include  $_server_path . "/lib/dpt25_config.inc";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";
include "./common.php";


		$sql_pol = "	SELECT top 1 kiosk_data_delete_day
							FROM tb_policy 
							ORDER BY policy_seq desc";

		$result = sqlsrv_query($wvcs_dbcon, $sql_pol);

		while( $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
			$kiosk_data_delete_day = $row[kiosk_data_delete_day];	//키오스크 데이터 삭제주기
			if($kiosk_data_delete_day=="") $kiosk_data_delete_day = "0";
		}
		echo $kiosk_data_delete_day;

?>