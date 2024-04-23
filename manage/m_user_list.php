<?php
$page_name = "m_user_list";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
include_once $_server_path . "/" . $_site_path . "/inc/header.inc";
include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";

$searchopt = $_REQUEST[searchopt];	// 검색옵션
$searchkey = $_REQUEST[searchkey];	// 검색어
$orderby = $_REQUEST[orderby];		// 정렬순서
$page = $_REQUEST[page];			// 페이지
$dept = $_REQUEST['dept'];
if($paging == "") $paging = $_paging;

$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($dept!="") $param .= ($param==""? "":"&")."dept=".$dept;

//검색 로그 기록
$proc_name = $_POST['proc_name'];
if($proc_name != ""){
	$work_log_seq = WriteAdminActLog($proc_name,'SEARCH');
}

?>
<div id="oper_list">
	<div class="container">
		
		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_manage_employee"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		
		<!--검색폼-->
		<form name="searchForm" action="<?php echo $_SERVER[PHP_SELF]?>" method="POST">
		<input type="hidden" name="page" value="">
		<input type='hidden' name='proc_name' id='proc_name'>
		<table class="search">
		<tr>
			<th><?=$_LANG_TEXT["usersearchtext"][$lang_code];?></th>
			<td>
				<select name='dept' id='dept' >
					<option value=''><?=$_LANG_TEXT['deptselecttext'][$lang_code]?></option>
				<?
					$qry_params = array();
					$qry_label = QRY_COMMON_DEPT;
					$sql = query($qry_label,$qry_params);

					$result = sqlsrv_query($wvcs_dbcon, $sql);
					
					if($result){
						while($row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
				?>
							<option value='<?=$row['dept_seq']?>' <?if($row['dept_seq']==$dept) echo "selected='selected'";?>><?if($row['lvl']=="") {echo $row['org_name']."-";}else{echo $row['lvl'];}?><?=$row['dept_name']?></option>
				<?
						} 
					}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<th><?=$_LANG_TEXT["userdetailsearchtext"][$lang_code];?></th>
			<td>
				<select name="searchopt" id="searchopt">
					<option value=''><?=$_LANG_TEXT["searchkeywordselecttext"][$lang_code];?></option>
					<option value='EMP_NAME' <?if($searchopt=="EMP_NAME") echo "selected=selected"?>><?=$_LANG_TEXT["empnametext"][$lang_code];?></option>
					<option value='EMP_NO' <?if($searchopt=="EMP_NO") echo "selected=selected"?>><?=$_LANG_TEXT["empnotext"][$lang_code];?></option>
					<option value="EMAIL" <?php if($searchopt == "EMAIL") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT["emailtext"][$lang_code];?></option>
					<option value="PHONE_NO" <?php if($searchopt == "PHONE_NO") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT["contactphonetext"][$lang_code];?></option>
				</select>
				<input type="text" name="searchkey" id="searchkey" class="frm_input" value="<?=$searchkey?>"  maxlength="50">
				
				<input type="submit" value="<?=$_LANG_TEXT["btnsearch"][$lang_code];?>" class="btn_submit" onclick="return SearchSubmit(document.searchForm);" >
			</td>
		</tr>
		</table>
		
		<div class="btn_confirm">
			<a href="./m_user_reg.php" class="btn" ><?=$_LANG_TEXT["btnregist"][$lang_code];?></a>
		</div>
		</form>
		
		<div class="btn_wrap" style='margin-right:120px;'>
			<?	
				$param_enc = ParamEnCoding($param.(($orderby)? "&orderby=".$orderby : ""));
				$excel_down_url = $_www_server."/manage/m_user_list_excel.php?enc=".$param_enc;
			?>
			<div class="right">
				<a href="#" id='btnExcelDown' onclick="ExcelDown('<?=$excel_down_url?>','btnExcelDown')" class="btnexcel required-print-auth hide" ><?=$_LANG_TEXT["btnexceldownload"][$lang_code];?></a>
			</div>
		</div>

		<!--검색결과리스트-->
		<table class="list"  style="margin-top:10px">
		<tr>
			<th style='min-width:80px;width:80px;'><?=$_LANG_TEXT["numtext"][$lang_code];?></th>
			<th style='min-width:100px;width:120px;'><a href="<?=$PHP_SELF?>?enc=<?=ParamEnCoding($param.($param? "&":"")."orderby=".($orderby=="emp_no"? "emp_no desc" : "emp_no"))?>" class='sort'><?=$_LANG_TEXT["empnotext"][$lang_code];?></a></th>
			<th style='min-width:100px;width:120px;'><a href="<?=$PHP_SELF?>?enc=<?=ParamEnCoding($param.($param? "&":"")."orderby=".($orderby=="emp_name"? "emp_name desc" : "emp_name"))?>" class='sort'><?=$_LANG_TEXT["empnametext"][$lang_code];?></a></th>
			<th  style='min-width:150px;width:200px;'><a href="<?=$PHP_SELF?>?enc=<?=ParamEnCoding($param.($param? "&":"")."orderby=".($orderby=="dept_name"? "dept_name desc" : "dept_name"))?>" class='sort'><?=$_LANG_TEXT["depttext"][$lang_code];?></a></th>
			<th style='min-width:100px;width:150px;'><?=$_LANG_TEXT["jobgradetext"][$lang_code];?></th>
			<th style='min-width:200px;width:200px;'><a href="<?=$PHP_SELF?>?enc=<?=ParamEnCoding($param.($param? "&":"")."orderby=".($orderby=="org_name"? "org_name desc" : "org_name"))?>"  class='sort'><?=$_LANG_TEXT["workplacetext"][$lang_code];?></a></th>
			<th style='min-width:80px' class='num_last'><?=$_LANG_TEXT["workyntext"][$lang_code];?></th>
		</tr>
		
		<?php

			//**유지보수 관리자아이디(dptadmin) 숨김처리
			$search_sql .= " and A.emp_no != 'dptadmin' ";

			if($dept != ""){
				$search_sql .= " and A.dept_seq = '$dept' ";
			}

			if($searchkey != "" && $searchopt != ""){

			  if($searchopt=="EMAIL" || $searchopt=="PHONE_NO"){

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
			   

				$qry_params = array("search_sql"=>$search_sql);
				$qry_label = QRY_EMP_LIST_COUNT;
				$sql = query($qry_label,$qry_params);

				//echo $sql;
				$result = sqlsrv_query($wvcs_dbcon, $sql);
				$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
				$total = $row['CNT'];

			
				$rows = $paging;			// 페이지당 출력갯수
				$lists = $_list;			// 목록수
				$page_count = ceil($total/$rows);
				if(!$page || $page > $page_count) $page = 1;
				$start = ($page-1)*$rows;
				$no = $total-$start;
				$end = $start + $rows;
       	
				if($orderby != "") {
					$order_sql = " ORDER BY $orderby";
				} else {
					$order_sql = " ORDER BY emp_seq DESC ";
				}
       	
       									
       			$qry_params = array("end"=>$end,"order_sql"=>$order_sql,"search_sql"=>$search_sql,"start"=>$start);
				$qry_label = QRY_EMP_LIST;
				$sql = query($qry_label,$qry_params);

				$result = sqlsrv_query($wvcs_dbcon, $sql);
				
				$cnt = 20;
				$iK = 0;
				$classStr = "";

				if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;
			
				 if($result){
				  while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

						$cnt--;
						$iK++;
						
						$emp_seq = $row['emp_seq'];
						$emp_no = $row['emp_no'];
						$emp_name = aes_256_dec($row['emp_name']);
	
						if($_encryption_kind=="1"){
							
							$phone_no = $row['phone_no_decript'];
							$email = $row['email_decript'];

						}else if($_encryption_kind=="2"){

							$phone_no = aes_256_dec($row['phone_no']);
							$email = aes_256_dec($row['email']);
						}

						$work_yn = $row['work_yn'];
						$use_lang = $row['use_lang'];

						$org_id = $row['org_id'];
						$org_name = $row['org_name'];
						$dept_seq = $row['dept_seq'];
						$dept_name = $row['dept_name'];
						$jpos_seq = $row['jpos_seq'];
						$jgrade_seq = $row['jgrade_seq'];
						$jduty_seq = $row['jduty_seq'];
						$sgrade_code = $row['sgrade_code'];
						$sgrade_name = $row['sgrade_name'];
						$jgrade_name = $row['jgrade_name'];
						$cr_dt = $row['cr_dt'];

						$param_enc = ParamEnCoding("emp_seq=".$emp_seq.($param==""? "":"&").$param);

				  ?>	
					<tr onclick="javascript:location.href='./m_user_reg.php?enc=<?=$param_enc?>'" style='cursor:pointer'>
						<td><?php echo $no; ?></td>
						<td><?=$emp_no?></td>
						<td><?=$emp_name?></td>
						<td><?=$dept_name?></td>
						<td><?=$jgrade_name?></td>
						<td><?=$org_name?></td>
						<td class='num_last'><?=$work_yn?></td>
					</tr>
					<?php
					
						$no--;
					}
					
				}

			 
				if($total < 1) {
					
				?>
					<tr>
						<td colspan="8" align="center"><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
					</tr>
				<?php
				}
				?>				
						
		</table>

		<!--페이징-->
<?php
		if($total > 0) {
			$param_enc = ($param)? "enc=".ParamEnCoding($param) : "";
			print_pagelistNew3($page, $lists, $page_count, $param_enc, '', $total );
		}
?>
		<div class="btn_confirm">
			<a href="./m_user_reg.php" class="btn" ><?=$_LANG_TEXT["btnregist"][$lang_code];?></a>
		</div>
	</div>

</div>

<?php

if($result) sqlsrv_free_stmt($result);  
sqlsrv_close($wvcs_dbcon);

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>