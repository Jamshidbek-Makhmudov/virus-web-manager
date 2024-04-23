<?
//const DPT_DB_USER_ENC = "1vp6YzZknkkYjHgclCClWg==";
//const DPT_DB_PASSWORD_ENC = "U9lLWkCxYioltM9t2+VYzA==";

//key 24byte 암호화
const DPT_DB_USER_ENC = "1vp6YzZknkkYjHgclCClWg==";
const DPT_DB_PASSWORD_ENC = "qIl7Vg5GJB0MT+V2VbO+QkpCJVVv9gMeb8pWLpcOmh0=";
const DB_SYMMETRIC_KEY_ENC = "1qZsYizo6+nLfk44+3fR4w==";

const WVCS_DB_USER_ENC = "qeRTcAhoiC34d1P6qxFPwg==";
const WVCS_DB_PASSWORD_ENC = "qIl7Vg5GJB0MT+V2VbO+QkpCJVVv9gMeb8pWLpcOmh0=";
const WVCS_DB_SYMMETRIC_KEY_ENC = "1qZsYizo6+nLfk44+3fR4w==";

//key 32byte 암호화


//**WVCS접속
$DB_PATH = "localhost,1444";
$DB_USER = AES_Rijndael_Decript(WVCS_DB_USER_ENC,$_AES_KEY,$_AES_IV);
$DB_PASSWORD =  AES_Rijndael_Decript(WVCS_DB_PASSWORD_ENC,$_AES_KEY,$_AES_IV);
$DB_NAME = 'WVCS';
$WVCS_DB_SYMMETRIC_KEY_ENC = AES_Rijndael_Decript(WVCS_DB_SYMMETRIC_KEY_ENC,$_AES_KEY,$_AES_IV);

//echo $DB_USER;

$wvcs_dbcon = @sqlsrv_connect($DB_PATH, array('UID' => $DB_USER, 'PWD' => $DB_PASSWORD, 'Database' => $DB_NAME, 'CharacterSet'=>'UTF-8'));

//**DPT접속
$DB_PATH = "localhost,1444";
$DB_USER = AES_Rijndael_Decript(DPT_DB_USER_ENC,$_AES_KEY,$_AES_IV);
$DB_PASSWORD =  AES_Rijndael_Decript(DPT_DB_PASSWORD_ENC,$_AES_KEY,$_AES_IV);
$DB_NAME = 'DPT25';
$DB_SYMMETRIC_KEY_ENC = AES_Rijndael_Decript(DB_SYMMETRIC_KEY_ENC,$_AES_KEY,$_AES_IV);

$dpt_dbcon = @sqlsrv_connect($DB_PATH, array('UID' => $DB_USER, 'PWD' => $DB_PASSWORD, 'Database' => $DB_NAME, 'CharacterSet'=>'UTF-8'));
?>