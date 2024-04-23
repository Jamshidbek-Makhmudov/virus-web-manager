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
header( "Content-Disposition: attachment; filename=Userlist_".date("YmdHis").".xls" ); 
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

  <Worksheet ss:Name="<?php echo $_LANG_TEXT["visitorinfotext"][$lang_code]; ?>">
    <Table>
	<Column ss:Width='60'/> 
    <Column ss:Width='100'/> 
    <Column ss:Width='150'/> 
	<Column ss:Width='100'/>
    <Column ss:Width='150'/> 
	<Column ss:Width='80'/>
	<Column ss:Width='80'/>
	<Column ss:Width='80'/>
	<Column ss:Width='80'/>
	  
	  <Row>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["numtext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["visitortext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["usercompanynametext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["contactphonetext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["emailtext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["allcheckresulttext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><? echo trsLang('취약점발견','weaknessdetectiontext');?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><? echo trsLang('악성코드발견','virusdetectiontext');?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["suspectforgerytext"][$lang_code];?></Data></Cell> 
	 </Row>
<?php

$v_com_seq = $_REQUEST[v_com_seq];
$searchopt = $_REQUEST[searchopt];	// 검색옵션
$searchkey = $_REQUEST[searchkey];	// 검색어
$orderby = $_REQUEST[orderby];		// 정렬순서
$useyn = $_REQUEST[useyn];


if($useyn=="Y" || $useyn=="N"){

  $search_sql .= " and vu.use_yn = '$useyn' ";
}

if($v_com_seq != ""){

	$search_sql = "and vu.v_com_seq = '{$v_com_seq}' ";
}

if($searchkey != ""){

  if($searchopt=="USER_NAME"){
	
	if($_encryption_kind=="1"){

		 $search_sql .= "and dbo.fn_DecryptString(vu.v_user_name) like '%$searchkey%' ";

	  }else if($_encryption_kind=="2"){

		 $search_sql .= " and vu.v_user_name = '".aes_256_enc($searchkey)."' ";
	  }

  }else if($searchopt=="USER_COM_NAME"){
	
	$search_sql .= " and vc.v_com_name like '%$searchkey%' ";

  }else if($searchopt=="EMAIL"){

	  if($_encryption_kind=="1"){

		 $search_sql .= "and dbo.fn_DecryptString(vu.v_email) like '%$searchkey%' ";

	  }else if($_encryption_kind=="2"){

		 $search_sql .= " and vu.v_email = '".aes_256_enc($searchkey)."' ";
	  }

  }else if($searchopt=="PHONE"){

	  $searchkey = preg_replace("/[^0-9-]*/s", "", $searchkey); 

	  if($_encryption_kind=="1"){

		 $search_sql .= "and dbo.fn_DecryptString(vu.v_phone) like '%$searchkey%' ";

	  }else if($_encryption_kind=="2"){

		 $search_sql .= " and vu.v_phone = '".aes_256_enc($searchkey)."' ";
	  }

  }else if($searchopt=="MANAGER"){

	 if($_encryption_kind=="1"){

		 $search_sql .= "and dbo.fn_DecryptString(vcs.mngr_name) like '%$searchkey%' ";

	  }else if($_encryption_kind=="2"){

		 $search_sql .= " and vcs.mngr_name = '".aes_256_enc($searchkey)."' ";
	  }

  }
}

if($orderby != "") {
	$order_sql = " ORDER BY $orderby";
} else {
	$order_sql = " ORDER BY vu.v_user_seq DESC ";
}

$qry_params = array("search_sql"=> $search_sql,"order_sql"=>$order_sql);
$qry_label = QRY_USER_LIST_EXCEL;
$sql = query($qry_label,$qry_params);
$result = @sqlsrv_query($wvcs_dbcon, $sql,array(), array("Scrollable"=>SQLSRV_CURSOR_KEYSET)); 
$total_cnt = sqlsrv_num_rows($result);


if($result){
	$no = $total_cnt;
	while ($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {

		$v_user_seq = $row['v_user_seq'];
		$com_name = $row['v_com_name'];

		if($_encryption_kind=="1"){
			
			$user_name = $row['v_user_name_decript'];
			$phone_no = $row['v_phone_decript'];
			$email = $row['v_email_decript'];

		}else if($_encryption_kind=="2"){
			
			$user_name = aes_256_dec($row['v_user_name']);
			$phone_no = aes_256_dec($row['v_phone']);
			$email = aes_256_dec($row['v_email']);
		}
		
		
		$vcs_cnt = $row['vcs_cnt'];
		$weak_cnt = $row['weak_cnt'];
		$virus_cnt = $row['virus_cnt'];
		$bad_cnt = $row['bad_cnt'];
					
			$excel_data  = '
			<Row> 
			<Cell ss:StyleID="s21"><Data ss:Type="Number">' . $no . '</Data></Cell> 
			<Cell ss:StyleID="s22"><Data ss:Type="String">' . $user_name . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $com_name. '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $phone_no . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="String">' . $email . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="Number">' . number_format($vcs_cnt) . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="Number">' . number_format($weak_cnt) . '</Data></Cell> 
			<Cell ss:StyleID="s21"><Data ss:Type="Number">' . number_format($virus_cnt) . '</Data></Cell>
			<Cell ss:StyleID="s21"><Data ss:Type="Number">' . number_format($bad_cnt) . '</Data></Cell>  
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