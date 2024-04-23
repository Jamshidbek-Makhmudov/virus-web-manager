<?php
$page_name = "app_update";
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

$searchopt = $_REQUEST[searchopt];	// 검색옵션
$searchkey = $_REQUEST[searchkey];	// 검색어
$orderby = $_REQUEST[orderby];		// 정렬순서
$gubun = $_REQUEST[gubun];		
$file_type = $_REQUEST[file_type];		
$page = $_REQUEST[page];			// 페이지
if($paging == "") $paging = $_paging;

$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;
if($gubun!="") $param .= ($param==""? "":"&")."gubun=".$gubun;
if($file_type!="") $param .= ($param==""? "":"&")."file_type=".$file_type;

?>
<div id="oper_list">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_manage_appupdate"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		
		<!--검색폼-->
		<form name="searchForm" action="<?php echo $_SERVER[PHP_SELF]?>" method="GET">
		<input type="hidden" name="page" value="">	
		<table class="search">
		<tr>
			<th><?=$_LANG_TEXT['usersearchtext'][$lang_code]?></th>
			<td >
				<select id='gubun' name='gubun'>
					<option value=""><?=$_LANG_TEXT['gubunselecttext'][$lang_code]?></option>
				<?
				$option = $_CODE['app_gubun'];
				foreach($option as $value => $name){
					
					echo "<option value='$value' ".($gubun==$value? "selected=true" : "").">$name</option>";
				}
				?>
				</select>
				<select id='file_type' name='file_type'>
					<option value=""><?=$_LANG_TEXT['filetypeselecttext'][$lang_code]?></option>
				<?
				$option = $_CODE['app_file_type'];
				foreach($option as $value => $name){
					
					echo "<option value='$value' ".($gubun==$value? "selected=true" : "").">$name</option>";
				}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<th><?=$_LANG_TEXT['userdetailsearchtext'][$lang_code]?></th>
			<td>
				<select name="searchopt" id="searchopt">
					<option value=""><?=$_LANG_TEXT['searchkeywordselecttext'][$lang_code]?></option>
					<option value="APPFILE" <?php if($searchopt == "APPFILE") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['appnfilenametext'][$lang_code]?></option>
					<option value="MEMO" <?php if($searchopt == "MEMO") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['memotext'][$lang_code]?></option>
				</select>

				<input type="text" name="searchkey" id="searchkey"  value="<?=$searchkey?>"  class="frm_input" style="width:50%"   maxlength="50">
				<input type="submit" value="<?=$_LANG_TEXT['btnsearch'][$lang_code]?>" class="btn_submit" onclick="SearchSubmit(document.searchForm);">
			</td>
		</tr>
		</table>
		
		<div class="btn_confirm">
			
			<a href="./app_update_reg.php" class="btn  required-create-auth hide"><?=$_LANG_TEXT['btnregist'][$lang_code]?></a>
		</div>

		</form>

		<!--검색결과리스트-->
		<table class="list">
		<tr>
			<th  style='width:100px;'><?=$_LANG_TEXT['numtext'][$lang_code]?></th>
			<th  style='width:100px;'><?=$_LANG_TEXT['gubuntext'][$lang_code]?></th>
			<th  style='width:100px;'><?=$_LANG_TEXT['filetypetext'][$lang_code]?></th>
			<th  style='min-width:100px;'><?=$_LANG_TEXT['appnametext'][$lang_code]?></th>
			<th  style='min-width:100px;'><?=$_LANG_TEXT['filenametext'][$lang_code]?></th>
			<th  style='min-width:100px;'><?=$_LANG_TEXT['attachfiletext'][$lang_code]?></th>
			<th  style='width:150px;'><?=$_LANG_TEXT['updatetimetext'][$lang_code]?></th>
			<th  style='width:80px;'><?=$_LANG_TEXT['useyntext'][$lang_code]?></th>
			<th  style='min-width:100px;' ><?=$_LANG_TEXT['memotext'][$lang_code]?></th>
		</tr>
<?php

	  if($gubun != ""){

		  $search_sql .= " and gubun ='$gubun' ";
	  }

	  if($file_type != ""){

		  $search_sql .= " and file_type ='$file_type' ";
	  }
	  
	  if($searchkey != ""){

		  if($searchopt=="APPFILE"){

			$search_sql .= " and (app_name like '%$searchkey%' OR real_name like '%$searchkey%' ) ";

		  }else if($searchopt == "MEMO"){
			
			$search_sql .= " and memo like '%$searchkey%' ";
		  
		  }
	  }

		$qry_params = array("search_sql"=>$search_sql);
		$qry_label = QRY_APP_UPDATE_LIST_COUNT;
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
			$order_sql = " ORDER BY app_seq DESC ";
		}

	
		$qry_params = array("end"=>$end,"order_sql"=>$order_sql,"search_sql"=>$search_sql,"start"=>$start);
		$qry_label = QRY_APP_UPDATE_LIST;
		$sql = query($qry_label,$qry_params);
									
		//echo $sql;
// echo nl2br($sql);
		$result =@sqlsrv_query($wvcs_dbcon, $sql);
		
		$cnt = 20;
		$iK = 0;
		$classStr = "";

		if($result){
		  while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

				$cnt--;
				$iK++;
				
				$app_seq = $row['app_seq'];
				$gubun = $_CODE['app_gubun'][$row['gubun']];
				$file_type = $_CODE['app_file_type'][$row['file_type']];
				$app_name = $row['app_name'];
				$file_name = $row['real_name'];
				$patch_date = $row['patch_dt'];
				$memo = $row['memo'];
				$use_yn = $row['use_yn'];

				if(substr($patch_date,0,10)=="1900-01-01"){	//매일 특정시간업데이트
					$patch_time = substr($patch_date,11,2);
					$str_patch_date = trsLang("매일","everyday")." ".$patch_time.trsLang('시','hourtimetext');
				}else{
					$str_patch_date=$patch_date;
				}
				
				$str_app_name = $_CODE_UPDATE_APP_NAME[$app_name];
				if($str_app_name=="") $str_app_name = $app_name;

		
				
				if($row['file_name']){
					$file = "<a href=\"{$_www_server}/common/download.php?enc=".ParamEnCoding("file=".$_SERVER['DOCUMENT_ROOT'].$row['server_path'].$row['file_name'])."\" class='required-print-auth'><img src='$_www_server/images/file.png' ></a>";
				}else{
					$file = "";
				}

				$param_enc = ParamEnCoding("app_seq=".$app_seq.($param ? "&":"").$param);

		  ?>	
			<tr onclick="sendPostForm('app_update_reg.php?enc=<?=$param_enc?>')" style='cursor:pointer'>
				<td><?php echo $no; ?></td>
				<td><?=$gubun?></td>
				<td><?=$file_type?></td>
				<td><?=$str_app_name?></td>
				<td><?=$file_name?></td>
				<td onclick="event.stopPropagation();"><?=$file?></td>
				<td><?=$str_patch_date?></td>
				<td><?=$use_yn?></td>
				<td class='num_last'><?=trim_str($memo,13)?></td>
			</tr>
			<?php
			
				$no--;
			}
				
		}

		if($result) sqlsrv_free_stmt($result);  
		if($wvcs_dbcon) sqlsrv_close($wvcs_dbcon);
		if($total < 1) {
			
		?>
			<tr>
				<td colspan="9" align='center'><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
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
		</table>

		<div class="btn_confirm">
			<a href="./app_update_reg.php" class="btn  required-create-auth hide"><?=$_LANG_TEXT['btnregist'][$lang_code]?></a>
		</div>

	</div>

</div>


<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>

