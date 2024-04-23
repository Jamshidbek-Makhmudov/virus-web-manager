<?
/*
* custom view 설정
* policy_in_available_date_hide : 반입가능기한 설정값이 '0' 이면 반입가능기한 정보는 보이지 않도록 한다
* policy_checkin_kiosk_in_type : 저장매체 반입형태가 파일만 반입하는 경우 반입일/반출일 정보 안보이게 처리
*/
$policy = getPolicy('checkin_pc_term,checkin_kiosk_term,checkin_kiosk_in_type');
$policy_in_available_date_hide = ($policy['checkin_pc_term']=="0" && $policy['checkin_kiosk_term']=="0");
$policy_checkin_kiosk_in_type = ($policy['checkin_kiosk_in_type']=="" ? "DEVICE" : $policy['checkin_kiosk_in_type']);

if($policy_in_available_date_hide==true){
	$cls_cfg_in_available_dt = "display:none !important;";
	$xls_cfg_in_available_dt = "ss:Hidden='1' ss:AutoFitWidth='0' ss:Width='0' ";
}else{
	$xls_cfg_in_available_dt = "ss:Width='120'  ";
}

//저장매체 반입형태가 파일만 반입하는 경우 반입일/반출일 정보 안보이게 처리
if($policy_checkin_kiosk_in_type=="FILE"){
	$cls_cfg_inout_info = "display:none !important;";
	$xls_cfg_inout_info = "ss:Hidden='1' ss:AutoFitWidth='0' ss:Width='0' ";
}else{
	$cls_cfg_inout_info ="";
	$xls_cfg_inout_info = "ss:Width='150'  ";
}

/*방문자 이름이 'GUEST' 인 경우  방문자를 식별할 수 있도록 이름 대신 휴대폰이나 이메일정보를 보여주도록 함.
* phone - 휴대폰 정보 표시
* email - 이메일정보 표시
* name - 이름 표시
*/

//점검구분
if(COMPANY_CODE=="600"){
	
	$cls_cfg_check_type = "display:none !important;";	//점검구분
	$xls_cfg_check_type = "ss:Hidden='1' ss:AutoFitWidth='0' ss:Width='0' ";
	$_cfg_user_identity_name = "name";		//방문자 식별값

}else{

	$cls_cfg_check_type ="";
	$xls_cfg_check_type = "ss:Width='100'  ";
	$vw_cfg_barcode = "show";
	$_cfg_user_identity_name = "phone";	//방문자 식별값
}

$custom_css = "
<style>
	.cls_cfg_in_available_dt{".$cls_cfg_in_available_dt."}
	.cls_cfg_inout_info{".$cls_cfg_inout_info."}
	.cls_cfg_check_type{".$cls_cfg_check_type."}
</style>\r\n";
?>