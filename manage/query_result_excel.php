<?php
$page_name = "custom_query";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$_query_enc = $_REQUEST[query_enc];	// 검색옵션
$query = trim(htmlentities(base64_decode($_query_enc),ENT_NOQUOTES));

$check_query = strtr($query,array("\r\n"=>'',"\r"=>'',"\n"=>''));
$check_query = strtolower($check_query);


if(substr($check_query,0,6) !="select" ){
	echo "<div style='text-align:center;margin-top:30px;'>Warning : ".trsLang('데이터조회만허용됩니다.','allowonlyselecttext').".</div>";
	exit;
}

if(strpos($check_query,"*")!==false){
	echo "<div style='text-align:center;margin-top:30px;'>Warning : '*' 문자열은 사용할 수 없습니다.</div>";
	exit;
}

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');


$Model_utils= new Model_Utils();
//  $Model_Utils->SHOW_DEBUG_SQL = true;	

				$start = 0;
				$rowcount = $_POST["record_count"];
				$lastPageNo = ceil($rowcount / RECORD_LIMIT_PER_FILE);



		
$j=1;

for ($i = $start; $i < $lastPageNo; $i ++) {

	$args = array("query"=>$query);

$result =  $Model_utils->getQueryEditorsList($args);
$total =  sqlsrv_num_rows($result);


if ($result) {
	
	$headerPrinted = false;
	$no =$total;
			$splitHTML[$i] = "<table class='list' id='tblList' cellpadding='2' style='margin:0px;auto; white-space: nowrap; ' >";
			
			while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
				
				if (!$headerPrinted) {
					$splitHTML[$i] .= '<thead><tr>';
								$splitHTML[$i] .= "<th style='width:60px;'>".trsLang('번호','numtext')."</th>";
					foreach ($row as $columnName => $value) {
						$splitHTML[$i] .= '<th style="text-align:left;padding:0 5px; width:200px;">' . strtolower($columnName) . '</th>';
					}
					$splitHTML[$i] .= '</tr></thead>';
					$headerPrinted = true;
				}
				
				$splitHTML[$i] .= '<tbody>';
				$splitHTML[$i] .= '<tr>';
				$splitHTML[$i] .= "<td style='text-align:center'>" . $no . "</td>";
				foreach ($row as $value) {
					if ($value instanceof DateTime) {
						$splitHTML[$i] .= '<td>' . $value->format('Y-m-d H:i:s') . '</td>';
					} else {
						$splitHTML[$i] .= '<td>' . $value . '</td>';
					}
				}
				$splitHTML[$i] .= '</tr>';
				$no--;
			}
			$splitHTML[$i] .= '</tbody>';
			$splitHTML[$i] .= '</table>';


				if ($result) sqlsrv_free_stmt($result);
			sqlsrv_close($wvcs_dbcon);	
	}else{
			$sql_errors = $Model_utils->SQL_ERRORS[0];
			$splitHTML[$i] = "<div style='text-align:center;margin-top:30px;'> SQL Errors : ".$sql_errors[message]."</div>";
			exit;
		}// if main
	$start = $start + RECORD_LIMIT_PER_FILE;
} //for loop main

print json_encode($splitHTML);

if($result) sqlsrv_free_stmt($result);  
if($wvcs_dbcon) sqlsrv_close($wvcs_dbcon);
exit;
?>


