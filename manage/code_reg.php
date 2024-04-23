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

$gubun    = $_REQUEST['gubun'];
$code_key = $_REQUEST['code_key'];
$cate_code_seq = $_REQUEST['cate_code_seq'];
$detail_code_seq = $_REQUEST['detail_code_seq'];

if($gubun=="") $gubun = "detail";

//**코드분류
if($gubun=="cate"){

	//슈퍼관리자만 코드분류 등록가능
	if($_ck_user_id!="dptadmin" && $_ck_user_id!="wvcsadmin"){
		header("location:./code_reg.php?gubun=detail");
		exit;
	}

	if($cate_code_seq != ""){
	
		
		$qry_params = array("cate_code_seq"=>$cate_code_seq);
		$qry_label = QRY_CODE_INFO_CATE;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);
		if($result){
			$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		
			$code_key = $row['code_key'];
			$code_name = $row['code_name'];
			$sort = $row['sort'];
			$fix_yn = $row['fix_yn'];
			$use_yn = $row['use_yn'];
			$p_code_seq = $row['p_code_seq'];
		}
	}

//echo $sql;

//**코드분류상세
}else{

	if($code_key != "" && $detail_code_seq != ""){


		$qry_params = array("code_key"=>$code_key,"detail_code_seq"=>$detail_code_seq);
		$qry_label = QRY_CODE_INFO;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);

		if($result){

			$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

			$code_key = $row['code_key'];
			$code_name = $row['code_name'];
			$sort = $row['sort'];
			$fix_yn = $row['fix_yn'];
			$use_yn = $row['use_yn'];
			$p_code_seq = $row['p_code_seq'];
			$rnum = $row['rnum'];
			$refer_val = $row['refer_val'];

		}

		$cate_code_seq = $p_code_seq;


		//이전,다음
		$qry_params = array("code_key"=>$code_key,"rnum"=>$rnum);
		$qry_label = QRY_CODE_INFO_PREV;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);
		$row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
		$prev_code_seq  = $row['code_seq'];

		$qry_params = array("code_key"=>$code_key,"rnum"=>$rnum);
		$qry_label = QRY_CODE_INFO_NEXT;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);
		$row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
		$next_code_seq  = $row['code_seq'];

	}

}//if($gubun=="cate"){

include_once $_server_path . "/" . $_site_path . "/inc/header.inc";
include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";
?>
<script language="javascript">
	$(function(){
		
		var gubun = "<?=$gubun?>";

		if(gubun=="cate"){
			$( "#btnCodeCate" ).trigger( "click" );
		}else if(gubun=="detail"){
			$( "#btnCodeDetail" ).trigger( "click" );
		}
	});
</script>
<div id="oper_input">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_manage_code"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		

		<form name="" action="">
		<input type='hidden' name='code_gubun' id='code_gubun' value="<?=$gubun?>">
		<input type='hidden' name='proc' id='proc'>
		<ul class="tab">
			<? if($_ck_user_id=="dptadmin" || $_ck_user_id=="wvcsadmin"){	//슈퍼관리자만 코드분류등록가능?>
			<li class="on">
				<a href="javascript:void(0)" id="btnCodeCate"><span onclick="sendPostForm('<? echo $_www_server?>/manage/code_reg.php?enc=<?=ParamEnCoding("gubun=cate&code_key=".$code_key."&cate_code_seq=".$cate_code_seq."&detail_code_seq=".$detail_code_seq)?>')"><?=$_LANG_TEXT['addcodeclassificationtext'][$lang_code]?></span></a>
			</li>	
			<?}?>
			<li class="on">
				<a href="javascript:void(0)" id="btnCodeDetail" ><span onclick="sendPostForm('<? echo $_www_server?>/manage/code_reg.php?enc=<?=ParamEnCoding("gubun=detail&code_key=".$code_key."&cate_code_seq=".$cate_code_seq."&detail_code_seq=".$detail_code_seq)?>')"><?=$_LANG_TEXT['addcodedetailclassificationtext'][$lang_code]?></span></a>
			</li>
		</ul>

		
		<?if($gubun=="cate"){?>
		<div>
			<input type='hidden' name='cate_p_code_seq' id='cate_p_code_seq' value='<?=$p_code_seq?>'>
			<input type='hidden' name='cate_code_seq' id='cate_code_seq' value='<?=$cate_code_seq?>'>
			<table class="view">
				<tr >
					<th><?=$_LANG_TEXT['codeclassificationvaluetext'][$lang_code]?></th>
					<td colspan='3'><input type='text' name='cate_code_key' id='cate_code_key' class="frm_input" style="width:250px" value="<?=$code_key?>"   maxlength="30">     <?=$_LANG_TEXT['codeclassificationvaluecreateguidetext'][$lang_code]?></td>
				</tr>
				<tr class="bg">
					<th style='width:150px'><?=$_LANG_TEXT['codeclassificationnametext'][$lang_code]?></th>
					<td  colspan='3'>
						<input type='text' name='cate_code_name' id='cate_code_name' class="frm_input" style="width:250px" value="<?=$code_name?>"   maxlength="50">
					</td>
				</tr>
				<tr >
					<th  style='width:150px'><?=$_LANG_TEXT['sortordertext'][$lang_code]?></th>
					<td  style='width:300px'>
						<input type='text' name='cate_sort' id='cate_sort' style="width:20%" class="frm_input"  onkeyup="onlyNumber(this)" value="<?=$sort?>"   maxlength="5">
					</td>
					<th  style='width:150px' class="line"><?=$_LANG_TEXT['useyntext'][$lang_code]?></th>
					<td>
						<select name='cate_useyn' id='cate_useyn'>
							<option value='Y' <?if($use_yn=="Y") echo "selected='selected'";?>><?=$_LANG_TEXT['useyestext'][$lang_code]?></option>
							<option value='N' <?if($use_yn=="N") echo "selected='selected'";?>><?=$_LANG_TEXT['usenotext'][$lang_code]?></option>
						</select>
					</td>
				</tr>
			</table>
		</div>
		<?}?>
		
		<?if($gubun=="detail"){?>
		<div>
			<input type='hidden' name='code_seq' id='code_seq' value='<?=$detail_code_seq?>'>
			<table class="view">
				<tr>
					<th style='width:150px'><?=$_LANG_TEXT['codeclassificationnametext'][$lang_code]?></th>
					<td style='width:300px'>
						<select id='code_key' name='code_key' onchange="changeCodeKey()">
							<option value='' ><?=$_LANG_TEXT['choosetext'][$lang_code]?></option>
							<?
								$str_code_key = "'".implode("','",$_CODE_LIST)."'";
								$search_sql = " and code_key in ($str_code_key)";

								$qry_params = array("show_yn"=>"Y","search_sql"=>$search_sql);
								$qry_label = QRY_CODE_CATE_LIST;
								$sql = query($qry_label,$qry_params);
								
								$result = sqlsrv_query($wvcs_dbcon, $sql);

								while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
									
									echo "<option value='".$row['code_key']."' ".($code_key==$row['code_key']? "selected='selected'" : "")." p_code_seq='".$row['code_seq']."'>".$row['code_name']."</option>";
								}
							?>
						</select>
					</td>
					<th  style='width:150px' class="line"><?=$_LANG_TEXT['codevaluetext'][$lang_code]?></th>
					<td>
						<!--카카오뱅크 IDC_CENTER 검사장 지정-->
						<span id='wrap_scan_center_code' <? if($code_key !="IDC_CENTER"){ echo "style='display:none'";}?>>
							<select id='scan_center_code' name='scan_center_code'>
								<option value=''><? echo trsLang('검사장선택','scancenterchoosetext');?></option>
								<?php
								$Model_manage = new Model_manage;
								$args = array(
									"search_sql"=> " and cn.scan_center_div='IDC' "
								);
								$result = $Model_manage->getCenterList($args);
								
								if($result){
									while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

										$_scan_center_code = $row['scan_center_code'];
										$_scan_center_name = $row['scan_center_name'];
								?>
								<option value='<?=$_scan_center_code?>' <?if($_scan_center_code==$refer_val) echo "selected" ;?>
									><?=$_scan_center_name?></option>
								<?php
									}
								}
								?>
							</select>
						</span>
						<input type='text' name='code_name' id='code_name' class="frm_input" style="250px" value="<?=$code_name?>"   maxlength="50">
					</td>
				</tr>
				<tr class="bg">
					<th><?=$_LANG_TEXT['sortordertext'][$lang_code]?></th>
					<td>
						<input type='text' name='sort' id='sort' style="width:20%" class="frm_input"  onkeyup="onlyNumber(this)" value="<?=$sort?>"   maxlength="5">
					</td>
					<th class="line"><?=$_LANG_TEXT['useyntext'][$lang_code]?></th>
					<td>
						<select name='useyn' id='useyn'>
							<option value='Y' <?if($use_yn=="Y") echo "selected='selected'";?>><?=$_LANG_TEXT['useyestext'][$lang_code]?></option>
							<option value='N' <?if($use_yn=="N") echo "selected='selected'";?>><?=$_LANG_TEXT['usenotext'][$lang_code]?></option>
						</select>
					</td>
				</tr>
			</table>
		</div>
		<?}?>



		
		<!--등록폼-->
	
		<div class="btn_wrap">
<?if($gubun=="detail" && $detail_code_seq !=""){?>
			<div class="left display-none">
				<a href="<?if(empty($prev_code_seq)){?>javASCript:alert(nodatatext[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."?enc=".ParamEnCoding("gubun=".$gubun."&code_key=".$code_key."&detail_code_seq=".$prev_code_seq); }?>"  class="btn" id='btnPrev'><?=$_LANG_TEXT["btnprev"][$lang_code];?></a>
				<a href="<?if(empty($next_code_seq)){?>javASCript:alert(nodatatext[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."?enc=".ParamEnCoding("gubun=".$gubun."&code_key=".$code_key."&detail_code_seq=".$next_code_seq); }?>"  class="btn" id='btnNext'><?=$_LANG_TEXT["btnnext"][$lang_code];?><a>
			</div>
<?}?>
			<div class="right">
				<a href="./code_list.php" class="btn"><?=$_LANG_TEXT['btnlist'][$lang_code]?></a>
<?
if($gubun=="cate"){
	$btnRegShow = ($cate_code_seq=="");
}else{
	$btnRegShow = ($detail_code_seq=="");
}
?>
<?if($btnRegShow){?>
				<a href="javascript:void(0)" id="btnReg" class="btn required-create-auth hide" onclick="return MngCodeSubmit('CREATE')"><?=$_LANG_TEXT['btnregist'][$lang_code]?></a>
<?}else{
	if($fix_yn=="Y"){?>
				<a href="javascript:void(0)" class="btn" ><font color='red'><?=$_LANG_TEXT['unchangeabletext'][$lang_code]?></fonr></a>
	<?}else{?>
				<a href="javascript:void(0)" id="btnEdit" class="btn required-update-auth hide" onclick="return MngCodeSubmit('UPDATE')"><?=$_LANG_TEXT['btnsave'][$lang_code]?></a>
				<a href="javascript:void(0)" id='btnDelete' class="btn required-delete-auth hide" onclick="return MngCodeSubmit('DELETE')"><?=$_LANG_TEXT['btndelete'][$lang_code]?></a>
<?	}
}?>
				<a href="javascript:void(0)" onclick="sendPostForm('./code_reg.php?gubun=<?=$gubun?>')" id="btnClear" class="btn" ><?=$_LANG_TEXT['btnclear'][$lang_code]?></a>
			</div>
		</div>

		</form>

	</div>

</div>

<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>