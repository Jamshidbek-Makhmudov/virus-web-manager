<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

/*
$src:호출페이지
1.USER_FILE_LIST : /result/pop_user_file_list.php
2.ALL_FILE_LIST : 전체반입파일내역조회
3.USER_VCS_LOG : /result/pop_user_check_list.php
*/

$src = $_REQUEST[src];
$v_wvcs_seq = $_REQUEST[v_wvcs_seq];
$orderby = $_REQUEST[orderby];		
$page = $_REQUEST[page];
$paging = 15;
if($paging == "") $paging = $_paging;

//echo $src;

$param = "";
if($src!="") $param .= ($param==""? "":"&")."src=".$src;
if($v_wvcs_seq!="") $param .= ($param==""? "":"&")."v_wvcs_seq=".$v_wvcs_seq;

if($v_wvcs_seq !=""){

	$search_sql .= " AND vcs.v_wvcs_seq = '".$v_wvcs_seq."' ";
}

$qry_params = array(
	"search_sql"=> $search_sql
);

$qry_label = QRY_RESULT_FILE_LIST_COUNT;
$sql = query($qry_label,$qry_params);
$result = sqlsrv_query($wvcs_dbcon, $sql); 

//echo nl2br($sql);

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
$qry_label = QRY_RESULT_FILE_LIST;
$sql = query($qry_label,$qry_params);
$result = sqlsrv_query($wvcs_dbcon, $sql); 

//echo nl2br($sql);


if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;

?>
<script type='text/javascript'>
$(function(){
	$("#uc_wrapper1").scroll(function(){
		$("#uc_wrapper2").scrollLeft($("#uc_wrapper1").scrollLeft());
	});
	$("#uc_wrapper2").scroll(function(){
		$("#uc_wrapper1").scrollLeft($("#uc_wrapper2").scrollLeft());
	});
	window.onresize = function(event) {
		var w = $("#tblUsrFileList").width();
		$("#uc_div1").width(w);
	};

});
</script>
<div id='uc_wrapper1' class="wrapper">
	<div id='uc_div1' style='height:1px;width:1100px'></div>
</div>
<div id='uc_wrapper2' class="wrapper">
<table id='tblUsrFileList' class="list" style="margin-top:0px;min-width:1100px;" >
<tr>
	<th style='min-width:60px' ><? echo trsLang('번호','numtext');?></th>
	<?if($src=="ALL_FILE_LIST"){?>
	<th style='min-width:60px' ><? echo trsLang('방문자','visitortext');?></th>
	<th style='min-width:100px' ><? echo trsLang('회사명','companynametext');?></th>
	<?}?>
	<th style='min-width:300px' ><? echo trsLang('반입파일','importfiletext');?></th>
	<th style='min-width:100px' ><? echo trsLang('파일크기','filesizetext');?></th>
	<th style='min-width:80px' ><? echo trsLang('반입일시','importdatetimetext');?></th>
</tr>

<?

 if($result){
  while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

		$cnt--;
		$iK++;
		
		$v_user_name = aes_256_dec($row['v_user_name']);
		$v_com_name = $row['v_com_name'];
		$file_path = $row['file_path'];
		$file_name_org = $row['file_name_org'];
		$file_size = $row['file_size'];
		$str_file_size  = getSizeCheck($file_size);
		$create_date  = $row['create_date'];
		$str_create_date = getDefineDateFormatDotShort($create_date);

  ?>	
	<tr >
		<td><?php echo $no; ?></td>
	<?if($src=="ALL_FILE_LIST"){?>
		<td><?=$v_user_name ?></td>
		<td><?=$v_com_name ?></td>
	<?}?>
		<td style='text-align:left;padding-left:10px;'><?=$file_path.$file_name_org?></td>
		<td><?=$str_file_size?></td>
		<td><?=$str_create_date?></td>
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
</div>

<!--페이징-->
<?php
if($total > 0) {
$param_enc = ($param)? "enc=".ParamEnCoding($param) : "";
print_pagelistNew3Func('user_file_list',$_www_server."/result/get_user_file_list.php",$page, $lists, $page_count, $param_enc, '', $total );
}
?>
<? 
	$excel_param_enc = ParamEnCoding($param.(($orderby)? "&orderby=".$orderby : ""));
	$excel_down_url = $_www_server."/result/user_file_list_excel.php?enc=".$excel_param_enc;
?>
<div class="right" style='margin-top:<?=$total > 0 ? "-70" : "10" ?>px;'>
	<a href="javascript:" id='btnexcelDown' onclick="ExcelDown('<?=$excel_down_url?>','btnexcelDown')" class="btnexcel required-print-auth hide" ><?=$_LANG_TEXT["btnexceldownload"][$lang_code];?></a>
</div>