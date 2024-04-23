<?php
$page_name = "access_ip_config";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI']) - 1);
$_apos = stripos($_REQUEST_URI, "/");
if ($_apos > 0) {
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


?>
<script type="text/javascript">
	$(document).ready(function () {
		$(".search_select").select2();
	});

</script>
<div id="oper_input">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				<h1><span id='page_title'><?= $_LANG_TEXT["m_manage_accessip"][$lang_code]; ?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		<div class="page_right"><span style='cursor:pointer'
				onclick="history.back();"><?= $_LANG_TEXT["btngobeforepage"][$lang_code]; ?></span></div>

		<!--등록폼-->
		<form name="frmAccessIP" id="frmAccessIP" method="post">
			<input type='hidden' name='proc' id='proc'>
			<input type='hidden' name='proc_name' id='proc_name'>

			<table class="view">
				<tr>
					<th><label for='ip_addr'><?=$_LANG_TEXT["ipaddresstext"][$lang_code];?></label></th>
					<td style="padding: 0;">
						<table id='tbl_AllowIPAdress_List'>
							<tr>
								<td style='width: 310px;'>
									<input type="text" name="ip_addr[]" id="ip_addr" class="frm_input" value="<?php echo $ip_addr; ?>" style="width:280px" maxlength="20" placeholder="<? echo trsLang('IP 주소를 입력하세요', 'inputipaddresstext'); ?>">
								</td>
								<td style='text-align:left; padding-left: 0;'>
									<a href="javascript:void(0)" class='btn20 gray' style='width:10px;font-weight:bold;' onclick="appendRow_AllowIPAdress()">+</a>
									<a href="javascript:void(0)" class='btn20 gray' style='width:10px;font-weight:bold;' onclick="removeRow_AllowIPAdress()">-</a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr class="bg">
					<th><label for='allow_id'><?=$_LANG_TEXT["accesspermitid"][$lang_code];?></label></th>
					<td style="padding: 0;">
						<table id='tbl_AccessID_List'>
							<tr>
								<td style='width: 310px;'>
									<select name='allow_id[]' id="allow_id" class='search_select' style="width:300px;">
										<option value="" <? if ($allow_id == "")
											echo 'selected="selected"'; ?>>
											<? echo $_LANG_TEXT['choosetext'][$lang_code] ?>
										</option>
										<option value="ALL" <? if ($allow_id == "ALL")
											echo 'selected="selected"'; ?>>
											<? echo $_LANG_TEXT['alltext'][$lang_code] ?>
										</option>
										<?php

										$qry_label = QRY_COMMON_ADMIN_ALL;
										$qry_params = array();
										$sql = query($qry_label, $qry_params);
										$result = @sqlsrv_query($wvcs_dbcon, $sql);

										while ($row = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

											$_admin_id = $row['emp_no'];
											$_admin_name = aes_256_dec($row['emp_name']);
											?>
											<option value="<?php echo $_admin_id ?>" <?php if ($_admin_id == $allow_id) {
													echo ' selected="selected"';
												} ?>><?php echo $_admin_name; ?> (<?= $_admin_id ?>)
											</option>
											<?php
										}
										?>
									</select>
								</td>
								<td style='text-align:left; padding-left: 0;'>
									<a href="javascript:void(0)" class='btn20 gray' style='width:10px;font-weight:bold;' onclick="appendRow_AllowID()">+</a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th><?=$_LANG_TEXT["memotext"][$lang_code];?></th>
					<td>
						<input type="text" name="memo" id="memo" class="frm_input" value="<?php echo $memo; ?>" style="width:90%" maxlength="200" placeholder="<? echo trsLang('비고를 입력하세요', 'inputmemotext'); ?>">
					</td>
				</tr>
			</table>

			<div class="btn_wrap">
				<div class="right">
					<a href="./access_ip_config.php" class="btn" id="btnList"><?= $_LANG_TEXT["btnlist"][$lang_code]; ?></a>
					<a href="javascript:void(0)" onclick="AccessIPSubmit2('CREATE')" class="btn required-create-auth hide"><?= $_LANG_TEXT["btnregist"][$lang_code]; ?></a>
					<a href="./access_ip_reg.php" class="btn" id='btnClear'><?= $_LANG_TEXT["btnclear"][$lang_code]; ?></a>
				</div>
			</div>

		</form>

	</div>

</div>




<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>
<!-- access_ip_reg_multiple.php -->