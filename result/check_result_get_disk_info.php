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

	//**������ũ����
	$qry_params = array("v_wvcs_seq"=>$v_wvcs_seq);
	$qry_label = QRY_RESULT_PC_DETAIL_INFO_LDISK;
	$sql = query($qry_label,$qry_params);

	//echo nl2br($sql);

	$result = sqlsrv_query($wvcs_dbcon, $sql); 

	$str_ldisk = "<div class='ldisk'>";

	$lno = 0;
	if($result){
		while($row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){

		//	$str_ldisk .= "<li>".$row['vol_letter']." , VOL_NAME = ".$row['vol_name']." , FILE_SYSTEM=".$row['file_system']."<BR>"
		//			."VOL_SIZE = ".formatBytes($row['tot_size'],3)." ( FREE_SIZE = ".formatBytes($row['free_size'],3)." )<BR>"
		//			."DRIVE_TYPE = ".$row['drive_type']."</li>";
			
			$windows_dir = $row['windows_dir'];
			$vol_letter = $row['vol_letter'];
			$v_asset_type = $row['v_asset_type'];
			$drive_type = $row['drive_type'];
			$file_system = $row['file_system'];
			$str_vol_name = $row['vol_name'];
			$str_file_size = formatBytes($row['free_size'],3)." free of ".formatBytes($row['tot_size'],3);
			$str_file_system = "File System : ".$row['file_system'];
			

			if(is_numeric($row['tot_size']) && is_numeric($row['free_size'])){
			
				$use_size = $row['tot_size'] - $row['free_size'];

				if($row['tot_size'] > 0){
					$usage_percent = (int)($use_size/$row['tot_size'] * 100);
				}else{
					$usage_percent = 0;
				}

				if($usage_percent > 90){
					$bar_color = "#d6484c";
				}else{
					$bar_color = "#5AD4EB";
				}
			}

			if($v_asset_type=="NOTEBOOK" && substr($windows_dir,0,2)==$vol_letter){

				$drive_img = "local_disk.png";

			}else{

				if(trim($file_system)=="CDFS"){
						
					$drive_img = "dvd_disk.png";
					$str_file_size = formatBytes($row['tot_size'],3);
					$str_file_system = "";
					$str_vol_name = "CD/DVD Drive";
					$bar_color = "#5AD4EB";

				}else{
				
					$drive_img = "disk.png";
				}
			}

			

			$str_ldisk .= "
			<div class='drive'>
				<div class='img'><img src='".$_www_server."/images/".$drive_img."' width='90px'></div>
				<div class='txt'>
					<div>".$str_vol_name." (".$row['vol_letter'].")</div>
					<div class='usage'><div style='width:".$usage_percent."%;height:100%;background:".$bar_color."'></div></div>
					<div> ".$str_file_size." </div>
					<div> ".$str_file_system." </div>
				</div>
			</div>
			";

			$lno++;
		}
	}

	$str_ldisk .= "</div>";

	//**������ũ����
	$qry_params = array("v_wvcs_seq"=>$v_wvcs_seq);
	$qry_label = QRY_RESULT_PC_DETAIL_INFO_PDISK;
	$sql = query($qry_label,$qry_params);

	//echo nl2br($sql);

	$result = sqlsrv_query($wvcs_dbcon, $sql); 

	$str_pdisk = "<div class='pdisk'>";

	if($result){

		while($row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){

			if($row['media_type']=="Removable"){
				
				if(stripos($row['disk_model'],"USB")!== false){

					$media_type = "USB";

				}else{

					$media_type = "Removable";
				}

			}else{

				$media_type = $row['media_type'];
			}
			
			if($media_type=="HDD"){

				if(stripos($row['disk_model'],"SSD")!== false){
					$media_img = "ssd.png";
				}else{
					$media_img = "hdd.png";
				}
			}else if($media_type=="USB"){
				$media_img = "usb.png";
			}else if($media_type=="CD/DVD"){
				$media_img = "cddvd.png";
			}else if($media_type=="Removable"){
				$media_img = "Removable.png";
			}else{
				$media_img = "Removable.png";
			}


			$str_pdisk .= "
				<div class='media'>
					<div class='img'><img src='".$_www_server."/images/".$media_img."' width='90px'><BR>".$media_type."</div>
					<div class='txt'>
							<li><b>Model</b> : ".$row['disk_model']."</li>
							<li><b>Size</b> : ".formatBytes($row['tot_size'],3)."</li>
							<li><b>Sector</b> : ".number_format($row['tot_sector'])."</li>
					</div>
				</div>";

		}

	}

	$str_pdisk .= "</div>";



	//**MAC �ּ�
	/*
	$qry_params = array("v_wvcs_seq"=>$v_wvcs_seq);
	$qry_label = QRY_RESULT_PC_DETAIL_INFO_MACADDR;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql); 

	$str_macaddr = "<ul class='info'>";

	while($row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){

		$str_macaddr .= "<li>ADAPTER_NAME = ".$row['adapter_name']."<BR>"
				."MAC_ADDRESS = ".$row['mac_addr']." , IP_ADDRESS=".$row['ip_addr']."</li>";
	}

	$str_macaddr .= "</ul>";
	*/

}//if($v_wvcs_seq){
?>
<table class="view">
<tr>
	<th style='width:150px'><?=$_LANG_TEXT['ldisktext'][$lang_code]?></th>
	<td><?=$str_ldisk?></td>
</tr>
<tr class="bg">
	<th><?=$_LANG_TEXT['pdisktext'][$lang_code]?></th>
	<td><?=$str_pdisk?></td>
</tr>
<!--<tr>
	<th><?=$_LANG_TEXT['macaddrtext'][$lang_code]?></th>
	<td><?=$str_macaddr?></td>
</tr>-->
</table>