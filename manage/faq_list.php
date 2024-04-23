<?php
$page_name = "faq_list";
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

$searchopt = $_REQUEST[searchopt];	// �˻��ɼ�
$searchkey = $_REQUEST[searchkey];	// �˻���
$orderby = $_REQUEST[orderby];		// ���ļ���
$page = $_REQUEST[page];			// ������
if($paging == "") $paging =$_paging;

$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;

?>
<div id="oper_list">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_manage_faq"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		
		<!--�˻���-->
		<form name="searchForm" action="<?php echo $_SERVER[PHP_SELF]?>" method="GET">
		<input type="hidden" name="page" value="">	
		<table class="search">
		<tr>
			<th><?=$_LANG_TEXT['usersearchtext'][$lang_code]?> </th>
			<td>
				<select name="searchopt" id="searchopt">
					<option value="" <?php if($searchopt == "") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['searchkeywordselecttext'][$lang_code]?></option>
					<option value="GUBUN" <?php if($searchopt == "GUBUN") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['gubuntext'][$lang_code]?></option>
					<option value="TITLE" <?php if($searchopt == "TITLE") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['titletext'][$lang_code]?></option>
					<option value="CONTENTS" <?php if($searchopt == "CONTENTS") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['contentstext'][$lang_code]?></option>
					<option value="TITLE_CONTENTS" <?php if($searchopt == "TITLE_CONTENTS") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['titlencontentstext'][$lang_code]?></option>
					<option value="WRITER" <?php if($searchopt == "WRITER") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['registertext'][$lang_code]?></option>
				</select>

				<input type="text" class="frm_input" style="width:50%" name="searchkey" id="searchkey"  value="<?=$searchkey?>"   maxlength="50">
				
				<input type="submit" value="<?=$_LANG_TEXT['btnsearch'][$lang_code]?>" class="btn_submit" onclick="return SearchSubmit(document.searchForm);">
			</td>
		</tr>
		</table>
		
		<?if($_ck_user_level !=""){?>
		<div class="btn_confirm">
			<a href="./faq_reg.php" class="btn" ><?=$_LANG_TEXT['btnregist'][$lang_code]?></a>
		</div>
		<?}?>
		</form>

		<!--�˻��������Ʈ-->
		<table class="list">
		<tr>
			<th class="num"><?=$_LANG_TEXT['numtext'][$lang_code]?></th>
			<th class="num"><?=$_LANG_TEXT['gubuntext'][$lang_code]?></th>
			<th><?=$_LANG_TEXT['titletext'][$lang_code]?></th>
			<th class="num"><?=$_LANG_TEXT['registertext'][$lang_code]?></th>
			<th class="datatime"><?=$_LANG_TEXT['registerdatetext'][$lang_code]?></th>
		</tr>
<?php
	  
	  if($searchkey != ""){

		  if($searchopt=="TITLE_CONTENTS"){

			$search_sql .= " and (title like '%$searchkey%' OR contents like '%$searchkey%' ) ";

		  }else if($searchopt == "WRITER"){
			
			$search_sql .= "and emp_name = '".aes_256_enc($searchkey)."' ";
		  
		  }else{

			$search_sql .= " and $searchopt like '%$searchkey%' ";

		  }
	  }

		$qry_params = array("search_sql"=>$search_sql);
		$qry_label = QRY_FAQ_LIST_COUNT;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);
		$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		$total = $row['CNT'];

		$rows = $paging;			// �������� ��°���
		$lists = $_list;			// ��ϼ�
		$page_count = ceil($total/$rows);
		if(!$page || $page > $page_count) $page = 1;
		$start = ($page-1)*$rows;
		$no = $total-$start;
		$end = $start + $rows;

		if($orderby != "") {
			$order_sql = " ORDER BY $orderby";
		} else {
			$order_sql = " ORDER BY faq_seq DESC ";
		}

	
		
									
		$qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start);
		$qry_label = QRY_FAQ_LIST;
		$sql = query($qry_label,$qry_params);
		$result =@sqlsrv_query($wvcs_dbcon, $sql);

		//echo $sql;
		
		$cnt = 20;
		$iK = 0;
		$classStr = "";

		if($result){
		  while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

				$cnt--;
				$iK++;
				
				$faq_seq = $row['faq_seq'];
				$gubun = $row['gubun'];
				$title = $row['title'];
				$writer = aes_256_dec($row['emp_name']);	
				$write_date = $row['write_date'];
				

				$param_enc = ParamEnCoding("faq_seq=".$faq_seq.($param ? "&":"").$param);

		  ?>	
			<tr onclick="location.href='faq_view.php?enc=<?=$param_enc?>'" style='cursor:pointer'>
				<td><?php echo $no; ?></td>
				<td><?=$gubun?></td>
				<td style="text-align:left"><?=$title?></td>
				<td><?=$writer?></td>
				<td><?=$write_date?></td>
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
				<td colspan="6" align='center'><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
			</tr>
		<?php
		}
		?>				
							
		</table>
		<!--����¡-->
		<?php
		if($total > 0) {
			$param_enc = ($param)? "enc=".ParamEnCoding($param) : "";
			print_pagelistNew3($page, $lists, $page_count, $param_enc, '', $total );
		}
		?>
		</table>

		<?if($_ck_user_level !=""){?>
		<div class="btn_confirm">
			<a href="./faq_reg.php" class="btn"><?=$_LANG_TEXT['btnregist'][$lang_code]?></a>
		</div>
		<?}?>

	</div>

</div>

<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>