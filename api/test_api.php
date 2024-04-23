<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_site_path = "wvcs";
//include  $_server_path . "/lib/dpt25_config.inc";
include  $_server_path . "/".$_site_path."/lib/lib.inc";
include  $_server_path . "/".$_site_path."/lib/wvcs_config.inc";
include "./common.php";


?>
<h4>API 주소</h4>
<ul style='border:1px solid #333;padding:20px;'>
	<li>방문자정보가져오기 - <? echo $_www_server?>/api/get_user_info.php?company_code=91</li>
</ul>

<h4>전송 Form</h4>
<form  id='frm' name='frm' method='post'>
<ul style='border:1px solid #333;padding:20px;'>
	<li>전송 주소
		<input type='text' name="url" id='url' style='width:100%' placeholder="URL 입력" value="<? echo $_www_server?>/api/"><BR><BR>
	</li>
	<li>전송값(JSON)
		<input type='text' name="json"  id='json' style='width:100%'  placeholder="JSON값 입력">
	</li>
</ul>
</form>
<button type='button' onclick="submit()">전송</button>
<script>
	function submit(){
		document.frm.action = document.all.url.value;
		document.frm.submit()
}
</script>
