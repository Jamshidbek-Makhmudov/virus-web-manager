<?
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI']) - 1);
$_apos = stripos($_REQUEST_URI,  "/");
if ($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";


$str_kiosk_name = $_REQUEST['kiosk_name'];
	// if($str_kiosk_name=="first") $str_kiosk_name = "IDC센터";	

$scan_center_code = $_REQUEST['scan_code'];
$Model_Dashboard=new Model_Dashboard();

if($scan_center_code !=""){ 

	$search_sql_Vaccine .= " and c.scan_center_code = '{$scan_center_code}'  ";
}
$start_date = date("Y-m-d", strtotime(date("Y-m-d") . " -1 month"));
$end_date = date("Y-m-d");
 $Model_Dashboard->SHOW_DEBUG_SQL=false;
 $args = array("end_date"=>$end_date,"start_date"=>$start_date,"search_sql"=>$search_sql_Vaccine);	
	$result = $Model_Dashboard->getVaccineUpdateStat($args);
	if ($result) {
		$rows = [];
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$rows[] = $row;
		}
	}

	if(count($rows) > 0){
		foreach ($rows as $row) {

						$kiosk_name=$row['kiosk_name'];
			$scan_center_name=$row['scan_center_name'];	
			$app_name=$row['app_name'];	
			$ver=$row['ver'];	
			$str_update_time=setDateFormat($row['update_time'],"Y.m.d H:i");

		if ($str_kiosk_name != "") {
			if ($kiosk_name == $str_kiosk_name) {
				?>
			
				<div class='list flex-start' style='margin-left:20px;'>
			<div class='flex-center '>	
				<?php if ($app_name == 'ESET') { ?>
					<span class='logo_eset' ></span>
					<?php } else if ($app_name == 'V3') { ?>		
						<span class='logo_v3' style='margin: 0 20px;'  ></span>
						<?php } else { ?>		
							<span class='logo_kabank_virus' style='margin: 0 20px;'></span>
							<?php } ?>		
			</div>
			<div class='' style='margin-left:20px;'>
				<span class='title'><?= $app_name ?> <?= $scan_center_name ?> </span>
				<br>
				<span class='title'> <?= trsLang('버전', 'versiontext'); ?> <?= $ver ?> | <?= trsLang('업데이트 일시', 'update_date_text'); ?> <?= $str_update_time ?></span>
			</div>													
		</div>

			<?
			}
		}else{
							?>
			
				<div class='list flex-start' style='margin-left:20px;'>
			<div class='flex-center '>	
				<?php if ($app_name == 'ESET') { ?>
					<span class='logo_eset' ></span>
					<?php } else if ($app_name == 'V3') { ?>		
						<span class='logo_v3' style='margin: 0 20px;'  ></span>
						<?php } else { ?>		
							<span class='logo_kabank_virus' style='margin: 0 20px;'></span>
							<?php } ?>		
			</div>
			<div class='' style='margin-left:20px;'>
				<span class='title'><?= $app_name ?> <?= $scan_center_name ?> </span>
				<br>
				<span class='title'> <?= trsLang('버전', 'versiontext'); ?> <?= $ver ?> | <?= trsLang('업데이트 일시', 'update_date_text'); ?> <?= $str_update_time ?></span>
			</div>													
		</div>

			<?

		}
		}



	}else{
							?>
				<span class='flex-center' style='width:100%; height:200px; color:#000; fornt-weight:bold;' ><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></span>
<?
		}
	
				if ($result) sqlsrv_free_stmt($result);
				if ($wvcs_dbcon) sqlsrv_close($wvcs_dbcon);
?>