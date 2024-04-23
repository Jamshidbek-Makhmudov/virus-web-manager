<?php
$page_name = "vaccine_list";
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
if($paging == "") $paging = $_paging;

$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;

//검색 로그 기록
$proc_name = $_POST['proc_name'];
if($proc_name != ""){
	$work_log_seq = WriteAdminActLog($proc_name,'SEARCH');
}

?>
<div id="oper_list" >
	<div class="container" style='padding-bottom:0px;'>

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_manage_vaccine"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		
		<!--검색폼-->
		<form name="searchForm" action="<?php echo $_SERVER[PHP_SELF]?>" method="GET">
		<input type="hidden" name="page" value="">	
			<input type='hidden' name='proc_name' id='proc_name'>
		<table class="search">
		<tr>
			<th><?=$_LANG_TEXT['usersearchtext'][$lang_code]?> </th>
			<td>
				<select name="searchopt" id="searchopt">
					<option value="" <?php if($searchopt == "") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['searchkeywordselecttext'][$lang_code]?></option>
					<option value="V_NAME" <?php if($searchopt == "V_NAME") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['nametext'][$lang_code]?></option>
					<option value="V_VER" <?php if($searchopt == "V_VER") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['versiontext'][$lang_code]?></option>
					<option value="V_DESC" <?php if($searchopt == "V_DESC") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['descriptiontext'][$lang_code]?></option>
					<option value="P_NAME" <?php if($searchopt == "P_NAME") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['filenametext'][$lang_code]?></option>
				</select>

				<input type="text" class="frm_input" style="width:50%" name="searchkey" id="searchkey"  value="<?=$searchkey?>"   maxlength="50">
				
				<input type="submit" value="<?=$_LANG_TEXT['btnsearch'][$lang_code]?>" class="btn_submit" onclick="return SearchSubmit(document.searchForm);">
				
			</td>
		</tr>
		</table>
		
		<?if($_ck_user_level !=""){?>
		<div class="btn_confirm">
			<a href="./vaccine_reg.php" class="btn" ><?=$_LANG_TEXT['btnregist'][$lang_code]?></a>
		</div>
		<?}?>
		
		</form>

		<!--검색결과리스트-->
		<table class="list">
		<tr>
			<th style='width:100px'><?=$_LANG_TEXT['numtext'][$lang_code]?></th>
			<th style='width:200px'><?=$_LANG_TEXT['nametext'][$lang_code]?></th>
			<th style='width:100px'><?=$_LANG_TEXT['versiontext'][$lang_code]?></th>
			<th style='width:200px'><?=$_LANG_TEXT['filenametext'][$lang_code]?></th>
			<th style='width:100px'><?=$_LANG_TEXT['registerdatetext'][$lang_code]?></th>
			<th style='width:80px'><?=$_LANG_TEXT['useyntext'][$lang_code]?></th>
			<th style='min-width:200px'   class="num_last"><?=$_LANG_TEXT['descriptiontext'][$lang_code]?></th>
		</tr>
<?php
	  
	  if($searchkey != ""){

		  if($searchopt=="V_NAME"){

			$search_sql .= " and vacc_name like '%$searchkey%' ";

		  }else if($searchopt == "V_VER"){
			
			$search_sql .= " and vacc_ver like '%$searchkey%' ";
		  
		  }else if($searchopt == "V_DESC"){
			
			$search_sql .= " and vacc_desc like '%$searchkey%' ";
		  
		  }else if($searchopt == "P_NAME"){
			
			$search_sql .= " and process_name like '%$searchkey%' ";
		  
		  }
	  }

		

		$qry_params = array("search_sql"=>$search_sql);
		$qry_label = QRY_VACCINE_LIST_COUNT;
		$sql = query($qry_label,$qry_params);

	
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
			$order_sql = " ORDER BY sort, vacc_seq DESC ";
		}
							
		$qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start);
		$qry_label = QRY_VACCINE_LIST;
		$sql = query($qry_label,$qry_params);

		
		$result =@sqlsrv_query($wvcs_dbcon, $sql);
		
		$cnt = 20;
		$iK = 0;
		$initxt = "";

		if($result){
		  while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

				$cnt--;
				$iK++;
				
				$vacc_seq = $row['vacc_seq'];
				$vacc_name = $row['vacc_name'];
				$vacc_ver = $row['vacc_ver'];
				$use_yn = $row['use_yn'];
				$vacc_desc = $row['vacc_desc'];
				$process_name = $row['process_name'];
				$create_dt = $row['create_dt'];
				$link = $row['link'];

				$param_enc = ParamEnCoding("v_seq=".$vacc_seq.($param ? "&":"").$param);

				if($use_yn=="Y"){

					$initxt .= ($initxt? "^" : "").$vacc_name."|".$process_name;
				}
				

		  ?>	
			<tr onclick="location.href='./vaccine_reg.php?enc=<?=$param_enc?>'" style='cursor:pointer'>
				<td><?php echo $no; ?></td>
				<td><?=$vacc_name?></td>
				<td><?=$vacc_ver?></td>
				<td onclick="event.stopPropagation();">
				<?
					if($link==""){ 
						echo $process_name;
					}else{
						echo "<a href='{$link}'>".$process_name."</a>";
					}
				?>
				</td>
				<td><?=$create_dt?></td>
				<td><?=$use_yn?></td>
				<td class="num_last"><?=$vacc_desc?></td>
			</tr>
			<?php
			
				$no--;
			}
				
		}

		if($result) sqlsrv_free_stmt($result);  
		sqlsrv_close($wvcs_dbcon);
		if($total < 1) {
			
		?>
			<tr>
				<td colspan="7" align='center'><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
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
		</table>

		<?if($_ck_user_level !=""){?>
		<div class="btn_confirm">
			<a href="./vaccine_reg.php" class="btn"><?=$_LANG_TEXT['btnregist'][$lang_code]?></a>
		</div>
		<?}?>
		<div style='margin:70px 0px 20px 0px;color:#fff;font-size:9pt;'><?=$initxt?></div>
	</div>

</div>

<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>