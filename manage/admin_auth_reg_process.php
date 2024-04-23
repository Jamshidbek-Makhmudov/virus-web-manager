<?php
{
    $page_name = "admin_auth_list";

    $_server_path = $_SERVER['DOCUMENT_ROOT'];
    $_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, (strLen($_SERVER['REQUEST_URI']) - 1));
    $_apos = stripos($_REQUEST_URI,  "/");

    if($_apos > 0) {
        $_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
    }

    $_site_path = $_REQUEST_URI;

	include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
}

{
	$proc_exec    = $_POST["proc_exec"];
	$proc_name    = $_POST["proc_name"];
	$preset_seq   = $_POST["preset_seq"];
	$preset_title = $_POST["preset_title"];
	$admin_level  = $_POST["admin_level"];
	$use_yn       = $_POST["use_yn"];
	$scan_center  = $_POST["mng_scan_center"];
	$menu_auth    = array();

	$str_page_auth = ($admin_level == "SUPER") ? "super_page_auth" : "page_auth";
	$str_exec_auth = ($admin_level == "SUPER") ? "super_exec_auth" : "exec_auth";
	
	$work_log_seq = WriteAdminActLog($proc_name, $proc_exec);
	
	$Model_manage = new Model_manage();
}

foreach ($_PAGE as $cate => $menu) {
	if ($cate == "MAIN") {
		continue;
	}

	$menu_code = $menu['MENU_CODE'];
	$page_list = $_POST["{$str_page_auth}_{$menu_code}"];

	if(is_array($page_list)==true){
		foreach($page_list as $page_code){
			$menu_auth[$menu_code][$page_code] = implode(",", $_POST["{$str_exec_auth}_{$menu_code}_{$page_code}"]);
		}
	}
}



if ($proc_exec == "CREATE") {
	$create_emp_seq = $_ck_user_seq;
	$args = @compact("preset_title", "admin_level", "use_yn", "create_emp_seq", "scan_center", "menu_auth");
	$preset_seq = $Model_manage->registAdminMenuAuthPreset($args);
	
	$result = ($preset_seq > 0);

} else if ($proc_exec == "UPDATE") {
	$modify_emp_seq = $_ck_user_seq;
	$args = @compact("preset_seq", "preset_title", "admin_level", "use_yn", "modify_emp_seq", "scan_center", "menu_auth");
	$result = $Model_manage->updateAdminMenuAuthPreset($args);

} else if ($proc_exec == "DELETE") {
	$args = @compact("preset_seq");
	$result = $Model_manage->deleteAdminMenuAuthPreset($args);
	
	if ($result) {
		printJson_OK('delete_ok');
		exit;
	}
}

if ($result) {
	printJson_OK('save_ok', $preset_seq);
} else {
	printJson_OK('proc_error');
}
?>