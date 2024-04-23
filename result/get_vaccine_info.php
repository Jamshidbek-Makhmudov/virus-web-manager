<?
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common2.inc";

$v_wvcs_seq = $_POST['v_wvcs_seq'];

if($v_wvcs_seq){

	//**백신실행정보
	$qry_params = array("v_wvcs_seq"=>$v_wvcs_seq);
	$qry_label = QRY_RESULT_PC_VACCINE_INFO;
	$sql = query($qry_label,$qry_params);

	$result = @sqlsrv_query($wvcs_dbcon, $sql,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	$vacc_row_count = @sqlsrv_num_rows($result);
	
	if($result) {

		$idx = 0;
		while($row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){

			$vaccine_seq = $row['vaccine_seq'];
			$scan_start_date = $row['scan_start_date'];
			$scan_end_date = $row['scan_end_date'];
			
			$str_vaccine = "";
			$str_vaccine .= '<b>'.$_LANG_TEXT['vaccinenametext'][$lang_code]."</b> : ".$row['vaccine_name'].", ";
			$str_vaccine .= '<b>'.$_LANG_TEXT['vaccineupdatedatetext'][$lang_code]."</b> : ".setDateFormat($row['vaccine_update_date'],'Y-m-d H:i').", ";


			//시간차 계산
			$diff_time = dateDiff($scan_start_date,$scan_end_date,'time');

			if($scan_start_date != "" && $scan_end_date != ""){
				$str_vaccine .= '<b>'.trsLang('검사소요시간','scanduringtime')."</b> : ".$diff_time." ( ".setDateFormat($scan_start_date,'Y-m-d H:i:s')." ~ ".setDateFormat($scan_end_date,'Y-m-d H:i:s')." )";
			}else{
				$str_vaccine .= '<b>'.$_LANG_TEXT['scandatetext'][$lang_code]."</b> : ".setDateFormat($row['scan_date'],'Y-m-d H:i');
			}

			if($row['img_path']){
				$str_vaccine .= "<ul class='info'><li><img src='".$row['img_path']."'></li></ul>";
			}

			//**백신실행 점검결과
			$qry_params = array("vaccine_seq"=>$vaccine_seq);
			$qry_label = QRY_RESULT_PC_VACCINE_DETAIL_INFO;
			$sql = query($qry_label,$qry_params);

			//echo nl2br($sql);

			$result2 = @sqlsrv_query($wvcs_dbcon, $sql,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			$row_count = @sqlsrv_num_rows($result2);

			$str_vaccine_detail = "<table class='info'>
							<thead>
								<th style='width:10%'>".$_LANG_TEXT['numtext'][$lang_code]."</th>
								<th style='width:10%'>".$_LANG_TEXT['devicetext'][$lang_code]."</th>
								<th style='width:20%'>".$_LANG_TEXT['virusnametext'][$lang_code]."</th>
								<th>".$_LANG_TEXT['filepathtext'][$lang_code]."</th>
								<th style='width:15%'>".$_LANG_TEXT['transresulttext'][$lang_code]."</th>
							</thead>";
			
			if($result2){

				$no = 1;
				while($row = @sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC)){

					$str_vaccine_detail .= "<tr style='background-color:#fff !important;'>
									<td>".$no."</td>
									<td>".$row['drive_type']."</td>
									<td>".$row['virus_name']."</td>
									<td>".$row['virus_path']."</td>
									<td>".$row['virus_status']."</td>
								</tr>";

					$no++;
				}
			}



			if($row_count == 0){

				$str_vaccine_detail .= "<tr style='background-color:#fff !important;'><td colspan='5'>".$_LANG_TEXT['nodata'][$lang_code]."</td></tr>";
			}

			$str_vaccine_detail .= "</table>";

?>

			<table  class="view"  <? if($idx > 0) echo "style='border:1px;'";?>>
				<tr>
					<th style='width:150px'><?=$_LANG_TEXT['runinfotext'][$lang_code]?></th>
					<td style='text-align:left'><?=$str_vaccine?></td>
				</tr>
				<?if($vacc_row_count > 0){?>
				<tr class="bg">
					<th><?=$_LANG_TEXT['checkresulttext'][$lang_code]?></th>
					<td><?=$str_vaccine_detail;?></td>
				</tr>
				<?}?>
			<table>

<?
		$idx++;
		}

	}


}//if($v_wvcs_seq){

if($vacc_row_count==0){
?>
	<table class="view">
		<tr>
			<th style='width:150px'><?=$_LANG_TEXT['runinfotext'][$lang_code]?></th>
			<td style='text-align:left'><ul class='info'><li><div style='text-align:center'><? echo $_LANG_TEXT['nodata'][$lang_code];?></div></li></ul></td>
		</tr>
	<table>
<?}?>