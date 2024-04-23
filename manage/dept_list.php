<?php
$page_name = "dept_list";
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


$org_id		= $_REQUEST["sel_org2"];
$p_dept_seq = $_REQUEST["p_dept_seq2"];
$searchopt = $_REQUEST["searchopt"];	// 검색옵션
$searchkey = $_REQUEST["searchkey"];	// 검색어
$page = $_REQUEST[page];				// 페이지
if($paging == "") $paging = $_paging;

$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($org_id!="") $param .= ($param==""? "":"&")."sel_org2=".$org_id;
if($p_dept_seq!="") $param .= ($param==""? "":"&")."p_dept_seq2=".$p_dept_seq;

/*소속기관*/
$qry_params = array();
$qry_label = QRY_COMMON_ORG;
$sql = query($qry_label,$qry_params);

$result = sqlsrv_query($wvcs_dbcon, $sql);
if($result){
	while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
			$arr_org[$row['org_id']] = $row['org_name'];
	}
}

/*부서*/
$qry_params = array();
$qry_label = QRY_COMMON_DEPT;
$sql = query($qry_label,$qry_params);

$result = sqlsrv_query($wvcs_dbcon, $sql);
if($result){
	while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
		
		$arr_dept[$row['dept_seq']] = array($row['org_id'],($row['lvl']==""?$row['org_name']."-":$row['lvl']).$row['dept_name']);
	}
}
?>
<script language="javascript">
	$("document").ready(function(){
		MngDeptListSubOrgSet();
	});
</script>
<div id="oper_list">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_manage_department"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		
		<!--검색폼-->
		<form name="searchForm" id="searchForm" method="GET">
		<table class="search">
		<tr>
			<th><?=$_LANG_TEXT['usersearchtext'][$lang_code]?></th>
			<td>
				
				<select name="sel_org2" id="sel_org2" class="select_bg" onchange="MngDeptListSubOrgSet();">
					<option value=""><?=$_LANG_TEXT['organselecttext'][$lang_code]?></option>
			
				<?php
					foreach($arr_org as $key => $name){
						$tmp_org_id = $key;
						$tmp_org_name = $name;
						echo "<option value='$tmp_org_id' ".($tmp_org_id==$org_id ? "selected='selected'" :"").">$tmp_org_name</option>";
					}
				?>
				</select>
				<select name="p_dept_seq2" id="p_dept_seq2" >
					<option value="" org=''><?=$_LANG_TEXT['superdeptselecttext'][$lang_code]?></option>
				
				<?php
					foreach($arr_dept as $key => $value){

							$tmp_dept_seq = $key;
							list($tmp_org_id,$tmp_dept_name) = $value;

							echo "<option value='$tmp_dept_seq' org='$tmp_org_id' ".($p_dept_seq==$tmp_dept_seq ? "selected='selected'" :"").">$tmp_dept_name</option>";
					}
				?>
				</select>

				<select name="searchopt" class="select_bg" id="searchopt">
					<option value="dept_name" <?=($searchopt=="dept_name"? "selected='selected'" : "") ?>><?=$_LANG_TEXT['deptnametext'][$lang_code]?></option>
					<option value="emp_name" <?=($searchopt=="emp_name"? "selected='selected'" : "") ?>><?=$_LANG_TEXT['deptchieftext'][$lang_code]?></option>
				</select>					
				<input type="text" name="searchkey" id="searchkey"  value="<?=$searchkey?>" class="frm_input" onKeyPress="if(event.keyCode==13){return DeptSearchSubmit(document.searchForm);}" style='width:250px;'   maxlength="50">
				<input type="submit" value="<?=$_LANG_TEXT['btnsearch'][$lang_code]?>" class="btn_submit"  onclick="DeptSearchSubmit(document.searchForm);" >
			</td>
		</tr>
		</table>
		
		<div class="btn_confirm">
			<a href="./dept_reg.php" class="btn"><?=$_LANG_TEXT['btnregist'][$lang_code]?></a>
		</div>

		</form>
	
		<!--검색결과리스트-->
		<table class="list" >
			<tr>
				<th style='width:100px;min-width:100px;'><?=$_LANG_TEXT['numtext'][$lang_code]?></th>
				<th style='width:100px;min-width:150px;'><?=$_LANG_TEXT['organtext'][$lang_code]?></th>
				<th style='width:100px;min-width:150px;'><?=$_LANG_TEXT['deptnametext'][$lang_code]?></th>
				<th style='width:100px;min-width:80px;'><?=$_LANG_TEXT['deptchieftext'][$lang_code]?></th>
				<th style='width:80px;min-width:80px;'><?=$_LANG_TEXT['useyntext'][$lang_code]?></th>
				<th style='width:60px;min-width:60px;'><?=$_LANG_TEXT['sorttext'][$lang_code]?></th>
				<th style='width:100px;min-width:100px;'><?=$_LANG_TEXT['deptauthtext'][$lang_code]?></th>
				<th class="num_last"><?=$_LANG_TEXT['deletetext'][$lang_code]?></th>
			</tr>
			
			<?php

				if($org_id !=""){
					$search_sql .= " and p.org_id = '$org_id'";
				}

				if($p_dept_seq != "" && $p_dept_seq != "0"){
					$search_sql .= " and p.p_dept_seq = '$p_dept_seq'";
				}

				if($searchkey != "" && $searchopt != "") {
					$search_sql .= " and $searchopt like '%$searchkey%' ";
				}


				$qry_params = array("search_sql"=>$search_sql);
				$qry_label = QRY_DEPT_LIST_COUNT;
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
				
				
				$qry_params = array("end"=>$end,"search_sql"=>$search_sql,"start"=>$start);
				$qry_label = QRY_DEPT_LIST;
				$sql = query($qry_label,$qry_params);
							
				//echo $sql;
				$result = sqlsrv_query($wvcs_dbcon, $sql);

				$cnt = 20;
				$iK = 0;
				$classStr = "";

				 if($result){
				  while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

						$cnt--;
						$iK++;
						
						$org_id = $row['org_id'];
						$org_name = $row['org_name'];
						$dept_seq = $row['dept_seq'];
						$lvl = $row['lvl'];
						$dept_name = $row['dept_name'];
						$p_dept_seq = $row['p_dept_seq'];
						$sort = $row['sort'];
						$dept_chief = aes_256_dec($row['emp_name']);	
						$dept_chief_seq = $row['chief_emp_seq'];
						$use_yn = $row['use_yn'];
						$dept_auth1 = $row['dept_auth1'];
						$dept_auth2 = $row['dept_auth2'];
						$dept_auth3 = $row['dept_auth3'];

						$param_enc = ParamEnCoding("dept_seq=".$dept_seq.($param ? "&" : "").$param);

					  ?>	
						<tr onclick="javascript:location.href='./dept_reg.php?enc=<?=$param_enc?>'" style='cursor:pointer'>
							<td><?=$no?></td>
							<td><?=$org_name?></td>
							<td style='text-align:left;padding-left:5px'><?=$lvl.$dept_name?></td>
							<td><?=$dept_chief?></td>
							<td><?=$use_yn?></td>
							<td><?=$sort?></td>
							<td><?=$dept_auth1.$dept_auth2.$dept_auth3?></td>
							<td class="num_last" onclick="event.stopPropagation();" ><span  onclick="DepartmentDeleteSubmit('<?=$dept_seq?>')" style='cursor:pointer;' ><?=$_LANG_TEXT['btndelete'][$lang_code]?></span></td>
						</tr>
						<?php
						
							$no--;
						}
						
					}

					if($total < 1) {
						
					?>
						<tr>
							<td colspan="8" align="center"><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
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
		<div class="btn_confirm">
			<a href="./dept_reg.php" class="btn"><?=$_LANG_TEXT['btnregist'][$lang_code]?></a>
		</div>
	</div>

</div>
<?php

if($result)	sqlsrv_free_stmt($result);  
sqlsrv_close($wvcs_dbcon);

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";
?>