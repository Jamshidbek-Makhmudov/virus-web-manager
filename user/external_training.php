<?php
$page_name = "external_training";

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI']) - 1);
$_apos = stripos($_REQUEST_URI,  "/");
if ($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;


include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
include_once $_server_path . "/" . $_site_path . "/inc/header.inc";
include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";



$searchopt = $_REQUEST[searchopt];	// 검색옵션
$searchkey = $_REQUEST[searchkey];	// 검색어
$orderby = $_REQUEST[orderby];		// 정렬순서
$page = $_REQUEST[page];
$paging = $_REQUEST[paging];			// 페이지
$start_date = $_REQUEST[start_date];
$end_date = $_REQUEST[end_date];

$searchandor1 = $_REQUEST[searchandor1];
$searchandor2 = $_REQUEST[searchandor2];
$searchandor3 = $_REQUEST[searchandor3];
$searchoptm1 = $_REQUEST[searchoptm1];
$searchoptm2 = $_REQUEST[searchoptm2];
$searchoptm3 = $_REQUEST[searchoptm3];
$searchkeym1 = $_REQUEST[searchkeym1];
$searchkeym2 = $_REQUEST[searchkeym2];
$searchkeym3 = $_REQUEST[searchkeym3];

$searchopt4 = $_REQUEST[searchopt4];
if($searchopt4=="") $searchopt4="train_date";

$seq_val="train_seq";
if ($paging == "") $paging = $_paging;

if($useyn=="") $useyn ="Y";

if ($start_date == "") $start_date = date("Y-m-d", strtotime(date("Y-m-d") . " -1 month"));
if ($end_date == "") $end_date = date("Y-m-d");

$param = "";
if ($searchopt != "") $param .= ($param == "" ? "" : "&") . "searchopt=" . $searchopt;
if ($searchkey != "") $param .= ($param == "" ? "" : "&") . "searchkey=" . $searchkey;
if ($orderby != "") $param .= ($param == "" ? "" : "&") . "orderby=" . $orderby;
if ($start_date != "") $param .= ($param == "" ? "" : "&") . "start_date=" . $start_date;
if ($end_date != "") $param .= ($param == "" ? "" : "&") . "end_date=" . $end_date;


if ($searchoptm1 != "") $param .= ($param == "" ? "" : "&") . "searchoptm1=" . $searchoptm1;
if ($searchoptm2 != "") $param .= ($param == "" ? "" : "&") . "searchoptm2=" . $searchoptm2;
if ($searchoptm3 != "") $param .= ($param == "" ? "" : "&") . "searchoptm3=" . $searchoptm3;
if ($searchkeym1 != "") $param .= ($param == "" ? "" : "&") . "searchkeym1=" . $searchkeym1;
if ($searchkeym2 != "") $param .= ($param == "" ? "" : "&") . "searchkeym2=" . $searchkeym2;
if ($searchkeym3 != "") $param .= ($param == "" ? "" : "&") . "searchkeym3=" . $searchkeym3;
if ($searchandor1 != "") $param .= ($param == "" ? "" : "&") . "searchandor1=" . $searchandor1;
if ($searchandor2 != "") $param .= ($param == "" ? "" : "&") . "searchandor2=" . $searchandor2;
if ($searchandor3 != "") $param .= ($param == "" ? "" : "&") . "searchandor3=" . $searchandor3;

if ($searchopt4 != "") $param .= ($param == "" ? "" : "&") . "searchopt4=" . $searchopt4;

//검색 로그 기록
$proc_name = $_POST['proc_name'];
if($proc_name != ""){
	$work_log_seq = WriteAdminActLog($proc_name,'SEARCH');
}

$Model_User=new Model_User();
?>
<script language="javascript">
	$(function() {
		$("#start_date").datepicker(pickerOpts);
		$("#end_date").datepicker(pickerOpts);
	});
</script>
<div id="user_list">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["External_training"][$lang_code];?></span></h1>

			</div>
			<span class="line"></span>
		</div>

		<!--검색폼-->
		<form name="searchForm" action="<?php echo $_SERVER[PHP_SELF] ?>" method="POST">
			<input type="hidden" name="page" value="">
				<input type='hidden' name='proc_name' id='proc_name'>
			<table class="search">
				<tr>
					<th style='widht:100px;'><?= trsLang('일자검색','datesearchtext'); ?> </th>
					<td>
						<!-- 검색 검색폼 -->
					<select name="searchopt4" id="searchopt4">
							<option value="create_date" <?php if($searchopt4 == "create_date" ) { echo 'selected="selected"'; } ?>>
									<?=$_LANG_TEXT['registdatetext'][$lang_code]?>
							</option>
							<option value="train_date" <?php if($searchopt4 == "train_date") { echo 'selected="selected"'; } ?>>
									<?=$_LANG_TEXT['trainingdate'][$lang_code]?>
							</option>
					</select>


						<input type="text" name="start_date" id="start_date" class="frm_input" placeholder="" style="width:100px" value="<?= $start_date ?>" maxlength="10"> ~ 
						<input type="text" name="end_date" id="end_date" class="frm_input" placeholder="" style="width:100px" value="<?= $end_date ?>" maxlength="10">
					</td>
				</tr>
								<?
				//검색키워드목록
				$searchopt_list = array(
					"project_name"=>trsLang("프로젝트명","projectname")
					,"user_company"=>trsLang("업체명","compname")
					,"user_name"=>trsLang("투입인력이름","inputname")
					,"manager_name"=>trsLang("교육담당자이름","trainingmanager")
					,"manager_belong"=>trsLang("교육담당자소속","trainingstaff")
					
				);
				?>
				<tr>
					<th><?= trsLang('키워드검색','keywordsearchtext'); ?> </th>
					<td style='padding:5px 13px'>

								<select name="searchopt" id="searchopt">
							<option value="" <?php if($searchopt == "") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['select_search_item'][$lang_code]?></option>
							<option value="project_name" <?php if($searchopt == "project_name") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['projectname'][$lang_code]?></option>
							<option value="user_company" <?php if($searchopt == "user_company") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['compname'][$lang_code]?></option>
							<option value="user_name" <?php if($searchopt == "user_name") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['inputname'][$lang_code]?></option>
							<option value="manager_name" <?php if($searchopt == "manager_name") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['trainingmanager'][$lang_code]?></option>
							<option value="manager_belong" <?php if($searchopt == "manager_belong") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['trainingstaff'][$lang_code]?></option>
						</select>

						<input type="text" class="frm_input" style="width:50%" name="searchkey" id="searchkey" value="<?= $searchkey ?>" maxlength="50">
						<input type="submit" value="<?= $_LANG_TEXT['usersearchtext'][$lang_code] ?>" class="btn_submit" onclick="return SearchSubmit(document.searchForm);">
						<input type="button" value="<?= $_LANG_TEXT['userdetailsearchtext'][$lang_code] ?>" class="btn_submit_no_icon" onclick="$('#search_detail').toggle()">
								<input type="button" value="<? echo trsLang('초기화','btnclear');?>" class="btn_submit_no_icon" onclick="location.href='<? echo $_www_server?>/user/external_training.php'">
						
						<!--상세검색-->
							<?
								$search_detail_visible = ($searchoptm1&&$searchkeym1 || $searchoptm2&&$searchkeym2 || $searchoptm3&&$searchkeym3);
							?>
						<div id='search_detail' style='<? if($search_detail_visible==false) echo "display:none";?>'>
							<? for($i = 1 ; $i < 4 ; $i++){?>
							<div  style='margin-top:5px;'>
									<select name="searchandor<? echo $i?>" id="searchandor<? echo $i?>" >
										<option value='AND' <? if(${"searchandor".$i}=="AND") echo "selected";?>>AND</option>
										<option value='OR' <? if(${"searchandor".$i}=="OR") echo "selected";?>>OR</option>
									</select>
									<select name="searchoptm<? echo $i ?>" id="searchoptm<? echo $i ?>">
										<option value="" <?php if ($searchoptm == "") {
																				echo ' selected="selected"';
																			} ?>>
											<?= $_LANG_TEXT['select_search_item'][$lang_code] ?></option>
										<?
										foreach($searchopt_list as $key=>$name){
											$selected = (${"searchoptm".$i}==$key) ? "selected" : "";
											echo "<option value='{$key}' {$selected} >{$name}</option>";
										}
										?>
									</select>
								<input style="width:50%" type="text" class="frm_input" name="searchkeym<? echo $i?>" id="searchkeym<? echo $i?>" maxlength="50" value='<? echo ${'searchkeym'.$i}?>'>

							</div>
							<?}?>
						</div>
					</td>
				</tr>
			</table>
						<!--  -->
			<?php 
			// seachpopt
			//검색항목
			$search_sql = "";

			$str_start_date =str_replace('-', '', $start_date);
			$str_end_date =str_replace('-', '', $end_date);
			
			if ($start_date != "" && $end_date != "" ) {

					if ($searchopt4 == "create_date") {
						$search_sql .= " and create_date between '{$str_start_date}000000' AND '{$str_end_date}999999' ";
					} else if ($searchopt4 == "train_date") {
						$search_sql .= " and train_date between '{$str_start_date}' AND '{$str_end_date}' ";
					}
			}

			// 키워드검색
			$searchandor0 = " and ( ";
			$searchoptm0 = $searchopt;
			$searchkeym0 = $searchkey;
			$keyword_search_sql = "";

			for ($i = 0; $i < 4; $i++) {

				$searchopt_i = ${"searchoptm".$i};	
				$searchkey_i = ${"searchkeym".$i};	
				$searchandor_i = ${"searchandor".$i};	

				if (!empty($searchopt_i) && !empty($searchkey_i)) {
					
						$keyword_search_sql .= " $searchandor_i ";


						if ($searchopt_i == "project_name") {
								$keyword_search_sql .= " (project_name like N'%$searchkey_i%') ";
						} else if ($searchopt_i == "user_company") {
								$keyword_search_sql .= " (user_company like N'%$searchkey_i%') ";
						}else if ($searchopt_i == "user_name") {
								$keyword_search_sql .= " (user_name = '".aes_256_enc($searchkey_i)."'  or user_name_en like '$searchkey_i%') ";
						} else if ($searchopt_i == "manager_name") {
								$keyword_search_sql .= " (manager_name = '".aes_256_enc($searchkey_i)."' or manager_name_en like '$searchkey_i%') ";
						} else if ($searchopt_i == "manager_belong") {
								$keyword_search_sql .= " (manager_belong like N'%$searchkey_i%') ";
						} 
				}
			}

			if($keyword_search_sql != ""){
				$search_sql .= $keyword_search_sql.")";
			}

			//echo $search_sql;

				$Model_User->SHOW_DEBUG_SQL = false;
				$args = array("search_sql"=>$search_sql);
	
				$total=$Model_User->getItemTrainDetailsCount($args); 
	
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
					$order_sql = " ORDER BY train_seq DESC ";
		
				}	

				$args = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start);			
				
				$Model_User->SHOW_DEBUG_SQL = false;
				$result = $Model_User->getItemTrainDetailsList($args); 
				
	
				$cnt = 20;
				$iK = 0;
				$classStr = "";
				//excel file name while downloading
		   $excel_name=$_LANG_TEXT['External_training'][$lang_code];		
			?>

		<div class="btn_wrap right" style='margin-bottom:10px;'>
			<? $excel_down_url = $_www_server."/user/external_training_excel.php?enc=".ParamEnCoding($param);?>
			<div class="right">
				<a href="javascript:void(0)" id="external_training_excel" class="btnexcel required-print-auth hide"
						onclick="getHTMLSplit('<?=$total?>','<?=$excel_down_url?>','<?=$excel_name?>',this);"><?=$_LANG_TEXT["btnexceldownload"][$lang_code];?></a>
			</div>
			
			<div style='margin-right:10px; line-height:30px; ' class="right">
				Results : <span style='color:blue'><?=number_format($total)?></span> /
				Records : <select name='paging' onchange="searchForm.submit();">
					<option value='20' <?if($paging=='20' ) echo "selected" ;?>>20</option>
					<option value='40' <?if($paging=='40' ) echo "selected" ;?>>40</option>
					<option value='60' <?if($paging=='60' ) echo "selected" ;?>>60</option>
					<option value='80' <?if($paging=='80' ) echo "selected" ;?>>80</option>
					<option value='100' <?if($paging=='100' ) echo "selected" ;?>>100</option>
				</select>
			</div>

		</div>


		</form>
		

		<!--검색결과리스트-->
		<table class="list" style="margin-top:10px; ">
			<tr>
				<th class="num"><?= $_LANG_TEXT['numtext'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['projectname'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['companyname_text'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['user_name_kr'][$lang_code] ?></th>
				<th style='width:200px'><?= trsLang('영문이름','engnameid'); ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['training_date'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['classify_edu'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['manager_en_id'][$lang_code] ?></th>
				<th style='width:200px'><?= $_LANG_TEXT['education_manager_affiliation'][$lang_code] ?></th>

				<th style='width:200px'><?= $_LANG_TEXT['memotext'][$lang_code] ?></th>

			</tr>
			<?php

									if ($result) {
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
						$cnt--;
						$iK++;

						$train_seq = $row['train_seq'];
						$train_name = $row['train_name'];//교육명
						$project_name = $row['project_name'];
						$user_name = aes_256_dec($row['user_name']);
						$user_name_en = $row['user_name_en'];
						$user_company = $row['user_company'];

						$manager_name = aes_256_dec($row['manager_name']);
						$manager_name_en = $row['manager_name_en'];
						$manager_company = $row['manager_company'];//담당자회사명
						$manager_belong = $row['manager_belong'];//담당자부서
						$manager_type = $row['manager_type'];
						$memo = $row['memo'];

													
						$train_date = $row['train_date'];
					  $formatted_train_date = date('Y-m-d', strtotime($train_date));
	
					$str_memo = $memo;						;


						$param_enc = ParamEnCoding("train_seq=".$train_seq.($param ? "&" : "").$param);

						if($manager_type=="EMP"){
							$manager_type_value=$_LANG_TEXT['out_manager_text'][$lang_code];
						} else if($manager_type=="OUT"){
							$manager_type_value=$_LANG_TEXT['onsite_agent_text'][$lang_code];
					} else {
					$manager_type_value=$manager_type;
					}




			?>

			<tr>

				<td class='center'><?=$no?></td>
				<td class='center'><?=$project_name?></td>
				<td class='center'><?=$user_company?></td>
				<td class='center'>
				<a href="javascript:void(0)" class='text_link' onclick="sendPostForm('<? echo $_www_server?>/user/information_training_details.php?enc=<?= $param_enc ?>')">
				<?=$user_name?></a></td>
				<td class='center'><?=$user_name_en?></td>
				<td class='center'><?=$formatted_train_date?></td>
				<!--  -->
				<td class='center'><?=$manager_type_value?></td>
				<td class='center'><?=$manager_name_en?></td>
				<td class='center'> <?php echo $manager_belong ?> </td>
				<td class='center viewlayer_parent'>
					<span class='text_link required-update-auth' onmouseover="viewlayer(true, 'moverlayerLock_<? echo $no ?>');" onmouseout="viewlayer(false, 'moverlayerLock_<? echo $no ?>');" onclick="appendRow_Memo('<? echo $train_seq;?>','<? echo $seq_val;?>')"><? echo $str_memo=="" ? trsLang('쓰기','btnwrite') : "<li class='fa fa-comments'></li>" ?></span>
					<? if ($str_memo > "") { ?>
					<div id="moverlayerLock_<? echo $no ?>" class="viewlayer left_view" style="display: none;"><? echo $str_memo; ?></div>
					<? } ?>
				</td>

			</tr>

			<?php

					$no--;
				}

			}

			if ($result) sqlsrv_free_stmt($result);
			sqlsrv_close($wvcs_dbcon);
				if($total < 1) {
			?>
			<tr>
				<td colspan="12" align='center'><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
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
	</div>
</div>
<!--메모전송폼-->
<form id='frmMemo' method='post' action=''></form>
<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>