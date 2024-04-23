<?php
$page_name = "notice_list";
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

$notice_seq = $_REQUEST['notice_seq'];
$searchopt = $_REQUEST['searchopt'];	// 검색옵션
$searchkey = $_REQUEST['searchkey'];	// 검색어
$orderby = $_REQUEST['orderby'];		// 정렬순서

$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;

if($notice_seq != ""){

	if($searchkey != ""){

		  if($searchopt=="TITLE_CONTENTS"){

			$search_sql .= " and (title like '%$searchkey%' OR contents like '%$searchkey%' ) ";

		  }else if($searchopt == "FILE"){
			
			$search_sql .= "and pds_file_real_name like '%$searchkey%' ";
		  
		  }else if($searchopt == "WRITER"){
			
			$search_sql .= "and emp_name = '".aes_256_enc($searchkey)."' ";
		  
		  }else{

			$search_sql .= " and $searchopt like '%$searchkey%' ";

		  }
	  }

	if($orderby != "") {
		$order_sql = " ORDER BY $orderby";
	} else {
		$order_sql = " ORDER BY notice_seq DESC ";
	}


	$qry_params = array("notice_seq"=>$notice_seq,"order_sql"=>$order_sql,"search_sql"=>$search_sql);
	$qry_label = QRY_NOTICE_INFO;
	$sql = query($qry_label,$qry_params);

	$result =@sqlsrv_query($wvcs_dbcon, $sql);

	if($result){
	  while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

		  $writer = aes_256_dec($row['emp_name']);
		  $writer_emp_seq = $row['emp_seq'];
		  $write_date = $row['write_date'];
		  $title = $row['title'];
		  $contents = $row['contents'];
		  $gubun = $row['gubun'];
		  $rnum = $row['rnum'];

		  if($row['pds_file_name']){
				$file = "<a href='/".$_site_path."/common/download.php?enc=".ParamEnCoding("file=".$_SERVER['DOCUMENT_ROOT'].$row['pds_file_path']."/".$row['pds_file_name'])."'>".$row['pds_file_real_name']."</a>";
		  }else{
				$file = "";
		  }

	  }
	}

	//이전,다음
	$qry_params = array("rnum"=>$rnum,"order_sql"=>$order_sql,"search_sql"=>$search_sql);
	$qry_label = QRY_NOTICE_INFO_PREV;
	$sql = query($qry_label,$qry_params);


	$result = sqlsrv_query($wvcs_dbcon, $sql);
	$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
	$prev_notice_seq = $row['notice_seq'];

	$qry_params = array("rnum"=>$rnum,"order_sql"=>$order_sql,"search_sql"=>$search_sql);
	$qry_label = QRY_NOTICE_INFO_NEXT;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);
	$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
	$next_notice_seq = $row['notice_seq'];

	if($result) sqlsrv_free_stmt($result);  
	sqlsrv_close($wvcs_dbcon);

}

//수정,삭제권한
$editable = !($_ck_user_level =="");
?>
<div id="oper_input">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_manage_notice"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		<div class="page_right"><span style='cursor:pointer' onclick="history.back();"><?=$_LANG_TEXT['btngobeforepage'][$lang_code]?></span></div>

		<!--등록폼-->
		<form name="frmNotice" id="frmNotice" enctype="multipart/form-data" method="post">
		<input type="hidden" name="n_seq" id="n_seq" value="<?=$notice_seq?>">
		<input type="hidden" name="proc" id="proc" >
		<table class="view">
		<tr>
			<th style='min-width:150px'><?=$_LANG_TEXT['registertext'][$lang_code]?></th>
			<td style='width:300px'><?=$writer?></td>
			<th style='min-width:150px' class="line"><?=$_LANG_TEXT['registerdatetext'][$lang_code]?></th>
			<td ><?=$write_date?></td>
		</tr>
		<tr class="bg">
			<th><?=$_LANG_TEXT['gubuntext'][$lang_code]?></th>
			<td colspan="3">
				<?=$gubun?>
			</td>
		</tr>
		<tr>
			<th><?=$_LANG_TEXT['titletext'][$lang_code]?></th>
			<td colspan="3"><?=$title?></td>
		</tr>
		<tr class="bg">
			<th><?=$_LANG_TEXT['contentstext'][$lang_code]?></th>
			<td colspan="3">
				<?=nl2br($contents)?>
			</td>
		</tr>
		<tr>
			<th><?=$_LANG_TEXT['attachfiletext'][$lang_code]?></th>
			<td colspan="3"><?=$file?></td>
		</tr>
		</table>
		
		
		<div class="btn_wrap">
			<div class="left">
				<a href="<?if(empty($prev_notice_seq)){?>javASCript:alert(nodatatext[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."?enc=".ParamEnCoding("notice_seq=".$prev_notice_seq.($param ? "&" : "").$param); }?>"  class="btn" id='btnPrev'><?=$_LANG_TEXT["btnprev"][$lang_code];?></a>
				<a href="<?if(empty($next_notice_seq)){?>javASCript:alert(nodatatext[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."?enc=".ParamEnCoding("notice_seq=".$next_notice_seq.($param ? "&" : "").$param); }?>"  class="btn" id='btnNext'><?=$_LANG_TEXT["btnnext"][$lang_code];?><a>
			</div>
			<div class="right">
				<a href="./notice_list.php" class="btn"><?=$_LANG_TEXT['btnlist'][$lang_code]?></a>
			<?if($editable){?>
				<a href="notice_reg.php?enc=<?=ParamEnCoding("notice_seq=".$notice_seq)?>" class="btn"><?=$_LANG_TEXT['btnupdate'][$lang_code]?></a>
				<a href="#" class="btn required-delete-auth hide" onclick="NoticeSubmit('DELETE')"><?=$_LANG_TEXT['btndelete'][$lang_code]?></a>
			<?}?>
			</div>
		</div>

		</form>

		

	</div>

</div>

<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>