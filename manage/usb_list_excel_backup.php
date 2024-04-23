<?php
$page_name = "usb_list";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = $_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');



header( "Content-type: application/vnd.ms-excel; charset=utf-8" ); 
header( "Content-Disposition: attachment; filename=signature_data_".date("Ymdhis").".xls" ); 
header( "Content-Description: PHP5 Generated Data" ); 
header("Expires: 0"); 
Header("Content-Transfer-Encoding: binary"); 
header("Cache-Control: must-revalidate, post-check=0,pre-check=0"); 
header("Pragma: public");


echo "<?xml version='1.0' encoding='UTF-8'?>";

?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office"
	xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
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

	<?php
//시그니처맵핑 
$signaturemapping = $_LANG_TEXT['signaturemapping'][$lang_code];
//시그니처맵핑정보
$signaturemappinginfonntext = $_LANG_TEXT['signaturemappinginfonntext'][$lang_code];
//전체
$totallist = $_LANG_TEXT['totallist'][$lang_code];
//확장자
$file_exttext = $_LANG_TEXT['file_ext'][$lang_code];
//검색대상 포함
$searchtargetinnntext = $_LANG_TEXT['searchtargetinnntext'][$lang_code];
//검색대상 제외
$searchtargetoutnntext = $_LANG_TEXT['searchtargetoutnntext'][$lang_code];
//시그니처여부 포함
$signatureinnntext = $_LANG_TEXT['alllistview'][$lang_code];
//시그니처여부 제외
$signatureoutnntext = $_LANG_TEXT['alllistview'][$lang_code];
//시그니처목록
$filesignaturelistnntext = $_LANG_TEXT['filesignaturelistnntext'][$lang_code];
//검색대상
$searchtargetnntext = $_LANG_TEXT['searchtargetnntext'][$lang_code];
//시그니처분석
$signaturestat = $_LANG_TEXT['signaturestat'][$lang_code];
//삭제
$deletedeletetext = $_LANG_TEXT['deletedeletetext'][$lang_code];
//포함 
$inclusionnntext = $_LANG_TEXT['inclusionnntext'][$lang_code];
//제외
$exclusionnntext = $_LANG_TEXT['exclusionnntext'][$lang_code];
//생성일자 
$createdatetext = $_LANG_TEXT['createdatetext'][$lang_code];
?>
	<Worksheet ss:Name="<?php echo $signaturemappinginfonntext; ?>">
		<Table>
			<Column ss:Width='50' />
			<Column ss:Width='100' />
			<Column ss:Width='100' />
			<Column ss:Width='160' />
			/* <Column ss:Width='100' />
			<Column ss:Width='100' /> */
			<Column ss:Width='140' />
			<Row>
				<Cell ss:StyleID="s20"><Data ss:Type="String">No</Data></Cell>
				<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $file_exttext; ?></Data></Cell>
				<Cell ss:StyleID="s20"><Data ss:Type="String">File ID</Data></Cell>
				<Cell ss:StyleID="s20"><Data ss:Type="String">ID NAME</Data></Cell>
				/* move here */

				<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $createdatetext; ?></Data></Cell>
			</Row>

			<?php



	
	$sql = "SELECT * FROM tb_signature_map ORDER BY file_id ASC";

	$result = sqlsrv_query($wvcs_dbcon, $sql);
	$i = 0;
	$j = 1;
	while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		
		
		$create_date =$row['create_date'];
		$formatted_date = date("Y-m-d H:i", strtotime($create_date));
		//$create_date = getDefineDateFormat($row['create_date']); //org
		
		$ext_name = trim(strtoupper($row['ext_name']));
		$ext_name1 = $ext_name;
		
		
		$file_id = $row['file_id'];

		$str_name = $row['str_name'];


		$excel_data  = '
		<Row> 
		<Cell ss:StyleID="s21"><Data ss:Type="Number">' . $j . '</Data></Cell> ';

		if($i != 0 && $ext_name_temp == $ext_name1) {
			$excel_data  .= '
			<Cell ss:StyleID="s22"><Data ss:Type="String">' . $ext_name . '</Data></Cell>  ';
		} else {
			$excel_data  .= '
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $ext_name . '</Data></Cell>  ';
		}

		$excel_data  .= '
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $file_id . '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $str_name . '</Data></Cell> ';

		$excel_data  .= '
		<Cell ss:StyleID="s22"><Data ss:Type="String">' . $formatted_date . '</Data></Cell> 
		</Row> 
		';

		echo $excel_data;

		$ext_name_temp = $ext_name1;

		$j++;
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