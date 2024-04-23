<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$v_com_seq = $_REQUEST[v_com_seq];
$src = $_REQUEST[src];
$useyn = $_REQUEST[useyn];
$orderby = $_REQUEST[orderby];		// 정렬순서
$page = $_REQUEST[page];			// 페이지
$paging = $_REQUEST[paging];
$paging = 10;
//if($paging == "") $paging = $_paging;

$param = "";
if($v_com_seq!="") $param .= ($param==""? "":"&")."v_com_seq=".$v_com_seq;
if($src!="") $param .= ($param==""? "":"&")."src=".$src;
if($useyn!="") $param .= ($param==""? "":"&")."useyn=".$useyn;
if($paging!="") $param .= ($param==""? "":"&")."paging=".$paging;


$search_sql = " and vu.v_com_seq = '{$v_com_seq}' ";

if($useyn=="Y" || $useyn=="N"){

  $search_sql .= " and vu.use_yn = '$useyn' ";
}

$qry_params = array("search_sql"=> $search_sql);
$qry_label = QRY_USER_LIST_COUNT;
$sql = query($qry_label,$qry_params);
$result = sqlsrv_query($wvcs_dbcon, $sql); 

$total = 0;
if($result){
	$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
	$total = $row['CNT'];
}


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
	$order_sql = " ORDER BY vu.v_user_name asc ";
}

$qry_params = array("end"=> $end,"order_sql"=>$order_sql,"search_sql"=>$search_sql,"start"=>$start);
$qry_label = QRY_USER_LIST;
$sql = query($qry_label,$qry_params);
$result = sqlsrv_query($wvcs_dbcon, $sql); 

//echo nl2br($sql);

$cnt = 20;
$iK = 0;

if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;
		
?>
	<table class="list" style='margin-top:0px'>
	<tr>
		<th style='min-width:60px;width:60px;'><?=$_LANG_TEXT["numtext"][$lang_code];?></th>
		<th style='min-width:80px;width:100px;'><?=$_LANG_TEXT["visitortext"][$lang_code];?></th>
		<th style='width:120px;min-width:120px;'><?=$_LANG_TEXT["contactphonetext"][$lang_code];?></th>
		<th style='width:200px;min-width:200px;'><?=$_LANG_TEXT["emailtext"][$lang_code];?></th>
		<th style='min-width:200px;text-align:left;padding-left:10px'>
			<?=$_LANG_TEXT["checkstatustext"][$lang_code];?>
			<div class='checkstatus'>
				<span class='checkbar tot'></span> <?=$_LANG_TEXT["allcheckresulttext"][$lang_code];?>
				<span class='checkbar weak'></span> <?=$_LANG_TEXT["weaknessshorttext"][$lang_code];?>
				<span class='checkbar virus'></span> <?=$_LANG_TEXT["virusshorttext"][$lang_code];?>
			</div>
		</th>
	</tr>

	<?php
			 if($result){
			  while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

					$cnt--;
					$iK++;
					
					$v_user_seq = $row['v_user_seq'];
					$v_com_seq = $row['v_com_seq'];
					$user_name = aes_256_dec($row['v_user_name']);
					$com_name = $row['v_com_name'];

					if($_encryption_kind=="1"){

						$phone_no = $row['v_phone_decript'];
						$email = $row['v_email_decript'];

					}else if($_encryption_kind=="2"){

						$phone_no = aes_256_dec($row['v_phone']);
						$email = aes_256_dec($row['v_email']);
					}
					
					$vcs_cnt = $row['vcs_cnt'];
					$weak_cnt = $row['weak_cnt'];
					$virus_cnt = $row['virus_cnt'];
					
					$weak_bar_width = 0;
					$virus_bar_width = 0;

					if($row['vcs_cnt'] > 0){
						$weak_bar_width = (int)($weak_cnt / $vcs_cnt * 100);
						$virus_bar_width = (int)($virus_cnt / $vcs_cnt * 100);
					};
					

					$view_param_enc = ParamEnCoding("page=".$page."&v_user_seq=".$v_user_seq.($param==""? "":"&").$param);

			  ?>	
				<tr>
					<td class="num"><?php echo $no; ?></td>
					<td class="center"><a href="javascript:" onclick="return popUserVcsSummary('<?=$v_user_seq?>');"><?=$user_name?></a></td>
					<td class="center"><?=$phone_no?></td>
					<td class="center"><?=$email?></td>
					<td class="center">
						<div class='totbar'><?=number_format($vcs_cnt)?></div>
						<div style='float:left;display:inline;'>
							<div class='weakbar' style='width:<?=$weak_bar_width?>px;'>
								<span style='width:<?=$weak_bar_width+45?>px;'><?=$weak_bar_width?>% (<?=number_format($weak_cnt)?>)</span>
							</div>
							<div class='virusbar' style='width:<?=$virus_bar_width?>px;'>
								<span style='width:<?=$virus_bar_width+45?>px;'><?=$virus_bar_width?>% (<?=number_format($virus_cnt)?>)</span>
							</div>
						</div>
					</td>
				</tr>
				<?php
				
					$no--;
				}
				
			}

		 
			if($total < 1) {
				
			?>
				<tr>
					<td colspan="7" align="center"><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
				</tr>
			<?php
			}
			?>				
					
	</table>
<!--페이징-->
<?php
if($total > 0) {
	$param_enc = ($param)? "enc=".ParamEnCoding($param) : "";
	print_pagelistNew3Func('com_device_vcs_list',$_www_server."/user/get_com_user_list.php",$page, $lists, $page_count, $param_enc, '', $total );
}
?>
<? 
	$excel_param_enc = ParamEnCoding($param.(($orderby)? "&orderby=".$orderby : ""));
	$excel_down_url = $_www_server."/user/user_list_excel.php?enc=".$excel_param_enc;
?>
<div class="right" style='margin-top:<?=$total > 0 ? "-70" : "10" ?>px;'>
	<a href="#" id='btnExcelDown' onclick="ExcelDown('<?=$excel_down_url?>','btnExcelDown')" class="btnexcel required-print-auth hide" ><?=$_LANG_TEXT["btnexceldownload"][$lang_code];?></a>
</div>
