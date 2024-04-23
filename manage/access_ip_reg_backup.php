<?php
$page_name = "access_ip_config";
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

$login_ip_mgt_seq = intVal($_REQUEST["login_ip_mgt_seq"]);
$searchopt = $_REQUEST['searchopt'];	// 검색옵션
$searchkey = $_REQUEST['searchkey'];	// 검색어
$orderby = $_REQUEST["orderby"];		//정렬

$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;


if($login_ip_mgt_seq <> "") {

		if($searchkey != "" && $searchopt != "") {
					
			if($searchopt=="ip"){


				$search_sql .= " and a.ip_addr like '%$searchkey%' "; 

			}else if($searchopt=="idorname"){  

	
				$search_sql .= " and (a.allow_id like '%$searchkey%' OR b.emp_name = '".aes_256_enc($searchkey)."' ) ";  
			
			}else if($searchopt=="memo"){


				$search_sql .= " and a.memo like '%$searchkey%' "; 
			}
		}

		if($orderby != "") {
			$order_sql = " ORDER BY $orderby ";
		} else {
			$order_sql = " ORDER BY login_ip_mgt_seq DESC ";
		}
		
		$qry_label = QRY_LOGIN_IP_LIMIT_INFO;
		$qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"login_ip_mgt_seq"=>$login_ip_mgt_seq);
		$sql = query($qry_label,$qry_params);
		$result = @sqlsrv_query($wvcs_dbcon, $sql);
		$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);


		$login_ip_mgt_seq = $row['login_ip_mgt_seq'];
		$ip_addr = $row['ip_addr'];
		$allow_id = $row['allow_id'];
		$memo = $row['memo'];
		$admin_name =aes_256_dec($row['emp_name']);	

		$create_date = $row['create_date'];
		
		$rnum = $row['rnum']; 

	
		//이전,다음
		$prev_sql = " AND rnum > '$rnum' ORDER BY rnum asc";
		$qry_label = QRY_LOGIN_IP_LIMIT_INFO_PREV_NEXT;
		$qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"prev_next_sql"=>$prev_sql);
		$sql = query($qry_label,$qry_params);

		$result = @sqlsrv_query($wvcs_dbcon, $sql);
		$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		$prev_login_ip_mgt_seq = $row['login_ip_mgt_seq'];
		
		$next_sql = " AND  rnum < '$rnum' ORDER BY rnum desc ";
		$qry_label = QRY_LOGIN_IP_LIMIT_INFO_PREV_NEXT;
		$qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"prev_next_sql"=>$next_sql);
		$sql = query($qry_label,$qry_params);

		$result = @sqlsrv_query($wvcs_dbcon, $sql);
		$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		$next_login_ip_mgt_seq = $row['login_ip_mgt_seq'];


}


?>
<script type="text/javascript">
	$(document).ready(function(){
	$(".search_select").select2();
});

</script>
<div id="oper_input">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				<h1><span id='page_title'><?=$_LANG_TEXT["m_manage_accessip"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		<div class="page_right"><span style='cursor:pointer'
				onclick="history.back();"><?=$_LANG_TEXT["btngobeforepage"][$lang_code];?></span></div>

		<!--등록폼-->
		<form name="frmAccessIP" id="frmAccessIP" method="post">
			<input type='hidden' name='proc' id='proc'>
			<input type='hidden' name='proc_name' id='proc_name'>
			<input type="hidden" name="login_ip_mgt_seq" id="login_ip_mgt_seq" value="<?php echo $login_ip_mgt_seq; ?>">
			<table class="view">
				<tr>
					<th><label for='ip_addr'><?=$_LANG_TEXT["ipaddresstext"][$lang_code];?></label></th>
					<td>
						<input type="text" name="ip_addr" id="ip_addr" class="frm_input" value="<?php echo $ip_addr; ?>"
							style="width:90%" maxlength="20">
					</td>
				</tr>
				<tr class="bg">
					<th><label for='allow_id'><?=$_LANG_TEXT["accesspermitid"][$lang_code];?></label></th>
					<td>
						<select name='allow_id' id="allow_id" class='search_select'>
							<option value="" <?if($allow_id=="" ) echo 'selected="selected"' ;?>>
								<? echo $_LANG_TEXT['choosetext'][$lang_code]?>
							</option>
							<option value="ALL" <?if($allow_id=="ALL" ) echo 'selected="selected"' ;?> >
								<? echo $_LANG_TEXT['alltext'][$lang_code]?>
							</option>
							<?php
				
					$qry_label = QRY_COMMON_ADMIN_ALL;
					$qry_params = array();
					$sql = query($qry_label,$qry_params);
					$result = @sqlsrv_query($wvcs_dbcon, $sql);
					
					while($row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){

						$_admin_id = $row['emp_no'];     
						 $_admin_name = aes_256_dec($row['emp_name']);	
					?>
							<option value="<?php echo $_admin_id ?>"
								<?php if($_admin_id == $allow_id) { echo ' selected="selected"'; } ?>><?php echo $_admin_name; ?> (<?= $_admin_id ?>)
							</option>
							<?php
					}
					?>
						</select>
					</td>
				</tr>
				<tr>
					<th><?=$_LANG_TEXT["memotext"][$lang_code];?></th>
					<td>
						<input type="text" name="memo" id="memo" class="frm_input" value="<?php echo $memo; ?>" style="width:90%"
							maxlength="200">
					</td>
				</tr>
				<?if($login_ip_mgt_seq != ""){?>
				<tr class="bg">
					<th><?=$_LANG_TEXT["registertext"][$lang_code];?></th>
					<td>
						<? echo $admin_name;?>
					</td>
				</tr>
				<tr>
					<th><?=$_LANG_TEXT["registdatetext"][$lang_code];?></th>
					<td>
						<? echo $create_date;?>
					</td>
				</tr>
				<?}?>
			</table>



			<div class="btn_wrap">
				<?php
		if ($login_ip_mgt_seq != "") {
?>
				<!-- agar  prev nect buttojnlari ishlamasa "sign_id_seq=" shu yerdagi  qoshtirnoqni tekshir auto savda xato beradi -->
				<div class="left display-none">
					<a href="<?if(empty($prev_login_ip_mgt_seq)){?>javascript:alert(qnodata[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."
						?enc=".ParamEnCoding("login_ip_mgt_seq=".$prev_login_ip_mgt_seq.($param ? " &" : "" ).$param); }?>"
						class="btn" id='btnPrev'><?=$_LANG_TEXT["btnprev"][$lang_code];?></a>
					<a href="<?if(empty($next_login_ip_mgt_seq)){?>javascript:alert(qnodata[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."
						?enc=".ParamEnCoding("login_ip_mgt_seq=".$next_login_ip_mgt_seq.($param ? " &" : "" ).$param); }?>"
						class="btn" id='btnNext'><?=$_LANG_TEXT["btnnext"][$lang_code];?><a>
				</div>
				<?php	}?>
				<div class="right">
					<a href="./access_ip_config.php" class="btn" id="btnList"><?=$_LANG_TEXT["btnlist"][$lang_code];?></a>
					<?php
					if ($login_ip_mgt_seq == "") {
?>
					<a href="javascript:void(0)" onclick="AccessIPSubmit('CREATE')" class="btn required-create-auth hide"><?=$_LANG_TEXT["btnregist"][$lang_code];?></a>
					<?php
					}else{
?>
					<a href="javascript:void(0)" onclick="AccessIPSubmit('UPDATE')" class="btn required-update-auth hide"><?=$_LANG_TEXT["btnsave"][$lang_code];?></a>
					<a href="javascript:void(0)" onclick="AccessIPSubmit('DELETE')" class="btn required-delete-auth hide"><?=$_LANG_TEXT["btndelete"][$lang_code];?></a>


					<?php
					}
?>
					<a href="./access_ip_reg.php" class="btn" id='btnClear'><?=$_LANG_TEXT["btnclear"][$lang_code];?></a>
				</div>
			</div>

		</form>

	</div>

</div>

<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>