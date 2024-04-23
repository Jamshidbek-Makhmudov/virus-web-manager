<?php
$page_name = "q_editor";
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

 $query_content = $_POST[query_content];	

$searchkey = $_REQUEST[searchkey];	// 검색어
$orderby = $_REQUEST[orderby];		// 정렬순서
$page = $_REQUEST[page];	
$paging = $_REQUEST[paging];		// 페이지
$start_date = $_REQUEST[start_date];
$end_date = $_REQUEST[end_date];

if( $query_content!= ""){
	 $searchkey =  $query_content;
}


  if ($paging == "") $paging = $_paging;

if ($start_date == "") $start_date = date("Y-m-d", strtotime(date("Y-m-d") . " -1 month"));
if ($end_date == "") $end_date = date("Y-m-d");

$param = "";
if ($searchopt != "") $param .= ($param == "" ? "" : "&") . "searchopt=" . $searchopt;

if ($searchkey != "") $param .= ($param == "" ? "" : "&") . "searchkey=" . $searchkey;
if ($orderby != "") $param .= ($param == "" ? "" : "&") . "orderby=" . $orderby;
if ($start_date != "") $param .= ($param == "" ? "" : "&") . "start_date=" . $start_date;
if ($end_date != "") $param .= ($param == "" ? "" : "&") . "end_date=" . $end_date;

//검색 로그 기록
$proc_name = $_POST['proc_name'];
if($proc_name != ""){
	$work_log_seq = WriteAdminActLog($proc_name,'SEARCH');
}

$Model_Utils=new Model_Utils();


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
			<li class="on" onclick="location.href='<? echo $_www_server?>/stat/query_editor.php'"><? echo trsLang('쿼리 검색 결과','query_search_result');?></li>
			<li  onclick="location.href='<? echo $_www_server?>/stat/query_saved.php'"><? echo trsLang('저장된 쿼리','saved_queries');?></li>

		</ul>

		<!--검색폼-->
		<form name="searchForm" action="<?php echo $_SERVER[PHP_SELF] ?>" method="POST">
		<input type='hidden' name='proc_name' id='proc_name'>

			<table  class="search">

				<tr>
					<th style='widht:100px;'><?= $_LANG_TEXT['query_execute'][$lang_code] ?> </th>
					
					<td style='padding:5px 13px'>
						
					<input id="openModalBtn" type="button" value="<?= $_LANG_TEXT['btnsavetext'][$lang_code] ?>" class="btn_submit_save">
					<input type="submit" value="<?= $_LANG_TEXT['execute'][$lang_code] ?>" class="btn_submit" onclick="return SearchSubmitQuery(document.searchForm);">
					<input type="button" value="<? echo trsLang('초기화','btnclear');?>" class="btn_submit_no_icon" onclick="location.href='<? echo $_www_server?>/stat/query_editor.php'">
					
				</td>
			</tr>
			<tr>
					<th style='widht:100px;'><?= $_LANG_TEXT['search_part'][$lang_code] ?> </th>

	
					<td>
						<?php if ($query_content) { ?>
							<textarea class="frm_textarea" style="width:99%" id="searchkey" name="searchkey" rows="4" cols="50"><?php echo  $searchkey; ?></textarea>
							<?php } else { ?>
								<textarea class="frm_textarea" style="width:99%" id="searchkey" name="searchkey" rows="4" cols="50"><?php echo base64_decode( $searchkey); ?></textarea>
								<?php }?>
								
					</td>				
				</tr>
			</table>

			<?php 
			//검색항목
			$search_sql = "";

			if($searchkey != ""){	
		
				  $search_sql .= base64_decode($searchkey);
			}
			 // $Model_Utils->SHOW_DEBUG_SQL = true;
				$args = array("search_sql"=>$search_sql);	
				$total=$Model_Utils->getQueryEditorsListCount($args); 

			?>
					<div style='margin:10px; line-height:30px; ' class="right">
				Results : <span style='color:blue'><?=number_format($total)?></span> /
				Records : <select name='paging' onchange="SearchSubmitQuery(document.searchForm);" >
					<option value='20' <?if($paging=='20' ) echo "selected" ;?>>20</option>
					<option value='100' <?if($paging=='100' ) echo "selected" ;?>>100</option>
					<option value='200' <?if($paging=='200' ) echo "selected" ;?>>200</option>
					<option value='400' <?if($paging=='400' ) echo "selected" ;?>>400</option>
					<option value='600' <?if($paging=='600' ) echo "selected" ;?>>600</option>
					<option value='800' <?if($paging=='800' ) echo "selected" ;?>>800</option>
					<option value='1000' <?if($paging=='1000' ) echo "selected" ;?>>1000</option>
				</select>
			</div>
		</form>

<?php
				$args = array("search_sql"=>$search_sql,"end"=>$paging,"start"=>$start);	
				$result = $Model_Utils->getQueryEditorsList($args);
			$numbers=$_LANG_TEXT['numtext'][$lang_code];

if ($result) {

    $headerPrinted = false;
    
    echo "<table class='list' style='margin-top:10px;display: block;
    overflow-x: auto;
    white-space: nowrap; '>";
   echo '<tbody style="display: table;
    width: 100%;" >';
    
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$no++;

        if (!$headerPrinted) {
            echo '<thead><tr>';
						echo "<th style='width:200px'>" . $numbers . "</th>";
            foreach ($row as $columnName => $value) {
                echo '<th style="width:200px">' . $columnName . '</th>';
            }
            echo '</tr></thead>';
            $headerPrinted = true;
        }
        
        echo '<tr>';
				echo "<td>" . $no . "</td>";
        foreach ($row as $value) {
					 if ($value instanceof DateTime) {
						  echo '<td>' . $value->format('Y-m-d H:i:s') . '</td>';
			} else {
			
				echo '<td>' . $value . '</td>';
			}

        }
        echo '</tr>';
    }
    
		
			if ($result) sqlsrv_free_stmt($result);
			sqlsrv_close($wvcs_dbcon);
			
    echo '</tbody>';
    echo '</table>';
		
}

?>

<div id="modal" class="modal">
  <div class="modal-content">
		<div class="" style="display:flex; align-items: center; justify-content:space-between; width:100%">
			
			<strong class="modal-title"><?php echo $_LANG_TEXT["query_content_ask_text"][$lang_code]; ?> </strong>
			<span class="close">&times;</span> 
		</div>
    <form id="frmSaveQuery" name="frmSaveQuery"  method="POST">
      <div class="form-group">

        <input style="width:95%" class="frm_input" type="text" id="query_title" name="query_title" >
      </div>
			<input type="hidden" id="searchkey" name="searchkey" value="<?php echo base64_decode( $searchkey); ?>">

			<input type='hidden' id='proc' name='proc'>

      <input style="margin-right:5px;"  type="submit" value="<?= $_LANG_TEXT['btnsavetext'][$lang_code] ?>" class=" right btn_submit_save">

    </form>
  </div>

   </div>
	</div>


</div>
<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>