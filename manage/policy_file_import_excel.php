<?php
$page_name = "policy";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$target = $_REQUEST[target];
$start_date = $_REQUEST[start_date];	
$end_date = $_REQUEST[end_date];
$adapt_yn = $_REQUEST[adapt_yn];
$searchopt = $_REQUEST[searchopt];		// 검색옵션
$searchkey = $_REQUEST[searchkey];		// 검색어
$searchopt1 = $_REQUEST[searchopt1];	// 검색옵션
$searchandor1 = $_REQUEST[searchandor1];
$searchkey1 = $_REQUEST[searchkey1];	// 검색어
$searchopt2 = $_REQUEST[searchopt2];	// 검색옵션
$searchandor2 = $_REQUEST[searchandor2];
$searchkey2 = $_REQUEST[searchkey2];	// 검색어
$searchopt3 = $_REQUEST[searchopt3];	// 검색옵션
$searchandor3 = $_REQUEST[searchandor3];
$searchkey3 = $_REQUEST[searchkey3];	// 검색어
$orderby = $_REQUEST[orderby];				// 정렬순서

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');

if($start_date==""){
	$start_date =$start_date = date("Y-m-d", strtotime(date("Y-m-d") . " -1 month"));
}
if($end_date==""){
	$end_date = date("Y-m-d");
}

$Model_manage = new Model_manage();

$search_sql = "";
if($target != ""){
	$search_sql .= " and t1.target='{$target}' ";
}


if($adapt_yn =="Y"){
	$search_sql .= " and t1.end_date > '".date('YmdHis')."' ";
}

$start_date = preg_replace("/[^A-Za-z0-9]/", "",$start_date)."000000";
$end_date = preg_replace("/[^A-Za-z0-9]/", "",$end_date)."235959";

if($start_date != "" && $end_date != ""){
	$search_sql .= " and t1.start_date <= '{$end_date}' and t1.end_date >= '{$start_date}' ";
}

$searchkey_sql= array(
	"policy_name" => " t1.policy_name like N'{?}%' "
	,"target" => " t1.target_name like N'{?}%' "
	,"apply_number"=> "t3.refer_apply_seq = '{?}' "
	,"file_hash" => " exists (Select 1 
				From tb_policy_file_in_list 
				Where t1.policy_file_in_seq = policy_file_in_seq
					and file_hash = '{?}' ) "
);		

if($searchopt != "" && $searchkey != ""){
	$search_sql .= " and ".str_replace('{?}', $searchkey, $searchkey_sql[$searchopt]);
}

if($searchopt1 != "" && $searchkey1 != ""){
	$search_sql .= " {$searchandor1} ".str_replace('{?}', $searchkey1, $searchkey_sql[$searchopt1]);
}

if($searchopt2 != "" && $searchkey2 != ""){
	$search_sql .= " {$searchandor2} ".str_replace('{?}', $searchkey2, $searchkey_sql[$searchopt2]);
}

if($searchopt3 != "" && $searchkey3 != ""){
	$search_sql .= " {$searchandor3} ".str_replace('{?}', $searchkey3, $searchkey_sql[$searchopt3]);
}

if($orderby != "") {
	$order_sql = " ORDER BY $orderby";
} else {
	$order_sql = " ORDER BY t1.policy_file_in_seq DESC "; 
}	

$args = array("search_sql"=>$search_sql);	
$rowcount = $Model_manage->getFileInPolicyListCount($args);
$lastPageNo = ceil($rowcount / RECORD_LIMIT_PER_FILE);			

$j=1;
$start = 0;
for ($i = $start; $i < $lastPageNo; $i ++) {

	
	$end = RECORD_LIMIT_PER_FILE*($i+1);

	$data = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start,"excel_download_flag"=>"1");		
	$Model_manage->SHOW_DEBUG_SQL = false;
	$result = $Model_manage->getFileInPolicyList($data);
	
	//table header
	$splitHTML[$i] = '<table id="tblList" class="list" style="	border-collapse: collapse; border:1px solid black;width: 100%;">
		<tr>
			<th  style="width:100px; background-color: #D4D0C8; border:0.5px solid black; ">'.trsLang('번호','numtext').'</th>
			<th  style="width:300px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('정책명','policyname').'</th>
			<th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('시작일','begindate').'</th>
			<th  style="width:150px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('종료일','enddate').'</th>
			<th  style="width:100px; background-color: #D4D0C8; border:0.5px solid black; ">'.trsLang('대상구분','targetdiv').'</th>
			<th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('예외대상','exceptiontarget').'</th>
			<th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('신청번호','applynumbertext').'</th>
			<th  style="width:300px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('예외적용파일','exceptionfile').'</th>
			<th  style="width:100px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('등록자','registertext').'</th>
			<th  style="width:100px; background-color: #D4D0C8; border:0.5px solid black;">'.trsLang('등록일자','registdatetext').'</th>
		</tr>';

	if ($result) {
		$rows = [];
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			
			$policy_name = $row['policy_name'];
			$start_date = setDateFormat($row['start_date'],'Y-m-d H:i:s');
			$end_date = setDateFormat($row['end_date'],'Y-m-d H:i:s');
			$target = $row['target'];
			$str_target = $_CODE_FILE_EXCEPTION_TARGET[$target];
			$target_name = aes_256_dec($row['target_name']);
			$refer_apply_seq = $row['refer_apply_seq'];
			
			$file_div = $row['file_div'];
			$file_hash = $row['file_hash'];
			if($file_div=="ALL") $file_hash = trsLang("전체","alltext");

			
			$emp_name = aes_256_dec($row['emp_name']);
			$refer = $row['refer'];
			if($refer=="API") $emp_name = $refer;

			$create_date = setDateFormat($row['create_date']);

			$splitHTML[$i] .= '<tr>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $j . '</td>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $policy_name . '</td>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $start_date . '</td>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  " >' . $end_date . '</td>							
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $str_target . '</td>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $target_name . '</td>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $refer_apply_seq . '</td>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $file_hash . '</td>	
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $emp_name . '</td>
                <td  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $create_date . '</td>			
     																						
            </tr>';

			$j++;
		}
	}

	$splitHTML[$i] .= '</table>';
	$start = $start + RECORD_LIMIT_PER_FILE;

}

print json_encode($splitHTML);

if($result) sqlsrv_free_stmt($result);  
if($wvcs_dbcon) sqlsrv_close($wvcs_dbcon);
exit;
?>


