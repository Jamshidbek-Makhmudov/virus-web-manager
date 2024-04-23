<?
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;	

?>
<script language="javascript">

var SITE_NAME = '/<?php echo $_site_path;?>';

var dptb_lang_code = "<?php echo $lang_code; ?>";
var user_lang_code = "<?php echo $_COOKIE['user_lang']; ?>";

var lang_code = user_lang_code;

if(lang_code == "") {
	lang_code = dptb_lang_code;
}
if(lang_code == "") {
	lang_code = "KR";
}
</script>