<?php

setcookie('user_seq', '', time()-3600, "/{$_site_path}");
setcookie('user_id', '', time()-3600, "/{$_site_path}");
setcookie('user_name', '', time()-3600, "/{$_site_path}");
setcookie('user_org', '', time()-3600, "/{$_site_path}");
setcookie('user_dept', '', time()-3600, "/{$_site_path}");
setcookie('user_level', '', time()-3600, "/{$_site_path}");
setcookie('user_mauth', '', time()-3600, "/{$_site_path}");
setcookie('user_mng_org_auth', '', time()-3600, "/{$_site_path}");
setcookie('user_mng_scan_center_auth', '', time()-3600, "/{$_site_path}");
setcookie('user_pwd_change', '', time()-3600, "/{$_site_path}");
setcookie('user_lsq', '', time()-3600, "/{$_site_path}");
setcookie('user_lang', '', time()-3600, "/{$_site_path}");

//세션종료
if(session_id() != '') {
	session_unset(); 
	session_destroy();  
}
?>