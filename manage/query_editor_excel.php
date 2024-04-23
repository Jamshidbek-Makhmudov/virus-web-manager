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

$searchopt = $_REQUEST[searchopt];	// 검색옵션
$searchkey = $_REQUEST[searchkey];	// 검색어
$orderby = $_REQUEST[orderby];		// 정렬순서
$page = $_REQUEST[page];			// 페이지
$paging = $_REQUEST[paging];
$start_date = $_REQUEST[start_date];	
$end_date = $_REQUEST[end_date];

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');

//lang codes
$numtext = $_LANG_TEXT["numtext"][$lang_code];
$query_content = $_LANG_TEXT["query_content"][$lang_code];
$query_title = $_LANG_TEXT["query_title"][$lang_code]; 
$registertext = $_LANG_TEXT["registertext"][$lang_code];
$createdatetext = $_LANG_TEXT["createdatetext"][$lang_code];


$Model_Utils= new Model_Utils();	
 //$Model_Utils->SHOW_DEBUG_SQL = true;	
			// seachpopt
			//검색항목
						 $search_sql = "";
				if ($start_date != "" && $end_date != "") {
				$search_sql .= " and create_date between '".str_replace('-', '', $start_date)."000000' AND '".str_replace('-', '', $end_date)."235959' ";
			}

			if ($searchkey != "" && $searchopt != "") {

       if ($searchopt == "query_content") {

					$search_sql .= " and query_content like N'%$searchkey%' ";
				} 
			

			}

//order
				if($orderby != "") {
					$order_sql = " ORDER BY $orderby";
				} else {
					$order_sql = " ORDER BY t1.custom_query_seq DESC ";
		
				}	

				$start = 0;
				$rowcount = $_POST["record_count"];
				$lastPageNo = ceil($rowcount / RECORD_LIMIT_PER_FILE);
		
$j=1;

for ($i = $start; $i < $lastPageNo; $i ++) {

	
	$end = RECORD_LIMIT_PER_FILE*($i+1);

	$data = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start,"excel_download_flag"=>"1");			
	
	$result = $Model_Utils->getQueryListInfo($data);

	if ($result) {
		$rows = [];
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$rows[] = $row;
		}
	}

	$splitHTML[$i] = '<table id="tblList" class="list" style="	border-collapse: collapse;
border:1px solid black;
	width: 100%;">
     <tr>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black; ">'.$numtext.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$query_title.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black; ">'.$query_content.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$registertext.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$createdatetext.'</th>

            </tr>';
	foreach ($rows as $row) {

						$custom_query_seq = $row['custom_query_seq'];

						$query_title = $row['query_title'];
						$query_content = $row['query_content'];					
						$create_emp_seq = $row['create_emp_seq'];
						$create_date = $row['create_date'];

						$emp_no = $row['emp_no'];
						$emp_name = aes_256_dec($row['emp_name']);

						 $date_value = date('Y-m-d H:i', strtotime($create_date));


		$splitHTML[$i] .= '<tr>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $j . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $query_title . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $query_content . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  " >' . $emp_name . '</td>							
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $date_value . '</td>
	     																						
            </tr>';

						$j++;

	}


  $splitHTML[$i] .= '</table>';
	$start = $start + RECORD_LIMIT_PER_FILE;

}

print json_encode($splitHTML);

if($result) sqlsrv_free_stmt($result);  
if($wvcs_dbcon) sqlsrv_close($wvcs_dbcon);
exit;
?>


