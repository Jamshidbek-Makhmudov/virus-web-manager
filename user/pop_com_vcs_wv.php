<?php
$_section_name = "pop_user_vcs_wv";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$v_com_seq = $_REQUEST[v_com_seq];
$src = $_REQUEST[src];

/*src(호출페이지)
1.user_info - /wvcs/user/user_info_view.php
2.pop_user_vcs_summary - /wvcs/user/pop_user_vcs_summary.php
*/

$qry_params = array("com_seq"=>$v_com_seq);
$qry_label = QRY_USER_COM_INFO;
$sql = query($qry_label,$qry_params);
$result = sqlsrv_query($wvcs_dbcon, $sql);
$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

$v_com_name = aes_256_dec($row['v_com_name']);

?>
<script language="javascript">
$("document").ready(function(){

	StatisticsComWVCheckData('<?=$v_com_seq?>');

	window.onresize = function(event) {
		ChartResizeUserWVCheck();
	};

});
</script>
<div id="mark">
	<div class="content">
		<div class='tit'>
			<div class='txt'><?=$v_com_name?> <?=$_LANG_TEXT["checkstatustext"][$lang_code];?></div>
			<div class='right'>
			<?if($src=="pop_com_vcs_summary"){?>
				<a href="javascript:" onclick="return popCompanyVcsSummary('<?=$v_com_seq?>');"><div class="prev_page"><?=$_LANG_TEXT['btngobeforepage'][$lang_code]?></div></a>
			<?}?>
				<div class='close' onClick="ClosepopContent();"></div>
			</div>
		</div>
		<div class='wrapper2'>
			<div style='margin-top:10px'>
				<div class="section01">
					<div style='margin:20px 0px 20px 20px;'>
						<h1><?=$_LANG_TEXT['weaknesstext'][$lang_code]?></h1>
						<div class="ch"><canvas id="chartUserCheckWEAK"  name="chartUserCheck" gubun="WEAK"  width="250%" height="250%" /></canvas></div>
						<div class='ch_legend'><div id="chartUserCheckWEAK_legend" class="chart-legend"></div></div>
					</div>
				</div>
				<div class='section01_data'>
					<div style='margin:5px'>
<?
$search_sql = " AND vcs1.v_user_seq in (SELECT v_user_seq FROM tb_v_user WHERE v_com_seq='{$v_com_seq}') ";
$qry_params = array("search_sql"=>$search_sql);
$qry_label = QRY_USER_VCS_WEAK_LIST;
$sql = query($qry_label,$qry_params);

//echo nl2br($sql);

$result = @sqlsrv_query($wvcs_dbcon, $sql,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

if($result) {
	$row_count = sqlsrv_num_rows($result);

?>
					<table class="list" style='margin-top:0px'>
						<tr>
							<th style='min-width:50px' ><?=$_LANG_TEXT["numtext"][$lang_code];?></th>
							<th style='min-width:80px' ><?=$_LANG_TEXT["visitortext"][$lang_code];?></th>
							<th style='min-width:70px' ><?=$_LANG_TEXT["checkdatetext"][$lang_code];?></th>
							<th style='min-width:70px' ><?=$_LANG_TEXT["indatetext"][$lang_code];?></th>
							<th style='min-width:80px' ><?=$_LANG_TEXT["checkgubuntext"][$lang_code];?></th>
							<th style='min-width:120px' ><?=$_LANG_TEXT["ostext"][$lang_code];?></th>
							<th style='min-width:150px' ><?=$_LANG_TEXT["checkitemtext"][$lang_code];?></th>
							<th style='min-width:80px' ><?=$_LANG_TEXT["checkresulttext"][$lang_code];?></th>
							<th style='min-width:80px' ><?=$_LANG_TEXT["resolvedresulttext"][$lang_code];?></th>
						</tr>
<?
					$no = $row_count;
					while($row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){

						$check_date = $row['check_date'];
						$in_date = $row['in_date'];
						$check_type = $row['wvcs_type'];
						$device = $row['os_ver_name'];
						$weakness_name = $row['weakness_name'];
						$v_user_name = aes_256_dec($row['v_user_name']);

						$str_org_status = $row['org_status']=="SAFE" ? $_LANG_TEXT['safetytext'][$lang_code] : $_LANG_TEXT['weaknessshorttext'][$lang_code];
						$str_fix_status = $row['fix_status']=="SAFE" ? $_LANG_TEXT['safetytext'][$lang_code] : $_LANG_TEXT['weaknessshorttext'][$lang_code];
						
						
?>
						<tr>
							<td><?=$no?></td>
							<td><?=$v_user_name?></td>
							<td><?=$check_date?></td>
							<td><?=$in_date?></td>
							<td><?=$check_type?></td>
							<td><?=$device?></td>
							<td><?=$weakness_name?></td>
							<td><?=$str_org_status?></td>
							<td><?=$str_fix_status?></td>
						</tr>
<?
						$no--;
					}

					if($row_count == 0){

						echo "<tr><td colspan='9'>".$_LANG_TEXT['nodata'][$lang_code]."</td></tr>";
					}
}
?>
					</table>
					</div>
				</div>
			
				<div class="section02">
					<div style='margin:20px'>
						<h1><?=$_LANG_TEXT['virustext'][$lang_code]?></h1>
						<div class="ch"><canvas id="chartUserCheckVIRUS"  name="chartUserCheck" gubun="WEAK"  width="250%" height="250%" /></canvas></div>
						<div class='ch_legend'><div id="chartUserCheckVIRUS_legend" class="chart-legend"></div></div>
					</div>
				</div>
				<div class='section02_data'>
					<div style='margin:5px'>
<?
$search_sql = " AND vcs1.v_user_seq in (SELECT v_user_seq FROM tb_v_user WHERE v_com_seq='{$v_com_seq}') ";
$qry_params = array("search_sql"=>$search_sql);
$qry_label = QRY_USER_VCS_VIRUS_LIST;
$sql = query($qry_label,$qry_params);

//echo nl2br($sql);

$result = @sqlsrv_query($wvcs_dbcon, $sql,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

if($result) {
	$row_count = sqlsrv_num_rows($result);

?>
					<table class="list" style='margin-top:0px'>
						<tr>
							<th style='min-width:50px' ><?=$_LANG_TEXT["numtext"][$lang_code];?></th>
							<th style='min-width:80px' ><?=$_LANG_TEXT["visitortext"][$lang_code];?></th>
							<th style='min-width:70px;width:70px;' ><?=$_LANG_TEXT["checkdatetext"][$lang_code];?></th>
							<th style='min-width:70px;width:70px;' ><?=$_LANG_TEXT["indatetext"][$lang_code];?></th>
							<th style='min-width:80px' ><?=$_LANG_TEXT["checkgubuntext"][$lang_code];?></th>
							<th style='min-width:120px' ><?=$_LANG_TEXT["osndevicetext"][$lang_code];?></th>
							<th style='min-width:80px' ><?=$_LANG_TEXT["virusnametext"][$lang_code];?></th>
							<th style='min-width:150px' ><?=$_LANG_TEXT["filepathtext"][$lang_code];?></th>
							<th style='min-width:60px' ><?=$_LANG_TEXT["transresulttext"][$lang_code];?></th>
						</tr>
<?
					$no = $row_count;
					while($row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
						
						$v_user_name = aes_256_dec($row['v_user_name']);
						$check_date = $row['check_date'];
						$in_date = $row['in_date'];
						$check_type = $row['wvcs_type'];
						$device = $row['os_ver_name'];
						$virus_name = $row['virus_name'];
						$virus_path = $row['virus_path'];
						$virus_status = $row['virus_status'];

						
						
?>
						<tr>
							<td><?=$no?></td>
							<td><?=$v_user_name?></td>
							<td><?=$check_date?></td>
							<td><?=$in_date?></td>
							<td><?=$check_type?></td>
							<td><?=$device?></td>
							<td><?=$virus_name?></td>
							<td><?=$virus_path?></td>
							<td><?=$virus_status?></td>
						</tr>
<?
						$no--;
					}


					if($row_count == 0){

						echo "<tr><td colspan='9'>".$_LANG_TEXT['nodata'][$lang_code]."</td></tr>";
					}
}
?>
					</table>
					</div>
				</div>
			</div>
<? 
	$param = "v_com_seq=".$v_com_seq."&src=".$src;
	$excel_param_enc = ParamEnCoding($param);
	$excel_down_url = $_www_server."/user/user_vcs_wv_list_excel.php?enc=".$excel_param_enc;
?>
			<div class="right" style='margin:5px 15px 0px 0px'>
				<a href="#" id='btnExcelDown' onclick="ExcelDown('<?=$excel_down_url?>','btnExcelDown')" class="btnexcel" ><?=$_LANG_TEXT["btnexceldownload"][$lang_code];?></a>
			</div>

		</div><!--wrapper//-->

	</div><!--content//-->

</div><!--mark//-->
