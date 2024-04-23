<?php
$page_name = "admin_list";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

header( "Content-type: application/vnd.ms-excel; charset=utf-8" ); 
header( "Content-Disposition: attachment; filename=Adminlist_".date("YmdHis").".xls" ); 
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

$admininfotext = $_LANG_TEXT["admininfotext"][$lang_code];

$numtext = $_LANG_TEXT["numtext"][$lang_code];
$empnametext = $_LANG_TEXT["adminnametext"][$lang_code];
$empnotext = $_LANG_TEXT["empnotext"][$lang_code];
$contactphonetext = $_LANG_TEXT["contactphonetext"][$lang_code];
$emailtext = $_LANG_TEXT["emailtext"][$lang_code];
$workplacetext = $_LANG_TEXT["workplacetext"][$lang_code];
$depttext = $_LANG_TEXT["depttext"][$lang_code];
$jobpostext = $_LANG_TEXT["jobpostext"][$lang_code];
$jobdutytext = $_LANG_TEXT["jobdutytext"][$lang_code];
$jobgradetext = $_LANG_TEXT["jobgradetext"][$lang_code];
$workyntext = $_LANG_TEXT["useyesnonntext"][$lang_code];
$adminleveltext = $_LANG_TEXT["adminleveltext"][$lang_code];
$uselangtext = $_LANG_TEXT["uselangtext"][$lang_code];
$registerdatetext = $_LANG_TEXT["registerdatetext"][$lang_code];
$workyestext = $_LANG_TEXT["useyestext"][$lang_code];
$worknotext = $_LANG_TEXT["usenotext"][$lang_code];
$generalusertext = $_LANG_TEXT['generalusertext'][$lang_code];
$koreantext = $_LANG_TEXT["koreantext"][$lang_code];
$englishtext = $_LANG_TEXT["englishtext"][$lang_code];
$japanesetext = $_LANG_TEXT["japanesetext"][$lang_code];
$chinesetext = $_LANG_TEXT["chinesetext"][$lang_code];
?>

  <Worksheet ss:Name="<?php echo $admininfotext; ?>">
    <Table>
	<Column ss:Width='60'/> 
    <Column ss:Width='100'/> 
    <Column ss:Width='100'/> 
    <Column ss:Width='100'/> 
	<Column ss:Width='150'/>
	<Column ss:Width='100'/>
	<Column ss:Width='100'/>
	<Column ss:Width='100'/>
	<Column ss:Width='100'/>
	<Column ss:Width='100'/>
	<Column ss:Width='80'/>
	<Column ss:Width='100'/>
	<Column ss:Width='60'/>
	<Column ss:Width='100'/>
	  
	  <Row>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $numtext; ?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $empnametext; ?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $empnotext; ?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $contactphonetext; ?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $emailtext; ?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $workplacetext; ?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $depttext; ?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $jobpostext; ?></Data></Cell>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $jobdutytext; ?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $jobgradetext; ?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $adminleveltext; ?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $workyntext; ?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $uselangtext; ?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $registerdatetext; ?></Data></Cell> 
	 </Row>
<?php

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');

$searchopt = $_REQUEST[searchopt];	// 검색옵션
$searchkey = $_REQUEST[searchkey];	// 검색어
$orderby = $_REQUEST[orderby];		// 정렬순서
$dept = $_REQUEST[dept];		

//**유지보수 관리자아이디(dptadmin) 숨김처리
$search_sql .= " and A.emp_no != 'dptadmin' ";

if($dept != ""){
	$search_sql .= " and A.dept_seq = '$dept' ";
}

if($searchkey != "" && $searchopt != ""){

  if($searchopt=="EMAIL" || $searchopt=="PHONE_NO" || $searchopt=="EMP_NAME"){

	  if($searchopt=="PHONE_NO"){

		 $searchkey = preg_replace("/[^0-9-]*/s", "", $searchkey); 
	  }

	  if($_encryption_kind=="1"){

		 $search_sql .= " and dbo.fn_DecryptString(".$searchopt.") like '%$searchkey%' ";

	  }else if($_encryption_kind=="2"){

		 $search_sql .= " and $searchopt = '".aes_256_enc($searchkey)."' ";
	  }

  }else{

	$search_sql .= " and $searchopt like '%$searchkey%' ";
  }
}

if($orderby != "") {
	$order_sql = " ORDER BY $orderby ";
} else {
	$order_sql = " ORDER BY emp_seq DESC ";
}

if(COMPANY_CODE=="600"){	//카카오뱅크
	$search_sql .= " AND admin_level in ('SUPER','MAJOR','MAJOR_S')  ";
}else{
	$search_sql .= " AND admin_level > ''  ";
}

$qry_params = array("search_sql"=>$search_sql,"order_sql"=>$order_sql);
$qry_label = QRY_EMP_LIST_EXCEL;
$sql = query($qry_label,$qry_params);

$result = @sqlsrv_query($wvcs_dbcon, $sql,array(), array("Scrollable"=>SQLSRV_CURSOR_KEYSET)); 

if($result){

	$total_cnt = sqlsrv_num_rows($result);

	$NO = $total_cnt;
	while ($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {

		$EMP_NAME = aes_256_dec($row['emp_name']);	
		$EMP_NO = $row['emp_no'];

		if($_encryption_kind=="1"){

			$EMAIL = $row['email_decript'];
			$TEL = $row['phone_no_decript'];
			
		}else if($_encryption_kind=="2"){

			$EMAIL = aes_256_dec($row['email']);
			$TEL = aes_256_dec($row['phone_no']);
		}
		
		$ORGAN = $row['org_name'];
		$DEPT = $row['dept_name'];
		$JOB_POSITION = $row['jpos_name'];
		$JOB_DUTY = $row['jduty_name'];
		$JOB_GRADE = $row['jgrade_name'];
		$WORK_YN = ($row['work_yn']=="Y" ? $workyestext : $worknotext);
		$USER_LEVEL = ($row['admin_level']? $_CODE['admin_level'][$row['admin_level']] : $generalusertext);
		$REGDATE = $row['cr_dt'];

		switch($row['use_lang']){
			case "KR" : $USE_LANG = $koreantext; break;
			case "EN" : $USE_LANG = $englishtext; break;
			case "JP" : $USE_LANG = $japanesetext; break;
			case "CN" : $USE_LANG = $chinesetext; break;
		}
					
			$excel_data  = '
			<Row> 
			<Cell ss:StyleID="s21"><Data ss:Type="Number">' . $NO . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $EMP_NAME . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $EMP_NO. '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $TEL . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $EMAIL . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $ORGAN . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $DEPT . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $JOB_POSITION . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $JOB_DUTY . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $JOB_GRADE . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $USER_LEVEL . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $WORK_YN . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $USE_LANG . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $REGDATE . '</Data></Cell> 
			</Row> 
			';

			echo $excel_data;
			$NO--;
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