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
header( "Content-Disposition: attachment; filename=User_File_list_".date("YmdHis").".xls" ); 
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
	<Style ss:ID="s24">
     <Font ss:FontName='굴림' x:CharSet='129' x:Family='Modern' ss:Size='9' /> 
	 <Alignment ss:Horizontal='Left' ss:Vertical='Center' ss:WrapText='1'/>
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
	$v_wvcs_seq = $_REQUEST[v_wvcs_seq];
	$orderby = $_REQUEST[orderby];	
	
	$qry_params = array("v_wvcs_seq"=>$v_wvcs_seq);
	$qry_label = QRY_GET_VCS_USER_INFO;
	$sql = query($qry_label,$qry_params);
	$result = sqlsrv_query($wvcs_dbcon, $sql);
	$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
	
	if($src=="USER_FILE_LIST"){

		$str_title = aes_256_dec($row['v_user_name'])." ".$_LANG_TEXT["importfilehistory"][$lang_code];

	}else{

		$str_title = $_LANG_TEXT["importfilehistory"][$lang_code];
	}

	
?>
  <Worksheet ss:Name="<?=$str_title;?>">
    <Table>
	<Column ss:Width='60'/> 
    <Column ss:Width='100'/> 
    <Column ss:Width='100'/>
    <Column ss:Width='200'/>
	<? if($src=="ALL_FILE_LIST"){?>
    <Column ss:Width='100'/> 
    <Column ss:Width='100'/> 
	<?}?>
    <Column ss:Width='500'/> 
    <Column ss:Width='100'/>
	<Column ss:Width='100'/>
	
	  
	  <Row>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><? echo trsLang('번호','numtext');?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><? echo trsLang('검사일','checkdatetext');?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["osndevicetext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String">S/N</Data></Cell> 
		<? if($src=="ALL_FILE_LIST"){?>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><? echo trsLang('방문자','visitortext');?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><? echo trsLang('회사명','companynametext');?></Data></Cell> 
		<?}?>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><? echo trsLang('반입파일','importfiletext');?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><? echo trsLang('파일크기','filesizetext');?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><? echo trsLang('반입일시','importdatetimetext');?></Data></Cell> 
	 </Row>
<?php

if($v_wvc_seq != ""){

	$search_sql = " AND vcs.v_com_seq = '".$v_wvc_seq."' ";
}

if($order_sql==""){

	$order_sql = " ORDER BY vcs.v_wvcs_seq DESC ";
}

$qry_params = array(
	"search_sql"=> $search_sql,
	"order_sql"=> $order_sql
);

$qry_label = QRY_RESULT_FILE_LIST_EXCEL;
$sql = query($qry_label,$qry_params);
$result = @sqlsrv_query($wvcs_dbcon, $sql,array(), array("Scrollable"=>SQLSRV_CURSOR_KEYSET)); 

if($result){

	$total_cnt = @sqlsrv_num_rows($result);

	//echo $sql;

	$no = $total_cnt;
	while ($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
		
		$check_date = $row['check_date'];
		$sys_sn = $row['v_sys_sn'];
		$os = $row['os_ver_name'];
		$v_user_name = aes_256_dec($row['v_user_name']);
		$v_com_name = $row['v_com_name'];
		$file_path = $row['file_path'];
		$file_size = $row['file_size'];
		$str_file_size  = getSizeCheck($file_size);
		$create_date  = $row['create_date'];
		$str_create_date = getDefineDateFormatDotShort($create_date);
			
		$excel_data  = '
		<Row> 
		<Cell ss:StyleID="s21"><Data ss:Type="Number">' . $no . '</Data></Cell>
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $check_date . '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $os . '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $sys_sn . '</Data></Cell>  ';

		 if($src=="ALL_FILE_LIST"){
			 $excel_data  .= '
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $v_user_name . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $v_com_name . '</Data></Cell> ';
		 }

		 $excel_data  .= '
		<Cell ss:StyleID="s24"><Data ss:Type="String">' . $file_path. '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $str_file_size. '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $str_create_date . '</Data></Cell> 
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