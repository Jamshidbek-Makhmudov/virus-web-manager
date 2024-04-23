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

//**설치프로그램
$qry_params = array("v_wvcs_seq"=>$v_wvcs_seq);
$qry_label = QRY_RESULT_PC_DETAIL_INFO_PROGRAM;
$sql = query($qry_label,$qry_params);

$result = @sqlsrv_query($wvcs_dbcon, $sql,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

$str_program = "<table class='info'>
				<thead>
					<th style='width:10%'>".$_LANG_TEXT['numtext'][$lang_code]."</th>
					<th style='width:30%'>".$_LANG_TEXT['programnametext'][$lang_code]."</th>
					<th style='width:15%'>".$_LANG_TEXT['versiontext'][$lang_code]."</th>
					<th style='width:30%'>".$_LANG_TEXT['manufacturertext'][$lang_code]."</th>
					<th style='width:15%'>".$_LANG_TEXT['installdatetext'][$lang_code]."</th>
				</thead>";
$pg_count = 0;
if($result){

	$pg_count = sqlsrv_num_rows($result);
	$no = 1;
	while($row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){

		$str_program .= "<tr>
						<td>".$no."</td>
						<td>".$row['prog_name']."</td>
						<td>".$row['prog_ver']."</td>
						<td>".$row['prod_company']."</td>
						<td>".$row['install_ymd']."</td>
					</tr>";

		$no++;
	}
}

if($pg_count == 0){

	$str_program .= "<tr><td colspan='5'>".$_LANG_TEXT['nodata'][$lang_code]."</td></tr>";
}

$str_program .= "</table>";
?>
<table class="view">
	<tr><td><?=$str_program;?></td></tr>
<table>