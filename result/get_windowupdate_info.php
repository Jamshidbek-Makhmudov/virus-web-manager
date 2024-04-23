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

//**윈도우업데이트정보
$qry_params = array("v_wvcs_seq"=>$v_wvcs_seq);
$qry_label = QRY_RESULT_PC_WINDOW_UPDATE_INFO;
$sql = query($qry_label,$qry_params);

$result = @sqlsrv_query($wvcs_dbcon, $sql,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
$vacc_row_count = @sqlsrv_num_rows($result);

if($result){

	while($row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){

		$windowsupdate_seq = $row['windowsupdate_seq'];
		
		$str_winupdate .= '<b>'.$_LANG_TEXT['windowupdateconfirmdatetext'][$lang_code]."</b> : ".$row['wu_check_date'].", ";
		$str_winupdate .= '<b>'.$_LANG_TEXT['windowupdateinstalldatetext'][$lang_code]."</b> : ".$row['wu_install_date'];

		if($row['img_path']){
			$str_winupdate .= "<ul class='info'><li><img src='".$row['img_path']."'></li></ul>";
		}
	}



	//**윈도우업데이트상세정보
	$qry_params = array("windowsupdate_seq"=>$windowsupdate_seq);
	$qry_label = QRY_RESULT_PC_WINDOW_UPDATE_DETAIL_INFO;
	$sql = query($qry_label,$qry_params);


	$result2 = @sqlsrv_query($wvcs_dbcon, $sql,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	$row_count = @sqlsrv_num_rows($result2);

	$str_winupdate_detail = "<table class='info'>
					<thead>
						<th style='width:10%'>".$_LANG_TEXT['numtext'][$lang_code]."</th>
						<!--<th style='width:15%'>".$_LANG_TEXT['gubuntext'][$lang_code]."</th>-->
						<th>".$_LANG_TEXT['fatchnametext'][$lang_code]."</th>
						<th style='width:15%'>".$_LANG_TEXT['installdatetext'][$lang_code]."</th>
					</thead>";
	
	if($result2){
		$no = 1;
		while($row = @sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC)){

			$str_winupdate_detail .= "<tr>
							<td>".$no."</td>
							<!--<td>".$row['wu_type']."</td>-->
							<td>".$row['wu_name']."</td>
							<td>".substr($row['install_date'],0,10)."</td>
						</tr>";

			$no++;
		}
	}

	if($row_count == 0){

		$str_winupdate_detail .= "<tr><td colspan='5'>".$_LANG_TEXT['nodata'][$lang_code]."</td></tr>";
	}

	$str .= "</table>";

}else{

	$str_winupdate = "<ul class='info'><li><div style='text-align:center'>".$_LANG_TEXT['nodata'][$lang_code]."</div></li></ul>";

}//if($vacc_row_count > 0){

?>
<table class="view">
<tr>
	<th style='width:150px'><?=$_LANG_TEXT['runinfotext'][$lang_code]?></th>
	<td style='text-align:left'><?=$str_winupdate?></td>
</tr>
<?if($vacc_row_count > 0){?>
<tr  class="bg">
	<th><?=$_LANG_TEXT['checkresulttext'][$lang_code]?></th>
	<td><?=$str_winupdate_detail;?></td>
</tr>
<?}?>
<table>