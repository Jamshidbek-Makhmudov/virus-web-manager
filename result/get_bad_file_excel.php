<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$page = $_REQUEST[page];			
// 페이지
$v_wvcs_seq = $_REQUEST['v_wvcs_seq'];

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');

$numtext = $_LANG_TEXT["numtext"][$lang_code];
$filepathtext = $_LANG_TEXT["filepathtext"][$lang_code];
$filenametext = $_LANG_TEXT["filenametext"][$lang_code];
$filesizetext = $_LANG_TEXT["filesizetext"][$lang_code];
$confirmed_person = $_LANG_TEXT["confirmed_person"][$lang_code];
$filesignature = $_LANG_TEXT["filesignature"][$lang_code];
$file_id = $_LANG_TEXT["fileidnntext"][$lang_code];
$md5 = $_LANG_TEXT["Md5"][$lang_code];

				$start = 0;
				$rowcount = $_POST["record_count"];
				$lastPageNo = ceil($rowcount / RECORD_LIMIT_PER_FILE);
				$no = $rowcount - $start;

$j=1;
for ($i = $start; $i < $lastPageNo; $i ++) {

	
	$end = RECORD_LIMIT_PER_FILE*($i+1);

	$data = array("v_wvcs_seq"=>$v_wvcs_seq,"excel_download_flag"=>"1");			
	$VCSScanList= new Model_result();	
	$result = $VCSScanList->getUserVCSBadFileList($data);

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
                <th width="40%" style="background-color: #D4D0C8; border:0.5px solid black; ">'.$numtext.'</th>

                <th width="40%" style="background-color: #D4D0C8; border:0.5px solid black;">'.$filepathtext.'</th>
                <th width="40%" style="background-color: #D4D0C8; border:0.5px solid black; ">'.$filenametext.'</th>
                <th width="40%" style="background-color: #D4D0C8; border:0.5px solid black;">'.$filesizetext.'</th>
               
                <th width="40%" style="background-color: #D4D0C8; border:0.5px solid black;">'.$filesignature.'</th>
                <th width="40%" style="background-color: #D4D0C8; border:0.5px solid black;">'.$file_id.'</th>
                <th width="40%" style="background-color: #D4D0C8; border:0.5px solid black;">'.$md5.'</th>

            </tr>';
	foreach ($rows as $row) {

						$v_wvcs_file_seq = $row['v_wvcs_file_seq'];
						$file_path = $row['file_path'];
						$file_name_org = $row['file_name_org'];
						$file_size = getSizeCheck($row['file_size']);
						$file_signature = $row['file_signature'];
						$file_id  = $row['file_id'];
						$md5  = $row['md5'];

		$splitHTML[$i] .= '<tr>
                <td width="40%"  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $no . '</td>                    
                <td width="40%"  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  " >' . $file_path . '</td>							
                <td width="40%"  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $file_name_org . '</td>
                <td width="40%"  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $file_size . '</td>						
                <td width="40%"  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $file_signature . '</td>
                <td width="40%"  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $file_id . '</td>
                <td width="40%"  style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $md5 . '</td>
																						
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


