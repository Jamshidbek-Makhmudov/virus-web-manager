<?php
$page_name = "group_list";
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

$searchopt = $_REQUEST[searchopt];		// 검색옵션
$searchkey = $_REQUEST[searchkey];		// 검색어
$orderby = $_REQUEST[orderby];			// 정렬순서
$page = $_REQUEST[page];				// 페이지
if($paging == "") $paging = $_paging;

/*
$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;
*/
?>

<div id="oper_list">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_manage_group"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>

		<!--검색결과리스트-->
		<table class="list" style="margin-top:0">
		<tr>
			<th style='width:100px;min-width:100px;'><h1><?=$_LANG_TEXT['numtext'][$lang_code]?></h1></th>
			<th style='width:150px;min-width:200px;'><?=$_LANG_TEXT['groupnametext'][$lang_code]?></th>
			<th style='min-width:300px;'><?=$_LANG_TEXT['groupdescriptiontext'][$lang_code]?></th>
			<th style='min-width:250px;'><?=$_LANG_TEXT['organnametext'][$lang_code]?></th>
			<th class="num_last"><?=$_LANG_TEXT['deletetext'][$lang_code]?></th>
		</tr>
<?php
		$qry_params = array();
		$qry_label = QRY_GROUP_LIST_COUNT;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);
		if($result){
			$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
			$total = $row['CNT'];
		}

		$rows = $paging;			// 페이지당 출력갯수
		$lists = $_list;			// 목록수
		$page_count = ceil($total/$rows);
		if(!$page || $page > $page_count) $page = 1;
		$start = ($page-1)*$rows;
		$no = $total-$start;
		$end = $start + $rows;

		$order_sql = " ORDER BY group_name ASC ,group_seq DESC ";

		$qry_params = array("order_sql"=>$order_sql,"start"=>$start,"end"=>$end);
		$qry_label = QRY_GROUP_LIST;
		$sql = query($qry_label,$qry_params);
				
		//echo $sql;
		$result = sqlsrv_query($wvcs_dbcon, $sql);
		
		$cnt = 20;
		$iK = 0;
		$classStr = "";
		
		if($result){
		  while($row=@sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

				$cnt--;
				$iK++;
				
				$group_seq = $row['group_seq'];
				$group_name = $row['group_name'];
				$org_name = $row['org_name'];
				$memo = $row['memo'];
				$create_dt = $row['create_dt'];
				$modify_dt = $row['modify_dt'];	

				$param_enc = ParamEnCoding("group_seq=".$group_seq);


		  ?>	
		  
			<tr onclick="javascript:location.href='./group_reg.php?enc=<?php echo $param_enc; ?>'" style='cursor:pointer' >
				<td><?php echo $no; ?></td>
				<td><?php echo $group_name; ?></td>
				<td><?php echo $memo; ?></td>
				<td><?php echo $org_name; ?></td>
				<td class="num_last"  onclick="event.stopPropagation();"><span onclick="GroupDeleteSubmit('<?php echo $group_seq; ?>');" style='cursor:pointer'  class=' required-delete-auth hide'><?=$_LANG_TEXT['btndelete'][$lang_code]?></span></td>
				
			</tr>
			<?php
			
				$no--;
			}
		}

		if($result) @sqlsrv_free_stmt($result);  
		if($wvcs_dbcon) sqlsrv_close($wvcs_dbcon);

		if($total < 1) {
		?>
			<tr>
				<td colspan="5"><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
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
			<a href="./group_reg.php" class="btn"><?=$_LANG_TEXT['btnregist'][$lang_code]?></a>
		</div>

	</div>

</div>

<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>