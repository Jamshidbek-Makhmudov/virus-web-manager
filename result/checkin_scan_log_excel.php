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
header( "Content-Disposition: attachment; filename=CheckinBarcodeScanLog_".date("YmdHis").".xls" ); 
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
<?php

$title = "Barcode Scan Log";

$numtext = $_LANG_TEXT["numtext"][$lang_code];
$barcodescanemptext = $_LANG_TEXT["barcodescanemptext"][$lang_code];
$barcodescandatetext = $_LANG_TEXT["barcodescandatetext"][$lang_code];
$scanresulttext = $_LANG_TEXT["scanresulttext"][$lang_code];
$visitortext = $_LANG_TEXT["visitortext"][$lang_code];
$checkdatetext = $_LANG_TEXT["checkdatetext"][$lang_code];
$scancentertext = $_LANG_TEXT["scancentertext"][$lang_code];
$checkgubuntext = $_LANG_TEXT["checkgubuntext"][$lang_code];
$devicegubuntext = $_LANG_TEXT["devicegubuntext"][$lang_code];
$osndevicetext = $_LANG_TEXT["osndevicetext"][$lang_code];
$barcodetext = "Barcode";
?>

  <Worksheet ss:Name="<?php echo $title; ?>">
    <Table>
	<Column ss:Width='60'/> 
    <Column ss:Width='150'/> 
    <Column ss:Width='150'/> 
    <Column ss:Width='80'/> 
    <Column ss:Width='100'/> 
    <Column ss:Width='100'/> 
    <Column ss:Width='100'/> 
    <Column ss:Width='150'/> 
    <Column ss:Width='80'/> 
    <Column ss:Width='150'/> 
    <Column ss:Width='300'/>
	
	  <Row>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $numtext; ?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $barcodetext; ?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $visitortext; ?></Data></Cell>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $checkdatetext; ?></Data></Cell>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $scancentertext; ?></Data></Cell>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $checkgubuntext; ?></Data></Cell>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $devicegubuntext; ?></Data></Cell>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $osndevicetext; ?></Data></Cell>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $barcodescanemptext; ?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $barcodescandatetext; ?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?php echo $scanresulttext; ?></Data></Cell>
	 </Row>
<?php

$searchopt = $_REQUEST[searchopt];	// 검색옵션
$searchkey = $_REQUEST[searchkey];	// 검색어
$orderby = $_REQUEST[orderby];		// 정렬순서
$start_date = $_REQUEST[start_date];	
$end_date = $_REQUEST[end_date];

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');


if($start_date != "" && $end_date != ""){
		$search_sql .= " AND lg.create_dt between '$start_date 00:00:00.000' and '$end_date 23:59:59.999' ";
}
  
if($searchkey != ""){

	if($searchopt=="EMP_NAME"){

		$search_sql .= " and emp_name = '".aes_256_enc($searchkey)."' ";

	}else if($searchopt == "BARCODE"){

		$search_sql .= " and lg.barcode like '%$searchkey%' ";

	}else if($searchopt == "USER"){

		$search_sql .= " and us.v_user_name = '".aes_256_enc($searchkey)."' ";

	}
}

if($orderby != "") {
	$order_sql = " ORDER BY $orderby";
} else {
	$order_sql = " ORDER BY scan_log_seq DESC ";
}



$qry_params = $qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start);
$qry_label = QRY_VCS_SCANLOG_LIST_EXCEL;
$sql = query($qry_label,$qry_params);

//echo $sql;

$result = @sqlsrv_query($wvcs_dbcon, $sql,array(), array("Scrollable"=>SQLSRV_CURSOR_KEYSET));  

if($result){

	$total_count = sqlsrv_num_rows($result);

	$NO = $total_count;
	while ($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {

		$emp_name = aes_256_dec($row['emp_name']);
		$barcode = $row['barcode'];
		$scan_log_seq = $row['scan_log_seq'];
		$create_dt = $row['create_dt'];
		$scan_result_msg = $row['scan_result_msg'];
		$v_wvcs_seq = $row['v_wvcs_seq'];

		$check_date = $row['check_date'];
		$in_available_date  = $row['checkin_available_dt'];
		if($in_available_date){
			$in_available_date = substr($in_available_date,0,4)."-".substr($in_available_date,4,2)."-".substr($in_available_date,6,2);
		}
		$in_date	= $row['in_date'];
		
		$v_scan_center_name = $row['org_name']." ".$row['scan_center_name'];
		$v_asset_type = $row['v_asset_type'];
		$sys_sn = $row['v_sys_sn'];
		$hdd_sn = $row['v_hdd_sn'];
		$board_sn = $row['v_board_sn'];
		$v_notebook_key = $row['v_notebook_key'];
		$os = $row['os_ver_name'];
		$maker = $row['v_manufacturer'];
		$mngr_dept = $row['mngr_department'];
		$mngr_name = aes_256_dec($row['mngr_name']);
		$vv_user_name = aes_256_dec($row['v_user_name']);
		$v_com_name = $row['v_com_name'];
		$vv_user_sq = $row['v_user_seq'];
		$weak_cnt = $row['weak_cnt'];
		$virus_cnt = $row['virus_cnt'];
		$wvcs_authorize_yn = $row['wvcs_authorize_yn'];

		$check_type = $row['wvcs_type'];

		if($_encryption_kind=="1"){

			$phone_no = $row['v_phone_decript'];
			$email = $row['v_email_decript'];

		}else if($_encryption_kind=="2"){

			$phone_no = aes_256_dec($row['v_phone']);
			$email = aes_256_dec($row['v_email']);
		}

		if($_cfg_user_identity_name=="phone"){
			$user_name_com = $phone_no;
			$vv_user_name= $phone_no;
		}else if($_cfg_user_identity_name=="email"){
			$user_name_com =$email;
			$vv_user_name= $email;
		}else{
			if($v_com_name=="-") $v_com_name="";
			$user_name_com = $vv_user_name.($v_com_name? "/" : "").$v_com_name;
		}

		$mngr = aes_256_dec($row['mngr_name']).($row['mngr_department']? " / " :"").$row['mngr_department'];


		if($wvcs_authorize_yn=="Y"){
			$vcs_status = $_LANG_TEXT["incompletetext"][$lang_code];
		}else{
			$vcs_status = $_LANG_TEXT["needchecktext"][$lang_code];
		}
		
			
			$excel_data  = '
			<Row> 
			<Cell ss:StyleID="s21"><Data ss:Type="Number">' . $NO . '</Data></Cell> 
			<Cell ss:StyleID="s22"><Data ss:Type="Number">' . $barcode . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $user_name_com. '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $check_date . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $v_scan_center_name . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $check_type. '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $_CODE['asset_type'][$v_asset_type]. '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $os . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $emp_name. '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $create_dt . '</Data></Cell> 
			<Cell ss:StyleID="s24"><Data ss:Type="String">' . $scan_result_msg . '</Data></Cell> 
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