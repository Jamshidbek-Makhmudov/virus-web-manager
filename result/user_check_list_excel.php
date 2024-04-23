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
header( "Content-Disposition: attachment; filename=User_VCS_list_".date("YmdHis").".xls" ); 
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
      <Font ss:FontName='����' x:CharSet='129' x:Family='Modern' ss:Size='10' ss:Bold="1" /> 
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
     <Font ss:FontName='����' x:CharSet='129' x:Family='Modern' ss:Size='9' /> 
	 <Alignment ss:Horizontal='Center' ss:Vertical='Center' ss:WrapText='1'/>
	 <Borders> 
		<Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1'/> 
		<Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1'/> 
		<Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1'/> 
		<Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1'/> 
	  </Borders> 
    </Style>
	<Style ss:ID="s22">
     <Font ss:FontName='����' x:CharSet='129' x:Family='Modern'  ss:Color='#FF0000' ss:Size='9' /> 
	 <Alignment ss:Horizontal='Center' ss:Vertical='Center' ss:WrapText='1'/>
	 <Borders> 
		<Border ss:Position='Bottom' ss:LineStyle='Continuous' ss:Weight='1'/> 
		<Border ss:Position='Left' ss:LineStyle='Continuous' ss:Weight='1'/> 
		<Border ss:Position='Right' ss:LineStyle='Continuous' ss:Weight='1'/> 
		<Border ss:Position='Top' ss:LineStyle='Continuous' ss:Weight='1'/> 
	  </Borders> 
	</Style>
	<Style ss:ID="s23">
     <Font ss:FontName='����' x:CharSet='129' x:Family='Modern'  ss:Color='#0000FF' ss:Size='9' /> 
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
	$v_asset_type = $_REQUEST[v_asset_type];
	$v_notebook_key = $_REQUEST[v_notebook_key];
	$storage_device_type = $_REQUEST[storage_device_type];
	$check_result1 = $_REQUEST[check_result1];
	$check_result2 = $_REQUEST[check_result2];
	$orderby = $_REQUEST[orderby];	

	$proc_name = $_REQUEST[proc_name];
	$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');

	if($src=="COM_INFO_VIEW"){

		$qry_params = array("com_seq"=>$v_com_seq);
		$qry_label = QRY_USER_COM_INFO;
		$sql = query($qry_label,$qry_params);
		$result = sqlsrv_query($wvcs_dbcon, $sql);
		$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

		$str_title = $row['v_com_name']." ".$_LANG_TEXT["checklisttext"][$lang_code];;

	}else if($src=="USER_VCS_LOG" || $src=="RESULT_VIEW" || $src=="USER_INFO_VIEW"){

		$qry_params = array("v_user_seq"=>$v_user_seq);
		$qry_label = QRY_USER_INFO;
		$sql = query($qry_label,$qry_params);
		$result = sqlsrv_query($wvcs_dbcon, $sql);
		$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

		if($_encryption_kind=="1"){

			$phone_no = $row['v_phone_decript'];
			$email = $row['v_email_decript'];

		}else if($_encryption_kind=="2"){

			$phone_no = aes_256_dec($row['v_phone']);
			$email = aes_256_dec($row['v_email']);
		}

		if($_cfg_user_identity_name=="phone"){
			$v_user_name= $phone_no;
		}else if($_cfg_user_identity_name=="email"){
			$v_user_name= $email;
		}else{
			$v_user_name= aes_256_dec($row['v_user_name']);
		}
		
		if($src=="USER_VCS_LOG"){

			$str_title = $v_user_name." ".$_CODE['asset_type'][$v_asset_type]." ".$_LANG_TEXT["checklogtext"][$lang_code];

		}else{

			$str_title = $v_user_name." ".$_LANG_TEXT["checklisttext"][$lang_code];
		}

	}
	
?>
  <Worksheet ss:Name="<?=$str_title;?>">
    <Table>
	<Column ss:Width='60'/> 
    <Column ss:Width='100'/> 
    <Column <? echo $xls_cfg_in_available_dt;?>/> 
    <Column ss:Width='100'/> 
    <Column ss:Width='100'/>
	<Column ss:Width='150'/>
	<Column ss:Width='100'/>
	<Column ss:Width='100'/>
	<Column ss:Width='100'/>
	<Column ss:Width='150'/>
	<Column ss:Width='150'/>
	<Column ss:Width='100'/>
	<Column ss:Width='100'/>
	<Column ss:Width='100'/>
	<Column ss:Width='100'/>
	<Column ss:Width='100'/>
	<Column ss:Width='100'/>
	<Column ss:Width='100'/>
	<Column ss:Width='100'/>
	<Column ss:Width='100'/>
	  
	  <Row>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["numtext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["checkdatetext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["inlimitdatetext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["indatetext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["outdatetext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["scancentertext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["checkgubuntext"][$lang_code];?></Data></Cell>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["devicegubuntext"][$lang_code];?></Data></Cell>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["checkdiskcounttext"][$lang_code];?></Data></Cell>    
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["osndevicetext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["serialnumbertext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["visitortext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["progressstatustext"][$lang_code];?></Data></Cell> 
		<?if(in_array("WEAK",$_CODE_INSPECT_OPTION)){?>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["weaknesstext"][$lang_code];?></Data></Cell> 
		<?}?>
		<?if(in_array("VIRUS",$_CODE_INSPECT_OPTION)){?>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["virustext"][$lang_code];?></Data></Cell>
		<?}?>
		<?if(in_array("BAD_EXT",$_CODE_INSPECT_OPTION)){?>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["suspectforgerytext"][$lang_code];?></Data></Cell>
		<?}?>
		<?if($_P_CHECK_FILE_SEND_TYPE !="N"){?>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["importfilecount"][$lang_code];?></Data></Cell>
		<?}?>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["checkresulttext"][$lang_code];?></Data></Cell>  
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["executives"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["employee_affiliation"][$lang_code];?></Data></Cell> 
	 </Row>
<?php

if($v_com_seq != ""){

	$search_sql = " AND us.v_com_seq = '".$v_com_seq."' ";
}

if($v_user_seq !=""){

	$search_sql = " AND us.v_user_seq = '".$v_user_seq."' ";
}

if($v_asset_type !=""){

	$search_sql .= " AND vcs.v_asset_type = '".$v_asset_type."' ";
}

if($v_asset_type =="NOTEBOOK"){
	
	if($v_notebook_key != ""){
		$search_sql .= " AND vcs.v_notebook_key = '".$v_notebook_key."' ";
	}
}

if($storage_device_type != ""){

	$search_sql .= " AND vcs.v_asset_type = 'RemovableDevice' ";


  if($storage_device_type=='DEVICE_ETC'){

	 $search_sql .= " AND exists (select value from dbo.fn_split(vcd.os_ver_name,',') WHERE value not in ('Removable','HDD') and value > '' ) ";

  }else{

	$search_sql .=  " AND CHARINDEX('".$storage_device_type."',isnull(vcd.os_ver_name,'')) > 0 ";
  }
}

if($check_result2=="weak"){

	$search_sql .= " and exists (SELECT TOP 1 weakness_seq FROM tb_v_wvcs_weakness WHERE vcs.v_wvcs_seq = v_wvcs_seq ) ";

}else if($check_result2=="virus"){

	$search_sql .= " and exists (
							SELECT TOP 1 vcc.vaccine_seq 
							FROM tb_v_wvcs_vaccine vcc
								INNER JOIN tb_v_wvcs_vaccine_detail vccd
									ON vcc.vaccine_seq = vccd.vaccine_seq
							WHERE vcs.v_wvcs_seq = v_wvcs_seq ) ";
}

if($order_sql==""){

	$order_sql = " ORDER BY vcs.v_wvcs_seq DESC ";
}

$qry_params = array(
	"search_sql"=> $search_sql,
	"order_sql"=> $order_sql
);

$qry_label = QRY_RESULT_CHECK_LIST_EXCEL;
$sql = query($qry_label,$qry_params);
$result = @sqlsrv_query($wvcs_dbcon, $sql,array(), array("Scrollable"=>SQLSRV_CURSOR_KEYSET)); 

if($result){

	$total_cnt = @sqlsrv_num_rows($result);

	//echo $sql;

	$no = $total_cnt;
	while ($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {

		$check_date = $row['check_date'];
		$in_available_date  = $row['checkin_available_dt'];
		if($in_available_date){
			
			$hour = substr($in_available_date,8,2);
			$min = substr($in_available_date,10,2);

			$in_available_date = substr($in_available_date,0,4)."-".substr($in_available_date,4,2)."-".substr($in_available_date,6,2);
			
			$in_available_date = $in_available_date." ".($hour? $hour : "00").":".($min? $min : "00");
		}
		$in_date	= $row['in_date'];
		$out_date	= $row['out_date'];
		
		$v_org_name = $row['org_name'];
		$scan_center_name = $row['org_name']." ".$row['scan_center_name'];
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
		$file_bad_cnt = $row['file_bad_cnt'];
		$wvcs_authorize_yn = $row['wvcs_authorize_yn'];
		$wvcs_authorize_name = aes_256_dec($row['wvcs_authorize_name']);
		$vacc_scan_count = $row['vacc_scan_count'];


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
		
		$mngr_name = aes_256_dec($row['mngr_name']);
		$mngr_department = $row['mngr_department'];

		$mngr = $mngr_name.($mngr_department? " / " :"").$mngr_department;

		$vcs_status = $_CODE['vcs_status'][$row['vcs_status']];

		$check_result = "";

		if($weak_cnt > 0){
			$check_result = $_LANG_TEXT["weaknessshorttext"][$lang_code];
		}

		//echo $virus_cnt;

		if($virus_cnt > 0){

			$check_result .= ($check_result ? ",":"").$_LANG_TEXT["virusshorttext"][$lang_code];
		}

		if($weak_cnt+$virus_cnt ==0){

			$check_result .= $_LANG_TEXT["safetytext"][$lang_code];
		}

		$disk_cnt = $row['disk_cnt'];
		$import_file_cnt = $row['import_file_cnt'];
			
		$excel_data  = '
		<Row> 
		<Cell ss:StyleID="s21"><Data ss:Type="Number">' . $no . '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $check_date . '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $in_available_date . '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $in_date. '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $out_date. '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $scan_center_name . '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $check_type . '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $_CODE['asset_type'][$v_asset_type] . '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="Number">' . $disk_cnt . '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $os . '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $sys_sn . '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $vv_user_name . '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $vcs_status . '</Data></Cell>';
		
		if(in_array("WEAK",$_CODE_INSPECT_OPTION)){
		$excel_data .='
		<Cell ss:StyleID="s21"><Data ss:Type="Number">' . $weak_cnt . '</Data></Cell> ';
		}
		
		if(in_array("VIRUS",$_CODE_INSPECT_OPTION)){
		$excel_data .='
		<Cell ss:StyleID="s21"><Data ss:Type="Number">' . $virus_cnt . '</Data></Cell>';
		}
		
		if(in_array("BAD_EXT",$_CODE_INSPECT_OPTION)){
		$excel_data .='
		<Cell ss:StyleID="s21"><Data ss:Type="Number">' . $file_bad_cnt . '</Data></Cell>';
		}


		if($_P_CHECK_FILE_SEND_TYPE !="N"){
		$excel_data  .= '
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . number_format($import_file_cnt) . '</Data></Cell>  ';
		}

		$excel_data  .= '
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $check_result . '</Data></Cell>  
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $mngr_name . '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $mngr_department . '</Data></Cell> 
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