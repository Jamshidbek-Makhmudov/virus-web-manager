<?php
$page_name = "result_list";
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


$page = $_REQUEST[page];			// 페이지
$v_wvcs_seq = $_REQUEST['v_wvcs_seq'];

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');


$numtext = $_LANG_TEXT["numtext"][$lang_code];
$filepathtext = $_LANG_TEXT["filepathtext"][$lang_code];
$filenametext = $_LANG_TEXT["filenametext"][$lang_code];
$filesizetext = $_LANG_TEXT["filesizetext"][$lang_code];
$confirmed_person = $_LANG_TEXT["confirmed_person"][$lang_code];
$filesignature = $_LANG_TEXT["filesignature"][$lang_code];
$m_result = $_LANG_TEXT["m_result"][$lang_code];
$carry_in_status = $_LANG_TEXT["carry_in_status"][$lang_code];
$server_transfer_status = $_LANG_TEXT["server_transfer_status"][$lang_code];
$deleteyntext = $_LANG_TEXT["deleteyntext"][$lang_code];

//order
		$order_sql = " ORDER BY v1.v_wvcs_seq DESC"; 
		$search_sql = " and v1.v_wvcs_seq = '{$v_wvcs_seq}' ";

if($orderby != "") {
				$order_sql = " ORDER BY $orderby";
	} else {
					$order_sql = " ORDER BY f1.v_wvcs_file_seq ";
				}	

				$start = 0;
				$rowcount = $_POST["record_count"];
				$lastPageNo = ceil($rowcount / RECORD_LIMIT_PER_FILE);
				$no = $rowcount - $start;
		
$j=1;
for ($i = $start; $i < $lastPageNo; $i ++) {

	
	$end = RECORD_LIMIT_PER_FILE*($i+1);

	$data = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start,"excel_download_flag"=>"1");			
	$VCSScanList= new Model_result();	
	$result = $VCSScanList->getVCSFileImportList($data);

	if ($result) {
		$rows = [];
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$rows[] = $row;
		}
	}

	
	$splitHTML[$i] = '<table id="tblList" class="list" style="	border-collapse: collapse;
border:1px solid black;">
     <tr>
                <th style="width:100px;background-color: #D4D0C8; border:0.5px solid black; ">'.$numtext.'</th>

                <th  style="width:300px;background-color: #D4D0C8; border:0.5px solid black;">'.$filepathtext.'</th>
                <th  style="width:100px;background-color: #D4D0C8; border:0.5px solid black; ">'.$filenametext.'</th>
                <th  style="width:100px;background-color: #D4D0C8; border:0.5px solid black;">'.$filesizetext.'</th>
                <th  style="width:100px;background-color: #D4D0C8; border:0.5px solid black;">'.$confirmed_person.'</th>
                <th  style="width:200px;background-color: #D4D0C8; border:0.5px solid black;">'.$filesignature.'</th>
                <th  style="width:100px;background-color: #D4D0C8; border:0.5px solid black;">'.$m_result.'</th>
                <th  style="width:100px;background-color: #D4D0C8; border:0.5px solid black;">'.$server_transfer_status.'</th>
                <th  style="width:250px;background-color: #D4D0C8; border:0.5px solid black;">md5</th>
                <th  style="width:450px;background-color: #D4D0C8; border:0.5px solid black;">sha256</th>
                <th  style="width:100px;background-color: #D4D0C8; border:0.5px solid black;">'.$deleteyntext.'</th>
            </tr>';
	foreach ($rows as $row) {

		$v_user_name = aes_256_dec($row['v_user_name']);
		$wvcs_dt = $row['wvcs_dt'];
		$formatted_date = $wvcs_dt->format('Y-m-d H:i');

		$file_path = $row['file_path'];
		$file_name_org = $row['file_name_org'];
		$file_size = $row['file_size'];
		$file_ext = $row['file_ext'];

		$file_signature = $row['file_signature'];
		$file_scan_result = $row['file_scan_result'];
		$md5 = $row['md5'];
		$sha256 = $row['sha256'];

		if ($file_scan_result == "BAD_EXT") {
			$m_result =$_LANG_TEXT['suspectforgerytext'][$lang_code];
		} else if ($file_scan_result == "VIRUS") {
			$m_result = $_LANG_TEXT['virustext'][$lang_code];
		} else {
			$m_result =  $_LANG_TEXT['cleantext'][$lang_code] ;
		}

		//seq id
		$v_wvcs_file_seq = $row['v_wvcs_file_seq'];
						//반입여부
						$v_wvcs_file_in_seq  = $row['v_wvcs_file_in_seq'];
						if($v_wvcs_file_in_seq>0){
					    $bring_in = "<font >".$_LANG_TEXT['intext'][$lang_code]."<font>";
				    }else{
				     	$bring_in = $_LANG_TEXT['nointext'][$lang_code];
				    }
						//서버전송여부
						$file_send_status  = $row['file_send_status'];
						$file_send_date = $row['file_send_date'];
						if ($file_send_status == "1" && $file_send_date != "") {
					    $send_server = "<font >".$_LANG_TEXT['send_server'][$lang_code]."<font>";
				    }else{
				     	$send_server = $_LANG_TEXT['notsend_server'][$lang_code];
				    }
						$file_id  = $row['file_id'];
						//삭제여부
						$file_delete_flag  = $row['file_delete_flag'];
						if($file_delete_flag==1){
					    $delete_flag = "<font >o<font>";
				    }else{
				     	$delete_flag = "X";

				    }

		$splitHTML[$i] .= '<tr>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $no . '</td>
              
             
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  " >' . $file_path . '</td>
								
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $file_name_org . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . formatBytes($file_size ). 'KB</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $file_ext . '</td>
								
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $file_signature . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $m_result . '</td>
								

                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $send_server . '</td>
				
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $md5 . '</td> 
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $sha256 . '</td>
                <td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $delete_flag . '</td>																							
            </tr>';

						$j++;
						$no--;


	}


  $splitHTML[$i] .= '</table>';
	$start = $start + RECORD_LIMIT_PER_FILE;

}

print json_encode($splitHTML);

if($result) sqlsrv_free_stmt($result);  
if($wvcs_dbcon) sqlsrv_close($wvcs_dbcon);
exit;
?>


