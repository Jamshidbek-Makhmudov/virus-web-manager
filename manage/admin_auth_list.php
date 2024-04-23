<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache,must-revalidate");

{
    $page_name = "admin_auth_list";

    $_server_path = $_SERVER['DOCUMENT_ROOT'];
    $_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, (strLen($_SERVER['REQUEST_URI']) - 1));
    $_apos = stripos($_REQUEST_URI,  "/");

    if($_apos > 0) {
        $_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
    }

    $_site_path = $_REQUEST_URI;

    include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
    include_once $_server_path . "/" . $_site_path . "/inc/header.inc";
    include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";
}

{
    $params     = array();
    $page       = $_REQUEST["page"];        // 페이지
    $search_opt = $_REQUEST["search_opt"];	// 검색옵션
    $search_key = $_REQUEST["search_key"];	// 검색어
    $proc_name  = $_REQUEST['proc_name'];
    
	$search_use_yn      = $_REQUEST["search_use_yn"];
	$search_admin_level = $_REQUEST["search_admin_level"];

    if($search_opt!="") array_push($params, "search_opt={$search_opt}");
    if($search_key!="") array_push($params, "search_key={$search_key}");
    
    if($search_use_yn!="") array_push($params, "search_use_yn={$search_use_yn}");
    if($search_admin_level!="") array_push($params, "search_admin_level={$search_admin_level}");

    $param = implode($params, "&");
}

//검색 로그 기록
if ($proc_name != ""){
	$work_log_seq = WriteAdminActLog($proc_name, 'SEARCH');
}

{
    $Model_manage = new Model_manage();

    $args = array();
    $preset_title = "";

    if ($search_opt == "PRESET_TITLE") {
        $preset_title = $search_key;
    }
    
    $args   = @compact("preset_title");
    $total  = $Model_manage->getAdminMenuAuthPresetCount($args);

    $paging     = isset($paging) ? $paging : $_paging;
    $lists      = isset($lists)  ? $lists  : $_list;
    $page_count = ceil($total / $paging);

    if(empty($page) || ($page > $page_count)) {
        $page = 1;
    }

	$start  = ($page - 1) * $paging;
	$end    = $start + $paging;
    $no     = $total - $start;
    $args   = @compact("preset_title", "search_use_yn", "search_admin_level", "start", "end");

    $rows   = $Model_manage->getAdminMenuAuthPresetLIsts($args);
}

?>
<div id="oper_list">
	<div class="container">
		<div id="tit_area">
			<div class="tit_line">
				<h1><span id='page_title'><?=$_LANG_TEXT['managepageaccessauth'][$lang_code]?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		
		<!--검색폼-->
		<form name="searchForm" action="<?php echo basename(__FILE__)?>" method="POST">
            <input type="hidden" name="page" value="">	
            <input type="hidden" name="proc_name" id="proc_name" >	
            <table class="search">
				<colgroup>
					<col width="100">
					<col width="200">
					<col width="100">
					<col>
				</colgroup>
				<tr>
					<th><?= $_LANG_TEXT['menuauthapplylevel'][$lang_code] ?> </th>
					<td style="padding: 5px 13px;">
						<select name='search_admin_level' id='search_admin_level' style="min-width:150px" onchange="changeAdminLevel()">
							<option value=""><?php echo $_LANG_TEXT["alltext"][$lang_code]; ?></option>
							<option disabled>─────────</option>
							<?
							foreach ($_CODE['admin_level'] as $value => $name) {
								$selected = ($value == $search_admin_level) ? "selected=true" : "";
								echo "<option value='{$value}' $selected>{$name}</option>\n";
							}
							?>
                            <option value='NONE' <?php if($search_admin_level == "NONE") echo "selected"; ?>>미지정</option>;
						</select>
					</td>
					<th><?= $_LANG_TEXT['useyntext'][$lang_code] ?> </th>
					<td style="padding: 5px 13px;">
						<select name="search_use_yn" id="search_use_yn" style="min-width:150px">
							<option value=""><?php echo $_LANG_TEXT["alltext"][$lang_code]; ?></option>
							<option disabled>─────────</option>
							<option value="Y" <?php if($search_use_yn == "Y") echo "selected"; ?>><?php echo $_LANG_TEXT["useyestext"][$lang_code]; ?></option>
							<option value="N" <?php if($search_use_yn == "N") echo "selected"; ?>><?php echo $_LANG_TEXT["usenotext"][$lang_code]; ?></option>
						</select>
					</td>
				</tr>
                <tr>
                    <th><?=$_LANG_TEXT["usersearchtext"][$lang_code];?></th>
                    <td colspan="3">
                        <select name="search_opt" class="select_bg" id="search_opt" style="min-width:150px;height:31px;margin-top:1px;">
                            <option value="" <?php echo (empty($search_opt)) ? 'selected="selected"':''; ?>><?=$_LANG_TEXT["searchkeywordselecttext"][$lang_code];?></option>
                            <option value="PRESET_TITLE" <?php echo ($search_opt == "PRESET_TITLE") ? 'selected="selected"':''; ?>><?=$_LANG_TEXT["groupnametext"][$lang_code];?></option>
                        </select>
                        <input type="text" name="search_key" id="search_key"  value="<?=$search_key?>" class="frm_input" style="width:50%"   maxlength="50">
                        <input type="submit" value="<?=$_LANG_TEXT["btnsearch"][$lang_code];?>" class="btn_submit" onclick="SearchSubmit(document.searchForm);">
                    </td>
                </tr>
            </table>
		</form>
		
		<!--검색결과리스트-->
		<table class="list" style="margin-top:10px">
            <tr>
                <th class="num"><?=$_LANG_TEXT["numtext"][$lang_code];?></th>
                <th style='width:500px;'><?=$_LANG_TEXT["menupresettitle"][$lang_code];?></th>
                <th style='width:140px;'><?=$_LANG_TEXT["menuauthapplylevel"][$lang_code];?></th>
                <th style='width:100px;'><?=$_LANG_TEXT["authusedcount"][$lang_code];?></th>
                <th style='width:100px;'><?=$_LANG_TEXT["useyntext"][$lang_code];?></th>
                <th class="num_last"><?=$_LANG_TEXT["registdatetext"][$lang_code];?></th>
                <?php if (0) { ?><th class="num_last"><?=$_LANG_TEXT["deletetext"][$lang_code];?></th> <?php } ?>
            </tr>
	        <?php
            if ($rows) {
                foreach ($rows as $row) {
                    @extract($row);
                    $param_enc = ParamEnCoding("preset_seq={$preset_seq}&{$param}");
                    $admin_level = (empty($admin_level)) ? "미지정":$_CODE['admin_level'][$admin_level];
                    $create_date = date_format(date_create($create_date), "Y-m-d H:i");
            ?>	
            <tr onclick="sendPostForm('./admin_auth_reg.php?enc=<?=$param_enc?>')" style='cursor:pointer'>
                <td><?php echo $no; ?></td>
                <td style="text-align:left"><?php echo $preset_title; ?></td>
                <td><?php echo $admin_level; ?></td>
                <td><?php echo number_format($preset_used); ?></td>
                <td><?php echo ($use_yn=="Y")?"사용":"사용안함"; ?></td>
                <td class="num_last"><?php echo $create_date; ?></td>
                <?php if (0) { ?><td class="num_last">삭제</td><?php } ?>
            </tr>
            <?php
                    $no--;
                }
			} else {
			?>
				<tr>
					<td colspan="6"><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
				</tr>
			<?php
			}
			?>		
		</table>

		<!--페이징-->
        <?php
        $param_enc = "enc=" . ParamEnCoding($param);

		if ($total > 0) {
			print_pagelistNew3($page, $lists, $page_count, $param_enc, '', $total);
		}
        ?>
		<div class="btn_confirm">
			<a href="./admin_auth_reg.php?<?=$param_enc?>" class="btn required-create-auth hide"><?=$_LANG_TEXT["btnregist"][$lang_code];?></a>
		</div>

	</div>

</div>
<?php


if($result) sqlsrv_free_stmt($result);  
sqlsrv_close($wvcs_dbcon);

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>