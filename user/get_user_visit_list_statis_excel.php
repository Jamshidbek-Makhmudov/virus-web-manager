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

$manager_name = $_REQUEST[manager_name];
$manager_name_en = $_REQUEST[manager_name_en];
$orderby = $_REQUEST[orderby];		

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');

$Model_User = new Model_User();

$search_sql = " and (v2.manager_name = '".aes_256_enc($manager_name)."'  and v2.manager_name_en ='{$manager_name_en}' ) ";

if ($orderby != "") {
	$order_sql = " ORDER BY $orderby";
} else {
	$order_sql = " ORDER BY v2.v_user_list_seq DESC ";
}

$start = 0;
$rowcount = $_POST["record_count"];
$lastPageNo = ceil($rowcount / RECORD_LIMIT_PER_FILE);
		
for ($i = $start; $i < $lastPageNo; $i ++) {

	$end = RECORD_LIMIT_PER_FILE*($i+1);

	$args = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start,"excel_download_flag"=>"1");		
	$Model_User->SHOW_DEBUG_SQL = false;
	$result = $Model_User->getUserVisitStatisList($args);

	$style['th'] ='background-color: #D4D0C8; border:0.5px solid black;';
	$style['td'] ='text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black; ';

	$splitHTML[$i] = '<table id="tblList" style="border-collapse: collapse;border:1px solid black;width: 100%;">
		 <tr>
			<th style="width:50px; '.$style['th'].'">'.$_LANG_TEXT['numtext'][$lang_code].'</th>
			<th style="width:100px; '.$style['th'].'">'.$_LANG_TEXT['belongdivtext'][$lang_code].'</th>
			<th style="width:100px; '.$style['th'].'">'.$_LANG_TEXT['visitor_name'][$lang_code].'</th>
			<th style="width:100px; '.$style['th'].'">'.$_LANG_TEXT['visitor_name_en'][$lang_code].'</th>
			<th style="width:200px; '.$style['th'].'">'.$_LANG_TEXT['belongtext'][$lang_code].'</th>
			<th style="width:100px; '.$style['th'].'">'.$_LANG_TEXT['contactphonetext'][$lang_code].'</th>
			<th style="width:150px; '.$style['th'].'">'.$_LANG_TEXT['totalNumberVisit'][$lang_code].'</th>
			<th style="width:150px; '.$style['th'].'">'.$_LANG_TEXT['fileimporttimes'][$lang_code].'</th>
			<th style="width:150px; '.$style['th'].'">'.$_LANG_TEXT['dateFirstVisit'][$lang_code].'</th>
			<th style="width:150px; '.$style['th'].'">'.$_LANG_TEXT['finalVisit'][$lang_code].'</th>
		</tr>';

	if ($result) {
		$no = 1;
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

			$v_user_seq = $row['v_user_seq'];
			$v_user_name = aes_256_dec($row['v_user_name']);
			$v_user_name_en = $row['v_user_name_en'];
			$v_user_belong = $row['v_user_belong'];
			$in_cnt = $row['in_cnt'];
			$file_import_cnt = $row['file_import_cnt'];
			$first_in_time = setDateFormat($row['first_in_time'],'Y-m-d H:i');
			$last_in_time = setDateFormat($row['last_in_time'],'Y-m-d H:i');
			
			$v_user_type = $row['v_user_type'];
			$str_v_user_type = $_CODE_V_USER_TYPE[$v_user_type];

			if($_encryption_kind=="1"){
				$phone_no = $row['v_phone_decript'];
				
			}else if($_encryption_kind=="2"){
			
				if($row['v_phone'] != ""){
					$phone_no = aes_256_dec($row['v_phone']);
				}
			}
			
			$splitHTML[$i] .= '<tr>
                <td   style="'.$style['td'].'">' . $no . '</td>
                <td   style="'.$style['td'].'">' . $str_v_user_type . '</td>
                <td   style="'.$style['td'].'">' . $v_user_name . '</td>
                <td   style="'.$style['td'].'">' . $v_user_name_en . '</td>
                <td   style="'.$style['td'].'">' . $v_user_belong . '</td>
                <td   style="'.$style['td'].'">' . $phone_no . '</td>
                <td   style="'.$style['td'].'">' . $in_cnt . '</td>							
                <td   style="'.$style['td'].'">' . $file_import_cnt . '</td>
                <td   style="'.$style['td'].'">' . $first_in_time . '</td>
                <td   style="'.$style['td'].'">' . $last_in_time . '</td>																			
            </tr>';

			$no++;
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
