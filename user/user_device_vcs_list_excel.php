<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

header( "Content-type: application/vnd.ms-excel; charset=utf-8" ); 
header( "Content-Disposition: attachment; filename=User_Device_VCS_list_".date("YmdHis").".xls" ); 
header( "Content-Description: PHP5 Generated Data" ); 
header("Expires: 0"); 
Header("Content-Transfer-Encoding: binary"); 
header("Cache-Control: must-revalidate, post-check=0,pre-check=0"); 
header("Pragma: public");

echo "<?xml version='1.0' encoding='UTF-8'?>"; 

?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" 
 xmlns:o="urn:schemas-microsoft-com:office:office" 
 xmlns:x="urn:schemas-microsoft-com:office:excel" 
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" 
 xmlns:html="http://www.w3.org/TR/REC-html40">  

<DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
    <Author>Dataprotec</Author>
    <LastAuthor>Dataprotec</LastAuthor>
    <Created><?php echo date('Y-m-d His'); ?></Created>
    <Company>Dataprotec</Company>
    <Version>1.0</Version>
  </DocumentProperties>
  <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
    <WindowHeight>6795</WindowHeight>
    <WindowWidth>8460</WindowWidth>
    <WindowTopX>120</WindowTopX>
    <WindowTopY>15</WindowTopY>
    <ProtectStructure>False</ProtectStructure>
    <ProtectWindows>False</ProtectWindows>
  </ExcelWorkbook>
  <Styles>
    <Style ss:ID="Default" ss:Name="Normal">
      <Alignment ss:Vertical="Bottom" />
	  <Borders />
      <Font />
      <Interior />
      <NumberFormat />
      <Protection />
    </Style>
    <Style ss:ID="s20">
      <Font ss:FontName='±¼¸²' x:CharSet='129' x:Family='Modern' ss:Size='10' ss:Bold="1" /> 
	  <Alignment ss:Horizontal='Center' ss:Vertical='Center' ss:WrapText='1'/>
	  <Borders> 
		<Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1'/> 
		<Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1'/> 
		<Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1'/> 
		<Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1'/> 
	  </Borders> 
	  <Interior ss:Color='#D4D0C8' ss:Pattern='Solid'/> 
    </Style>
	<Style ss:ID="s21">
     <Font ss:FontName='±¼¸²' x:CharSet='129' x:Family='Modern' ss:Size='9' /> 
	 <Alignment ss:Horizontal='Center' ss:Vertical='Center' ss:WrapText='1'/>
	 <Borders> 
		<Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1'/> 
		<Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1'/> 
		<Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1'/> 
		<Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1'/> 
	  </Borders> 
    </Style>
	<Style ss:ID="s22">
     <Font ss:FontName='±¼¸²' x:CharSet='129' x:Family='Modern'  ss:Color='#FF0000' ss:Size='9' /> 
	 <Alignment ss:Horizontal='Center' ss:Vertical='Center' ss:WrapText='1'/>
	 <Borders> 
		<Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1'/> 
		<Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1'/> 
		<Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1'/> 
		<Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1'/> 
	  </Borders> 
	</Style>
	<Style ss:ID="s23">
     <Font ss:FontName='±¼¸²' x:CharSet='129' x:Family='Modern'  ss:Color='#0000FF' ss:Size='9' /> 
	 <Alignment ss:Horizontal='Center' ss:Vertical='Center' ss:WrapText='1'/>
	 <Borders> 
		<Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1'/> 
		<Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1'/> 
		<Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1'/> 
		<Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1'/> 
	  </Borders> 
	</Style>
   </Styles>
<?
	$src = $_REQUEST[src];
	$v_com_seq = $_REQUEST[v_com_seq];
	$v_user_seq = $_REQUEST[v_user_seq];
	$device_gubun = $_REQUEST[device_gubun];
	$orderby = $_REQUEST[orderby];	

	if($src=="pop_com_vcs_summary"){

		$qry_params = array("com_seq"=>$v_com_seq);
		$qry_label = QRY_USER_COM_INFO;
		$sql = query($qry_label,$qry_params);
		$result = sqlsrv_query($wvcs_dbcon, $sql);
		$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

		$str_title = $row['v_com_name'];

	}else if($src=="pop_user_vcs_summary"){

		$qry_params = array("v_user_seq"=>$v_user_seq);
		$qry_label = QRY_USER_INFO;
		$sql = query($qry_label,$qry_params);
		$result = sqlsrv_query($wvcs_dbcon, $sql);
		$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

		$str_title = aes_256_dec($row['v_user_name']);

	}
	
	$str_title .= "_".$_LANG_TEXT["devicechecklisttext"][$lang_code];

?>
  <Worksheet ss:Name="<?=$str_title;?>">
    <Table>
	<Column ss:Width='60'/> 
    <Column ss:Width='80'/> 
    <Column ss:Width='150'/>
    <Column ss:Width='100'/> 
    <Column ss:Width='100'/> 
    <Column ss:Width='80'/> 
	<Column ss:Width='200'/>
	<Column ss:Width='150'/>
	<Column ss:Width='200'/>
	<Column ss:Width='80'/>
	<Column ss:Width='80'/>
	<Column ss:Width='80'/>
	<Column ss:Width='100'/>
	  
	  <Row>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["numtext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["visitortext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["devicegubuntext"][$lang_code];?></Data></Cell>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["checkdiskcounttext"][$lang_code];?></Data></Cell>    
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["indatetext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["outdatetext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["manufacturertext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["serialnumbertext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["modeltext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["checkdatetext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["checkgubuntext"][$lang_code];?></Data></Cell>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["progressstatustext"][$lang_code];?></Data></Cell>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["scancentertext"][$lang_code];?></Data></Cell> 
	 </Row>
<?php

if($v_com_seq != ""){
	$search_sql = " AND vcs1.v_user_seq in (SELECT v_user_seq FROM tb_v_user WHERE v_com_seq='{$v_com_seq}') ";
}

if($v_user_seq != ""){
	$search_sql = " AND vcs1.v_user_seq ='{$v_user_seq}' ";
}

if($device_gubun !=""){

	if($device_gubun=='NOTEBOOK'){

		$search_sql .= " AND vcs1.v_asset_type = 'NOTEBOOK' ";

	}else if($device_gubun=='HDD'){
		
		$search_sql .= " AND vcs1.v_asset_type = 'RemovableDevice' AND dsk.media_type ='HDD' ";

	}else if($device_gubun=='Removable'){
		
		$search_sql .= " AND vcs1.v_asset_type = 'RemovableDevice' AND dsk.media_type ='Removable' ";

//	}else if($device_gubun=='CD/DVD'){
//		
//		$search_sql .= " AND vcs1.v_asset_type = 'RemovableDevice' AND lk.drive_type =''CD/DVD' ";

	}else if($device_gubun=='ETC'){

		$search_sql .= " AND vcs1.v_asset_type = 'RemovableDevice' ";
		$search_sql .= " AND CHARINDEX('HDD',dsk.media_type) = 0 ";
		$search_sql .= " AND CHARINDEX('Removable',dsk.media_type) = 0 ";
	}
}


$qry_params = array(
	"search_sql"=> $search_sql
);


if($orderby != "") {
	$order_sql = " ORDER BY $orderby";
} else {
	$order_sql= " ORDER BY vcs1.v_wvcs_seq DESC ";
}

$qry_params = array(
	"order_sql"=>$order_sql
	,"search_sql"=> $search_sql
);
$qry_label = QRY_USER_DEVICE_VCS_LIST_EXCEL;
$sql = query($qry_label,$qry_params);
$result = @sqlsrv_query($wvcs_dbcon, $sql,array(), array("Scrollable"=>SQLSRV_CURSOR_KEYSET)); 

//echo $sql;

if($result){

	$total_cnt = sqlsrv_num_rows($result);
	$no = $total_cnt;
	while ($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {

		$v_wvcs_seq = $row['v_wvcs_seq'];
		$v_user_name = aes_256_dec($row['v_user_name']);

		$check_date = $row['check_date'];
		$in_date	= $row['in_date'];
		$out_date	= $row['out_date'];

		$v_scan_center_name = $row['org_name']." ".$row['scan_center_name'];
		$v_asset_type = $row['v_asset_type'];

		if($v_asset_type=='NOTEBOOK'){
			$sn = $row['v_sys_sn'];
			$maker = $row['v_manufacturer'];
			$model = $row['v_model_name'];
		}else{
			$sn = $row['serial_number'];
			$maker = $row['manufacturer'];
			$model = $row['disk_model'];
		}

		$check_type = $row['wvcs_type'];

		$media_type = $row['media_type'];

		$disk_cnt = $row['disk_cnt'];

		if($v_asset_type=='NOTEBOOK'){
			$str_device_gubun = $_LANG_TEXT["laptoptext"][$lang_code]."(".$row['os_ver_name'].")";
		}else if($media_type=='HDD'){
			$str_device_gubun =  $_CODE['storage_device_type']['HDD'];
		}else if($media_type=='Removable'){
			$str_device_gubun =  $_CODE['storage_device_type']['Removable'];
		}else{
			
			$str_device_gubun = $media_type;
		}

		$vcs_status = $_CODE['vcs_status'][$row['vcs_status']];
			
		$excel_data  = '
			<Row> 
				<Cell ss:StyleID="s21"><Data ss:Type="Number">' . $no . '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $v_user_name . '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $str_device_gubun . '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $disk_cnt . '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $in_date. '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $out_date. '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $maker . '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $sn . '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $model . '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $check_date. '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $check_type . '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $vcs_status . '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $v_scan_center_name . '</Data></Cell> 
			</Row> 
			';

		echo $excel_data;
		$no--;

	}

}

echo "</Table> 

<WorksheetOptions xmlns='urn:schemas-microsoft-com:office:excel'> 
  <Print> 
    <ValidPrinterInfo /> 
    <HorizontalResolution>600</HorizontalResolution> 
    <VerticalResolution>600</VerticalResolution> 
   </Print> 
   <Selected /> 
   <Panes> 
     <Pane> 
       <Number>3</Number> 
       <ActiveRow>5</ActiveRow> 
       <ActiveCol>1</ActiveCol> 
     </Pane> 
   </Panes> 
    <ProtectObjects>False</ProtectObjects> 
      <ProtectScenarios>False</ProtectScenarios> 
    </WorksheetOptions> 
  </Worksheet> 
</Workbook>  ";

if($result) sqlsrv_free_stmt($result);  
if($wvcs_dbcon) sqlsrv_close($wvcs_dbcon);

exit;


?>