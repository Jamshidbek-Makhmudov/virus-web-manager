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

	//**보안취약점
	$qry_params = array("v_wvcs_seq"=>$v_wvcs_seq);
	$qry_label = QRY_RESULT_PC_WEAKNESS_INFO;
	$sql = query($qry_label,$qry_params);

	//echo nl2br($sql);

	$result = @sqlsrv_query($wvcs_dbcon, $sql,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	$row_count = @sqlsrv_num_rows($result);

	$str = "<table class='info'>
					<thead>
						<th style='width:10%'>".$_LANG_TEXT['numtext'][$lang_code]."</th>
						<th style='width:60%'>".$_LANG_TEXT['checkitemtext'][$lang_code]."</th>
						<th style='width:15%'>".$_LANG_TEXT['checkresulttext'][$lang_code]."</th>
						<th style='width:15%'>".$_LANG_TEXT['resolvedresulttext'][$lang_code]."</th>
					</thead>";

	if($result){

		$no = 1;
		while($row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){

			$str_org_status = $row['org_status']=="SAFE" ? $_LANG_TEXT['safetytext'][$lang_code] : $_LANG_TEXT['weaknessshorttext'][$lang_code];
			$str_fix_status = $row['fix_status']=="SAFE" ? $_LANG_TEXT['safetytext'][$lang_code] : $_LANG_TEXT['weaknessshorttext'][$lang_code];

			$str .= "<tr>
							<td>".$no."</td>
							<td>".$row['weakness_name']."</td>
							<td>".$str_org_status."</td>
							<td>".$str_fix_status."</td>
						</tr>";

			$no++;
		}

	}

	if($row_count == 0){

		$str .= "<tr><td colspan='4'>".$_LANG_TEXT['nodata'][$lang_code]."</td></tr>";
	}

	$str .= "</table>";


}//if($v_wvcs_seq){
?>
<table class="view">
	<tr  class="bg">
		<td><?=$str;?></td>
	</tr>
<table>