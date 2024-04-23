<?php
$page_name = "external_training";

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

$searchandor1 = $_REQUEST[searchandor1];
$searchandor2 = $_REQUEST[searchandor2];
$searchandor3 = $_REQUEST[searchandor3];
$searchoptm1 = $_REQUEST[searchoptm1];
$searchoptm2 = $_REQUEST[searchoptm2];
$searchoptm3 = $_REQUEST[searchoptm3];
$searchkeym1 = $_REQUEST[searchkeym1];
$searchkeym2 = $_REQUEST[searchkeym2];
$searchkeym3 = $_REQUEST[searchkeym3];

$orderby = $_REQUEST[orderby];		// 정렬순서
$page = $_REQUEST[page];			// 페이지
$paging = $_REQUEST[paging];
$start_date = $_REQUEST[start_date];	
$end_date = $_REQUEST[end_date];

$searchopt4 = $_REQUEST[searchopt4];
if($searchopt4=="") $searchopt4="create_date";

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');

//lang codes
$numtext = $_LANG_TEXT["numtext"][$lang_code];
$projectname = $_LANG_TEXT["projectname"][$lang_code];
$companyname_text = $_LANG_TEXT["companyname_text"][$lang_code]; 
$name_or_id_eng = $_LANG_TEXT["name_or_id_eng"][$lang_code];
$training_date = $_LANG_TEXT["training_date"][$lang_code];
$training_staf_name_eng = $_LANG_TEXT["training_staf_name_eng"][$lang_code];
$education_manager_affiliation = $_LANG_TEXT["education_manager_affiliation"][$lang_code];
$memotext = $_LANG_TEXT["memotext"][$lang_code];
$user_name_kr = $_LANG_TEXT["user_name_kr"][$lang_code];
$user_en_id = $_LANG_TEXT["user_en_id"][$lang_code];
$classify_edu = $_LANG_TEXT["classify_edu"][$lang_code];
$manager_en_id = $_LANG_TEXT["manager_en_id"][$lang_code];


	$Model_User= new Model_User();	
			  // $Model_User->SHOW_DEBUG_SQL = true;	
// seachpopt
			//검색항목
			$search_sql = "";

			$str_start_date =str_replace('-', '', $start_date);
			$str_end_date =str_replace('-', '', $end_date);
			
			if ($start_date != "" && $end_date != "" ) {

					if ($searchopt4 == "create_date") {
						$search_sql .= " and create_date between '{$str_start_date}000000' AND '{$str_end_date}999999' ";
					} else if ($searchopt4 == "train_date") {
						$search_sql .= " and train_date between '{$str_start_date}' AND '{$str_end_date}' ";
						
						
					}
			}

			// 키워드검색
			$searchandor0 = " and ( ";
			$searchoptm0 = $searchopt;
			$searchkeym0 = $searchkey;
			$keyword_search_sql = "";

			for ($i = 0; $i < 4; $i++) {

				$searchopt_i = ${"searchoptm".$i};	
				$searchkey_i = ${"searchkeym".$i};	
				$searchandor_i = ${"searchandor".$i};	

				if (!empty($searchopt_i) && !empty($searchkey_i)) {
					
						$keyword_search_sql .= " $searchandor_i ";


						if ($searchopt_i == "project_name") {
								$keyword_search_sql .= " (project_name like N'%$searchkey_i%') ";
						} else if ($searchopt_i == "user_company") {
								$keyword_search_sql .= " (user_company like N'%$searchkey_i%') ";
						}else if ($searchopt_i == "user_name") {
								$keyword_search_sql .= " (user_name = '".aes_256_enc($searchkey_i)."'  or user_name_en like '$searchkey_i%') ";
						} else if ($searchopt_i == "manager_name") {
								$keyword_search_sql .= " (manager_name = '".aes_256_enc($searchkey_i)."' or manager_name_en like '$searchkey_i%') ";
						} else if ($searchopt_i == "manager_belong") {
								$keyword_search_sql .= " (manager_belong like N'%$searchkey_i%') ";
						} 
				}
			}

			if($keyword_search_sql != ""){
				$search_sql .= $keyword_search_sql.")";
			}


				if($orderby != "") {
					$order_sql = " ORDER BY $orderby";
				} else {
					$order_sql = " ORDER BY train_seq DESC ";
		
				}	



				$start = 0;
				$rowcount = $_POST["record_count"];
				$lastPageNo = ceil($rowcount / RECORD_LIMIT_PER_FILE);
		
$j=1;
for ($i = $start; $i < $lastPageNo; $i ++) {

	
	$end = RECORD_LIMIT_PER_FILE*($i+1);

	$data = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start,"excel_download_flag"=>"1");			


	$result = $Model_User->getItemTrainDetailsList($data);

	if ($result) {
		$rows = [];
		while ($row = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$rows[] = $row;
		}
	}

	$splitHTML[$i] = '<table id="tblList" class="list" style="	border-collapse: collapse;
border:1px solid black;
	width: 100%;">
     <tr>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black; ">'.$numtext.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$projectname.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$companyname_text.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$user_name_kr.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$user_en_id.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black; ">'.$training_date.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black; ">'.$classify_edu.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$manager_en_id.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$education_manager_affiliation.'</th>
                <th  style="width:200px; background-color: #D4D0C8; border:0.5px solid black;">'.$memotext.'</th>

            </tr>';
	if(count($rows) > 0){
		foreach ($rows as $row) {

							$train_seq = $row['train_seq'];
							$train_name = $row['train_name'];//교육명
							$project_name = $row['project_name'];
							$user_name = aes_256_dec($row['user_name']);
							$user_name_en = $row['user_name_en'];
							$user_company = $row['user_company'];
							$manager_type = $row['manager_type'];

							$manager_name = aes_256_dec($row['manager_name']);
							$manager_name_en = $row['manager_name_en'];
							$manager_company = $row['manager_company'];//담당자회사명
							$manager_belong = $row['manager_belong'];//담당자소속
							$memo = $row['memo'];
														
							$train_date = $row['train_date'];
						  $formatted_train_date = date('Y-m-d', strtotime($train_date));
				if (!empty($memo) && $memo !== 'null') {
				$_memo_text=$memo;
			}else{
				$_memo_text="";
			}

									if($manager_type=="EMP"){
								$manager_type_value=$_LANG_TEXT['out_manager_text'][$lang_code];


							} else if($manager_type=="OUT"){
								$manager_type_value=$_LANG_TEXT['onsite_agent_text'][$lang_code];

						} else {
						$manager_type_value="";

						}


			$splitHTML[$i] .= '<tr>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $j . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $project_name . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  " >' . $user_company . '</td>							
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $user_name. '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $user_name_en. '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $formatted_train_date . '</td>

					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $manager_type_value . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">'  . $manager_name_en . '</td>
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $manager_belong . '</td>							
				 
					<td   style="text-align: center; vertical-align: middle;min-height:30px;  border:0.5px solid black;  ">' . $_memo_text . '</td>
																								
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


