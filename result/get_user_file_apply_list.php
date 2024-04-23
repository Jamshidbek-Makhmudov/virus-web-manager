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
$src : 호출하는 페이지
1.VISIT_INFO_VIEW : /user/access_info.php
*/

//print_r($_REQUEST);


$src = $_REQUEST[src];
$v_user_list_seq = $_REQUEST[v_user_list_seq];
$v_wvcs_seq = $_REQUEST[v_wvcs_seq];
$orderby = $_REQUEST[orderby];		
$page = $_REQUEST[page];

$paging = 15;
if($paging == "") $paging = $_paging;

//echo $src;

$param = "";
if($src!="") $param .= ($param==""? "":"&")."src=".$src;
if($v_user_list_seq!="") $param .= ($param==""? "":"&")."v_user_list_seq=".$v_user_list_seq;
if($v_wvcs_seq!="") $param .= ($param==""? "":"&")."v_wvcs_seq=".$v_wvcs_seq;


if($v_user_list_seq != ""){
	$search_sql = " and v1.v_user_list_seq='{$v_user_list_seq}' ";
}else if($v_wvcs_seq != ""){
	$search_sql = " and t1.v_wvcs_seq='{$v_wvcs_seq}' ";
}else{
	$search_sql = " and 1= 2 ";
}


$Model_result = new Model_result();
$args = array("search_sql"=>$search_sql);
$total=$Model_result->getFileInApplyFileListCount($args);

$rows = $paging;			// 페이지당 출력갯수
$lists = $_list;				// 목록수
$page_count = ceil($total/$rows);
if(!$page || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;
$no = $total-$start;
$end = $start + $rows;

if($orderby != "") {
	$order_sql = " ORDER BY $orderby";
} else {
	$order_sql = " ORDER BY t1.file_in_apply_seq DESC "; 
}	

$args = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start);	

//$Model_result->SHOW_DEBUG_SQL = true;
$result = $Model_result->getFileInApplyFileList($args);

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
		var w = $("#tblUsrFileApplyList").width();
		$("#uc_div1").width(w);
	};

});
</script>
<div id='uc_wrapper1' class="wrapper">
	<div id='uc_div1' style='height:1px;width:1100px'></div>
</div>
<div id='uc_wrapper2' class="wrapper">
<table id='tblUsrFileApplyList' class="list" style="margin-top:0px;min-width:1100px;" >
	<tr>
		<th style='width:50px'><? echo trsLang('번호','numtext');?></th>
		<th style='width:300px'><? echo trsLang('파일명','filenametext');?></th>
		<th style='width:300px'><? echo trsLang('파일해시','filehash');?>(md5)</th>
		<th style='width:300px'><? echo trsLang('신청사유','applyreason');?></th>
		<th style='width:300px'><? echo trsLang('적용기간','applicationperiod');?></th>
		<th style='width:140px;min-width:140px'><? echo trsLang('승인여부','approvedyesnotext');?></th>
		<th style='width:100px;min-width:100px'><? echo trsLang('승인자','approver');?></th>
		<th style='width:150px;min-width:150px'><? echo trsLang('승인일자','approvedate');?></th>
	</tr>

		<?

		if($result){
				while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
				
					$file_name = $row['file_name'];
					$file_hash = $row['file_hash'];
					$file_comment = $row['file_comment'];
					$apprv_emp_name = aes_256_dec($row['apprv_emp_name']);
					$approve_date = setDateFormat($row['approve_date']);
					$str_approve_status = $_CODE_FILE_EXCEPTION_APPRV_STATUS[$row['approve_status']];
					
					if($str_approve_status=="") $str_approve_status = $row['approve_status'];
					
					$start_date = setDateFormat($row['start_date']);
					$end_date = setDateFormat($row['end_date']);
					
					$param_enc = ParamEnCoding("v_user_list_seq=".$v_user_list_seq.($param==""? "":"&").$param);
		?>
			<tr>
				<td><? echo $no?></td>
				<td><? echo $file_name?></td>
				<td><? echo $file_hash?></td>
				<td><? echo $file_comment?></td>
				<td><? echo $start_date?> ~ <? echo $end_date?></td>
				<td><? echo $str_approve_status?></td>
				<td><? echo $apprv_emp_name?></td>
				<td><? echo $approve_date?></td>
			</tr>
		<?
					$no--;
				}
			}

if($total < 1) {
	
?>
	<tr>
		<td colspan="15" align="center"><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
	</tr>
<?php
}
?>				
		
</table>
</div>

<!--paging-->
<?php
if($total > $paging) {
	$param_enc = ($param)? "enc=".ParamEnCoding($param) : "";
	print_pagelistNew3Func('file_apply_list',$_www_server."/result/get_user_file_apply_list.php",$page, $lists, $page_count, $param_enc, '', $total );
}else{
	echo "<div id='paging2'><ul><li><!--paging hide--></li></ul></div>";
}
?>