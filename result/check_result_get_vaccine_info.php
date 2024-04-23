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
$idx = 10;
	
			$str_vaccine = "";
			$str_vaccine .= '<b style="">'.$_LANG_TEXT['vaccinenametext'][$lang_code]."</b> : AhnLab V3 Internet Security 9.0, ";
			$str_vaccine .= '<b>'.$_LANG_TEXT['vaccineupdatedatetext'][$lang_code]."</b> : 2023-08-28 16:11:53, ";
			$str_vaccine .= '<b>'.$_LANG_TEXT['scandatetext'][$lang_code]."</b> : 2023-09-04 10:37:00";

	
				// $str_vaccine .= "<ul class='info'><li><img src='".$row['img_path']."'></li></ul>";


			$str_vaccine_detail = "<table class='info'>
							<thead>
								<th style='width:10%'>".$_LANG_TEXT['numtext'][$lang_code]."</th>
								<th style='width:10%'>".$_LANG_TEXT['devicetext'][$lang_code]."</th>
								<th style='width:20%'>".$_LANG_TEXT['virusnametext'][$lang_code]."</th>
								<th>".$_LANG_TEXT['filepathtext'][$lang_code]."</th>
								<th style='width:15%'>".$_LANG_TEXT['transresulttext'][$lang_code]."</th>
							</thead>";
			


					$str_vaccine_detail .= "<tr>
									<td>".$no."</td>
									<td>".$row['drive_type']."</td>
									<td>".$row['virus_name']."</td>
									<td>".$row['virus_path']."</td>
									<td>".$row['virus_status']."</td>
								</tr>";



			$str_vaccine_detail .= "</table>";

?>

			<table  class="view"   <? if($idx == 0) echo "style='border:1px;'";?>>
				<tr style="height:10px;">
					<th style='width:150px'><?=$_LANG_TEXT['inspec_info'][$lang_code]?></th>
					<td style='text-align:left'><?=$str_vaccine?></td>
				</tr>

				<tr class="bg">
					<th><?=$_LANG_TEXT['checkresulttext'][$lang_code]?></th>
					<td><?=$str_vaccine_detail;?></td>
				</tr>
	
			<table>

<?


//}//if($v_wvcs_seq){

//if($vacc_row_count==0){
?>
	<!-- <table class="view">
		<tr>
			<th style='width:150px'><?//=$_LANG_TEXT['inspec_info'][$lang_code]?></th>
			<td style='text-align:left'><ul class='info'><li><div style='text-align:center'><?// echo $_LANG_TEXT['nodata'][$lang_code];?></div></li></ul></td>
		</tr>
	<table> -->
<?//}?>