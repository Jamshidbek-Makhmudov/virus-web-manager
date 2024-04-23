<?php
$page_name = "code_list";
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


$str_code_key = "'".implode("','",$_CODE_LIST)."'";
$search_sql = " and code_key in ($str_code_key) ";

$qry_params = array("show_yn"=>"Y", "search_sql"=>$search_sql);
$qry_label = QRY_CODE_LIST;
$sql = query($qry_label,$qry_params);

//echo nl2br($sql);

$result = sqlsrv_query($wvcs_dbcon, $sql);
if($result){
	while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

		$div[$row['p_code_seq']][$row['code_seq']] = array($row['code_key'],$row['code_name'],$row['depth'],$row['sort'],$row['fix_yn'],$row['use_yn'],$row['create_dt']);
	}
}

?>
<div id="oper_list">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_manage_code"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>

		<div class="right">
			<a href="javascript:void(0)" onclick="sendPostForm('./code_reg.php?gubun=detail')" class="btn2 required-create-auth hide"><?=$_LANG_TEXT['btnregist'][$lang_code]?></a>
		</div>
		
		<!--검색결과리스트-->
<?
		$p_code_seq = 0;
		$div1 = $div[$p_code_seq];
		$div1_cnt = sizeof($div1);

		if($div1_cnt > 0){
			foreach($div1 as $code_seq => $data){	
				
				$div1_p_code_seq = 0;

				list($code_key,$code_name,$depth,$sort,$fix_yn,$use_yn,$create_dt) = $data;

				$param_enc = ParamEnCoding("gubun=cate&code_key=".$code_key."&cate_code_seq=".$code_seq);
		?>
				<table class="list" style="margin-top:30px">
				<caption class="tit2" onclick="location.href='./code_reg.php?enc=<?=$param_enc?>'" style='cursor:pointer'><?=$code_name?></caption>
				<tr>
					<th style='width:100px'><?=$_LANG_TEXT['numtext'][$lang_code]?></th>
					<th style='width:400px'><?=$_LANG_TEXT['codevaluetext'][$lang_code]?></th>
					<th style='width:100px'><?=$_LANG_TEXT['sortordertext'][$lang_code]?></th>
					<th style='width:100px'><?=$_LANG_TEXT['useyntext'][$lang_code]?></th>
					<th style='width:100px'><?=$_LANG_TEXT['registerdatetext'][$lang_code]?></th>
					<th class="num_last"><?=$_LANG_TEXT['deletetext'][$lang_code]?></th>
				</tr>
		<?
				$div2_p_code_seq = $code_seq;
				$div2 = $div[$div2_p_code_seq];
				$cnt = sizeof($div2);
				
				if($cnt > 0){
					foreach($div2 as $code_seq => $data){	

						list($code_key,$code_name,$depth,$sort,$fix_yn,$use_yn,$create_dt) = $data;

						$param_enc = ParamEnCoding("gubun=detail&code_key=".$code_key."&detail_code_seq=".$code_seq);

			?>
						<tr  onclick="sendPostForm('./code_reg.php?enc=<?=$param_enc?>')" style='cursor:pointer'>
							<td ><?=$cnt?></td>
							<td><?=$code_name?></td>
							<td><?=$sort?></td>
							<td><?=$use_yn?></td>
							<td><?=$create_dt?></td>
							<td class='num_last' onclick="event.stopPropagation();">
								<?if($fix_yn=="Y"){?>-<?}else{?><span  onclick="MngCodeDeleteSubmit('<?=$code_seq?>')" style='cursor:pointer' class=' required-delete-auth hide'><?=$_LANG_TEXT['btndelete'][$lang_code]?></span><?}?>
								
							</td>
						</tr>
			<?			
						$cnt--;
					} //foreach($div2 as $code_seq => $data){
				}else{
					echo "<tr><td colspan='6'>".$_LANG_TEXT["nodata"][$lang_code]."</td></tr>";
				}
		?>
				</table>
		<?
			}//foreach($div1 as $code_seq => $data){
		}
?>

		<div class="btn_confirm">
			<a href="javascript:void(0)" onclick="sendPostForm('./code_reg.php?gubun=detail')" class="btn required-create-auth hide"><?=$_LANG_TEXT['btnregist'][$lang_code]?></a>
		</div>

	</div>

</div>
<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>