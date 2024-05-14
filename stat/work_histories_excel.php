<?php
$page_name = "work_histories";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI']) - 1);
$_apos = stripos($_REQUEST_URI, "/");
if ($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$searchopt = $_REQUEST[searchopt];	// 검색옵션
$searchkey = $_REQUEST[searchkey];	// 검색어
$orderby = $_REQUEST[orderby];		// 정렬순서
$page = $_REQUEST[page];				// 페이지
$start_date = $_REQUEST[start_date];
$end_date = $_REQUEST[end_date];
$scan_center_code = $_REQUEST[scan_center_code];
$menu_code = $_REQUEST[menu_code];
$act_type = $_REQUEST[act_type];

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name, 'DOWNLOAD');

//lang codes
$numtext = $_LANG_TEXT["numtext"][$lang_code];
$nametext = $_LANG_TEXT["nametext"][$lang_code];
$idtext = $_LANG_TEXT["idtext"][$lang_code];
$work_detail = $_LANG_TEXT["work_detail"][$lang_code];
$ipaddresstext = $_LANG_TEXT["ipaddresstext"][$lang_code];
$work_date = $_LANG_TEXT["work_date"][$lang_code];
$work_classification = $_LANG_TEXT["work_classification"][$lang_code];

$Model_Stat = new Model_Stat();
$Model_Stat->SHOW_DEBUG_SQL = false;
$search_sql = "";

if ($start_date != "" && $end_date != "") {
	$start_datetime = str_replace('-', '', $start_date) . '000000';
	$end_datetime   = str_replace('-', '', $end_date) . '235959';

	$search_sql .= "AND a1.create_dt BETWEEN '{$start_datetime}' AND '{$end_datetime}' ";
}

if ($scan_center_code !="") { 
	$search_sql .= "AND a1.scan_center_code = '{$scan_center_code}' ";
}

if ($menu_code !="") { 
	$search_sql .= "AND a1.menu_code = '{$menu_code}' ";
}

if ($work_type !="") { 
	$search_sql .= "AND a1.work_type = '{$work_type}' ";
}

if ((!empty($searchopt)) && (!empty($searchkey))) {
	if ($searchopt == "id") {
		$search_sql .= "AND a1.user_emp_no LIKE '%{$searchkey}%' ";

	} else if ($searchopt == "name") {
		$searchkey = aes_256_enc($searchkey);
		$search_sql .= "AND a1.user_emp_name LIKE '{$searchkey}' ";

	} else if ($searchopt == "ip") {
		$search_sql .= "AND a1.create_ip LIKE N'%{$searchkey}%' ";

	} else if ($searchopt == "title") {
		$search_sql .= "AND a1.work_title LIKE N'%{$searchkey}%' ";
	}
}


if ($orderby != "") {
	$order_sql = " ORDER BY $orderby";
} else {
	$order_sql = " ORDER BY a1.work_seq DESC ";
}

$start = 0;
$rowcount = $_POST["record_count"];
$lastPageNo = ceil($rowcount / RECORD_LIMIT_PER_FILE);

$th_style = "background-color: #D4D0C8; border:0.5px solid black; white-space: nowrap;";
$td_style = "text-align: center; vertical-align: middle;min-height:30px; border:0.5px solid black; white-space: nowrap;";

$j = 1;
for ($i = $start; $i < $lastPageNo; $i++) {
	$end  = RECORD_LIMIT_PER_FILE * ($i + 1);
	$data = array("order_sql" => $order_sql, "search_sql" => $search_sql, "end" => $end, "start" => $start, "excel_download_flag" => "1");
	$rows = $Model_Stat->getAdminWorkHistories($data);

	$splitHTML[$i] = '
		<table id="tblList" class="list" style="border-collapse: collapse; border:1px solid black; width: 100%;">
     		<tr>
                <th style="width: 160px;'.$th_style.'">' . $numtext . '</th>
                <th style="width: 240px;'.$th_style.'">' . $work_date . '</th>
                <th style="width: 200px;'.$th_style.'">' . $nametext . '</th>
                <th style="width: 200px;'.$th_style.'">' . $idtext . '</th>
                <th style="width: 200px;'.$th_style.'">' . $work_classification . '</th>
                <th style="width: 300px;'.$th_style.'">' . trsLang('검사장','scancentertext') . '</th>
                <th style="width: 200px;'.$th_style.'">' . trsLang('메뉴','menu_text') . '</th>
                <th style="width: 500px;'.$th_style.'">' . $work_detail . '</th>
                <th style="width: 240px;'.$th_style.'">' . $ipaddresstext . '</th>
            </tr>
	';
	
	foreach ($rows as $row) {
		$user_emp_no = $row['user_emp_no'];
		$work_title = $row['work_title'];
		$create_ip = $row['create_ip'];
		$work_type = $row['work_type'];
		$scan_center_name = $row['scan_center_name'];
		$menu_name = $row['menu_name'];
		$user_emp_name = aes_256_dec($row['user_emp_name']);
		$create_dt = setDateFormat($row['create_dt'], "Y-m-d H:i");

		$splitHTML[$i] .= '
			<tr>
                <td style="'.$td_style.'">' . $j . '</td>
                <td style="'.$td_style.'">' . $create_dt . '</td>
                <td style="'.$td_style.'">' . $user_emp_name . '</td>
                <td style="'.$td_style.'">' . $user_emp_no . '</td>
                <td style="'.$td_style.'">' . $work_type . '</td>
                <td style="'.$td_style.'">' . $scan_center_name . '</td>
                <td style="'.$td_style.'">' . $menu_name . '</td>
                <td style="'.$td_style.'">' . $work_title . '</td>
                <td style="'.$td_style.'">' . $create_ip . '</td>
            </tr>
		';
		$j++;
	}


	$splitHTML[$i] .= '</table>';
	$start = $start + RECORD_LIMIT_PER_FILE;

}

print json_encode($splitHTML);

if ($result)
	sqlsrv_free_stmt($result);
if ($wvcs_dbcon)
	sqlsrv_close($wvcs_dbcon);
exit;
?>