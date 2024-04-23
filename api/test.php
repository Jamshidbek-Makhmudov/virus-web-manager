<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

/* Description
*  방문자정보 등록
*/

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
//include  $_server_path . "/lib/dpt25_config.inc";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";
include "./common.php";


$enc = 'P6yA6UfIA2dofGAs+hA1095+lzggPCKQ90BcOl39nzE=';

echo AES_Rijndael_Decript($enc,$_AES_KEY,$_AES_IV);
exit;
?>