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
header( "Content-Disposition: attachment; filename=AdminLoginLog_".date("YmdHis").".xls" ); 
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
<?php

$adminloginlogtext = $_LANG_TEXT["adminloginlogtext"][$lang_code];

$numtext = $_LANG_TEXT["numtext"][$lang_code];
$empnotext = $_LANG_TEXT["empnotext"][$lang_code];
$empnametext = $_LANG_TEXT["empnametext"][$lang_code];
$organtext = $_LANG_TEXT["organtext"][$lang_code];
$depttext = $_LANG_TEXT["depttext"][$lang_code];
$contactphonetext = $_LANG_TEXT["contactphonetext"][$lang_code];
$emailtext = $_LANG_TEXT["emailtext"][$lang_code];

$ipaddresstext = $_LANG_TEXT["ipaddresstext"][$lang_code];
$logintimetext = $_LANG_TEXT["logintimetext"][$lang_code];
$logouttimetext = $_LANG_TEXT["logouttimetext"][$lang_code];
$loginstatustext = $_LANG_TEXT["loginstatustext"][$lang_code];
$loginidstatus = $_LANG_TEXT["loginidstatus"][$lang_code];
?>
  <Worksheet ss:Name="<?php echo $adminloginlogtext; ?>">
/*  */
<?php 
$_query_enc = $_REQUEST[query_enc];	// 검색옵션
$query = trim(htmlentities(base64_decode($_query_enc),ENT_NOQUOTES));

$check_query = strtr($query,array("\r\n"=>'',"\r"=>'',"\n"=>''));
$check_query = strtolower($check_query);


if(substr($check_query,0,6) !="select" ){
	echo "<div style='text-align:center;margin-top:30px;'>Warning : ".trsLang('데이터조회만허용됩니다.','allowonlyselecttext').".</div>";
	exit;
}

if(strpos($check_query,"*")!==false){
	echo "<div style='text-align:center;margin-top:30px;'>Warning : '*' 문자열은 사용할 수 없습니다.</div>";
	exit;
}

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');


$Model_utils= new Model_Utils();
	$args = array("query"=>$query);

$result =  $Model_utils->getQueryEditorsList($args);
$total =  sqlsrv_num_rows($result);
			$headerPrinted = false;
			$no =$total;


	if ($result) {
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

	$excel_data = "    <table>
	<Column ss:Width='60'/> 
    <Column ss:Width='100'/> 
    <Column ss:Width='100'/> 
    <Column ss:Width='100'/> 
    <Column ss:Width='100'/> 
	<Column ss:Width='100'/>
	<Column ss:Width='150'/>
	<Column ss:Width='100'/>
	<Column ss:Width='100'/>
	<Column ss:Width='100'/>
	<Column ss:Width='100'/>
	<Column ss:Width='100'/>";

	if (!$headerPrinted) {
  	$excel_data .=   '<Row>
								<Cell ss:StyleID="s21"><Data ss:Type="Number">' . trsLang("번호","numtext") . '</Data></Cell>  ';
					foreach ($row as $columnName => $value) {
						$excel_data .= '<Cell ss:StyleID="s21"><Data ss:Type="Number">' . strtolower($columnName) . '</Data></Cell>';
					}

        $excel_data .= ' </Row>';
				$headerPrinted = true;
				}
				$excel_data .= '<Row> ';

				$excel_data .= '<Cell ss:StyleID="s21"><Data ss:Type="Number">' . $no . '</Data></Cell>';

					foreach ($row as $value) {
					if ($value instanceof DateTime) {
						$excel_data .= '<Cell ss:StyleID="s21"><Data ss:Type="Number">' . $value->format('Y-m-d H:i:s') . '</Data></Cell>';
					} else {
						$excel_data .= '<Cell ss:StyleID="s21"><Data ss:Type="Number">' . $value . '</Data></Cell>';
					}
				}
			  $excel_data .= '</Row> ';
					echo $excel_data;
				$no--;


	 }// while



  echo  '</table>';
	$start = $start + RECORD_LIMIT_PER_FILE;
				if ($result) sqlsrv_free_stmt($result);
			sqlsrv_close($wvcs_dbcon);	
	}
?>
/*  */

<?php


	
		

echo "<WorksheetOptions xmlns='urn:schemas-microsoft-com:office:excel'> 
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
