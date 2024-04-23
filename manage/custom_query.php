<?php
$page_name = "custom_query";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI']) - 1);
$_apos = stripos($_REQUEST_URI,  "/");
if ($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

$custom_query_seq = $_POST['custom_query_seq'];

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
include_once $_server_path . "/" . $_site_path . "/inc/header.inc";
include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";

if($custom_query_seq != ""){
	$Model_utils = new Model_Utils();
	$args = array("custom_query_seq"=>$custom_query_seq);
	$result = $Model_utils->getQueryInfo($args);

	if($result){
		while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
			$query_content = $row['query_content'];
			$query_title = $row['query_title'];
			$query_enc = $row['query_enc'];
		}
	}
}
?>
<div id="user_list">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_query_editor"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>

		<!--tab 메뉴-->
		<ul class="tab">
			<li class="on" onclick="location.href='<? echo $_www_server?>/manage/custom_query.php'"><? echo trsLang('쿼리 검색 결과','query_search_result');?></li>
			<li  onclick="location.href='<? echo $_www_server?>/manage/query_saved.php'"><? echo trsLang('저장된 쿼리','saved_queries');?></li>

		</ul>

		<!--입력폼-->
		<form name="queryForm" id='queryForm' action="<?php echo $_SERVER[PHP_SELF] ?>" method="POST">
			<input type='hidden' name='proc_name' id='proc_name'>

			<table  class="search">
				<tr>
					<th style='widht:100px;'><?= $_LANG_TEXT['query_execute'][$lang_code] ?> </th>
					
					<td style='padding:5px 13px;'>
						
						<input type="button" value="<?= $_LANG_TEXT['execute'][$lang_code] ?>" class="btn_submit" onclick="return submitQuery();">
						<input type="button" value="<? echo trsLang('초기화','btnclear');?>" class="btn_submit_no_icon" onclick="location.href='<? echo $_www_server?>/manage/custom_query.php'">
						<input id="openModalBtn" type="button" value="<?= $_LANG_TEXT['btnsavetext'][$lang_code] ?>" class="btn_submit_save  required-create-auth hide">
						
					</td>
					<td style='text-align:right;font-weight:bold'>
						*<?= $_LANG_TEXT['customquerymaxrows'][$lang_code] ?>
					</td>
				</tr>
				<tr>
					<th style='widht:100px;'><?= $_LANG_TEXT['search_part'][$lang_code] ?> </th>
					<td colspan="2">
						<textarea class="frm_textarea" style="width:99%" id="query" name="query" rows="4" cols="50"><? echo $query_content?></textarea>
					</td>				
				</tr>
			</table>
		</form>
		<div id='query_result'><!--쿼리실행결과--></div>
	</div>
</div>

<div id="modal" class="modal">
  <div class="modal-content">
		<div class="" style="display:flex; align-items: center; justify-content:space-between; width:100%">
			<strong class="modal-title"><?php echo $_LANG_TEXT["query_content_ask_text"][$lang_code]; ?> </strong>
			<span class="close">&times;</span> 
		</div>
    <form id="frmSaveQuery" name="frmSaveQuery"  method="POST">
		 <input type='hidden' id='custom_query_seq' name='custom_query_seq' value='<? echo $custom_query_seq?>'>
		 <input type='hidden' id='query_enc' name='query_enc' value='<? echo $query_enc?>'>	
		<input type='hidden' id='proc' name='proc'>
		<input type='hidden'  name='proc_name'>
		  <div class="form-group">
			<input style="width:95%" class="frm_input" type="text" id="query_title" name="query_title"  value='<? echo $query_title?>'>
		  </div>
			<div class='btn_wrap' >
				<input type="button" value="<?= $_LANG_TEXT['btnsavetext'][$lang_code] ?>" class="btn_submit_save  required-create-auth hide" onclick="saveQuery()">
			</div>
    </form>
  </div>
</div>
<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>