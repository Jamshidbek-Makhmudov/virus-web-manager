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

//**�Ϲݻ���� ��������
if($_ck_user_level ==""){
	alert($_LANG_TEXT["accessdenied"][$lang_code]);
	exit;
}

include_once $_server_path . "/" . $_site_path . "/inc/header.inc";
include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";

$faq_seq = $_REQUEST['faq_seq'];
$searchopt = $_REQUEST['searchopt'];	// �˻��ɼ�
$searchkey = $_REQUEST['searchkey'];	// �˻���
$orderby = $_REQUEST['orderby'];		// ���ļ���

$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;


if(empty($faq_seq)){
		
	$writer = $_ck_user_name."(".$_ck_user_id.")"; 
	$write_date = date("Y-m-d H:i");

}else{

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
		$order_sql = " ORDER BY faq_seq DESC ";
	}

	

	$qry_params = array("faq_seq"=>$faq_seq,"order_sql"=>$order_sql,"search_sql"=>$search_sql);
	$qry_label = QRY_FAQ_INFO;
	$sql = query($qry_label,$qry_params);

	$result =@sqlsrv_query($wvcs_dbcon, $sql);

	if($result){
	  while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

		  $writer = aes_256_dec($row['emp_name']);	
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

	//����,����
	$qry_params = array("rnum"=>$rnum,"order_sql"=>$order_sql,"search_sql"=>$search_sql);
	$qry_label = QRY_FAQ_INFO_PREV;
	$sql = query($qry_label,$qry_params);


	$result = sqlsrv_query($wvcs_dbcon, $sql);
	$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
	$prev_faq_seq = $row['faq_seq'];

	$qry_params = array("rnum"=>$rnum,"order_sql"=>$order_sql,"search_sql"=>$search_sql);
	$qry_label = QRY_FAQ_INFO_NEXT;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);
	$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
	$next_faq_seq = $row['faq_seq'];

}

?>
<div id="oper_input">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_manage_faq"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		<div class="page_right"><span style='cursor:pointer' onclick="history.back();"><?=$_LANG_TEXT['btngobeforepage'][$lang_code]?></span></div>

		<!--�����-->
		<form name="frmFaq" id="frmFaq" method="post">
		<input type="hidden" name="f_seq" id="f_seq" value="<?=$faq_seq?>">
		<input type="hidden" name="proc" id="proc" >
		<table class="view">
		<tr>
			<th style='min-width:150px'><?=$_LANG_TEXT['registertext'][$lang_code]?></th>
			<td style='width:300px'><?=$writer?></td>
			<th style='min-width:150px' class="line"><?=$_LANG_TEXT['registerdatetext'][$lang_code]?></th>
			<td><?=$write_date?></td>
		</tr>
		<tr class="bg">
			<th><?=$_LANG_TEXT['gubuntext'][$lang_code]?></th>
			<td colspan="3">
			<?
			/*�ڵ尡������*/
			$qry_params = array("code_keys"=>"FAQ_CATEGORY");
			$qry_label = QRY_COMMON_CODE;
			$sql = query($qry_label,$qry_params);
			
			$params = array();
			$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			$result = @sqlsrv_query($wvcs_dbcon, $sql,$params,$options);
			

			if($result){
				$row_count = sqlsrv_num_rows($result);
				if($row_count > 0){
			?>
				<select id='f_gubun' name='f_gubun'>
			<?
					
					while($row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
						
						$value = $row['code_name'];
						echo "<option value='".$value."' ".($gubun==$value? "selected=true" : "").">".$value."</option>";
						$idx++;
					}
			 ?>
				</select>
			<?
				}else{
					echo "-";
				}
			}
			?>
				
			</td>
		</tr>
		<tr>
			<th><?=$_LANG_TEXT['titletext'][$lang_code]?></th>
			<td colspan="3"><input type="text" name="f_title" id="f_title" class="frm_input" style="width:750px" value="<?=$title?>"   maxlength="100"></td>
		</tr>
		<tr class="bg">
			<th><?=$_LANG_TEXT['contentstext'][$lang_code]?></th>
			<td colspan="3">
				<textarea id='f_contents'  name='f_contents'  style="width:750px"><?=$contents?></textarea>
			</td>
		</tr>
		</table>
		
		
		<div class="btn_wrap">
<?if($faq_seq != ""){?>
			<div class="left">
				<a href="<?if(empty($prev_faq_seq)){?>javASCript:alert(nodatatext[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."?enc=".ParamEnCoding("faq_seq=".$prev_faq_seq.($param ? "&" : "").$param); }?>"  class="btn" id='btnPrev'><?=$_LANG_TEXT["btnprev"][$lang_code];?></a>
				<a href="<?if(empty($next_faq_seq)){?>javASCript:alert(nodatatext[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."?enc=".ParamEnCoding("faq_seq=".$next_faq_seq.($param ? "&" : "").$param); }?>"  class="btn" id='btnNext'><?=$_LANG_TEXT["btnnext"][$lang_code];?><a>
			</div>
<?}?>
			<div class="right">
				<a href="./faq_list.php" class="btn"><?=$_LANG_TEXT['btnlist'][$lang_code]?></a>
<?if($faq_seq == ""){?>
				<a href="#" class="btn required-create-auth hide"  onclick="return FaqSubmit('CREATE')"><?=$_LANG_TEXT['btnsave'][$lang_code]?></a>
<?}else{?>
				<a href="#" class="btn required-update-auth hide"  onclick="return FaqSubmit('UPDATE')"><?=$_LANG_TEXT['btnsave'][$lang_code]?></a>
				<a href="#" class="btn required-delete-auth hide" onclick="FaqSubmit('DELETE')"><?=$_LANG_TEXT['btndelete'][$lang_code]?></a>
<?}?>
				<a href="./faq_reg.php" class="btn"><?=$_LANG_TEXT['btnclear'][$lang_code]?></a>
			</div>
		</div>

		</form>

		

	</div>

</div>


<?php

if($result) sqlsrv_free_stmt($result);  
sqlsrv_close($wvcs_dbcon);

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>