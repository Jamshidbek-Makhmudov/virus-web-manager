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
    <Table>
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
	<Column ss:Width='100'/>
	
	  <Row>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $numtext; ?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $logintimetext; ?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $logouttimetext; ?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $empnametext; ?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $empnotext; ?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $organtext; ?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $depttext; ?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $contactphonetext; ?></Data></Cell>  
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $emailtext; ?></Data></Cell>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $ipaddresstext; ?></Data></Cell>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $loginstatustext; ?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $loginidstatus; ?></Data></Cell> 
	 </Row>
<?php

$searchopt = $_REQUEST[searchopt];	// 검색옵션
$searchkey = $_REQUEST[searchkey];	// 검색어
$orderby = $_REQUEST[orderby];		// 정렬순서
$start_date = $_REQUEST[start_date];	
$end_date = $_REQUEST[end_date];

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');

//**유지보수 관리자아이디(dptadmin) 숨김처리
$search_sql .= " and emp_no != 'dptadmin' ";

if($start_date != "" && $end_date != ""){
	$search_sql .= " AND login_dt between '$start_date 00:00:00.000' and '$end_date 23:59:59.999' ";
}
  
if($searchkey != ""){

	if($searchopt=="EMP_NAME"){

	$search_sql .= " and emp_name = '".aes_256_enc($searchkey)."' ";

	}else if($searchopt == "EMP_NO"){

	$search_sql .= " and emp_no like '%$searchkey%' ";

	}else if($searchopt == "IP"){

	$search_sql .= " and ip_addr like '%$searchkey%' ";

	}
}

if($orderby != "") {
	$order_sql = " ORDER BY $orderby";
} else {
	$order_sql = " ORDER BY login_seq DESC ";
}



$qry_params = array("search_sql"=>$search_sql,"order_sql"=>$order_sql);
$qry_label = QRY_USER_LOGINLOG_EXCEL;
$sql = query($qry_label,$qry_params);

$result = @sqlsrv_query($wvcs_dbcon, $sql,array(), array("Scrollable"=>SQLSRV_CURSOR_KEYSET));  

if($result){

	$total_count = sqlsrv_num_rows($result);

	$NO = $total_count;
	while ($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {

		$EMP_NAME = aes_256_dec($row['emp_name']);
		$EMP_NO = $row['emp_no'];
		$ORGAN = $row['org_name'];
		$DEPT = $row['dept_name'];
		$EMAIL = aes_256_dec($row['email']);
		$TEL = aes_256_dec($row['phone_no']);
		
		$IP_ADDR = $row['ip_addr'];
		$LOGIN_DT = $row['login_dt'];
		$LOGOUT_DT = $row['logout_dt'];

		$login_yn = $row['LOGIN_YN'];
		$login_fail_cnt = $row['LOGIN_FAIL_CNT'];
		$login_lock_yn = $row['LOGIN_LOCK_YN'];
		$login_lock_type = $row['LOGIN_LOCK_TYPE'];

		if($login_yn=="N"){
			if($login_fail_cnt=="") $login_fail_cnt = "1";
			$str_login_yn = $_LANG_TEXT['procfail'][$lang_code]."(".$login_fail_cnt.trim($_LANG_TEXT['timestext'][$lang_code]).")";
		}else{
			$str_login_yn = $_LANG_TEXT['procsuccess'][$lang_code];
		}

		if($login_lock_type=="LOGIN_ATTEMPT_OVER"){
			$str_login_lock_type = $_LANG_TEXT['loginattemptexceed'][$lang_code];
		}else{
			$str_login_lock_type = "";
		}

		if($login_lock_yn=="Y"){
			$str_lock_yn = $_LANG_TEXT['loginlock'][$lang_code];
		}else{
			$str_lock_yn = $_LANG_TEXT['normal'][$lang_code];
		}
		
			
			$excel_data  = '
			<Row> 
			<Cell ss:StyleID="s21"><Data ss:Type="Number">' . $NO . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $LOGIN_DT . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $LOGOUT_DT . '</Data></Cell> 
			<Cell ss:StyleID="s22"><Data ss:Type="String">' . $EMP_NAME . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $EMP_NO. '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $ORGAN . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $DEPT . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $TEL . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $EMAIL . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $IP_ADDR . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $str_login_yn . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $str_lock_yn . '</Data></Cell> 
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