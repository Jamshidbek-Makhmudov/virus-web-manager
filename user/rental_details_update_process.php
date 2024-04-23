<?php
$page_name = "access_control";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$proc = $_POST['proc']; 
$memo = $_POST['memo']; 

// 물품 대여정보 가져오기
$rent_list_seq = $_POST['rent_list_seq'];
$user_dept = $_POST['user_dept']; 
$user_belong = $_POST['user_belong']; 
$user_name = $_POST['user_name']; 
$user_name_en = $_POST['user_name_en']; 
$user_phone = $_POST['user_phone']; 
$item_name = $_POST['item_name']; 
$item_mgt_number = $_POST['item_mgt_number']; 
$rent_center_code = $_POST['rent_center_code']; 
$rent_purpose = $_POST['rent_purpose']; 
$rent_date = $_POST['rent_date']; 
$return_schedule_date = $_POST['return_schedule_date']; 
$user_type = $_POST['user_type']; 



//주차권 지급 가져오기
$ticket_list_seq = $_POST['ticket_list_seq'];
$user_name = $_POST['user_name']; 
$user_name_en = $_POST['user_name_en']; 
$user_belong = $_POST['user_belong']; 
$ticket_desc = $_POST['ticket_desc']; 
$car_number = $_POST['car_number']; 
$serve_time = $_POST['serve_time']; 

//외부인력 정보교육 가져오기
$train_seq = $_POST['train_seq']; 
$project_name = $_POST['project_name']; 
$user_company = $_POST['user_company'];
$user_name = $_POST['user_name']; 
$user_name_en = $_POST['user_name_en'];  
$manager_type = $_POST['manager_type'];  
$manager_name_en = $_POST['manager_name_en'];  
$manager_belong = $_POST['manager_belong'];  
$train_date = $_POST['train_date'];  
$out_time = $_POST['out_time'];

//출입정보 상세 가져오기
$v_user_type = $_POST['v_user_type']; 
$v_user_list_seq = $_POST['v_user_list_seq']; 
$v_user_name = $_POST['v_user_name']; 
$v_user_name_en = $_POST['v_user_name_en']; 
$v_user_belong = $_POST['v_user_belong']; 
$v_phone = $_POST['v_phone']; 
$in_center_name = $_POST['in_center_name']; 
$v_purpose = $_POST['v_purpose']; 
$label_value = $_POST['label_value']; 
$usb_return_schedule_date = $_POST['usb_return_schedule_date']; 
$elec_doc_number = $_POST['elec_doc_number']; 
$manager_dept = $_POST['manager_dept']; 
$manager_name = $_POST['manager_name']; 
$manager_name_en = $_POST['manager_name_en']; 
$in_time = $_POST['in_time']; 
$additional_cnt = $_POST['additional_cnt']; 

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,$proc);

$Model_User = new Model_User();

if ($rent_list_seq ) {


	// 물품 대여정보 업데이트하기
	if($user_type=="OUT"){	//외부직원
		$user_dept="";	
	}else {
		$user_company = COMPANY_NAME;
		$user_dept= $user_company;
	}

// $Model_User->SHOW_DEBUG_SQL = true;
	$args = array("memo" => $memo,"user_dept" => $user_dept,"user_belong" => $user_belong,"user_name" => $user_name,"user_name_en" => $user_name_en,"user_phone" => $user_phone,"item_name" => $item_name,"item_mgt_number" => $item_mgt_number,"rent_center_code" => $rent_center_code,"rent_purpose" => $rent_purpose,"return_schedule_date" => $return_schedule_date, "rent_date"=>$rent_date,"user_company"=>$user_company,"user_type"=>$user_type,
	 "rent_list_seq" => $rent_list_seq
);
	$result = $Model_User->updateRentListInfo($args);

	$data_value="rent_list_seq";

}else if ($ticket_list_seq) {


	//주차권 지급 업데이트하기
	if($user_type=="OUT"){	//외부직원
		$user_dept="";	
	}else {
		$user_company = COMPANY_NAME;
		$user_dept= $user_company;
	}

	$args = array("memo" => $memo,"user_name" => $user_name,"user_name_en" => $user_name_en,"user_belong" => $user_belong,"ticket_desc" => $ticket_desc,"car_number" => $car_number, "serve_time"=>$serve_time,"user_dept" => $user_dept,"user_company"=>$user_company,"user_type"=>$user_type,
	"ticket_list_seq" => $ticket_list_seq,"out_time"=>$out_time);
	// $Model_User->SHOW_DEBUG_SQL = true;
	$result = $Model_User->updateParkingListInfo($args);

	$data_value="ticket_list_seq";

}else if ($train_seq) {

	//외부인력 정보교육 업데이트하기

	if($manager_type=="EMP"){
		$manager_company=COMPANY_NAME;
		$manager_dept = $manager_company;
}else{
	$manager_dept ="";
}

	$args = array("memo" => $memo,"user_name" => $user_name,"user_name_en" => $user_name_en,"project_name" => $project_name,"user_company" => $user_company, "manager_type"=>$manager_type, "manager_name_en"=>$manager_name_en,"manager_belong"=>$manager_belong,"train_date"=>$train_date,"manager_dept"=>$manager_dept,"manager_company"=>$manager_company,
	"train_seq" => $train_seq);
	$result = $Model_User->updateTrainListInfo($args);

	$data_value="train_seq";

}else if ($v_user_list_seq) {

	//출입정보 상세 업데이트하기

	$args = array("memo" => $memo,"v_user_name" => $v_user_name,"v_user_name_en" => $v_user_name_en,"v_user_belong" => $v_user_belong,"v_phone" => $v_phone,"in_center_code" => $in_center_name,"v_purpose" => $v_purpose,"label_value" => $label_value,"elec_doc_number" => $elec_doc_number,"manager_dept"=>$manager_dept, "manager_name"=>$manager_name, "manager_name_en"=>$manager_name_en,"in_time"=>$in_time,"additional_cnt"=>$additional_cnt,"usb_return_schedule_date"=>$usb_return_schedule_date,
	"v_user_list_seq" => $v_user_list_seq,"v_user_type"=>$v_user_type);

	$result = $Model_User->updateVisitUser($args);
	$result = $Model_User->updateVisitUserList($args);
	$result = $Model_User->updateVisitUserListInfo($args);

	$data_value="v_user_list_seq";

}

if($result){
	printJson_OK('save_ok');
}else{
	printJson_ERROR('proc_error');
}
?>