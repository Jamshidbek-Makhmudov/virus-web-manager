<?
/*
* 메뉴 리스트
* $_CODE_PAGE_LINK - /lib/code_wvcs.inc 참조
*/
$_PAGE = array (
		"MAIN"=>array(
				"MENU_CODE"=>"MAIN",
				"MENU_NAME"=> "MAIN",
				"MENU_PATH"=> "/",
				"MENU_LINK"=>$_CODE_PAGE_LINK["MAIN"],
				"PAGE"=>array(
					"main"=>array("HOME","Dashboard","$gate_name"),
					"my_info"=>array("$gate_name",$_LANG_TEXT['m_user'][$lang_code],$_LANG_TEXT['m_my_info'][$lang_code]))
			),
		"USER"=>array(
				"MENU_CODE"=>"U1000",
				"MENU_NAME"=>$_LANG_TEXT['m_visitor'][$lang_code],
				"MENU_PATH"=> "/user",
				"MENU_LINK"=>$_CODE_PAGE_LINK["U1000"],
				"PAGE"=>array(
					"access_control"=>array("$gate_name",$_LANG_TEXT['m_visitor'][$lang_code],$_LANG_TEXT['access_control_theme'][$lang_code]),
					"access_control_idc"=>array("$gate_name",$_LANG_TEXT['m_visitor'][$lang_code],"IDC ".$_LANG_TEXT['access_control_theme'][$lang_code]),
					"rental_details"=>array("$gate_name",$_LANG_TEXT['m_visitor'][$lang_code],trsLang("물품대여","rental_goods")),
					"parking_ticket_payment"=>array("$gate_name",$_LANG_TEXT['m_visitor'][$lang_code],trsLang("주차권지급","parking_ticket_payment")),
					"external_training"=>array("$gate_name",$_LANG_TEXT['m_visitor'][$lang_code],$_LANG_TEXT['External_training'][$lang_code]))
			),
		"RESULT"=>array(
				"MENU_CODE"=>"R1000",
				"MENU_NAME"=>$_LANG_TEXT['m_result'][$lang_code],
				"MENU_PATH"=> "/result",
				"MENU_LINK"=>$_CODE_PAGE_LINK["R1000"],
				"PAGE"=>array(
					"result_list"=>array("$gate_name",$_LANG_TEXT['m_result'][$lang_code],$_LANG_TEXT['m_result'][$lang_code]),
					"file_in_apply_list"=>array("$gate_name",$_LANG_TEXT['m_result'][$lang_code],$_LANG_TEXT['file_in_apply_list'][$lang_code]))
			),
		"STATISTICS"=>array(
				"MENU_CODE"=>"S1000",
				"MENU_NAME"=>$_LANG_TEXT['m_statistics'][$lang_code],
				"MENU_PATH"=> "/stat",
				"MENU_LINK"=>$_CODE_PAGE_LINK["S1000"],
				"PAGE"=>array(
				"file_inspection_details"=>array("$gate_name",$_LANG_TEXT['m_statistics'][$lang_code],$_LANG_TEXT['file_inspection_details'][$lang_code]),
				"rental_info_change_history"=>array("$gate_name",$_LANG_TEXT['m_statistics'][$lang_code],$_LANG_TEXT['rental_info_change_history'][$lang_code]),
				"user_agree_list"=>array("$gate_name",$_LANG_TEXT['m_statistics'][$lang_code],$_LANG_TEXT['user_agree_list'][$lang_code]),
				"idc_checkinout_list"=>array("$gate_name",$_LANG_TEXT['m_statistics'][$lang_code],$_LANG_TEXT['idccheckinoutdetails'][$lang_code]),
				"login_log"=>array("$gate_name",$_LANG_TEXT['m_statistics'][$lang_code],$_LANG_TEXT['m_manage_adminloginlog'][$lang_code]),
				"work_log"=>array("$gate_name",$_LANG_TEXT['m_statistics'][$lang_code],$_LANG_TEXT['work_log'][$lang_code]),
				"system_log"=>array("$gate_name",$_LANG_TEXT['m_statistics'][$lang_code],$_LANG_TEXT['worklogtext'][$lang_code]),
				"app_update_log"=>array("$gate_name",$_LANG_TEXT['m_statistics'][$lang_code],$_LANG_TEXT['app_update_log_text'][$lang_code]),
				"visit_stat"=>array("$gate_name",$_LANG_TEXT['m_statistics'][$lang_code],$_LANG_TEXT['inoutstatus'][$lang_code]),
				"vcs_stat"=>array("$gate_name",$_LANG_TEXT['m_statistics'][$lang_code],$_LANG_TEXT['m_statistics_result'][$lang_code]),
				"rental_stat"=>array("$gate_name",$_LANG_TEXT['m_statistics'][$lang_code],$_LANG_TEXT['item_rental_status'][$lang_code]),
				"k_report"=>array("$gate_name",$_LANG_TEXT['m_statistics'][$lang_code],$_LANG_TEXT['m_report'][$lang_code])),
	
				
			),
		"MANAGE"=>array(
				"MENU_CODE"=>"M1000",
				"MENU_NAME"=>$_LANG_TEXT['m_manage'][$lang_code],
				"MENU_PATH"=> "/manage",
				"MENU_LINK"=>$_CODE_PAGE_LINK["M1000"],
				"PAGE"=>array(
					/*"tree_list"=>array("$gate_name",$_LANG_TEXT['m_manage'][$lang_code],trsLang("기관설정","institution_settings")), */
					"scan_center_list"=>array("$gate_name",$_LANG_TEXT['m_manage'][$lang_code],$_LANG_TEXT['m_manage_scan_center'][$lang_code]),
					"policy"=>array("$gate_name",$_LANG_TEXT['m_manage'][$lang_code],$_LANG_TEXT['m_manage_policy'][$lang_code]),
					"admin_list"=>array("$gate_name",$_LANG_TEXT['m_manage'][$lang_code],$_LANG_TEXT['m_manage_admin'][$lang_code]),
					"admin_auth_list"=>array("$gate_name",$_LANG_TEXT['m_manage'][$lang_code],$_LANG_TEXT['managepageaccessauth'][$lang_code]),
					"kabang_emp_list"=>array("$gate_name",$_LANG_TEXT['m_manage'][$lang_code],$_LANG_TEXT['staffinfo'][$lang_code]),
					"access_ip_config"=>array("$gate_name",$_LANG_TEXT['m_manage'][$lang_code],$_LANG_TEXT['m_manage_accessip'][$lang_code]),
					"code_list"=>array("$gate_name",$_LANG_TEXT['m_manage'][$lang_code],$_LANG_TEXT['m_manage_code'][$lang_code]),
					"usb_list"=>array("$gate_name",$_LANG_TEXT['m_manage'][$lang_code],$_LANG_TEXT['managesecurityusb'][$lang_code]),
					"document_list"=>array("$gate_name",$_LANG_TEXT['m_manage'][$lang_code],$_LANG_TEXT['managedocument'][$lang_code]),
					"file_signature"=>array("$gate_name",$_LANG_TEXT['m_manage'][$lang_code],trsLang('파일시그니처정보관리','mgtfilesignitureinfo')),
					"app_update"=>array("$gate_name",$_LANG_TEXT['m_manage'][$lang_code],$_LANG_TEXT['m_manage_appupdate'][$lang_code]),
					"agree_list"=>array("$gate_name",$_LANG_TEXT['m_manage'][$lang_code],$_LANG_TEXT['information_consent_manag'][$lang_code]),
					"custom_query"=>array("$gate_name",$_LANG_TEXT['m_manage'][$lang_code],$_LANG_TEXT['m_query_editor'][$lang_code])
				)
			)
	);
?>