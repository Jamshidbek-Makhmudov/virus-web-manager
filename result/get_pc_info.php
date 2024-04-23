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

$qry_params = array("v_wvcs_seq"=>$v_wvcs_seq);
$qry_label = QRY_RESULT_PC_INFO_DETAIL;
$sql = query($qry_label,$qry_params);

//echo nl2br($sql);
$result = @sqlsrv_query($wvcs_dbcon, $sql,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

if($result){
	$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
	$row_count = @sqlsrv_num_rows( $result );  
}

if($row){
	
	$_v_notebook_key = $row['v_notebook_key'];
	$_v_asset_type = $row['v_asset_type'];
	$_v_sys_sn = $row['v_sys_sn'];
	$_v_hdd_sn = $row['v_hdd_sn'];
	$_v_board_sn = $row['v_board_sn'];

	$_v_model_name = $row['v_model_name'];
	$_v_manufacturer = $row['v_manufacturer'];
	$_host_name = $row['host_name'];
	$_ram_size = $row['ram_size'];
	$_mac_addr = $row['mac_addr'];
	$_cpu_info = $row['cpu_info'];

	$_os_info = $row['os_info'];
	$_os_ver_name = $row['os_ver_name'];
	$_os_architecture = $row['os_architecture'];
	$_os_ver_major = $row['os_ver_major'];
	$_os_ver_minor = $row['os_ver_minor'];
	$_os_ver_build = $row['os_ver_build'];
	$_os_ver_sp = $row['os_ver_sp'];
	$_os_key = $row['os_key'];

	$_boot_device = $row['boot_device'];
	$_pc_gmt = $row['pc_gmt'];
	$_pc_time = $row['pc_time'];
	$_work_group = $row['work_group'];
	$_user_account = $row['user_account'];
	$_user_grade = $row['user_grade'];
	$_mui_lang = $row['mui_lang'];
	$_bios_ver = $row['bios_ver'];
	$_windows_dir = $row['windows_dir'];
}

?>
<table class="view">
	<tr>
		<th style='width:150px'><?=$_LANG_TEXT['devicegubuntext'][$lang_code]?></th>
		<td style='width:350px'><?=$_CODE['asset_type'][$_v_asset_type]?></td>
		<th style='width:150px' class="line"><?=$_LANG_TEXT['ostext'][$lang_code]?></th>
		<td ><?=$_os_info?><?if($_os_architecture) echo " (".$_os_architecture."bit)";?></td>
	</tr>
	<tr class="bg">
		<th><?=$_LANG_TEXT['modeltext'][$lang_code]?></th>
		<td><?=$_v_model_name?></td>
		<th class="line"><?=$_LANG_TEXT['manufacturertext'][$lang_code]?></th>
		<td><?=$_v_manufacturer?></td>
	</tr>
	
	<tr >
		<th>System <?=$_LANG_TEXT['serialnumbertext'][$lang_code]?></th>
		<td><?=$_v_sys_sn?></td>
		<th class="line">Board <?=$_LANG_TEXT['serialnumbertext'][$lang_code]?></th>
		<td><?=$_v_board_sn?></td>
	</tr>
	<tr class="bg" >
		<th>HardWare <?=$_LANG_TEXT['serialnumbertext'][$lang_code]?></th>
		<td><?=$_v_hdd_sn?></td>
		<th class="line"><?=$_LANG_TEXT['laptoptext'][$lang_code]?> <?=$_LANG_TEXT['serialnumbertext'][$lang_code]?></th>
		<td><?=$_v_notebook_key?></td>
	</tr>
	<tr >
		<th>Host Name</th>
		<td><?=$_host_name?></td>
		<th class="line">Ram Size</th>
		<td><?=$_ram_size?></td>
	</tr>
	<tr class="bg" >
		<th><?=$_LANG_TEXT['cputext'][$lang_code]?></th>
		<td><?=$_cpu_info?></td>
		<th class="line"><?=$_LANG_TEXT['macaddresstext'][$lang_code]?></th>
		<td><?=$_mac_addr?></td>
	</tr>
	<tr >
		<th>Work Group</th>
		<td><?=$_work_group?></td>
		<th class="line"><?=$_LANG_TEXT['bootdevicetext'][$lang_code]?></th>
		<td><?=$_boot_device?></td>
	</tr>
	<tr class="bg" >
		<th><?=$_LANG_TEXT['useraccounttext'][$lang_code]?></th>
		<td><?=$_user_account?></td>
		<th class="line">Windows Directory</th>
		<td><?=$_windows_dir?></td>
	</tr>
</table>