<?
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI']) - 1);
$_apos = stripos($_REQUEST_URI,  "/");
if ($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$scan_center_code = $_POST['scan_center_code'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

$search_sql = "";
if($scan_center_code !=""){ 

  $search_sql .= " and v2.in_center_code = '{$scan_center_code}'  ";
}

$Model_Dashboard=new Model_Dashboard();

	$Model_Dashboard->SHOW_DEBUG_SQL = false;
	$args = array("end_date"=>$end_date,"start_date"=>$start_date,"search_sql"=>$search_sql);		

	$result = $Model_Dashboard->getVcsFilePeriodStat($args);
	if ($result) {
			$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
			$file_count = $row['file_count'];
			$file_in_count=number_format($row['file_in_count']);
			$virus_count=number_format($row['virus_count']);
			$bad_ext_count=number_format($row['bad_ext_count']);
			$exp_file_in_count=number_format($row['exp_file_in_count']);
			

				// calculate the percents for progress bar
				if (isset($row['file_in_count'])) {
					$count = $row['file_in_count'];
				} else {
					$count = 0;
				}

				if($file_count=="") $file_count = 0;

            $file_in_count_percent = $file_count==0 ? 0 : min(($count / $file_count) * 100, 100);

							if (isset($row['virus_count'])) {
								$count = $row['virus_count'];
							} else {
								$count = 0;
							}
							$virus_count_percent = $file_count==0 ? 0 : ($count / $file_count) * 100;

							if (isset($row['bad_ext_count'])) {
								$count = $row['bad_ext_count'];
							} else {
								$count = 0;
							}
							$bad_ext_countpercent = $file_count==0 ? 0 : ($count / $file_count) * 100;

							if (isset($row['exp_file_in_count'])) {
								$count = $row['exp_file_in_count'];
							} else {
								$count = 0;
							}
							$exp_file_in_count_percent = $file_count==0 ? 0 : ($count / $file_count) * 100;
		}
	
?>

					<div class="progress-container ">
					<div class="progress-label">
						<span> &#x2022; <?= trsLang('반입 파일 수','number_imported_files'); ?></span>
					</div>
					<div class="progress-bar-container">
						<div class="progress-bar1" style="width: <?=$file_in_count_percent?>%;"></div>
					</div>
					<div class="progress-value">
						<span class="value" style='color:#0273eb;'><?=$file_in_count?></span>
					</div>
				</div>
				
				<div class="progress-container">
					<div class="progress-label">
						<span> &#x2022; <?= trsLang('예외 반입 파일','exp_file_in_count_text'); ?></span>
					</div>
					<div class="progress-bar-container">
						<div class="progress-bar2" style="width: <?=$exp_file_in_count_percent?>%;"></div>
					</div>
					<div class="progress-value">
						<span class="value" style='color:#49c65e;'><?=$exp_file_in_count?></span>
					</div>
				</div>
				<div class="progress-container">
					<div class="progress-label">
						<span> &#x2022; <?= trsLang('악성코드 발견','virusdetectiontext'); ?></span>
					</div>
					<div class="progress-bar-container">
						<div class="progress-bar3" style="width: <?=$virus_count_percent?>%;"></div>
					</div>
					<div class="progress-value">
						<span class="value" style='color:#ff9e01;'><?=$virus_count?></span>
					</div>
				</div>
				<div class="progress-container">
					<div class="progress-label">
						<span> &#x2022; <?= trsLang('위변조 의심','suspectforgerytext'); ?></span>
					</div>
					<div class="progress-bar-container">
						<div class="progress-bar4" style="width: <?=$bad_ext_countpercent?>%;"></div>
					</div>
					<div class="progress-value">
						<span class="value" style='color:#fe5200;'><?=$bad_ext_count?></span>
					</div>
				</div>		
	<?
				if ($result) sqlsrv_free_stmt($result);
				if ($wvcs_dbcon) sqlsrv_close($wvcs_dbcon);
?>