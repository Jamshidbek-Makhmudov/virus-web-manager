<?php
$page_name = "result_list";

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";


header( "Content-type: application/vnd.ms-excel; charset=utf-8" ); 
header( "Content-Disposition: attachment; filename=VCS_list_".date("YmdHis").".xls" ); 
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
	$searchopt = $_REQUEST[searchopt];	// �˻��ɼ�
	$searchkey = $_REQUEST[searchkey];	// �˻���
	$asset_type = $_REQUEST[asset_type];
	$storage_device_type = $_REQUEST[storage_device_type];
	$vcs_type = $_REQUEST[vcs_type];
	$scan_center_code = $_REQUEST[scan_center_code];
	$check_result1 = $_REQUEST[check_result1];
	$check_result2 = $_REQUEST[check_result2];
	$checkdate1 = $_REQUEST[checkdate1];
	$checkdate2 = $_REQUEST[checkdate2];
	$io_gubun = $_REQUEST[io_gubun];
	$iodate1 = $_REQUEST[iodate1];
	$iodate2 = $_REQUEST[iodate2];
	$status = $_REQUEST[status];
	$org_name = $_REQUEST[org_name];
	$v_user_seq = $_REQUEST[v_user_seq];
	$v_user_name = $_REQUEST[v_user_name];
	$v_sys_sn = $_REQUEST[v_sys_sn];
	$orderby = $_REQUEST[orderby];		

	$proc_name = $_REQUEST[proc_name];
	$work_log_seq = WriteAdminActLog($proc_name,'DOWNLOAD');

	if($check_result1 == "") $check_result1 = "last";
?>
  <Worksheet ss:Name="<?=$_LANG_TEXT["checkresulttext"][$lang_code];?>">
    <Table>
	<Column ss:Width='60'/> 
    <Column ss:Width='100'/> 
    <Column ss:Width='100'/>
    <Column ss:Width='100'/> 
    <Column  <? echo $xls_cfg_in_available_dt;?>/>  
	<Column  <? echo $xls_cfg_inout_info;?>/>
	<Column  <? echo $xls_cfg_inout_info;?>/>
	<Column ss:Width='100'/>
	<Column ss:Width='100'/>
	<Column ss:Width='150'/>
	<Column ss:Width='150'/>
	<Column ss:Width='150'/>
	<Column ss:Width='100'/>
	<?if(in_array("WEAK",$_CODE_INSPECT_OPTION)){?>
	<Column ss:Width='100'/>
	<?}?>
	<?if(in_array("VIRUS",$_CODE_INSPECT_OPTION)){?>
	<Column ss:Width='100'/>
	<?}?>
	<?if(in_array("BAD_EXT",$_CODE_INSPECT_OPTION)){?>
	<Column ss:Width='100'/>
	<?}?>
	<Column ss:Width='100'/>
	<Column ss:Width='100'/>
	<Column ss:Width='100'/>
	<Column ss:Width='100'/>
	<Column ss:Width='100'/>
	<Column ss:Width='100'/>
	<Column  <? echo $xls_cfg_check_type;?>/>
	  
	  <Row>
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["numtext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["visitortext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["belongtext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["checkdatetext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["inlimitdatetext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["indatetext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["outdatetext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["scancentertext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["devicegubuntext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["checkdiskcounttext"][$lang_code];?></Data></Cell>   
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["osndevicetext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["serialnumbertext"][$lang_code];?></Data></Cell> 
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
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["checkapprovertext"][$lang_code];?></Data></Cell>  
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["executives"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["employee_affiliation"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["organtext"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["scanfilecount"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["importfilecount"][$lang_code];?></Data></Cell> 
		<Cell ss:StyleID="s20"><Data ss:Type="String"><?=$_LANG_TEXT["checkgubuntext"][$lang_code];?></Data></Cell>
	 </Row>
<?php

$search_sql = "";

if($asset_type != ""){

	$search_sql .=  " AND vcs.v_asset_type = '".$asset_type."' ";
}

if($storage_device_type != ""){

  if($storage_device_type=='DEVICE_ETC'){

	  $search_sql .= " AND exists (select value from dbo.fn_split(vcd.os_ver_name,',') WHERE value not in ('Removable','HDD','CD/DVD') and value > '' ) ";

  }else{

	$search_sql .=  " AND CHARINDEX('".$storage_device_type."',vcd.os_ver_name) > 0 ";
  }

}


if($vcs_type != ""){

   $search_sql .=  " AND vcs.wvcs_type = '".$vcs_type."' ";
}

if($scan_center_code != ""){

   $search_sql .=  " AND vcs.scan_center_code = '".$scan_center_code."' ";
}


if($checkdate1 != "" && $checkdate2 !=""){

	$search_sql .= " AND vcs.wvcs_dt between '$checkdate1 00:00:00.000' and '$checkdate2 23:59:59.999' ";
}


if($io_gubun=="indate" && $iodate1 !="" && $iodate2 != ""){	

	$search_sql .= " AND vcs.wvcs_authorize_dt between '$iodate1 00:00:00.000' and '$iodate2 23:59:59.999' ";
}

if($io_gubun=="outdate" && $iodate1 !="" && $iodate2 != ""){	

	$search_sql .= " AND vcs.checkout_dt between '$iodate1 00:00:00.000' and '$iodate2 23:59:59.999' ";
}

if($status !=""){

	if($status=="CHECK"){

		$search_sql .= " and (vcs.wvcs_authorize_yn = 'N' OR isnull(vcs.wvcs_authorize_yn,'')='') ";

	}else if($status=="SUCCESS"){

		$search_sql .= " and vcs.wvcs_authorize_yn = 'Y' ";

	}
	
}//if($status !=""){

if($v_user_seq !=""){
	
	$search_sql .= " AND us.v_user_seq = '$v_user_seq' ";
}

if($v_user_name !=""){
	
	$search_sql .= " and us.v_user_name = '".aes_256_enc($v_user_name)."' ";
}

if($v_sys_sn !=""){
	
	$search_sql .= " and vcd.v_sys_sn like '%$v_sys_sn%' ";
}


if($searchkey != ""){
		
	  if($searchopt=="CHECK_APRV_NAME"){
		
		$search_sql .= " and vcs.wvcs_authorize_name = '".aes_256_enc($searchkey)."' ";

	  }else if($searchopt=="USER_NAME"){
					
			if($_cfg_user_identity_name=="phone"){
						
				$searchkey = preg_replace("/[^0-9-]*/s", "", $searchkey); 

			  if($_encryption_kind=="1"){

				 $search_sql .= "and dbo.fn_DecryptString(us.v_phone) like '%$searchkey%' ";

			  }else if($_encryption_kind=="2"){

				 $search_sql .= " and us.v_phone = '".aes_256_enc($searchkey)."' ";
			  }

			}else if($_cfg_user_identity_name=="email"){
				
					if($_encryption_kind=="1"){

						$search_sql .= "and dbo.fn_DecryptString(us.v_email) like '%$searchkey%' ";

					}else if($_encryption_kind=="2"){

						$search_sql .= " and us.v_email = '".aes_256_enc($searchkey)."' ";
					}

			}else{

				$search_sql .= " and us.v_user_name = '".aes_256_enc($searchkey)."' ";
			}

	  }else if($searchopt=="OS"){
		
		$search_sql .= " and vcd.os_ver_name like '%$searchkey%' ";

	  }else if($searchopt=="MODEL"){
		
		$search_sql .= " and vcd.v_model_name like '%$searchkey%' ";

	  }else if($searchopt=="MANUFACTURER"){
		
		$search_sql .= " and vcd.v_manufacturer like '%$searchkey%' ";

	  }else if($searchopt=="MANAGER"){
		
		$search_sql .= " and vcs.mngr_name  = '".aes_256_enc($searchkey)."' ";

	  }else if($searchopt=="MANAGER_DEPT"){
		
		$search_sql .= " and vcs.mngr_department like '%$searchkey%' ";

	  }else if($searchopt=="ORG_NAME"){
		
		$search_sql .= " and org.org_name like '%$searchkey%' ";

	  }else if($searchopt=="SN"){
		
		$search_sql .= " and vcd.v_sys_sn like '%$searchkey%' ";

	  }else if($searchopt=="COM_NAME"){
		
		$search_sql .= " and vc.v_com_name like '%$searchkey%' ";	

	  }else if($searchopt=="COM_SEQ"){
		
		$search_sql .= " and vc.v_com_seq = '{$searchkey}' ";	

	  }

}//if($searchkey != ""){


if($check_result2=="weak"){

	$search_sql .= " and exists (SELECT TOP 1 weakness_seq FROM tb_v_wvcs_weakness WHERE vcs.v_wvcs_seq = v_wvcs_seq ) ";

}else if($check_result2=="virus"){

	$search_sql .= " and exists (
							SELECT TOP 1 vcc.vaccine_seq 
							FROM tb_v_wvcs_vaccine vcc
								INNER JOIN tb_v_wvcs_vaccine_detail vccd
									ON vcc.vaccine_seq = vccd.vaccine_seq
							WHERE vcs.v_wvcs_seq = v_wvcs_seq ) ";

}else if($check_result2=="bad_ext"){	//�������ǽ�
				
	$search_sql .= " and exists (
							SELECT TOP 1 f.v_wvcs_file_seq
							from tb_v_wvcs_info_file f
							WHERE f.v_wvcs_seq = vcs.v_wvcs_seq
								AND f.file_scan_result ='BAD_EXT' ) ";
}

if($orderby != "") {
	$order_sql = " ORDER BY $orderby";
} else {
	$order_sql = " ORDER BY vcs.v_wvcs_seq DESC ";
}

$qry_params = array("search_sql"=> $search_sql,"order_sql"=>$order_sql);

if($check_result1=="last"){
	$qry_label = QRY_RESULT_LASTCHECK_LIST_EXCEL;
}else{
	$qry_label = QRY_RESULT_CHECK_LIST_EXCEL;
}

$sql = query($qry_label,$qry_params);
$result = @sqlsrv_query($wvcs_dbcon, $sql,array(), array("Scrollable"=>SQLSRV_CURSOR_KEYSET)); 

if($result){

	$total_cnt = @sqlsrv_num_rows($result);
	$no = $total_cnt;

	while ($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {

		$check_date = $row['check_date'];
		$in_available_date  = $row['checkin_available_dt'];
		if($in_available_date){
			$in_available_date = substr($in_available_date,0,4)."-".substr($in_available_date,4,2)."-".substr($in_available_date,6,2);
		}
		$in_date	= $row['in_date'];
		$out_date = $row['out_date'];
		
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
		
		$v_com_name = $row['v_com_name'];
		$vv_user_sq = $row['v_user_seq'];
		$weak_cnt = $row['weak_cnt'];
		$virus_cnt = $row['virus_cnt'];
		$file_bad_cnt = $row['file_bad_cnt'];
		$wvcs_authorize_yn = $row['wvcs_authorize_yn'];
		$wvcs_authorize_name = aes_256_dec($row['wvcs_authorize_name']);

		$vacc_scan_count = $row['vacc_scan_count'];
		$import_file_cnt = $row['import_file_cnt'];
		$scan_file_cnt = $row['scan_file_cnt'];

		//���������� ������ �����ϴ� ���� ���̷��� �˻����ϼ� ��� ���۵� ������������ ǥ���� �ش�.
		if($scan_file_cnt > 0){
			$vacc_scan_count = $scan_file_cnt;
		}

		if($_encryption_kind=="1"){

			$phone_no = $row['v_phone_decript'];
			$email = $row['v_email_decript'];

		}else if($_encryption_kind=="2"){

			$phone_no = aes_256_dec($row['v_phone']);
			$email = aes_256_dec($row['v_email']);
		}

		if($_cfg_user_identity_name=="phone"){
			$vv_user_name = $phone_no;
		}else if($_cfg_user_identity_name=="email"){
			$vv_user_name =$email;
		}else{
			$vv_user_name = aes_256_dec($row['v_user_name']);
		}


		$check_type = $row['wvcs_type'];
		

		$mngr_name = aes_256_dec($row['mngr_name']);
		$mngr_department = $row['mngr_department'];

		$mngr = $mngr_name.($mngr_department? " / " :"").$mngr_department;


		$vcs_status = $_CODE['vcs_status'][$row['vcs_status']];

		$disk_cnt = $row['disk_cnt'];
			
		$excel_data  = '
		<Row> 
		<Cell ss:StyleID="s21"><Data ss:Type="Number">' . $no . '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $vv_user_name . '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $v_com_name . '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $check_date . '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $in_available_date . '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $in_date. '</Data></Cell>  
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $out_date. '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $scan_center_name . '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $_CODE['asset_type'][$v_asset_type] . '</Data></Cell>  
		<Cell ss:StyleID="s21"><Data ss:Type="Number">' . $disk_cnt . '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $os . '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $sys_sn . '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $vcs_status . '</Data></Cell> ';
		
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

		$excel_data .='
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $wvcs_authorize_name . '</Data></Cell>  
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $mngr_name . '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $mngr_department . '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $v_org_name . '</Data></Cell> 
		<Cell ss:StyleID="s21"><Data ss:Type="Number">' . number_format($vacc_scan_count) . '</Data></Cell>  ';

		if($_P_CHECK_FILE_SEND_TYPE !="N"){
		$excel_data  .= '
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . number_format($import_file_cnt) . '</Data></Cell>  ';
		}

		

		$excel_data  .= '
		<Cell ss:StyleID="s21"><Data ss:Type="String">' . $check_type . '</Data></Cell> 
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