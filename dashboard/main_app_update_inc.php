<?php 

	if($scan_center_code !=""){ 
		$search_sql_Vaccine .= " and c.scan_center_code = '{$scan_center_code}'  ";
	}

	//키오스크정보 가져오기
	$args = array("search_sql"=>$search_sql_Vaccine);		
	$result = $Model_Dashboard->getScanCenterKioskList($args);
	if ($result) {
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

			$key = $row['scan_center_code']."_".$row['kiosk_id'];
			
			if($row['kiosk_name'] == ""){
				$kiosk_list[$key] .= $row['scan_center_name'];
			}else{
				$kiosk_list[$key] = $row['kiosk_name'];
			}
		}
		$first_kiosk_name = "(".reset($kiosk_list).")";
	}else{
		$kiosk_list[$scan_center_code."_0"] = "";
	}
	
	//앱 업데이트 정보
	$Model_Dashboard->SHOW_DEBUG_SQL = false;
	$args = array("end_date"=>$end_date,"start_date"=>$start_date,"search_sql"=>$search_sql_Vaccine);		
	$result = $Model_Dashboard->getVaccineUpdateStat($args);
	if ($result) {
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$key = $row['scan_center_code']."_".$row['kiosk_id'];
			$content = trsLang('업데이트 일시', 'update_date_text')." : ".setDateFormat($row[update_time],"Y-m-d H:i")." <BR>(ver. ".$row[ver].")";
			$kiosk_update_content[$key][$row['app_name']] =  $content;
		}
	}
?>
<div class='outline_left' >
	 <div class='chart_title flex-between'>
		<h2 ><?= trsLang('백신 업데이트 현황','vaccine_update_status'); ?> 
			<span id='vaccine_update_title'><? echo $first_kiosk_name;?></span>
		</h2>	
		<div class='cirlce_box flex-evenly'>
			<? $idx = 0; 
				foreach($kiosk_list as $key =>$kiosk_name){?>			
				<a href='javascript:void(0)' onclick="toggleAppUpdate()" class='circle <?  if($idx==0) echo "on";?>' data-kiosk-key='<? echo $key?>' title='<?= $kiosk_name ?>' ></a>	
			<?$idx++;}?>	
		</div>
	</div>
	 <div class='list_box '>
		<div id='file_app_update_list' >
		<? 
		$idx = 0;
		foreach($kiosk_list as $key =>$kiosk_name){

			$v3_update_content = $kiosk_update_content[$key]['V3'];
			$eset_update_content = $kiosk_update_content[$key]['ESET'];		

			if($v3_update_content =="") $v3_update_content = trsLang('업데이트내역이없습니다', 'noupdatetext');
			if($eset_update_content =="") $eset_update_content = trsLang('업데이트내역이없습니다', 'noupdatetext');

		?>		
		<table class="view" id='tbl_<? echo $key;?>' <? if($idx > 0) echo "style='display:none;'";?>>
			<tr><td class='text-center bg' >
				<div class='list flex-start' style='margin-left:20px;'>
					<div class='flex-center' style='width:100px;'>	
						<span class='logo_v3' ></span>
					</div>
					<div class='' style='margin-left:20px;text-align:left'>
						<div class='title'>AhnLab V3 Internet Security</div>
						<div class='title'>
							<? echo $v3_update_content;?>
						</div>													
					</div>
				</div>
				<div class='list flex-start' style='margin-left:20px;'>
					<div class='flex-center ' style='width:100px;'>	
						<span class='logo_eset' ></span>
					</div>
					<div class='' style='margin-left:20px;text-align:left'>
						<div class='title'>ESET Endpoint Security</div>
						<div class='title'>
							<? echo $eset_update_content;?>
						</div>													
					</div>
				</div>
				<div class='list flex-start' style='margin-left:20px;'>
					<div class='flex-center ' style='width:100px;'>	
						<span class='logo_kabank_virus'></span>
					</div>
					<div class='' style='margin-left:20px;text-align:left'>
						<div class='title'>Vaccine</div>
						<div class='title'>
							<?= trsLang('업데이트내역이없습니다', 'noupdatetext'); ?>
						</div>													
					</div>
				</div>				
			</td></tr>
		</table>
		<?
			$idx++;
		}
		?>
	</div>
	</div>




</div>