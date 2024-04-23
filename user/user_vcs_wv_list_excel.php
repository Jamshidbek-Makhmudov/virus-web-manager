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
header( "Content-Disposition: attachment; filename=User_VCS_WV_list_".date("YmdHis").".xls" ); 
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
      <Font ss:FontName='굴림' x:CharSet='129' x:Family='Modern' ss:Size='10' ss:Bold="1" /> 
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
     <Font ss:FontName='굴림' x:CharSet='129' x:Family='Modern' ss:Size='9' /> 
	 <Alignment ss:Horizontal='Center' ss:Vertical='Center' ss:WrapText='1'/>
	 <Borders> 
		<Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1'/> 
		<Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1'/> 
		<Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1'/> 
		<Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1'/> 
	  </Borders> 
    </Style>
	<Style ss:ID="s22">
     <Font ss:FontName='굴림' x:CharSet='129' x:Family='Modern'  ss:Color='#FF0000' ss:Size='9' /> 
	 <Alignment ss:Horizontal='Center' ss:Vertical='Center' ss:WrapText='1'/>
	 <Borders> 
		<Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1'/> 
		<Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1'/> 
		<Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1'/> 
		<Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1'/> 
	  </Borders> 
	</Style>
	<Style ss:ID="s23">
     <Font ss:FontName='굴림' x:CharSet='129' x:Family='Modern'  ss:Color='#0000FF' ss:Size='9' /> 
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

	if($src=="com_info" || $src=="pop_com_vcs_summary"){

		$qry_params = array("com_seq"=>$v_com_seq);
		$qry_label = QRY_USER_COM_INFO;
		$sql = query($qry_label,$qry_params);
		$result = sqlsrv_query($wvcs_dbcon, $sql);
		$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

		$str_title = $row['v_com_name'];

	}else if($src=="user_info" || $src=="pop_user_vcs_summary"){

		$qry_params = array("v_user_seq"=>$v_user_seq);
		$qry_label = QRY_USER_INFO;
		$sql = query($qry_label,$qry_params);
		$result = sqlsrv_query($wvcs_dbcon, $sql);
		$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

		$str_title = aes_256_dec($row['v_user_name']);

	}
	
	$str_title1 = $str_title."_".$_LANG_TEXT["weaknesstext"][$lang_code]."_".$_LANG_TEXT["checklisttext"][$lang_code];
	$str_title2 = $str_title."_".$_LANG_TEXT["virustext"][$lang_code]."_".$_LANG_TEXT["checklisttext"][$lang_code];

?>
<!--취약점 점검내역-->
  <Worksheet ss:Name="<?=$str_title1;?>">
    <Table>
	<Column ss:Width='60'/> 
	<Column ss:Width='80'/>
    <Column ss:Width='80'/> 
    <Column ss:Width='80'/> 
    <Column ss:Width='80'/> 
	<Column ss:Width='150'/>
	<Column ss:Width='150'/>
	<Column ss:Width='80'/>
	<Column ss:Width='80'/>
	  
	  <Row>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["numtext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["visitortext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["checkdatetext"][$lang_code];?></Data></Cell>  
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["indatetext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["checkgubuntext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["osndevicetext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["checkitemtext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["checkresulttext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["resolvedresulttext"][$lang_code];?></Data></Cell>
	 </Row>
<?php

if($v_com_seq != ""){
	$search_sql = " AND vcs1.v_user_seq in (SELECT v_user_seq FROM tb_v_user WHERE v_com_seq='{$v_com_seq}') ";
}

if($v_user_seq != ""){
	$search_sql = " AND vcs1.v_user_seq ='{$v_user_seq}' ";
}

$qry_params = array("search_sql"=>$search_sql);
$qry_label = QRY_USER_VCS_WEAK_LIST;
$sql = query($qry_label,$qry_params);
$result = @sqlsrv_query($wvcs_dbcon, $sql,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

if($result){

	$total_cnt = sqlsrv_num_rows($result);

	//echo nl2br($sql);

	$no = $total_cnt;
	while($row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){

		$v_user_name = aes_256_dec($row['v_user_name']);
		$check_date = $row['check_date'];
		$in_date = $row['in_date'];
		$check_type = $row['wvcs_type'];
		$device = $row['os_ver_name'];
		$weakness_name = $row['weakness_name'];

		$str_org_status = $row['org_status']=="SAFE" ? $_LANG_TEXT['safetytext'][$lang_code] : $_LANG_TEXT['weaknessshorttext'][$lang_code];
		$str_fix_status = $row['fix_status']=="SAFE" ? $_LANG_TEXT['safetytext'][$lang_code] : $_LANG_TEXT['weaknessshorttext'][$lang_code];
			
		$excel_data  = '
			<Row> 
				<Cell ss:StyleID="s21"><Data ss:Type="Number">' . $no . '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $v_user_name . '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $check_date . '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $in_date. '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $check_type . '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $device . '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $weakness_name . '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $str_org_status. '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $str_fix_status . '</Data></Cell> 
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
  </Worksheet>  ";

?>
<!--악성코드 점검내역-->
  <Worksheet ss:Name="<?=$str_title2;?>">
    <Table>
	<Column ss:Width='60'/> 
	<Column ss:Width='80'/>
    <Column ss:Width='80'/> 
    <Column ss:Width='80'/> 
    <Column ss:Width='80'/> 
	<Column ss:Width='150'/>
	<Column ss:Width='200'/>
	<Column ss:Width='300'/>
	<Column ss:Width='80'/>
	  
	  <Row>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["numtext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["visitortext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["checkdatetext"][$lang_code];?></Data></Cell>  
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["indatetext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["checkgubuntext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["osndevicetext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["virusnametext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["filepathtext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["transresulttext"][$lang_code];?></Data></Cell>
	 </Row>
<?php

if($v_com_seq != ""){
	$search_sql = " AND vcs1.v_user_seq in (SELECT v_user_seq FROM tb_v_user WHERE v_com_seq='{$v_com_seq}') ";
}

if($v_user_seq != ""){
	$search_sql = " AND vcs1.v_user_seq ='{$v_user_seq}' ";
}

$qry_params = array("search_sql"=>$search_sql);
$qry_label = QRY_USER_VCS_VIRUS_LIST;
$sql = query($qry_label,$qry_params);
$result = @sqlsrv_query($wvcs_dbcon, $sql,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));


if($result){

	$total_cnt = sqlsrv_num_rows($result);

	//echo nl2br($sql);

	$no = $total_cnt;
	while($row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		
		$v_user_name = aes_256_dec($row['v_user_name']);
		$check_date = $row['check_date'];
		$in_date = $row['in_date'];
		$check_type = $row['wvcs_type'];
		$device = $row['os_ver_name'];
		$virus_name = $row['virus_name'];
		$virus_path = $row['virus_path'];
		$virus_status = $row['virus_status'];
			
		$excel_data  = '
			<Row> 
				<Cell ss:StyleID="s21"><Data ss:Type="Number">' . $no . '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $v_user_name . '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $check_date . '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $in_date. '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $check_type . '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $device . '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $virus_name . '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="String">' . $virus_path. '</Data></Cell> 
				<Cell ss:StyleID="s21"><Data ss:Type="Number">' . $virus_status . '</Data></Cell> 
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


sqlsrv_free_stmt($result);  
sqlsrv_close($wvcs_dbcon);

exit;


?>