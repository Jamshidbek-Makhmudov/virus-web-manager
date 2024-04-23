<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$v_user_seq = $_REQUEST[v_user_seq];
$v_sys_sn = $_REQUEST[v_sys_sn];
$v_hdd_sn = $_REQUEST[v_hdd_sn];
$v_board_sn = $_REQUEST[v_board_sn];
$orderby = $_REQUEST[orderby];		
$page = $_REQUEST[page];
if($paging == "") $paging = $_paging;

$param = "";
if($v_user_seq!="") $param .= ($param==""? "":"&")."v_user_seq=".$v_user_seq;
if($v_sys_sn!="") $param .= ($param==""? "":"&")."v_sys_sn=".$v_sys_sn;
if($v_hdd_sn!="") $param .= ($param==""? "":"&")."v_hdd_sn=".$v_hdd_sn;
if($v_board_sn!="") $param .= ($param==""? "":"&")."v_user_seq=".$v_board_sn;

$search_sql = " AND us.v_user_seq = '".$v_user_seq."' ";

if($v_sys_sn){

	$search_sql .= " AND vcs.v_sys_sn = '".$v_sys_sn."' ";
}

if($v_hdd_sn){

	$search_sql .= " AND vcs.v_hdd_sn = '".$v_hdd_sn."' ";
}

if($v_board_sn){

	$search_sql .= " AND vcs.v_board_sn = '".$v_board_sn."' ";
}

$qry_params = array(
	"search_sql"=> $search_sql
);

$qry_label = QRY_RESULT_PC_CHECK_LIST_COUNT;
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
	$order_sql= " ORDER BY vcs.v_wvcs_seq DESC ";
}

$qry_params = array(
	"end"=> $end
	,"start"=>$start
	,"order_sql"=>$order_sql
	,"search_sql"=> $search_sql
);
$qry_label = QRY_RESULT_PC_CHECK_LIST;
$sql = query($qry_label,$qry_params);
$result = sqlsrv_query($wvcs_dbcon, $sql); 

//echo nl2br($sql);

$cnt = 20;
$iK = 0;

if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;

?>

<!--검색결과리스트-->
<!--<div style="margin-top:0px;width:1200px;">Results: <span style='color:blue'><?=number_format($total)?></span></div>-->
<table class="list" style="margin-top:0px;" >
<tr>
	<th style='min-width:60px' ><?=$_LANG_TEXT["numtext"][$lang_code];?></th>
	<th style='min-width:80px' ><?=$_LANG_TEXT["checkdatetext"][$lang_code];?></th>
	<th style='min-width:80px' ><?=$_LANG_TEXT["inlimitdatetext"][$lang_code];?></th>
	<th style='min-width:80px' ><?=$_LANG_TEXT["indatetext"][$lang_code];?></th>
	<th style='min-width:80px'><?=$_LANG_TEXT["organtext"][$lang_code];?></th>
	<th style='min-width:80px' ><?=$_LANG_TEXT["checkgubuntext"][$lang_code];?></th>
	<th style='min-width:80px'><?=$_LANG_TEXT["ostext"][$lang_code];?></th>
	<th style='min-width:80px'><?=$_LANG_TEXT["serialnumbertext"][$lang_code];?></th>
	<th style='min-width:80px' ><?=$_LANG_TEXT["progressstatustext"][$lang_code];?></th>
	<th style='min-width:80px' ><?=$_LANG_TEXT["checkresulttext"][$lang_code];?></th>
	<th style='min-width:80px'><?=$_LANG_TEXT["managertext"][$lang_code];?></th>
</tr>

<?

 if($result){
  while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

		$cnt--;
		$iK++;

		$v_wvcs_seq = $row['v_wvcs_seq'];

		$check_date = $row['check_date'];
		$in_available_date  = $row['checkin_available_dt'];
		if($in_available_date){
			$in_available_date = substr($in_available_date,0,4)."-".substr($in_available_date,4,2)."-".substr($in_available_date,6,2);
		}
		$in_date	= $row['in_date'];

		$v_org_name = $row['org_name'];
		$v_asset_type = $row['v_asset_type'];
		$sys_sn = $row['v_sys_sn'];
		$hdd_sn = $row['v_hdd_sn'];
		$board_sn = $row['v_board_sn'];
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


		$check_type = "OneClick";
		
		$param_enc = ParamEnCoding("v_wvcs_seq=".$v_wvcs_seq.($param==""? "":"&").$param);

		$check_result ="";
		
		if($weak_cnt > 0){
			$check_result = "<span class='icontxt weakness'>".$_LANG_TEXT["weaknessshorttext"][$lang_code]."</span> ";
		}

		//echo $virus_cnt;

		if($virus_cnt > 0){

			$check_result .= "<span class='icontxt virus'>".$_LANG_TEXT["virusshorttext"][$lang_code]."</span> ";
		}

		$user_name_com = $vv_user_name.($v_com_name? "/" : "").$v_com_name;

		$mngr = aes_256_dec(($row['mngr_name']).($row['mngr_department']? " / " :"").$row['mngr_department'];


		if($wvcs_authorize_yn=="Y"){
			$vcs_status = $_CODE['vcs_status']['SUCCESS'];
		}else{
			$vcs_status = $_CODE['vcs_status']['CHECK'];
		}

  ?>	
	<tr onclick="javascript:location.href='<?=$_www_server?>/result/result_view_pc.php?enc=<?=$param_enc?>'" style='cursor:pointer;text-align:center'>
		<td><?php echo $no; ?></td>
		<td><?=$check_date?></td>
		<td><?=$in_available_date?></td>
		<td><?=$in_date?></td>
		<td><?=$v_org_name?></td>
		<td><?=$check_type?></td>
		<td><?=$os?></td>
		<td><?=$sys_sn?></td>
		<td><?=$vcs_status?></td>
		<td><?=$check_result?></td>
		<td><?=$mngr?></td>
	</tr>
	<?php
	
		$no--;
	}
	
}

if($total < 1) {
	
?>
	<tr>
		<td colspan="13" align="center"><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
	</tr>
<?php
}
?>				
		
</table>

<!--페이징-->
<?php
if($total > 0) {
$param_enc = ($param)? "enc=".ParamEnCoding($param) : "";
print_pagelistNew3Func('user_pc_list',"get_user_pc_list.php",$page, $lists, $page_count, $param_enc, '', $total );
}
?>